<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class RoomsCategorys extends \yii\db\ActiveRecord
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
        return '{{%rooms_categorys}}';
    }
    public static function tableGroup(){
    	return '{{%rooms_groups}}';
    }
    
    public static function tablePrice(){
    	return '{{%price_to_car}}';
    }
    public static function tableToSupplier(){
    	return '{{%rooms_to_hotel}}';
    }
    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
        ];
    }
    public static function getItem($id=0,$o=[]){
    	$item = static::find()
    	->where(['id'=>$id, 'sid'=>__SID__]);
    
    	$item = $item->asArray()->one();
    
    	return $item;
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            
        ];
    }

    public function getID(){
    	$sql = "select max(id) +1 from ".self::tableName();
    	return Yii::$app->db->createCommand($sql)->queryScalar();
    }     
     
    public static function getRoomBySupplier($id){
    	$query = static::find()
    	->select(['a.*','b.quantity'])
    	->from(['a'=>self::tableName()])
    	->innerJoin(['b'=>self::tableToSupplier()],'a.id=b.room_id')
    	->where(['b.parent_id'=>$id])
    	->orderBy(['a.title'=>SORT_ASC]);
    	return $query->asArray()->all();
    }
    
    public static function getAllRooms($type_id = CONTROLLER_CODE,$o = []){
    	$in = isset($o['in']) ? $o['in'] : [];
    	if(!is_array($in) && $in != "") $in = explode(',', $in);
    	$not_in = isset($o['not_in']) ? $o['not_in'] : [];
    	if(!is_array($not_in) && $not_in != "") $not_in = explode(',', $not_in);
    	$query = static::find()
    	->select(['a.*'])
    	->from(['a'=>self::tableName()])
    	//->innerJoin(['b'=>self::tableToSupplier()],'a.id=b.room_id')
    	->where(['a.type_id'=>$type_id])
    	->orderBy(['a.title'=>SORT_ASC]);
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['or',
    				['like', 'a.title', $filter_text],
    				['like', 'a.note', $filter_text]
    				]);
    	}
    	if(!empty($in)){
    		$query->andWhere(['a.id'=>$in]);
    	}
    	if(!empty($not_in)){
    		$query->andWhere(['not in','a.id',$not_in]);
    	}
    	return $query->asArray()->all();
    }
    
    
    public static function getAll($o = []){
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : -1;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.title'=>SORT_ASC,'a.id'=>SORT_DESC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : 1;
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : CONTROLLER_CODE;
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$not_in = isset($o['not_in']) ? $o['not_in'] : [];
    	$in = isset($o['in']) ? $o['in'] : [];
    	if(!is_array($in) && $in != "") $in = explode(',', $in);
    	if(!is_array($not_in) && $not_in != "") $not_in = explode(',', $not_in);
    	$offset = ($p-1) * $limit;
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	->where(['a.sid'=>__SID__,'a.type_id'=>$type_id])
    	->andWhere(['>','a.state',-2]);
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['like', 'a.title', $filter_text]);
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
    	if(is_array($in) && !empty($in)){
    		$query->andWhere(['a.id'=>$in]);
    	}
    	if(is_array($not_in) && !empty($not_in)){
    		$query->andWhere(['not in','a.id',$not_in]);
    	}
    
    	$query->select(['a.*'])
    	->orderBy($order_by);
    	//->offset($offset)
    	//->limit($limit);
    	if($limit > 0){
    		$query
    		->offset($offset)
    		->limit($limit);
    	}
    	return $query->asArray()->all();
    
    
    }
    
    public static function getAvailabledRooms($o = []){
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : -1;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.title'=>SORT_ASC,'a.id'=>SORT_DESC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : 1;
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : CONTROLLER_CODE;
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$not_in = isset($o['not_in']) ? $o['not_in'] : [];
    	$in = isset($o['in']) ? $o['in'] : [];
    	if(!is_array($in) && $in != "") $in = explode(',', $in);
    	if(!is_array($not_in) && $not_in != "") $not_in = explode(',', $not_in);
    	$offset = ($p-1) * $limit;
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	->where(['a.sid'=>__SID__ ])
    	->andWhere(['>','a.state',-2]);
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['like', 'a.title', $filter_text]);
    	}
    	if($supplier_id > 0){
    		$query->andWhere(['not in', 'a.id', (new Query())->select('room_id')->from(['rooms_to_hotel'])->where(['parent_id'=>$supplier_id])]);
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
    	if(is_array($in) && !empty($in)){
    		$query->andWhere(['a.id'=>$in]);
    	}
    	if(is_array($not_in) && !empty($not_in)){
    		$query->andWhere(['not in','a.id',$not_in]);
    	}
    if($supplier_id>0){
    	
    }
    	$query->select(['a.*'])
    	->orderBy($order_by);
    	//->offset($offset)
    	//->limit($limit);
    	//if($limit > 0){
    	//	$query
    	//	->offset($offset)
    	//	->limit($limit);
    	//}
    	//view($query->createCommand()->getRawSql());
    	return $query->asArray()->all();
    
    
    }
    
    /*
     * 
     */
    public static function getList($o = []){    	
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['title'=>SORT_ASC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : (defined('CONTROLLER_CODE') ? CONTROLLER_CODE : -1);
    	
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$not_in = isset($o['not_in']) ? $o['not_in'] : [];
    	$in = isset($o['in']) ? $o['in'] : [];
    	if(!is_array($in) && $in != "") $in = explode(',', $in);
    	if(!is_array($not_in) && $not_in != "") $not_in = explode(',', $not_in);
    	$offset = ($p-1) * $limit;

    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	//->where(['a.type_id'=>CONTROLLER_CODE])
    	->where(['>','a.state',-2]); 
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['like', 'a.title', $filter_text]);
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
    	if(!empty($in)){
    		$query->andWhere(['a.id'=>$in]);
    	}
    	if(!empty($not_in)){
    		$query->andWhere(['not in','a.id',$not_in]);
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
    public static function get_list_room_group($parent_id=0){    	
    	return static::find()->from(self::tableGroup())->where(['parent_id'=>$parent_id,'sid'=>__SID__])->asArray()->all();
    }
}
