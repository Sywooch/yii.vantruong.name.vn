<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
use app\modules\admin\models\AdminMenu;
class Distances extends \yii\db\ActiveRecord
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
        return '{{%distances}}';
    }
    public static function table_to_place(){
    	return 'distance_to_places';
    }
    public static function table_to_prices(){
    	return 'distances_to_prices';
    }
    public static function table_place(){
    	return 'departure_places';
    }
    public static function tableToSupplier(){
    	return 'distances_to_suppliers';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
        ];
    }
    public function get_list_seats($id = 0){
     
    	return VehiclesCategorys::get_list_seats();
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
    
    public static function getItemBySupplier($id){
    	$query = static::find()
    	->select(['a.*'])
    	->from(['a'=>self::tableName()])
    	->innerJoin(['b'=>self::tableToSupplier()],'a.id=b.item_id')
    	->where(['b.supplier_id'=>$id])
    	->orderBy(['a.title'=>SORT_ASC]);
    	return $query->asArray()->all();
    }
    
    public static function getAll($type_id = CONTROLLER_CODE,$o = []){
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : -1;
    	$local = isset($o['local']) && is_numeric($o['local']) ? $o['local'] : 0;
    	$place_id = isset($o['place_id']) && is_numeric($o['place_id']) ? $o['place_id'] : 0;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : ''; 
    	$not_in = isset($o['not_in']) ? $o['not_in'] : [];
    	$in = isset($o['in']) ? $o['in'] : [];
    	if(!is_array($in) && $in != "") $in = explode(',', $in);
    	if(!is_array($not_in) && $not_in != "") $not_in = explode(',', $not_in);
    	$query = static::find()
    	->select(['a.*'])
    	->from(['a'=>self::tableName()])
    	//->innerJoin(['b'=>self::tableToSupplier()],'a.id=b.room_id')
    	->where(['a.type_id'=>$type_id])->andWhere(['>','a.state',-2])
    	->orderBy(['a.title'=>SORT_ASC]);
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['like', 'a.title', $filter_text]);
    	}
    	if(!empty($in)){
    		$query->andWhere(['a.id'=>$in]);
    	}
    	if(!empty($not_in)){
    		$query->andWhere(['not in','a.id',$not_in]);
    	}
    	if($place_id>0){
    		$query->andWhere(['a.id'=>(new Query())->select('distance_id')->from('distance_to_places')->where(['place_id'=>$place_id,'type_id'=>$type_id])]);
    	}
    	if($limit>0){
    		$query->limit($limit);
    	}
    	
    	return $query->asArray()->all(); 
    }
    
    /*
     * 
     */
    public function get_list_hight_way($id=0,$o = array()){
    	$not_in = isset($o['not_in']) ? $o['not_in'] : array();
    	$prices = isset($o['prices']) && $o['prices'] == false ? $o['prices'] : true;
    	$in = isset($o['in']) ? $o['in'] : array();
    	if(!is_array($not_in) && $not_in != ""){
    		$not_in = explode(',', $not_in);
    	}
    	if(!is_array($in) && $in != ""){
    		$in = explode(',', $in);
    	}
    	if($id == -1 ) return [];
    	$sql = "SELECT a.*";
    	$sql.= $id>0 ? " ,b.is_required,b.around " : '';
    	$sql .= " FROM {$this->tableName()} as a";
    	$sql .= $id > 0 ? " left outer join {$this->table_to_place()} as b on a.id=b.place_id and b.type_id=".TYPE_CODE_HIGHT_WAY : '';
    	$sql .= " where a.state>-2 and a.sid=".__SID__;
    	$sql .= " and a.type_id=". TYPE_CODE_HIGHT_WAY;
    	$sql .= $id > 0 ? " and b.distance_id=$id" : "";
    	$sql .= $id > 0 ? " and a.id in(select place_id from {$this->table_to_place()} where distance_id=$id and type_id=".TYPE_CODE_HIGHT_WAY.")" : '';
    	$sql .= !empty($in) ? " and a.id in(".implode(',', $in).")" : '';
    	$sql .= !empty($not_in) ? " and a.id not in(".implode(',', $not_in).")" : '';
    
    	$sql .= " group by a.id order by a.title";
    	//view($sql);
    	$l = Yii::$app->db->createCommand($sql)->queryAll();
    	$r = array();
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			$v['prices'] = $prices ? $this->get_hight_way_prices($v['id']) : array();
    			$r[] = $v;
    		}
    	}
    	return $r;
    }
    public function get_hight_way_prices($id = 0,$seats=0){
    	$sql = "select * from {$this->table_to_prices()} as a where a.distance_id=$id and a.parent_id=0";
    	$sql .= $seats > 0 ? " and a.vehicle_id=$seats" : '';
    	$l = Yii::$app->db->createCommand($sql)->queryAll();
    	$r = array();
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			$r[$v['vehicle_id']] = $v;
    		}}
    		return $r;
    }
    public function show_list_hight_way($id = 0){
    	if($id > 0){
    		$r = [];
    		$linkx =  AdminMenu::get_menu_link('hight_way',TYPE_CODE_HIGHT_WAY).'/edit?id=';
    		$l = $this->get_list_hight_way($id,array('prices'=>false));
    		if(!empty($l)){
    			foreach ($l as $k=>$v){
    				$link = $linkx . $v['id'].'#tab-panel-prices';
    				$r[] = '<a href="'.$link.'" class="block hover_underline" target="_blank">' . $v['title'] . (isset($v['around']) && $v['around'] > 1 ? ' <i class="red"> &nbsp;('.$v['around'].')</i>' : '') . '</a>';
    					
    			}
    			return implode('', $r);
    		}
    		 
    	}
    	return '';
    }
    public function get_existed_place($id = 0, $type = CONTROLLER_CODE){
    	$sql = "select a.* from {$this->table_place()} as a";
    	$sql .= " where a.sid=".__SID__;
    	$sql .= " and a.id in(select b.place_id from {$this->table_to_place()} as b where b.distance_id=$id and b.type_id=$type)";
    	 
    	return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public static function getList($o = []){
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.title'=>SORT_ASC,'a.id'=>SORT_DESC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);    
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';    	
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : CONTROLLER_CODE;
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$place_id = isset($o['place_id']) ? $o['place_id'] : 0;
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
    	if($place_id>0){
    		$query->andWhere(['a.id'=>(new Query())->from('distance_to_places')->where(['place_id'=>$place_id, 'type_id'=>$type_id])->select('distance_id')]);
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
    
    public function updateHighway($id){
    	$hight_way = post('hight_way',[]);
    	//view($hight_way);
    	if(!empty($hight_way)){
    		foreach ($hight_way as $k=>$v){
    			$x['is_required'] = isset($v['is_required']) ? cbool($v['is_required']) : 0;
    			$x['around'] = isset($v['around']) && cbool($v['around']) == 1 ? 2 : 1;
    			Yii::$app->db->createCommand()->update('distance_to_places',$x,['place_id'=>$k,'distance_id'=>$id,'type_id'=>TYPE_CODE_HIGHT_WAY])->execute();
    		}
    	}
    	//exit;
    	$delete_high_way = post('delete_high_way',[]);
    	Yii::$app->db->createCommand()->delete('distance_to_places',['place_id'=>$delete_high_way,'distance_id'=>$id,'type_id'=>TYPE_CODE_HIGHT_WAY])->execute();
    }
}
