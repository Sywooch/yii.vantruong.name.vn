<?php
namespace app\modules\admin\controllers;
use Yii;
use app\modules\admin\models\TextInstructions;
use app\modules\admin\models\FormActive;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\controllers\CrsController; 
use yii\db\Query;

/**
 * Ad_moduleController implements the CRUD actions for TextInstructions model.
 */
class Text_instructionsController extends CrsController
{
    /**
     * @inheritdoc
     */
	private $model;
	
    public function behaviors()
    {
    	$this->model = new TextInstructions();
    	return parent::__behaviors();
    }

    /**
     * Lists all TextInstructions models.
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
     * Displays a single TextInstructions model.
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
     * Creates a new TextInstructions model.
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
    		$titles = explode(',', trim($f['title']));
    		foreach ($titles as $title){
    			if(trim($title) != "" && (new Query())->from(TextInstructions::tableName())->where(['title'=>trim($title),'sid'=>__SID__])->count(1) == 0){    			
    				$f['title'] = trim($title);
    				Yii::$app->db->createCommand()->insert(TextInstructions::tableName(),$f)->execute();
    			}
    			
    		}
    		$id = Yii::$app->db->createCommand("select max(id) from ".TextInstructions::tableName())->queryScalar();
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		btnClickReturn($btn,$id,$tab);
    	}
    	return $this->render('edit', [
    		'v'=>$this->model->getItem(getParam('id',0)),	
    		'model'=>$this->model,	
    		'id'=>Yii::$app->request->get('id',0)
    	]);
    	 
    }
	     
    /**
     * Updates an existing TextInstructions model.
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
    		Yii::$app->db->createCommand()->update(TextInstructions::tableName(),$f,$con)->execute();    		    	
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		btnClickReturn($btn,$id,$tab);
    	}
    	return $this->render(!empty($model) ? $this->action->id : '../error/index',[
    			'v' => $model,
    			'model'=>$this->model,
    			'id'=>Yii::$app->request->get('id',0),
    	]);
    	
    }
	
     
    /**
     * Deletes an existing TextInstructions model.
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
     * Finds the TextInstructions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $lang
     * @return TextInstructions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TextInstructions::findOne(['id' => $id,'sid'=>__SID__])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
