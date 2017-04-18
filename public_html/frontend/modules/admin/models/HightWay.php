<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class HightWay extends Distances
{
	public static function getBooleanFields(){
		return [
				'is_active',	
		];
	}
    /**
     * @inheritdoc
     */
     
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

    public function update_price($id = 0,$type = CONTROLLER_CODE){
    	if($id>0){
    		$prices = post('prices',[]); 
    		$currency = post('currency',[]);
    		Yii::$app->db->createCommand()->delete($this->table_to_prices(),array('distance_id'=>$id,'parent_id'=>0))->execute();
    		if(!empty($prices)){
    			foreach ($prices as $k=>$v){
    				if(!empty($v)){
    					foreach ($v as $k1=>$v1){
    						Yii::$app->db->createCommand()->insert($this->table_to_prices(),[
    								'distance_id'=>$id,
    								'parent_id'=>0,
    								'vehicle_id'=>$k1,
    								'price1'=>cprice($v1['price1']),
    								'currency'=>$currency[$k]
    								
    						])->execute();
    					}
    				}
    			}
    		}
    		 
    	} 
    }
    public function getID(){
    	return (new Query())->select('max(id) +1')->from(self::tableName())->scalar();
    }     
    public function get_list_cars_by_seats($id=0){
    	$m = load_model('cars');
    	return $m->get_list_cars_by_seats($id);
    }
    public function get_list_seats(){
    	$m = load_model('vehicles_categorys');
    	return $m->get_list_seats();
    }
    public function get_hight_way_prices($distance_id = 0, $seat = 0,$o=array()){    	 
    	return (new Query())->from(['a'=>$this->table_to_prices()])
    	->where(['a.distance_id'=>$distance_id,
    			'a.vehicle_id'=>$seat,
    			'a.parent_id'=>0])
    	->one();
    }
    public function get_existed_place($id = 0, $type = CONTROLLER_CODE){
    	return (new Query())->from(['a'=>$this->table_place()])
    	->where(['a.sid'=>__SID__])
    	->andWhere(['in','a.id',(new Query())->select(['place_id'])
    			->from($this->table_to_place())->where([
    					'distance_id'=>$id,
    					'type_id'=>$type
    			])])
    	->all();
    }
    
    /*
     * 
     */
     
}
