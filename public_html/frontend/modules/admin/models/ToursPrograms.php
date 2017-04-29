<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class ToursPrograms extends \yii\db\ActiveRecord
{
	public static function getBooleanFields(){
		return [
				'is_active',	
		];
	}
	
	public static function getDateTimeFields(){
		return [
				'from_date', 
				'to_date',
		];
	}
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tours_programs}}';
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
    
    public static function setExchangeRateItem($item_id){
    	$to = 1;
    	foreach (Yii::$app->zii->getUserCurrency()['list'] as $k1=>$v1){
    		$value = Yii::$app->zii->getExchangeRate($v1['id'],$to);
    		if((new Query())->from('tours_programs_exchange_rate')->where([
    				'item_id'=>$item_id,
    				'from_currency'=>$v1['id'],
    				'to_currency'=>$to
    		])->count(1) == 0){
    			Yii::$app->db->createCommand()->insert('tours_programs_exchange_rate',[
    					'item_id'=>$item_id,
    					'from_currency'=>$v1['id'],
    					'to_currency'=>$to,
    					'value'=>$value
    			])->execute();
    			
    		}else{
    			/*Yii::$app->db->createCommand()->update('tours_programs_exchange_rate',[
    					'value'=>$value
    			],[
    					'item_id'=>$item_id,
    					'from_currency'=>$v1['id'],
    					'to_currency'=>$to,
    						
    			])->execute();
    			*/
    		}
    	}
    }
    
    public static function countProgramServicesPerDay($o = []){
    	//
    	$item_id = isset($o['item_id']) ? $o['item_id'] : 0;
    	$day = isset($o['day_id']) ? $o['day_id'] : -1;
    	$time = isset($o['time_id']) ? $o['time_id'] : -1;
    	//
    	
    	$query = (new Query())->from(['a'=>'tours_programs_services_days'])
    	->where(['item_id'=>$item_id,'day_id'=>$day]);
    	if ($time>-1){
    		$query->andWhere(['time_id'=>$day]);
    	}
    	$r = $query->count(1);
    	 
    	 
    	return $r;
    }
    public static function getProgramServices($id = 0, $day = 0, $time = 0,$o = []){
    	$query = (new Query())->from(['a'=>'tours_programs_services_days'])    	
    	->where([
    			'a.item_id'=>$id,
    			'a.day_id'=>$day,
    			'a.time_id'=>$time,
    			
    	]);
    	if(isset($o['not_in'])){
    		//$query->andWhere(['not'])
    	}
    	
    	$l = $query->orderBy(['position'=>SORT_ASC])->all();
    	
    	$r = [];
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			switch ($v['type_id']){
    				case TYPE_ID_HOTEL: case TYPE_ID_REST: case TYPE_ID_SHIP_HOTEL:
    					$item = Customers::getItem($v['service_id']);
    					break;
    				case TYPE_CODE_DISTANCE:
    					$item = Distances::getItem($v['service_id']);
    					break;
    				case TYPE_ID_SCEN:
    					$item = Tickets::getItem($v['service_id']);
    					break;
    				case TYPE_ID_GUIDES:
    					$item = Guides::getGuide($v['service_id']); 
    				break;
    				case TYPE_ID_SHIP:
    					$item = Distances::getItem($v['service_id']); 
    				break;
    				case TYPE_ID_TEXT:
    					$item = TextInstructions::getItem($v['service_id']);
    					break;
    			}
    			$item['supplier_id'] = Yii::$app->zii->getSupplierIDFromService($v['service_id'],$v['type_id']);
    			//$item['sub_item_id'] = $v['sub_item_id'];
    			$item['type_id'] = $v['type_id'];
    			$item['package_id'] = $v['package_id'];
    			//view($v);
    			$r[] = $item;
    		}
    	}
    	return $r;
    }
    
    public static function getListSuppliers($o = []){
    	$query = (new Query())->from(['a'=>'tours_programs_to_suppliers'])
    	->where(['item_id'=>$id,'supplier_id'=>$supplier_id]);
    	if(isset($o['not_in'])){
    		//$query->andWhere(['not'])
    	}
    }
    
    public static function getListVehicleBySupplier($o = []){
    	$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
    	$item_id = isset($o['item_id']) ? $o['item_id'] : 0;
    	
    	$query = (new Query())
    	->select('a.*','b.*')
    	->from(['a'=>'tours_programs_to_suppliers'])
    	->innerJoin(['b'=>'vehicles_categorys'],'b.id=a.vehicle_id')
    	->where(['a.item_id'=>$item_id,'a.supplier_id'=>$supplier_id,'b.type'=>1]);
    	if(isset($o['not_in'])){
    		//$query->andWhere(['not'])
    	}
    	
    	
    	return $query->all();
    }
    
    
    
    public static function getProgramDistanceServices($id = 0, $supplier_id = 0 ,$o = []){
    	$query = (new Query())->from(['a'=>'tours_programs_services_distances'])
    	->where(['item_id'=>$id,'supplier_id'=>$supplier_id]);
    	 
    	 
    	$l = $query->orderBy(['position'=>SORT_ASC])->all();
    	 
    	$r = [];
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			switch ($v['type_id']){
    				case TYPE_ID_HOTEL: case TYPE_ID_REST:
    					$item = Customers::getItem($v['service_id']);
    					break;
    				case TYPE_CODE_DISTANCE:
    					$item = Distances::getItem($v['service_id']);
    					break;
    				case TYPE_ID_SCEN:
    					$item = Tickets::getItem($v['service_id']);
    					break;
    				case TYPE_ID_GUIDES:
    					$item = Guides::getGuide($v['service_id']);
    					break;
    			}
    			$item ['package_id'] = $v['package_id'];
    			$item ['type_id'] = $v['type_id'];
    			$r[] = $item;
    		}
    	}
    	///view($r); 
    	return $r;
    }
    /*
     * 
     */
    public static function getList($o = []){
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.title'=>SORT_ASC,'a.id'=>SORT_DESC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);    
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
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
