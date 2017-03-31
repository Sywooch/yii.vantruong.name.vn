<?php
namespace app\modules\admin\controllers;
use Yii;
use app\modules\admin\models\Content;
use app\modules\admin\models\FormActive;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\controllers\CrsController; 
use app\modules\admin\models\Menu;
use app\modules\admin\models\AdForms;
use app\modules\admin\models\Slugs;
use app\modules\admin\models\Producers;
use app\modules\admin\models\Customers;
use app\modules\admin\models\ProductsMadeIn;
use app\modules\admin\models\Filters;

/**
 * Ad_moduleController implements the CRUD actions for Content model.
 */
class ContentController extends CrsController
{
    /**
     * @inheritdoc
     */
	private $model;
	
    public function behaviors()
    {
    	$this->model = new Content();
    	return parent::__behaviors();
    }

    /**
     * Lists all Content models.
     * @return mixed
     */
    public function actionIndex()
    {    	 
    	$path = $this->action->id;
    	//if(defined('CONTROLLER_CODE') && !is_numeric(CONTROLLER_CODE) && strlen(CONTROLLER_CODE)>1){
    	$fp = $this->viewPath . '/_forms/'.Yii::$app->request->get('type').DS.$this->action->id .'.php';
    	if(file_exists($fp)){
    		$path = '_forms/'. Yii::$app->request->get('type').DS.$this->action->id;
    	}
    	//}
    	return $this->render(Yii::$app->user->can([ROOT_USER,ADMIN_USER, 'form-'.getParam('type').'-index']) ? $path : '../error/index', [
    			'model' => $this->model,
    			'l'=>$this->model->getList(Yii::$app->request->get()),
    			
    	]);
    }

