<?php
namespace app\modules\admin\controllers;
use Yii;
use app\modules\admin\models\Box;
use app\modules\admin\models\FormActive;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\controllers\CrsController; 
use app\modules\admin\models\Content;
use app\modules\admin\models\Filters;
use app\modules\admin\models\AdForms;

/**
 * Ad_moduleController implements the CRUD actions for Box model.
 */
class BoxController extends CrsController
{
    /**
     * @inheritdoc
     */
	private $model;
	
    public function behaviors()
    {
    	$this->model = new Box();
    	return parent::__behaviors();
    }

    /**
     * Lists all Box models.
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
    			'l'=>$this->model->getList(Yii::$app->request->get())
    	]);
    }

    /**
     * Displays a single Box model.
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
     * Creates a new Box model.
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
    		$f['code'] = $this->model->getBoxCode($f['code'],0);
    		$f['lang'] = __LANG__;
    		$f['sid'] = __SID__;
    		Yii::$app->db->createCommand()->insert(Box::tableName(),$f)->execute();
    		$id = Yii::$app->db->createCommand("select max(id) from ".Box::tableName())->queryScalar();
    		$this->updateSlug($id, $f);
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		btnClickReturn($btn,$id,$tab);
    	}
    	return $this->render('edit', [
    		'v'=>$this->model->getItem(getParam('id',0)),	
    		'model'=>$this->model,	
    		'id'=>Yii::$app->request->get('id',0),
    			'articles_list'=>[],
    			'filters'=>[],
    			'filter_by'=>[],
    			'forms'=>AdForms::getUserForms(['is_content'=>1]),
    			'attrs'=>Content::getListAttrsByType(),
    	]);
    	 
    }
	     
    /**
     * Updates an existing Box model.
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
    		//
    		
    		//
    		$f['code'] = $this->model->getBoxCode($f['code'],$id);
    		$con = array('id'=> $id,'sid'=>__SID__);
    		// update with lang    		
    		Yii::$app->db->createCommand()->update(Box::tableName(),$f,$con)->execute();    	
    		$this->updateSlug($id, $f);
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		btnClickReturn($btn,$id,$tab);
    	}
    	return $this->render($this->action->id, [
    			'v' => $model,
    			'model'=>$this->model,
    			'id'=>Yii::$app->request->get('id',0),
    			'articles_list'=>Content::getListItem(['in'=>isset($model['articles_list']) ? $model['articles_list'] : []]),
    			'filters'=>Filters::getListItem(['in'=>isset($model['filters']) ? $model['filters'] : []]),
    			'filter_by'=>Filters::getListItem(['in'=>isset($model['filter_by']) ? $model['filter_by'] : []]),
    			'forms'=>AdForms::getUserForms(['is_content'=>1]),
    			'attrs'=>Content::getListAttrsByType(),
    	]);
    	
    }
    
    private function updateSlug($id, $f){
    	$isSlug = false; $route = '';
    	$biz = post('biz');
    	if(isset($biz['make_url']) && $biz['make_url'] == 'on'){
    		$route = isset($biz['route']) ? $biz['route'] : '';   	
    	 	if($route != ""){
    			$url = \app\modules\admin\models\Slugs::getSlug(unMark($f['title']), $id);
    			\app\modules\admin\models\Slugs::updateSlug($url,$id,$route,2);
    			\app\modules\admin\models\Siteconfigs::updateBizrule($this->model->tableName(),['id'=>$id,'sid'=>__SID__],['url_link'=>cu([DS.$url])]);
    	 	}elseif(isset($f['menu_id']) && $f['menu_id']>0){
    	 		\app\modules\admin\models\Siteconfigs::updateBizrule($this->model->tableName(),['id'=>$id,'sid'=>__SID__],['url_link'=>cu(false,false,['category_id'=>$f['menu_id']])]);
    	 	}
    	}
    	 
    }
     
    /**
     * Deletes an existing Box model.
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
     * Finds the Box model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $lang
     * @return Box the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Box::findOne(['id' => $id,'sid'=>__SID__])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
