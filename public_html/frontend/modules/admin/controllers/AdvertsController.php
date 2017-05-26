<?php
namespace app\modules\admin\controllers;
use Yii;
use app\modules\admin\models\Adverts;
use app\modules\admin\models\FormActive;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\controllers\CrsController; 

/**
 * Ad_moduleController implements the CRUD actions for Adverts model.
 */
class AdvertsController extends CrsController
{
    /**
     * @inheritdoc
     */
	private $model;
	
    public function behaviors()
    {
    	$this->model = new Adverts();
    	return parent::__behaviors();
    }

    /**
     * Lists all Adverts models.
     * @return mixed
     */
    public function actionIndex()
    {    	 
    	$path = $this->action->id ;
    	if(getParam('child') == 'list'){
    		$path = '_forms/list';
    	}
    	return $this->render($path, [
    			'model' => $this->model,
    			'l'=>getParam('child') == 'list' ?  $this->model->getList(Yii::$app->request->get()) :
    			$this->model->getListCategory(Yii::$app->request->get()),
    			
    	]);
    }

    /**
     * Displays a single Adverts model.
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
     * Creates a new Adverts model.
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
    		if(isset($f['is_all']) && $f['is_all'] == 1){
    			
    		}else{
    			$f['sid'] = __SID__; 
    		}
    		if(isset($_POST['f']['is_active']) && in_array($_POST['f']['is_active'], [-1])){
    			unset($f['is_active']);
    		}
    		$f['lang'] = __LANG__;
    		$f['set_language'] = $this->model->getSetLanguage($f['type']);
    		Yii::$app->db->createCommand()->insert(getParam('child') == 'list' ? $this->model->tableName() :  Adverts::tableCategory(),$f)->execute();
    		$id = Yii::$app->db->createCommand("select max(id) from ".(getParam('child') == 'list' ? $this->model->tableName() :  Adverts::tableCategory()))->queryScalar();
    		$this->model->updateTask($id);
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		btnClickReturn($btn,$id,$tab);
    	}
    	if(getParam('child') == 'list'){
    		$path = '_forms/edit';
    	}else{
    		$path = 'edit';
    	}
    	return $this->render($path, [
    		'v'=>$this->model->getItem(getParam('id',0)),	
    		'model'=>$this->model,	
    		'id'=>Yii::$app->request->get('id',0)
    	]);
    	 
    }
	     
    /**
     * Updates an existing Adverts model.
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
    		if(Yii::$app->user->can([ROOT_USER])){
    			$con = ['id'=> $id];
    		}else{
    			$con = ['id'=> $id,'sid'=>__SID__];
    		}
    		if(isset($_POST['f']['is_active']) && in_array($_POST['f']['is_active'], [-1])){
    			unset($f['is_active']);
    		}
    		$f['set_language'] = $this->model->getSetLanguage($f['type']);
    		// update with lang    		
    		Yii::$app->db->createCommand()->update(getParam('child') == 'list' ? $this->model->tableName() :  Adverts::tableCategory(),$f,$con)->execute();    		    	
    		$this->model->updateTask($id);
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		btnClickReturn($btn,$id,$tab);
    	}
    	return $this->render(getParam('child') == 'list' ? '_forms/edit' : $this->action->id, [
    			'v' => $model,
    			'model'=>$this->model,
    			'id'=>Yii::$app->request->get('id',0),
    	]);
    	
    }
	
     
    /**
     * Deletes an existing Adverts model.
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
     * Finds the Adverts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $lang
     * @return Adverts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Adverts::findOne(['id' => $id,'sid'=>__SID__])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
