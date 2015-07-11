<?php

namespace common\models;

use Yii;
//use yii\helpers\Html;
//use yii\helpers\Url;
use common\models\Follower;
use common\models\CheckLot;
use common\models\Lot;
use common\models\CategoryLot;
use common\models\CronInfo;
//use common\models\UserSettings;
use common\models\UserInterests;



class Delivery
{

	/**
	*	start metod 
	*  если нужно что бы отправлялись сообщения и на переложеные лоты, то нужно когда лот перелаживается, делать обновление времени создания
	* 
	* @return
	*/
	// ищем все новые лоты, проверяем их категории, отправляем на getUserInteres
	public function getLotsInfo()
	{
		$cronInfo = CronInfo::findone(['type' => 'email_interest']);
		
		$Lots = Lot::find()->where(['>',  'creation_time', $cronInfo->time])->all();
		//$Lots = Lot::findall([3,4]); //тест
		//var_dump($Lots);
		if($Lots)
		{
			foreach($Lots as $Lot)
			{
				//получить все email подпищиков $this->getFollowers();
				// получил все email по категория $this->getAllEmailsOfCategories($lotId);
				$emails = array_merge($this->getAllEmailsOfCategories($Lot->id), $this->getFollowers());
				// eсли будут повтояться значения то array_unique 
				$emails = array_unique($emails);
				$this->limitingEmails($Lot, $emails);
			}
		}
		$cronInfo->time = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss');
		$cronInfo->save();
		return TRUE;
		//получиьт лот, получить 
		//return $this->getUserInteres($categoryId, $Lot);
	}
	
	// по LotId найти все категории и с них получить все email
	public function getAllEmailsOfCategories($lotId)
	{
		$emails = [];
		$CategoryLots = CategoryLot::find()->where(['lot_id' => $lotId])->all();
		if($CategoryLots)
		{
			foreach($CategoryLots as $CategoryLot)
			{
				$emailsArray = $this->getUserInteresEmails($CategoryLot->category_id);
				if($emailsArray)
				{
					$emails = array_merge($emails, $emailsArray);
				}
			}
			
		}
		return $emails;
	}
	
	// return all Follower emails
	public function getFollowers()
	{
		$emails = [];
		$Followers = Follower::find()->all();
		if($Followers)
		{
			foreach($Followers as $Follower)
			{
				$emails[] = $Follower->mail;
			}
		}
		return $emails;

	}
	
	// ищи по категории всех юзеров, которые делали ставки ($interesStep)
	public function getUserInteresEmails($categoryId)
	{
		
		$interesStep = Yii::$app->params['delivery.interesStep']; // шаг интереса, с которого начинается отправка email
		$emails = [];
		
		$users = UserInterests::find()->all();
		if($users)
		{
			foreach($users as $user)
			{
				$interestsArray = json_decode($user->interests, TRUE);
				if(isset($interestsArray[$categoryId]) and ($interestsArray[$categoryId] > $interesStep ))
				{
					// проверка из настроек пользователя
					if($user->userSettings->interest)
					{
						$emails[] = $user->users->email;
					}
				}
			}
		}
		return $emails;
	}
	
	
	// ограничение emails в одном письме
	protected function limitingEmails($Lot, $emails)
	{

		$countEmail = Yii::$app->params['delivery.countEmailInEmail']; // количество emails которые можно отправить в одном письме = 15
		if(count($emails) > $countEmail)
		{
			$emailsArray = array_chunk($emails, $countEmail);
			foreach($emailsArray as $key=>$value)
			{
				$this->sendInfoAboutNewLot($Lot, $value);
			}
		}
		else
		{
			return $this->sendInfoAboutNewLot($Lot, $emails);
		}
		return FALSE;
	}
	
	// форирование email
	public function sendInfoAboutNewLot($Lot, $emails)
	{
		$newEmail = Yii::$app->params['emailText']['interest'];
		$newEmail['subject'] = sprintf(Yii::$app->params['emailText']['interest']['subject'], $Lot->name);
		$newEmail['messege'] = sprintf(Yii::$app->params['emailText']['interest']['messege'],  Yii::$app->urlManager->createAbsoluteUrl(['lot/view', 'slug' => $Lot->slug]), $Lot->name);
		// сделать проверку на логирование, когда не отправилось		
		$return = $this->sendEmail($newEmail, $emails);
		if(!$return)
		{
			$log = 'false email send --- '.Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss').'; ';	
			Yii::info($log, 'follower');
		}
		return $return;
	}
	
	
	//отправка сообщения пользователю
	// array $emails
	public function sendEmail($mail, $emails)
	{
		/*$mail = [
			'view' => 'email-html',
			'emailTo' => 'developer.awam@gmail.com',
			'messege' => 'Привет мир',
			'subject' => 'subject текст',
		];*/
		return  Yii::$app->mailer->compose( $mail['view'], ['mail' => $mail])
		    ->setFrom(Yii::$app->params['mainEmail'])
		    ->setTo($emails)
		    ->setSubject($mail['subject'])
		    ->send();	  
	}
	
	
	

}
