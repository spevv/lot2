<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
            // enter optional module parameters below - only if you need to
            // use your own export download action or custom translation
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ]
    ],
    'homeUrl' => '/admin',
    'components' => [
       // 'urlManager' => [
            /*'enablePrettyUrl' => true,
            'showScriptName' => false,*/
       /* ],*/
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'request' => [
	        'csrfParam' => '_backendCSRF',
	        'csrfCookie' => [
	            'httpOnly' => true,
	            'path' => '/admin',
	        ],
	    ],
	    'user' => [
	    	'identityClass' => 'backend\models\User',
            'enableAutoLogin' => true,
	        'identityCookie' => [
	            'name' => '_backendIdentity',
	            'path' => '/admin',
	            'httpOnly' => true,
	        ],
	    ],
	   /* 'session' => [ // влияет на загрузку изображений, что бы сработало, нужно закоментить, а потом разкоментить
	        'name' => 'BACKENDSESSID',
	        'cookieParams' => [
	            'path' => '/admin',
	        ],
	    ],*/
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
