<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\AdModule;
use app\modules\admin\models\AdminMenu;
use app\modules\admin\models\FormActive;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\controllers\CrsController; 

/**
 * Ad_moduleController implements the CRUD actions for AdModule model.
 */
class Ad_moduleController extends CrsController
{
    /** 
     * @inheritdoc
     */
	private $model;
	 
	public function behaviors()
	{
		$this->model = new AdModule();
		return parent::__behaviors();
	}
     

    /**
     * Lists all AdModule models.
     * @return mixed
     */
    public function actionIndex()
    {
    	//exit;
    	//view($)
        //$searchModel = new Ad_moduleSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
          'model'=>$this->model
          //  'searchModel' => $searchModel,
          //  'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AdModule model.
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
     * Creates a new AdModule model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	$model = new AdModule();
    	
    	if ($model->load(Yii::$app->request->post()) && $model->save()) {
    		return $this->redirect(['view', 'id' => $model->id]);
    	} else {
    		return $this->render('create', [
    				'model' => $model,
    		]);
    	}
    }
    
    public function actionAdd()
    {
    	$model = new AdModule();
    
    	$id = $model->getID();
    	if(Yii::$app->request->method == 'POST'){
    		
    		FormActive::setBooleanFields($this->model->getBooleanFields());
    		$f = FormActive::getFormSubmit(); 
    		$f['url'] = $this->model->getUrl(unMark($f['title']),$id);
    		//$f1['title'] = $f['title'];
    		$f['id'] = $id;
    		//unset($f['title']); unset($f['url']);
    		$f['lang']=ADMIN_LANG;
    		// update with lang    	
    		if($f['parent_id']>0){
    		$parent = $this->findModel($f['parent_id']);
    		$f['level'] = $parent['level']+1;
    		}
    		if(Yii::$app->db->createCommand()->insert(AdModule::tableName(),$f)->execute()){
	    		//$id = Yii::$app->db->createCommand("select max(id) from ".AdModule::tableName())->queryScalar();
	    		
	    		//$this->model->update_lft();
	    		$this->model->updateForms($id);
	    		$this->_update_controller($f['route'],$f['child_code']);
	    		
	    		Yii::$app->zii->update_table_lft([
	    				'table'=>$this->model->tableName(),
	    				//'sid'=>__SID__,
	    				'orderBy'=>['position'=>SORT_ASC, 'title'=>SORT_ASC],
	    				'level'=>true
	    		]);
    		}
    		 
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		btnClickReturn($btn,$id,$tab);
    	}
    	return $this->render('edit', [
    		'model' => $model,
    		'v'=>getParam('id')>0 ? AdminMenu::getItem(getParam('id')) : [],
    		'igr'=>[],	
    	]);
    	 
    }
	
