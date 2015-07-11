<?php

namespace frontend\controllers;

//use frontend\models\PG_Signature;

class Platron
{
	/*
	* Секретный ключ магазина в системе Platron (выдается при подключении магазина к Platron)
	*/
    public $MERCHANT_SECRET_KEY = "xisywonixawypasa"; 
    public $MERCHANT_ID = 7836;

    private $_user = false;


   
    public function pay()
    {
		$arrReq = array();

		/* Обязательные параметры */
		$arrReq['pg_merchant_id'] = $this->MERCHANT_ID;// Идентификатор магазина
		$arrReq['pg_order_id']    = "67";		// Идентификатор заказа в системе магазина
		$arrReq['pg_amount']      = 500.54;		// Сумма заказа
		$arrReq['pg_lifetime']    = 3600*24;	// Время жизни счёта (в секундах)
		$arrReq['pg_description'] = "Описание"; // Описание заказа (показывается в Платёжной системе)

		$arrReq['pg_currency'] = "RUR"; // Описание заказа (показывается в Платёжной системе)

		/*
		 * Название ПС из справочника ПС. Задаётся, если не требуется выбор ПС. Если не задано, выбор будет
		 * предложен пользователю на сайте platron.ru.
		 */
		$arrReq['pg_payment_system'] = 'TESTCARD';
		$arrReq['pg_language'] = 'ru';

		//
		$arrReq['pg_user_contact_email'] = 'developer.awam@gmail.com';
		$arrReq['pg_need_email_notification'] = '1';
		$arrReq['pg_user_phone'] = '7(922)1111111';
		$arrReq['pg_need_phone_notification'] = '0';


		/*
		 * Нижеследующие параметры имеет смысл определять, только если они отличаются от заданных
		 * в настройках магазина на сайте platron.ru (https://www.platron.ru/admin/merchant_settings.php)
		 */
		$arrReq['pg_success_url'] = 'http://yii.awam-it.ru/db/payment_ok.php';
		$arrReq['pg_success_url_method'] = 'AUTOGET';
		$arrReq['pg_failure_url'] = 'http://yii.awam-it.ru/db/payment_failure.php';
		$arrReq['pg_failure_url_method'] = 'AUTOGET';

		/* Параметры безопасности сообщения. Необходима генерация pg_salt и подписи сообщения. */
		$arrReq['pg_salt'] = rand(21,43433);
		$arrReq['pg_sig'] = PG_Signature::make('payment.php', $arrReq, $MERCHANT_SECRET_KEY);
		$query = http_build_query($arrReq);

		header("Location: https://www.platron.ru/payment.php?$query");
    }
    
    public function paymentFailure()
    {

		$arrParams = $_GET;
		$thisScriptName = PG_Signature::getOurScriptName();

		var_dump($arrParams);
		
		if ( !PG_Signature::check($arrParams['pg_sig'], $thisScriptName, $arrParams, $this->MERCHANT_SECRET_KEY) )
		    die("Bad signature");
		    
		echo "Ваш заказ <b>не</b> оплачен.";
    }
    
    public function  paymentOk()
    {
		$arrParams = $_GET;
		$thisScriptName = PG_Signature::getOurScriptName();

		var_dump($arrParams);

		if ( !PG_Signature::check($arrParams['pg_sig'], $thisScriptName, $arrParams, $this->MERCHANT_SECRET_KEY) )
		    die("Bad signature");
		    
		echo "Спасибо, Ваша оплата принята! Ждите доставки...";
    	
    }
    
}



$platron = new Platron();
$platron->pay();



