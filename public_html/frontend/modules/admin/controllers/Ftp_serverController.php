<?php
namespace app\modules\admin\controllers;
use Yii;
use app\modules\admin\models\FtpServer;
use app\modules\admin\models\FormActive;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\controllers\CrsController; 

/**
 * Ad_moduleController implements the CRUD actions for FtpServer model.
 */
class Ftp_serverController extends CrsController
{
    /**
     * @inheritdoc
     */
	private $model;
	
    public function behaviors()
    {
    	$this->model = new FtpServer();
    	return parent::__behaviors();
    }

    /**
     * Lists all FtpServer models.
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
     * Displays a single FtpServer model.
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
     * Creates a new FtpServer model.
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
    		$f['is_active'] = isset($f['is_active']) && cbool($f['is_active']) == 1 ? 1 : 0;
    		$f['ssl_mode'] = isset($f['ssl_mode']) && cbool($f['ssl_mode']) == 1 ? 1 : 0;
    		//
    		$f['label'] = eString($f['label']);
    		$f['host_address'] = eString($f['host_address']);
    		$f['web_address'] = eString($f['web_address']);
    		$f['root_directory'] = eString($f['root_directory']);
    		$fx = isset($_POST['fx']) ? $_POST['fx'] : array();
    		if(strlen($f['username'])>3){
    			$f['username'] = eString($f['username']);
    		}else{
    			unset($f['username']);
    		}
    		if(!empty($fx) && strlen($fx['password'])>5){
    			if($fx['password']==$fx['repassword']){
    				$f['password'] = eString($fx['password']);
    				$f['last_modify'] = date("Y-m-d H:i:s");
    			}
    		}
    		Yii::$app->db->createCommand()->insert(FtpServer::tableName(),$f)->execute();
    		$id = Yii::$app->db->createCommand("select max(id) from ".FtpServer::tableName())->queryScalar();
    		//
    		if($f['update_source'] == 'on'){
    		
    			$ftp = new \yii\web\FtpUpload($model);
    		
    			if($ftp->testConnected()){
    				$fp = Yii::getAlias('@webroot');
    				$ftp->nfileupload($fp . DS .'image.php', '/image.php');
    				$thumbs = __LIBS_PATH__ . DS . 'thumb';
    				$fps = (dirToArray($thumbs));
    				//$ftp = new \yii\web\FtpUpload($model);
    				//$ftp2 = new \yii\web\FtpUpload($model);
    				//view($model);
    				if(!empty($fps)){
    					foreach ($fps as $file){
    						//view(file_exists($file));
    						(new \yii\web\FtpUpload($model))->nfileupload($file, str_replace(__ROOT_PATH__, '', $file));
    					}
    				}
    			}
    		
    			 
    		}
    		//
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
     * Updates an existing FtpServer model.
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
    		$f['is_active'] = isset($f['is_active']) && cbool($f['is_active']) == 1 ? 1 : 0;
    		$f['ssl_mode'] = isset($f['ssl_mode']) && cbool($f['ssl_mode']) == 1 ? 1 : 0;
    		//
    		$f['label'] = eString($f['label']);
    		$f['host_address'] = eString($f['host_address']);
    		$f['web_address'] = eString($f['web_address']);
    		$f['root_directory'] = eString($f['root_directory']);
    		$fx = isset($_POST['fx']) ? $_POST['fx'] : array();
    		if(strlen($f['username'])>3){
    			$f['username'] = eString($f['username']);
    		}else{
    			unset($f['username']);
    		}
    		if(!empty($fx) && strlen($fx['password'])>5){
    			if($fx['password']==$fx['repassword']){
    				$f['password'] = eString($fx['password']);
    				$f['last_modify'] = date("Y-m-d H:i:s");
    			}
    		}
    		$con = array('id'=> $id,'sid'=>__SID__);
    		if($f['update_source'] == 'on'){
    			 
    			$ftp = new \yii\web\FtpUpload($model);
    			 
    			if($ftp->testConnected()){
    				$fp = Yii::getAlias('@webroot');    				 
    				$ftp->nfileupload($fp . DS .'image.php', '/image.php');
    				$thumbs = __LIBS_PATH__ . DS . 'thumb';
    				$fps = (dirToArray($thumbs));
    				//$ftp = new \yii\web\FtpUpload($model);
    				//$ftp2 = new \yii\web\FtpUpload($model);
    				//view($model);
    				if(!empty($fps)){
    					foreach ($fps as $file){
    						//view(file_exists($file));
    						(new \yii\web\FtpUpload($model))->nfileupload($file, str_replace(__ROOT_PATH__, '', $file));
    					}
    				}
    			}
    			 
    		 
    		}
    		// update with lang    		
    		Yii::$app->db->createCommand()->update(FtpServer::tableName(),$f,$con)->execute();    		    	
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
     * Deletes an existing FtpServer model.
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
     * Finds the FtpServer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $lang
     * @return FtpServer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FtpServer::findOne(['id' => $id,'sid'=>__SID__])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
