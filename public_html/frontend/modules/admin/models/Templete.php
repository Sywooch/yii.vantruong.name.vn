<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class Templete extends \yii\db\ActiveRecord
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
        return '{{%templetes}}';
    }
	public static function tableToShop()
    {
        return '{{%temp_to_shop}}';
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

    public function getID(){
    	$sql = "select max(id) +1 from ".self::tableName();
    	return Yii::$app->db->createCommand($sql)->queryScalar();
    }     
     
    public static function getItem($id=0,$o=[]){    	
    	$item = static::find()
    	->where(['id'=>$id]);
    
    	$item = $item->asArray()->one();
    	 
    	return $item;
    }
    public static function updateUserTemplete($temp_id,$sid){
    	//view($temp_id,true);
    	Yii::$app->db->createCommand()->update(self::tableToShop(),['state'=>2],['sid'=>$sid])->execute();
    	if((new Query())->from(self::tableToShop())->where(['temp_id'=>$temp_id, 'sid'=>$sid])->count(1)>0){
    		Yii::$app->db->createCommand()->update(self::tableToShop(),['state'=>1],['temp_id'=>$temp_id, 'sid'=>$sid])->execute();
    	}else{
    		Yii::$app->db->createCommand()->insert(self::tableToShop(),['temp_id'=>$temp_id, 'state'=>1,'sid'=>$sid])->execute();
    	}
    }
    public function updateTempleteFolder($id =0, $f = []){
    	$protect = ['default','admin'];
    	$old_name = post('old_name');
    	$name = isset($f['name']) ? $f['name'] : $old_name;
    	if($name != $old_name && !in_array($name, $protect)){
    		
    	}
    	view(Yii::getAlias('@themes')); exit;
    }
    /*
     * 
     */
    public static function getList($o = []){
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.title'=>SORT_ASC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);    
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';    	
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : -1;
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$offset = ($p-1) * $limit;
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	//->where(['a.sid'=>__SID__])
    	->andWhere("a.state>-2");
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['like', 'title', $filter_text]);
    	}
    	if(is_numeric($type_id) && $type_id > -1){
    		$query->andWhere(['a.type_id'=>$type_id]);
    	}
    	if(is_numeric($parent_id) && $parent_id > -1){
    		$query->andWhere("a.parent_id=$parent_id");
    	}
    	if(is_numeric($is_active) && $is_active > -1){
    		$query->andWhere(['a.is_active'=>$is_active]);
    	}
    	//view($query->createCommand()->getSql());
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
}
