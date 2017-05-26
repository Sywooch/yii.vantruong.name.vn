<?php
namespace app\models;
use Yii;
use yii\db\Query;
class Articles extends \yii\db\ActiveRecord
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
        return '{{%articles}}';
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
    public static function getBoxIndex($module = 'index'){    	
    	return static::find()
    	->where(['module'=>$module,'lang'=>__LANG__,'is_active'=>1,'sid'=>__SID__])
    	->andWhere(['>','state',-2])
    	->orderBy(['position'=>SORT_ASC])
    	->asArray()->all();
    }
    public static function getID(){
    	return (new Query())->select('max(id) +1')->from(self::tableName())->scalar();
    }     
    
    public static function getBoxCode($code,$id = 0){
    	if($id == 0) $id = self::getID();
    	if($code == '') $code = 'ub';
    	while((new Query())->from(self::tableName())
    			->where(['code'=>$code,'sid'=>__SID__,'lang'=>__LANG__])
    			->andWhere(['>','state',-2])
    			->andWhere(['not in','id',$id])
    			->count(1) > 0
    			){
    		$code .= '-'.$id;
    	}
    	return $code;
    }
    
    public static function getAll(){
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	->where(['a.sid'=>__SID__])
    	->andWhere(['>','a.state',-2]);
    	return $query->asArray()->all();
    }
     
    public static function getItem($id=0,$o=[]){    	
    	$item = static::find()
    	->where(['id'=>$id, 'sid'=>__SID__]);
    
    	$item = $item->asArray()->one();
    	 
    	return $item;
    }
    
    public static function getBox($code=false,$lang = __LANG__){    	
    	$query = (new Query())->from(self::tableName())
    	->where(['>','state',-2])
    	->andWhere(['is_active'=>1,'sid'=>__SID__]);
    	if(is_numeric($code)){
    		$query->andWhere(['id'=>$code]);
    	}else{
    		$query->andWhere(['code'=>$code]);
    	}
    	if($lang !== false){
    		$query->andWhere(['lang'=>$lang]);
    	}
    	return $query->one();
    }
    /*
     * 
     */
     
}
