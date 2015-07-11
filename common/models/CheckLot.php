<?php

namespace common\models;


use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Rate;
use common\models\RateWinner;
use common\models\Lot;
use common\models\CronInfo;
use common\models\LotRateStatistic;


class CheckLot
{
	
    
    public function checkAndUpdate()
    {
    	// делать проверку и отправку только через cron
    	//$this->toLoser(3);
    	
    	//  проверка на отправку email на отзыв
    	$this->checkComment();
    	
    	$this->endsInMinutes();
    	//проверка и запись новых лотов в победители
    	$this->checkAndWriteNewWinner();

		//перебили, выиграл, проиграл, лот заканчивается через столько-то минут
		
		//запись времени оплаты и проверка 
		$this->checkPay();
		
		//рассылка по интиресам
	}
	
	//
	
	// отправка писем после n дней пользователю на комменты
	public function checkComment()
	{
		$currentTime = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss');
		$rateWinners =  RateWinner::find()->where(['<',  'send_email_time', $currentTime])->all();

		if($rateWinners)
		{
			foreach($rateWinners as $rateWinner)
			{
				$rate = Rate::findone($rateWinner->rate_id);
				if($rate)
				{
					$url = Url::to(['comment/index', 'id'=>$rateWinner->comment]);
					Yii::$app->params['emailText']['emailToComment']['email'] = $rate->user['email'];
					Yii::$app->params['emailText']['emailToComment']['messege'] = sprintf(Yii::$app->params['emailText']['emailToComment']['messege'], $rate->user['name'], $url);
					$this->sendEmail(Yii::$app->params['emailText']['emailToComment']);
				}
				$secondStepTimeToComment = Yii::$app->params['lot.secondStepTimeToComment'];
				if($secondStepTimeToComment)
				{
					$rateWinner->send_email_time = date('Y-m-d H:i:s', strtotime("+$secondStepTimeToComment minutes", strtotime($currentTime))); 
				}
				else
				{
					$rateWinner->send_email_time = '';
				}
				$rateWinner->save();
			}
		}
		return TRUE;
		
		//$cronInfo->time = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss');
		//$cronInfo->save();
	}
	
	//лот заканчивается через столько-то минут
	public function endsInMinutes()
	{
		$timeToFinish = Yii::$app->params['lot.timeToFinish'];
		$cronTime = Yii::$app->params['cronTime'];
		$currentTime = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss');
		
		$cronInfo = CronInfo::findone(['type' => 'update_endsInMinutes']);
		$cronInfo->time =  $currentTime;
		$cronInfo->save();
		
		$more =  date('Y-m-d H:i:s', strtotime("+$timeToFinish minutes", strtotime($currentTime))); 
		$less =  date('Y-m-d H:i:s', strtotime("+$cronTime minutes", strtotime($currentTime))); 
		
		$lots = Lot::find()
    		->where(['<',  'remaining_time', $more])
    		->andWhere(['>',  'remaining_time', $less])
    		->all(); 
    		
		if($lots)
		{
			foreach($lots as $lot)
			{
				$lot_time = floor((strtotime($lot->remaining_time) - strtotime($currentTime))/60);
				
				$lotRateStatistic = LotRateStatistic::find()->where(['lot_id'=>$lot->id])->orderBy('id desc')->one();
				if($lotRateStatistic){
					$rates = Rate::find()->where(['lot_id'=>$lot->id])->andWhere(['refusal'=>0])->andWhere(['>',  'id', $lotRateStatistic->last_rate])->offset(1)->orderBy('price desc')->all();
				}
				else
				{
					$rates = Rate::find()->where(['lot_id'=>$lot->id])->andWhere(['refusal'=>0])->offset(1)->orderBy('price desc')->all();
				}
				if($rates)
				{
					foreach($rates as $rate)
					{
						$url = Url::to(['lot/view', 'slug'=>$rate->lot['slug']]);
						Yii::$app->params['emailText']['endsInMinutes']['email'] =  $rate->user['email'];
						Yii::$app->params['emailText']['endsInMinutes']['messege'] = sprintf(Yii::$app->params['emailText']['endsInMinutes']['messege'],  $rate->user['name'], $lot_time." м", $url); // имя, время, урл на лот
						$this->sendEmail(Yii::$app->params['emailText']['endsInMinutes']);
					}		
				}
			}	
		}
		return true;
		
	}
	
