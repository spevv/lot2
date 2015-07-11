<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Rate;
use common\models\RateWinner;
use common\models\CheckLot;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

use frontend\controllers\PG_Signature;

class PayController extends \yii\web\Controller
{
	
	/*
	* Секретный ключ магазина в системе Platron (выдается при подключении магазина к Platron)
	*/
    public $MERCHANT_SECRET_KEY = "xisywonixawypasa"; 
    public $MERCHANT_ID = 7836;
   
   
    public function beforeAction($action) 
    {
    	//var_dump(($action->id !== "payment-failure"));
    	if(($action->id == "payment-failure") OR ($action->id == "payment-ok"))
    	{
			$this->enableCsrfValidation = false;
		}

        //$this->enableCsrfValidation = ($action->id !== "payment-failure"); 
        //$this->enableCsrfValidation = ($action->id !== "payment-ok"); 
        return parent::beforeAction($action);
    }
    
    public function pay($array)
    {
		$arrReq = array();

		/* Обязательные параметры */
		$arrReq['pg_merchant_id'] = $this->MERCHANT_ID;// Идентификатор магазина
		$arrReq['pg_order_id']    = $array['order_id'];		// Идентификатор заказа в системе магазина
		$arrReq['pg_amount']      = $array['amount'];		// Сумма заказа
		$arrReq['pg_lifetime']    = 3600*24;	// Время жизни счёта (в секундах)
		$arrReq['pg_description'] = $array['description']; // Описание заказа (показывается в Платёжной системе)

		$arrReq['pg_currency'] = "RUR"; // Описание заказа (показывается в Платёжной системе)

		/*
		 * Название ПС из справочника ПС. Задаётся, если не требуется выбор ПС. Если не задано, выбор будет
		 * предложен пользователю на сайте platron.ru.
		 */
		//$arrReq['pg_payment_system'] = 'TESTCARD';
		//$arrReq['pg_language'] = 'ru';

		//
		$arrReq['pg_user_contact_email'] = $array['email'];
		$arrReq['pg_need_email_notification'] = '1';
		//$arrReq['pg_user_phone'] = '7(922)1111111';
		$arrReq['pg_need_phone_notification'] = '0';


		/*
		 * Нижеследующие параметры имеет смысл определять, только если они отличаются от заданных
		 * в настройках магазина на сайте platron.ru (https://www.platron.ru/admin/merchant_settings.php)
		 */
		$arrReq['pg_success_url'] = Url::home(true).'pay/payment-ok';
		$arrReq['pg_success_url_method'] = 'AUTOPOST';
		$arrReq['pg_failure_url'] = Url::home(true).'pay/payment-failure';
		$arrReq['pg_failure_url_method'] = 'AUTOPOST';

		/* Параметры безопасности сообщения. Необходима генерация pg_salt и подписи сообщения. */
		$arrReq['pg_salt'] = rand(21,43433);
		$arrReq['pg_sig'] = PG_Signature::make('payment.php', $arrReq, $this->MERCHANT_SECRET_KEY);
		
		$query = http_build_query($arrReq);
		
		return "https://www.platron.ru/payment.php?$query";
		//header("Location: https://www.platron.ru/payment.php?$query");
    }
    
    public function actionPaymentFailure()
    {
		$arrParams = Yii::$app->request->post();

		if($arrParams)
		{
			if ( !PG_Signature::check($arrParams['pg_sig'], 'payment-failure', $arrParams, $this->MERCHANT_SECRET_KEY) ){ //payment_failure.php
				throw new NotFoundHttpException('Страница не найдена.');
			}
			
			$currentTime = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss');
			$log = 'PaymentFailure --- '.$currentTime.'; id='.$arrParams['pg_payment_id'].'; pay='.$arrParams['pg_order_id'].'; ';
			Yii::info($log, 'payment');
			// var_dump($arrParams); записать в лог
			return $this->render('failure');
		}
		throw new NotFoundHttpException('Страница не найдена.');
		
    }
    
