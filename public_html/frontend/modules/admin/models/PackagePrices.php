<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class PackagePrices extends \yii\db\ActiveRecord
{
	public static function getBooleanFields(){
		return [
				//'is_active',	
		];
	}
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%package_prices}}';
    }
    public static function tableToSupplier()
    {
    	return '{{%package_to_supplier}}';
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
    
    public static function getAvailabledPackages($supplier_id, $o = []){
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
    	->from(['a'=>self::tableName()])
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
    		$query->andWhere(['not in','a.id',(new Query())->from(self::tableToSupplier())->where(['supplier_id'=>$supplier_id])->select('package_id')]);
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
    
    public static function getPackages($supplier_id, $default = true){
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
    	->from(['a'=>self::tableName()])
    	->where(['a.sid'=>__SID__])
    	->andWhere(['>','a.state',-2]);
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['like', 'a.title', $filter_text]);
    	}
    	if(is_numeric($type_id) && $type_id > -1){
    		//$query->andWhere(['a.type_id'=>$type_id]);
    	}
    	if(is_numeric($supplier_id) && $supplier_id > -1){
    		$query->innerJoin(['b'=>self::tableToSupplier()],'a.id=b.package_id');
    		$query->andWhere(['b.supplier_id'=>$supplier_id]);
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
    	if($default && empty($l)){
    		$l= [
    				[
    						'id'=>0,
    						'title'=>''
    				]
    		];
    	}
    	return $l;
    }
    public static function getList($o = []){
    	
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.title'=>SORT_DESC,'a.id'=>SORT_DESC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);    
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';    	
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : -1;
    	$supplier_id = isset($o['supplier_id']) ?  $o['supplier_id'] : -1;
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$not_in = isset($o['not_in']) ? $o['not_in'] : [];
    	 
    	if(!is_array($not_in)){
    		$not_in = explode(',', $not_in);
    	}
    	
    	$offset = ($p-1) * $limit;
    	 
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	->where(['a.sid'=>__SID__])
    	->andWhere(['>','a.state',-2]);
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['like', 'a.title', $filter_text]);
    	}
    	if(is_numeric($type_id) && $type_id > -1){
    		//$query->andWhere(['a.type_id'=>$type_id]);
    	}
    	 
    	if(is_numeric($is_active) && $is_active > -1){
    		//$query->andWhere(['a.is_active'=>$is_active]);
    	}
    	if(!empty($not_in)){
    		$query->andWhere(['not in','a.id',$not_in]);
    	}
    	if(is_numeric($supplier_id) && $supplier_id > -1){
    		$query->innerJoin(['b'=>self::tableToSupplier()],'a.id=b.package_id');
    		$query->andWhere(['b.supplier_id'=>$supplier_id]);
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
