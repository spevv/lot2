<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Follower;
use common\models\CheckLot;
use common\models\Lot;
use common\models\CategoryLot;
use common\models\CronInfo;
use common\models\UserInterests;
use console\models\SendEmail;

class Delivery
{

	/**
	*	start metod 
	* ищем все новые лоты, проверяем их категории, отправляем на getUserInteres
	* 
	*  если нужно что бы отправлялись сообщения и на переложеные лоты, то нужно когда лот перелаживается, делать обновление времени создания
	* 
	* @return
	*/ 
	public function startCron()
	{
		$cronInfo = CronInfo::findone(['type' => 'email_interest']);
		$Lots = Lot::find()->where(['>',  'creation_time', $cronInfo->time])->andWhere(['public'=>1])->all();
		//Yii::info('count Lots - '.count($Lots), 'delivery');
		//$Lots = Lot::findall([30]); //тест
		//var_dump($Lots);
		if($Lots)
		{
			foreach($Lots as $Lot)
			{
				//получить все email подпищиков $this->getFollowers();
				// получил все email по категория $this->getAllEmailsOfCategories($lotId);
				$emails = array_merge($this->getAllEmailsOfCategories($Lot->id), $this->getFollowers());
				$emails = array_unique($emails);
				$this->limitingEmails($Lot, $emails);
			}
		}
		$cronInfo->time = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss');
		$cronInfo->save();
		return TRUE;
	}
	
	/**
	* по LotId найти все категории и с них получить все email 
	* 
	* @param int $lotId
	* 
	* @return array $emails
	*/
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
	
	/**
	* возращает все emails подписщиков
	* 
	* @return all Follower emails
	*/
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
	 
	/**
	* ищи по категории всех юзеров, которые делали ставки ($interesStep)
	* 
	* @param int $categoryId
	* 
	* @return $emails
	*/
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
	
	
	/**
	* ограничение emails в одном письме
	* 
	* @param obj $Lot
	* @param array $emails
	* 
	* @return TRUE
	*/
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
			$this->sendInfoAboutNewLot($Lot, $emails);
		}
		return TRUE;
	}
	 
	/**
	* форирование email и отправка на sendEmail()
	* 
	* @param obj $Lot
	* @param array $emails
	* 
	* @return true or false
	*/
	public function sendInfoAboutNewLot($Lot, $emails)
	{
		$url = Yii::$app->urlManager->createAbsoluteUrl(['lot/view', 'slug'=>$Lot->slug]);
		$newEmail = Yii::$app->params['emailText']['interest'];
		$newEmail['subject'] = sprintf($newEmail['subject'], $Lot->name);
		$newEmail['messege'] = sprintf($newEmail['messege'], $url, $Lot->name);
			
		$return = $this->sendEmail($newEmail, $emails);
		if(!$return)
		{
			Yii::info('false email send', 'delivery');
		}
		return $return;
		
	}
	
	/**
	* отправка сообщения пользователю 
	* 
	* @param array $mail, контент сообщение
	* @param array $emails
	* 
	* @return true or false
	*/
	public function sendEmail($mail, $emails)
	{
		return  Yii::$app->mailer->compose( $mail['view'], ['mail' => $mail])
		    ->setFrom(Yii::$app->params['mainEmail'])
		    ->setTo($emails)
		    ->setSubject($mail['subject'])
		    ->send();	 
	}
}
