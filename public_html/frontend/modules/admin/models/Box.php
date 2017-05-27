<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class Box extends \yii\db\ActiveRecord
{
	public static function getBooleanFields(){
		return [
				'is_active',	
		];
	}
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%box}}';
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

    public static function getID(){
    	return (new Query())->select('max(id) +1')->from(self::tableName())->scalar();
    }     
    
    public static function getBoxCode($code,$id = 0){
    	if($id == 0) $id = self::getID();
    	if($code == '') $code = 'ub';
    	while((new Query())->from(self::tableName())
    			->where(['code'=>$code,'sid'=>__SID__,'lang'=>__LANG__])
    			->andWhere(['>','state',-2])
    			->andWhere(['not in','id',$id])
    			->count(1) > 0
    			){
    		$code .= '-'.$id;
    	}
    	return $code;
    }
    
    public static function getAll(){
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	->where(['a.sid'=>__SID__])
    	->andWhere(['>','a.state',-2]);
    	return $query->asArray()->all();
    }
     
    public static function getItem($id=0,$o=[]){    	
    	$item = static::find()
    	->where(['id'=>$id, 'sid'=>__SID__]);
    
    	$item = $item->asArray()->one();
    	 
    	return $item;
    }
    /*
     * 
     */
    public static function getList($o = []){
    	 
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.module'=>SORT_ASC,'a.position'=>SORT_ASC, 'a.title'=>SORT_ASC,'a.id'=>SORT_DESC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);    
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';    	
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : -1;
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$offset = ($p-1) * $limit;
    	$query = (new Query())
    	->from(['a'=>self::tableName()])
    	->where(['a.sid'=>__SID__])
    	->andWhere(['>','a.state',-2]);
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['like', 'title', $filter_text]);
    	}
    	if(is_numeric($type_id) && $type_id > -1){
    		$query->andWhere(['a.type_id'=>$type_id]);
    	}
    	
    	if(is_numeric($parent_id) && $parent_id > -1){
    		$query->andWhere(['a.parent_id'=>$parent_id]);
    	}
    	if(is_numeric($is_active) && $is_active > -1){
    		$query->andWhere(['a.is_active'=>$is_active]);
    	}
    	$c = 0;
    	if($count){
    		//$query->select('count(1)');
    		$c = $query->count(1);
    	}
    	 
    	$query
    	->select(['a.*','categoryName'=>'(select title from {{%site_menu}} where id=a.menu_id)'])
    	->orderBy($order_by)
    	->offset($offset)
    	->limit($limit);
    	$l = $query
    	//->asArray()
    	->all();
    	//
    	//view($query->createCommand()->getRawSql(),true);
    	return [
    			'listItem'=>$l,
    			'total_records'=>$c,
    			'total_pages'=>ceil($c/$limit),
    			'limit'=>$limit,
    			'p'=>$p,
    	];
    	
    }
}