    /**
     * Displays a single Content model.
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
     * Creates a new Content model.
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
    		FormActive::$jsonFields = $this->model->jsonFields();
    		$f = FormActive::getFormSubmit();    		    		
    		$f['sid'] = __SID__;
    		$f['owner'] = Yii::$app->user->id;
    		if(isset($f['time'])){
    			$f['time'] = ctime(['string'=>$f['time']]);
    		}
    		if(isset($_POST['f']['is_active']) && in_array($_POST['f']['is_active'], [-1])){
    			unset($f['is_active']);
    		}
    		$id = 0;
    		$titles = explode(';', $f['title']);
    		foreach ($titles as $title){
    			if(trim($title) != ""){
    				$f['title'] = $title;
    				$f['url'] = Slugs::getSlug(isset($f['url']) && $f['url'] != "" ? $f['url'] : unMark($f['title']), $this->model->getID());
    				$code = isset($f['code']) && $f['code'] != "" ? $f['code'] : ''; 
    				if($code == "") {
    					$f['code'] = genCustomerCode(isset(Yii::$site['settings'][$f['type']]) ? Yii::$site['settings'][$f['type']]+ ['table'=>$this->model->tableName()]: []);
    					if($f['code'] === false){
    						unset($f['code']);
    					}
    					
    				}
		    		if(Yii::$app->db->createCommand()->insert(Content::tableName(),$f)->execute()){
			    		$id = Yii::$app->db->createCommand("select max(id) from ".Content::tableName())->queryScalar();
			    		$this->model->updateCategory($id);
			    		$this->model->updateArticleAttr($id);
			    		$this->model->updateTabs($id);
			    		$this->model->updatePrice($id);
			    		$this->model->updateProducers($id);
			    		$this->model->updateTask($id);
			    		$this->model->updateFilters($id);   
			    		$this->model->updateAttrType($id, $f['type']);			    					    		
			    		Slugs::updateSlug($f['url'],$id,$f['type'],1,post('biz',[]));
			    		$con = array('id'=> $id,'sid'=>__SID__);
			    		Yii::$app->db->createCommand()->update(Content::tableName(),['url_link'=>Yii::$app->zii->getUrl($f['url'])],$con)->execute();
			    		
			    		//
			    		//view(CONTROLLER_CODE,true);
			    		switch (getParam('type')){
			    			case FORM_TYPE_TESTIMONIALS:
			    				Yii::$app->db->createCommand()->delete('item_to_courses',['item_id'=>$id])->execute();
			    				if(!empty(post('course_id',[]))){
			    					foreach (post('course_id',[]) as $k=>$v){
			    						Yii::$app->db->createCommand()->insert('item_to_courses',[
			    								'item_id'=>$id,
			    								'course_id'=>$v,
			    						])->execute();
			    					}
			    				}
			    				break;
			    		}
			    		//
			    		
			    		
		    		}
    			}
    		}
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		btnClickReturn($btn,$id,$tab);
    	}
    	$path = 'edit';
    	$fp = $this->viewPath . '/_forms/'.Yii::$app->request->get('type').DS.'edit' .'.php';
    	if(file_exists($fp)){
    		$path = '_forms/'. Yii::$app->request->get('type').DS.'edit';
    	}
    	return $this->render(Yii::$app->user->can([ROOT_USER,ADMIN_USER, 'form-'.getParam('type').'-add']) ? $path : '../error/index', [
    		'v'=>$this->model->getItem(getParam('id',0)),	
    		'model'=>$this->model,	
    		'id'=>Yii::$app->request->get('id',0),
    			'all_menu'=>Menu::getAll(['type'=>getParam('type',false)]),
    			'categorys'=>$this->model->getItemCategorys(getParam('id',0),['field'=>'id','return_type'=>0]),
    			'form'=>AdForms::getForm(['code'=>getParam('type')]),
    			'attrs'=>$this->model->getListAttrsByType(getParam('type')),
    			'producers'=>Producers::getList(['limit'=>10000,'is_active'=>1]),
    			'vendors'=>Customers::getList(['limit'=>10000,'is_active'=>1,'p'=>1,'type_id'=>TYPE_ID_VENDOR]),
    			'madein'=>ProductsMadeIn::getList(['limit'=>10000,'is_active'=>1,'p'=>1]),
    			'filtersModel'=>new Filters()
    	]);
    	 
    }
	     
    /**
     * Updates an existing Content model.
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
    	//view((new yii\db\Query())->select('max(id) +1')->from($this->model->tableName())->scalar());
    	$model = $this->model->getItem($id,Yii::$app->request->get());
    	if(Yii::$app->request->method == 'POST'){
    		FormActive::setBooleanFields($this->model->getBooleanFields());
    		FormActive::$jsonFields = $this->model->jsonFields();
    		$f = FormActive::getFormSubmit();    	
    		$f['url'] = Slugs::getSlug(isset($f['url']) && $f['url'] != "" ? $f['url'] : unMark($f['title']), $id);
    		if(isset($f['time'])){
    			$f['time'] = ctime(['string'=>$f['time']]);
    		}
    		if(isset($_POST['f']['is_active']) && in_array($_POST['f']['is_active'], [-1])){
    			unset($f['is_active']);
    		}
    		$code = isset($f['code']) && $f['code'] != "" ? $f['code'] : $model['code'];
    		if($code == "") {
    			//view(Yii::$site['settings'][$model['type']]['code']);
    			$f['code'] = genCustomerCode(isset(Yii::$site['settings'][$model['type']]['code']) ? Yii::$site['settings'][$model['type']]['code'] + ['table'=>$this->model->tableName()] : []);
    			//view($f['code']);
    			if($f['code'] === false){
    				unset($f['code']);
    			}
    			
    		}
    		if($model['owner'] == 0){
    			$f['owner'] = Yii::$app->user->id;
    		}
    		//view($code,true);
    		$con = array('id'=> $id,'sid'=>__SID__);   		
    		Yii::$app->db->createCommand()->update(Content::tableName(),$f,$con)->execute();
    		$this->model->updateCategory($id);
    		$this->model->updateArticleAttr($id);
    		$this->model->updateTabs($id);
    		$this->model->updatePrice($id);
    		$this->model->updateProducers($id);
    		$this->model->updateTask($id);
    		$this->model->updateFilters($id);
    		$this->model->updateAttrType($id, $f['type']);
    		Slugs::updateSlug($f['url'],$id,$f['type'],1,post('biz',[]));
    		Yii::$app->db->createCommand()->update(Content::tableName(),['url_link'=>Yii::$app->zii->getUrl($f['url'])],$con)->execute();
    		//
    		 
    		switch (getParam('type')){
    			case FORM_TYPE_TESTIMONIALS:
    				Yii::$app->db->createCommand()->delete('item_to_courses',['item_id'=>$id])->execute();
    				if(!empty(post('course_id',[]))){
    					foreach (post('course_id',[]) as $k=>$v){
    						Yii::$app->db->createCommand()->insert('item_to_courses',[
    								'item_id'=>$id,
    								'course_id'=>$v, 
    						])->execute();
    					}
    				} 
    				break;
    		}
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		btnClickReturn($btn,$id,$tab);
    	}
    	$path = $this->action->id;
    	$fp = $this->viewPath . '/_forms/'.Yii::$app->request->get('type').DS.$this->action->id .'.php';
    	if(file_exists($fp)){
    		$path = '_forms/'. Yii::$app->request->get('type').DS.$this->action->id;
    	}
    	return $this->render(Yii::$app->user->can([ROOT_USER,ADMIN_USER, 'form-'.getParam('type').'-edit']) ? $path : '../error/index', [
    			'v' => $model,
    			'model'=>$this->model,
    			'id'=>Yii::$app->request->get('id',0),
    			'all_menu'=>Menu::getAll(['type'=>getParam('type',false)]),
    			'categorys'=>$this->model->getItemCategorys($id,['field'=>'id','return_type'=>0]),
    			'form'=>AdForms::getForm(['code'=>getParam('type')]),
    			'attrs'=>$this->model->getListAttrsByType(getParam('type')),
    			'producers'=>Producers::getList(['limit'=>10000,'is_active'=>1]),
    			'vendors'=>Customers::getList(['limit'=>10000,'is_active'=>1,'p'=>1,'type_id'=>TYPE_ID_VENDOR]),
    			'madein'=>ProductsMadeIn::getList(['limit'=>10000,'is_active'=>1,'p'=>1]),
    			'filtersModel'=>new Filters(),
    	]);
    	
    }
	
     
    /**
     * Deletes an existing Content model.
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
     * Finds the Content model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $lang
     * @return Content the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Content::findOne(['id' => $id,'sid'=>__SID__])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
