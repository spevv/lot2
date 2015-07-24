<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\Delivery;
use common\models\CheckLot;

/**
* запуск крон, проверки
*/
class MainController extends Controller
{
    public function actionIndex()
    {
    	Yii::info("start - CheckLot and Delivery", 'mainCron');
    	$CheckLot = new CheckLot();
    	$CheckLot->startCron();
    	$Delivery = new Delivery();
    	$Delivery->startCron();
    	Yii::info("finish - CheckLot and Delivery", 'mainCron');
		return 1;
    }
}
