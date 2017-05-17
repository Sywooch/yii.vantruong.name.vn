<?php

namespace app\modules\admin\controllers;
use Yii;
use yii\web\Controller;
use common\controllers\CrsController; 
use app\models\AdminMenu;
use app\models\SiteMenu;
use app\models\AdminMenuSearch;
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
 * Default controller for the `admin` module
 */
class DefaultController extends CrsController
{
    /**
     * Renders the index view for the module
     * @return string
     */
	 
	public function actionError()
	{
		//exit;
		$this->layout = 'ad_ajax';
		return $this->render('ajax/index');
	}
	public function behaviors()
	{		
		return parent::__behaviors();
	}
    public function actionIndex()
    {
    	$_SESSION['configs']['adLogin']['ID'] = Yii::$app->user->getId(); 
    	
        return $this->render('index');
    }
    public function actionAjax() 
    {
    	//exit;
    	$this->layout = 'ad_ajax';
    	return $this->render('ajax/index');
    }
    public function actionLogin()
    {
     
    	$this->layout = Yii::$app->controller->action->id;
    	if (!Yii::$app->user->isGuest) {
    		return __DOMAIN_ADMIN__ ?  $this->gohome() : $this->redirect(['index']);
    	}
    	 
    	$model = new LoginForm();
    	if ($model->load(Yii::$app->request->post()) && $model->login()) {
    		return $this->redirect('/admin');
    	} else {
    		return $this->render('login', [
    				'model' => $model,
    		]);
    	}    	 
    }
    
    public function actionForgot()
    {
    	 
    	$this->layout = 'login';
    	 
    	if (!Yii::$app->user->isGuest) {
    		return $this->redirect(['index']);
    	}
	    return $this->render('forgot', [
    		//'model' => $model,
    	]);
	    
    	 
    }
    public function actionLogout()
    {
    	Yii::$app->user->logout();    
    	return $this->redirect(['index']);
    }
}
