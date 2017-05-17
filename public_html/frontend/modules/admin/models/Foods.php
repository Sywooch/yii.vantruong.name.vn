<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class Foods extends \yii\db\ActiveRecord
{
	public static function getBooleanFields(){
		return [
		//		'is_active',	
		];
	}
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%foods}}';
    }
    public static function tableToCategory()
    {
    	return '{{%foods_to_categorys}}';
    }
    
    public static function tableToMenu()
    {
    	return '{{%foods_to_menus}}';
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
    	return (new Query())->select('max(id) +1')->from(self::tableName())->scalar();
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
    public static function getAll(){
    	return static::find()
    	->from(['a'=>self::tableName()])
    	->where(['a.sid'=>__SID__])->orderBy(['a.title'=>SORT_ASC])
    	->asArray()->all();
    }
    public static function getItemCategorys($food_id,$o = 1){
    	$l = static::find()
    	->from(['a'=>FoodsCategorys::tableName()])
    	->innerJoin(['b'=>self::tableToCategory()],'a.id=b.category_id')
    	->where(['b.food_id'=>$food_id])
    	->asArray()->all();
    	$r = [];
    	if(!empty($l)){
    		foreach($l as $k=>$v){
    			$r[] = $o == 1 ? $v['id'] : uh($v['title']);
    		}
    	}
    	return $r;
    }
    public static function getList($o = []){
    	 
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.title'=>SORT_ASC,'a.id'=>SORT_DESC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);    
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$listItem  = isset($o['listItem']) && $o['listItem'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';    	
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : -1;
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$not_in = isset($o['not_in']) ? $o['not_in'] : [];
    	$in = isset($o['in']) ? $o['in'] : [];
    	
    	if(!is_array($in) && $in != "") $in = explode(',', $in);
    	if(!is_array($not_in) && $not_in != "") $not_in = explode(',', $not_in);
    	$offset = ($p-1) * $limit;
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	->where(['a.sid'=>__SID__])
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
    	$c = 0;
    	if($count){
    		//$query->select('count(1)');
    		$c = $query->count(1);
    	}
    	//view($query->createCommand()->getRawSql());
    	$query->select(['a.*'])
    	->orderBy($order_by)
    	->offset($offset)
    	->limit($limit);
    	$l = $query->asArray()->all();
    	//
    	return !$listItem ? $l : [
    			'listItem'=>$l,
    			'total_records'=>$c,
    			'total_pages'=>ceil($c/$limit),
    			'limit'=>$limit,
    			'p'=>$p,
    	];
    	
    }
    public function updateCategory($id){
    	$category_id = post('category_id',[]);
    	Yii::$app->db->createCommand()->delete(self::tableToCategory(),['food_id'=>$id])->execute();
    	if(!empty($category_id)){
    		foreach ($category_id as $c){
    			Yii::$app->db->createCommand()->insert(self::tableToCategory(),['food_id'=>$id,'category_id'=>$c,])->execute();
    		}
    	}
    }
}
