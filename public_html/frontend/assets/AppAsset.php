<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    public $css = [
    	 
       'themes/'. __MOBILE_TEMPLETE__ . __TEMP_NAME__ . '/css/site.min.css',
    ];
    public $js = [
    		__LIBS_DIR__ . '/c/js/base.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset', 
    ];
}
