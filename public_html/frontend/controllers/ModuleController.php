<?php

namespace app\controllers;

use yii\web\Controller;
use Yii;
/**
 * Default controller for the `acp` module
 */
class ModuleController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
	public $layout='main';
	
	public function init()
	{
		parent::init();
		//Yii::$app->errorHandler->errorAction = 'admin/default/error';
		$handler = new \yii\web\ErrorHandler(['errorAction' => 'admin/default/error']);
		Yii::$app->set('errorHandler', $handler);
		$handler->register();
	}
}
