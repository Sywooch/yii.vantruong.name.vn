<?php
namespace app\modules\admin\controllers;
use Yii;
use app\modules\admin\models\Menu;
use app\modules\admin\models\FormActive;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\controllers\CrsController; 
use app\modules\admin\models\AdForms;
use app\modules\admin\models\Filters;
use app\modules\admin\models\DeparturePlaces;
use app\modules\admin\models\Slugs;
use yii\db\Query;
use app\modules\admin\models\Siteconfigs;

/**
 * Ad_moduleController implements the CRUD actions for Menu model.
 */
class MenuController extends CrsController
{
    /**
     * @inheritdoc
     */
	private $model;
	
    public function behaviors()
    {
    	$this->model = new Menu();
    	return parent::__behaviors();
    }

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {    	 
    	//Menu::updateAllLevel();
    	//Menu::update_lft();
    	$path = $this->action->id;
    	if(defined('CONTROLLER_CODE') && !is_numeric(CONTROLLER_CODE) && strlen(CONTROLLER_CODE)>1){
    		$fp = $this->viewPath . '/_forms/'.CONTROLLER_CODE .'.php';
    		if(file_exists($fp)){
    			$path = '_forms/'. CONTROLLER_CODE;
    		}
    	}
    	return $this->render($path, [
    			'model' => $this->model,
    			'all'=>$this->model->getAll(),
    			'l'=>$this->model->getList(Yii::$app->request->get()),
    			//'current_position'=>Menu::getPosition($id)
    	]);
    }

    /**
     * Displays a single Menu model.
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
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	return $this->actionAdd();
    }
    
    public function actionAdd()
    {    	
    	if(Yii::$app->user->can([ROOT_USER,ADMIN_USER, 'form-'.getParam('type').'-add-child'])){
    	if(Yii::$app->request->method == 'POST'){
    		FormActive::setBooleanFields($this->model->getBooleanFields());
    		$f = FormActive::getFormSubmit();
    		if($f['type'] == -1){
    			$f['type'] = (new Query())->select('type')->from(Menu::tableName())->where(['id'=>$f['parent_id']])->scalar();
    		}
    		$f['route'] = isset($f['route']) ? $f['route'] : $f['type'];
    		
    		$f['sid'] = __SID__; $id = 0;
    		$biz = post('biz',[]);
    		$f['type'] = $f['type'] == 'manual' && isset($biz['link_target']) ? $biz['link_target'] : $f['type'];
    		if($f['type'] == -1){
    			$f['type'] = (new Query())->select('type')->from(Menu::tableName())->where(['id'=>$f['parent_id']])->scalar();
    		}
    		$titles = explode(',', $f['title']); 
    		foreach ($titles as $title){
    			if(trim($title) != ""){
    				$f['title'] = $title;
    				$f['url'] = Slugs::getSlug(unMark($f['title']), $this->model->getID());
	    		if(Yii::$app->db->createCommand()->insert(Menu::tableName(),$f)->execute()){
	    			$id = Yii::$app->db->createCommand("select max(id) from ".Menu::tableName())->queryScalar();
	    			$this->model->updatePosition($id);
	    			$this->model->updateDestination($id);
	    			 			 
	    			$con = ['id'=>$id];
	    			Slugs::updateSlug($f['url'],$id,$f['type'],0,$biz);
	    			if(isset($biz['manual_link']) && $biz['manual_link'] == 'on'){
	    				Yii::$app->db->createCommand()->update(Menu::tableName(),['url_link'=>$biz['url_link']],$con)->execute();
	    			}else {
	    				Yii::$app->db->createCommand()->update(Menu::tableName(),['url_link'=>Yii::$app->zii->getUrl($f['url'])],$con)->execute();
	    				//
	    			}//if(isset($old['url']) && $old['url'] != $f['url']){
	    			Yii::$app->zii->generateSitemap([
	    				'updateDatabase'=>true
	    			]);
	    			//}	    			
	    		}}
    		}
    		//$this->model->update_lft();
    		//$this->model->updateAllLevel($f['parent_id']);
    		Yii::$app->zii->update_table_lft([
    				'table'=>$this->model->tableName(),
    				'sid'=>__SID__,
    				'orderBy'=>['position'=>SORT_ASC, 'title'=>SORT_ASC],
    				'level'=>true
    		]);
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		btnClickReturn($btn,$id,$tab);
    	}
    	return $this->render('edit', [
    		'v'=>[],	
    		'model'=>$this->model,	
    		'id'=>Yii::$app->request->get('id',0),
    			'all'=>$this->model->getAll(),
    			'igr'=>[],
    			'forms'=>AdForms::getUserForms(),
    			
    			'filters' =>Filters::getAll(array(
    					'code'=>'category_'. (isset($v['type']) ? $v['type'] : ''.getParam('type')),
    					'parent_id'=>0,
    			)),
    			'positions'=>$this->model->getMenuPosition([]),
    			'destination'=>DeparturePlaces::getList(['limit'=>1000,'is_destination'=>1]),
    			'selected_destination' => Menu::get_menu_departure(0,1),
    			
    	]);
    	}else{
    		return $this->render('../error/index');
    	}
    }
	     
    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $lang
     * @return mixed
     */
    public function actionUpdate($id)
    {    	
        return $this->actionEdit($id);
    }
    public static function getUrl($url, $id=0,$lang=ADMIN_LANG){
    	if($id > 0){}else{
    		$sql = "select max(id)+1 from ".self::tableName();
    		$id = Yii::$app->db->createCommand($sql)->queryScalar();
    	}
    	$id = $id > 0 ? $id : 0;
    	$sql = "select count(1) from ".self::tableName(). " as a where a.url = '$url' and a.id not in($id) and a.state>0 and a.lang='".$lang."'";
    	while (Yii::$app->db->createCommand($sql)->queryScalar() > 0){
    		$url .= '-' . $id;
    		$sql = "select count(1) from ".self::tableName(). " as a where a.url = '$url' and a.id not in($id) and a.state>0 and a.lang='".$lang."'";
    	}
    	return $url;
    }
    
    
    
