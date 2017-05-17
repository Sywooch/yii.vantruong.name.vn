<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
$a = [
		'SHOP_EXPIRED' => 1,
		'USER_EXPIRED' => 2,
		'DOMAIN_EXPIRED' => 3,
		
		'SHOP_RENEWED' => 11,
		'USER_RENEWED' => 12,
		'DOMAIN_RENEWED' => 13,
		
		'SHOP_SUSPENDED' => 31,
		'SHOP_COMINGSOON' => 32,
];
foreach($a as $k=>$v){
	defined($k) or define($k,$v);
}