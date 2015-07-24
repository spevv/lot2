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
	
    /**
	* функция запуска со стороны пользователя
	* 
	* @return
	*/
    public function start()
    {
    	//проверка и запись новых лотов в победители
    	$this->checkAndWriteNewWinner();
    	//  проверка на отправку email на отзыв
    	$this->checkComment();
		//запись времени оплаты и проверка 
		$this->checkPay();	
	}
	
	/**
	* start, функция запуска через крон
	* 
	* @return
	*/
	public function startCron()
	{
		$this->start();
		// делать проверку и отправку только через cron
    	//$this->toLoser(3);
		$this->getIdLotsToLoser();	
		// проверка на отправку "осталось несколько минут"
    	$this->endsInMinutes();
	}
	
	/**
	* отправка писем после n дней пользователю на комменты
	* 
	* @return TRUE
	*/
	public function checkComment()
	{
		$currentTime = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss');
		//$rateWinners =  RateWinner::find()->where(['<',  'send_email_time', $currentTime])->all();
		$rateWinners =  RateWinner::find()->where(['<',  'send_email_time', $currentTime])->andWhere(['pay'=>NULL])->all();
		//var_dump($rateWinners);
		if($rateWinners)
		{
			foreach($rateWinners as $rateWinner)
			{
				$rate = Rate::findone($rateWinner->rate_id);
				if($rate)
				{
					$url = Yii::$app->urlManager->createAbsoluteUrl(['comment/index', 'id'=>$rateWinner->comment]);
					$email = Yii::$app->params['emailText']['emailToComment'];
					$email['email'] = $rate->user['email'];
					$email['messege'] = sprintf($email['messege'], $rate->user['name'], $url);
					$this->sendEmail($email);
				}
				$secondStepTimeToComment = Yii::$app->params['lot.secondStepTimeToComment'];
				//var_dump($secondStepTimeToComment);
				if($secondStepTimeToComment)
				{
					$rateWinner->send_email_time = date('Y-m-d H:i:s', strtotime("+$secondStepTimeToComment days", strtotime($currentTime))); 
				}
				else
				{
					$rateWinner->send_email_time = '';
				}
				$rateWinner->save();
			}
		}
		return TRUE;
	}
	
	/**
	* лот заканчивается через столько-то минут
	* 
	* @return true
	*/
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
				//Yii::info('lot_time - '.$lot_time, 'mainCron');
				//Yii::info('remaining_time - '.$lot->remaining_time, 'mainCron');
				$lotRateStatistic = LotRateStatistic::find()->where(['lot_id'=>$lot->id])->orderBy('id desc')->one();
				if($lotRateStatistic){
					//$rates = Rate::find()->where(['lot_id'=>$lot->id])->andWhere(['refusal'=>0])->andWhere(['>',  'id', $lotRateStatistic->last_rate])->offset(1)->orderBy('price desc')->all();
					$rates = Rate::find()->where(['lot_id'=>$lot->id])->andWhere(['refusal'=>0])->andWhere(['>',  'id', $lotRateStatistic->last_rate])->orderBy('id desc')->all();
				}
				else
				{
					//$rates = Rate::find()->where(['lot_id'=>$lot->id])->andWhere(['refusal'=>0])->offset(1)->orderBy('price desc')->all();
					$rates = Rate::find()->where(['lot_id'=>$lot->id])->andWhere(['refusal'=>0])->orderBy('id desc')->all();
				}
				if($rates)
				{
					$emailArray = [];
					foreach($rates as $rate)
					{
						//Yii::info('in fore - ', 'mainCron');
						if (!array_key_exists($rate->user['email'], $emailArray)) 
						{
							//Yii::info('in if - ', 'mainCron');
						    $url = Yii::$app->urlManager->createAbsoluteUrl(['lot/view', 'slug'=>$rate->lot['slug']]);
						    $email = Yii::$app->params['emailText']['endsInMinutes'];
							$email['email'] =  $rate->user['email'];
							$email['messege'] = sprintf($email['messege'],  $rate->user['name'], $lot_time." мин", $url);
							$this->sendEmail($email);
						}
						$emailArray[$rate->user['email']] = $rate->user['name'];
					}		
					unset($emailArray);
				}
			}	
		}
		return true;
		
	}
	
	/**
	* проверка и запись новых лотов в победители
	* 
	* @return TRUE
	*/
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
	
	/**
	* поиск всех отыграных лотов за последний проход cron
	* 
	* @return obj Lots
	*/
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
	
	/**
	* поиск всех отыграных лотов за последний проход cron
	* 
	* @return obj Lots
	*/
	public function findeLotOnStepCronToLoser()
	{
		$cronInfo = CronInfo::findone(['type' => 'update_email_loser']);
		$lots = Lot::find()
    		->where(['<',  'remaining_time', Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss')])
    		->andWhere(['>',  'remaining_time', $cronInfo['time']])
    		->all(); 
    		
    	$cronInfo->time = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss');
		$cronInfo->save();
    	return $lots;
	}
	
	/**
	*  запись в таблицу статистики 
	* 
	* @param lot_id $id
	* 
	* @return $rate->id
	*/
	public function rateStatistic($id)
	{
		$lotRateStatistic = LotRateStatistic::find()->where(['lot_id'=>$id])->orderBy('id desc')->one();
    	if($lotRateStatistic)
    	{
			if($lotRateStatistic->status == 0)
			{
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
	}

	/**
	* получение id lot что бы отправить проигравшим
	* 
	* @return TRUE
	*/
	public function getIdLotsToLoser()
	{
		$lots = $this->findeLotOnStepCronToLoser();
    	if($lots)
    	{
			foreach($lots as $lot)
			{
				$this->toLoser($lot->id);
			}
		}
		return TRUE;
	}
	
	/**
	* отправка писем все проигравшим (нужно только через  cron)
	* 
	* @param int $lot_id
	* 
	* @return
	*/
	public function toLoser($lot_id)
	{
		$lotRateStatistic = LotRateStatistic::find()->where(['lot_id'=>$lot_id])->orderBy('id desc')->one();
		
		if($lotRateStatistic){
			$rates = Rate::find()->where(['lot_id'=>$lot_id])->andWhere(['refusal'=>0])->andWhere(['>',  'id', $lotRateStatistic->last_rate])->offset(1)->orderBy('id desc')->all();
			$winner = Rate::find()->where(['lot_id'=>$lot_id])->andWhere(['refusal'=>0])->andWhere(['>',  'id', $lotRateStatistic->last_rate])->orderBy('id desc')->one();
		}
		else
		{
			$rates = Rate::find()->where(['lot_id'=>$lot_id])->andWhere(['refusal'=>0])->offset(1)->orderBy('id desc')->all();
			$winner = Rate::find()->where(['lot_id'=>$lot_id])->andWhere(['refusal'=>0])->orderBy('id desc')->one();
		}
		if($rates)
		{
			foreach($rates as $rate)
			{
				$url = Yii::$app->urlManager->createAbsoluteUrl(['']);
				$newEmail = Yii::$app->params['emailText']['toLoser'];
				$newEmail['email'] = $rate->user['email'];
				$newEmail['messege'] = sprintf($newEmail['messege'], $rate->user['name'], $rate->lot['name'], $winner->price, $winner->user['name'], $url);
				$this->sendEmail($newEmail);
			}		
		}
		return TRUE;
	}
	
	/**
	* запись в победители 
	* 
	* @param int $rate_id
	* @param int $numberUser
	* 
	* @return
	*/
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
		$timePay = Yii::$app->formatter->asDatetime(date('Y-m-d H:i:s', strtotime("+12 hours", strtotime($rateWinner->winner_time))), 'dd.MM HH:mm');
		
		$rate = Rate::findone($rate_id);
		
		// получить данные пользователя и сформировать емайл
		$url = Yii::$app->urlManager->createAbsoluteUrl(['pay/index', 'id'=>$rate_id]);
		Yii::$app->params['emailText']['toWinner']['email'] = $rate->user['email'];
		Yii::$app->params['emailText']['toWinner']['messege'] = sprintf(Yii::$app->params['emailText']['toWinner']['messege'], $rate->user['name'], $rate->lot['name'], $rate->price, $timePay, $url);
		//echo(Yii::$app->params['emailText']['toWinner']['messege'] );
		return $this->sendEmail(Yii::$app->params['emailText']['toWinner']);
		
		
    	//return $this->sendEmail(Yii::$app->params['emailText']['toWinner']);
	}
	
	/**
	* изминение в победители  
	* 
	* @param int $rateWinner_id
	* @param int $rate_id
	* @param int $numberUser
	* 
	* @return true or false
	*/
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
		$url = Yii::$app->urlManager->createAbsoluteUrl(['pay/index', 'id'=>$rate_id]);
		$email = Yii::$app->params['emailText']['toWinner'];
		$email['email'] = $rate->user['email'];
		$email['messege'] = sprintf($email['messege'], $rate->user['name'], $rate->lot['name'], $rate->price, Yii::$app->formatter->asDatetime($rate->lot['remaining_time'], 'dd.MM HH:mm'), $url);
		return $this->sendEmail($email);
	}
	
	/**
	* проверка оплачено ли
	* 
	* @return
	*/
	public function checkPay()
	{
		$rateWinners = RateWinner::find()->where(['status'=>0])->all();
		if($rateWinners)
		{
			$currentTime  = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss');
			$cronInfo = CronInfo::findone(['type' => 'update_pay']);
			
			$cronInfo->time = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss');
			$cronInfo->save();
			
			foreach($rateWinners as $rateWinner){
				$rate_info = json_decode($rateWinner->rate_info);
				$time = $rateWinner->winner_time;
				$timePay = Yii::$app->formatter->asDatetime(date('Y-m-d H:i:s', strtotime("+12 hours", strtotime($rateWinner->winner_time))), 'dd.MM HH:mm');
				$rate = Rate::findone($rateWinner->rate_id);
				$url = Yii::$app->urlManager->createAbsoluteUrl(['pay/index', 'id'=>$rate->id]);
				//Yii::info('timePay - '.$timePay, 'mainCron');
				//Yii::info('time - '.$time, 'mainCron');
				//Yii::info('cronInfo->time - '.$cronInfo->time, 'mainCron');
				//if(  ($rate_info->email->second == '') and ($cronInfo->time > date('Y-m-d H:i:s', strtotime("+4 hours", strtotime($time)) ) ) ) //+4
				if(  ($rate_info->email->second == '') and ($cronInfo->time > date('Y-m-d H:i:s', strtotime("+5 minutes", strtotime($time)) ) ) ) //+4
				{
					
					//Yii::info('+4 hours - '.strtotime("+4 hours", strtotime($time)), 'mainCron');
					//послать емайл 8 часов
					$rate_info->email->second = '1';
					$email = Yii::$app->params['emailText']['toPay']['first'];
					$email['email'] = $rate->user['email'];
					$email['messege'] = sprintf($email['messege'], $rate->user['name'], $timePay, $rate->lot['name'], $url);
					$this->sendEmail($email);
					
				//}elseif(  ($rate_info->email->third == '') and ($cronInfo->time > date('Y-m-d H:i:s', strtotime("+8 hours", strtotime($time)))) ) //+8
				}elseif(  ($rate_info->email->third == '') and ($cronInfo->time > date('Y-m-d H:i:s', strtotime("+10 minutes", strtotime($time)))) ) //+8
				{
					//Yii::info('+8 hours - '.strtotime("+8 hours", strtotime($time)), 'mainCron'); 
					//послать емайл 4 часов
					$rate_info->email->third = '1';
					//$this->sendEmail(Yii::$app->params['emailText']['toPay']['second']);
					//$rate = Rate::findone($rateWinner->rate_id);
					//$url = Yii::$app->urlManager->createAbsoluteUrl(['pay/index', 'id'=>$rate->id]);
					$email = Yii::$app->params['emailText']['toPay']['second'];
					$email['email'] = $rate->user['email'];
					$email['messege'] = sprintf($email['messege'], $rate->user['name'], $timePay, $rate->lot['name'], $url);
					$this->sendEmail($email);
				//}elseif(  ($rate_info->email->fours == '') and ($cronInfo->time> date('Y-m-d H:i:s', strtotime("+11 hours", strtotime($time)))) ) //+11
				}elseif(  ($rate_info->email->fours == '') and ($cronInfo->time > date('Y-m-d H:i:s', strtotime("+15 minutes", strtotime($time)))) ) //+11
				{
					//Yii::info('+11 hours - '.strtotime("+8 hours", strtotime($time)), 'mainCron');
					//послать емайл 1 часов
					$rate_info->email->fours = '1';
					//$this->sendEmail(Yii::$app->params['emailText']['toPay']['third']);
					//$rate = Rate::findone($rateWinner->rate_id);
					//$url = Yii::$app->urlManager->createAbsoluteUrl(['pay/index', 'id'=>$rate->id]);
					$email = Yii::$app->params['emailText']['toPay']['third'];
					$email['email'] = $rate->user['email'];
					$email['messege'] = sprintf($email['messege'], $rate->user['name'], $timePay, $rate->lot['name'], $url);
					$this->sendEmail($email);
				//}elseif($cronInfo->time > date('Y-m-d H:i:s', strtotime("+12 hours", strtotime($time))) ) //+12
				}elseif($cronInfo->time > date('Y-m-d H:i:s', strtotime("+20 minutes", strtotime($time))) ) //+12
				{
					//Yii::info('попали в проверку', 'mainCron');
					//$rate = Rate::findone($rateWinner->rate_id);
					//$url = Yii::$app->urlManager->createAbsoluteUrl(['pay/index', 'id'=>$rate->id]);
					$email = Yii::$app->params['emailText']['toPay']['close'];
					$email['email'] = $rate->user['email'];
					$email['subject'] = sprintf($email['subject'], $rate->lot['name']);
					$email['messege'] = sprintf($email['messege'], $rate->user['name'], $rate->lot['name']);
					$this->sendEmail($email);
					//Yii::info('отправили письмо о проебе', 'mainCron');
					//новый пользователь
					$numberUser =  $rate_info->user+1; 
					//Yii::info('передаем в getNextRate  rateWinner->rate_id='.$rateWinner->rate_id." и numberUser".$numberUser, 'mainCron');
					$rate_id = $this->getNextRate($rateWinner->rate_id, $numberUser);
					//Yii::info('получили след rate_id'.$rate_id, 'mainCron');
					if($rate_id)
					{
						$this->chengeRateRefusal($rateWinner->rate_id);
						//Yii::info('изминили rate_id', 'mainCron');
						$this->chengeWinnerRate($rateWinner->id, $rate_id, $numberUser);
					}
					else
					{
						$this->chengeRateRefusal($rateWinner->rate_id);
						$rateWinner->status = 2;
					}
				}
				$rateWinner->rate_info =  json_encode($rate_info);
				$rateWinner->save();
			}
		}
		return TRUE;
	}
	
	/**
	* изминение статуса refusal
	* 
	* @param int $rate_id
	* 
	* @return
	*/
	public function chengeRateRefusal($rate_id)
	{
		$rate = Rate::findone($rate_id);
		if($rate)
		{
			$rate->refusal = 1;
			$rate->save();
		}
		return TRUE;
	}
	
	/**
	* возращает предидущий id 
	* 
	* @param undefined $lastRate
	* @param undefined $offset
	* 
	* @return  rate->id or false
	*/
	public function getNextRate($lastRate, $offset = 1)
	{
		$rateLast = Rate::findone($lastRate);
		if($rateLast)
		{
			$rate = Rate::find()->where(['lot_id'=>$rateLast->lot_id])->orderBy('id desc')->offset($offset)->one();
			if($rate)
			{
				if($rate->refusal != 1)
				{
					return $rate->id;
				}
				
			}
		}
		return false;
	}
	
	/**
	* отправка сообщения пользователю
	* @param undefined $mail
	* 
	* @return true or false
	*/
	public function sendEmail($mail)
	{
		return  Yii::$app->mailer->compose( $mail['view'], ['mail' => $mail])
		    ->setFrom(Yii::$app->params['mainEmail'])
		    ->setTo($mail['email'])
		    ->setSubject($mail['subject'])
		    ->send();	  
	}

}
