<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class Seasons extends \yii\db\ActiveRecord
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
        return '{{%seasons}}';
    }
    
    public static function tableWeekend(){
    	return '{{%weekend}}';
    }
    public static function tableCategory(){
    	return '{{%seasons_categorys}}';
    }
    public static function tableToSuppliers(){
    	return '{{%seasons_to_suppliers}}';
    }
    public static function table_category_to_supplier(){
    	return '{{%seasons_categorys_to_suppliers}}';
    }
	public static function tableCategoryToServices(){
    	return '{{%seasons_categorys_to_services}}';
    }
    public static function get_rooms_groups($supplier_id = 0,$default = true){
    	$sql = "select * from rooms_groups as a ";
    	$sql .= " where a.parent_id=$supplier_id";
    	//	$sql .= $type_id > 0 ? "  and a.type_id=$type_id" : "";
    	$sql .= " order by a.pmin";
    	//	view($sql);
    	$v = Yii::$app->db->createCommand($sql)->queryAll();
    	if($default && empty($v)){
    		$v = [
    				[
    					'id'=>0,'title'=>'Thiết lập nhóm'	,'note'=>''
    				]
    		];
    	}
    		return $v;
    }
    
    public static function get_incurred_category_for_price($id = CONTROLLER_CODE,$type_id = [2],$o = []){
    	if(is_numeric($type_id)) $type_id = [$type_id];
    	$price_type = isset($o['price_type']) ? $o['price_type'] : -1;
    	$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
    	//
    	$query= static::find()->from(['a'=>'seasons_categorys'])
    	->leftJoin(['b'=>self::tableToSuppliers()],'a.id=b.season_id')
    	//->innerJoin(['b'=>'seasons_categorys_to_suppliers'],'a.id=b.season_id')
    	->where(['a.sid'=>__SID__])
    	->andWhere(['or',[
    			'b.supplier_id'=>$id,'b.type_id'=>2
    	]+(!empty($type_id) ? ['a.type_id'=>$type_id] : []),[
    			'a.id'=>(new Query())->select('season_id')->from('seasons_categorys_to_suppliers')->where(['supplier_id'=>$supplier_id])
    	]]);
    	
    	
    	//if(!empty($type_id)){
    		//$query->andWhere(['a.type_id'=>$type_id]);
    	//}
    	$query->andWhere(['a.id'=>(new Query())->select('season_id')->from(self::tableToSuppliers())->where([
    			'supplier_id'=>$supplier_id,
    			//'season_id'=>'a.id',
    			//'price_type'=>0,
    			'type_id'=>20
    	])]);
    	
    	$query->orderBy(['a.type_id'=>SORT_ASC,'a.position'=>SORT_ASC, 'a.title'=>SORT_ASC]);
    	return $query->asArray()->all();
    }
    
    public static function get_incurred_category($id = CONTROLLER_CODE,$type_id = [2],$o = []){
    	if(is_numeric($type_id)) $type_id = [$type_id];
    	$price_type = isset($o['price_type']) ? $o['price_type'] : -1;
    	$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
    	$sql = "select a.* from seasons_categorys as a";
    	$sql .= " left outer join seasons_to_suppliers as b on a.id=b.season_id ";
    	$sql .= " where a.sid=".__SID__." and (b.supplier_id=$id and b.type_id=2";
    	$sql .= !empty($type_id) ? "  and a.type_id in (".implode(',', $type_id).")" : "";
    	$sql .= ")";
    	//if($price_type > -1){
    		//$sql .= " and b.price_type=$price_type" ;
    	//}
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
    	if(!is_array($in)) $in = explode(',', $in);
    	if(!is_array($not_in)) $in = explode(',', $not_in);
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
    	if(!empty($in)){
    		$query->andWhere(['a.id'=>$in]);
    	}
    	if(!empty($not_in)){
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
    public static function get_season_type_category(){
    	return array(
    			array('id'=>0,'title'=>'Khác'),
    			array('id'=>2,'title'=>'Mùa dịch vụ'),
    			array('id'=>3,'title'=>'Nhóm cuối tuần'),
    			array('id'=>4,'title'=>'Nhóm ngày trong tuần'),
    			//array('id'=>8,'title'=>'Tàu thuyền'),
    	);
    }
    public function get_incurred_services(){
    	return array(
    			array('id'=>TYPE_ID_HOTEL,'title'=>'Khách sạn'),
    			array('id'=>TYPE_ID_REST,'title'=>'Nhà hàng'),
    			array('id'=>TYPE_ID_VECL,'title'=>'Vận chuyển'),
    			array('id'=>TYPE_ID_SHIP,'title'=>'Tàu thuyền'),
    			array('id'=>TYPE_ID_SHIP_HOTEL,'title'=>'Tàu ngủ'),
    			array('id'=>TYPE_ID_GUIDES,'title'=>'Hướng dẫn viên'),
    			array('id'=>TYPE_ID_TRAIN,'title'=>'Tàu hỏa'),
    	);
    }
    
    
    public static function getAvailableSeasons($type_id = 2, $supplier_id = 0){
    	$query = new Query();
    	$query->from(['a'=>self::tableCategory()])
    	->where([
    			'a.sid'=>__SID__
    	])->andWhere(['>','a.state',-2]);
    	
    	
    	if($supplier_id > 0){
    		$query->andWhere(['a.owner'=>[0,$supplier_id]]);
    		$query->andWhere(['not in','a.id',(new Query())->select('season_id')->from('seasons_categorys_to_suppliers')->where([
    			'supplier_id'=>$supplier_id,
    			 
    		])]);
    		
    		$query->andWhere(['or',['in','a.id',(new Query())->select('season_id')->from(self::tableCategoryToServices())->where([
    				'service_id'=>$type_id,
    				'is_active'=>1
    		])],['a.owner'=>$supplier_id]]);
    	}else{
    		$query->andWhere(['in','a.id',(new Query())->select('season_id')->from(self::tableCategoryToServices())->where([
    				'service_id'=>$type_id,
    				'is_active'=>1
    		])]);
    	}
    	
    	return $query->orderBy(['a.title'=>SORT_ASC])->all();
    }
    
    public static function getListx($o = []){
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.title'=>SORT_ASC,'a.id'=>SORT_DESC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : [];
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$not_in = isset($o['not_in']) ? $o['not_in'] : [];
    	$in = isset($o['in']) ? $o['in'] : [];
    	if(!is_array($in)) $in = explode(',', $in);
    	if(!is_array($not_in)) $in = explode(',', $not_in);
    	$offset = ($p-1) * $limit;
    	$query = static::find()
    	->from(['a'=>self::tableCategory()])
    	->where(['a.sid'=>__SID__])
    	->andWhere(['>','a.state',-2]);
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['like', 'title', $filter_text]);
    	}
    	if(!empty($type_id)){
    		$query->andWhere(['a.type_id'=>$type_id]);
    	}else{
    		$query->andWhere(['a.type_id'=>[2,3,4]]);
    	}
    	if(is_numeric($parent_id) && $parent_id > -1){
    		$query->andWhere(['a.parent_id'=>$parent_id]);
    	}
    	if(is_numeric($is_active) && $is_active > -1){
    		$query->andWhere(['a.is_active'=>$is_active]);
    	}
    	if(!empty($in)){
    		$query->andWhere(['a.id'=>$in]);
    	}
    	if(!empty($not_in)){
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
    
    public static function getAvailableWeekend($o = []){
    	//$parent_id = isset($o['parent_id']) ? $o['parent_id'] : 0;
    	$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
    	$type_id = isset($o['type_id']) ? $o['type_id'] : -1;
    	
    	$query = (new Query())->from(['a'=>self::tableWeekend()])->where([
    			'a.sid'=>__SID__
    	]);
    	//if($parent_id > 0){
    		//$query->andWhere(['a.parent_id'=>$parent_id]);
    	//}
    	if($supplier_id > 0 ){
    		$query->andWhere(['not in','a.id',(new Query())->select('season_id')->from('seasons_to_suppliers')->where([
    				'supplier_id'=>$supplier_id,    				
    				'type_id'=>[SEASON_TYPE_WEEKEND,SEASON_TYPE_WEEKDAY],
    		])]);
    	}
    	if($type_id != -1){
    		$query->andWhere(['a.type_id'=>$type_id]);
    	}
    	return $query->orderBy([
    			'a.title'=>SORT_ASC
    	])->all();
    }
    
    public static function getAvailableSeason($o = []){
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : 0;
    	$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
    	$type_id = isset($o['type_id']) ? $o['type_id'] : -1;
    	 
    	$query = (new Query())->from(['a'=>self::tableName()])
    	->where([
    			'a.sid'=>__SID__,'a.is_default'=>1
    	]);
    	if($parent_id > 0){
    		//$query->andWhere(['a.parent_id'=>$parent_id]);
    	}
    	if($supplier_id > 0 && $parent_id > 0){
    		$query->andWhere(['not in','a.id',(new Query())->select('season_id')->from('seasons_to_suppliers')->where([
    				'parent_id'=>$parent_id,
    				'supplier_id'=>$supplier_id,
    				'type_id'=>$type_id
    		])]);
    	}
    	if($type_id != -1){
    		$query->andWhere(['a.type_id'=>$type_id]);
    	}
    	return $query->orderBy([
    			'a.title'=>SORT_ASC
    	])->all();
    }
    
    public function get_weekend($o = array()){
    	$limit = isset($o['limit']) ? $o['limit'] : 30;
    	$select = isset($o['select']) ? $o['select'] : 'a.*';
    	$type_id = isset($o['type_id']) && !empty($o['type_id']) ?  $o['type_id'] : [3,4];
    	if(!is_array($type_id)) $type_id = [$type_id];
    	$p = isset($o['p']) ? $o['p'] : getParam('p',array('num'=>true,'min'=>1,'default'=>1));
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$option = array('biz'=>true,'content'=>(isset($o['content']) && $o['content'] == false ? false   : true));
    	$filter_text = getParam('filter_text');
    	$not_in = isset($o['not_in']) ? $o['not_in'] : [];
    	$in = isset($o['in']) ? $o['in'] : [];
    	if(!is_array($in) && $in != "") $in = explode(',', $in);
    	if(!is_array($not_in) && $not_in != "") $not_in = explode(',', $not_in);
    	//////////
    	$offset = ($p-1) * $limit;
    	
    	$query = static::find()
    	->from(['a'=>self::tableWeekend()])
    	//->innerJoin(['b'=>self::tableCategory()],'a.parent_id=b.id')
    	->where(['a.sid'=>__SID__])
    	->andWhere(['>','a.state',-2]);
    	if(is_array($in) && !empty($in)){
    		$query->andWhere(['in','a.id',$in]);
    	}
    	if(is_array($not_in) && !empty($not_in)){
    		$query->andWhere(['not in','a.id',$not_in]);
    	}
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['like', 'a.title', $filter_text]);
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
    	->orderBy(['a.title'=>SORT_ASC])
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
    
    public static function get_incurred_category_by_supplier($id = CONTROLLER_CODE,$type_id = [2],$o = []){
    	if(is_numeric($type_id)) $type_id = [$type_id];
    	$sql = "select a.* from ".self::tableCategory()." as a inner join ".self::tableToSuppliers()." as b on a.id=b.season_id ";
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
    			$sql = "select * from ".self::tableToSuppliers()." where 1";
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
    public static function get_selected_supplier_holiday($season_id = 0,$type_id=0){
    	$sql = "select service_id from ".self::tableCategoryToServices()." where season_id=$season_id ";
    	$l  = Yii::$app->db->createCommand($sql)->queryAll();
    	$r = array();
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			switch ($v['service_id']){
    				case TYPE_ID_HOTEL: $r[] = 'Khách sạn'; break;
    				case TYPE_ID_REST: $r[] = 'Nhà hàng'; break;
    				case TYPE_ID_VECL: $r[] = 'Xe vận chuyển'; break;
    				case TYPE_ID_SHIP: $r[] = 'Tàu thuyền'; break;
    				case TYPE_ID_SHIP_HOTEL: $r[] = 'Tàu ngủ'; break;
    				case TYPE_ID_GUIDES: $r[] = 'Hướng dẫn viên'; break;
    				case TYPE_ID_TRAIN: $r[] = 'Tàu hỏa'; break;
    				default: $r[] = 'Khác'; break;
    			}
    		}
    	}else{
    		$r[] = '-';
    	}
    	return $r;
    }
    
    public static function get_unit_prices(){
    	return array(
    			array('id'=>1,'title'=>'Phòng / Xe vận chuyển'),
    			array('id'=>2,'title'=>'Khách'),
    			//array('id'=>3,'title'=>'Đoàn'),
    			//array('id'=>4,'title'=>'Tàu thuyền'),
    	);
    }
    
    public static function get_incurred_charge_type($controller_code = 0){
    	switch ($controller_code){
    		case 4:
    			$r = [
    				//['id'=>-1,'title'=>'Không sử dụng'],
    				['id'=>0,'title'=>'Tính giá trực tiếp'],
    				['id'=>1,'title'=>'Tính giá phát sinh (%)'],
    				['id'=>2,'title'=>'Phụ thu tiền mặt'],
    				//['id'=>8,'title'=>'Tàu thuyền'],
    			];
    			break;
    		case 6: 
    			$r = [
    					//['id'=>-1,'title'=>'Không sử dụng'],
    					['id'=>0,'title'=>'Tính giá trực tiếp'],
    					['id'=>1,'title'=>'Tính giá phát sinh (%)'],
    					['id'=>2,'title'=>'Phụ thu tiền mặt'],
    					//['id'=>8,'title'=>'Tàu thuyền'],
    			];
    			break;
    		default:
    			$r = [
    					['id'=>-1,'title'=>'Không sử dụng'],
    					['id'=>0,'title'=>'Tính giá trực tiếp'],
    					['id'=>1,'title'=>'Tính giá phát sinh (%)'],
    					['id'=>2,'title'=>'Phụ thu tiền mặt'],
    					//['id'=>8,'title'=>'Tàu thuyền'],
    			];
    			break;
    	}
    	return $r;
    }
    public static function get_season_supplier($o = []){
    	
    	$season_id = isset($o['season_id']) ? $o['season_id'] : -1;
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : -1;
    	$type_id = isset($o['type_id']) ? $o['type_id'] : -1;
    	$price_type = isset($o['price_type']) ? $o['price_type'] : -1;
    	/* 
    	$sql = "select * from ".self::tableToSuppliers()." as a where 1";
    	$sql .= $season_id > -1 ? " and a.season_id=$season_id" : '';
    	$sql .= $parent_id > -1 ? " and a.parent_id=$parent_id" : '';
    	$sql .= $supplier_id > -1 ? " and a.supplier_id=$supplier_id" : '';
    	$sql .= $type_id > -1 ? " and a.type_id=$type_id" : '';
    	$sql .= $price_type > -1 ? " and a.price_type=$price_type" : '';
    	return Yii::$app->db->createCommand($sql)->queryOne();
    	*/
    	$query = (new Query())->from(['a'=>self::tableToSuppliers()])->where([
    			'a.supplier_id'=>$supplier_id
    	]);
    	if($season_id > -1){
    		$query->andWhere(['a.season_id'=>$season_id]);
    	}
    	if($parent_id > -1){
    		$query->andWhere(['a.parent_id'=>$parent_id]);
    	}
    	if($type_id > -1){
    		$query->andWhere(['a.type_id'=>$type_id]);
    	}
    	if($price_type > -1){
    		$query->andWhere(['a.price_type'=>$price_type]);
    	}
    	return $query->one();
    }
    public static function get_list_seasons_by_parent($supplier_id = 0, $season_category_id=0){
    	$sql = "select a.* from ".self::tableName()." as a where a.state>-2 and a.sid=".__SID__;
    	$sql .= " and a.id in(select season_id from ".self::tableToSuppliers()." where supplier_id=$supplier_id 
    	and parent_id=$season_category_id)";
    	//$sql .= " and ";
    	$sql .= " order by a.from_date,a.title";
    	//view($sql);
    	return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public static function get_list_weekend_by_parent($supplier_id = 0, $season_category_id=0){
    	//
    	$query = (new Query())->from(['a'=>self::tableWeekend()])
    	->innerJoin(['b'=>self::tableToSuppliers()],'a.id=b.season_id')
    	->where([
    			'b.type_id'=>[SEASON_TYPE_WEEKEND,SEASON_TYPE_WEEKDAY,SEASON_TYPE_TIME],
    			'b.supplier_id'=>$supplier_id,
    			'b.parent_id'=>$season_category_id
    	]);
    	return $query->select(['a.*'])->orderBy(['a.from_date'=>SORT_ASC,'a.from_time'=>SORT_ASC])->all();
    	 
    }
    public function check_holiday_supplier($season_id = 0, $service_id = 0, $type_id = 0){
    	$c = (new Query())->from(self::tableCategoryToServices())->where(array(
    			'season_id'=>$season_id,
    			'service_id'=>$service_id,
    			//'type_id'=>$type_id
    	))->count(1);
    	return $c > 0 ? true : false;
    }
    public function getUserSeason($season_id = 0,$supplier_id=0, $price_type = []){
    	$query = (new Query())->from(['a'=>Seasons::tableCategory()])
    	->innerJoin(['b'=>Seasons::table_category_to_supplier()],'a.id=b.season_id')
    	->where(['and',
    			['a.sid'=>__SID__,
    				'b.supplier_id'=>$supplier_id,
    				//'a.id'=>$season_id,
    					//'b.price_type'=>[0]
    			] + (!empty($price_type) ? ['b.price_type'=>$price_type] : []),
    			['>','a.state',-2]
    	
    	]);
    	if($season_id>0){
    		$query->andWhere(['a.id'=>$season_id]);
    	}else {
    		return $query->all();
    	}
    	
    	return $query->one();
    }
    public function getUserSeasons($season_id = 0,$supplier_id=0, $price_type = []){
    	$query = (new Query())->from(['a'=>Seasons::tableCategory()])
    	->innerJoin(['b'=>Seasons::table_category_to_supplier()],'a.id=b.season_id')
    	->where(['and',
    			['a.sid'=>__SID__,
    					'b.supplier_id'=>$supplier_id,
    					//'a.id'=>$season_id,
    					//'b.price_type'=>[0]
    			] + (!empty($price_type) ? ['b.price_type'=>$price_type] : []),
    			['>','a.state',-2]
    			 
    	]);
    	if($season_id>0){
    		$query->andWhere(['a.id'=>$season_id]);
    	}     		
    	return $query->all();    	     	     
    }
    public function getCategoryItem($id = 0){
    	return (new Query())->from(['a'=>self::tableCategory()])->where(['id'=>$id])->one();
    }
    public function get_category_item($id = 0){    	
    	return (new Query())->from(['a'=>self::tableCategory()])->where(['id'=>$id])->one();
    }
    public function delete_item($item){    	
    	return Yii::$app->db->createCommand()->delete(self::tableName(),['id'=>$item])->execute();
    }
    public function delete_item1($item){    	
    	return Yii::$app->db->createCommand()->delete(self::tableCategory(),['id'=>$item])->execute();
    }
    public function delete_item3($item){    	
    	return Yii::$app->db->createCommand()->delete(self::tableWeekend(),['id'=>$item])->execute();
    }
}
