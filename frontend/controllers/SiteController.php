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
    
    
    
    public function actionFollower()
	{
	   // var_dump('sdfsfsfsdf');
	   // var_dump(Yii::$app->request->post());
	    //Yii::$app->formatter->asDate('now', 'yyyy-MM-dd hh:mm:ss')
	    $model = new Follower();

	    if ($model->load(Yii::$app->request->post())) {
	        if ($model->validate()) {
	            // form inputs are valid, do something here
	            $model->date = Yii::$app->formatter->asDate('now', 'yyyy-MM-dd hh:mm:ss');
	            $model->save();
	            //var_dump($model->mail);
	            // сделать сохранение
	            
	            return '<div class="success-follower">Вы успешно подписались!</div>';
	        }
	        else{
				 return '<div class="success-follower">Такой e-mail уже существует!</div>';
			}
	    }
	    else{
	    	return $this->redirect(Url::previous());
			 //return '<div class="success-follower">Такой e-mail уже существует!   2</div>';
		}
		
	   /* return $this->render('follower', [
	        'model' => $model,
	    ]);*/
	}

    /*public function successCallback($client)
    {
        $attributes = $client->getUserAttributes();
        // user login or signup comes here
    }*/

    public function actionIndex()
    {

	    /*return $this->render('follower', [
	        
	    ]);*/
	    
		    Yii::$app->opengraph->set([
			    'title' => 'ИНТЕРНЕТ-АУКЦИОН БИЗНЕС-ОБРАЗОВАНИЯ',
			    'description' => 'описание сайта',
			    'image' => 'http://lot2.localhost/image/bg1.png',
			]);

    	
    	
    	   	$kcfOptions = array_merge(KCFinder::$kcfDefaultOptions, [
		    'uploadURL' => '/uploads',
		    'uploadDir' => dirname(dirname(__DIR__)).'/www/uploads',
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
    	
    	

    	
    	
    	
    	
		$categoryInfo = Category::find()
		    ->where(['public' => 1])
		    ->orderBy('priority')
		    ->all();
		
		foreach($categoryInfo as $key => $value){
			$items[$key]['label'] = $value['name'];
			$items[$key]['linkOptions'] =  ['class'=> $value['slug']];
			$items[$key]['url'] = ['category/view', 'category' => $value['slug']];
		}

       /* return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'categoryInfo' => $items,
        ]);*/
        
        $lot = ArrayHelper::map(Lot::find()->all(), 'id', 'city_id');
		$lot = array_unique($lot);
		$region = ArrayHelper::map(GeobaseCity::findAll($lot), 'id', 'name');

		$subjectLot = ArrayHelper::map(SubjectLot::find()->all(), 'id', 'subject_id');
		$subjectLot = array_unique($subjectLot);
		$subjects = ArrayHelper::map(Subject::findAll($subjectLot), 'id', 'name');

		$branchLot = ArrayHelper::map(BranchLot::find()->all(), 'id', 'branch_id');
		$branchLot = array_unique($branchLot);
		$branchs = ArrayHelper::map(Branch::findAll($branchLot), 'id', 'name');
        
        $searchModel = new LotSearch();
        
        $dataProvider2 = $searchModel->search(null, false);
        
		if(Yii::$app->session->get('city')){
			$param["LotSearch"]["city_id"][0] = Yii::$app->session->get('city');
			Yii::$app->session->remove('city');
			$dataProvider = $searchModel->search($param, true);
			
	           return $this->render('index', [
	            'searchModel' => $searchModel,
	            'dataProvider' => $dataProvider,
	            'dataProvider2' => $dataProvider2,
	            'categoryInfo' => $items,
	            'region' => $region,
	            'subjects' => $subjects,
	            'branchs' => $branchs,
	            
	        ]);
	       	
		}
		
		if(isset(Yii::$app->request->queryParams['page']))
		{
			if(Yii::$app->session->get('searchData'))
			{
				$dataProvider = $searchModel->search(Yii::$app->session->get('searchData'), true);
		           return $this->render('index', [
		            'searchModel' => $searchModel,
		            'dataProvider' => $dataProvider,
		             'dataProvider2' => $dataProvider2,
		            'categoryInfo' => $items,
		            'region' => $region,
		            'subjects' => $subjects,
		            'branchs' => $branchs,
		            
		        ]);
			}
			else
			{
				$dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);
				return $this->render('index', [
		            'searchModel' => $searchModel,
		            'dataProvider' => $dataProvider,
		             'dataProvider2' => $dataProvider2,
		            'categoryInfo' => $items,
		            'region' => $region,
		            'subjects' => $subjects,
		            'branchs' => $branchs,
		            
		        ]);
			}
		}

		if(Yii::$app->request->isPost){
			
			Yii::$app->session->set('searchData', Yii::$app->request->post());
			$dataProvider = $searchModel->search(Yii::$app->request->post(), true);
			return $this->renderAjax('index', [
	            'searchModel' => $searchModel,
	            'dataProvider' => $dataProvider,
	             'dataProvider2' => $dataProvider2,
	            'categoryInfo' => $items,
	            'region' => $region,
	            'subjects' => $subjects,
	            'branchs' => $branchs,
	            
	        ]);
		}
		else
		{
			Yii::$app->session->remove('searchData');
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);
			return $this->render('index', [
	            'searchModel' => $searchModel,
	            'dataProvider' => $dataProvider,
	             'dataProvider2' => $dataProvider2,
	            'categoryInfo' => $items,
	            'region' => $region,
	            'subjects' => $subjects,
	            'branchs' => $branchs,
	            
	        ]);
		}
    	
    	
        /*return $this->render('index');*/
    }
    

	public function actionLogin() {
		//var_dump(Url::to(''));
		//var_dump(Url::previous());
		//die;
        $serviceName = Yii::$app->getRequest()->getQueryParam('service');
          //var_dump($serviceName); exit;

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


   /* public function actionLogin()
    {
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
    }*/

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
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

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
