<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class ProgramSegments extends \yii\db\ActiveRecord
{
	public static function getBooleanFields(){
		return [
				//'is_active',	
		];
	}
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tours_programs_segments}}';
    }

    public static function tableToPlace()
    {
    	return '{{%tours_programs_segments_to_places}}';
    }
    public static function tableToGuide()
    {
    	return '{{%tours_programs_segments_guides}}';
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
    
    public static function getPlaceIDs($segment_id = 0){
    	$l = (new Query())->from(self::tableToPlace())->where(['segment_id'=>$segment_id])->all();
    	$r = [];
    	if(!empty($l)){
    		foreach ($l as $v){
    			$r[] = $v['place_id']; 
    		}
    	}
    	return $r;
    }
    
    public static function getSegmentGuideType($o= []){
    	$item_id = isset($o['item_id']) ?  $o['item_id'] : 0;
    	$segment_id = isset($o['segment_id']) ?  $o['segment_id'] : 0;
    	$package_id = isset($o['package_id']) ?  $o['package_id'] : 0;
    	return (new Query())->from(self::tableToGuide())->where([
    			'item_id'=>$item_id,
    			'segment_id'=>$segment_id,
    			'package_id'=>$package_id,
    	])->one();
    }
    public static function getItem($id=0,$o=[]){    	
    	$item = static::find()
    	->where(['id'=>$id, 'sid'=>__SID__]);    
    	$item = $item->asArray()->one();
    	if(!empty($item)){
    		$item['places'] = (new Query())->from(['a'=>'departure_places'])
    		->where(['a.id'=>(new Query())
    				
    				->from('tours_programs_segments_to_places')->where(['segment_id'=>$item['id']])->select('place_id')
    		])
    		->select(['a.*','title'=>'a.name'])
    		->all();
    	}
    	return $item;
    }
    /*
     * 
     */
    public static function getList($o = []){
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.position'=>SORT_ASC,'a.title'=>SORT_ASC,'a.id'=>SORT_DESC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);    
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';    	
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : -1;
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$offset = ($p-1) * $limit;
    	$query = static::find()
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
    
    
    public static function getAll($item_id=0, $o = []){
    	$item_id = is_numeric($item_id) ? $item_id : (isset($o['item_id']) ? $o['item_id'] : 0);
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.position'=>SORT_ASC, 'a.title'=>SORT_ASC,'a.id'=>SORT_DESC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : -1;
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$not_in = isset($o['not_in']) ? $o['not_in'] : [];
    	$offset = ($p-1) * $limit;
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	->where(['a.sid'=>__SID__,'a.item_id'=>$item_id])
    	;
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['like', 'a.title', $filter_text]);
    	}
    	if($parent_id>-1){
    		$query->andWhere(['a.parent_id'=>$parent_id]);
    	}
    	if(!empty($not_in)){
    		$query->andWhere(['not in','a.id',$not_in]);
    	}    	    	 
    	$query->select(['a.*'])
    	->orderBy($order_by)
    	->offset($offset)
    	->limit($limit);
    	return $query->asArray()->all();    	 
    }
}
