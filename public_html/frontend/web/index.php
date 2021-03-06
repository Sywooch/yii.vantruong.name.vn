<?php
$domain = $_SERVER['HTTP_HOST'];  
$dev = [
		'dev.codedao.info', 'songlamht.vn',
		'dev100.thaochip.com',
		'demo.intranet.dalaco.travel','demo2.intranet.dalaco.travel',
		'beta3.lenguyet.vn',
		'yii.vantruong.name.vn','aplus.codedao.info','choweb.com.vn',
		'babymart.net.vn',
		'demo3.dalaco.travel'
];
defined('YII_DEBUG') or define('YII_DEBUG', in_array($domain, $dev) ? true : false);
defined('YII_ENV') or define('YII_ENV', in_array($domain, $dev) ? 'dev' : 'prod');
defined('__ROOT_PATH__') or define('__ROOT_PATH__', dirname(__FILE__));
require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');
require(__DIR__ . '/../../common/functions/_function.php');
$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/main-local.php'),
    require(__DIR__ . '/../config/main.php'),
    require(__DIR__ . '/../config/main-local.php')
);
(new yii\web\Application($config))->run();