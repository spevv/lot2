<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/owl.carousel.css',
    ];
    public $js = [
    	
    	"http://vk.com/js/api/openapi.js",
    	'js/owl.carousel.min.js',
    	'js/social.js',
    	'js/script.js',
    	
    	
    	//'js/social/vk.js',
    	//'js/social/facebook.js',
    	//'js/social/okru.js',
    	'js/jquery.scrollUp.min.js',
    	"http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/md5.js"
    	//'js/script.js', http://lot2.localhost/
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'uran1980\yii\assets\jQueryEssential\JqueryEssentialAsset',
        'yii2mod\alert\AlertAsset',
    ];
}
