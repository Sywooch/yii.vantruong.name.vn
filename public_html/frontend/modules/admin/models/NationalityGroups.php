<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
use app\modules\admin\models\Local;
class NationalityGroups extends \yii\db\ActiveRecord
{
	public static function getBooleanFields(){
		return [
				//'is_active',	
				'is_locked'
		];
	}
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%nationality_groups}}';
    }
    public static function tableToLocal()
    {
    	return '{{%nationality_groups_to_local}}';
    }
    public static function tableToSupplier()
    {
    	return '{{%nationality_groups_to_supplier}}';
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

    public static function saveLocal($id){
    	$fx = post('fx');
    	Yii::$app->db->createCommand()->delete(self::tableToLocal(),['group_id'=>$id])->execute();
    	if(isset($fx['local_id']) && !empty($fx['local_id'])){
    		$row = [];
    		foreach ($fx['local_id'] as $f){
    			$row[] = [$id,$f];
    		}
    		Yii::$app->db->createCommand()->batchInsert(self::tableToLocal(),['group_id','local_id'],$row)->execute();    		 
    	}
    }
    public function getID(){
    	$sql = "select max(id) +1 from ".self::tableName();
    	return Yii::$app->db->createCommand($sql)->queryScalar();
    }     
     
    public static function getItem($id=0,$o=[]){    	
    	$item = static::find()
    	->where(['id'=>$id ]);
    
    	$item = $item->asArray()->one();
    	if(!empty($item)){
    		$item['local_id'] = []; 
    		$l = static::find()->from(['a'=>Local::tableName()])->where([
    				'in','a.id',(new Query)->select('local_id')->from(self::tableToLocal())->where(['group_id'=>$item['id']])
    		])->asArray()->all();
    		if(!empty($l)){
    			foreach($l as $k=>$v){
    				$item['local_id'][] = $v['id'];
    			}
    		}
    	}
    	return $item;
    }
    /*
     * 
     */
    public function get_all_local_other($supplier_id= 0, $id = 0){
    	$sql = "select * from {{%local}} as a where a.parent_id=0 and a.id not in(
    	select local_id from {$this->tableToLocal()} where group_id in(select group_id from {$this->tableToSupplier()} where supplier_id=$supplier_id) and group_id in(select id from {$this->tableName()} where state>-2)
    	)";
    	return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public static function get_all_supplier_group($supplier_id = 0,$o = []){
    	$not_in = isset($o['not_in']) ? $o['not_in'] : [];
    	$state = isset($o['state']) ? $o['state'] : false;
    	if(!is_array($not_in) && $not_in != "") $not_in = explode(',', $not_in);
    	/*
    	$sql = "select a.*,(select count(1) from {$this->tableToLocal()} where group_id=a.id) as count_local from {$this->tableName()} as a";
    	// Join bảng (tùy chọn)
    	$sql .= " where  a.state>-2";
    	$sql .= is_numeric($state) ? " and a.state=$state" : "";
    	$sql .= is_array($not_in) && !empty($not_in) ? " and a.id not in(".implode(',', $not_in).")" : "";
    	$sql .= $id > 0 ? " and (a.id in (select group_id from {$this->tableToSupplier()} where supplier_id=$id))" : '';
    	$sql .= " order by a.state, a.title";
    	return Yii::$app->db->createCommand($sql)->queryAll();
    	/*/
    	$query = (new Query())
    	->select(['a.*','count_local'=>(new Query())->select(['count(1)'])->from(self::tableToLocal())->where('group_id=a.id')])
    	->from(['a'=>self::tableName()])->where(['>','a.state',-2])->andWhere(['a.sid'=>__SID__]);
    	
    	if(is_numeric($state)){
    		$query->andWhere(['a.state'=>$state]);
    	}
    	if(is_array($not_in) && !empty($not_in)){
    		$query->andWhere(['not in','a.id',$not_in]);
    	}
    	if($supplier_id>0){
    		$query->andWhere(['or',['a.supplier_id'=>$supplier_id],
    				['a.is_locked'=>1]]);
    		$query->andWhere(['not in','a.id',(new Query())->select('group_id')->from(self::tableToSupplier())->where(['supplier_id'=>$supplier_id])]);
    	}
    	//view($query->createCommand()->getRawSql());
    	return $query->orderBy(['a.title'=>SORT_ASC])->all();
    	//
    }
    public function getListByID($id = []){
    	$sql = "select a.* from {$this->tableName()} as a where a.state>-2 and a.id in(".implode(',', $id).") order by a.title";
    	return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public static function getList($o = []){
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.title'=>SORT_ASC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);    
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';    	
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : -1;
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : -1;
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
    		//$query->andWhere(['a.type_id'=>$type_id]);
    	}
    	if(is_numeric($parent_id) && $parent_id > -1){
    		//$query->andWhere(['a.parent_id'=>$parent_id]);
    	}
    	if(is_numeric($supplier_id) && $supplier_id > -1){
    		$query->andWhere(['a.id'=>(new Query())->select(['group_id'])->from(self::tableToSupplier())->where(['supplier_id'=>$supplier_id])]);
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
    	$query->select(['a.*','count_local'=>"(select count(1) from ".self::tableToLocal()." where group_id=a.id)"])
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
    public static function get_supplier_group($id = 0,$default = false){
    	$sql = "select a.*,(select count(1) from ".self::tableToLocal()." where group_id=a.id) as count_local 
    	from ".self::tableName()." as a";
    	// Join bảng (tùy chọn)
    	$sql .= " where  a.state>-2";
    	$sql .= " and (a.id in (select group_id from ".self::tableToSupplier()." where supplier_id=$id))";
    	$sql .= " order by a.state, a.title";
    	$l = Yii::$app->db->createCommand($sql)->queryAll();
    	if($default && empty($l)){
    		$l = [
    			['id'=>0,'title'=>'Mặc định']	
    		];
    	}
    	return $l;
    	$query = (new Query())->from()->where();
    }
}
