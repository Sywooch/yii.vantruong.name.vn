<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class Cars extends Customers
{
	public static function getBooleanFields(){
		return [
				'is_active',	
		];
	}
	
	public static function tablePrice(){
		return 'vehicles_to_prices';
	}
	public static function tableDistancePrice(){
		return 'distances_to_prices';
	}
	public static function tableCar(){
		return 'vehicles_to_cars';
	}
	public static function tableCarCategory(){
		return 'vehicles_categorys';
	}
	public static function table_vehicles_makers(){
		return 'vehicles_makers';
	}
	public static function table_place(){
		return 'departure_places';
	}
	public static function table_to_place(){
		return 'distance_to_places';
	}
	public static function tableDistances(){
		return 'distances';
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

     
    
    public static function getParentGroupID(){
    	return (new Query())->select('max(parent_group_id) +1')->from(self::tablePrice())->scalar();
    }
     
    public function get_existed_place($id = 0, $type = 1){
    	$sql = "select a.* from {$this->table_place()} as a";
    	$sql .= " where a.sid=".__SID__;
    	$sql .= " and a.id in(select b.place_id from {$this->table_to_place()} as b where b.distance_id=$id and b.type_id=$type)";
    
    	return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public function getVehicleID(){
    	$sql = "select max(id) +1 from {$this->tableCar()}";
    	$max = Zii::$db->queryScalar($sql);
    	$max = $max > 0 ? $max : 1;
    	return $max;
    }
    public static function get_vehicles_makers(){
    	return (new Query())->from(['a'=>self::table_vehicles_makers()])->where([
    			'>','a.state',-2
    	])->all();
    	
    }
    public function getModule($id = 0,$type = 0){
    	$sql = "select module_id from module_to_group where group_id=$id and type=$type and sid=".__SID__;
    	$l = Yii::$app->db->createCommand($sql)->queryAll();
    	$r = array();
    	if(!empty($l)){
    		foreach($l as $k=>$v){
    			$r[] = $v['module_id'];
    		}
    	}
    	return $r;
    }
    public static function get_list_cars_by_seats($id = 0, $o = array()){
    	$l = self::getListCars($id,$o);
    	$r = array();
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			$r[$v['seats']][] = $v;
    		}
    	}
    	return $r;
    }
    public static function getListCars($id = 0,$o = array()){ 
    	$quantity = isset($o['quantity']) ? $o['quantity'] : -1;
    	$type_id = isset($o['type_id']) ? $o['type_id'] : -1;
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	
    	$not_in = isset($o['not_in']) ? $o['not_in'] : [];
    	$in = isset($o['in']) ? $o['in'] : [];
    	if(!is_array($in) && $in != "") $in = explode(',', $in);
    	if(!is_array($not_in) && $not_in != "") $not_in = explode(',', $not_in);
    	//$table = $this->tableCar();
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';
    	
    	$sql = "select a.*,b.title as maker_title,c.quantity,c.is_active as is_active,c.is_default from ".self::tableCarCategory()." as a";
    	$sql .= " left outer join ".self::tableCar()." as c on a.id=c.vehicle_id";
    	$sql .= " left outer join ".self::table_vehicles_makers()." as b on a.maker_id=b.id";
    	$sql .= " where a.state>-2 ";
    	$sql .= is_numeric($id) && $id > -1 ? " and c.parent_id=$id" : '';
    	$sql .= is_numeric($type_id) && $type_id > 0 ? " and a.type_id=$type_id" : '';
    	$sql .= $quantity == '+0' ? " and c.quantity>0" : '';
    	$sql .= $is_active > -1 ? " and c.is_active=$is_active" : '';
    	if(!empty($not_in)){
    		//$query->andWhere(['not in','a.id',$not_in]);
    		$sql .= " and a.id not in(".implode(',', $not_in).")";
    	}
    	if(strlen($filter_text) > 0){
    		//$query->andFilterWhere(['like', 'a.title', $filter_text]);
    		$sql .= " and a.title like '%$filter_text%'";
    	}
    	$sql .= " group by a.id";
    	$sql .= " order by a.seats, a.title";
    
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
    
    public function get_prices($id,$o = array()){
    	$vinfo = isset($o['vinfo']) && $o['vinfo'] == true ? true : false;
    	$type = isset($o['type']) ? $o['type'] : 3;
    	$sql = "select a.*";
    	$sql .= $vinfo ? ",b.name,b.title" : "";
    	$sql .= " from {$this->tablePrice()} as a";
    	$sql .= " inner join {$this->tableCarCategory()} as b on a.item_id=b.id";
    	$sql .= " where a.state>0 and a.parent_id=$id and b.type=$type  order by a.type desc,b.seats,a.pmin,b.title";
    
    	return Yii::$app->db->createCommand($sql)->queryAll();
    }
    
    public static function getPrices($o=[]){
    	$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
    	$query = (new Query())->from(['a'=>self::tablePrice()])
    	->innerJoin(['b'=>self::tableCarCategory()],'a.item_id=b.id')
    	->where(['a.supplier_id'=>$supplier_id,'b.type'=>3])
    	->groupBy(['a.item_id','a.parent_group_id'])
    	->select(['a.*','b.title','b.id'])
    	;
    	//view($query->createCommand()->getRawSql());
    	return $query->orderBy(['b.seats'=>SORT_ASC,'b.title'=>SORT_ASC])->all();
    }
    
    public static function get_list_distance_from_price($id,$o = array()){
    	$vinfo = isset($o['vinfo']) && $o['vinfo'] == true ? true : false; 
    	$supplier_id= $id;
    	/*
    	$l = (new Query())->from(['a'=>self::tableDistances()])->where([
    			'a.id'=>(new Query())->from(self::tableDistancePrice())->where(['supplier_id'=>$id])->select('item_id')
    	])->orderBy(['a.title'=>SORT_ASC])->all();
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			if((new Query())->from('distances_to_suppliers')->where(['item_id'=>$v['id'],'supplier_id'=>$supplier_id])->count(1) == 0){
    				Yii::$app->db->createCommand()->insert('distances_to_suppliers',[
    						'item_id'=>$v['id'],'supplier_id'=>$supplier_id
    				])->execute();
    			}
    		}
    	}
    	*/
    	return (new Query())->from(['a'=>self::tableDistances()])->where([
    			'a.id'=>(new Query())->from('distances_to_suppliers')->where(['supplier_id'=>$id])->select('item_id')
    	])->orderBy(['a.title'=>SORT_ASC])->all();
    	//return $l;
    }
    public function get_distance_by_id($id,$o = array()){
    
    	$sql = "select a.*";
    	//$sql .= $vinfo ? ",b.name,b.title" : "";
    	$sql .= " from {$this->tableDistances()} as a";
    	//$sql .= " inner join {$this->tableDistancePrice()} as b on a.id=b.distance_id";
    	$sql .= " where a.id in(".(is_array($id) ? implode(',', $id) : $id).")";
    	$sql .= " order by a.title";
    
    	return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public function get_price_distance($vehicle_id=0,$distance_id=0,  $supplier_id=0){
    	$sql = "select a.*";
    
    	$sql .= " from {$this->tableDistancePrice()} as a";
    	//$sql .= " inner join {$this->tableDistancePrice()} as b on a.id=b.distance_id";
    	$sql .= " where a.supplier_id=$supplier_id and a.distance_id=$distance_id and a.item_id=$vehicle_id";
    	//view($sql);
    	return Yii::$app->db->createCommand($sql)->queryOne();
    	return (new Query())->from(self::tableDistancePrice())->where([
    			'supplier_id'=>$supplier_id,
    			
    	])->one();
    }
    
    public function getListUser($id = 0){
    	$id = $id > 0 ? $id : 0;
    	$sql = "select a.id,a.username,a.email,a.phone,b.state from users as a inner join user_to_group as b on a.id = b.user_id and b.group_id=$id";
    	return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public function get_vehicle($id = 0){
    	 
    	$sql = "select a.* from ".$this->tableCar(). " as a ";
    	 
    	$sql .= " where  a.state>0 and a.id=$id and a.sid='".__SID__."'";
    	$l = Yii::$app->db->createCommand($sql)->queryOne();
    	 
    	return $l;
    }
    public function get_vehicle_price($id = 0,$pvalue = 0){
    	 
    	$sql = "select a.name,a.title,b.*,b.price1 as price from ".$this->tableCar(). " as a ";
    	$sql .= " inner join ".$this->tablePrice(). " as b on a.id=b.item_id ";
    	$sql .= " where  a.state>0 and a.id=$id and a.sid='".__SID__."'";
    	$sql .= " and $pvalue between b.pmin and b.pmax";
    	$l = Yii::$app->db->createCommand($sql)->queryOne();
    	return $l;
    }
    /*
     * 
     */
    public function update_seasons($id){
    	$seasons = isset($_POST['seasons']) ? $_POST['seasons'] : [];
    	Yii::$app->db->createCommand()->delete('seasons_to_suppliers',array('supplier_id'=>$id,'type_id'=>10))->execute();
    	if(!empty($seasons)){
    		foreach ($seasons as $k=>$v){
    			// k = season id
    			if(Yii::$app->zii->countTable('seasons_to_suppliers',array('season_id'=>$k,'supplier_id'=>$id,'type_id'=>20)) == 0){
    				Yii::$app->db->createCommand()->insert('seasons_to_suppliers',[
    						'season_id'=>$k,
    						'supplier_id'=>$id,
    						'is_default'=>isset($v['is_default']) ? cbool($v['is_default']) : 0,
    						'price_incurred'=>cprice($v['price_incurred']),
    						'price_type'=>$v['price_type'],
    						'parent_id'=>isset($v['parent_id']) ? $v['parent_id'] : 0,
    						'currency'=>isset($v['currency']) ? $v['currency'] : 1,
    						'unit_price'=>isset($v['unit_price']) ? $v['unit_price'] : 1,
    				'type_id'=>20])->execute();
    			}else {
    				Yii::$app->db->createCommand()->update('seasons_to_suppliers',[
    						'is_default'=>isset($v['is_default']) ? cbool($v['is_default']) : 0,
    						'price_incurred'=>cprice($v['price_incurred']),
    						'price_type'=>$v['price_type'],
    						'parent_id'=>isset($v['parent_id']) ? $v['parent_id'] : 0,
    						'currency'=>isset($v['currency']) ? $v['currency'] : 1,
    						'unit_price'=>isset($v['unit_price']) ? $v['unit_price'] : 1,
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
    
    }
    public function update_place($id = 0,$type = TYPE_CODE_VEHICLE){
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
    public function updateList($id = 0){
    	$c = post('c',[]);
    	$d = post('ckc_default',[]);
    	$df = [];
    	if(!empty($d)){
    		foreach ($d as $k=>$v){
    			$df[] = $v;
    		}
    	}
    	$delete_car_id = post('delete_car_id');
    	if(is_array($delete_car_id) && !empty($delete_car_id)){
    		Yii::$app->db->createCommand()->delete($this->tableCar(),array(
    				'vehicle_id'=>$delete_car_id,
    				'parent_id'=>$id
    		))->execute();
    	}
    	
    	if(!empty($c)){
    		foreach ($c as $k=>$v){
    			$v['quantity'] = cprice($v['quantity']);
    			$v['is_active'] = isset($v['is_active']) ? cbool($v['is_active']) : 0;
    			if((new Query())->from($this->tableCar())->where(array('vehicle_id'=>$v['id'],'parent_id'=>$id))->count(1) > 0){
    				Yii::$app->db->createCommand()->update($this->tableCar(),array(
    						'quantity'=>$v['quantity'],'is_active'=>$v['is_active'],
    						'is_default'=>in_array($v['id'], $df) ? 1 : 0
    				),array('vehicle_id'=>$v['id'],'parent_id'=>$id))->execute();
    			}else{
    					
    				Yii::$app->db->createCommand()->insert($this->tableCar(),array(
    						'quantity'=>$v['quantity'],'is_active'=>$v['is_active'],
    						'parent_id'=>$id,'vehicle_id'=>$v['id'],
    						'is_default'=>in_array($v['id'], $df) ? 1 : 0,
    				'state'=>1))->execute();
    					
    			}
    		}
    		 
    	}
    	// default 
    	
    
    }
    public function updatePrice($id = 0){
    	$price = post('price',[]);
    	$delete_price_id = post('delete_price_id');
    	/*//
    	if(is_array($delete_price_id) && !empty($delete_price_id)){
    		Yii::$app->db->createCommand()->delete($this->tablePrice(),$delete_price_id)->execute();
    	}
    
    	if(!empty($price)){
    		foreach ($price as $k=>$v){
    			$v['price1'] = cprice($v['price1']);
    			//$v['price3'] = cprice($v['price3']);
    			$v['price2'] = cprice($v['price2']);
    			$v['pmin'] = cprice($v['pmin']);
    			$v['pmax'] = cprice($v['pmax']);
    			$v['parent_id'] = $id;
    			if(isset($v['id']) && $v['id'] > 0){
    				$pid = $v['id']; unset($v['id']);
    				Yii::$app->db->createCommand()->update($this->tablePrice(),$v,array('id'=>$pid,'sid'=>__SID__))->execute();
    			}else{
    				$v['sid'] = __SID__;
    				Yii::$app->db->createCommand()->insert($this->tablePrice(),$v)->execute();
    				//view($v['item_id']);
    			}
    		}
    	}
    	//// distance price
    	$dprice = isset($_POST['dprice']) ? $_POST['dprice'] : array();
    	$dcurrency = isset($_POST['dcurrency']) ? $_POST['dcurrency'] : array();
    	$dactive = isset($_POST['dactive']) ? $_POST['dactive'] : array();
    	Yii::$app->db->createCommand()->delete($this->tableDistancePrice(),array('supplier_id'=>$id))->execute();
    	if(!empty($dprice)){
    		foreach ($dprice as $k=>$v){
    			if(!empty($v)){
    				foreach ($v as $k1=>$v1){
    					if(!empty($v1)){
    						foreach ($v1 as $k2=>$v2){
    							Yii::$app->db->createCommand()->insert($this->tableDistancePrice(),array(
    									'distance_id'=>$k1,
    									'item_id'=>$k2,
    									'price1'=>cprice($v2['price1']) > 0 ? cprice($v2['price1']) : 0,
    									'price2'=>cprice($v2['price2']) > 0 ? cprice($v2['price2']) : 0,
    									'currency'=>$dcurrency[$k][$k1]['currency'],
    									'is_active'=> (isset($dactive[$k][$k1]['is_active']) ? cbool($dactive[$k][$k1]['is_active']) : 0)
    							,'supplier_id'=>$id))->execute();
    						}
    					}
    
    				}
    			}
    		}
    	}
    	/*////view($dprice,true);
    }
    public function get_season_category($id = 0, $o=[]){
    	$field = isset($o['field']) ? $o['field'] : 'a.*';
    	$query = isset($o['query']) ? $o['query'] : 'row';
    	$sql = "select $field from seasons_categorys as a where a.id=$id";
    	return strtolower($query) == 'scalar' ? Yii::$app->db->createCommand($sql)->queryScalar() : Yii::$app->db->createCommand($sql)->queryOne();
    }
}
