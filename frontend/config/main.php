<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
	'name' => 'Интернет-аукцион бизнес-образования',
    'id' => 'spevvlot',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    
    'homeUrl' => '/',
    
    'components' => [
    	/*'assetManager' => [
            'bundles' => [
                'all' => [
                    'class' => 'yii\web\AssetBundle',
                    'basePath' => '@webroot/assets',
                    'baseUrl' => '@web/assets',
                    'css' => ['css/all-da3700e020e038602699e51ed32bdaa9.css'],
                    'js' => [
                    	"http://vk.com/js/api/openapi.js", 
                		"http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/md5.js",
                    	'js/all-964688fba33e775865cd35f2d67bc42c.js'
                    ],
                ],
                
		       //'uran1980\yii\assets\jQueryEssential\JqueryEssentialAsset' => ['css' => [], 'js' => [], 'depends' => []],
		        'yii2mod\alert\AlertAsset' => ['css' => [], 'js' => [], 'depends' => ['all']],
		        'dosamigos\multiselect\MultiSelectAsset' => ['css' => [], 'js' => [], 'depends' => ['all']],
		       	'metalguardian\fotorama\FotoramaAsset' => ['css' => [], 'js' => [], 'depends' => ['all']],
		        'nodge\eauth\assets\WidgetAssetBundle' => ['css' => [], 'js' => [], 'depends' => ['all']],
		        'nirvana\infinitescroll\InfiniteScrollAsset' => ['css' => [], 'js' => [], 'depends' => ['all']],
		        'kartik\spinner\SpinnerAsset' => ['css' => [], 'js' => [], 'depends' => ['all']],
		        'russ666\widgets\CountdownAsset' => ['css' => [], 'js' => [], 'depends' => ['all']],
		        'kartik\form\ActiveFormAsset' => ['css' => [], 'js' => [], 'depends' => ['all']],
		        'yii\widgets\ActiveFormAsset' => ['css' => [], 'js' => [], 'depends' => ['all']],
		        'yii\widgets\PjaxAsset' => ['css' => [], 'js' => [], 'depends' => ['all']],
		        
		        
		        'yii\bootstrap\BootstrapAsset' => ['css' => [], 'js' => [], 'depends' => ['all']],
		        'yii\bootstrap\BootstrapPluginAsset' => ['css' => [], 'js' => [], 'depends' => ['all']],
		        'yii\web\YiiAsset'  => ['css' => [], 'js' => [], 'depends' => ['all']],
		        'yii\validators\ValidationAsset'  => ['css' => [], 'js' => [], 'depends' => ['all']],
		        'yii\web\JqueryAsset'  => ['css' => [], 'js' => [], 'depends' => ['all']],
		        'frontend\assets\AppAsset' => [
                	'css' => [], 
                	'js' => [], 
                	'depends' => ['all']
                ],
            ],
        ],*/
    
    	'request' => [
            'baseUrl' => '',
            'class' => 'yii\web\Request', // поставил на время 22.07
        ],
        'opengraph' => [
	        'class' => 'dragonjet\opengraph\OpenGraph',
	    ],
	    
	   /* 'session' => [ // влияет на загрузку изображений, что бы сработало, нужно закоментить, а потом разкоментить
	        'name' => 'FRONTENDSESSID',
	        'cookieParams' => [
	            'path' => '/',
	        ],
	    ],
	    */
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
            	[
            		'pattern' => 'lot/get-rate-info',
                    'route' => 'lot/get-rate-info',
                    'suffix' => ''
            	],
                [
                    'pattern' => 'site/get-rates-info',
                    'route' => 'site/get-rates-info',
                    'suffix' => ''
                ],
            	[
            		'pattern' => 'cancel-subscription/<email>',
                    'route' => 'site/cancel-subscription',
                    'suffix' => ''
            	],
            	[
            		'pattern' => 'account/<action>',
                    'route' => 'account/<action>',
                    'suffix' => ''
            	],
            	[
            		'pattern' => 'comment/<id>',
                    'route' => 'comment/index',
                    'suffix' => ''
            	],
            	[
            		'pattern' => 'comments',
                    'route' => 'comment/comments',
                    'suffix' => '.html'
            	],
            	
            	/*[
            		'pattern' => 'pay/<action>',
                    'route' => 'pay/<action>',
                    'suffix' => ''
            	],*/
            	[
            		'pattern' => 'pay/payment-failure',
                    'route' => 'pay/payment-failure',
                    'suffix' => ''
            	],
            	[
            		'pattern' => 'pay/payment-ok',
                    'route' => 'pay/payment-ok',
                    'suffix' => ''
            	],
            	[
            		'pattern' => 'pay/<id>',
                    'route' => 'pay/index',
                    'suffix' => ''
            	],
            	/*[
            		'pattern' => 'thanks',
                    'route' => 'pay/thanks',
                    'suffix' => ''
            	],*/
            	[
            		'pattern' => 'lot/finish-lot',
                    'route' => 'lot/finish-lot',
                    'suffix' => ''
            	],
            	[
            		'pattern' => 'lot/contact',
                    'route' => 'lot/contact',
                    'suffix' => ''
            	],
            	[
            		'pattern' => 'article/contact',
                    'route' => 'article/contact',
                    'suffix' => ''
            	],
            	[
            		'pattern' => 'lot/rate',
                    'route' => 'lot/rate',
                    'suffix' => ''
            	],
            	[
            		'pattern' => 'site/login',
                    'route' => 'site/login',
                    'suffix' => ''
            	],
                [
                    'pattern' => '',
                    'route' => 'site/index',
                    'suffix' => ''
                ],
                [
                    'pattern' => 'articles',
                    'route' => 'article/index',
                    'suffix' => '.html'
                ],
                [
                    'pattern' => 'lots',
                    'route' => 'lot/index',
                    'suffix' => '.html'
                ],
               /* [
                    'pattern' => 'categories',
                    'route' => 'category/index',
                    'suffix' => '.html'
                ],*/
                [
                    'pattern' => '<category>',
                    'route' => 'category/view',
                    'suffix' => ''
                ],
                [
                    'pattern' => 'articles/<article>',
                    'route' => 'article/view',
                    'suffix' => '.html'
                ],
                [
                    'pattern' => 'lot/<slug>',
                    'route' => 'lot/view',
                    'suffix' => '.html'
                ],
               

                [
                    'pattern' => '<controller>/<action>/<id:\d+>',
                    'route' => '<controller>/<action>',
                    'suffix' => ''
                ],
                [
                    'pattern' => '<controller>/<action>',
                    'route' => '<controller>/<action>',
                    'suffix' => '.html'
                ],
                [
                    'pattern' => '<module>/<controller>/<action>/<id:\d+>',
                    'route' => '<module>/<controller>/<action>',
                    'suffix' => ''
                ],
                [
                    'pattern' => '<module>/<controller>/<action>',
                    'route' => '<module>/<controller>/<action>',
                    'suffix' => '.html'
                ],
            ],
            
        ],
        'cache' => array(
			'class' => 'yii\caching\FileCache',
		),
        'eauth' => array(
	            'class' => 'nodge\eauth\EAuth',
	            'popup' => true, // Use the popup window instead of redirecting.
	            'cache' => 'cache', // Cache component name or false to disable cache. Defaults to 'cache' on production environments.
	            'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
	            'services' => array( // You can change the providers and their classes.
                    'facebook' => array(
                        // register your app here: https://developers.facebook.com/apps/
                        'class' => 'frontend\models\social\FacebookOAuth2Service',
                        'clientId' => '1629410954003891',//'848319381869872', // '860300950671715', //'860398493995294',
                        'clientSecret' => '14d67798de91b18c3f2e393d473c97f9',//'db7acdbd30eadfb4647e46a64be0ee5f', //'078f0ef6a3682b6b659f8bbf32ae096c', // 'a50a97ca31c69603d73440e605ca906a',
                        'title' => '',
                    ),
                    'vkontakte' => array(
                        // register your app here: https://vk.com/editapp?act=create&site=1
                        'class' => 'frontend\models\social\VKontakteOAuth2Service',
                        'clientId' => '4922757', // '4923090',
                        'clientSecret' => 'g4VTjJiw97wko2AS8zjw', //'bijIsupnRxwKSf3VdkAH',
                        'title' => '',
                    ),
                    'odnoklassniki' => array(
                        // register your app here: http://dev.odnoklassniki.ru/wiki/pages/viewpage.action?pageId=13992188
                        // ... or here: http://www.odnoklassniki.ru/dk?st.cmd=appsInfoMyDevList&st._aid=Apps_Info_MyDev
                        // 'class' => 'nodge\eauth\services\extended\OdnoklassnikiOAuth2Service',
                        'class' => 'frontend\models\social\OdnoklassnikiOAuth2Service',
                        'clientId' => '1152714240',//'1137718016',
                        'clientSecret' => 'D612BA2E1497B9290FB45B45',//'6320D57ED9A1D769B992081B',
                        'clientPublic' => 'CBAHAGLFEBABABABA',//'CBAEDBNEEBABABABA',
                        'title' => '',
                    ),
	            ),
	        ),
        'user' => [
            'identityClass' => 'frontend\models\User',
            'enableAutoLogin' => true,
            'enableSession'  => true,
            //'autoRenewCookie' => true,
            // test 230715
            'identityCookie' => [
	            'name' => '_identity',
	            'path' => '/',
	            'httpOnly' => true,
	        ],
	        // end test
	        
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    
                    'logFile' => '@app/runtime/logs/eauth.log',
                    'categories' => array('nodge\eauth\*'),
                    'logVars' => []
                ],
                 [
		            'class' => 'yii\log\FileTarget',
		            'levels' => ['info','error', 'warning'],
		            'categories' => ['payment'],
		            'logFile' => '@frontend/runtime/logs/payment.log',
		            'maxFileSize' => 1024 * 2,
		            'maxLogFiles' => 50,
		            'logVars' => [],
		        ],
		         [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info', 'error', 'warning'],
                    'logFile' => '@console/runtime/logs/mainCron.log',
                    'categories' => ['mainCron'],
                    'logVars' => []
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info', 'error', 'warning'],
                    'logFile' => '@console/runtime/logs/delivery.log',
                    'categories' => ['delivery'],
                    'logVars' => []
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'i18n' => array(
            'translations' => array(
                'eauth' => array(
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@eauth/messages',
                ),
            ),
        ),
    ],
    'params' => $params,
];