    private function _update_controller($route, $child_code = ''){
    	if(strlen($route)>1 && Yii::$app->request->method == 'POST'){
    		$controller_path = Yii::getAlias('@modules') . DS . Yii::$app->controller->module->id . '/controllers';
    		$model_path = Yii::getAlias('@modules') . DS . Yii::$app->controller->module->id . '/models';
    		$view_path = Yii::getAlias('@modules') . DS . Yii::$app->controller->module->id . '/views';
    		$controller_name = strtoupper(substr($route, 0,1)) . strtolower(substr($route, 1)) . 'Controller';
    		$models = explode('_', $route);
    		$model_name = '';
    		foreach ($models as $m){
    			$model_name .= strtoupper(substr($m, 0,1)) . strtolower(substr($m, 1));
    		}
    		$controller_file = $controller_path . DS . $controller_name .'.php';
    		if(!file_exists($controller_file)){
	    		
	    		$html = str_replace([
	    				'@CONTROLLER_NAME@',
	    				'@MODEL_NAME@',
	    				'@MODULEID@',
	    		], [
	    				$controller_name,
	    				$model_name,
	    				Yii::$app->controller->module->id
	    				
	    		], file_get_contents( $controller_path .'/_templetes/controller.php'));
	    		writeFile($controller_file,$html);
    		}
    		$model_file = $model_path . DS . $model_name .'.php';
    		if (!file_exists($model_file)){
    			
    			$html = str_replace([
    					'@CONTROLLER_NAME@',
    					'@MODEL_NAME@',
    					'@MODULEID@',
    					'@TABLE_NAME@',
    			], [
    					$controller_name,
    					$model_name,
    					Yii::$app->controller->module->id,
    					strtolower($route),
    					 
    			], file_get_contents( $controller_path .'/_templetes/model.php'));
    			writeFile($model_file,$html);
    		}
    		$index_file = $view_path . DS . strtolower($route) .'/index.php';
    		if (!file_exists($index_file)){
    			 
    			$html = str_replace([
    					'@CONTROLLER_NAME@',
    					'@MODEL_NAME@',
    					'@MODULEID@',
    					'@TABLE_NAME@',
    			], [
    					$controller_name,
    					$model_name,
    					Yii::$app->controller->module->id,
    					strtolower($route),
    		
    			], file_get_contents( $controller_path .'/_templetes/index.php'));
    			writeFile($index_file,$html);
    		}
    		// edit
    		$fp = $view_path . DS . strtolower($route) .'/edit.php';
    		if (!file_exists($fp)){
    		
    			$html = str_replace([
    					'@CONTROLLER_NAME@',
    					'@MODEL_NAME@',
    					'@MODULEID@',
    					'@TABLE_NAME@',
    			], [
    					$controller_name,
    					$model_name,
    					Yii::$app->controller->module->id,
    					strtolower($route),
    		
    			], file_get_contents( $controller_path .'/_templetes/edit.php'));
    			writeFile($fp,$html);
    		}
    		/*/ child code
    		if(strlen($route)>1 && strlen($child_code) >1){
    		$fp = $view_path . DS . strtolower($route) ."/_forms/$child_code.php";
    		if (!file_exists($fp)){    		
    			$html = str_replace([
    					'@CONTROLLER_NAME@',
    					'@MODEL_NAME@',
    					'@MODULEID@',
    					'@TABLE_NAME@',
    			], [
    					$controller_name,
    					$model_name,
    					Yii::$app->controller->module->id,
    					strtolower($route),
    		
    			], file_get_contents( $controller_path .'/_templetes/edit.php'));
    			writeFile($fp,$html);
    		}}
    		
    		//*/
    	}
    	 
    }
    /**
     * Updates an existing AdModule model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $lang
     * @return mixed
     */
    public function actionUpdate($id)
    {    	
        $this->actionEdit($id);
    }
    public function actionEdit($id)
    {    	
    	$model = $this->findModel($id);   
    	
    	if(Yii::$app->request->method == 'POST'){
    		
    		FormActive::setBooleanFields($this->model->getBooleanFields());
    		$f = FormActive::getFormSubmit();    
    		$f['url'] = $this->model->getUrl(unMark($f['title']),$id);    		
    		$f1['title'] = $f['title'];
    		$f1['url'] = $f['url'];
    		unset($f['title']); unset($f['url']);
    		$con = array('id'=> $id,'lang'=>ADMIN_LANG);   	
    		$parent = $this->model->getItem($f['parent_id']);    		 
    		$f['level'] = !empty($parent) ? ($parent['level']+1) : 0;    		 
    		// update with lang
    		Yii::$app->db->createCommand()->update(AdModule::tableName(),$f1,$con)->execute();
    		unset($con['lang']);
    		Yii::$app->db->createCommand()->update(AdModule::tableName(),$f,$con)->execute();
    		//$this->model->update_lft();
    		
    		Yii::$app->zii->update_table_lft([
    				'table'=>$this->model->tableName(),
    				//'sid'=>__SID__,
    				'orderBy'=>['position'=>SORT_ASC, 'title'=>SORT_ASC],
    				'level'=>true
    		]);
    		$this->model->updateForms($id);
    		$this->_update_controller($f['route'],$f['child_code']);    		
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		 
    		btnClickReturn($btn,$id,$tab);
    	}
    	
    	return $this->render($this->action->id, [
    		'v' => $model,
    		'model'=>$this->model,	 
    		'id'=>Yii::$app->request->get('id',0),	
    			'igr' => AdminMenu::get_all_child_id($model['id']),
    			'v' => AdminMenu::getItem($id),
    	]);    		
    	
    }
	
     
    /**
     * Deletes an existing AdModule model.
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
     * Finds the AdModule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $lang
     * @return AdModule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $lang = __LANG__)
    {
        if (($model = AdModule::findOne(['id' => $id,'lang'=>$lang])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
