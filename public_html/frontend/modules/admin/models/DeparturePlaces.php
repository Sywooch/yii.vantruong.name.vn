<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class DeparturePlaces extends \yii\db\ActiveRecord
{
	public static function getBooleanFields(){
		return [
				'is_active','is_start',
				'is_default','is_start1','is_destination','is_destination1'
		];
	}
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%departure_places}}';
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
    	$sql = "select max(id) +1 from ".self::tableName();
    	return Yii::$app->db->createCommand($sql)->queryScalar();
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
    public function getListDestination($id = 0, $type = 0){
    	$table = $type > 0 ? '{{%departure_to_destination_type}}' : '{{%departure_to_destination}}';
    	$sql = "select destination_id from $table where 1";
    	$sql .= $id > 0 ? " and departure_id=$id" : '';
    	$sql .= $type > 0 ? " and type_id=$type" : '';
    	$l = Yii::$app->db->createCommand($sql)->queryAll();
    	$rs = array();
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			$rs[] = $v['destination_id'];
    		}
    	}
    	return $rs;
    }
    public function getListDeparture($id = 0,$type = 0){
    	$table = $type > 0 ? '{{%departure_to_destination_type}}' : '{{%departure_to_destination}}';
    	$sql = "select departure_id from $table where 1";
    	$sql .= $id > 0 ? " and destination_id=$id" : '';
    	$sql .= $type > 0 ? " and type_id=$type" : '';
    	$l = Yii::$app->db->createCommand($sql)->queryAll();
    	$rs = array();
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			$rs[] = $v['departure_id'];
    		}
    	}
    	return $rs;
    }
    public static function getType(){
    	return array(
		     		array('id'=>1,'name'=>'Du lịch trong nước'),
		     		array('id'=>2,'name'=>'Du lịch nước ngoài'),
		     );
    }
    public function getListDestinationByType($type_id = 0, $destination_id = 0){
    	$table =  '{{%departure_to_destination_type}}';
    	$sql = "select departure_id from $table where 1";
    	$sql .= $type_id > 0 ? " and type_id=$type_id" : '';
    	$sql .= $destination_id > 0 ? " and destination_id=$destination_id" : '';
    	$l = Yii::$app->db->createCommand($sql)->queryAll();
    	$rs = array();
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			$rs[] = $v['departure_id'];
    		}
    	}
    	return $rs;
    }
    public static function updateDestination($id = 0){
    	$fDestination = isset($_POST['fDestination']) ? $_POST['fDestination'] : array();    	 
    	Yii::$app->db->createCommand()->delete('{{%departure_to_destination}}',['departure_id'=>$id])->execute();
    	if(!empty($fDestination)){
    		foreach ($fDestination as $v){
    			Yii::$app->db->createCommand()->insert('{{%departure_to_destination}}',['destination_id'=>$v,'departure_id'=>$id])->execute();
    		}
    	}
    	/// 
    	$fDestination = isset($_POST['fDeparture']) ? $_POST['fDeparture'] : array();
    	
    	Yii::$app->db->createCommand()->delete('{{%departure_to_destination}}',['destination_id'=>$id])->execute();
    	if(!empty($fDestination)){
    		foreach ($fDestination as $v){
    			Yii::$app->db->createCommand()->insert('{{%departure_to_destination}}',['departure_id'=>$v,'destination_id'=>$id])->execute();
    		}
    	}
    }
    public static function getList($o = []){
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.name'=>SORT_ASC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);    
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';    	
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : -1;
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$is_start = isset($o['is_start']) ? $o['is_start'] : -1;
    	$is_destination = isset($o['is_destination']) ? $o['is_destination'] : -1;
    	$in = isset($o['in']) ? $o['in'] : [];
    	$not_in = isset($o['not_in']) ? $o['not_in'] : [];
    	if(!is_array($in) && $in != "") $in = explode(',', $in);
    	if(!is_array($not_in) && $not_in != "") $not_in = explode(',', $not_in);
    	$offset = ($p-1) * $limit;
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	->where(['a.sid'=>__SID__])
    	->andWhere(['>','a.state',-2]);
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['like', 'name', $filter_text]);
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
    	if(is_numeric($is_start) && $is_start > -1){
    		$query->andWhere(['a.is_start'=>$is_start]);
    	}
    	if(is_numeric($is_destination) && $is_destination > -1){
    		$query->andWhere(['a.is_destination'=>$is_destination]);
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
}
