<?php
namespace app\modules\admin\controllers;
use Yii;
use app\modules\admin\models\Siteconfigs;
use app\modules\admin\models\FormActive;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\controllers\CrsController; 

/**
 * Ad_moduleController implements the CRUD actions for Siteconfigs model.
 */
class SiteconfigsController extends CrsController
{
    /**
     * @inheritdoc
     */
	private $model;
	
    public function behaviors()
    {
    	$this->model = new Siteconfigs();
    	return parent::__behaviors();
    }

    /**
     * Lists all Siteconfigs models.
     * @return mixed
     */
    public function actionIndex()
    {    	 
    	//
    	if(Yii::$app->request->method == 'POST'){    		
    		$f = FormActive::getFormSubmit();
    		header("X-XSS-Protection: 0");
    		
    		$new = post('new',[]);
    		$required = post('required',[]);
    		//
    		if(isset($f['listItem']) && !empty($f['listItem']) && post('sort') == 1){
    			$f['listItem'] = array_sort($f['listItem'] ,'position', SORT_ASC);
    		}
    		if(isset($f['logo']['listItem']) && count($f['logo']['listItem']) == 1){
    			$f['logo']['listItem'][0]['main'] = 1;
    			$f['logo']['image'] = $f['logo']['listItem'][0]['image'] ;
    		}
    		
    		//
    		if(!empty($new)){
    			$state = true;
    			if(!empty($required)){
    				foreach ($required as $r){
    					if(isset($new[$r]) && $new[$r] != ""){
    						
    					}else{
    						$state = false;
    						break;
    					}
    				}
    			}
    			if($state){
    				switch (CONTROLLER_CODE){
    					case 'livechat':
    						switch ($new['type_id']){
    							case 'facebook':
    								$fanpage_link = strpos($new['fanpage'], 'http') === false ? 'https://www.facebook.com/'.$new['fanpage'] : $new['fanpage'];
    								$new['embed_code'] = '<div id="livechat_facebook">
  <a href="#" class="chat_fb_header" onclick="return:false;"><i class="fa fa-facebook-square"></i> Chat với chúng tôi</a>
  <div class="fchat">
  <div class="fb-page" data-tabs="messages" data-href="'.$fanpage_link.'" data-width="250" data-height="400" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"></div>
  </div>
  </div>';
    								break;
    						}
    						break;
    				}
    				$f['listItem'][] = $new;
    			}
    		}
    		
    		$this->model->updateSiteConfigs(CONTROLLER_CODE, $f);    
    		if(isset($_POST['btnSubmit'])){
    			///btnClickReturn(post('btnSubmit'),0,post('currentTab'));
    		}
    		//view($f);
    	}
    	
    	//
    	$path = $this->action->id;
    	if(defined('CONTROLLER_CODE') && !is_numeric(CONTROLLER_CODE) && strlen(CONTROLLER_CODE)>1){
    		$fp = $this->viewPath . '/_forms/'.CONTROLLER_CODE .'.php';
    		if(file_exists($fp)){
    			$path = '_forms/'. CONTROLLER_CODE;
    		}
    	}
    	//view($this->model->getItem('SITE_CONFIGS'));
    	return $this->render($path, [
    			'model' => $this->model,
    			'v'=>$this->model->getList(CONTROLLER_CODE)
    	]);
    }

    /**
     * Displays a single Siteconfigs model.
     * @param integer $id
     * @param string $lang
     * @return mixed
     */
    public function actionView($id, $lang)
    {
        return $this->render('view', [
            'model' => $this->findModel($id, $lang),
        ]);
    }

    /**
     * Creates a new Siteconfigs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	return $this->actionAdd();
    }
    
    public function actionAdd()
    {    	
    	if(Yii::$app->request->method == 'POST'){
    		FormActive::setBooleanFields($this->model->getBooleanFields());
    		$f = FormActive::getFormSubmit();    		
    		$f['sid'] = __SID__;
    		Yii::$app->db->createCommand()->insert(Siteconfigs::tableName(),$f)->execute();
    		$id = Yii::$app->db->createCommand("select max(id) from ".Siteconfigs::tableName())->queryScalar();
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		btnClickReturn($btn,$id,$tab);
    	}
    	return $this->render('edit', [
    		'v'=>[],	
    		'model'=>$this->model,	
    		'id'=>Yii::$app->request->get('id',0)
    	]);
    	 
    }
	     
    /**
     * Updates an existing Siteconfigs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $lang
     * @return mixed
     */
    public function actionUpdate($id)
    {    	
        return $this->actionEdit($id);
    }
    public function actionEdit($id)
    {    	
    	$model = $this->findModel($id);
    	if(Yii::$app->request->method == 'POST'){
    		FormActive::setBooleanFields($this->model->getBooleanFields());
    		$f = FormActive::getFormSubmit();    		     		
    		$con = array('id'=> $id,'sid'=>__SID__);
    		// update with lang    		
    		Yii::$app->db->createCommand()->update(Siteconfigs::tableName(),$f,$con)->execute();    		    	
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		btnClickReturn($btn,$id,$tab);
    	}
    	return $this->render($this->action->id, [
    			'v' => $model,
    			'model'=>$this->model,
    			'id'=>Yii::$app->request->get('id',0),
    	]);
    	
    }
	
     
    /**
     * Deletes an existing Siteconfigs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param string $lang
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Siteconfigs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $lang
     * @return Siteconfigs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Siteconfigs::findOne(['id' => $id,'sid'=>__SID__])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
