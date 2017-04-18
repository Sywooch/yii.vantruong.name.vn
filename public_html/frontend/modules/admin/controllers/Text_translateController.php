<?php
namespace app\modules\admin\controllers;
use Yii;
use app\modules\admin\models\TextTranslate;
use app\modules\admin\models\FormActive;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\controllers\CrsController; 
use app\modules\admin\models\AdLanguage;
use yii\db\Query;

/**
 * Ad_moduleController implements the CRUD actions for TextTranslate model.
 */
class Text_translateController extends CrsController
{
    /**
     * @inheritdoc
     */
	private $model;
	
	public function behaviors()
	{
		$this->model = new TextTranslate();
		return parent::__behaviors();
	}

    /**
     * Lists all TextTranslate models.
     * @return mixed
     */
    public function actionIndex()
    {
    	$model = new TextTranslate();
    	$path = $this->action->id;
    	if(defined('CONTROLLER_CODE') && !is_numeric(CONTROLLER_CODE) && strlen(CONTROLLER_CODE)>1){
    		$fp = $this->viewPath . '/_forms/'.CONTROLLER_CODE .'.php';
    		if(file_exists($fp)){
    			$path = '_forms/'. CONTROLLER_CODE;
    		}
    	}
    	if(Yii::$app->request->method == 'POST'){
    		 
    		//
    		
    		if(post('ajaxSubmit') == 1){
    			$f = post('f');
    			if($f['value'] != ""){
    				if((new Query())->from(TextTranslate::tableName())->where(['value'=>$f['value'],'lang'=>DEFAULT_LANG])->count(1) == 0){
    					Yii::$app->db->createCommand()->insert(TextTranslate::tableName(),['value'=>$f['value'],'id'=>$this->model->getID()])->execute();
    				}
    				echo json_encode(['event'=>'redirect_link','target'=>cu([__RCONTROLLER__.DS,'filter_text'=>$f['value']])]);
    			}
    			
    			exit;
    		}else{
    			$btn = post('btnSubmit');
    			$tab = post('currentTab');
    			btnClickReturn($btn,$id,$tab);
    		}
    	}
        return $this->render($path, [
            'model' => $this->model,
        	'l' => $this->model->getList(Yii::$app->request->get()),
        	'language'=>AdLanguage::getList(['is_active' =>1])	
        ]);
    }

    /**
     * Displays a single TextTranslate model.
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
     * Creates a new TextTranslate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	return $this->actionAdd();
    }
    
    public function actionAdd()
    {
    	//$model = new TextTranslate();    	
    	if(Yii::$app->request->method == 'POST'){
    		FormActive::setBooleanFields($this->model->getBooleanFields());
    		$f = FormActive::getFormSubmit();    		
    		// update with lang
    		Yii::$app->db->createCommand()->insert(TextTranslate::tableName(),$f)->execute();
    		$id = Yii::$app->db->createCommand("select max(id) from ".TextTranslate::tableName())->queryScalar();
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		btnClickReturn($btn,$id,$tab);
    	}
    	return $this->render('edit', [
    			'model'=>$this->model,
    			'v'=>[],
    			'id'=>Yii::$app->request->get('id',0)
    	]);
    	 
    }
	     
    /**
     * Updates an existing TextTranslate model.
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
    		$con = array('id'=> $id,'lang'=>ADMIN_LANG);
    		// update with lang    		
    		Yii::$app->db->createCommand()->update(TextTranslate::tableName(),$f,$con)->execute();    		    	
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		btnClickReturn($btn,$id,$tab);
    	}
    	
    	
    	return $this->render($this->action->id, [
    		'v' => $model,
    		'model'=>$this->model,	
    		'id'=>Yii::$app->request->get('id',0)
    	]);    		
    	
    }
	
     
    /**
     * Deletes an existing TextTranslate model.
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
     * Finds the TextTranslate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $lang
     * @return TextTranslate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $lang = __LANG__)
    {
        if (($model = TextTranslate::findOne(['id' => $id,'lang'=>$lang])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
