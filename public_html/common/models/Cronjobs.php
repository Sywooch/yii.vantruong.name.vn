<?php
namespace common\models;
use Yii;
use yii\db\Query;
class Cronjobs extends \yii\db\ActiveRecord
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
        return '{{%cronjobs}}';
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
    
}