	//проверка и запись новых лотов в победители
	public function checkAndWriteNewWinner()
	{
		$lots = $this->findeLotOnStepCron();
    	if($lots)
    	{
			foreach($lots as $lot)
			{
				$rate_id = $this->rateStatistic($lot->id);
				if($rate_id)
				{
					$this->toWinnerRate($rate_id, 0);
				}
			}
		}
		return TRUE;
	}
	
	// поиск всех отыграных лотов за последний проход cron
	public function findeLotOnStepCron()
	{
		$cronInfo = CronInfo::findone(['type' => 'update_rate']);
		$lots = Lot::find()
    		->where(['<',  'remaining_time', Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss')])
    		->andWhere(['>',  'remaining_time', $cronInfo['time']])
    		->all(); 
    		
    	$cronInfo->time = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss');
		$cronInfo->save();
    	return $lots;
	}
	
	// запись в таблицу статистики 
	public function rateStatistic($id)
	{
		
		/*$lotRateStatistic = LotRateStatistic::find()->where(['lot_id'=>$modelId->id])->orderBy('id desc')->one();
		
    	if($lotRateStatistic){
			if($lotRateStatistic->status)
			{
				//var_dump($lotRateStatistic->last_rate);
				return Rate::find()->where(['lot_id'=>$modelId->id])->andWhere(['refusal'=>0])->andWhere(['>',  'id', $lotRateStatistic->last_rate])->orderBy('price desc')->all();
			}
		}
		return Rate::find()->where(['lot_id'=>$modelId->id])->andWhere(['refusal'=>0])->orderBy('price desc')->all();
		*/
		$lotRateStatistic = LotRateStatistic::find()->where(['lot_id'=>$id])->orderBy('id desc')->one();
		
		/*var_dump('lot statistic <br>');
		var_dump($lotRateStatistic);
		var_dump('lot statistic end <br>');*/
		
		
		
		
    	if($lotRateStatistic){
    		//var_dump('lot statistic pre if <br>');
			if($lotRateStatistic->status == 0)
			{
				//var_dump('lot statistic if<br>');
				//var_dump($lotRateStatistic->last_rate);
				$rate = Rate::find()->where(['lot_id'=>$id])->orderBy('price desc')->one();
				if($rate)
				{
					$lotRateStatistic = new LotRateStatistic();
			    	$lotRateStatistic->lot_id = $id;
			    	$lotRateStatistic->last_rate = $rate['id'];
			    	$lotRateStatistic->status = 0;
			    	$lotRateStatistic->save();
			    	return $rate->id;
				}
				else
				{
					return false;
				}
				
			}
		}
		
		$rate = Rate::find()->where(['lot_id'=>$id])->orderBy('id desc')->one();
		
		if($rate)
		{
			$lotRateStatistic = new LotRateStatistic();
	    	$lotRateStatistic->lot_id = $id;
	    	$lotRateStatistic->last_rate = $rate['id'];
	    	$lotRateStatistic->status = 0;
	    	$lotRateStatistic->save();
	    	return $rate->id;
		}
		else
		{
			return false;
		}
		
	/*	$rate = Rate::find()->where(['lot_id'=>$id])->orderBy('price desc')->one();
		
		if($rate)
		{
			$lotRateStatistic = new LotRateStatistic();
	    	$lotRateStatistic->lot_id = $id;
	    	$lotRateStatistic->last_rate = $rate['id'];
	    	$lotRateStatistic->status = 0;
	    	$lotRateStatistic->save();
	    	return $rate->id;
		}
		else
		{
			return false;
		}*/
		
	}

	// отправка писем все проигравшим (нужно только через  cron)
	public function toLoser($lot_id)
	{
		$lotRateStatistic = LotRateStatistic::find()->where(['lot_id'=>$lot_id])->orderBy('id desc')->one();
		
		if($lotRateStatistic){
			$rates = Rate::find()->where(['lot_id'=>$lot_id])->andWhere(['refusal'=>0])->andWhere(['>',  'id', $lotRateStatistic->last_rate])->offset(1)->orderBy('price desc')->all();
			$winner = Rate::find()->where(['lot_id'=>$lot_id])->andWhere(['refusal'=>0])->andWhere(['>',  'id', $lotRateStatistic->last_rate])->orderBy('price desc')->one();
		}
		else
		{
			$rates = Rate::find()->where(['lot_id'=>$lot_id])->andWhere(['refusal'=>0])->offset(1)->orderBy('price desc')->all();
			$winner = Rate::find()->where(['lot_id'=>$lot_id])->andWhere(['refusal'=>0])->orderBy('price desc')->one();
		}
		if($rates)
		{
			foreach($rates as $rate)
			{
				$url = Url::to(['']);
				$newEmail = Yii::$app->params['emailText']['toLoser'];
				$newEmail['email'] = $rate->user['email'];
				$newEmail['messege'] = sprintf(Yii::$app->params['emailText']['toLoser']['messege'], $rate->user['name'], $rate->lot['name'], $winner->price, $winner->user['name'], $url);
				$this->sendEmail($newEmail);
			}		
		}
	}
	
	// запись в победители  ---- добавить отправку на почту
	public function toWinnerRate($rate_id, $numberUser = 0)
	{
		$currentTime  = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss');
		$firstStepTimeToComment = Yii::$app->params['lot.firstStepTimeToComment'];
		
		$rate_info = [
			'email'=>[
				'first' => '1',
				'second' => '',
				'third' => '',
				'fours' => '',
			],
			'user' => $numberUser,
		];
		
		$timeStep = date('Y-m-d H:i:s', strtotime("+$firstStepTimeToComment days", strtotime($currentTime)));
		
		$rateWinner = new RateWinner();
		$rateWinner->pay = '';
		$rateWinner->rate_id = $rate_id;
		$rateWinner->comment = md5($rate_id.$currentTime);
		$rateWinner->rate_info =  json_encode($rate_info);
		$rateWinner->status  = 0;
		$rateWinner->winner_time = $currentTime;
		$rateWinner->pay_time = '';
		$rateWinner->send_email_time = $timeStep;
		$rateWinner->save();
		//return TRUE;
		
		$rate = Rate::findone($rate_id);
		
		// получить данные пользователя и сформировать емайл
		$url = Url::to(['pay/index', 'id'=>$rate_id]);
		Yii::$app->params['emailText']['toWinner']['email'] = $rate->user['email'];
		Yii::$app->params['emailText']['toWinner']['messege'] = sprintf(Yii::$app->params['emailText']['toWinner']['messege'], $rate->user['name'], $rate->lot['name'], $rate->price, Yii::$app->formatter->asDatetime($timeStep, 'dd.MM hh:mm'), $url);
		return $this->sendEmail(Yii::$app->params['emailText']['toWinner']);
		
		
    	//return $this->sendEmail(Yii::$app->params['emailText']['toWinner']);
	}
	
	// изминение в победители  ---- добавить отправку на почту
	public function chengeWinnerRate($rateWinner_id, $rate_id, $numberUser = 0)
	{
		$currentTime  = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss');
		$firstStepTimeToComment = Yii::$app->params['lot.firstStepTimeToComment'];
		//date('Y-m-d H:i:s', strtotime("+$stepTimeToComment days", strtotime($currentTime)))
		
		$rate_info = [
			'email'=>[
				'first' => '1',
				'second' => '',
				'third' => '',
				'fours' => '',
			],
			'user' => $numberUser,
		];
		
		$rateWinner = RateWinner::findone($rateWinner_id);
		$rateWinner->pay = '';
		$rateWinner->rate_id = $rate_id;
		$rateWinner->comment = md5($rate_id.$currentTime);
		$rateWinner->rate_info =  json_encode($rate_info);
		$rateWinner->status  = 0;
		$rateWinner->winner_time = $currentTime;
		$rateWinner->pay_time = '';
		$rateWinner->send_email_time = date('Y-m-d H:i:s', strtotime("+$firstStepTimeToComment days", strtotime($currentTime)));
		$rateWinner->save();
		//return TRUE;
		
		$rate = Rate::findone($rate_id);
		
		// получить данные пользователя и сформировать емайл
		$url = Url::to(['pay/index', 'id'=>$rate_id]);
		Yii::$app->params['emailText']['toWinner']['email'] = $rate->user['email'];
		Yii::$app->params['emailText']['toWinner']['messege'] = sprintf(Yii::$app->params['emailText']['toWinner']['messege'], $rate->user['name'], $rate->lot['name'], $rate->price, Yii::$app->formatter->asDatetime($rate->lot['remaining_time'], 'dd.MM hh:mm'), $url);
		return $this->sendEmail(Yii::$app->params['emailText']['toWinner']);
	}
	
	// проверка оплачено ли
	public function checkPay()
	{
		$rateWinners = RateWinner::find()->where(['status'=>0])->all();
		if($rateWinners){
			$currentTime  = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss');
			$cronInfo = CronInfo::findone(['type' => 'update_pay']);
			
			$cronInfo->time = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss');
			$cronInfo->save();
			
			foreach($rateWinners as $rateWinner){
				$rate_info = json_decode($rateWinner->rate_info);
				$time = $rateWinner->winner_time;

				if(  ($rate_info->email->second == '') and ($cronInfo->time > date('Y-m-d H:i:s', strtotime("+4 hours", strtotime($time)))) )
				{
					//послать емайл 8 часов
					$rate_info->email->second = '1';
					//$this->sendEmail(Yii::$app->params['emailText']['toPay']['first']);
					
					$rate = Rate::findone($rateWinner->rate_id);
					$url = Url::to(['pay/index', 'id'=>$rate->id]);
					Yii::$app->params['emailText']['toPay']['first']['email'] = $rate->user['email'];
					Yii::$app->params['emailText']['toPay']['first']['messege'] = sprintf(Yii::$app->params['emailText']['toPay']['first']['messege'], $rate->user['name'],Yii::$app->formatter->asDatetime($rateWinner->send_email_time, 'dd.MM hh:mm') , $rate->lot['name'], $url);
					$this->sendEmail(Yii::$app->params['emailText']['toPay']['first']);
					
				}elseif(  ($rate_info->email->third == '') and ($cronInfo->time > date('Y-m-d H:i:s', strtotime("+8 hours", strtotime($time)))) )
				{
					//послать емайл 4 часов
					$rate_info->email->third = '1';
					//$this->sendEmail(Yii::$app->params['emailText']['toPay']['second']);
					$rate = Rate::findone($rateWinner->rate_id);
					$url = Url::to(['pay/index', 'id'=>$rate->id]);
					Yii::$app->params['emailText']['toPay']['second']['email'] = $rate->user['email'];
					Yii::$app->params['emailText']['toPay']['second']['messege'] = sprintf(Yii::$app->params['emailText']['toPay']['second']['messege'], $rate->user['name'],Yii::$app->formatter->asDatetime($rateWinner->send_email_time, 'dd.MM hh:mm') , $rate->lot['name'], $url);
					$this->sendEmail(Yii::$app->params['emailText']['toPay']['second']);
				}elseif(  ($rate_info->email->fours == '') and ($cronInfo->time> date('Y-m-d H:i:s', strtotime("+11 hours", strtotime($time)))) )
				{
					//послать емайл 1 часов
					$rate_info->email->fours = '1';
					//$this->sendEmail(Yii::$app->params['emailText']['toPay']['third']);
					$rate = Rate::findone($rateWinner->rate_id);
					$url = Url::to(['pay/index', 'id'=>$rate->id]);
					Yii::$app->params['emailText']['toPay']['third']['email'] = $rate->user['email'];
					Yii::$app->params['emailText']['toPay']['third']['messege'] = sprintf(Yii::$app->params['emailText']['toPay']['third']['messege'], $rate->user['name'],Yii::$app->formatter->asDatetime($rateWinner->send_email_time, 'dd.MM hh:mm') , $rate->lot['name'], $url);
					$this->sendEmail(Yii::$app->params['emailText']['toPay']['third']);
				}elseif($cronInfo->time > date('Y-m-d H:i:s', strtotime("+12 hours", strtotime($time))) )
				{
					
					$rate = Rate::findone($rateWinner->rate_id);
					$url = Url::to(['pay/index', 'id'=>$rate->id]);
					Yii::$app->params['emailText']['toPay']['close']['email'] = $rate->user['email'];
					Yii::$app->params['emailText']['toPay']['close']['subject'] = sprintf(Yii::$app->params['emailText']['toPay']['close']['subject'], $rate->lot['name']);
					Yii::$app->params['emailText']['toPay']['close']['messege'] = sprintf(Yii::$app->params['emailText']['toPay']['close']['messege'], $rate->user['name'], $rate->lot['name']);
					$this->sendEmail(Yii::$app->params['emailText']['toPay']['close']);
					
					//новый пользователь
					$numberUser =  $rate_info->user+1; 
					$rate_id = $this->getNextRate($rateWinner->rate_id, $numberUser);
					if($rate_id){
						$this->chengeRateRefusal($rateWinner->rate_id);
						$this->chengeWinnerRate($rateWinner->id, $rate_id, $numberUser);
					}
					else
					{
						$rateWinner->status = 2;
					}
					
					
					
				}
				$rateWinner->rate_info =  json_encode($rate_info);
				$rateWinner->save();
			}
		}
	}
	
	// изминение статуса refusal
	public function chengeRateRefusal($rate_id)
	{
		$rate = Rate::findone($rate_id);
		if($rate)
		{
			$rate->refusal = 1;
			$rate->save();
			return TRUE;
		}
		else
		{
			return false;
		}
		
	}
	
	// возращает предидущий id 
	public function getNextRate($lastRate, $offset = 1)
	{
		$rateLast = Rate::findone($lastRate);
		//var_dump($rateLast);
		//die;
		$rate = Rate::find()->where(['lot_id'=>$rateLast->lot_id])->orderBy('price desc')->offset($offset)->one();
		if($rate){
			return $rate->id;
		}
		else{
			return false;
		}
		
		
	}
	
	//отправка сообщения пользователю
	public function sendEmail($mail)
	{
		/*$mail = [
			'view' => 'first-html',
			'emailTo' => 'developer.awam@gmail.com',
			'messege' => 'Привет мир',
			'subject' => 'subject текст',
		];*/
		//return true;
		return  Yii::$app->mailer->compose( $mail['view'], ['mail' => $mail])
		    ->setFrom(Yii::$app->params['emailText'])
		    ->setTo($mail['email'])
		    ->setSubject($mail['subject'])
		    ->send();	  
	}

}


//$stepTimeToComment = Yii::$app->params['lot.stepTimeToComment'];
// $rateWinner->send_email_time = date('Y-m-d H:i:s', strtotime("+$stepTimeToComment days", strtotime($currentTime)))



