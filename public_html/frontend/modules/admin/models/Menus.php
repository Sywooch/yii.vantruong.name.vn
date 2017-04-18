<?php

namespace app\modules\admin\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "menus".
 *
 * @property integer $id
 * @property string $title
 * @property integer $sid
 * @property integer $state
 * @property string $bizrule
 * @property integer $type_id
 * @property integer $time
 *
 * @property MenusToSuppliers[] $menusToSuppliers
 * @property Customers[] $suppliers
 */
class Menus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menus}}';
    }
    
    public static function tableToPrice()
    {
        return '{{%menus_to_prices}}';
    }
    
    public static function tableToSupplier()
    {
    	return '{{%menus_to_suppliers}}';
    }
    public static function tableToCategory()
    {
    	return '{{%menus_to_categorys}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'bizrule'], 'required'],
            [['sid', 'state', 'type_id', 'time'], 'integer'],
            [['bizrule'], 'string'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'sid' => 'Sid',
            'state' => 'State',
            'bizrule' => 'Bizrule',
            'type_id' => 'Type ID',
            'time' => 'Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenusToSuppliers()
    {
        return $this->hasMany(MenusToSuppliers::className(), ['menu_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    
    public static function getItemCategorys($menu_id,$o = 1){
    	$l = static::find()
    	->from(['a'=>FoodsCategorys::tableName()])
    	->select(['a.*'])
    	->innerJoin(['b'=>self::tableToCategory()],'a.id=b.category_id')
    //	->innerJoin(['c'=>FoodsCategorys::tableName()],'c.id=b.category_id')
    	->where(['b.item_id'=>$menu_id,'a.sid'=>__SID__])
    	->asArray()->all();
    	$r = [];
    	if(!empty($l)){
    		foreach($l as $k=>$v){
    			$r[] = $o == 1 ? $v['id'] : uh($v['title']);
    		}
    	}
    	return $r;
    }
    
    public function getSuppliers()
    {
        return $this->hasMany(Customers::className(), ['id' => 'supplier_id'])->viaTable('menus_to_suppliers', ['menu_id' => 'id']);
    }
    
    public static function getMenuPrice1($o = []){
    	$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
    	$menu_id = isset($o['menu_id']) ? $o['menu_id'] : 0;
    	//$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
    	$query = static::find()->from(['a'=>self::tableToPrice()])
    	->where([
    			'item_id'=>$menu_id,
    			'supplier_id'=>$supplier_id
    	]) 
    	->asArray()->one();
    	return $query;
    }
    
    public static function getMenuPrice($o = []){
    	$item_id = isset($o['item_id']) ? $o['item_id'] : 0;
    	$season_id = isset($o['season_id']) ? $o['season_id'] : 0;
    	$weekend_id = isset($o['weekend_id']) ? $o['weekend_id'] : 0;
    	$group_id = isset($o['group_id']) ? $o['group_id'] : 0;
    	$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
    	$package_id = isset($o['package_id']) ? $o['package_id'] : 0;
    	$quotation_id = isset($o['quotation_id']) ? $o['quotation_id'] : 0;
    	$nationality_id = isset($o['nationality_id']) ? $o['nationality_id'] : 0;
    	$time_id = isset($o['time_id']) ? $o['time_id'] :-1;
    	//$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
    	return (new Query())->select(['a.price1','a.price2','a.currency'])->from(['a'=>'menus_to_prices'])->where([
    			'item_id'=>$item_id,
    			'season_id'=>$season_id,
    			'weekend_id'=>$weekend_id,
    			'group_id'=>$group_id,
    			'supplier_id'=>$supplier_id,
    			'package_id'=>$package_id,
    			'quotation_id'=>$quotation_id,
    			'time_id'=>$time_id,
    			'nationality_id'=>$nationality_id
    	])->one();
    	
    	
    }
    
    
    public static function get_price($item_id = 0, $supplier_id=0,$type_id=1,$package_id=0){
    	//$sql = "select a.* from {$this->tablePrice()} as a";
    	//$sql .= " inner join {$this->tableRoomCategory()} as b on a.item_id=b.id";
    	//$sql .= " where a.supplier_id=$supplier_id and a.item_id=$item_id and a.type_id=$type_id and a.package_id=$package_id";
    	//$l = Yii::$app->db->createCommand($sql)->queryAll();
    	
    	$query = static::find()
    	->select(['a.*'])
    	->from(['a'=>self::tableToPrice()])
    	->innerJoin(['b'=>self::tableName()],'a.item_id=b.id')
    	->where([
    			'a.supplier_id'=>$supplier_id,
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
    
    public static function getMenuFoods($o = []){
    	//$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
    	$menu_id = isset($o['menu_id']) ? $o['menu_id'] : 0;
    	//$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
    	$query = static::find()->from(['a'=>Foods::tableName()])
    	->innerJoin(['b'=>Foods::tableToMenu()],'a.id=b.food_id')
    	->where([
    			'b.menu_id'=>$menu_id,
    			'a.sid'=>__SID__
    	])->andWhere(['>','a.state',-2])
    	->orderBy(['a.title'=>SORT_ASC])
    	->asArray()->all();
    	return $query;
    }
    public static function getItem($id=0,$o=[]){
    	$item = static::find()
    	->where(['id'=>$id, 'sid'=>__SID__]);
    
    	$item = $item->asArray()->one();
    
    	return $item;
    }
    public static function getMenus($o=[]){
    	$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : (is_numeric($o) ? $o : 0);
    	$menu_id = isset($o['menu_id']) ? $o['menu_id'] : 0;
    	$query = static::find()->from(['a'=>self::tableName()])
    	->innerJoin(['b'=>self::tableToSupplier()],'a.id=b.menu_id')
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
    			$l[$k]['prices']= self::getMenuPrice([
    					'menu_id'=>$v['id'],
    					'supplier_id'=>$supplier_id
    			]);
    			$l[$k]['foods'] = self::getMenuFoods(['menu_id'=>$v['id']]);
    		}
    	}
    	return $l;
    }
    
    public static function updatePrice($id = 0){
    	$prices = post('prices',[]);
    	Yii::$app->db->createCommand()->delete(self::tableToPrice(),array('supplier_id'=>$id))->execute();
    	
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
    														'supplier_id'=>$id])->execute();
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
