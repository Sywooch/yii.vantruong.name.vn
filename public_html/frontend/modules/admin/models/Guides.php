<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class Guides extends Customers
{
	public static function getBooleanFields(){
		return [
			//	'is_active',	
		];
	}
    /**
     * @inheritdoc
     */
	public static function tableToSupplier()
	{
		return 'guides_to_suppliers';
	}
	public static function tableToPrice()
	{
		return 'guides_to_prices';
	}
	public static function tableGuide()
	{
		return 'guides';
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

    
    /*
     * 
     */
    public static function get_price($item_id = 0, $parent_id=0,$type_id=1,$package_id=0){
    	//$sql = "select a.* from {$this->tablePrice()} as a";
    	//$sql .= " inner join {$this->tableRoomCategory()} as b on a.item_id=b.id";
    	//$sql .= " where a.parent_id=$parent_id and a.item_id=$item_id and a.type_id=$type_id and a.package_id=$package_id";
    	//$l = Yii::$app->db->createCommand($sql)->queryAll();
    	 
    	$query = static::find()
    	->select(['a.*'])
    	->from(['a'=>self::tableToPrice()])
    	->innerJoin(['b'=>self::tableGuide()],'a.item_id=b.id')
    	->where([
    			'a.parent_id'=>$parent_id,
    			'a.item_id'=>$item_id,
    			'a.type_id'=>$type_id,
    			'a.package_id'=>$package_id
    	]);
    	$l = $query->asArray()->all();
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
    
    public static function getGuidePrice($o = []){
    	
    	$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
    	$menu_id = isset($o['guide_id']) ? $o['guide_id'] : 0;
    	//$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
    	$query = static::find()->from(['a'=>self::tableToPrice()])
    	->where([
    			'item_id'=>$menu_id,
    			'supplier_id'=>$supplier_id
    	])
    	->asArray()->one();
    	return $query;
    }
    
    
    public static function getGuide($id,$o = []){
    	$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
    	$menu_id = isset($o['guide_id']) ? $o['guide_id'] : 0;    	
    	//    	
    	$query = static::find()->from(['a'=>self::tableGuide()])
    	//->innerJoin(['b'=>self::tableToSupplier()],'a.id=b.guide_id')
    	->where(['a.sid'=>__SID__,'a.id'=>$id])
    	->andWhere(['>','a.state',-2])
    	//->orderBy(['a.title'=>SORT_ASC])
    	->select(['a.*','supplier_name'=>(new Query())->select('name')->from('customers')->where(['id'=>(new Query())
    			->select('supplier_id')->from('guides_to_suppliers')->where('guide_id=a.id')
    	])->limit(1)]);     
    	//
    	 	
    	//    
    	return $query->asArray()->one();
    	 
    }
    public static function getGuidesByPlace($o=[]){
    	$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
    	$menu_id = isset($o['guide_id']) ? $o['guide_id'] : 0;
    	$place_id = isset($o['place_id']) ? $o['place_id'] : 0;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';
    	$language = isset($o['language']) ? $o['language'] : '';
    	$query = static::find()->from(['a'=>self::tableGuide()])
    	//->innerJoin(['b'=>self::tableToSupplier()],'a.id=b.guide_id')
    	
    	->where(['a.sid'=>__SID__])
    	->andWhere(['>','a.state',-2])
    	->orderBy(['a.title'=>SORT_ASC])
    	->select(['a.*','supplier_name'=>(new Query())->select('name')->from('customers')->where(['id'=>(new Query())
    			->select('supplier_id')->from('guides_to_suppliers')->where('guide_id=a.id')
    	])->limit(1)]); 
    
    	if($place_id>0){
    		$query->andWhere(['in','a.id',(new Query())->select('guide_id')->from('guides_to_suppliers')->where(['supplier_id'=>(new Query())->
    				select(['customer_id'])->from(['customers_to_places'])->where(['place_id'=>$place_id])
    		])]);
    	}
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['like', 'a.title', $filter_text]);
    	}
    	if(strlen($language) > 0){
    		$query->andWhere(['a.language'=> $language]);
    	}
    	if($menu_id>0){
    		$query->andWhere(['a.id'=>$menu_id]);
    	}
    
    	$l = $query->asArray()->all();
    	$r = [];
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			//$l[$k]['prices']= self::getGuidePrice([
    			//		'guide_id'=>$v['id'],
    			//		'supplier_id'=>$supplier_id
    			//]);
    			//$l[$k]['foods'] = self::getMenuFoods(['menu_id'=>$v['id']]);
    		}
    	}
    	return $l;
    }
    
    
    public static function getGuides($o=[]){
    	$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
    	$menu_id = isset($o['guide_id']) ? $o['guide_id'] : 0;
    	$query = static::find()->from(['a'=>self::tableGuide()])
    	->innerJoin(['b'=>self::tableToSupplier()],'a.id=b.guide_id')
    	->where(['b.supplier_id'=>$supplier_id,'a.sid'=>__SID__])
    	->andWhere(['>','a.state',-2])
    	->orderBy(['a.title'=>SORT_ASC])
    	->select(['a.*']);
    	 
    	if($menu_id>0){
    		$query->andWhere(['a.id'=>$menu_id]);
    	}
    	 
    	$l = $query->asArray()->all();
    	$r = [];
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			$l[$k]['prices']= self::getGuidePrice([
    					'guide_id'=>$v['id'],
    					'supplier_id'=>$supplier_id
    			]);
    			//$l[$k]['foods'] = self::getMenuFoods(['menu_id'=>$v['id']]);
    		}
    	}
    	return $l;
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
    public static function updatePrice($id = 0){
    	$prices = post('prices',[]);
    	Yii::$app->db->createCommand()->delete(self::tableToPrice(),array('parent_id'=>$id))->execute();
    	 
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
    												Yii::$app->db->createCommand()->insert(self::tableToPrice(),[
    														'season_id'=>$k1,
    														'package_id'=>$package_id,
    														'group_id'=>$k2,
    														'type_id'=>$kb,
    														'item_id'=>$k,
    														'weekend_id'=>$k3,
    														'price1'=>$v3['price1'] != "" ? cprice($v3['price1']) : 0,
    														'price2'=>isset($v['price2']) ? cprice($v['price2']) : 0,
    														'currency'=>$v['currency'],
    														'parent_id'=>$id])->execute();
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
    	
    	$remove_menu = post('remove_menu',[]);
    	if(!empty($remove_menu)){
    		Yii::$app->db->createCommand()->delete(self::tableToSupplier(),['supplier_id'=>$id,'guide_id'=>$remove_menu])->execute();
    	}
    	//	exit;
    }
}
