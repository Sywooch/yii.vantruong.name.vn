<?php
namespace app\modules\admin\controllers;
use Yii;
use app\modules\admin\models\AdLanguage;
use app\modules\admin\models\FormActive;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\controllers\CrsController; 
use app\modules\admin\models\Siteconfigs;

/**
 * Ad_moduleController implements the CRUD actions for AdLanguage model.
 */
class Ad_languageController extends CrsController
{
    /**
     * @inheritdoc
     */
	private $model;
	
    public function behaviors()
    {
    	$this->model = new AdLanguage();
    	return parent::__behaviors();
    }

    /**
     * Lists all AdLanguage models.
     * @return mixed
     */
    public function actionIndex()
    {    	 
    	//view($this->model->getList());
    	$path = $this->action->id;
    	if(defined('CONTROLLER_CODE') && !is_numeric(CONTROLLER_CODE) && strlen(CONTROLLER_CODE)>1){
    		$fp = $this->viewPath . '/_forms/'.CONTROLLER_CODE .'.php';
    		if(file_exists($fp)){
    			$path = '_forms/'. CONTROLLER_CODE;
    		}
    	}
    	//
    	if(Yii::$app->request->method == 'POST'){
    		FormActive::setBooleanFields($this->model->getBooleanFields());
    		$f = FormActive::getFormSubmit();
    		Siteconfigs::updateData(AdLanguage::$key, $f);
    	}
    	//
    	return $this->render($path, [
    			'model' => $this->model,
    			'list'=>$this->model->getList(),
    	]);
    }

    /**
     * Displays a single AdLanguage model.
     * @param integer $id
     * @param string $lang
     * @return mixed
     */
     

    /**
     * Creates a new AdLanguage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	return $this->actionAdd();
    }
    
    public function actionAdd()
    {    	
    	 
    }
	     
    /**
     * Updates an existing AdLanguage model.
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
     
    }
	
     
    /**
     * Deletes an existing AdLanguage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param string $lang
     * @return mixed
     */
    public function actionDelete($id)
    {
       
    }

    /**
     * Finds the AdLanguage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $lang
     * @return AdLanguage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdLanguage::findOne(['id' => $id,'sid'=>__SID__])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
