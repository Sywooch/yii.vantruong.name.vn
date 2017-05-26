<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class Adverts extends \yii\db\ActiveRecord
{
	public static function getBooleanFields(){
		return [
				'is_active',//'is_all','is_desktop','is_mobile',
				'set_language'
		];
	}
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%adverts}}';
    }
    public static function tableCategory()
    {
    	return '{{%adverts_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            
        ];
    }
	
    public static function getSetLanguage($typ_id = 0){
    	return (new Query())->select('set_language')->from(self::tableCategory())->where(['id'=>$typ_id])->scalar();
    }
    
    public function getID(){
    	return (new Query())->select('max(id) +1')->from(self::tableName())->scalar();
    }     
     
    public static function getItem($id=0,$o=[]){    	
    	$item = static::find()
    	->from(getParam('child') == 'list' ? self::tableName() : self::tableCategory())
    	->where(['id'=>$id]);
    	//var_dump(Yii::$app->user->can([ROOT_USER])); 
    	if(Yii::$app->user->can([ROOT_USER])){
    		$item->andWhere(['sid'=>[0,__SID__]]);
    	}else{
    		$item->andWhere(['sid'=>__SID__]);
    	}
    	$item = $item->asArray()->one(); 
    	 
    	return $item;
    }
    /*
     * 
     */
    public static function getList($o = []){
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.code'=>SORT_ASC,'a.id'=>SORT_DESC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);    
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';    	
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$category_id = isset($o['category_id']) ? $o['category_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : -1;
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$offset = ($p-1) * $limit;
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	//->where(['a.sid'=>__SID__])
    	->where(['>','a.state',-2]);
    	if(Yii::$app->user->can([ROOT_USER])){
    		$query->andWhere(['a.sid'=>[0,__SID__]]);
    	}else{
    		$query->andWhere(['a.sid'=>__SID__]);
    	}
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['like', 'title', $filter_text]);
    	}
    	if(is_numeric($category_id) && $category_id > -1){
    		$query->andWhere(['a.category_id'=>$category_id]);
    	}
    	if(is_numeric($type_id) && $type_id > -1){
    		$query->andWhere(['a.type'=>$type_id]);
    	}
    	if(is_numeric($parent_id) && $parent_id > -1){
    		$query->andWhere(['a.parent_id'=>$parent_id]);
    	}
    	if(is_numeric($is_active) && $is_active > -1){
    		$query->andWhere(['a.is_active'=>$is_active]);
    	}
    	$c = 0;
    	if($count){
    		$query->select('count(1)');
    		$c = $query->scalar();
    	}
    	$query->select(['a.*'])
    	->orderBy($order_by)
    	->offset($offset)
    	->limit($limit);
    	$l = $query->asArray()->all();
    	//
    	return [
    			'listItem'=>$l,
    			'total_records'=>$c,
    			'total_pages'=>ceil($c/$limit),
    			'limit'=>$limit,
    			'p'=>$p,
    	];
    	
    }
    
    public static function getListCategory($o = []){
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.title'=>SORT_ASC,'a.id'=>SORT_DESC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : -1;
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	//$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$offset = ($p-1) * $limit;
    	$query = static::find()
    	->from(['a'=>self::tableCategory()])
    	//->where(['a.sid'=>__SID__])
    	->where(['>','a.state',-2]);
    	if(Yii::$app->user->can([ROOT_USER])){
    		$query->andWhere(['a.sid'=>[0,__SID__]]);
    	}else{
    		$query->andWhere(['a.sid'=>__SID__]);
    	}
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['like', 'title', $filter_text]);
    	}
    	//if(is_numeric($type_id) && $type_id > -1){
    	//	$query->andWhere(['a.type_id'=>$type_id]);
    	//}
    	//if(is_numeric($parent_id) && $parent_id > -1){
    	//	$query->andWhere(['a.parent_id'=>$parent_id]);
    	//}
    	if(is_numeric($is_active) && $is_active > -1){
    		$query->andWhere(['a.is_active'=>$is_active]);
    	}
    	$c = 0;
    	if($count){
    		$query->select('count(1)');
    		$c = $query->scalar();
    	}
    	$query->select(['a.*'])
    	->orderBy($order_by)
    	->offset($offset)
    	->limit($limit);
    	$l = $query->asArray()->all();
    	//
    	// view($query->createCommand()->getRawSql());
    	return [
    			'listItem'=>$l,
    			'total_records'=>$c,
    			'total_pages'=>ceil($c/$limit),
    			'limit'=>$limit,
    			'p'=>$p,
    	];
    	 
    }
    
    public function updateTask($id=0){
    	$task = isset($_POST['task']) ? $_POST['task'] : array();
    	if($id >0){
    		if(!empty($task)){
    			if(isset($task['active_from_date']) && check_date_string($task['active_from_date'])){
    				$code = 'ACTIVE_ADVERT_CATEGORY';
    				TaskScheduler::updateScheduler([
    						'code'=>$code,
    						'item_id'=>$id,
    						'sid'=>__SID__,
    						'time'=>ctime(['string'=>$task['active_from_date']])
    				]);
    			}
    			if(isset($task['active_to_date']) && check_date_string($task['active_to_date'])){
    				$code = 'DEACTIVE_ADVERT_CATEGORY';
    				TaskScheduler::updateScheduler([
    						'code'=>$code,
    						'item_id'=>$id,
    						'sid'=>__SID__,
    						'time'=>ctime(['string'=>$task['active_from_date']])
    				]);
    			}
    
    			if(ctime(array('string'=>$task['active_from_date'],'return_type'=>1)) > time()+300){
    				Yii::$app->db->createCommand()->update(self::tableName(),['is_active'=>0],['id'=>$id,'sid'=>__SID__])->execute();
    			}
    		}
    		Siteconfigs::updateBizrule(getParam('child') == 'list' ? self::tableName() :  self::tableCategory(),['id'=>$id],$task);
    		return true;
    	}
    	return false;
    }
}
