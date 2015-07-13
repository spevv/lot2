<?php
namespace frontend\controllers;

use Yii;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
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
use common\models\SubjectLot;
use common\models\BranchLot;
use common\models\GeobaseCity;
use common\models\Subject;
use common\models\CheckLot;
use common\models\Branch;

use frontend\models\User;
use yii\helpers\Url;

use common\models\Follower;
use iutbay\yii2kcfinder\KCFinder;
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
	    	return $this->redirect(Url::previous());
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

    public function actionIndex()
    {
	     /*Yii::$app->opengraph->set([
			    'title' => Yii::$app->params['siteInfo']['title'],
			    'description' =>Yii::$app->params['siteInfo']['description'],
			    'image' => Yii::$app->params['siteInfo']['image'],
			]);*/
		
		
		$this->setKCFINDER();
        $searchModel = new LotSearch();
        $dataProvider2 = $searchModel->search(null, false);
        
       // $searchModel['city_id'] = 710;
        
        $search =  $this->renderPartial('_search', [
        	'model' => $searchModel, 
        	'action' => ['/site/index'], 
        	'region' => $this->getActiveRegions(), 
        	'subjects' => $this->getActiveSubjects(), 
        	'branchs' => $this->getActiveBranchs()
        ]);
        
        $siteInfo = (object) [
				'name' => Yii::$app->params['siteInfo']['title'],
				'meta_description' => Yii::$app->params['siteInfo']['description'],
				'meta_keyword' => Yii::$app->params['siteInfo']['keyword'],
			];
        
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
			 	'search' => $search,
			 	'dataProvider' => $dataProvider,
	        ]);
			
			return $this->render('index', [
	            'dataProvider2' => $dataProvider2,
	            'categoryInfo' => $this->getCategoty(),
	            'activeBlockLot' => $activeBlockLot, 
	            'model'=> $siteInfo,
	        ]);  	
		}

		if(Yii::$app->request->isPost)
		{
			//var_dump('is post');
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
			 	'search' => $search,
			 	'dataProvider' => $dataProvider,
	        ]);
		}
		elseif(Yii::$app->request->isPjax)
        {
        	$dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);
        	$search =  $this->renderAjax('_search', [
        		'action' =>['/site/index'],
	        	'model' => $searchModel, 
	        	'region' => $this->getActiveRegions(), 
        		'subjects' => $this->getActiveSubjects(), 
        		'branchs' => $this->getActiveBranchs()
	        ]);
			
			return $this->renderPartial('_active-block-lot', [
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
			
			
			//var_dump('else post');	
			Yii::$app->session->remove('searchData');
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);
			
			$activeBlockLot =  $this->renderPartial('_active-block-lot', [
			 	'search' => $search,
			 	'dataProvider' => $dataProvider,
	        ]);
			
			return $this->render('index', [
	            'dataProvider2' => $dataProvider2,
	            'categoryInfo' => $this->getCategoty(),
	            'activeBlockLot' => $activeBlockLot,
	            'model'=> $siteInfo,
	            
	        ]);
		}
    }
    
    //return public category
    private function getCategoty()
    {
		$categoryInfo = Category::find()
		    ->where(['public' => 1])
		    ->orderBy('priority')
		    ->all();
		
		foreach($categoryInfo as $key => $value){
			$items[$key]['label'] = $value['name'];
			$items[$key]['linkOptions'] =  ['class'=> $value['slug']];
			$items[$key]['url'] = ['category/view', 'category' => $value['slug']];
		}
		return $items;
	}
	
	// return all active subjects
	private function getActiveSubjects()
	{
		$subjectLots = SubjectLot::find()->all();
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
		return false;
		
	}
	
	// return all active Branchs
	private function getActiveBranchs()
	{
		$branchLots = BranchLot::find()->all();
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
			return ArrayHelper::map(Subject::findAll($branchLot), 'id', 'name');
		}
		return false;
		
		
		/*foreach($branchLots as $key => $branchLot)
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
		
		$branchLot = ArrayHelper::map($branchLots, 'id', 'branch_id');
		$branchLot = array_unique($branchLot);
		return ArrayHelper::map(Branch::findAll($branchLot), 'id', 'name');*/
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
//                  var_dump($eauth->getIsAuthenticated(), $eauth->getAttributes()); exit;

                    $identity = User::findByEAuth($eauth);
                    Yii::$app->getUser()->login($identity);

                    // special redirect with closing popup window
                    $eauth->redirect(Url::previous()); //"/lot/lot-4.html"
                }
                else {
                    // close popup window and redirect to cancelUrl
                    $eauth->cancel();
                }
            }
            catch (\nodge\eauth\ErrorException $e) {
                // save error to show it later
                Yii::$app->getSession()->setFlash('error', 'EAuthException: '.$e->getMessage());

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

    public function actionContact()
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
    }
    
    private function setKCFINDER()
    {
		if(!Yii::$app->session->get('KCFINDER')){
			$kcfOptions = array_merge(KCFinder::$kcfDefaultOptions, [
			    'uploadURL' => Yii::getAlias('@uploadURL'), //'/uploads',
			    'uploadDir' => Yii::getAlias('@uploadDir'), //dirname(dirname(__DIR__)).'/uploads',
			    'access' => [
			        'files' => [
			            'upload' => true,
			            'delete' => true,
			            'copy' => true,
			            'move' => true,
			            'rename' => true,
			        ],
			        'dirs' => [
			            'create' => true,
			            'delete' => true,
			            'rename' => true,
			        ],
			    ],
			    'disabled' => false,
		        'denyZipDownload' => true,
		        'denyUpdateCheck' => true,
		        'denyExtensionRename' => true,
		        'theme' => 'default',
		        'types' => [  // @link http://kcfinder.sunhater.com/install#_types
		            'files' => [
		                'type' => '',
		            ],
		        ],
				    'access' => [
				        'files' => [
				            'upload' => true,
				            'delete' => true,
				            'copy' => true,
				            'move' => true,
				            'rename' => true,
				        ],
				        'dirs' => [
				            'create' => true,
				            'delete' => true,
				            'rename' => true,
				        ],
				    ],
				    'lang' => 'ru',
				    'thumbsDir' => 'thumbs',
			        'thumbWidth' => 100,
			        'thumbHeight' => 100,
			        'filenameChangeChars' => array(
					    " " => "_",
					    ":" => ".",
					   "Є"=>"YE","І"=>"I","Ѓ"=>"G","і"=>"i","№"=>"-","є"=>"ye","ѓ"=>"g",
					   "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
					   "Е"=>"E","Ё"=>"YO","Ж"=>"ZH",
					   "З"=>"Z","И"=>"I","Й"=>"J","К"=>"K","Л"=>"L",
					   "М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R",
					   "С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"X",
					   "Ц"=>"C","Ч"=>"CH","Ш"=>"SH","Щ"=>"SHH","Ъ"=>"'",
					   "Ы"=>"Y","Ь"=>"","Э"=>"E","Ю"=>"YU","Я"=>"YA",
					   "а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
					   "е"=>"e","ё"=>"yo","ж"=>"zh",
					   "з"=>"z","и"=>"i","й"=>"j","к"=>"k","л"=>"l",
					   "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
					   "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"x",
					   "ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"",
					   "ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
					   " "=>"_","—"=>"_",","=>"_","!"=>"_","@"=>"_",
					   "#"=>"-","$"=>"","%"=>"","^"=>"","&"=>"","*"=>"",
					   "("=>"",")"=>"","+"=>"","="=>"",";"=>"",":"=>"",
					   "'"=>"", "\""=>"","~"=>"","`"=>"","?"=>"","/"=>"",
					   "\\"=>"","["=>"","]"=>"","{"=>"","}"=>"","|"=>""
					),
			]);
			// Set kcfinder session options
			Yii::$app->session->set('KCFINDER', $kcfOptions);
		}
		return TRUE;	
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
