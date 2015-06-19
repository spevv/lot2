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
    	
    	$this->endsInMinutes();
    	//проверка и запись новых лотов в победители
    	$this->checkAndWriteNewWinner();

		//перебили, выиграл, проиграл, лот заканчивается через столько-то минут
		
		//запись времени оплаты и проверка 
		$this->checkPay();
		
		//рассылка по интиресам
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
						Yii::$app->params['emailText']['endsInMinutes']['email'] = $rate->user->email;
						Yii::$app->params['emailText']['endsInMinutes']['messege'] = sprintf(Yii::$app->params['emailText']['endsInMinutes']['messege'], $lot_time);
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

	// отправка писем все проигравшим (нужно только через  cron)
	public function toLoser($lot_id)
	{
		$lotRateStatistic = LotRateStatistic::find()->where(['lot_id'=>$lot_id])->orderBy('id desc')->one();
		
		if($lotRateStatistic){
			$rates = Rate::find()->where(['lot_id'=>$lot_id])->andWhere(['refusal'=>0])->andWhere(['>',  'id', $lotRateStatistic->last_rate])->offset(1)->orderBy('price desc')->all();
		}
		else
		{
			$rates = Rate::find()->where(['lot_id'=>$lot_id])->andWhere(['refusal'=>0])->offset(1)->orderBy('price desc')->all();
		}
		if($rates)
		{
			foreach($rates as $rate)
			{
				Yii::$app->params['emailText']['toLoser']['email'] = $rate->user->email;
				Yii::$app->params['emailText']['toLoser']['messege'] = sprintf(Yii::$app->params['emailText']['toLoser']['messege'], $rate->user->name);
				$this->sendEmail(Yii::$app->params['emailText']['toLoser']);
			}		
		}
	}
	
	// запись в победители  ---- добавить отправку на почту
	public function toWinnerRate($rate_id, $numberUser = 0)
	{
		$currentTime  = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss');
		
		$rate_info = [
			'email'=>[
				'first' => '1',
				'second' => '',
				'third' => '',
				'fours' => '',
			],
			'user' => $numberUser,
		];
		
		$rateWinner = new RateWinner();
		$rateWinner->pay = '';
		$rateWinner->rate_id = $rate_id;
		$rateWinner->comment = md5($rate_id.$currentTime);
		$rateWinner->rate_info =  json_encode($rate_info);
		$rateWinner->status  = 0;
		$rateWinner->winner_time = $currentTime;
		$rateWinner->pay_time = '';
		$rateWinner->save();
		//return TRUE;
		
		// получить данные пользователя и сформировать емайл
    	return $this->sendEmail(Yii::$app->params['emailText']['toWinner']);
	}
	
	// изминение в победители  ---- добавить отправку на почту
	public function chengeWinnerRate($rateWinner_id, $rate_id, $numberUser = 0)
	{
		$currentTime  = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss');
		
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
		$rateWinner->save();
		//return TRUE;
		
		// получить данные пользователя и сформировать емайл
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
					$this->sendEmail(Yii::$app->params['emailText']['toPay']['first']);
				}elseif(  ($rate_info->email->third == '') and ($cronInfo->time > date('Y-m-d H:i:s', strtotime("+8 hours", strtotime($time)))) )
				{
					//послать емайл 4 часов
					$rate_info->email->third = '1';
					$this->sendEmail(Yii::$app->params['emailText']['toPay']['second']);
				}elseif(  ($rate_info->email->fours == '') and ($cronInfo->time> date('Y-m-d H:i:s', strtotime("+11 hours", strtotime($time)))) )
				{
					//послать емайл 1 часов
					$rate_info->email->fours = '1';
					$this->sendEmail(Yii::$app->params['emailText']['toPay']['third']);
				}elseif($cronInfo->time > date('Y-m-d H:i:s', strtotime("+12 hours", strtotime($time))) )
				{
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
		return true;
		/*return  Yii::$app->mailer->compose( $mail['view'], ['mail' => $mail])
		    ->setFrom('spevv@mail.ru')
		    ->setTo($mail['email'])
		    ->setSubject($mail['subject'])
		    ->send();	  */
	}

}




