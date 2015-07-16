<?php

namespace frontend\controllers;

use Yii;
use common\models\Lot;
use frontend\models\LotSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
//use yii\filters\VerbFilter;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Response;
use common\models\UserInterests;
use frontend\models\ContactForm2;
use common\models\CategoryLot;
use common\models\CheckLot;
use common\models\Comment;
use common\models\Rate;
use common\models\LotRateStatistic;
use frontend\models\UserSocial;

//use common\models\Delivery;

/**
 * LotController implements the CRUD actions for Lot model.
 */
class LotController extends Controller
{
   /* public function behaviors()
    {
        return [
           
        ];
    }*/
    
    /**
     * Lists all Lot models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LotSearch();
		
		if(Yii::$app->request->isPost){
			$dataProvider = $searchModel->search(Yii::$app->request->post());
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
    }    
    
    public function actionContact($id)
    {  	
        $model = new ContactForm2();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Поздравляем! Ваше сообщение успешно отправлено.');
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось отправить сообщение.');
            }
            
            $lot =  $this->findModelId($id);
            return $this->renderPartial('_contact', [
                'model' => $this->getContactData($lot),
                'lotId' => $lot->id,
            ]);
        } 
    }
    
    public function actionFinishLot()
    {
		if (Yii::$app->request->isPost) {
			
			//поместить в finishLot
	    	$checkLot = new CheckLot();
	    	$checkLot->checkAndUpdate();
    	
	    	$post = Yii::$app->request->post();
	    	Yii::$app->response->format = Response::FORMAT_JSON;

	    	$identity = Yii::$app->getUser()->getIdentity();
		    if (isset($identity->profile)) 
		    {
		    	$user_id =  $identity->profile['service'].'-'.$identity->profile['id'];
		    	//$rate = Rate::find()->where(['lot_id'=>$post['lot_id']])->orderBy('price desc')->one();
		    	
		    	
		    	$rates = $this->getRateState($post['lot_id']);
	    		$ratesInfo = $this->getRateAndRetes($rates, $post['lot_id']);

		       	if($ratesInfo['rate']){
					if($user_id == $ratesInfo['rate']['user2_id'])
			    	{
						$res = array(
				            'success' => true,
				            'url' => Url::to(['pay/index', 'id'=>$ratesInfo['rate']['id']]),
				            
				        );
				        return $res;
					}
				}
		    	
		    }
		    return array(
		        'success' => false,
		    );
	    }
	} 
    
    public function actionGetRateInfo() 
    {
	    if (Yii::$app->request->isPost) {
	    	$post = Yii::$app->request->post();
	        Yii::$app->response->format = Response::FORMAT_JSON;
	        
	       	//$rate = Rate::find()->where(['lot_id'=>$post['lot_id']])->orderBy('price desc')->one();
	       	$rates = $this->getRateState($post['lot_id']);
    		$ratesInfo = $this->getRateAndRetes($rates, $post['lot_id']);

	       	if($ratesInfo['rate']){
				$res = array(
		            'success' => true,
		            'rate' => $ratesInfo['rate']['id'],
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
			$post = Yii::$app->request->post();
			$modelId = $this->findModelId($post["Rate"]['lot_id']);
			$rates = $this->getRateState($modelId->id);
			
			if(!$rates)
    		{
    			$rate = new Rate();
    			$rate->price=0;
    		}
    		else
    		{
				$rate = clone $rates[0];
			}
    		
			$identity = Yii::$app->getUser()->getIdentity();
		    if (isset($identity->profile)) 
		    {
		    	$user2_id =  $identity->profile['service'].'-'.$identity->profile['id'];
		    }
		    else
		    {
		        return $this->redirect(['/lot/view', 'slug' => $modelId->slug]);
			}
			
			if($post["Rate"]['price'] > $rate->price)
			{
				$model = new Rate();
				$model->time = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss');
				$model->price = $post["Rate"]['price'];
				$model->lot_id = $post["Rate"]['lot_id'];
				$model->user2_id = $user2_id;
				$model->refusal = 0;
				$model->save();
				
		    	$count = $this->getRateCount($modelId);
    			$rates2 = $this->getRateState($modelId->id);
		    	$ratesInfo = $this->getRateAndRetes($rates2, $modelId->id);
		    	//$this->slewRate($modelId->id);		    	
		    	$this->setInterests($modelId, $user2_id);
		    	$socialShare = $this->social($modelId, $ratesInfo['rate']);
				
				return $this->renderPartial('_lotLeft', [
	            	 'model' => $modelId->toArray([], ['subjects','branchs', 'images']),
		             'rate' => $ratesInfo['rate'],
		             'rates' =>  $ratesInfo['rates'],
		             'count' =>  $count,
		             'currentPrice' => $ratesInfo['currentPrice'],
		             'social' => json_encode($socialShare['social']),
		             'emailForm' => '',
                ]);
			}
		}
		
		return $this->redirect(Url::previous());
		
	}
    
    private function checkEmail()
    {
    	
    	$identity = Yii::$app->getUser()->getIdentity();
	    if (isset($identity->profile)) 
	    {
	    	$user2_id =  $identity->profile['service'].'-'.$identity->profile['id'];
	    	$user = UserSocial::find()->where(['user_id' => $user2_id])->one();
		    if($user)
		    {
				if(!$user->email)
				{
				    /*$userHtml = $this->renderPartial('_email-form', [
			            'user' => $user,
			        ]);*/
			        $userHtml = $this->renderPartial('@frontend/views/account/_email-form', [
			            'user' => $user,
			        ]);
				    
