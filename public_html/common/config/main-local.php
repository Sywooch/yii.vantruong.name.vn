<?php
return [ 
    'components' => [
    		'session' => [
    				//'class' => 'yii\web\DbSession',
    				// 'db' => 'mydb',  // the application component ID of the DB connection. Defaults to 'db'.
    				// 'sessionTable' => 'my_session', // session table name. Defaults to 'session'.
    		],
    		//'cookieValidationKey' => 'WXBtZFdYQnRaR3h2WTJGc2FHOXpkQT09',
    	'authManager'=>[
    		'class'=>'yii\rbac\DbManager'
    	],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host='.dString('WXBtZFdYQnRaR3h2WTJGc2FHOXpkQT09').';dbname='.dString('TzIwTVR6SXdUV1JoZEdGaVlYTmxYMnh1ZG00PQ=='),
            'username' => dString('M0g3WU0wZzNXV1JoZEdGaVlYTmxYMnh1ZG00PQ=='),
            'password' => dString('SWtTb1NXdFRiMU5pUWtKSVUwaFI='),
            'charset' => 'utf8',
        ],
    		
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        	//'transport' => [
        	//		'class' => 'Swift_SmtpTransport',
        	//		'host' => 'smtp.gmail.com',  // e.g. smtp.mandrillapp.com or smtp.gmail.com
        	//		'username' => 'no-reply@dalaco.travel',
        	//		'password' => '123456123',
        	//		'port' => '587', // Port 25 is a very common port too
        	//		'encryption' => 'tls', // It is often used, check your provider or mail server specs
        	//], 
        ],
    	'i18n' => [
    			'translations' => [
    					'app' => [
    							'class' => 'yii\i18n\PhpMessageSource',
    					],
    			],
    	],
    		
    ],
	'language'=>'vi',
	'bootstrap' => ['gii'],
	'modules' => [
		'gii' => [
			'class' => 'yii\gii\Module',
			'allowedIPs' => ['127.0.0.1', '::1','222.252.0.171'],  //allowing ip's, 
			//'password'=>'13573368'
	],
				// ... 
	],
];
