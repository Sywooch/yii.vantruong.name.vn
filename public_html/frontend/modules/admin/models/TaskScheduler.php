<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class TaskScheduler extends \yii\db\ActiveRecord
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
        return '{{%task_scheduler}}';
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
    
    public static function deleteScheduler($code,$item_id,$sid = __SID__){
    	Yii::$app->db->createCommand()->delete(self::tableName(),[
    			'code'=>$code,
    			'item_id'=>$item_id,
    			'sid'=>$sid
    	])->execute();
    }
    public static function updateScheduler($o = []){
    	$code = $o['code'];
    	$item_id = isset($o['item_id']) ? $o['item_id'] : 0;
    	$state = isset($o['state']) ? $o['state'] : -1;
    	$time = isset($o['time']) ? $o['time'] : date("Y-m-d H:i:s");
    	$bizrule = isset($o['bizrule']) ? $o['bizrule'] : [];
    	$sid = isset($o['sid']) ? $o['sid'] : __SID__;
    	//Yii::$app->db->createCommand()->delete(self::tableName(),['sid'=>$sid,'code'=>$code,'item_id'=>$item_id,])->execute();
    	// 
    	 
    	if(check_date_string($time)){   
    		Yii::$app->db->createCommand()->delete(self::tableName(),[
    				'sid'=>$sid,'code'=>$code,
    				'item_id'=>$item_id])->execute();
    	if((new Query())->from(['a'=>self::tableName()])->where(['sid'=>$sid,'code'=>$code,'item_id'=>$item_id,'time'=>$time])->count(1) == 0){    		
    		 
    		$a = Yii::$app->db->createCommand()->insert(self::tableName(),[
    				'sid'=>$sid,'code'=>$code,
    				'item_id'=>$item_id,
    				'state'=>$state,
    				'time'=>$time,
    				//'bizrule'=>jsonify($bizrule)
    		]+(!empty($bizrule) ? ['bizrule'=>jsonify($bizrule)] : []))->execute();
    		 
    	}else{
    		return Yii::$app->db->createCommand()->update(self::tableName(),[    				
    				'state'=>$state,    				    	
    		]+(!empty($bizrule) ? ['bizrule'=>jsonify($bizrule)] : []),['sid'=>$sid,'code'=>$code,'item_id'=>$item_id,'time'=>$time])->execute();
    	}
    	}
    }

    public function getID(){
    	$sql = "select max(id) +1 from ".self::tableName();
    	return Yii::$app->db->createCommand($sql)->queryScalar();
    }     
     
    public static function getAll($o = [],$r=[]){
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : 0;
    	$level = isset($o['level']) ? $o['level'] : -1;
    	$query = new Query();
    	$query->from(['a'=>self::tableName()])    	
    	->where(['a.parent_id'=>$parent_id])
    	->orderBy(['a.name'=>SORT_ASC]);
    	if($level>-1){
    		$query->andWhere(['<','a.level',$level]);
    	}
    	//if($level>1) view($query->createCommand()->getSql(),true);
    	$l = $query->all();
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			$r[] = $v;
    			$o['parent_id'] = $v['id'];
    			$r = self::getAll($o,$r);
    		}
    	}
    	return $r;
    }
    public function update_lft($id = 0,$r = array()){
    	global $lftxx; 
    
    	$sql = "select a.parent_id, a.id,a.lft,a.rgt from ".self::tableName()." as a where a.parent_id=$id";
    	$sql .= " order by a.name";
    	
    	$l = Yii::$app->db->createCommand($sql)->queryAll();
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    
    			$v['lft_c'] = $lftxx;
    			$v['childs'] = $this->count_all_child($v['id']);
    			//view($v['childs']);
    			if($v['childs'] > 0){
    				$rgt = $v['childs'] * 2 +1 +$lftxx;
    
    			}else{
    				$rgt = ++$lftxx;
    				if($k == count($l)-1) ++$lftxx;
    			}
    			$lftxx++;
    
    			if($v['parent_id']>0){
    				$level = Yii::$app->db->createCommand("select a.level from `local` as a where a.id=".$v['parent_id'])->queryScalar();
    				$sql = "update `".self::tableName() ."` as a set a.level=$level+1 where a.id=".$v['id'];
    				Yii::$app->db->createCommand($sql)->execute();
    			}
    
    			$v['rgt_c'] = $rgt;
    			$r[] = $v;
    			Yii::$app->db->createCommand()->update(self::tableName(),[
    					'lft'=>$v['lft_c'],
    					'rgt'=>$v['rgt_c']
    			],array('id'=>$v['id']))->execute();
    			//view($lftxx);
    			$r = $this->update_lft($v['id'],$r);
    		}
    	}
    	return $r;
    }
    public function count_all_child($id = 0,$c = 0){
    	$m = Yii::$app->db->createCommand("select a.id,a.parent_id from ".self::tableName()." as a where a.parent_id=$id" )->queryAll();
    	$c += count($m);
    	if(!empty($m)){
    		foreach ($m as $k=>$v){
    			$c = $this->count_all_child($v['id'],$c);
    		}
    	}
    	return $c;
    }
    
    public static function getTodayTask(){
    	return static::find()->where(['state'=>-1, 'sid'=>__SID__])
    	->andWhere(['<','unix_timestamp(time)',time()])
    	->asArray()->all();
    }
    public static function update_exchange_rates(){
    	$d = get_exchangerates();
    	 
    	if(isset($d->Exrate)){    		
    		$g_currency = Yii::$app->zii->getCurrency();    	
    		//view($d->DateTime);
    		$time_update = date("Y-m-d H:i:s",strtotime($d->DateTime));
    		if((new \yii\db\Query())->from('exchange_rate')->where(array('from_date'=>$time_update))->count(1) == 0){    			 
    			foreach ($d->Exrate as $v){
    				//view($v);
    				if((new \yii\db\Query())->from('currency')->where(['code'=>$v['CurrencyCode'].''])->count(1) == 0){
    					$id = Yii::$app->zii->insert('currency',array(
    							'code'=>$v['CurrencyCode'],
    							'symbol'=>$v['CurrencyCode'],
    							'name'=>$v['CurrencyName'],
    							'title'=>$v['CurrencyName'],
    					));
    
    				}else{
    					//Zii::$db->update('currency',array('name'=>$v['CurrencyName']),array('code'=>$v['CurrencyCode']));
    				}
    				// view($v['Sell']);
    				// Cập nhật tỷ giá
    				$item = Yii::$app->zii->getCurrencyByCode($v['CurrencyCode']);      				 
    				if(!empty($item)){
    					$ex  = Yii::$app->zii->get_exrate(array(
    							'from' => $item['id'],
    							'to'=>$g_currency['id'],
    							'return'=>'last'
    					));
    					
    					//view($ex);
    					
    					if(!empty($ex) && $ex['value'] == $v['Sell']){
    						// Khi tỷ giá không tăng
    					}else{
    						// Khi tỷ giá biến động
    						Yii::$app->db->createCommand()->insert('exchange_rate',array(
    								'from_currency'=>$item['id'],
    								'value'=>$v['Sell'],
    								'from_date'=>$time_update,
    								'to_currency'=>$g_currency['id']))->execute(); 
    					}
    				}
    			}
    		}}
    }
    
    /*
     * 
     */
    public static function getList($o = []){
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.lft'=>SORT_ASC,'a.name'=>SORT_ASC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);    
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';    	
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : -1;
    	$level = isset($o['level']) ? $o['level'] : -1;
    	$offset = ($p-1) * $limit;
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	//->where(['a.sid'=>__SID__])
    	//->andWhere(['>','a.state',-2])
    	;
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['like', 'name', $filter_text]);
    	}
    	if(is_numeric($type_id) && $type_id > -1){
    		$query->andWhere(['a.type_id'=>$type_id]);
    	}
    	if(is_numeric($parent_id) && $parent_id > -1){
    		$query->andWhere(['a.parent_id'=>$parent_id]);
    	}
    	if($level>-1){
    		$query->andWhere(['<','a.level',$level]);
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
