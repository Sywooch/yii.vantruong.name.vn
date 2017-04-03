<?php
namespace app\modules\admin\controllers;
use Yii;
use app\modules\admin\models\Courses;
use app\modules\admin\models\FormActive;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\controllers\CrsController; 
use app\modules\admin\models\Menu;
use app\modules\admin\models\Content;
use app\modules\admin\models\Slugs;
use app\modules\admin\models\AdForms;

/**
 * Ad_moduleController implements the CRUD actions for Courses model.
 */
class CoursesController extends CrsController
{
    /**
     * @inheritdoc
     */
	private $model;
	
    public function behaviors()
    {
    	$this->model = new Courses();
    	return parent::__behaviors();
    }

    /**
     * Lists all Courses models.
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
     * Displays a single Courses model.
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
     * Creates a new Courses model.
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
    		$f['url'] = Slugs::getSlug(isset($f['url']) && $f['url'] != "" ? $f['url'] : unMark($f['title']), $this->model->getID());
    		Yii::$app->db->createCommand()->insert(Courses::tableName(),$f)->execute();
    		$id = Yii::$app->db->createCommand("select max(id) from ".Courses::tableName())->queryScalar();
    		//Slugs::updateSlug($f['url'],$id,$f['type'],1,post('biz',[]));
    		$con = array('id'=> $id,'sid'=>__SID__);
    		$biz = post('biz',[]);
    		Slugs::updateSlug($f['url'],$id,$f['type'],1,$biz);
    		if(isset($biz['manual_link']) && $biz['manual_link'] == 'on'){
    			Yii::$app->db->createCommand()->update(Content::tableName(),['url_link'=>$biz['url_link']],$con)->execute();
    		}else {
    			Yii::$app->db->createCommand()->update(Content::tableName(),['url_link'=>Yii::$app->zii->getUrl($f['url'])],$con)->execute();
    			//
    		}
    		//Yii::$app->db->createCommand()->update(Content::tableName(),['url_link'=>Yii::$app->zii->getUrl($f['url'])],$con)->execute();
    		Content::updateCategory($id);
    		Content::updateBranches($id);
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		btnClickReturn($btn,$id,$tab);
    	}
    	return $this->render('edit', [
    		'v'=>$this->model->getItem(getParam('id',0)),	
    		'model'=>$this->model,	
    		'id'=>Yii::$app->request->get('id',0),
    			'all_menu'=>Menu::getAll(['type'=>CONTROLLER_CODE]),
    			'categorys'=>Content::getItemCategorys(getParam('id',0),['field'=>'id','return_type'=>0]),
    			'form'=>AdForms::getForm(['code'=>CONTROLLER_CODE]),
    	]);
    	 
    }
	     
    /**
     * Updates an existing Courses model.
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
    		FormActive::$jsonFields = $this->model->jsonFields();
    		$f = FormActive::getFormSubmit();    		     
    		$f['url'] = Slugs::getSlug(isset($f['url']) && $f['url'] != "" ? $f['url'] : unMark($f['title']), $id);
    		$con = array('id'=> $id,'sid'=>__SID__);
    		// update with lang    		
    		Yii::$app->db->createCommand()->update(Courses::tableName(),$f,$con)->execute();    	
    		
    		$biz = post('biz',[]);
    		Slugs::updateSlug($f['url'],$id,$f['type'],1,$biz);
    		if(isset($biz['manual_link']) && $biz['manual_link'] == 'on'){
    			Yii::$app->db->createCommand()->update(Content::tableName(),['url_link'=>$biz['url_link']],$con)->execute();
    		}else {
    			Yii::$app->db->createCommand()->update(Content::tableName(),['url_link'=>Yii::$app->zii->getUrl($f['url'])],$con)->execute();
    			//
    		}
    		//Slugs::updateSlug($f['url'],$id,$f['type'],1,post('biz',[]));
    		//Yii::$app->db->createCommand()->update(Content::tableName(),['url_link'=>Yii::$app->zii->getUrl($f['url'])],$con)->execute();
    		Content::updateCategory($id);
    		Content::updateBranches($id);
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		btnClickReturn($btn,$id,$tab);
    	}
    	return $this->render(!empty($model) ? $this->action->id : '../error/index',[
    			'v' => $model,
    			'model'=>$this->model,
    			'id'=>Yii::$app->request->get('id',0),
    			'all_menu'=>Menu::getAll(['type'=>CONTROLLER_CODE]),
    			//'categorys'=>$this->model->getItemCategorys($id,['field'=>'id','return_type'=>0]),
    			'categorys'=>Content::getItemCategorys($id,['field'=>'id','return_type'=>0]),
    			'form'=>AdForms::getForm(['code'=>CONTROLLER_CODE]),
    	]);
    	
    }
	
     
    /**
     * Deletes an existing Courses model.
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
     * Finds the Courses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $lang
     * @return Courses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Courses::findOne(['id' => $id,'sid'=>__SID__])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
