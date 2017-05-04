<?php
namespace common\models;
use Yii;
use yii\db\Query;
class Suspended extends \yii\db\ActiveRecord
{
	public static function getBooleanFields(){
		return [
				// 'is_active',	
		];
	}
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%suspended}}';
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
    
    public static function checkSuspended($id = __SID__, $type_id = 1){
    	if((new Query())->from(self::tableName())->where(['id'=>$id,'type_id'=>$type_id])->count(1) == 0){
    		return false;
    	}
    	return true;
    }
    public static function removeSuspended($id = __SID__, $type_id = 1){
    	return Yii::$app->db->createCommand()->delete(self::tableName(),['id'=>$id,'type_id'=>$type_id])->execute();
    }
    public static function unSuspended($id = __SID__, $type_id = 1){
    	return self::removeSuspended($id,$type_id);
    }
    public static function addSuspended($id = __SID__, $type_id = 1){
    	if((new Query())->from(self::tableName())->where(['id'=>$id,'type_id'=>$type_id])->count(1) == 0){
    		Yii::$app->db->createCommand()->insert(self::tableName(),['id'=>$id,'type_id'=>$type_id])->execute();
    	}    	
    }
      
    public static function getItem($id=0,$o=[]){    	
    	$item = static::find()
    	->where(['id'=>$id, 'sid'=>__SID__])
    	->andWhere(['>', 'state',-2]);
    	$item = $item->asArray()->one();
    	return $item;
    }
     
}
