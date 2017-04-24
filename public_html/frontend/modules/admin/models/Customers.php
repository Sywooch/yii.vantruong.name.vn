<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class Customers extends \yii\db\ActiveRecord
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
        return '{{%customers}}';
    }
	public static function tableLocal()
    {
        return '{{%local}}';
    }
    public static function table_place(){
    	return DeparturePlaces::tableName();
    }
    public static function tableToPlace(){
    	return '{{%customers_to_places}}';
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
    public function get_existed_place($id = 0, $type = 2){
    	$sql = "select a.* from {$this->table_place()} as a";
    	$sql .= " where a.sid=".__SID__;
    	$sql .= " and a.id in(select b.place_id from {$this->tableToPlace()} as b where b.customer_id=$id)";
    
    	return Yii::$app->db->createCommand($sql)->queryAll();
    }
    
	public static function getSupplierPlace($supplier_id = 0){
		return (new Query())->from(['a'=>self::table_place()])
		->innerJoin(['b'=>self::tableToPlace()],'a.id=b.place_id')
		->where(['a.sid'=>__SID__,'b.customer_id'=>$supplier_id])
		->orderBy(['a.name'=>SORT_ASC])->all()
		;
	}
    
    public static function getID(){
    	$sql = "select max(id) +1 from ".self::tableName();
    	return Yii::$app->db->createCommand($sql)->queryScalar();
    }     
     
    public static function getItem($id=0,$o=[]){
    	$item = static::find()->from(['a'=>self::tableName()])
    	->select(['a.*','title'=>'a.name'])
    	->where(['a.id'=>$id, 'a.sid'=>__SID__])
    	->asArray()->one();
    
    	return $item;
    }
    public function get_customer_type_code(){
    	return array(
    			array('id'=>0,'title'=>'--',),
    			array('id'=>20,'title'=>'Doanh nghiệp tư nhân',),
    			array('id'=>21,'title'=>'Doanh nghiệp nhà nước',),
    			array('id'=>22,'title'=>'Công ty cổ phần'),
    			array('id'=>23,'title'=>'Công ty TNHH',),
    			array('id'=>24,'title'=>'Công ty hợp danh',),
    			array('id'=>25,'title'=>'Công ty liên doanh',),
    			array('id'=>26,'title'=>'Hợp tác xã',),
    			array('id'=>27,'title'=>'Cá nhân',),
    	);
    }
    
    public static function getAvailabledQuotations($supplier_id, $o = []){
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.title'=>SORT_ASC,'a.id'=>SORT_DESC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : -1;
    	//$supplier_id = isset($o['supplier_id']) ?  $o['supplier_id'] : -1;
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$offset = ($p-1) * $limit;
    	 
    	$query = static::find()
    	->from(['a'=>'supplier_quotations'])
    	->where(['a.sid'=>__SID__])
    	->andWhere(['>','a.state',-2]);
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['like', 'a.title', $filter_text]);
    	}
    	if(is_numeric($type_id) && $type_id > -1){
    		//$query->andWhere(['a.type_id'=>$type_id]);
    	}
    	if(is_numeric($supplier_id) && $supplier_id > -1){
    		///$query->innerJoin(['b'=>self::tableToSupplier()],'a.id=b.package_id');
    		$query->andWhere(['a.supplier_id'=>$supplier_id]);
    		$query->andWhere(['not in','a.id',(new Query())->from('supplier_quotations_to_supplier')->where(['supplier_id'=>$supplier_id])->select('quotation_id')]);
    	}
    	if(is_numeric($is_active) && $is_active > -1){
    		//$query->andWhere(['a.is_active'=>$is_active]);
    	}
    	//view($query->createCommand()->getSql());
    	$query->select(['a.*'])
    	->orderBy($order_by)
    	->offset($offset);
    	//->limit($limit);
    	$l = $query->asArray()->all();
    
    	return $l;
    }
    
    
    public static function getSupplierQuotations($supplier_id, $o = []){
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.title'=>SORT_ASC,'a.id'=>SORT_DESC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : -1;
    	//$supplier_id = isset($o['supplier_id']) ?  $o['supplier_id'] : -1;
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$offset = ($p-1) * $limit;
    
    	$query = static::find()
    	->from(['a'=>'supplier_quotations'])
    	->where(['a.sid'=>__SID__])
    	->andWhere(['>','a.state',-2]);
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['like', 'a.title', $filter_text]);
    	}
    	if(is_numeric($type_id) && $type_id > -1){
    		//$query->andWhere(['a.type_id'=>$type_id]);
    	}
    	if(is_numeric($supplier_id) && $supplier_id > -1){
    		///$query->innerJoin(['b'=>self::tableToSupplier()],'a.id=b.package_id');
    		//$query->andWhere(['a.supplier_id'=>$supplier_id]);
    		$query->andWhere(['in','a.id',(new Query())->from('supplier_quotations_to_supplier')->where(['supplier_id'=>$supplier_id])->select('quotation_id')]);
    	}
    	if(is_numeric($is_active) && $is_active > -1){
    		$query->andWhere(['a.is_active'=>$is_active]);
    	}
    	//view($query->createCommand()->getRawSql());
    	$query->select(['a.*'])
    	->orderBy($order_by);
    	//->offset($offset);
    	//->limit($limit);
    	$l = $query->asArray()->all();
    
    	return $l;
    }
    
    public static function getCustomerSeasons($supplier_id=0,$o = []){
    	$price_type = isset($o['price_type']) ? $o['price_type'] : [];
    	$default = isset($o['default']) && $o['default'] == true ? true : false;
    	$type_id = isset($o['type_id']) ? $o['type_id'] : [];
    	$query = new Query();
    	$query
    	->select(['a.*','b.*'])
    	->innerJoin(['b'=>'seasons_categorys_to_suppliers'],'b.season_id=a.id')
    	->from(['a'=>Seasons::tableCategory()])->where([
    		'a.sid'=>__SID__,'b.supplier_id'=>$supplier_id	
    	])->andWhere(['>','a.state',-2])
    	;
    	if(is_numeric($price_type) || !empty($price_type)){
    		
    		$query->andWhere(['b.price_type'=>$price_type]);
    	}
    	if(is_numeric($type_id) || !empty($type_id)){    	
    		$query->andWhere(['a.type_id'=>$type_id]);
    	}
    	$r = $query->orderBy(['a.type_id'=>SORT_ASC,'a.title'=>SORT_ASC])->all();
    	if($default && empty($r)){
    		$r = [
    			['id'=>0,'title'=>'Mặc định']	
    		];
    	}
    	return $r;
    }
    public static function getCustomerWeekend($o = []){
    	$price_type = isset($o['price_type']) ? $o['price_type'] : [];
    	$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
    	$return_type = isset($o['return_type']) ? $o['return_type'] : 0;
    	
    	
    	
    	$query = new Query();
    	$query
    	->select(['a.*'])
    	->innerJoin(['b'=>'seasons_categorys_to_suppliers'],'b.season_id=a.id')
    	->from(['a'=>Seasons::tableCategory()])->where([
    			'a.sid'=>__SID__,'b.supplier_id'=>$supplier_id,
    			'a.type_id'=>[3,4]
    	])->andWhere(['>','a.state',-2])
    	;
    	if(!empty($price_type)){
    		$query->andWhere(['price_type'=>$price_type]);
    	}
    	$r = $query->orderBy(['a.type_id'=>SORT_ASC,'a.title'=>SORT_ASC])->all();
    	switch ($return_type){
    		case 'for_price':
    			$t = true;
    			if(!empty($r)){
    				foreach ($r as $k=>$v){
    					if($v['type_id'] == 4) $t = false;
    				}
    			}
    			if($t){
    				$r[] = ['id'=>0,'title'=>'Ngày thường (mặc định)','type_id'=>4];
    			}
    			break;
    	}
    	
    	return $r;
    	 
    }
    
    public static function getCustomerWeekendTime($o = []){
    	$price_type = isset($o['price_type']) ? $o['price_type'] : [];
    	$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
    	$return_type = isset($o['return_type']) ? $o['return_type'] : 0;
    	 
    	 
    	 
    	$query = new Query();
    	$query
    	->select(['a.*'])
    	->innerJoin(['b'=>'seasons_categorys_to_suppliers'],'b.season_id=a.id')
    	->from(['a'=>Seasons::tableCategory()])->where([
    			'a.sid'=>__SID__,'b.supplier_id'=>$supplier_id,
    			'a.type_id'=>[5]
    	])->andWhere(['>','a.state',-2])
    	;
    	if(!empty($price_type)){
    		$query->andWhere(['price_type'=>$price_type]);
    	}
    	$r = $query->orderBy(['a.type_id'=>SORT_ASC,'a.title'=>SORT_ASC])->all();
    	switch ($return_type){
    		case 'for_price':
    			$t = true;
    			if(!empty($r)){
    				foreach ($r as $k=>$v){
    					if($v['type_id'] == 5) $t = false;
    				}
    			}
    			if($t){
    				$r[] = ['id'=>-1,'title'=>'Cả ngày (mặc định)','type_id'=>5];
    			}
    			break;
    	}
    	 
    	return $r;
    
    }
    
    /*
     * 
     */
    public static function getList($o = []){
    	
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$offset = ($p-1) * $limit;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.name'=>SORT_ASC];
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$is_active = isset($o['is_active']) ?  $o['is_active'] : -1;
    	
    	$not_in = isset($o['not_in']) ? $o['not_in'] : [];
    	$in = isset($o['in']) ? $o['in'] : [];
    	if(!is_array($in) && $in != "") $in = explode(',', $in);
    	if(!is_array($not_in) && $not_in != "") $not_in = explode(',', $not_in);
    	//view($o,true);
    	$type_id = isset($o['type_id']) ? $o['type_id'] : CONTROLLER_CODE . '';
    	$place_id = isset($o['place_id']) ? $o['place_id'] : 0;
    	$local_id = isset($o['local_id']) ? $o['local_id'] : 0;
    	$query = (new Query())   
    	->from(['a'=>self::tableName()])
    	->leftJoin(['b'=>self::tableLocal()],'a.local_id=b.id')
    	->where(['a.sid'=>__SID__])
    	->andWhere(['>','a.state',-2]);    	 
    	//->andWhere(['in','a.type_code',3]);
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['or',
    				['like', 'a.name', $filter_text],
    				['like', 'a.email', $filter_text],
    				['like', 'a.phone', $filter_text],
    		]);
    	}    	
    	if(is_numeric($type_id) && $type_id > -1){
    		$query->andWhere(['in','a.type_id',$type_id]);    		 
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
    	if($place_id > 0){
    		$query->andWhere(['in','a.id',(new Query())->select('customer_id')->from('customers_to_places')->where(['place_id'=>$place_id])]);
    	}
    	if($local_id > 0){
    		$query->andWhere(['a.local_id'=>$local_id]);
    	}
    	
    	$c = 0;
    	if($count){   	
    		$c = $query->count(1);
    	}
    	$query->select(['a.*','localName'=>'b.name','localType'=>'b.type_id','title'=>'a.name'])
    	->orderBy($order_by)
    	->offset($offset)
    	->limit($limit);
    	//view($query->createCommand()->getSql());
    	$l = $query->all();   
    	return [
    			'listItem'=>$l,
    			'total_records'=>$c,
    			'total_pages'=>ceil($c/$limit),
    			'limit'=>$limit,
    			'p'=>$p,
    	];
    	
    }
    public static function updatePlace($id = 0){
    	if($id>0){
    		$places = post('places',[]);
    		Yii::$app->db->createCommand()->delete(self::tableToPlace(),array('customer_id'=>$id))->execute();
    		if(!empty($places)){
    			foreach ($places as $p){
    				Yii::$app->db->createCommand()->insert(self::tableToPlace(),array(
    						'place_id'=>$p,'customer_id'=>$id))->execute();
    			}
    		}
    	}
    
    }
    
    public static function getSupplierDetailPrice($o = []){
    	$price_type = isset($o['price_type']) ? $o['price_type'] : 1;
    	$price = [];
    	$a = [    		
    			'item_id',
    			'season_id',
		    	'weekend_id',
		    	'group_id',
		    	'supplier_id',
		    	'package_id',
		    	'quotation_id',		    	 
		    	'nationality_id',
    	];
    	if(isset($o['parent_group_id'])){
    		$a[] = 'parent_group_id';
    	}
    	if(isset($o['vehicle_id'])){
    		$a[] = 'vehicle_id';
    	}
    	$time_id = isset($o['time_id']) ? $o['time_id'] : -1;
    	foreach ($a as $b){
    		$$b = isset($o[$b]) ? $o[$b] : 0;
    		$condition[$b] = $$b;
    	}
    	$condition['time_id'] = $time_id;
    	//////////////////////////////////////
    	$supplier = self::getItem($supplier_id);
    	if(!empty($supplier)){
    		$t = 'supplier_to_prices_default';
    		 
    		$t = Yii::$app->zii->getTablePrice($supplier['type_id'],$price_type);
    		$price = (new Query())->select(['a.price1','a.price2','a.currency','a.is_default'])->from(['a'=>$t])->where($condition)->one(); 
    		//view((new Query())->select(['a.price1','a.price2','a.currency'])->from(['a'=>$t])->where($condition)->createCommand()->getRawSql());
    	}
    	 
    	return $price;
    }
}
