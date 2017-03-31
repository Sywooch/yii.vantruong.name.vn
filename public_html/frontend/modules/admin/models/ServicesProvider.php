<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class ServicesProvider extends Customers
{
	public static function getBooleanFields(){
		return [
				'is_active',	
		];
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

     
    public function updateMenus($id){
    	$remove_menu = post('remove_menu',[]);
    	if(!empty($remove_menu)){
    		Yii::$app->db->createCommand()->delete(Menus::tableToSupplier(),['supplier_id'=>$id,'menu_id'=>$remove_menu])->execute();
    	}
    	$remove_nationality = post('remove_nationality',[]);
    	if(!empty($remove_nationality)){
    		Yii::$app->db->createCommand()->delete(NationalityGroups::tableToSupplier(),['supplier_id'=>$id,'group_id'=>$remove_nationality])->execute();
    	}
    }
    public function updateSeasons($id){
    	$seasons = post('seasons',[]);
    	Yii::$app->db->createCommand()->delete('seasons_to_suppliers',array('supplier_id'=>$id,'type_id'=>[10,20]))->execute();
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
    						'type_id'=>20])->execute();
    			}else {
    
    				Yii::$app->db->createCommand()->update('{{%seasons_to_suppliers}}',[
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
    
    public static function updatePrices($id){
    	$prices = post('prices',[]);
    	Yii::$app->db->createCommand()->delete('trains_to_prices',['parent_id'=>$id,'type_id'=>CONTROLLER_CODE])->execute();
    	if(!empty($prices)){
    		foreach ($prices as $package_id=>$v){
    			foreach ($v as $k1=>$v1){
    				foreach ($v1['list'] as $season_id =>$v2){
    					foreach ($v2 as $room_id=>$price){
    						Yii::$app->db->createCommand()->insert('trains_to_prices',[
    								'parent_id'=>$id,
    								'type_id'=>CONTROLLER_CODE,
    								'station_from'=>$v1['station_from'],
    								'station_to'=>$v1['station_to'],
    								'season_id'=>$season_id,
    								'item_id'=>$room_id,
    								'package_id'=>$package_id,
    								'currency'=>$v1['currency'],
    								'distance'=>cprice($v1['distance'])>0 ? cprice($v1['distance']) : 0,
    								'price1'=>cprice($price) > 0 ? cprice($price) : 0,
    								
    						])->execute();
    					}
    				}
    			}
    		}
    	}
    	$remove_package_routes = post('remove_package_routes',[]);
    	if(!empty($remove_package_routes)){
    		foreach ($remove_package_routes as $package_id=>$v){
    			foreach ($v as $v1){
    			$x = explode('-', $v1);
    			
    			Yii::$app->db->createCommand()->delete('trains_to_prices',[
    					'parent_id'=>$id,
    					'type_id'=>CONTROLLER_CODE,
    					'package_id'=>$package_id,
    					'station_from'=>$x[0],
    					'station_to'=>$x[1],
    			])->execute();
    			
    			}
    		}
    	}
    //	exit;
    }
    
    public static function getTrainPrice($station_from = 0, $station_to = 0, $parent_id = 0, $package_id = 0){
    	$l = static::find()->from(['a'=>'trains_to_prices'])->where([
    			'station_from'=>$station_from,
    			'station_to'=>$station_to,
    			'parent_id'=>$parent_id,
    			'package_id'=>$package_id,
    	])->asArray()->all();
    	$r = [];
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			$r['price2'] = $v['price2'];
    			$r['currency'] = $v['currency'];
    			$r['distance'] = $v['distance'];
    			$r[$v['season_id']][$v['item_id']]['price1'] = $v['price1'];
    		}
    	}
    	return $r;
    }
    
    public static function get_price($item_id = 0, $parent_id=0,$type_id=1,$package_id=0){
    	$sql = "select a.* from {$this->tablePrice()} as a";
    	$sql .= " inner join {$this->tableRoomCategory()} as b on a.item_id=b.id";
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
    
    
    public function updateToSupplier($id){
    	$items = post('items',[]);
    	Yii::$app->db->createCommand()->delete('rooms_to_hotel',['parent_id'=>$id,'room_id'=>post('remove_items',[])])->execute();
    	if(!empty($items)){
    		foreach ($items as $k=>$v){
    			Yii::$app->db->createCommand()->update('rooms_to_hotel',['quantity'=>cprice($v['quantity'])],['parent_id'=>$id,'room_id'=>$k])->execute();
    		}
    	}
    	//
    	// $items = post('remove_routes',[]);
    	Yii::$app->db->createCommand()->delete('distances_to_suppliers',['supplier_id'=>$id,'item_id'=>post('remove_routes',[])])->execute();    	
    }
     
    
    public static function getTrainDistanceBySupplier($o=[], $parent_id = 0, $package_id = 0){
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : 0;
    	$package_id = isset($o['package_id']) ? $o['package_id'] : 0;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : TYPE_ID_TRAIN;
    	$query = static::find()->select(['station_from','station_to'])->from(['a'=>'trains_to_prices'])->where(['parent_id'=>$parent_id,'package_id'=>$package_id])->orderBy(['a.station_from'=>SORT_ASC, 'a.distance'=>SORT_ASC]);
    	//$l = $query->asArray()->all();
    	$r1 = $r2 = [];$r = [];$stations = [];
    	foreach ($query->asArray()->all() as $k=>$v){
    		$r1[$v['station_from'] .'-' . $v['station_to']] = [$v['station_from'],$v['station_to']];
    		if(!in_array($v['station_from'], $r)) $r[] = $v['station_from'];if(!in_array($v['station_to'], $r)) $r[] = $v['station_to'];
    	}
    	if(!empty($r)){
    		foreach (Stations::getAll(['type_id'=>$type_id,'in'=>$r]) as $k=>$v){
    			$stations[$v['id']] = uh($v['title']);
    		}    		
    	}
    	if(!empty($r1)){
    		foreach ($r1 as $k=>$v){
    			$r1[$k]['distance'] = $stations[$v[0]] .' - ' . $stations[$v[1]];
    		}
    	}
    	
    	return $r1;
    }
    /*
     * 
     */
     
}
