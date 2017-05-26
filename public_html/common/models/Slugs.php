<?php
namespace common\models;
use Yii;
use yii\db\Query;
class Slugs extends \yii\db\ActiveRecord
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
        return '{{%slugs}}';
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
      
    
    public static function getAll(){
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	->where(['a.sid'=>__SID__])
    	->andWhere(['>','a.state',-2]);
    	return $query->asArray()->all();
    }
     
    public static function getItem($url = '', $item_id = 0,$item_type = 0){    	
    	$query = (new Query())
    	//->select(['route'])
    	->from(self::tableName())    	
    	->where(['sid'=>__SID__]);
    	if($url != '' ){
    		$query->andWhere(['url'=>$url]);
    	}else{
    		if($item_type == -1){
    			$item_type = defined('__IS_DETAIL__') && __IS_DETAIL__ ? 1 : 0;
    		}
    		$query->andWhere(['item_id'=>$item_id, 'item_type'=>$item_type]);
    	}
    	 
    	return $query->one();
    }
    
    public static function getRoute($url = '', $item_id = 0,$item_type = 0){    	
    	$query = (new Query())
    	->select(['route'])
    	->from(self::tableName())    	
    	->where(['sid'=>__SID__]);
    	if($url != '' ){
    		$query->andWhere(['url'=>$url]);
    	}else{
    		if($item_type == -1){
    			$item_type = defined('__IS_DETAIL__') && __IS_DETAIL__ ? 1 : 0;
    		}
    		$query->andWhere(['item_id'=>$item_id, 'item_type'=>$item_type]);
    	}
    	 
    	return $query->scalar();
    }
    /*
     * 
     */
    public static function getAllParent($id = 0){
    	$item = (new Query())->from(['site_menu'])->where(['id'=>$id])->one();
    	if(!empty($item)){
    	return static::find()->from(['site_menu'])->select(['*'])->where([
    			'<=','lft',$item['lft']
    	])->andWhere([
    			'>=','rgt',$item['rgt']
    	])->andWhere(['sid'=>__SID__])->orderBy(['lft'=>SORT_ASC])->asArray()->all();
    	}
    	return false;
    }
     
}
