<?php

namespace frontend\controllers;

use common\models\Follower;

class FollowerController extends \yii\web\Controller
{
	
    public function actionCreate()
    {
    	
    	$model = new Follower();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else 
        {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
       // return $this->render('create');
    }

}
