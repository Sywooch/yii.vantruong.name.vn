<?php
namespace app\modules\admin\controllers;
use Yii;
use app\modules\admin\models\Templete;
use app\modules\admin\models\FormActive;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\controllers\CrsController; 
use app\modules\admin\models\AdTemplete;
use yii\db\Query;

/**
 * Ad_moduleController implements the CRUD actions for Templete model.
 */
class TempleteController extends CrsController
{
    /**
     * @inheritdoc
     */
	private $model;
	
    public function behaviors()
    {
    	$this->model = new Templete();
    	return parent::__behaviors();
    }

    /**
     * Lists all Templete models.
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
    			//  'dataProvider' => $dataProvider,
    	]);
    }

    /**
     * Displays a single Templete model.
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
     * Creates a new Templete model.
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
    		$f = FormActive::getFormSubmit(); $id = 0;    		
    		$name = $f['name'];
    		if((new Query())->from('templetes')->where([
    					'name'=>$name
    			])->count(1) == 0){
    			Yii::$app->db->createCommand()->insert(Templete::tableName(),$f)->execute();
    			$id = Yii::$app->db->createCommand("select max(id) from ".Templete::tableName())->queryScalar();
    			//
    			copy_all(Yii::getAlias('@views/site') . DIRECTORY_SEPARATOR .'default',
    					 Yii::getAlias('@views/site') . DIRECTORY_SEPARATOR .$name);
    			copy_all(Yii::getAlias('@themes') . DIRECTORY_SEPARATOR .'default',
    					 Yii::getAlias('@themes') . DIRECTORY_SEPARATOR .$name);
    		}
    		
    		
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		btnClickReturn($btn,$id,$tab);
    	}
    	return $this->render('edit', [
    		'v'=>[],	
    		'model'=>$this->model,	
    			'category'=>AdTemplete::getList(['limit'=>1000]),
    		'id'=>Yii::$app->request->get('id',0)
    	]);
    	 
    }
	     
    /**
     * Updates an existing Templete model.
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
    		$con = array('id'=> $id);
    		// update with lang    		
    		 
    		$protect = ['default','admin','ajax','login','main','sajax'];
    		$old_name = post('old_name');
    		$name = isset($f['name']) ? $f['name'] : $old_name;
    		if($name != $old_name && !in_array($name, $protect)){
    			if((new Query())->from('templetes')->where([
    					'name'=>$name
    			])->count(1) == 0){
    				
    				// rename themes
    				$path = Yii::getAlias('@themes') . DIRECTORY_SEPARATOR . $name;
    				if(!file_exists(Yii::getAlias('@themes') . DIRECTORY_SEPARATOR . $old_name)){
    					@mkdir($path,0755,true);
    				}else{
    					@rename(Yii::getAlias('@themes') . DIRECTORY_SEPARATOR . $old_name, $path);
    				}
    				// rename layout
    				if(file_exists(Yii::getAlias('@views/layouts') . DIRECTORY_SEPARATOR . $old_name . '.php')){
    					@rename(Yii::getAlias('@views/layouts') . DIRECTORY_SEPARATOR . $old_name . '.php', Yii::getAlias('@views/layouts') . DIRECTORY_SEPARATOR . $name . '.php');
    				}
    				// rename site
    				if(file_exists(Yii::getAlias('@views/site') . DIRECTORY_SEPARATOR . $old_name)){
    					@rename(Yii::getAlias('@views/site') . DIRECTORY_SEPARATOR . $old_name , Yii::getAlias('@views/site') . DIRECTORY_SEPARATOR . $name  );
    				}else {
    					writeFile(Yii::getAlias('@views/site') . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . 'index.php','<h1 style="text-align:center; margin-top:30px;color:green">Chào mừng bạn đến với <b>'.$_SERVER['HTTP_HOST'].'</b></h1>');
    					
    				}
    				
    			}else{
    				unset($f['name']);
    			}
    		}else{
    			unset($f['name']);
    		}
    		Yii::$app->db->createCommand()->update(Templete::tableName(),$f,$con)->execute();
    		//$this->model->updateTempleteFolder($id, $f);
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		btnClickReturn($btn,$id,$tab);
    	}
    	return $this->render($this->action->id, [
    			'v' => $model,
    			'model'=>$this->model,
    			'id'=>Yii::$app->request->get('id',0),
    			'category'=>AdTemplete::getList(['limit'=>1000])
    	]);
    	
    }
	
     
    /**
     * Deletes an existing Templete model.
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
     * Finds the Templete model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $lang
     * @return Templete the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Templete::findOne(['id' => $id ])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