    public function actionEdit($id)
    {    	
    	if(Yii::$app->user->can([ROOT_USER,ADMIN_USER,'site-'.$id.'-edit'])){
    	$model = $this->model->getItem($id);
    	if(Yii::$app->request->method == 'POST'){
    		FormActive::setBooleanFields($this->model->getBooleanFields());
    		$f = FormActive::getFormSubmit();    	
    		$old = post('old');
    		if($f['type'] == -1){
    			$f['type'] = (new Query())->select('type')->from(Menu::tableName())->where(['id'=>$f['parent_id']])->scalar();
    		}
    		
    		$f['url'] = Slugs::getSlug(isset($f['url']) && $f['url'] != "" ? $f['url'] : unMark($f['title']), $id);
    		
    		if(isset($old['url']) && $old['url'] != $f['url']){
    			Yii::$app->zii->generateSitemap([
    					'updateDatabase'=>true
    			]);
    		}
    		
    		$f['route'] = isset($f['route']) ? $f['route'] : $f['type'];
    		
    		$con = array('id'=> $id,'sid'=>__SID__);
    		  		
    		Yii::$app->db->createCommand()->update(Menu::tableName(),$f,$con)->execute();
    		$this->model->updatePosition($id);
    		$this->model->updateDestination($id);
    		//$this->model->update_lft();
    		
    		Yii::$app->zii->update_table_lft([
    			'table'=>$this->model->tableName(),
    			'sid'=>__SID__,
    				'orderBy'=>['position'=>SORT_ASC, 'title'=>SORT_ASC],
    				'level'=>true
    		]);
    		
    		//$this->model->updateAllLevel($f['parent_id']);
    		//view(Yii::$site['seo']);
    		$biz = post('biz',[]);
    		$f['type'] = $f['type'] == 'manual' && isset($biz['link_target']) ? $biz['link_target'] : $f['type'];
    		Slugs::updateSlug($f['url'],$id,$f['type'],0,$biz);
    		if(isset($biz['manual_link']) && $biz['manual_link'] == 'on'){
    			Yii::$app->db->createCommand()->update(Menu::tableName(),['url_link'=>$biz['url_link']],$con)->execute();
    		}else {
    			Yii::$app->db->createCommand()->update(Menu::tableName(),['url_link'=>Yii::$app->zii->getUrl($f['url'])],$con)->execute();
    			//
    		}
    		//view(Yii::$app->zii->getUrl($f['url']),true);
    		//Slugs::updateSlug($f['url'],$id,$f['type'],0,post('biz',[]));
    		//Yii::$app->db->createCommand()->update(Menu::tableName(),['url_link'=>Yii::$app->zii->getUrl($f['url'])],$con)->execute();
    		$btn = post('btnSubmit');
    		$tab = post('currentTab');
    		
    		btnClickReturn($btn,$id,$tab);
    	}
    	return $this->render($this->action->id, [
    			'v' => $model,
    			'model'=>$this->model,
    			'id'=>Yii::$app->request->get('id',0),
    			'all'=>$this->model->getAll(),
                'igr'=>$this->model->get_all_child_id($model['id']),
    			'forms'=>AdForms::getUserForms(),
    			 
    			'filters' =>Filters::getAll(array(
    					'code'=>'category_'. (isset($v['type']) ? $v['type'] : ''.getParam('type')),
    					'parent_id'=>0,
    			)),
    			'positions'=>$this->model->getMenuPosition($model),
    			'destination'=>DeparturePlaces::getList(['limit'=>1000,'is_destination'=>1]),
    			'selected_destination' => Menu::get_menu_departure($id,1),
    			 
    	]);
    	}else{
    		return $this->render('../error/index');
    	}
    	
    }
	
     
    /**
     * Deletes an existing Menu model.
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
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $lang
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne(['id' => $id,'sid'=>__SID__])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