    public function actionPaymentOk()
    {
    	/*$rateWinner = RateWinner::find()->where(['rate_id' => 415])->one();
		if($rateWinner)
		{
			$rateWinner->pay = $arrParams['pg_payment_id'];
			$rateWinner->pay_time = $currentTime;
			$rateWinner->status = '1';
			$rateWinner->save();
			
			return $this->render('thanks', [
				'messege' => 'Вы успешно провели оплатилу, вся информация <a href="'.Url::to(['lot/view', 'slug'=>$rateWinner->rate->lot['slug']]).'" target="_blank">"'.$rateWinner->rate->lot['name'].'"</a>.',
				//'url' => $this->pay($array),
			]);
		}*/
			
			
		$arrParams = Yii::$app->request->post();
		
		if($arrParams)
		{
			if ( !PG_Signature::check($arrParams['pg_sig'], 'payment-ok', $arrParams, $this->MERCHANT_SECRET_KEY) ){
				throw new NotFoundHttpException('Страница не найдена.');
			}
			    
			// var_dump($arrParams); записать в лог
			//var_dump($arrParams);
			$currentTime = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss');
			$log = 'PaymentOk --- '.$currentTime.'; id='.$arrParams['pg_payment_id'].'; pay='.$arrParams['pg_order_id'].'; ';
			Yii::info($log, 'payment');
			//выполнить запись и перейти на страницу thanks
			//$arrParams['pg_payment_id'] $arrParams['pg_order_id']
			
			$rateWinner = RateWinner::find()->where(['rate_id' => $arrParams['pg_order_id']])->one();
			if($rateWinner)
			{
				$rateWinner->pay = $arrParams['pg_payment_id'];
				$rateWinner->pay_time = $currentTime;
				$rateWinner->status = '1';
				$rateWinner->save();
				// отправить письмо на почту с кодом
				$this->sendEmail($rateWinner->rate, $arrParams['pg_payment_id']);
				
				return $this->render('thanks', [
					'messege' => 'Вы успешно провели оплатилу, вся информация -  <a href="'.Url::to(['lot/view', 'slug'=>$rateWinner->rate->lot['slug']]).'" target="_blank">"'.$rateWinner->rate->lot['name'].'"</a>. Ваш код <strong>'.$arrParams['pg_payment_id'].'</strong>' ,
					//'url' => $this->pay($array),
				]);
			}
			
		}
		throw new NotFoundHttpException('Страница не найдена.');
    	
    }
    
    
    private function sendEmail($rate, $code)
    {
    	$checkLot = new CheckLot();
    	
    	Yii::$app->params['emailText']['toPay']['payed']['email'] = $rate->user['email'];
		Yii::$app->params['emailText']['toPay']['payed']['subject'] = sprintf(Yii::$app->params['emailText']['toPay']['payed']['subject'], $rate->lot['name']);
		Yii::$app->params['emailText']['toPay']['payed']['messege'] = sprintf(Yii::$app->params['emailText']['toPay']['payed']['messege'], $rate->user['name'], $rate->lot['name'], $code);
		return $checkLot->sendEmail(Yii::$app->params['emailText']['toPay']['payed']);
	}
	
	
	public function actionIndex($id)
	{
		$identity = Yii::$app->getUser()->getIdentity();
	    if (isset($identity->profile)) 
	    {
	    	$user2_id =  $identity->profile['service'].'-'.$identity->profile['id'];
	    }
	    else
	    {
	    	Yii::$app->getSession()->setFlash('error', 'Для того что бы перейти к оплате, вы должны авторизироваться.');
             return $this->render('index', [
		    	'name' => 'Пройдите авторизацию',
		    	'content' => '',
			    'button' => '',
			    'social' => 'false'
		    ]);
		}
		
		
		$rate = Rate::findOne($id);
		if($rate)
		{
			if($rate->user['user_id'] == $user2_id)
			{
				//var_dump( $rate->id);
				//toPay($array);
				$rateWinner = RateWinner::find()->where(['rate_id' => $rate->id])->one();
							//var_dump($rateWinner);
				if($rateWinner)
				{
					$socialShare = $this->social($rate->lot, $rate);
					$winnerTime = Yii::$app->formatter->asDatetime($rateWinner->winner_time, 'dd.MM.yyyy hh:mm');
					
					$content = $rate->user['name'].",  ".$winnerTime. ' вы победили в лоте, и выиграли <a href="'.Url::to(['lot/view', 'slug'=>$rate->lot['slug']]).'" target="_blank">"'.$rate->lot['name'].'"</a>'. ' оплатите ваш лот. Спасибо.';
					$button = '1';
					
					$array = [
						'order_id' => $rate->id,
						'amount' =>$rate->price.'.00', //11.00
						'description' => $rate->lot['name'],
						'email' =>  $rate->user['email'],
					];
					
		            return $this->render('index', [
				    	'name' => 'Оплатите лот',
				    	'content' => $content,
				    	'button' => $button,
				    	'url' => $this->pay($array),
				        'social' => json_encode($socialShare['social']),
				    ]);
				}
				else
				{
					throw new HttpException(404,'Вы не имеете права находиться на этой странице.');
				}
				
			}
			else
			{
				throw new HttpException(404,'Вы не имеете права находиться на этой странице.');
			}	
		}
		else
		{
			throw new HttpException(404,'Указанная страница не найдена. Вы не имеете права оставлять отзыв.');	
		}
	}

