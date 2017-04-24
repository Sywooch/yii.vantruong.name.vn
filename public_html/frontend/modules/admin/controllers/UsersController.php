<?php
namespace app\modules\admin\controllers;
use Yii;
use app\modules\admin\models\Users;
use app\modules\admin\models\Local;
use app\modules\admin\models\Permission;
use app\modules\admin\models\FormActive;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\controllers\CrsController; 

/**
 * Ad_moduleController implements the CRUD actions for Users model.
 */
class UsersController extends CrsController
{
    /**
     * @inheritdoc
     */
	private $model;
	
    public function behaviors()
    {
    	$this->model = new Users();
    	return parent::__behaviors();
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {    	 
    	//view("{{%users}}",true);
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
     * Displays a single Users model.
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
     * Creates a new Users model.
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
    		Yii::$app->db->createCommand()->insert(Users::tableName(),$f)->execute();
    		$id = Yii::$app->db->createCommand("select max(id) from ".Users::tableName())->queryScalar();
    		$this->model->update_groups($id);
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		btnClickReturn($btn,$id,$tab);
    	}
    	return $this->render('edit', [
    		'v'=>[],	
    		'model'=>$this->model,	
    		'permission'=>new Permission(),	
    		'local'=>new Local(),
    		'id'=>Yii::$app->request->get('id',0)
    	]);
    	 
    }
	     
    /**
     * Updates an existing Users model.
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
    		Yii::$app->db->createCommand()->update(Users::tableName(),$f,$con)->execute();    		
    		$this->model->update_groups($id);
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		btnClickReturn($btn,$id,$tab);
    	}
    	return $this->render($this->action->id, [
    			'v' => $model,
    			'model'=>$this->model,
    			'permission'=>new Permission(),
    			'local'=>new Local(),
    			'id'=>Yii::$app->request->get('id',0),
    	]);
    	
    }
	
     
    /**
     * Deletes an existing Users model.
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
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $lang
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    
    protected function findModel($id, $lang = __LANG__)
    {
    	//Users::setNewProperty('groups'); 
        if (($model = Users::find(['id' => $id,'sid'=>__SID__])->asArray()->one()) !== null) {
         
        		$sql = "select * from ".Users::tableGroup()." as a where a.id in(select group_id from ".Users::tableToGroup()." where user_id=".$model['id'].")";
        		$g = Yii::$app->db->createCommand($sql)->queryAll();
        		view($model,true);  
        		//$model->setAttributes(['groups'=>$g]) ; 
        		$v = array(); $vID = array();
        		if(!empty($g)){
        			foreach ($g as $x){
        				$v[] = $x['title'];
        				$vID[] = $x['id'];
        			}
        		}
        		//$model->permission = ['groups'=> $g,'group_id' => $vID,'groupName' => implode(' | ', $v)]; 
        		 
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
