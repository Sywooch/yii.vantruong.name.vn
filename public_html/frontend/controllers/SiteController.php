<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\db\Query;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
    	 
    	define('__VIEWS__', $this->viewPath . DS . __MOBILE_TEMPLETE__. __TEMP_NAME__); 
    	defined('__IS_SEARCH__') or define('__IS_SEARCH__', Yii::$app->controller->action->id == 'search' ? true : false);
    	/*if(Yii::$device != 'desktop' 
    			&& !in_array(Yii::$app->controller->action->id, ['sajax'])
    			&& file_exists(Yii::getAlias('@app/views/site/'.Yii::$device .DS. __TEMP_NAME__))
    			){
    		$this->viewPath = '@app/views/site/'.Yii::$device;
    	}*/
    	if(__IS_MOBILE_TEMPLETE__ && !in_array(Yii::$app->controller->action->id, ['sajax'])){
    		$this->viewPath = '@app/views/site/'.Yii::$device;
    	}
    	$fp = Yii::getAlias('@app/views/layouts/'.__TEMP_NAME__.'.php');
    	if(file_exists($fp)) $this->layout = __TEMP_NAME__;
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
     
    /**
     * @inheritdoc
     */
    public function actions()
    {    	
    	//var_dump(Yii::$app->controller->id);
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    
    public function actionError()
    {
    	//$fp = Yii::getAlias('@app/views/layouts/'.__TEMP_NAME__.'.php');
    	//view($fp);
    	//$this->layout = 'main';
    	 
   		return $this->render(Yii::$app->controller->action->id ); 
    }
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionSitemap(){
    	header('Content-type: text/xml'); 
    	echo get_site_value('seo/sitemap');
    	exit;
    }
    
    public function actionRobots(){
    	header('Content-type: text/plain');
    	echo (get_site_value('seo/robots'));
    	exit;
    }
    
    public function actionSajax(){
    	$this->layout = 'sajax';
    	switch (getParam('view')){
    		case '__system_init__':
    			$r = getBrowser();
    			$r['device'] = Yii::$device;
    			//$b = isset(Yii::$site['background']) ? Yii::$site['background'] : [];
    			$b = \app\modules\admin\models\Siteconfigs::getSiteConfigs('background');
    			 
    			$style = false;
    			if(!empty($b)){
    				$style = '';
    				// body
    				if(!empty($b['body'])){
    					$c = $b['body'];
    					//foreach ($b['body'] as $c){
    					//	 view($c);
    						//if(count($b['body']) == 1 || (isset($c['main']) && $c['main'] == 1)){
    							$style .= 'body{';
    							$style .= isset($c['color']) && $c['color'] != '' ? 'background-color:' . $c['color'] .';' : '';
    							$style .= isset($c['show_image']) && $c['show_image'] == 1 && isset($c['image']) && $c['image'] != '' ? 
    							'background-image:url(\''. $c['image'].'\');' : (isset($c['show_image']) && $c['show_image'] == 1 && isset($c['image']) && $c['image'] == '' ? 
    							'background-image:none;' : '');
    							$style .= (isset($c['repeat']) && $c['repeat'] != "" ? 'background-repeat:' . $c['repeat'] . ';' : '');
    							$style .= (isset($c['scroll']) && $c['scroll'] != "" ? 'background-attachment:' . $c['scroll'] . ';' : '');
    							$style .= (isset($c['left']) && $c['left'] != "" ? 'background-position-x:' .$c['left'] . ';' : '');
    							$style .= (isset($c['top']) && $c['top'] != "" ? 'background-position-y:' .$c['top'] . ';' : '');
    							$style .= isset($c['size']) && $c['size'] != "" ? 'background-size:'.$c['size'] . ';' : '';
    							$style .= '}';
    							//break;
    						//}
    					//}
    				}
    				// header out
    				if(!empty($b['header_out'])){
    					$c = $b['header_out'];
    					//$a = $c['image'];
    					//view($c);
    					//view(isset($c['show_image']) && $c['show_image'] == 1 && isset($c['image']) && $c['image']);exit;
    					//foreach ($b['header_out'] as $c){
    						//view($c,true); exit;
    						//if(count($b['header_out']) == 1 || (isset($c['main']) && $c['main'] == 1)){
    							$style .= '.header_out_side{';
    							$style .= isset($c['color']) && $c['color'] != '' ? 'background-color:' . $c['color'] .';' : '';
    							
    							$style .= (isset($c['show_image']) && $c['show_image'] == 1 && 
    							isset($c['image']) && $c['image'] != '') ? 
    							'background-image:url(\''. $c['image'].'\');' : (isset($c['show_image']) && $c['show_image'] == 1 && isset($c['image']) && $c['image'] == '' ? 
    									'background-image:none;' : '');
    							$style .= (isset($c['repeat']) && $c['repeat'] != "" ? 'background-repeat:' . $c['repeat'] . ';' : '');
    							$style .= (isset($c['scroll']) && $c['scroll'] != "" ? 'background-attachment:' . $c['scroll'] . ';' : '');
    							$style .= (isset($c['left']) && $c['left'] != "" ? 'background-position-x:' .$c['left'] . ';' : '');
    							$style .= (isset($c['top']) && $c['top'] != "" ? 'background-position-y:' .$c['top'] . ';' : '');
    							$style .= isset($c['size']) && $c['size'] != "" ? 'background-size:'.$c['size'] . ';' : '';
    							$style .= '}';
    							//break;
    						//}
    					//}
    				}
    				// header in
    				if(!empty($b['header_in'])){
    					$c = $b['header_in'];
    					//foreach ($b['header_in'] as $c){
    						//view($c);
    					//	if(count($b['header_in']) == 1 || (isset($c['main']) && $c['main'] == 1)){
    							$style .= '.header_in_side{';
    							$style .= isset($c['color']) && $c['color'] != '' ? 'background-color:' . $c['color'] .';' : '';
    							$style .= isset($c['show_image']) && $c['show_image'] == 1 && isset($c['image']) && $c['image'] != '' ? 'background-image:url(\''. $c['image'].'\');' : (isset($c['show_image']) && $c['show_image'] == 1 && isset($c['image']) && $c['image'] == '' ? 'background-image:none;' : '');
    							$style .= (isset($c['repeat']) && $c['repeat'] != "" ? 'background-repeat:' . $c['repeat'] . ';' : '');
    							$style .= (isset($c['scroll']) && $c['scroll'] != "" ? 'background-attachment:' . $c['scroll'] . ';' : '');
    							$style .= (isset($c['left']) && $c['left'] != "" ? 'background-position-x:' .$c['left'] . ';' : '');
    							$style .= (isset($c['top']) && $c['top'] != "" ? 'background-position-y:' .$c['top'] . ';' : '');
    							$style .= isset($c['size']) && $c['size'] != "" ? 'background-size:'.$c['size'] . ';' : '';
    							$style .= '}';
    					//		break;
    					//	}
    					//}
    				}
    				// main_out
    				if(!empty($b['main_out'])){
    					$c = $b['main_out'];
    					//foreach ($b['main_out'] as $c){
    						//view($c);
    					//	if(count($b['main_out']) == 1 || (isset($c['main']) && $c['main'] == 1)){
    							$style .= '.main_out_side{';
    							$style .= isset($c['color']) && $c['color'] != '' ? 'background-color:' . $c['color'] .';' : '';
    							$style .= isset($c['show_image']) && $c['show_image'] == 1 && isset($c['image']) && $c['image'] != '' ? 'background-image:url(\''. $c['image'].'\');' : (isset($c['show_image']) && $c['show_image'] == 1 && isset($c['image']) && $c['image'] == '' ? 'background-image:none;' : '');
    							$style .= (isset($c['repeat']) && $c['repeat'] != "" ? 'background-repeat:' . $c['repeat'] . ';' : '');
    							$style .= (isset($c['scroll']) && $c['scroll'] != "" ? 'background-attachment:' . $c['scroll'] . ';' : '');
    							$style .= (isset($c['left']) && $c['left'] != "" ? 'background-position-x:' .$c['left'] . ';' : '');
    							$style .= (isset($c['top']) && $c['top'] != "" ? 'background-position-y:' .$c['top'] . ';' : '');
    							$style .= isset($c['size']) && $c['size'] != "" ? 'background-size:'.$c['size'] . ';' : '';
    							$style .= '}';
    						//	break;
    						//}
    					//}
    				}
    				// main in
    				if(!empty($b['main_in'])){
    					$c = $b['main_in'];
    					//foreach ($b['main_in'] as $c){
    						//view($c);
    					//	if(count($b['main_in']) == 1 || (isset($c['main']) && $c['main'] == 1)){
    							$style .= '.main_in_side{';
    							$style .= isset($c['color']) && $c['color'] != '' ? 'background-color:' . $c['color'] .';' : '';
    							$style .= isset($c['show_image']) && $c['show_image'] == 1 && isset($c['image']) && $c['image'] != '' ? 'background-image:url(\''. $c['image'].'\');' : (isset($c['show_image']) && $c['show_image'] == 1 && isset($c['image']) && $c['image'] == '' ? 'background-image:none;' : '');
    							$style .= (isset($c['repeat']) && $c['repeat'] != "" ? 'background-repeat:' . $c['repeat'] . ';' : '');
    							$style .= (isset($c['scroll']) && $c['scroll'] != "" ? 'background-attachment:' . $c['scroll'] . ';' : '');
    							$style .= (isset($c['left']) && $c['left'] != "" ? 'background-position-x:' .$c['left'] . ';' : '');
    							$style .= (isset($c['top']) && $c['top'] != "" ? 'background-position-y:' .$c['top'] . ';' : '');
    							$style .= isset($c['size']) && $c['size'] != "" ? 'background-size:'.$c['size'] . ';' : '';
    							$style .= '}';
    					//		break;
    					//	}
    					//}
    				}
    				// main_out
    				if(!empty($b['footer_out'])){
    					$c = $b['footer_out'];
    					//foreach ($b['footer_out'] as $c){
    						//view($c);
    					//	if(count($b['footer_out']) == 1 || (isset($c['main']) && $c['main'] == 1)){
    							$style .= '.footer_out_side{';
    							$style .= isset($c['color']) && $c['color'] != '' ? 'background-color:' . $c['color'] .';' : '';
    							$style .= isset($c['show_image']) && $c['show_image'] == 1 && isset($c['image']) && $c['image'] != '' ? 'background-image:url(\''. $c['image'].'\');' : (isset($c['show_image']) && $c['show_image'] == 1 && isset($c['image']) && $c['image'] == '' ? 'background-image:none;' : '');
    							$style .= (isset($c['repeat']) && $c['repeat'] != "" ? 'background-repeat:' . $c['repeat'] . ';' : '');
    							$style .= (isset($c['scroll']) && $c['scroll'] != "" ? 'background-attachment:' . $c['scroll'] . ';' : '');
    							$style .= (isset($c['left']) && $c['left'] != "" ? 'background-position-x:' .$c['left'] . ';' : '');
    							$style .= (isset($c['top']) && $c['top'] != "" ? 'background-position-y:' .$c['top'] . ';' : '');
    							$style .= isset($c['size']) && $c['size'] != "" ? 'background-size:'.$c['size'] . ';' : '';
    							$style .= '}';
    					//		break;
    					//	}
    					//}
    				}
    				// main in
    				if(!empty($b['footer_in'])){
    					$c = $b['footer_in'];
    					//foreach ($b['footer_in'] as $c){
    						//view($c);
    					//	if(count($b['footer_in']) == 1 || (isset($c['main']) && $c['main'] == 1)){
    							$style .= '.footer_in_side{';
    							$style .= isset($c['color']) && $c['color'] != '' ? 'background-color:' . $c['color'] .';' : '';
    							$style .= isset($c['show_image']) && $c['show_image'] == 1 && isset($c['image']) && $c['image'] != '' ? 'background-image:url(\''. $c['image'].'\');' : (isset($c['show_image']) && $c['show_image'] == 1 && isset($c['image']) && $c['image'] == '' ? 'background-image:none;' : '');
    							$style .= (isset($c['repeat']) && $c['repeat'] != "" ? 'background-repeat:' . $c['repeat'] . ';' : '');
    							$style .= (isset($c['scroll']) && $c['scroll'] != "" ? 'background-attachment:' . $c['scroll'] . ';' : '');
    							$style .= (isset($c['left']) && $c['left'] != "" ? 'background-position-x:' .$c['left'] . ';' : '');
    							$style .= (isset($c['top']) && $c['top'] != "" ? 'background-position-y:' .$c['top'] . ';' : '');
    							$style .= isset($c['size']) && $c['size'] != "" ? 'background-size:'.$c['size'] . ';' : '';
    							$style .= '}';
    					//		break;
    					//	}
    					//}
    				}
    					
    					
    				$style .= '';
    			}else{
    				$r['background'] = '';
    			}
    			//
    			$box = (new Query())->from('box')->where(['sid'=>__SID__,'is_active'=>1])->select(['bizrule','code'])->all();
    			//view($box);
    			if(!empty($box)){
    				foreach ($box as $b){
    					if(isset($b['css']) && !empty($b['css'])){
    						$c = $b['css'];
    						//view($c);
    						$style .= '.box-'.$b['code'].'-background{';
    						$style .= isset($c['color']) && $c['color'] != '' ? 'background-color:' . $c['color'] .';' : '';
    						$style .= isset($c['show_image']) && $c['show_image'] == 1 && isset($c['image']) && $c['image'] != '' ? 'background-image:url(\''. $c['image'].'\');' : (isset($c['show_image']) && $c['show_image'] == 1 && isset($c['image']) && $c['image'] == '' ? 'background-image:none;' : '');
    						$style .= (isset($c['repeat']) && $c['repeat'] != "" ? 'background-repeat:' . $c['repeat'] . ';' : '');
    						$style .= (isset($c['scroll']) && $c['scroll'] != "" ? 'background-attachment:' . $c['scroll'] . ';' : '');
    						$style .= (isset($c['left']) && $c['left'] != "" ? 'background-position-x:' .$c['left'] . ';' : '');
    						$style .= (isset($c['top']) && $c['top'] != "" ? 'background-position-y:' .$c['top'] . ';' : '');
    						$style .= isset($c['size']) && $c['size'] != "" ? 'background-size:'.$c['size'] . ';' : '';
    						$style .= '}';
    					}
    				}
    			}
    			//
    			$r['background'] = $style;
    			echo json_encode($r);
    			exit;
    			break;
    		default:
    			return $this->render('sajax');
    			break;
    	}
    	
    }
    public function actionIndexa()
    {
    	 
    		return $this->render('index');
    }
    public function actionIndex()
    {     	
    	if(__DOMAIN_ADMIN__){
    		$this->redirect(['/admin']);
    	}else
        return $this->render(__TEMP_NAME__ .'/index');
    }
    
    public function actionProducts()
    {    	
    	 return $this->render(__TEMP_NAME__ .DS . Yii::$app->controller->action->id . (__IS_DETAIL__ ? '_detail' : ""));
    }
    public function actionTags()
    {
    	return $this->render(__TEMP_NAME__ .DS . Yii::$app->controller->action->id . (__IS_DETAIL__ ? '_detail' : ""));
    }
    public function actionNews()
    {
    	return $this->render(__TEMP_NAME__ .DS . Yii::$app->controller->action->id . (__IS_DETAIL__ ? '_detail' : ""));
    }
    
    public function actionLessions()
    {
    	return $this->render(__TEMP_NAME__ .DS . Yii::$app->controller->action->id . (__IS_DETAIL__ ? '_detail' : ""));
    }
    
    public function actionTours()
    {
    	return $this->render(__TEMP_NAME__ .DS . Yii::$app->controller->action->id . (__IS_DETAIL__ ? '_detail' : ""));
    }
    
    public function actionHotels()
    {
    	return $this->render(__TEMP_NAME__ .DS . Yii::$app->controller->action->id . (__IS_DETAIL__ ? '_detail' : ""));
    }
    
    public function actionMembers()
    {
    	return $this->render(__TEMP_NAME__ .DS . Yii::$app->controller->action->id);
    }
    public function actionCustomers()
    {
    	return $this->render(__TEMP_NAME__ .DS . Yii::$app->controller->action->id);
    }
    
    public function actionGallery()
    {
    	return $this->render(__TEMP_NAME__ .DS . Yii::$app->controller->action->id . (__IS_DETAIL__ ? '_detail' : ""));
    }
     
    public function actionDocs()
    {
    	return $this->render(__TEMP_NAME__ .DS . Yii::$app->controller->action->id . (__IS_DETAIL__ ? '_detail' : ""));
    }
    
    public function actionTestimonials()
    {
    	return $this->render(__TEMP_NAME__ .DS . Yii::$app->controller->action->id . (__IS_DETAIL__ ? '_detail' : ""));
    }
    
    public function actionTeachers()
    {
    	return $this->render(__TEMP_NAME__ .DS . Yii::$app->controller->action->id . (__IS_DETAIL__ ? '_detail' : ""));
    }
    public function actionCoaches()
    {
    	return $this->render(__TEMP_NAME__ .DS . Yii::$app->controller->action->id . (__IS_DETAIL__ ? '_detail' : ""));
    }
     
    
    public function actionFaqs()
    {
    	return $this->render(__TEMP_NAME__ .DS . Yii::$app->controller->action->id);
    }
    public function actionCart()
    {
    	 
    	return $this->render(__TEMP_NAME__ .DS . Yii::$app->controller->action->id );
    }
    public function actionSearch()
    {
    
    	return $this->render(__TEMP_NAME__ .DS . Yii::$app->controller->action->id );
    }
    public function actionAjax()
    {
    	$this->layout = 'ajax';
    	return $this->render(__TEMP_NAME__ .DS . Yii::$app->controller->action->id);
    }
    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
    	 
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render(__TEMP_NAME__ .DS . Yii::$app->controller->action->id, [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    public function actionText()
    {
    	return $this->render(__TEMP_NAME__ .DS . Yii::$app->controller->action->id);
    }
    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
