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

    			$r[] = $item;
    		}
    	}
    	return $r;
    }
    
    
    public static function getProgramService($service_id = 0, $type_id = 0,$o = []){
    	$item = [];
    	 switch ($type_id){
    		case TYPE_ID_HOTEL: case TYPE_ID_REST: case TYPE_ID_SHIP_HOTEL:
    			$item = Customers::getItem($service_id);
    			break;
    		case TYPE_CODE_DISTANCE:
    			$item = Distances::getItem($service_id);
    			break;
    		case TYPE_ID_SCEN:
    			$item = Tickets::getItem($service_id);
    			break;
    		case TYPE_ID_GUIDES:
    			$item = Guides::getGuide($service_id);
    			break;
    		case TYPE_ID_SHIP:
    			$item = Distances::getItem($service_id);
    			break;
    		case TYPE_ID_TEXT:
    			$item = TextInstructions::getItem($service_id);
    			break;
    	 }
    			 
    	return $item;
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
    	$segment_id = isset($o['segment_id']) ? $o['segment_id'] : 0;
    	$query = (new Query())
    	->select('a.*','b.*')
    	->from(['a'=>'tours_programs_to_suppliers'])
    	->innerJoin(['b'=>'vehicles_categorys'],'b.id=a.vehicle_id')
    	->where(['a.item_id'=>$item_id,'a.supplier_id'=>$supplier_id,'b.type'=>1,'a.segment_id'=>$segment_id]);
    	if(isset($o['not_in'])){
    		//$query->andWhere(['not'])
    	}
    	
    	
    	return $query->all();
    }
    
    
    
    public static function getProgramDistanceServices($id = 0, $supplier_id = 0 ,$o = []){
    	$segment_id = isset($o['segment_id']) ? $o['segment_id'] : 0;
    	$query = (new Query())->from(['a'=>'tours_programs_services_distances'])
    	->where([
    			'item_id'=>$id,
    			'supplier_id'=>$supplier_id,
    			'segment_id'=>$segment_id
    	]);
    	 
    	 
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
    
    public static function getProgramGuides($o = []){
    	$a = ['item_id','segment_id','supplier_id'];
    	$guide_type = isset($o['guide_type']) ? $o['guide_type'] : 2;
    	foreach ($a as $b){
    		$$b = isset($o[$b]) ? $o[$b] : 0;
    	}
    	$query = (new Query())
    	->select(['a.*','b.*','supplier_name'=>(new Query())->select('name')->from('customers')->where(['id'=>(new Query())
    			->select('supplier_id')->from('guides_to_suppliers')->where('guide_id=b.id')
    	])->limit(1)])
    	->from(['a'=>'tours_programs_guides'])
    	->innerJoin(['b'=>'guides'],'a.guide_id=b.id')
    	->where([
    			'a.item_id'=>$item_id, 
    			'a.segment_id'=>$segment_id,
    			'a.type_id'=>$guide_type
    	]);
    	if($supplier_id>0){
    		$query->andWhere(['a.supplier_id'=>$supplier_id]);
    	}
    	
    	return $query->orderBy(['a.position'=>SORT_ASC,'b.title'=>SORT_ASC])->all();
    }
    
    /*
     * Lấy số lượng hướng dẫn theo nhà xe đã chọn
     * 1. Lấy theo số lượng xe (nhiều nhất) của nhà xe đầu tiên trong chặng
     */
    public static function getNumberOfGuides($o = []){
    	$item_id = isset($o['item_id']) ? $o['item_id'] : 0;
    	$segment_id = isset($o['segment_id']) ? $o['segment_id'] : 0;
    	$s = self::getAutoGuideQuantity($o); 
    	return $s['quantity']; 
    }
    
    public static function setSegmentsAutoGuides($o = []){
    	$item_id = isset($o['item_id']) ? $o['item_id'] : 0;    	
    	$item = \app\modules\admin\models\ToursPrograms::getItem($item_id);
    	$item_guide_type = (isset($item['guide_type']) ? $item['guide_type'] : 2);
    	if($item_guide_type == 1) {
    		$segment_id = 0;
    		
    		$segments = [[
    			'id'=>0	,'guide_type'=>1
    		]];
    	}else{
    		$segments = \app\modules\admin\models\ProgramSegments::getAll($item_id,['parent_id'=>0]);
    	}
    	if(!empty($segments)){
    		foreach ($segments as $segment){
    			//
    			$segment_id = $segment['id'];
    			
    			
    			$guide_language = isset($segment['lang']) ? $segment['lang'] : (isset($item['guide_language']) ? $item['guide_language'] : DEFAULT_LANG);
    			$guide_type = isset($segment['type_id']) ? $segment['type_id'] : (isset($item['guide_type']) ? $item['guide_type'] : 2);
    			 
    			
    			 
    			
    			if($guide_type == 1){
    				$sgms = [$segment];
    			}else{    			
    				$segments1 = \app\modules\admin\models\ProgramSegments::getAll($item_id,['parent_id'=>$segment['id']]);
    				if(!empty($segments1)){
    					$sgms = $segments1;
    				}else{
    					$sgms = [$segment];
    				}
    			}
    			
    			if(!empty($sgms)){
    				foreach ($sgms as $sgm){
    					//
    					$x = self::getAutoGuideQuantity([
    							'item_id'=>$item_id,
    							'segment_id'=>$sgm['id']
    					]);
    					Yii::$app->db->createCommand()->delete('tours_programs_guides',[
    							'item_id'=>$item_id,
    							'segment_id'=>$sgm['id'],
    							'type_id'=>$guide_type
    					])->execute();
    					Yii::$app->db->createCommand()->delete('tours_programs_guides_prices',[
    							'item_id'=>$item_id,
    							'segment_id'=>$sgm['id'],
    							'type_id'=>$guide_type
    					])->execute();
    					 
    					// Lay ngon ngu HDV tu tours_programs_segments_guides
    					$c = (new Query())->from('guides')->where([
    							'language'=>$guide_language,'state'=>1,'sid'=>__SID__,
    							'id'=>(new Query())->from('guides_to_suppliers')->where([
    									'supplier_id'=>(new Query())->from('customers_to_places')->where([
    											'place_id'=>(new Query())->from('tours_programs_segments_to_places')
    											->where(['segment_id'=>$x['segment_id']])
    											->select('place_id')
    									])->select('customer_id')
    							])->select('guide_id')
    					
    					])->one();
    									
    					if(!empty($c)){
    						$supplier_id = Yii::$app->zii->getSupplierIDFromService($c['id'],TYPE_ID_GUIDES);
    						Yii::$app->db->createCommand()->insert('tours_programs_guides',[
    								'item_id'=>$item_id,
    								'segment_id'=>$sgm['id'],
    								'type_id'=>$guide_type,
    								'guide_id'=>$c['id']	,
    								'quantity'=>$x['quantity'],
    								'supplier_id'=>$supplier_id
    						])->execute();
    					
    						Yii::$app->db->createCommand()->insert('tours_programs_guides_prices',[
    								'item_id'=>$item_id,
    								'segment_id'=>$sgm['id'],
    								'type_id'=>$guide_type,
    								'service_id'=>$c['id']	,
    								'supplier_id'=>$supplier_id,
    								'number_of_day'=>$x['number_of_day']>0 ? $x['number_of_day'] : 0,
    								'quantity'=>$x['quantity']
    						])->execute();
    					}
    				}
    			}
    			    			     			    			
    			//
    		}
    		 
    		}
    	//}
    	
    	 
    }
    
    public static function getAutoGuideQuantity($o = ['item_id'=>0,'segment_id'=>0]){
    	$item_id = $o['item_id']; $segment_id = $o['segment_id'];
    	 
    	$item = self::getItem($o['item_id']);
    	$item_guide_type = isset($item['guide_type']) ? $item['guide_type'] : 2;
    	$quantity = $number_of_day = $supplier_id =  $last_segment_id = 0; 
    	if($item_guide_type == 1){ // Suot tuyen cho tat ca cac chang
    		$segment_id = $last_segment_id =0;
    		$segments = \app\modules\admin\models\ProgramSegments::getAll($item_id,['parent_id'=>0]);
    		if(!empty($segments)){
    			$segment = $segments[0];
    			$segments = \app\modules\admin\models\ProgramSegments::getAll($item_id,['parent_id'=>$segment['id']]);
    			if(!empty($segments)){
    				$segment = $segments[0];
    			}
    			//    			 
    			$segment_id = $segment['id']; 
    			// Lấy sl xe của nhà xe đầu tiên
    			$supplier = Yii::$app->zii->getTourProgramSuppliers($item_id,['segment_id'=>$segment['id']]);
    			if(!empty($supplier)){
    				$supplier = $supplier[0];
    				$supplier_id = $supplier['id'];
    			} 
    			
    			foreach (Yii::$app->zii->getSelectedVehicles([
    					'supplier_id'=>$supplier_id,
    					'item_id'=>$item_id,
    					'segment_id'=>$segment['id'],
    					'loadDefault'=>false,
    					'updateDatabase'=>false
    			]) as $car){
    				$quantity = $car['quantity'];
    				break;
    			}
    			//
    			$number_of_day = ProgramSegments::countDayOfParent($item_id,0);  
    		}
    	}elseif($segment_id>0){
    		$segment = ProgramSegments::getXItem($segment_id);
    		//view($segment);
    		$last_segment_id = $segment_id;
    		if($segment['type_id'] == 1){ // Suot tuyen cho tat ca cac chang cấp 2
    			$segments = \app\modules\admin\models\ProgramSegments::getAll($item_id,['parent_id'=>$segment_id]);
    			if(!empty($segments)){
    				$segment = $segments[0];
    				$number_of_day = ProgramSegments::countDayOfParent($item_id,$segment_id);
    				$segment_id = $segment['id'];
    			}else{
    				$number_of_day = $segment['number_of_day']; 
    			}
    			//
    			 
    			 
    			// Lấy sl xe của nhà xe đầu tiên
    			$supplier = Yii::$app->zii->getTourProgramSuppliers($item_id,['segment_id'=>$segment['id']]);
    			if(!empty($supplier)){
    				$supplier = $supplier[0];
    				$supplier_id = $supplier['id'];
    			}
    			  
    			foreach (Yii::$app->zii->getSelectedVehicles([
    					'supplier_id'=>$supplier_id,
    					'item_id'=>$item_id,
    					'segment_id'=>$segment['id'],
    					'loadDefault'=>false, 
    					'updateDatabase'=>false
    			]) as $car){
    				$quantity = $car['quantity'];
    				break;
    			}
    			//    			
    		}else{
    			// Lấy sl xe của nhà xe đầu tiên
    			$supplier = Yii::$app->zii->getTourProgramSuppliers($item_id,['segment_id'=>$segment_id]);
    			if(!empty($supplier)){
    				$supplier = $supplier[0];
    				$supplier_id = $supplier['id'];
    			} 
    			
    			foreach (Yii::$app->zii->getSelectedVehicles([
    					'supplier_id'=>$supplier_id,
    					'item_id'=>$item_id,
    					'segment_id'=>$segment_id,
    					'loadDefault'=>false,
    					'updateDatabase'=>false
    			]) as $car){
    				$quantity = $car['quantity']; 
    				break;
    			}
    			$number_of_day = $segment['number_of_day'];
    		}
    		
    	}
    	
    	return [
    			'item_id'=>$item_id,
    			'segment_id'=>$segment_id,
    			'supplier_id'=>$supplier_id,
    			'quantity'=>$quantity,
    			'number_of_day'=>$number_of_day,
    			'last_segment_id'=>$last_segment_id
    			
    	];
    }
    
    public static function getExtendPrices($o = []){
    	$item_id = isset($o['item_id']) ? $o['item_id'] : 0; 
    	$segment_id = isset($o['segment_id']) ? $o['segment_id'] : 0;
    	$type_id = isset($o['type_id']) ? $o['type_id'] : 2;
    	return (new Query())->from('tours_programs_segments_extend_prices')->where([
    			'item_id'=>$item_id,
    			'segment_id'=>$segment_id,
    			'type_id'=>$type_id
    	])->orderBy(['title'=>SORT_ASC])->all();
    }
}
