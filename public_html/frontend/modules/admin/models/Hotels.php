<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
use app\modules\admin\models\Customers;
class Hotels extends Customers
{
	public static function getBooleanFields(){
		return [
				'is_active',	
		];
	}
	public static function tablePrice(){
		return '{{%rooms_to_prices}}';
	}
	public static function tableRoom(){
		return '{{%rooms_to_hotel}}';
	}
	public static function tableRoomCategory(){
		return RoomsCategorys::tableName();
	}
	public static function table_place(){
		return DeparturePlaces::tableName();
	}
	public static function table_to_place(){
		return '{{%distance_to_places}}';
	}
	public static function tableSeason(){
		return Seasons::tableName();
	}
	public static function tableWeekend(){
		return '{{%weekend}}';
	}
	public function table_to_season_supplier(){
		return '{{%seasons_to_suppliers}}';
	}
    /**
     * @inheritdoc
     */
     

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

     
    /*
     * 
     */
    public function get_existed_place($id = 0, $type = 2){
    	$sql = "select a.* from {$this->table_place()} as a";
    	$sql .= " where a.sid=".__SID__;
    	$sql .= " and a.id in(select b.place_id from {$this->table_to_place()} as b where b.distance_id=$id and b.type_id=$type)";
    
    	return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public static function getListRooms($id = 0, $o=array()){
    	$not_in = isset($o['not_in']) ? $o['not_in'] : array();
    	$prices = isset($o['prices']) && $o['prices'] == false ? $o['prices'] : true;
    	$in = isset($o['in']) ? $o['in'] : array();
    	 
    	$sql = "select a.*,b.*";
    	$sql .= $id >-1 ? ",b.quantity" : "";
    	$sql .= " from ".self::tableRoomCategory()." as a";
    	 
    	$sql .= $id >-1 ? " inner join ".self::tableRoom()." as b on a.id=b.room_id" : '';
    	$sql .= " where a.state>0 and a.is_active=1 and a.sid=".__SID__;
    	$sql .= $id >-1 ? " and b.parent_id=$id" : "";
    	//
    	if(!empty($not_in)){
    		$sql .= " and a.id not in(".implode(',', $not_in).")";
    	}
    	if(!empty($in)){
    		$sql .= " and a.id in(".implode(',', $in).")";
    	}
    	//
    	$sql .= " group by a.id order by a.title,a.position, a.seats";
    
    	$l = Yii::$app->db->createCommand($sql)->queryAll();
    
    	return $l;
    }
    public static function get_incurred_category_by_supplier($id = CONTROLLER_CODE,$type_id = [2],$o = []){
    	if(is_numeric($type_id)) $type_id = [$type_id];
    	$sql = "select a.* from seasons_categorys as a inner join seasons_to_suppliers as b on a.id=b.season_id ";
    	$sql .= " where b.supplier_id=$id and b.type_id=2";
    	$sql .= !empty($type_id) ? "  and a.type_id in (".implode(',', $type_id).")" : "";
    	 
    	$sql .= " order by a.type_id,a.position, a.title";
    	$l = Yii::$app->db->createCommand($sql)->queryAll();
    	 
    	$r = [];
    	if(!empty($l)){
    		 
    		$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    		$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : -1;
    		$type_id = isset($o['type_id']) ? $o['type_id'] : -1;
    		$price_type = isset($o['price_type']) ? $o['price_type'] : [];
    		foreach ($l as $k=>$v){
    			$season_id = isset($o['season_id']) ? $o['season_id'] : $v['id'];
    			$sql = "select * from seasons_to_suppliers where 1";
    			$sql .= $season_id > -1 ? " and season_id=$season_id" : "";
    			$sql .= $parent_id > -1 ? " and parent_id=$parent_id" : "";
    			$sql .= $supplier_id > -1 ? " and supplier_id=$supplier_id" : "";
    			$sql .= $type_id > -1 ? " and type_id=$type_id" : "";
    			$sql .= !empty($price_type) ? " and price_type in (".implode(',', $price_type).")" : "";
    			$l1 = Yii::$app->db->createCommand($sql)->queryOne();
    			 
    			if(!empty($l1)){
    				$r[$k] = $v;
    				$r[$k]['supplier'] = $l1;
    			}
    		}
    	}
    	return $r;
    }
    public function get_incurred_category($id = CONTROLLER_CODE,$type_id = [2],$o = []){
    	if(is_numeric($type_id)) $type_id = [$type_id];
    	$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
    	$sql = "select a.* from seasons_categorys as a";
    	$sql .= " left outer join seasons_to_suppliers as b on a.id=b.season_id ";
    	$sql .= " where a.sid=".__SID__." and (b.supplier_id=$id and b.type_id=2";
    	$sql .= !empty($type_id) ? "  and a.type_id in (".implode(',', $type_id).")" : "";
    	$sql .= ")";
    	$sql .= $supplier_id>0 ? " or a.id in(select season_id from seasons_categorys_to_suppliers where supplier_id=$supplier_id)" : '';
    	$sql .= " group by a.id";
    	$sql .= " order by a.type_id,a.position, a.title";
    	
    	 
    	
    	$l = Yii::$app->db->createCommand($sql)->queryAll();
    	if(!empty($l)){
    		 
    		$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    		$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : -1;
    		$type_id = isset($o['type_id']) ? $o['type_id'] : -1;
    		foreach ($l as $k=>$v){
    			$season_id = isset($o['season_id']) ? $o['season_id'] : $v['id'];
    			$sql = "select * from seasons_to_suppliers where 1";
    			$sql .= $season_id > -1 ? " and season_id=$season_id" : "";
    			$sql .= $parent_id > -1 ? " and parent_id=$parent_id" : "";
    			$sql .= $supplier_id > -1 ? " and supplier_id=$supplier_id" : "";
    			$sql .= $type_id > -1 ? " and type_id=$type_id" : "";
    			//view($sql);
    			$l[$k]['supplier'] = Yii::$app->db->createCommand($sql)->queryOne();
    		}
    	}
    	return $l;
    }
    public function get_rooms_groups($id = 0 ){
    	$sql = "select * from rooms_groups as a ";
    	$sql .= " where a.parent_id=$id";
    	//	$sql .= $type_id > 0 ? "  and a.type_id=$type_id" : "";
    	$sql .= " order by a.pmin";
    	//	view($sql);
    	return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public static function get_price($item_id = 0, $parent_id=0,$type_id=1,$package_id=0){
    	$sql = "select a.* from ".self::tablePrice()." as a";
    	$sql .= " inner join ".self::tableRoomCategory()." as b on a.item_id=b.id";
    	$sql .= " where a.parent_id=$parent_id and a.item_id=$item_id and a.type_id=$type_id and a.package_id=$package_id";
    	$l = Yii::$app->db->createCommand($sql)->queryAll();
    	$r = [];
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			$r['price2'] = $v['price2'];
    			$r['currency'] = $v['currency'];
    			$r[$v['season_id']][$v['group_id']][$v['item_id']][$v['weekend_id']]['price1'] = $v['price1'];
    		}
    	}
    	return $r;
    }
    public function get_season_category($id = 0, $o=[]){
    	$field = isset($o['field']) ? $o['field'] : 'a.*';
    	$query = isset($o['query']) ? $o['query'] : 'row';
    	$sql = "select $field from seasons_categorys as a where a.id=$id";
    	return strtolower($query) == 'scalar' ? Yii::$app->db->createCommand($sql)->queryScalar() : Yii::$app->db->createCommand($sql)->queryOne();
    }
    public function get_season_supplier($o = []){
    	$season_id = isset($o['season_id']) ? $o['season_id'] : -1;
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : -1;
    	$type_id = isset($o['type_id']) ? $o['type_id'] : -1;
    	$price_type = isset($o['price_type']) ? $o['price_type'] : -1;
    	 
    	$sql = "select * from {$this->table_to_season_supplier()} as a where 1";
    	$sql .= $season_id > -1 ? " and a.season_id=$season_id" : '';
    	$sql .= $parent_id > -1 ? " and a.parent_id=$parent_id" : '';
    	$sql .= $supplier_id > -1 ? " and a.supplier_id=$supplier_id" : '';
    	$sql .= $type_id > -1 ? " and a.type_id=$type_id" : '';
    	$sql .= $price_type > -1 ? " and a.price_type=$price_type" : '';
    	return Yii::$app->db->createCommand($sql)->queryOne();
    }
    public function get_incurred_charge_type(){
    	return array(
    			array('id'=>0,'title'=>'Tính giá trực tiếp'),
    			array('id'=>1,'title'=>'Tính giá phát sinh (%)'),
    			array('id'=>2,'title'=>'Phụ thu tiền mặt'),
    			//array('id'=>8,'title'=>'Tàu thuyền'),
    	);
    }
    public function get_unit_prices(){
    	return array(
    			array('id'=>1,'title'=>'Phòng | Xe vận chuyển'),
    			array('id'=>2,'title'=>'Khách'),
    			array('id'=>3,'title'=>'Đoàn'),
    			//array('id'=>4,'title'=>'Tàu thuyền'),
    	);
    }
    public function get_list_seasons_by_parent($supplier_id = 0, $season_category_id=0){
    	$sql = "select a.* from {$this->tableSeason()} as a where a.state>-2 and a.sid=".__SID__;
    	$sql .= " and a.id in(select season_id from {$this->table_to_season_supplier()} where supplier_id=$supplier_id and parent_id=$season_category_id)";
    	//$sql .= " and ";
    	$sql .= " order by a.from_date,a.title";
    	//view($sql);
    	return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public function get_list_weekend_by_parent($supplier_id = 0, $season_category_id=0){
    	$sql = "select a.* from {$this->tableWeekend()} as a where a.state>-2 and a.sid=".__SID__;
    	$sql .= " and a.id in(select season_id from {$this->table_to_season_supplier()} where supplier_id=$supplier_id and parent_id=$season_category_id)";
    	$sql .= " order by parent_id, a.from_date,a.from_time";
    	return Yii::$app->db->createCommand($sql)->queryAll();
    }
    
    
    public function update_place($id = 0,$type = 2){
    	if($id>0){
    		$places = isset($_POST['places']) ? $_POST['places'] : array();
    		Yii::$app->db->createCommand()->delete($this->table_to_place(),array('distance_id'=>$id,'type_id'=>$type))->execute();
    		if(!empty($places)){
    			foreach ($places as $p){
    				Yii::$app->db->createCommand()->insert($this->table_to_place(),array(
    						'place_id'=>$p,'type_id'=>$type
    				,'distance_id'=>$id))->execute();
    			}
    		}
    	}
    
    }
     
     
    public function update_seasons($id){
    	/*
    	$seasons = isset($_POST['seasons']) ? $_POST['seasons'] : [];
    	Yii::$app->db->createCommand()->delete('seasons_to_suppliers',array('supplier_id'=>$id,'type_id'=>10))->execute();
    	if(!empty($seasons)){
    		foreach ($seasons as $k=>$v){
    			// k = season id
    			if((new Query())->from('{{%seasons_to_suppliers}}')->where(array('season_id'=>$k,'supplier_id'=>$id,'type_id'=>20))->count(1)==0){
    				Yii::$app->db->createCommand()->insert('seasons_to_suppliers',[
    						'season_id'=>$k,
    						'supplier_id'=>$id,
    						'is_default'=>isset($v['is_default']) ? cbool($v['is_default']) : 0,
    						'price_incurred'=>cprice($v['price_incurred']),
    						'price_type'=>$v['price_type'],
    						'parent_id'=>isset($v['parent_id']) ? $v['parent_id'] : 0,
    						'currency'=>isset($v['currency']) ? $v['currency'] : 1,
    						'unit_price'=>isset($v['unit_price']) ? $v['unit_price'] : 1,
    						'sub_id'=>isset($v['sub_id']) ? $v['sub_id'] : 0,
    						'object_id'=>isset($v['object_id']) ? $v['object_id'] : 0,
    				'type_id'=>20])->execute();
    			}else {
    				
    				Yii::$app->db->createCommand()->update('{{%seasons_to_suppliers}}',[
    						'is_default'=>isset($v['is_default']) ? cbool($v['is_default']) : 0,
    						'price_incurred'=>cprice($v['price_incurred']),
    						'price_type'=>$v['price_type'],
    						'parent_id'=>isset($v['parent_id']) ? $v['parent_id'] : 0,
    						'currency'=>isset($v['currency']) ? $v['currency'] : 1,
    						'unit_price'=>isset($v['unit_price']) ? $v['unit_price'] : 1,
    						'sub_id'=>isset($v['sub_id']) ? $v['sub_id'] : 0,
    						'object_id'=>isset($v['object_id']) ? $v['object_id'] : 0,
    				],array('season_id'=>$k,'supplier_id'=>$id,'type_id'=>20 ))->execute();
    			}
    			//
    			if(isset($v['list_child']) && !empty($v['list_child'])){
    				foreach ($v['list_child'] as $k1=>$v1){
    					Yii::$app->db->createCommand()->insert('seasons_to_suppliers',['season_id'=>$v1['id'],'parent_id'=>$k,'supplier_id'=>$id,'type_id'=>10])->execute();
    				}
    			}
    			//view($v);
    		}
    		//exit;
    	}
    	 
    	 */
    }
    public function updateRooms($id = 0){
    	$h = isset($_POST['h']) ? $_POST['h'] : array();
    	Yii::$app->db->createCommand()->delete($this->tableRoom(),array('parent_id'=>$id,'room_id'=>post('delete_room',[])))->execute();    		
    	if(!empty($h)){
    		foreach ($h as $k=>$v){
    			if((new Query())->from($this->tableRoom())->where([
    					'room_id'=>$v['id'],    					
    					'parent_id'=>$id,
    			])->count(1) == 0){
	    			Yii::$app->db->createCommand()->insert($this->tableRoom(),array(
	    					'room_id'=>$v['id'],
	    					'quantity'=>cprice($v['quantity']),    			
	    					'parent_id'=>$id,
	    					//'is_default'=>cbool($v['is_default'])
	    			))->execute();
    			}else{
    				Yii::$app->db->createCommand()->update($this->tableRoom(),array(
    						//'room_id'=>$v['id'],
    						'quantity'=>cprice($v['quantity']),
    						//'parent_id'=>$id,
    						//'is_default'=>cbool($v['is_default'])
    				),[
    						'room_id'=>$v['id'],
    						'parent_id'=>$id,
    				])->execute();
    			}
    		}
    	}
    		
    }
    public function updatePrice($id = 0){
    	$prices = isset($_POST['prices']) ? $_POST['prices'] : [];
    	//$prices1 = isset($_POST['prices1']) ? $_POST['prices1'] : [];
    	//if(is_array($delete_price_id) && !empty($delete_price_id)){
    	Yii::$app->db->createCommand()->delete($this->tablePrice(),array('parent_id'=>$id))->execute();
    	//}
    	//view($prices,true);
    	if(!empty($prices)){
    		foreach ($prices as $package_id=>$packages){
    			 
    			foreach ($packages as $kb => $vb){
    			 
    			if(!empty($vb)){
    				foreach ($vb as $k=>$v){
    					if(!empty($v['list_child'])){
    						foreach ($v['list_child'] as $k1=>$v1){
    							// $k1 = season_id
    							if(!empty($v1)){
    								foreach ($v1 as $k2=>$v2){
    									// $k2 = group_id
    									if(!empty($v2)){
    										foreach ($v2 as $k3=>$v3){
    											Yii::$app->db->createCommand()->insert($this->tablePrice(),[
    													'season_id'=>$k1,
    													'package_id'=>$package_id,
    													'group_id'=>$k2,
    													'type_id'=>$kb,
    													'item_id'=>$k,
    													'weekend_id'=>$k3,
    													'price1'=>cprice($v3['price1']),
    													'price2'=>cprice($v['price2']),
    													'currency'=>$v['currency'],
    													'parent_id'=>$id,
    													'supplier_id'=>$id,
    											])->execute();
    										}
    									}
    
    										
    								}
    							}
    						}
    					}
    				}
    			}
    		}
    		}
    		
    	}
    	//	exit;
    }
}
