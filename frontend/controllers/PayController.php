<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Rate;

class PayController extends \yii\web\Controller
{
	
    public function actionIndex($id)
    {
    	//проверка на авторизацию
    	$identity = Yii::$app->getUser()->getIdentity();
	    if ($identity->profile) 
	    {
	    	//проверка оплачен ли
    		//$rate = Rate::find()->where(['lot_id'=>$post['lot_id']])->orderBy('price desc')->one();
    		$rate = Rate::findOne($id);
    		if($rate){
				
			}
			else{
				
			}
    		var_dump($rate);
	    }
	    else
	    {
			return $this->render('index', [
	        	'info' => 'Что бы приступить к оплате вы должны быть авторизованы.'
	        ]);
		}
    	
    	
    	/*var_dump($id);
        return $this->render('index', [
        	
        ]);*/
    }
    
     public function actionThanks()
    {
        return $this->render('thanks');
    }

}
