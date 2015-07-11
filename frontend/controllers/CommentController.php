<?php

namespace frontend\controllers;

use Yii;
use common\models\Comment;
use common\models\RateWinner;
use common\models\Rate;
use yii\web\HttpException;
use frontend\models\CommentSearch;
use common\models\Article;

class CommentController extends \yii\web\Controller
{
	
	public function actionComments()
	{
		//$model
		$model = Article::findone(6);
		
		$searchModel = new CommentSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		return $this->render('comments', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,  
            'model' => $model,  
        ]);
		
		/*$model = Comment::find()->where(['public' => 1])->all();
		return $this->render('comments', [
			'model' => $model,
			'content' => '',
		]);*/
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
		        'text' => '',
		    ]);
		}
			
		
		$rateWinner = RateWinner::findone(['comment'=>$id]);
		if($rateWinner)
		{
			$model = new Comment();
			
			$rate = Rate::findone(['id' => $rateWinner->rate_id]);
			
		    if ($model->load(Yii::$app->request->post())) 
		    {
		    	if($rate)
		    	{
		    		if($rate->user['user_id'] == $user2_id){
						$model->public = 1;
			        	$model->user2_id = $rate->user['id'];
			        	$model->lot_id = $rate->lot_id;
			        
				        if ($model->validate()) {
				        	$model->save();
				            // form inputs are valid, do something here
				            $rateWinner->comment = "";
				            $rateWinner->send_email_time = "";
				            $rateWinner->save();
				            
				            Yii::$app->getSession()->setFlash('success', 'Спасибо. Вы успешно оставили отзыв.');
				             return $this->render('index', [
						    	'name' => 'Вы успешно оставили отзыв',
						    	'text' => '',
						        'content' => '',
						    ]);
				        }
					}
					else{
						throw new HttpException(404,'Указанная страница не найдена. Вы не имеете права оставлять отзыв.');
					}	
				}
		    	
		    }
		    
		    $text = $rate->user['name'].' вы выиграли "'. $rate->lot['name'].'" напешите ваше мнение, спасибо ';
			
		    return $this->render('index', [
		    	'name' => 'Ваш отзыв',
		    	'text' => $text,
		        'content' => $this->renderPartial('_form', [
	            	'model' => $model,
	            ]),
		    ]);
		}
		else
		{
			throw new HttpException(404,'Указанная страница не найдена. Вы не верно перешли по ссылке или вы уже оставляли отзыв.');
		}
	}

}



