<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    
    /*'request' => [
    	'hostInfo' => 'yii.awam-it.ru',
        'baseUrl' => 'yii.awam-it.ru',
        'class' => 'yii\web\request', // поставил на время 22.07
    ],*/
        
    'components' => [
	    'urlManager' => [
	     	'scriptUrl' => '',
	   	 	'baseUrl' => '',
	   	 	'hostInfo' => 'http://eduhot.biz/',
	   	 	
	   	 	'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
             'rules' => [
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
            		'pattern' => 'pay/<id>',
                    'route' => 'pay/index',
                    'suffix' => ''
            	],
                [
                    'pattern' => '',
                    'route' => 'site/index',
                    'suffix' => ''
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
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    //'logFile' => '@console/runtime/logs/mainCron.log',
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
                    'logFile' => '@console/runtime/logs/follower.log',
                    'categories' => ['follower'],
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
    ],
    'params' => $params,
];
