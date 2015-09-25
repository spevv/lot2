<?php
namespace frontend\controllers;

use Yii;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\User;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\Category;
use frontend\models\CategorySearch;
use frontend\models\CategoryLotSearch;
use yii\web\NotFoundHttpException;

use common\models\CategoryLot;
use common\models\Lot;
use frontend\models\LotSearch;

//use common\models\Lot;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use common\models\SubjectLot;
use common\models\BranchLot;
use common\models\GeobaseCity;
use common\models\Subject;
use common\models\CheckLot;
use common\models\Branch;
use common\models\Follower;
use common\models\Article;
//use frontend\models\ContactForm;
use yii\helpers\Url;

use common\models\Rate;
use common\models\LotRateStatistic;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */

    public function behaviors()
    {
        return [
            'eauth' => [
                // required to disable csrf validation on OpenID requests
                'class' => \nodge\eauth\openid\ControllerBehavior::className(),
                'only' => array('login'),
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            /*'auth' => [
                   'class' => 'yii\authclient\AuthAction',
                   'successCallback' => [$this, 'successCallback'],
               ],*/
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionCancelSubscription($email = null)
    {
        if($email)
        {
            //var_dump($email);
            $follower = Follower::find()->where(['mail' => $email])->one();
            if($follower)
            {
                $follower->delete();
                Yii::$app->getSession()->setFlash('success', 'Вы успешно отписались от подписки.');
                //редирект на стрницу с сообщением
            }
        }
        return $this->redirect('/',302);
    }
    
    public function actionFollower()
    {
        $model = new Follower();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                // form inputs are valid, do something here
                $model->date = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss');
                $model->save();
                $post = Yii::$app->request->post();
                //var_dump($post["Follower"]['mail']);
                $this->sendEmail($post["Follower"]['mail']);

                //Yii::$app->getSession()->setFlash('success', 'Вы успешно подписались!');
                return '<div class="success-follower">Вы успешно подписались!</div>';
            }
            else
            {
                //Yii::$app->getSession()->setFlash('success', 'Такой e-mail уже существует!');
                return '<div class="success-follower">Такой e-mail уже существует!</div>';
            }
        }
        else
        {
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    private function sendEmail($userEmail)
    {
        //var_dump(Yii::$app->params['emailText']['followerFirst']);
        //return TRUE;
        $checkLot = new CheckLot();
        $url = Yii::$app->urlManager->createAbsoluteUrl(['site/index']);
        Yii::$app->params['emailText']['followerFirst']['email'] = $userEmail;
        //Yii::$app->params['emailText']['followerFirst']['subject'] = sprintf(Yii::$app->params['emailText']['followerFirst']['subject'], $rate->lot['name']);
        Yii::$app->params['emailText']['followerFirst']['messege'] = sprintf(Yii::$app->params['emailText']['followerFirst']['messege'], $url);
        return $checkLot->sendEmail(Yii::$app->params['emailText']['followerFirst']);
    }

    public function actionGetRatesInfo()
    {
        $var = [36, 52, 38, 53, 37, 40, 49, 50];
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if($post['lots_id'])
            {
                $arrId = explode( ',', $post['lots_id'] );
                $retRates = [];
                foreach($arrId as $id)
                {
                    $rateState = $this->getRateState($id);
                    if($rateState)
                    {
                        //$retRates[] = $rateState[0];
                        $retRates[$rateState[0]->lot_id] = $rateState[0]->price;

                    }
                }

                $res = array(
                    'status' => true,
                    'rates' => $retRates,
                );
                return $res;

               /* $rates = Rate::find()->select(['lot_id', 'price'])->where(['lot_id'=>$arrId])->andWhere(['refusal' => 0])->orderBy('id desc')->all();

                if($rates){
                    // сформировать массив
                    $retRates = [];
                    foreach($rates as $rate)
                    {
                        $retRates[$rate->lot_id] = $rate->price;
                    }
                    $res = array(
                        'status' => true,
                        'rates' => $rates,
                    );
                    return $res;
                }
                else
                {
                    $res = array(
                        'status' => FALSE,
                        'rates' => 0,
                    );
                    return $res;
                }*/
            }

        }
    }


    private function getRateAndRetes($rates,$model_id)
    {
        if($rates)
        {
            return clone $rates[0];
        }
        return 	FALSE;
    }

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
        return Rate::find()->where(['lot_id'=>$modelId_id])->andWhere(['refusal'=>0])->orderBy('id desc')->all();
    }


    public function actionIndex()
    {
    	
        $searchModel = new LotSearch();
        $dataProvider2 = $searchModel->search(null, false);
        
        $search =  $this->renderPartial('_search', [
            'model' => $searchModel,
            'action' => ['site/index'],
            'region' => $this->getActiveRegions(),
            'subjects' => $this->getActiveSubjects(),
            'branchs' => $this->getActiveBranchs()
        ]);
        
        $siteInfo = (object) [
            'name' => Yii::$app->params['siteInfo']['title'],
            'meta_description' => Yii::$app->params['siteInfo']['description'],
            'meta_keyword' => Yii::$app->params['siteInfo']['keyword'],
        ];

        $Contact = new ContactForm();
        $contact = $this->renderPartial('_contact', [
            'model' => $Contact,
        ]);
        
        
        if(Yii::$app->session->get('city'))
        {
            //var_dump('city');
            $searchModel['city_id'] = Yii::$app->session->get('city');

            $param["LotSearch"]["city_id"][0] = Yii::$app->session->get('city');
            Yii::$app->session->remove('city');
            $dataProvider = $searchModel->search($param, true);


            $search =  $this->renderPartial('_search', [
                'model' => $searchModel,
                'action' => ['/site/index'],
                'region' => $this->getActiveRegions(),
                'subjects' => $this->getActiveSubjects(),
                'branchs' => $this->getActiveBranchs()
            ]);
            $activeBlockLot =  $this->renderPartial('_active-block-lot', [
                'categoryInfo' => $this->getCategoty(),
                'search' => $search,
                'dataProvider' => $dataProvider,
            ]);

            return $this->render('index', [
                'dataProvider2' => $dataProvider2,
                // 'categoryInfo' => $this->getCategoty(),
                'activeBlockLot' => $activeBlockLot,
                'model'=> $siteInfo,
            ]);
        }

        if(Yii::$app->request->isPost)
        {
            //var_dump('is post');
            // die;
            $this->getCountScript();
            Yii::$app->session->set('searchData', Yii::$app->request->post());
            $dataProvider = $searchModel->search(Yii::$app->request->post(), true);

            $search =  $this->renderAjax('_search', [
                'model' => $searchModel,
                'action' => ['/site/index'],
                'region' => $this->getActiveRegions(),
                'subjects' => $this->getActiveSubjects(),
                'branchs' => $this->getActiveBranchs()
            ]);

            return $this->renderPartial('_active-block-lot', [
                'categoryInfo' => $this->getCategoty(),
                'search' => $search,
                'dataProvider' => $dataProvider,
            ]);
        }
        elseif(Yii::$app->request->isPjax)
        {
        	//$this->getView()->registerJsFile("js/register4.js", ["position" => \yii\web\View::POS_END]);
			$this->getCountScript();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);
            $search =  $this->renderAjax('_search', [
                'action' =>['/site/index'],
                'model' => $searchModel,
                'region' => $this->getActiveRegions(),
                'subjects' => $this->getActiveSubjects(),
                'branchs' => $this->getActiveBranchs()
            ]);

            return $this->renderPartial('_active-block-lot', [
                'categoryInfo' => $this->getCategoty(),
                'search' => $search,
                'dataProvider' => $dataProvider,
            ]);
        }
        else
        {
            Yii::$app->opengraph->set([
                'title' => Yii::$app->params['siteInfo']['title'],
                'description' => Yii::$app->params['siteInfo']['description'],
                'image' => Yii::$app->params['siteInfo']['image'],
            ]);

            Yii::$app->session->remove('searchData');
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);

            $activeBlockLot =  $this->renderPartial('_active-block-lot', [
                'categoryInfo' => $this->getCategoty(),
                'search' => $search,
                'dataProvider' => $dataProvider,
            ]);

            return $this->render('index', [
                'dataProvider2' => $dataProvider2,
                //'categoryInfo' => $this->getCategoty(),
                'activeBlockLot' => $activeBlockLot,
                'model'=> $siteInfo,
                'contact'=> $contact,
                'urls'=> $this->getArticleUrls(),

            ]);
        }
    }
    
    
    private function getCountScript()
    {

$js = <<< JS
	//console.log('in Register 2');

 $('.load-time').each(
        function( i, val ) {
            //classTime = $(this).attr('class');
            time = $(this).html();
            $(this).countdown(time, function(event) {
                $(this).text(
                    event.strftime('%D %H %M')
                );
            }).on('finish.countdown',function() {
            	location.reload();
            })
            .on('update.countdown', function(event) {
			   
			   
	var format = "";
				                if(event.offset.minutes > 0){
				                	if(event.offset.minutes >= 10)
				                	{
										format = "<div class=\"wrt\"><span class=\"min\">%-M</span></div> " + format;
									}
									else
									{
										format = "<div class=\"wrt\"><span class=\"min\">0%-M</span></div> " + format;
									}
				                	 
				                }
				                else
				                {
									format = "<div class=\"wrt\"><span class=\"min\">00</span></div> " + format;
								}
				                if(event.offset.hours > 0)
				                {
				                	if(event.offset.hours  >= 10)
				                	{
										format = "<div class=\"wrt\"><span class=\"houver\">%-H</span></div> "  + format;
									}
									else
									{
										format = "<div class=\"wrt\"><span class=\"houver\">0%-H</span></div> "  + format;
									}
				                	 
				                } 
				                 else
				                {
									format = "<div class=\"wrt\"><span class=\"houver\">00</span></div> " + format;
								}
				                /*if(event.offset.days > 0)
				                {
				                	if(event.offset.days  >= 10)
				                	{
										format = "<div class=\"wrt\"><span class=\"day\">%-D</span></div> " + format;
									}
									else
									{
										format = "<div class=\"wrt\"><span class=\"day\">0%-D</span></div> " + format;
									}
				                	 
				                }  */
				                if(event.offset.weeks  > 0)
				                {
				                	var days = ((event.offset.weeks*7)+event.offset.days);
				                	if(days  >= 10)
				                	{
				                		var format = "";
						                if(event.offset.minutes > 0){
						                	if(event.offset.minutes >= 10)
						                	{
												format = "<div class=\"wrt\"><span class=\"min\">%-M</span></div> " + format;
											}
											else
											{
												format = "<div class=\"wrt\"><span class=\"min\">0%-M</span></div> " + format;
											}
						                	 
						                }
						                else
						                {
											format = "<div class=\"wrt\"><span class=\"min\">00</span></div> " + format;
										}
						                if(event.offset.hours > 0)
						                {
						                	if(event.offset.hours  >= 10)
						                	{
												format = "<div class=\"wrt\"><span class=\"houver\">%-H</span></div> "  + format;
											}
											else
											{
												format = "<div class=\"wrt\"><span class=\"houver\">0%-H</span></div> "  + format;
											}
						                	 
						                } 
						                else
						                {
											format = "<div class=\"wrt\"><span class=\"houver\">00</span></div> " + format;
										}
										 format =  "<div class=\"wrt\"><span class=\"day\">"+days +"</span></div> "+ format ;
									}
									else
									{
										var format = "";
						                if(event.offset.minutes > 0){
						                	if(event.offset.minutes >= 10)
						                	{
												format = "<div class=\"wrt\"><span class=\"min\">%-M</span></div> " + format;
											}
											else
											{
												format = "<div class=\"wrt\"><span class=\"min\">0%-M</span></div> " + format;
											}
						                	 
						                }
						                else
						                {
											format = "<div class=\"wrt\"><span class=\"min\">00</span></div> " + format;
										}
						                if(event.offset.hours > 0)
						                {
						                	if(event.offset.hours  >= 10)
						                	{
												format = "<div class=\"wrt\"><span class=\"houver\">%-H</span></div> "  + format;
											}
											else
											{
												format = "<div class=\"wrt\"><span class=\"houver\">0%-H</span></div> "  + format;
											}
						                	 
						                }
						                 else
						                {
											format = "<div class=\"wrt\"><span class=\"houver\">00</span></div> " + format;
										}
										format =  "<div class=\"wrt\"><span class=\"day\">0"+days  +"</span></div> "+ format ;
									}
				                	 
				                }
				                else
				                {
									if(event.offset.days > 0)
					                {
					                	if(event.offset.days  >= 10)
					                	{
											format = "<div class=\"wrt\"><span class=\"day\">%-D</span></div> " + format;
										}
										else
										{
											format = "<div class=\"wrt\"><span class=\"day\">0%-D</span></div> " + format;
										}
					                	 
					                }
					                else
					                {
										format = "<div class=\"wrt\"><span class=\"day\">00</span></div> " + format;
									} 
								}
								
								if(event.offset.weeks == 0 && event.offset.days == 0 && event.offset.hours < 25 ) {
									format = "";
									if(event.offset.seconds > 0)
									{
					                	if(event.offset.seconds >= 10)
					                	{
											format = "<div class=\"wrt\"><span class=\"sec\">%-S</span></div> " + format;
										}
										else
										{
											format = "<div class=\"wrt\"><span class=\"sec\">0%-S</span></div> " + format;
										}
					                	 
					                }
					                else
					                {
										format = "<div class=\"wrt\"><span class=\"sec\">00</span></div> " + format;
									}
									if(event.offset.minutes > 0)
									{
					                	if(event.offset.minutes >= 10)
					                	{
											format = "<div class=\"wrt\"><span class=\"min\">%-M</span></div> " + format;
										}
										else
										{
											format = "<div class=\"wrt\"><span class=\"min\">0%-M</span></div> " + format;
										}
					                	 
					                }
					                else
					                {
										format = "<div class=\"wrt\"><span class=\"min\">00</span></div> " + format;
									}
					                if(event.offset.hours > 0)
					                {
					                	if(event.offset.hours  >= 10)
					                	{
											format = "<div class=\"wrt\"><span class=\"houver\">%-H</span></div> "  + format;
										}
										else
										{
											format = "<div class=\"wrt\"><span class=\"houver\">0%-H</span></div> "  + format;
										}
					                	 
					                } 
					                 else
					                {
										format = "<div class=\"wrt\"><span class=\"houver\">00</span></div> " + format;
									}
				                } 
				                
								if(event.offset.weeks == 0 && event.offset.days == 0 && event.offset.hours == 0 && event.offset.minutes  < 60 ) {
									format = "";
									if(event.offset.seconds > 0)
									{
					                	if(event.offset.seconds >= 10)
					                	{
											format = "<div class=\"wrt\"><span class=\"sec\">%-S</span></div> " + format;
										}
										else
										{
											format = "<div class=\"wrt\"><span class=\"sec\">0%-S</span></div> " + format;
										}
					                	 
					                }
					                else
					                {
										format = "<div class=\"wrt\"><span class=\"sec\">00</span></div> " + format;
									}
									if(event.offset.minutes > 0)
									{
					                	if(event.offset.minutes >= 10)
					                	{
											format = "<div class=\"wrt\"><span class=\"min\">%-M</span></div> " + format;
										}
										else
										{
											format = "<div class=\"wrt\"><span class=\"min\">0%-M</span></div> " + format;
										}
					                	 
					                }
					                else
					                {
										format = "<div class=\"wrt\"><span class=\"min\">00</span></div> " + format;
									}
				                } 
				                if(event.offset.weeks == 0 && event.offset.days == 0 && event.offset.hours == 0 && event.offset.minutes == 0 && event.offset.seconds < 60) {
				                    format = "<div class=\"wrt\"><span class=\"sec\">%-S</span></div>";
				                }

				                $(this).html(event.strftime(format));
			   
			   
			   
			});
			
            $(this).removeClass();
            //console.log(val);
        }
    );
JS;
        	return $this->getView()->registerJs($js);
	}
    

    /**
     *
     *
     * @return urls article #10
     */
    private function getArticleUrls()
    {
        $urls['organizations'] = [];
        $article = Article::find()->where(['id' => 10])->one();
        if($article)
        {
            $urls['organizations'] = ['article/view', 'article' => $article->slug];
        }
        return $urls;
    }

    //return public category
    private function getCategoty()
    {
        $items = Yii::$app->cache->get('getCategoty-new');
        if ($items === false)
        {
            $categoryInfo = Category::find()
                ->where(['public' => 1])
                ->orderBy('priority')
                ->all();
            foreach($categoryInfo as $key => $value){
                $items[$key]['label'] = $value['name'];
                //$items[$key]['linkOptions'] =  ['class'=> $value['slug']];
                $items[$key]['url'] = ['category/view', 'category' => $value['slug']];
            }
            Yii::$app->cache->set('getCategoty-new', $items);
        }
        return $items;
    }

    // return all active subjects
    private function getActiveSubjects()
    {
        /*$subjectLots = SubjectLot::find()->all();
        if($subjectLots)
        {

            foreach($subjectLots as $key => $subjectLot)
            {
                if($subjectLot->lot)
                {
                    if($subjectLot->lot->public != 1)
                    {
                        unset($subjectLots[$key]);
                    }
                    if($subjectLot->lot->remaining_time < Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss'))
                    {
                        unset($subjectLots[$key]);
                    }
                }
                else
                {
                    unset($subjectLots[$key]);
                }
            }

            $subjectLot = ArrayHelper::map($subjectLots, 'id', 'subject_id');
            $subjectLot = array_unique($subjectLot);
            return ArrayHelper::map(Subject::findAll($subjectLot), 'id', 'name');
        }
        return false;*/

        $subjects = Subject::find()->all();
        if($subjects)
        {
            return ArrayHelper::map($subjects, 'id', 'name');
        }
        return false;

    }

    // return all active Branchs
    private function getActiveBranchs()
    {
        /*$branchLots = BranchLot::find()->all();
        if($branchLots)
        {
            foreach($branchLots as $key => $branchLot)
            {
                if($branchLot->lot)
                {
                    if($branchLot->lot->public != 1)
                    {
                        unset($branchLots[$key]);
                    }
                    if($branchLot->lot->remaining_time < Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss'))
                    {
                        unset($branchLots[$key]);
                    }
                }
                else
                {
                    unset($branchLots[$key]);
                }
            }

            $branchLot = ArrayHelper::map($branchLots, 'id', 'branch_id');
            $branchLot = array_unique($branchLot);
            return ArrayHelper::map(Branch::findAll($branchLot), 'id', 'name');
        }
        return false;*/

        $branchs = Branch::find()->all();
        if($branchs)
        {
            return ArrayHelper::map($branchs, 'id', 'name');
        }
        return false;
    }

    //return region
    private function getActiveRegions()
    {
        $lot = ArrayHelper::map(Lot::find()->where(['public'=> 1])->andWhere(['>',  'remaining_time', Yii::$app->formatter->asDate('now', 'yyyy-MM-dd HH:mm:ss')])->all(), 'id', 'city_id');
        $lot = array_unique($lot);
        return  ArrayHelper::map(GeobaseCity::findAll($lot), 'id', 'name');
    }
    

    public function actionLogin()
    {

        $serviceName = Yii::$app->getRequest()->getQueryParam('service');

        if (isset($serviceName)) {
            /** @var $eauth \nodge\eauth\ServiceBase */
            $eauth = Yii::$app->get('eauth')->getIdentity($serviceName);

            $eauth->setRedirectUrl(Yii::$app->getUser()->getReturnUrl());
            $eauth->setCancelUrl(Yii::$app->getUrlManager()->createAbsoluteUrl('site/login'));

            try {
                if ($eauth->authenticate()) {
                    // var_dump($eauth->getAttributes()); exit;

                    $identity = User::findByEAuth($eauth);
                    //var_dump($identity);
                    //die;
                    //Yii::$app->getUser()->login($identity);
                    //Yii::$app->getUser()->login($identity, 3600*24*30);
                    Yii::$app->user->login($identity, 3600*24*30);
                    // special redirect with closing popup window
                    
                    //$this->getView()->registerJsFile(Yii::$app->request->baseUrl.'/js/register1.js');
                    
                    //$this->render('error');
                    //var_dump(Yii::$app->request->baseUrl.'js/register1.js');
                    //die;
                    Yii::$app->getSession()->setFlash('login', 's-'.$serviceName);

                    $eauth->redirect(Yii::$app->request->referrer); //"/lot/lot-4.html"
                }
                else {
                    // close popup window and redirect to cancelUrl
                    $eauth->cancel();
                }
            }
            catch (\nodge\eauth\ErrorException $e) {
                // save error to show it later
                Yii::$app->getSession()->setFlash('error', 'EAuthException: '.$e->getMessage());
                Yii::$app->getSession()->setFlash('login', 'f-'.$serviceName);
                // close popup window and redirect to cancelUrl
//              $eauth->cancel();
                $eauth->redirect($eauth->getCancelUrl());
            }
        }
        
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }

        // default authorization code through login/password ..
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();

    }

    /* public function actionContact()
     {
         $model = new ContactForm();
         if ($model->load(Yii::$app->request->post()) && $model->validate()) {
             if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                 Yii::$app->session->setFlash('success', 'Спасибо, что написали нам. Мы скоро ответим вам.');
             } else {
                 Yii::$app->session->setFlash('error', 'Ошибка отправи email.');
             }

             return $this->refresh();
         } else {
             return $this->render('contact', [
                 'model' => $model,
             ]);
         }
     }*/
    
    public function actionContact()
    {
        $model = new ContactForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->subject = "Хотим стать вашим партнером - ".$model->subject;
            if ($model->sendFromEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Поздравляем! Ваше сообщение успешно отправлено.');
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось отправить сообщение.');
            }
            $Contact = new ContactForm();
            return $this->renderPartial('_contact', [
                'model' => $Contact,
            ]);
        }
    }
    


    /* public function actionAbout()
     {
         return $this->render('about');
     }*/
    

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

}