					return $userHtml;
				}
			}
	    }
		return '';
	}
    
    public function actionView($slug)   
    {
    	//$url = Url::to(['pay/index', 'id'=>415]);
    	//$url = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => 4654]);
    	//echo($url);
		//поместить в finishLot
    	//$checkLot = new CheckLot();
    	//$checkLot->checkAndUpdate();
    	//
		
		//$delivery = new Delivery();
		//$delivery->getUserInteres();
		//$delivery->getLotsInfo();
		



    	Url::remember();
    	
    	$modelId = $this->findModel($slug);
    	$count = $this->getRateCount($modelId);
    	$rates = $this->getRateState($modelId->id);
    	$ratesInfo = $this->getRateAndRetes($rates, $modelId->id);
		$socialShare = $this->social($modelId, $ratesInfo['rate']);
		
		$comments = $this->getComments($modelId->id);
		
		//var_dump($count);
		
		Yii::$app->opengraph->set([
		    'title' => $modelId->name,
		    'description' => $modelId->meta_description,
		    'image' => $this->getImage($modelId->image),
		]);
		
		if($modelId->remaining_time > Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss'))
		{
			$emailForm = $this->checkEmail();
			if($emailForm)
			{
				$checkEmail = '1';
			}
			else
			{
				$checkEmail = '';
			}
			
			$lotLeft = $this->renderPartial('_lotLeft', [
            	'model' => $modelId->toArray([], ['subjects','branchs', 'images']),
                'rate' => $ratesInfo['rate'],
	            'rates' =>  $ratesInfo['rates'],
	            'count' =>  $count,
	            'currentPrice' => $ratesInfo['currentPrice'],
	            'social' => json_encode($socialShare['social']),
	            'emailForm' => $emailForm,
	            'checkEmail' => $checkEmail,
            ]);
		}
		else
		{
			$lotLeft = $this->renderPartial('_lotLeftNoactive', [
            	'model' => $modelId->toArray([], ['subjects','branchs', 'images']),
                'rate' => $ratesInfo['rate'],
	            'rates' =>  $ratesInfo['rates'],
	            'count' =>  $count,
	            'currentPrice' => $ratesInfo['currentPrice'],
	            'emailForm' => '',
            ]);
		}
		
        return $this->render('view', [
            'model' => $modelId->toArray([], ['subjects','branchs', 'images']),
            'lotLeft' => $lotLeft,   
            'contact' => $this->renderPartial('_contact', [
                'model' => $this->getContactData($modelId),
                'lotId' => $modelId->id,
            ]),
            'share' => $socialShare['share'],
            'comments' => $comments,
        ]);
    }
    
    // формируем текст сообщения на почту
    private function getContactData($modelId = null)
    {
		$contact = new ContactForm2();
		$contact->subject = 'Тебе понравится!';		
		$contact->body = "Привет! Смотри, что я нашел.
		
Торги за «".$modelId->name."».
Давай поучаствуем в аукционе!";
			//<p>До окончания торгов: 1 день 1 час. Текущая цена: 1 800 руб.</p>
			
		return $contact;
	}
    
    private function getComments($lot_id)
    {
    	return Comment::find()->where(['lot_id' => $lot_id])->orderBy('creation_time desc')->all();
    	
	}
    
    // add user Interests
    private function setInterests($modelId, $user2_id)
    {
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
		return TRUE;
	}
    
    // создание новой ставки
    private function getRateAndRetes($rates,$model_id)
    {
		if(!$rates)
    	{
    		$rates = new Rate();
    		$rate = new Rate();
    		$rate->id = 0;
    		$rate->lot_id = $model_id;
    		$rate->price = 0;
	    	$currentPrice = 0;
    	}
    	else
    	{
			$rate = clone $rates[0];
	    	$currentPrice = $rates[0]->price;
	    	$rates = $this->changeRateUserInfo($rates);
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
		
		return 	[
			'currentPrice' => $currentPrice,
			'rates' => $rates,
			'rate' => $rate,
		];
	}
    
    // создание ссылки на пользователя
    private function changeRateUserInfo($rates)
    {
		foreach($rates as $rate)
    	{
    		$rate->user2_id = Html::a($rate->user['name'],  Url::to($rate->user['link']), ['target' => '_blank']);
		}
		return $rates;
	}
    
    // ставка была перебита
    private function slewRate($id)
    {
		$rate = Rate::find()->where(['lot_id'=>$id])->orderBy('price desc')->offset($offset)->one();
		if($rate)
		{
			$checkLot = new CheckLot();
			Yii::$app->params['emailText']['slewRate']['email'] = $rate->user['email'];
	    	$checkLot->sendEmail(Yii::$app->params['emailText']['slewRate']);
		}
		return false;
		
		
	}
    
    // получить ставки
    private function getRateState($modelId_id)
    {
		$lotRateStatistic = LotRateStatistic::find()->where(['lot_id'=>$modelId_id])->orderBy('id desc')->one();
		
    	if($lotRateStatistic){
			if($lotRateStatistic->status)
			{
				//var_dump($lotRateStatistic->last_rate);
				return Rate::find()->where(['lot_id'=>$modelId_id])->andWhere(['refusal'=>0])->andWhere(['>',  'id', $lotRateStatistic->last_rate])->orderBy('price desc')->all();
			}
			else
			{
				$lotRateStatisticNext = LotRateStatistic::find()->where(['lot_id'=>$modelId_id])->orderBy('id desc')->offset(1)->one();
				//var_dump($lotRateStatisticNext);
				if($lotRateStatisticNext)
				{
					return Rate::find()->where(['lot_id'=>$modelId_id])->andWhere(['refusal'=>0])->andWhere(['>',  'id', $lotRateStatisticNext->last_rate])->orderBy('id desc')->all();
				}
				
			}
		}
		return Rate::find()->where(['lot_id'=>$modelId_id])->andWhere(['refusal'=>0])->orderBy('price desc')->all();
	}
	
	// получить количество ставок
	private function getRateCount($modelId)
	{
		$lotRateStatistic = LotRateStatistic::find()->where(['lot_id'=>$modelId->id])->orderBy('id desc')->one();
		//var_dump($lotRateStatistic);
    	if($lotRateStatistic){
			if($lotRateStatistic->status)
			{
    			$count = Rate::find()->where(['lot_id'=>$modelId->id])->andWhere(['refusal'=>0])->andWhere(['>',  'id', $lotRateStatistic->last_rate])->count();
    			//var_dump($count);
    			return  $count.' '.$this->plural($count,['ставка', 'ставки', 'ставок']);
			}
			else
			{
				$lotRateStatisticNext = LotRateStatistic::find()->where(['lot_id'=>$modelId->id])->orderBy('id desc')->offset(1)->one();
				//var_dump($lotRateStatisticNext);
				if($lotRateStatisticNext)
				{
					$count = Rate::find()->where(['lot_id'=>$modelId->id])->andWhere(['refusal'=>0])->andWhere(['>',  'id', $lotRateStatisticNext->last_rate])->count();
    			//var_dump($count);
    			return  $count.' '.$this->plural($count,['ставка', 'ставки', 'ставок']);
				}
				
			}
		}
    	$count = Rate::find()->where(['lot_id'=>$modelId->id])->andWhere(['refusal'=>0])->count();
    	return $count.' '.$this->plural($count,['ставка', 'ставки', 'ставок']);
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
	private function getUrl()
    {
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
		return $url;
	}
    
    // return socil info
    private function social($modelId, $rate)
    {
    	$img = $this->getImage($modelId->image);
    	$url = $this->getUrl();
    	
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
		
		return ['social' => $social, 'share' => $share];
		
	}
    
	public function plural($n, $forms)
	{
		
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
       // if (($model = Lot::findOne(['slug' => $slug])) !== null) {
        if (($model = Lot::find()->where(['slug' => $slug])->andWhere(['public' => 1])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }
}
