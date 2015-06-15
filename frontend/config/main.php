<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
	'name' => 'ИНТЕРНЕТ-АУКЦИОН БИЗНЕС-ОБРАЗОВАНИЯ',
    'id' => 'spevvlot',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    
    'homeUrl' => '/',
    'components' => [
    	'request' => [
            'baseUrl' => '',
        ],
        'opengraph' => [
	        'class' => 'dragonjet\opengraph\OpenGraph',
	    ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
            	//'login/<service:google|facebook|etc>' => 'site/login',
            	/*[
            		'pattern' => 'login/<service:google|facebook|etc>',
                    'route' => 'site/login',
                    'suffix' => ''
            	],*/
            	
            	/*[
            		'class' => 'yii\rest\UrlRule', 
            		'controller' => 'lot',
            		'patterns' => [
		                'PUT,PATCH {id}/update' => 'update',
		                'DELETE {id}/delete' => 'delete',
		                'GET,HEAD {id}' => 'view',
		                'POST {id}/create' => 'create',
		                'GET,HEAD' => 'index',
		                '{id}' => 'options',
		                '' => 'options',
		            ],
            	],*/
            	[
            		'pattern' => 'lot/get-rate-info',
                    'route' => 'lot/get-rate-info',
                    'suffix' => ''
            	],
            	[
            		'pattern' => 'lot/contact',
                    'route' => 'lot/contact',
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
                [
                    'pattern' => 'categories',
                    'route' => 'category/index',
                    'suffix' => '.html'
                ],
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
        'eauth' => array(
	            'class' => 'nodge\eauth\EAuth',
	            'popup' => true, // Use the popup window instead of redirecting.
	            'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache' on production environments.
	            'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
	            'httpClient' => array(
	                // uncomment this to use streams in safe_mode
	                //'useStreamsFallback' => true,
	            ),
	            'services' => array( // You can change the providers and their classes.
	                'facebook' => array(
	                    // register your app here: https://developers.facebook.com/apps/
	                    'class' => 'frontend\models\social\FacebookOAuth2Service',
	                    'clientId' => '848319381869872', // '860300950671715', //'848319381869872',
	                    'clientSecret' => 'db7acdbd30eadfb4647e46a64be0ee5f', //'078f0ef6a3682b6b659f8bbf32ae096c', // 'a50a97ca31c69603d73440e605ca906a',
	                    'title' => '',
	                ),
	                'vkontakte' => array(
	                    // register your app here: https://vk.com/editapp?act=create&site=1
	                    'class' => 'frontend\models\social\VKontakteOAuth2Service',
	                    'clientId' => '4923090', // '4923090', 4922757
	                    'clientSecret' => 'bijIsupnRxwKSf3VdkAH', //'bijIsupnRxwKSf3VdkAH', g4VTjJiw97wko2AS8zjw
	                    'title' => '',
	                ),
	                'odnoklassniki' => array(
	                    // register your app here: http://dev.odnoklassniki.ru/wiki/pages/viewpage.action?pageId=13992188
	                    // ... or here: http://www.odnoklassniki.ru/dk?st.cmd=appsInfoMyDevList&st._aid=Apps_Info_MyDev
	                   // 'class' => 'nodge\eauth\services\extended\OdnoklassnikiOAuth2Service',
	                    'class' => 'frontend\models\social\OdnoklassnikiOAuth2Service',
	                    'clientId' => '1137718016',
	                    'clientSecret' => '6320D57ED9A1D769B992081B',
	                    'clientPublic' => 'CBAEDBNEEBABABABA',
	                    'title' => '',
	                ),
	            ),
	        ),
        'user' => [
            'identityClass' => 'frontend\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    
                    'logFile' => '@app/runtime/logs/eauth.log',
                    'categories' => array('nodge\eauth\*'),
                    'logVars' => array()
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