    // возврат изображения
    private function getImage($image)
    {
		if($image{0} == '/')
		{
			$st = substr($image, 1);
			$img = Url::home(true).$st;
		}
		else
		{
			$img = Url::home(true).$image;
		}
		return $img;
	}
	
	// return current url
	private function getUrl($modelId)
    {
    	
    	$string = Url::to(['lot/view', 'slug'=>$modelId['slug']]);
		if($string{0} == '/')
		{
			$st = substr(Url::to(['lot/view', 'slug'=>$modelId['slug']]), 1);
			$url = Url::home(true).$st;
		}
		else
		{
			$url = Url::home(true).Url::to(['lot/view', 'slug'=>$modelId['slug']]);
		}
		return $url;
		//return Url::to(['lot/view', 'slug'=>$modelId['slug']]);
	}
    
    // return socil info
    private function social($modelId, $rate)
    {
    	$img = $this->getImage($modelId['image']);
    	$url = $this->getUrl($modelId);
    	
    	//$attachment =  '{"media": [{"type": "link","url": "http://yii.awam-it.ru"},{"type": "text", "text": "hello world!"}]}';
		$attachment =  '{"media": [{"type": "link","url": "'.$url.'"},{"type": "text", "text": "Я победил в лоте за - '.$rate->price.' р"}]}';
		$sigSource = 'st.attachment='.$attachment.Yii::$app->params['odnoklassniki']['clientSecret'];
		$sign = md5($sigSource);
		
		$okUrl = 'http://connect.ok.ru/dk?st.cmd=WidgetMediatopicPost'.
			'&st.app='.Yii::$app->params['odnoklassniki']['clientId'].
			'&st.attachment='.urlencode($attachment).
			'&st.signature='.$sign.
			"&st.silent=on";
		
		$social = [
			'vk' => [
				'apiId' => Yii::$app->params['vkontakte']['clientId'],
				'url' => $url,
				'message' => 'Я победил в лоте за - '.$rate->price.' р',
				'title' => $modelId['name'],
				'image' => $img,
			],
			'fb' => [
				'apiId' => Yii::$app->params['facebook']['clientId'],
				'name' => $modelId['name'],
				'caption' => '',
				'link' => $url,
				'picture' => $img,
				'description' => 'Я победил в лоте за - '.$rate->price.' р',
			],
			'ok' => [
				//'apiId' => Yii::$app->params['odnoklassniki']['clientId'],
				//'clientSecret' => Yii::$app->params['odnoklassniki']['clientSecret'],
				'url' => $okUrl,
			],
		];
		
		
		//данные для share 
		$share =[
			'image' => $img,
			'text' => "Присоединяйтесь к нам.",
		];
		
		return ['social' => $social, 'share' => $share];
		
	}

}
