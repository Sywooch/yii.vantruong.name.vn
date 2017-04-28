<?php 
$config = [
		'modules' => [
				'acp' => [
						'class' => 'app\modules\acp\Module',
						//'defaultController' => 'default',
				],
				'admin' => [
						'class' => 'app\modules\admin\Module',
						//'defaultController' => 'default',
				], 
		],
		//'defaultRoute' =>'admin' ,
		'components' => [
				't'=>[
						'class'=>'yii\translate\TextTranslate'
				],
				'zii'=>[
						'class'=>'yii\zii\Zii'
				],
	        'request' => [
	            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
	            'cookieValidationKey' => '0Bgrt1XIMgtfPH4kBD_Y_UC06eQwq1uq',
	        	'enableCookieValidation' => true,
	        	'enableCsrfValidation' => true,
	        	//'cookieValidationKey' => 'some_random_key',
	        	'baseUrl'=>'',
	        	//'enableCsrfValidation'=>false,	
	        ],    		
    		'urlManager'=>[
    				'class' => 'yii\web\UrlManager',
    				'showScriptName' => false,
    				'enablePrettyUrl' => true,
    				'scriptUrl'=>'/index.php',
    				//'suffix'=>'.html',
    				'rules' => array(
    						''=>'site/index',
    						'<action:\w+>'=>'site/<action>',
    						'<alias:sajax>/<view>'=>'site/<alias>',
    						//'<action:\w+>/<view>'=>'site/<action>',
    						//''=>'site/sajax',
    						//'admin'=>'admin/default/index',
    						'site/<action>'=>'site/<action>',
    						'site/<action>/<view>'=>'site/<action>',
    						'site/<action>/<view>/<id:\d+>'=>'site/<action>',
    						'site/<action>/<view>/<url:\w+>'=>'site/<action>',
    						'site/<action>/<view>/<url:\w+>/<url2:\w+>'=>'site/<action>',
    						//'admin/<action>/<view>'=>'admin/<action>',
    						//'admin/<action>/<view>/<id:\d+>'=>'admin/<action>',
    						'gii'=>'gii/default/index',
    						'gii/<controller>'=>'gii/<controller>',
    						'gii/<controller>/<action>'=>'gii/<controller>/<action>',
    						'<module:\w+>'=>'<module>/default/index',
    						'<module:\w+><alias:index|default>'=>'<module>/default',
    						'<module:\w+>/<alias:login|logout|forgot>'=>'<module>/default/<alias>',
    						'<module:\w+>/<controller:\w+>'=>'<module>/<controller>',
    						'<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
                			'<module:\w+><controller:\w+>/<action:update|delete>/<id:\d+>' => '<module>/<controller>/<action>',
    						
    							
    						//'<controller>/<action>'=>'<controller>/<action>',
    						//		'<controller:\w+>/<action:\w+>'=>'site/index',
    						//		'<controller:\w+>'=>'site/index',
    						//		'login' => 'site/login',
    						//		'<controller:\w+>/<id:\d+>' => '<controller>/view',
    						//'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
    						//'<controller:\w+>/<action:\w+>' => '<controller>/<action>',    						 
    						 
    				),
    		],
    
		],
		 
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
