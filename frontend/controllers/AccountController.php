<?php

namespace frontend\controllers;

use yii\filters\VerbFilter;
use Yii;
use common\models\Rate;
use common\models\UserSettings;
use frontend\models\AccountSearch;
use frontend\models\AccountWinnerSearch;
use frontend\models\UserSocial;


class AccountController extends \yii\web\Controller
{
	
	public function behaviors()
	{
		return [
			/*'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],*/
			'access' => [
				'class' => \yii\filters\AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
		];
	}
	
	public function actionRate()
    {
    	
    	$identity = Yii::$app->getUser()->getIdentity();
	    if (isset($identity->profile)) 
	    {
	    	$user2_id =  $identity->profile['service'].'-'.$identity->profile['id'];
	    	
	    	$rates = Rate::find()->where(['user2_id'=>$user2_id])->all();
	    
	    
	    	$searchModel = new AccountSearch();
	    	$dataProvider = $searchModel->search(Yii::$app->request->post());
	    	
	    	//var_dump($dataProvider);
	    	
	    
	    	return $this->render('rate', [
        		//'rates' => $rates,
        		'searchModel' => $searchModel,
	            'dataProvider' => $dataProvider,
        		'menu' => $this->getMenu(),
        	]);
	    }
		//var_dump($rates);
			
    	//$rate = Rate::find()->where(['lot_id'=>$modelId->id])->andWhere(['refusal'=>0])->andWhere(['>',  'id', $lotRateStatistic->last_rate])->count();
    	
        
    }
	
    public function actionChangeEmail()
    {	
    
    	$identity = Yii::$app->getUser()->getIdentity();
	    if (isset($identity->profile)) 
	    {
	    	$user2_id =  $identity->profile['service'].'-'.$identity->profile['id'];
	    	$user = UserSocial::find()->where(['user_id' => $user2_id])->one();
			
			$post = Yii::$app->request->post();
	    	if($post)
	    	{
	    		//$email = 'test@example.com';
	    		$error = 'Email - не может быть пустым, заполните поле.';
				$validator = new \yii\validators\EmailValidator();

				if ($validator->validate($post["UserSocial"]['email'], $error)) {
				    $user->email = $post["UserSocial"]['email'];
					$user->save();
					Yii::$app->session->setFlash('success', 'Поздравляем! Вы успешно записали email.');
				} else {
				    Yii::$app->session->setFlash('error', 'Введите правильный email. Поле не может быть пустым!');
				}
	    		
				
				return $this->renderPartial('_email-form', [
	                'user' => $user,
	            ]);
				
			}
			else
			{
				return $this->render('change-email', [
		        	'menu' => $this->getMenu(),
		        	'emailForm' => $this->renderPartial('_email-form', [
		                'user' => $user,
		            ]),
		        ]);
			}
			
	    	
	    }
       
    }

    public function actionEmail()
    {
    	
    	$identity = Yii::$app->getUser()->getIdentity();
	    if (isset($identity->profile)) 
	    {
	    	$user2_id =  $identity->profile['service'].'-'.$identity->profile['id'];
	    	$user2_id =  $identity->profile['service'].'-'.$identity->profile['id'];
	    	$userSettings = UserSettings::find()->where(['user_id'=> $user2_id])->one();
			
			$post = Yii::$app->request->post();
	    	if($post)
	    	{
				if ($userSettings->load($post)) {
					//var_dump('load');
			        if ($userSettings->validate()) {
			            // form inputs are valid, do something here
			            //$model->date = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss');
			            $userSettings->save();
			            var_dump('in');
			            Yii::$app->session->setFlash('success', 'Поздравляем! Вы успешно сохранили настройки.');
			        }
			    }
				return $this->renderPartial('_settings-form', [
	                'model' => $userSettings,
	            ]);
				
			}
			else
			{
				return $this->render('email', [
		        	'menu' => $this->getMenu(),
		        	'emailForm' => $this->renderPartial('_settings-form', [
		                'model' => $userSettings,
		            ]),
		        ]);
			}
			
	    	
	    }
    /*	
    	$identity = Yii::$app->getUser()->getIdentity();

	    $user2_id =  $identity->profile['service'].'-'.$identity->profile['id'];
	    $userSettings = UserSettings::find()->where(['user_id'=> $user2_id])->one();
	    var_dump($userSettings);
	    
    	//_settings-form
        return $this->render('email', [
        	'menu' => $this->getMenu(),
        	'model' => $userSettings,
        ]);*/
    } 
    
	public function actionWon()
    {
    	
    	
    	$searchModel = new AccountWinnerSearch();
	    $dataProvider = $searchModel->search(Yii::$app->request->post(), TRUE);
	    	

    	return $this->render('won', [
    		//'rates' => $rates,
    		'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
    		'menu' => $this->getMenu(),
    	]);
    	
    }
    
	public function actionActive()
    {
    	
    	$searchModel = new AccountSearch();
	    $dataProvider = $searchModel->search(Yii::$app->request->post(), TRUE);
	    
    	return $this->render('active', [
    		//'rates' => $rates,
    		'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
    		'menu' => $this->getMenu(),
    	]);

    }
    
    private function getMenu()
    {
		$items = [
    		[
    			'label' => 'Лоты в которых участвую',
            	'url' => ['account/active'],
           		'linkOptions' => [],
    		],
    		[
    			'label' => 'Лоты в которых победил',
            	'url' => ['account/won'],
           		'linkOptions' => [],
    		],
    		[
    			'label' => 'Лоты в которых делал ставки',
            	'url' => ['account/rate'],
           		'linkOptions' => [],
    		],
    		[
    			'label' => 'Настройка email',
            	'url' => ['account/change-email'],
           		'linkOptions' => [],
    		],
    		[
    			'label' => 'Настройка уведомлений',
            	'url' => ['account/email'],
           		'linkOptions' => [],
    		],
    	];
    	return $this->renderPartial('_menu', [
            'items' => $items,
        ]);	
	}

}
