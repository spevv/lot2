<?php

namespace console\controllers;

use yii\console\Controller;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Rate;
use common\models\RateWinner;
use common\models\Lot;
use console\models\CronInfo;


class MainController extends Controller
{
	
    public function actionIndex()
    {
    	/*$rate = Rate::findOne($id);
		if($rate)
		{
			$rateWinner = new RateWinner();
			$rateWinner->pay = '';
			$rateWinner->rate_id = $id;
			$rateWinner->comment = 'md5';
			$rateWinner->rate_info = '{a:5}';
			$rateWinner->status = '1';
			$rateWinner->winner_time = '';
			$rateWinner->pay_time = '';
			$rateWinner->save();
			return true;
		}
		else
		{
			return false;	
		}*/
		echo('start');
        
        //return $this->render('index');
    }
    
    public function actionCheck()
    {
		
		$cronInfo = CronInfo::findone(['type' => 'update_rate']);
		
		
		//var_dump($cronInfo['time']);
		// проверить запрос
		$lots = Lot::find()
    		->where(['<',  'remaining_time', Yii::$app->formatter->asDate('now', 'yyyy-MM-dd hh:mm:ss')])
    		->andWhere(['>',  'remaining_time', $cronInfo['time']])
    		->all(); 
    		
    	//var_dump($lots);
    	//return $lots;
    	//echo($lots);
    	
    	$cronInfo->time = '2015-06-03 11:11:00';
		$cronInfo->save();
		
		return false;
		
	}

}
