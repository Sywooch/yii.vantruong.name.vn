<?php 
use yii\helpers\Url;
use frontend\assets\AppAsset;
AppAsset::register($this);
 
switch (Yii::$app->request->post('action')){
	case 'changeLanguage':
		 
		$language = \app\modules\admin\models\AdLanguage::getLanguage(post('lang'));
		 
		$config = Yii::$app->session['config'];
		$config['language'] = $language;
		// view(post('lang'));
		Yii::$app->session->set('config',$config);		
		//view(Yii::$app->session->get('config'));
		exit;
		break;
	
}