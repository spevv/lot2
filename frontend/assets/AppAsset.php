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
    	//"http://vk.com/js/api/openapi.js",
    	//"http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/md5.js",
    	'js/social.js',
    	'js/owl.carousel.min.js',
    	'js/script.js',
    	'js/jquery.scrollUp.min.js',
    ];
    public $depends = [
    	'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\validators\ValidationAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'yii2mod\alert\AlertAsset',
       	//
       	//'uran1980\yii\assets\jQueryEssential\JqueryEssentialAsset',
       	'metalguardian\fotorama\FotoramaAsset',
        'nodge\eauth\assets\WidgetAssetBundle',
        'nirvana\infinitescroll\InfiniteScrollAsset',
        'kartik\spinner\SpinnerAsset',
        'dosamigos\multiselect\MultiSelectAsset',
        'russ666\widgets\CountdownAsset',
        'kartik\form\ActiveFormAsset',
        'yii\widgets\ActiveFormAsset',
        'yii\widgets\PjaxAsset',
    ];
}
