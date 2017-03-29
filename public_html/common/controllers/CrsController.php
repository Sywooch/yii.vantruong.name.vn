<?php

namespace common\controllers;

use Yii;
use app\models\AdminMenu;
use app\models\SiteMenu;
use app\models\AdminMenuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm; 
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\assets\AdminAsset; 
/**
 * AdminController implements the CRUD actions for AdminMenu model.
 */
class CrsController extends Controller
{
    /**
     * @inheritdoc
     */
	public $layout = 'main';
    public function __behaviors()
    {
    	define('__IS_ROOT__', Yii::$app->user->can([ROOT_USER]));
    	 
        return [
        		 
        		'access' => [
        				'class' => AccessControl::className(),
        				'rules' => [
        						[        								 
        								'actions' => ['login', 'forgot'],
        								'allow' => true, 'roles' => ['?'],
        						],
        						[
        								
        								'actions' => ['logout','ajax','error','forgot'],
        								'allow' => true,
        								'roles' => ['@'],
        		
        						],
        						[
	        						'allow' => true,
	        						'roles' => ['@'],
	        						'matchCallback'=>function($rule,$action){  
	        							
        								if(__SID__ == 0) {
        									//header('Location:' . DOMAIN);
        									return false; 
        								}
        						 		
	        							if(in_array(Yii::$app->controller->id, ['index','default']) || !CHECK_PERMISSION ){
	        								return true;
	        							}
	        							
	        							return  Yii::$app->user->can([ROOT_USER,Yii::$app->controller->id . '-' . (defined('CONTROLLER_CODE') && CONTROLLER_CODE != "" ? CONTROLLER_CODE . '-' : '') .$action->id]);        							
	        						}        								
        						],
        		
        				],
        		
        		],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                	'logout' => ['POST'],
                ],
            ],
        ];
    }
    
    public function actionError()
    {
    	 
    	return $this->render(Yii::$app->controller->action->id );
    }
      
}
