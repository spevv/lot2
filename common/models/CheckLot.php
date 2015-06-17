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
    	//проверка и запись новых лотов в победители
    	$lots = $this->findeLotOnStepCron();
    	if($lots)
    	{
			foreach($lots as $lot)
			{
				$rate_id = $this->rateStatistic($lot->id);
				if($rate_id)
				{
					$this->toWinnerRate($rate_id);
				}
			}
		}
		
		//отправка писем Конец лота
		
		//отправка Победителю
		
		// запись времени оплаты и проверка 
		
		//отправка новых писем до конца лота
		
		//рассылка по интиресам

	}
	
	
	// поиск всех отыграных лотов за последний проход cron
	public function findeLotOnStepCron()
	{
		$cronInfo = CronInfo::findone(['type' => 'update_rate']);
		$lots = Lot::find()
    		->where(['<',  'remaining_time', Yii::$app->formatter->asDate('now', 'yyyy-MM-dd hh:mm:ss')])
    		->andWhere(['>',  'remaining_time', $cronInfo['time']])
    		->all(); 
    		
    	$cronInfo->time = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd hh:mm:ss');
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
	
	// запись в победители
	public function toWinnerRate($rate_id)
	{
		$currentTime  = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd hh:mm:ss');
		$rateWinner = new RateWinner();
		$rateWinner->pay = '';
		$rateWinner->rate_id = $rate_id;
		$rateWinner->comment = md5($rate_id.$currentTime);
		$rateWinner->rate_info = '';
		$rateWinner->status  = 0;
		$rateWinner->winner_time = $currentTime;
		$rateWinner->pay_time = '';
		$rateWinner->save();
		return TRUE;
	}

}




