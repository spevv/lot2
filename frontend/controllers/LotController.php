<?php

namespace frontend\controllers;

use Yii;
use common\models\Lot;
use frontend\models\LotSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\Rate;
use common\models\RateWinner;
use common\models\LotRateStatistic;
use frontend\models\UserSocial;
use yii\helpers\VarDumper;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Response;
use backend\models\UserInterests;

use frontend\models\ContactForm;
use common\models\CategoryLot;



use common\models\CheckLot;


/**
 * LotController implements the CRUD actions for Lot model.
 */
class LotController extends Controller
{
    public function behaviors()
    {
        return [
           
        ];
    }
    
    

    /**
     * Lists all Lot models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LotSearch();
		
		if(Yii::$app->request->isPost){
			$dataProvider = $searchModel->search(Yii::$app->request->post());
			//var_dump('post');
			//die();
			
			return $this->renderAjax('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
		}
		else
		{
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
			return $this->render('index', [
	            'searchModel' => $searchModel,
	            'dataProvider' => $dataProvider,
	        ]);
		}
       /* return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);*/
    }

    /**
     * Displays a single Lot model.
     * @param string $id
     * @return mixed
     */
    /*public function actionView($id)
    {
    	$modelId = $this->findModel($id);
        return $this->render('view', [
           // 'model' => $this->findModel($id),
             'model' => $modelId->toArray([], ['subjects','branchs', 'images']),
        ]);
    }*/
    
    
    
    public function actionContact()
    {
    	/*var_dump('actionContact');
    	die;*/
    	
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Поздравляем! Ваше сообщение успешно отправлено.');
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось отправить сообщение.');
            }

           // return $this->refresh();
           //return '<div class="success-follower">Вы успешно подписались! 111111</div>';
            $modelNew = new ContactForm();
           return $this->renderPartial('contact', [
                'model' => $modelNew,
                ]);
        } 
    }
    
    public function actionFinishLot()
    {

		if (Yii::$app->request->isPost) {
	    	$post = Yii::$app->request->post();
	    	Yii::$app->response->format = Response::FORMAT_JSON;
	    	
	    	//выполнить проверки
	    	
	    	
	
	    	$identity = Yii::$app->getUser()->getIdentity();
		    if (isset($identity->profile)) 
		    {
		    	$user_id =  $identity->profile['service'].'-'.$identity->profile['id'];
		    	$rate = Rate::find()->where(['lot_id'=>$post['lot_id']])->orderBy('price desc')->one();
		    	if($user_id == $rate['user2_id'])
		    	{
					$res = array(
			            'success' => true,
			            'url' => Url::to(['pay/index', 'id'=>$rate['id']]),
			            
			        );
			        return $res;
				}
		    }
		    return array(
		        'success' => false,
		    );
			
	    }
	} 
    
    public function actionGetRateInfo() 
    {
    	//var_dump('simple');
	    if (Yii::$app->request->isPost) {
	    	$post = Yii::$app->request->post();
	    	
	        Yii::$app->response->format = Response::FORMAT_JSON;
	       	
	       	$rate = Rate::find()->where(['lot_id'=>$post['lot_id']])->orderBy('price desc')->one();
	       	
	       	if($rate){
				$res = array(
		            'success' => true,
		            'rate' => $rate->id,
		        );
		        return $res;
			}
			else
			{
				$res = array(
		            'success' => true,
		            'rate' => 0,
		        );
		        return $res;
			}
	    }
	} 
    
    public function actionRate()
    {
    	if(Yii::$app->request->isPost)
    	{
    		//var_dump('in');
			$post = Yii::$app->request->post();
			//$rates = Rate::find()->where(['lot_id'=>$post["Rate"]['lot_id']])->orderBy('price desc')->all();
			
			$lotRateStatistic = LotRateStatistic::find()->where(['lot_id'=>$post["Rate"]['lot_id']])->orderBy('id desc')->one();
	    	$temp = 0;
	    	if($lotRateStatistic){
				if($lotRateStatistic->status)
				{
					$rates = Rate::find()->where(['lot_id'=>$post["Rate"]['lot_id']])->andWhere(['refusal'=>0])->andWhere(['>',  'id', $lotRateStatistic->last_rate])->orderBy('price desc')->all();
	    			//$count = Rate::find()->where(['lot_id'=>$modelId->id])->andWhere(['>',  'id', $lotRateStatistic->last_rate])->count();
	    			$temp = 1;
				}
			}
			
			if(!$temp){
				$rates = Rate::find()->where(['lot_id'=>$modelId->id])->andWhere(['refusal'=>0])->orderBy('price desc')->all();
	    		//$count = Rate::find()->where(['lot_id'=>$modelId->id])->count();
			}
		
		
			if(!$rates)
    		{
    			$rate = new Rate();
    			$rate->price=0;
    			
    		}
    		else{
				$rate = clone $rates[0];
			}
    		
			$identity = Yii::$app->getUser()->getIdentity();
		    if (isset($identity->profile)) 
		    {
		    	$user2_id =  $identity->profile['service'].'-'.$identity->profile['id'];
		    }
		    else
		    {
		    	//return $this->redirect(Url::previous());
				$modelId = $this->findModelId($post["Rate"]['lot_id']);
		        return $this->redirect(['/lot/view', 'slug' => $modelId->slug]);
			}
			
			if($post["Rate"]['price'] > $rate->price)
			{
				$model = new Rate();
				$model->time = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss');
				$model->price = $post["Rate"]['price'];
				$model->lot_id = $post["Rate"]['lot_id'];
				$model->user2_id = $user2_id;
				$model->save();
				
				
				/*$checkLot = new CheckLot();
						
				Yii::$app->params['emailText']['slewRate']['email'] = '';
    			$checkLot->sendEmail(Yii::$app->params['emailText']['slewRate']);*/
				
				
				
				
				$lotRateStatistic = LotRateStatistic::find()->where(['lot_id'=>$post["Rate"]['lot_id']])->orderBy('id desc')->one();
		    	$temp = 0;
		    	if($lotRateStatistic){
					if($lotRateStatistic->status)
					{
						$rates2 = Rate::find()->where(['lot_id'=>$post["Rate"]['lot_id']])->andWhere(['>',  'id', $lotRateStatistic->last_rate])->orderBy('price desc')->all();
		    			$count = Rate::find()->where(['lot_id'=>$modelId->id])->andWhere(['>',  'id', $lotRateStatistic->last_rate])->count();
		    			$temp = 1;
					}
				}
				
				if(!$temp){
					$rates2 = Rate::find()->where(['lot_id'=>$modelId->id])->orderBy('price desc')->all();
		    		$count = Rate::find()->where(['lot_id'=>$modelId->id])->count();
				}
				
				//$rates2 = Rate::find()->where(['lot_id'=>$post["Rate"]['lot_id']])->orderBy('price desc')->all();
		    	//$count = Rate::find()->where(['lot_id'=>$post["Rate"]['lot_id']])->count();
		    	$count = $count.' '.$this->plural($count,['ставка', 'ставки', 'ставок']);
		    	
		    	if(!$rates2)
		    	{
		    		$rates2 = new Rate();
		    		$rate2 = new Rate();
		    		$rate2->lot_id = $post["Rate"]['lot_id'];
		    		$rate2->price = 0;
			    	$currentPrice = 0;
		    	}
		    	else
		    	{
					$rate2 = clone $rates2[0];
			    	$currentPrice = $rates2[0]->price;
			    	
			    	foreach($rates2 as $value)
			    	{
						$user = UserSocial::findOne(['user_id'=>$value->user2_id]);
						if($user){
							$value->user2_id = 	Html::a($user['name'],  Url::to($user['link']), ['target' => '_blank']);
						}
						
					}
				}
				
		    	if($currentPrice<10){
					$rate2->price = $rate2->price+1;
				}elseif($currentPrice<100 and $currentPrice>=10){
					$rate2->price = $rate2->price+10;
				}elseif($currentPrice<600 and $currentPrice>=100){
					$rate2->price = $rate2->price+50;
				}elseif($currentPrice>=600){
					$rate2->price = $rate2->price+100;
				}
				
				$modelId = $this->findModelId($post["Rate"]['lot_id']);
				
				
				/* spevv s - Interests 	*/
				$categoryLot = CategoryLot::find()
					->select('category_id')
				    ->where(['lot_id' => $modelId->id])
				    ->all();
				    
				if($categoryLot !== null)
				{  
					if (($userInterests = UserInterests::findOne(['user_id' => $user2_id])) !== null) 
					{
						if($userInterests->interests)
						{
							$interestsArray = json_decode($userInterests->interests, TRUE);
							foreach($categoryLot as $key=>$value)
							{
								if(isset($interestsArray[$value->category_id]))
								{
									$interestsArray[$value->category_id] = $interestsArray[$value->category_id]+1;
								}
								else
								{
									$interestsArray[$value->category_id] = 1;
								}
							}
							$userInterests->interests = json_encode($interestsArray);
							$userInterests->save();
						}
						else
						{	
							foreach($categoryLot as $key=>$value)
							{
								$interestsArray[$value->category_id] = 1;
							}
							$userInterests->interests = json_encode($interestsArray);
							$userInterests->save();
						}
					}
					else
					{
						$userInterestsNew = new UserInterests();
						$userInterestsNew->user_id = $user2_id;
						foreach($categoryLot as $key=>$value)
						{
							$interestsArray[$value->category_id] = 1;
						}
						$userInterestsNew->interests = json_encode($interestsArray);
						$userInterestsNew->save();
					}
				}
				/* spevv f	*/
				
						//данные по соцсетям
				$string = Url::to('');
				if($string{0} == '/')
				{
					$st = substr(Url::to(''), 1);
					$url = Url::home(true).$st;
				}
				else
				{
					$url = Url::home(true).Url::to('');
				}
				if($modelId->image{0} == '/')
				{
					$st = substr($modelId->image, 1);
					$img = Url::home(true).$st;
				}
				else
				{
					$img = Url::home(true).$modelId->image;
				}
				
				$attachment =  '{"media": [{"type": "link","url": "'.$url.'"},{"type": "text", "text": "Моя ставка - '.$rate2->price.' р"}]}';
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
						'message' => 'Моя ставка - '.$rate2->price.' р',
						'title' => $modelId->name,
						'image' => $img,
					],
					'fb' => [
						'apiId' => Yii::$app->params['facebook']['clientId'],
						'name' => $modelId->name,
						'caption' => '',
						'link' => $url,
						'picture' => $img,
						'description' => 'Моя ставка - '.$rate2->price.' р',
					],
					'ok' => [
						//'apiId' => Yii::$app->params['odnoklassniki']['clientId'],
						//'clientSecret' => Yii::$app->params['odnoklassniki']['clientSecret'],
						'url' => $okUrl,
					],
				];
				
				return $this->renderPartial('_lotLeft', [
	            	 'model' => $modelId->toArray([], ['subjects','branchs', 'images']),
		             'rate' => $rate2,
		             'rates' =>  $rates2,
		             'count' =>  $count,
		             'currentPrice' => $currentPrice,
		             'social' => json_encode($social),
                ]);
			}
			else
			{
				 return $this->redirect(Url::previous());
				//var_dump('ups2');
				//die;
				//$modelId = $this->findModelId($post["Rate"]['lot_id']);
		        //return $this->redirect(['/lot/view', 'slug' => $modelId->slug]);
			}
			
		}
		else
		{
			//Url::previous();
			//var_dump(Url::previous());
			//die;
			//$modelId = $this->findModelId($post["Rate"]['lot_id']);
		    return $this->redirect(Url::previous());
		}
		
	}
    
    public function actionView($slug)
    {
	
		/*$time = '2015-06-17 15:48:10';
		$new_date = date('Y-m-d H:m:s', strtotime("+3 hours", strtotime($time)));
		var_dump($new_date);*/
	
	
		//поместить в finishLot
    	$checkLot = new CheckLot();
    	$checkLot->checkAndUpdate();
    	
    	//var_dump($checkLot->getNextRate(405, $offset = 1));
    	
    	

    	Url::remember();
    	
    	$modelId = $this->findModel($slug);
    	$lotRateStatistic = LotRateStatistic::find()->where(['lot_id'=>$modelId->id])->orderBy('id desc')->one();
    	$temp = 0;
    	if($lotRateStatistic){
			if($lotRateStatistic->status)
			{
				$rates = Rate::find()->where(['lot_id'=>$modelId->id])->andWhere(['refusal'=>0])->andWhere(['>',  'id', $lotRateStatistic->last_rate])->orderBy('price desc')->all();
    			$count = Rate::find()->where(['lot_id'=>$modelId->id])->andWhere(['refusal'=>0])->andWhere(['>',  'id', $lotRateStatistic->last_rate])->count();
    			$temp = 1;
			}
		}
		
		if(!$temp){
			$rates = Rate::find()->where(['lot_id'=>$modelId->id])->andWhere(['refusal'=>0])->orderBy('price desc')->all();
    		$count = Rate::find()->where(['lot_id'=>$modelId->id])->andWhere(['refusal'=>0])->count();
		}
		

    	$count = $count.' '.$this->plural($count,['ставка', 'ставки', 'ставок']);
    	
    	if(!$rates)
    	{
    		$rates = new Rate();
    		$rate = new Rate();
    		$rate->id = 0;
    		$rate->lot_id = $modelId->id;
    		$rate->price = 0;
	    	$currentPrice = 0;
    	}
    	else
    	{
			$rate = clone $rates[0];
	    	$currentPrice = $rates[0]->price;
	    	
	    	foreach($rates as $value)
	    	{
				$user = UserSocial::findOne(['user_id'=>$value->user2_id]);
				if($user){
					$value->user2_id = 	Html::a($user['name'],  Url::to($user['link']), ['target' => '_blank']);
				}
				
			}
		}
		
    	if($currentPrice<10){
			$rate->price = $rate->price+1;
		}elseif($currentPrice<100 and $currentPrice>=10){
			$rate->price = $rate->price+10;
		}elseif($currentPrice<600 and $currentPrice>=100){
			$rate->price = $rate->price+50;
		}elseif($currentPrice>=600){
			$rate->price = $rate->price+100;
		}	
		
		$contact = new ContactForm();
		
		//данные по соцсетям
		$string = Url::to('');
		if($string{0} == '/')
		{
			$st = substr(Url::to(''), 1);
			$url = Url::home(true).$st;
		}
		else
		{
			$url = Url::home(true).Url::to('');
		}
		if($modelId->image{0} == '/')
		{
			$st = substr($modelId->image, 1);
			$img = Url::home(true).$st;
		}
		else
		{
			$img = Url::home(true).$modelId->image;
		}
		
		Yii::$app->opengraph->set([
		    'title' => $modelId->name,
		    'description' => $modelId->meta_description,
		    'image' => $img,
		]);
		
		//$attachment =  '{"media": [{"type": "link","url": "http://yii.awam-it.ru"},{"type": "text", "text": "hello world!"}]}';
		$attachment =  '{"media": [{"type": "link","url": "'.$url.'"},{"type": "text", "text": "Моя ставка - '.$rate->price.' р"}]}';
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
				'message' => 'Моя ставка - '.$rate->price.' р',
				'title' => $modelId->name,
				'image' => $img,
			],
			'fb' => [
				'apiId' => Yii::$app->params['facebook']['clientId'],
				'name' => $modelId->name,
				'caption' => '',
				'link' => $url,
				'picture' => $img,
				'description' => 'Моя ставка - '.$rate->price.' р',
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
		
		if($modelId->remaining_time > Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss'))
		{
			$lotLeft = $this->renderPartial('_lotLeft', [
            	'model' => $modelId->toArray([], ['subjects','branchs', 'images']),
                'rate' => $rate,
	            'rates' =>  $rates,
	            'count' =>  $count,
	            'currentPrice' => $currentPrice,
	            'social' => json_encode($social),
            ]);
		}
		else
		{
			$lotLeft = $this->renderPartial('_lotLeftNoactive', [
            	'model' => $modelId->toArray([], ['subjects','branchs', 'images']),
                'rate' => $rate,
	            'rates' =>  $rates,
	            'count' =>  $count,
	            'currentPrice' => $currentPrice,
            ]);
		}
		
		
        return $this->render('view', [
            'model' => $modelId->toArray([], ['subjects','branchs', 'images']),
            'lotLeft' => $lotLeft,   
            'contact' => $this->renderPartial('_contact', [
                'model' => $contact,
            ]),
            'share' => $share,
        ]);
    }



	public function plural($n, $forms){
		
		    $plural = 0;
		    if ($n % 10 == 1 && $n % 100 != 11) {
		      $plural = 0;
		    } else {
		      if (($n % 10 >= 2 && $n % 10<=4) && ($n % 100 < 10 || $n % 100 >= 20)) {
		        $plural = 1;
		      } else {
		        $plural = 2;
		      }
		    }
		    return $forms[$plural];
	}
	


    /**
     * Finds the Lot model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Lot the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelId($id)
    {
        if (($model = Lot::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    protected function findModel($slug)
    {
        if (($model = Lot::findOne(['slug' => $slug])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
