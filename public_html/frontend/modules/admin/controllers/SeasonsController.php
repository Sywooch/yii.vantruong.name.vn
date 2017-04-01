<?php
namespace app\modules\admin\controllers;
use Yii;
use app\modules\admin\models\Seasons;
use app\modules\admin\models\FormActive;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\controllers\CrsController; 
use yii\db\Query;

/**
 * Ad_moduleController implements the CRUD actions for Seasons model.
 */
class SeasonsController extends CrsController
{
    /**
     * @inheritdoc
     */
	private $model;
	
    public function behaviors()
    {
    	$this->model = new Seasons();
    	return parent::__behaviors();
    }

    /**
     * Lists all Seasons models.
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
    	 
    	if(Yii::$app->request->method == 'POST'){
    		$f = isset($_POST['f']) ? $_POST['f'] : array();
    		//view($f,true);
    		$f1 = isset($_POST['f1']) ? $_POST['f1'] : array();
    		$fx = isset($_POST['fx']) ? $_POST['fx'] : array();
    		$delete_item = isset($_POST['delete_item']) ? $_POST['delete_item'] : array(0);
    		$delete_item1 = isset($_POST['delete_item1']) ? $_POST['delete_item1'] : array(0);
    		$delete_item3 = isset($_POST['delete_item3']) ? $_POST['delete_item3'] : array(0);
    		$this->model->delete_item($delete_item);
    		$this->model->delete_item1($delete_item1);
    		$this->model->delete_item3($delete_item3);
    		if(!empty($fx)){
    			foreach ($fx as $k=>$v){
    				//$v['type_id'] = 2;
    				if(isset($v['id']) && $v['id'] > 0){
    					$id = $v['id']; unset($v['id']);
    					Yii::$app->db->createCommand()->update($this->model->tableCategory(),$v,array(
    							'id'=>$id,'sid'=>__SID__
    					))->execute();
    				}else{
    					$v['sid'] = __SID__;
    					Yii::$app->db->createCommand()->insert($this->model->tableCategory(),$v)->execute();
    					$id = Yii::$app->db->createCommand("select max(id) from ".$this->model->tableCategory())->queryScalar();
    		
    				}
    			}
    		}
    		 
    		if(!empty($f)){
    			foreach ($f as $k=>$v){
    				$v['from_date'] = ctime(array('format'=>'Y-m-d','string'=>$v['from_date']));
    				$v['to_date'] = ctime(array('format'=>'Y-m-d','string'=>$v['to_date'])) . ' 23:59:59';
    				//view($v,true);
    				$v['is_default'] = isset($v['is_default']) && $v['is_default']  == 'on' ? 1 : 0;
    				
    				if(isset($v['id']) && $v['id'] > 0){
    					$id = $v['id']; unset($v['id']);
    					Yii::$app->db->createCommand()->update($this->model->tableName(),$v,array(
    							'id'=>$id,'sid'=>__SID__
    					))->execute();
    				}else{
    					$v['sid'] = __SID__;
    					Yii::$app->db->createCommand()->insert($this->model->tableName(),$v)->execute();
    		
    				}
    			}
    		}
    		
    		if(!empty($f1)){
    			foreach ($f1 as $k=>$v){
    				//$v['type_id'] = Zii::$db->getField($this->model->tableCategory(), 'type_id',['id'=>$v['parent_id']]);
    				//$v['type_id'] = (new Query())->select(['type_id'])->from($this->model->tableCategory())->where(['id'=>$v['parent_id']])->scalar();
    				$v['is_default'] = isset($v['is_default']) && $v['is_default']  == 'on' ? 1 : 0;
    				if(isset($v['id']) && $v['id'] > 0){
    					$id = $v['id']; unset($v['id']);
    					Yii::$app->db->createCommand()->update($this->model->tableWeekend(),$v,array(
    							'id'=>$id,'sid'=>__SID__
    					))->execute();
    				}else{
    					$v['sid'] = __SID__;
    					Yii::$app->db->createCommand()->insert($this->model->tableWeekend(),$v)->execute();
    		
    				}
    			}
    		}
    		$s = isset($_POST['s']) ? $_POST['s'] : array();
    		$sx = post('sx');
    		//view($sx,true);
    		if(!empty($sx)){
    		Yii::$app->db->createCommand()->delete('seasons_categorys_to_services',['season_id'=>$sx['season_id']])->execute();
    		Yii::$app->db->createCommand()->delete($this->model->tableToSuppliers(),['season_id'=>$sx['season_id'],'type_id'=>$sx['type_id']])->execute();
    		}
    		if(!empty($s)){    			    		
    			foreach ($s as $c){
    				Yii::$app->db->createCommand()->insert($this->model->tableToSuppliers(),array(
    						'season_id'=>$sx['season_id'],
    						'supplier_id'=>$c['supplier_id'],    				
    						'type_id'=>$sx['type_id']
    				))->execute();
    				//
    				Yii::$app->db->createCommand()->insert('seasons_categorys_to_services',['season_id'=>$sx['season_id'],'service_id'=>$c['supplier_id'],'is_active'=>1])->execute();
    				
    			}
    		}
    		//
    		
    		//
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		btnClickReturn($btn,0,$tab);
    		
    	}
    	return $this->render($path, [
    			'model' => $this->model,
    			'l'=>$this->model->getList(Yii::$app->request->get()+['limit'=>10000]),
    			'lx'=>$this->model->getListx(Yii::$app->request->get()),
    			'lx1' => $this->model->getListx(array('type_id'=>[3,4]))
    	]);
    }

    /**
     * Displays a single Seasons model.
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
     * Creates a new Seasons model.
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
    		Yii::$app->db->createCommand()->insert(Seasons::tableName(),$f)->execute();
    		$id = Yii::$app->db->createCommand("select max(id) from ".Seasons::tableName())->queryScalar();
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
     * Updates an existing Seasons model.
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
    		$con = array('id'=> $id,'sid'=>__SID__);
    		// update with lang    		
    		Yii::$app->db->createCommand()->update(Seasons::tableName(),$f,$con)->execute();    		    	
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
     * Deletes an existing Seasons model.
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
     * Finds the Seasons model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $lang
     * @return Seasons the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Seasons::findOne(['id' => $id,'sid'=>__SID__])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
