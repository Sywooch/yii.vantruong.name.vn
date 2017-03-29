<?php
namespace app\modules\admin\controllers;
use Yii;
use app\modules\admin\models\Customers;
use app\modules\admin\models\FormActive;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\controllers\CrsController; 
use app\modules\admin\models\Local;
use app\modules\admin\models\Siteconfigs;

/**
 * Ad_moduleController implements the CRUD actions for Customers model.
 */
class CustomersController extends CrsController
{
    /**
     * @inheritdoc
     */
	private $model;
	
    public function behaviors()
    {
    	$this->model = new Customers();
    	return parent::__behaviors();
    }

    /**
     * Lists all Customers models.
     * @return mixed
     */
    public function actionIndex()
    {    	 
    	$path = $this->action->id;
    	if(defined('CONTROLLER_CODE') && !is_numeric(CONTROLLER_CODE) && strlen(CONTROLLER_CODE)>1){
    		$fp = $this->viewPath . '/_forms/'.CONTROLLER_CODE .'.php';
    		if(file_exists($fp)){
    			$path = '_forms/'. CONTROLLER_CODE;
    		}
    	}
    	
    	return $this->render($path, [
    			'model' => $this->model,
    			'l' => $this->model->getList(Yii::$app->request->get() + ['type_id'=> CONTROLLER_CODE])
    	]);
    }

    /**
     * Displays a single Customers model.
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
     * Creates a new Customers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	return $this->actionAdd();
    }
    
    public function actionAdd()
    {
    	$model = new Customers();    	
    	if(Yii::$app->request->method == 'POST'){
    		FormActive::setBooleanFields($this->model->getBooleanFields());
    		$f = FormActive::getFormSubmit();
    		$f = splitName($f);
    		$f['sid'] = __SID__;
    		$f['owner'] = Yii::$app->user->id;
    		$code = isset($f['code']) && $f['code'] != "" ? $f['code'] : '';
    		if($code == "") {
    			$f['code'] = genCustomerCode(isset(Yii::$site['settings']['customers'][CONTROLLER_CODE]['code']) ? Yii::$site['settings']['customers'][CONTROLLER_CODE]['code'] : []);
    			if($f['code'] === false){
    				unset($f['code']);
    			}
    		}
    		// update with lang 
    		Yii::$app->db->createCommand()->insert(Customers::tableName(),$f)->execute();
    		$id = Yii::$app->db->createCommand("select max(id) from ".Customers::tableName())->queryScalar();
    		$this->updateSlug($id, $f);
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		btnClickReturn($btn,$id,$tab);
    	}
    	return $this->render('edit', [
    		'v'=>$this->model->getItem(getParam('id',0)),	
    		'id'=>Yii::$app->request->get('id',0),
    			'model'=>$this->model,
    			'id'=>Yii::$app->request->get('id',0),
    			'locals'=>Local::getAll(),
    	]);
    	 
    }
	     
    /**
     * Updates an existing Customers model.
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
    	$model = $this->model->getItem($id);
    	 
    	if(Yii::$app->request->method == 'POST'){
    		FormActive::setBooleanFields($this->model->getBooleanFields());
    		$f = FormActive::getFormSubmit();    
    		$f = splitName($f); 
    		$code = isset($f['code']) && $f['code'] != "" ? $f['code'] : $model['code'];
    		if($code == "") {
    			$f['code'] = genCustomerCode(isset(Yii::$site['settings']['customers'][CONTROLLER_CODE]['code']) ? Yii::$site['settings']['customers'][CONTROLLER_CODE]['code'] : []);
    			if($f['code'] === false){
    				unset($f['code']);
    			}
    			 
    		}
    		$con = array('id'=> $id);
    		
    		// update with lang    		
    		Yii::$app->db->createCommand()->update(Customers::tableName(),$f,$con)->execute();
    		$this->updateSlug($id, $f);
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		btnClickReturn($btn,$id,$tab);
    	}
    	return $this->render($this->action->id, [
    			'v' => $model,
    			'model'=>$this->model,
    			'id'=>Yii::$app->request->get('id',0),
    			'locals'=>Local::getAll(),
    			
    	]);
    	
    }
	
    private function updateSlug($id, $f){
    	$isSlug = false; $route = 'index';
    	switch (CONTROLLER_CODE){
    		case TYPE_ID_TEA:
    			$route = 'teachers'; $isSlug = true;
    			break;
    		case TYPE_ID_COACHES:
    		//case TYPE_ID_TEA:
    		//case TYPE_ID_TEA:
    			$route = 'coaches'; $isSlug = true;
    			break;			
    	}
    	if($isSlug){
    		$f['url'] = \app\modules\admin\models\Slugs::getSlug(unMark($f['name']), $id);
    		\app\modules\admin\models\Slugs::updateSlug($f['url'],$id,$route,3);
    		Siteconfigs::updateBizrule($this->model->tableName(),['id'=>$id,'sid'=>__SID__],['url_link'=>cu([DS.$f['url']])]);
    	}
    	
    }
     
    /**
     * Deletes an existing Customers model.
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
     * Finds the Customers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $lang
     * @return Customers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $lang = __LANG__)
    {
        if (($model = Customers::findOne(['id' => $id,'lang'=>$lang])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
