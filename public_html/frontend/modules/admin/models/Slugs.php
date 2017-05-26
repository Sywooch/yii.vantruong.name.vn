<?php

namespace app\modules\admin\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "templete_category".
 *
 * @property integer $id
 * @property string $title
 * @property integer $state
 * @property integer $is_active
 */
class Slugs extends \yii\db\ActiveRecord
{
	public static function tableName(){
		return '{{%slugs}}';
	}
	public static function setBooleanFields($fields){
		self::$booleanFields = $fields;
	}
	public static function setNumericFields($fields){
		self::$numericFields = $fields;
	}
	public static function getContentItem($url = '',$o=[]){
		//
		$v = self::getItem($url,$o);
		if(!empty($v)){
			//
			$item = static::find()
			->select(['a.*','b.item_type'])
			->from(['a'=>$v['item_type'] == 1 ? Content::tableName() : Menu::tableName()])
			->innerJoin(['b'=>self::tableName()],'a.id=b.item_id')
			->where(['b.url'=>$url,'a.sid'=>__SID__ ,'b.sid'=>__SID__]);
			
			$item = $item->asArray()->one();
			
			
			return $item;
		}
		
	}
	public static function getItem($url = '',$o=[]){
		$item = static::find()
		->where(['url'=>$url, 'sid'=>__SID__]);
	
		$item = $item->asArray()->one();
		
		
		return $item;
	}
	 
	public static function getSlug($url, $item_id = 0){
		while((new Query())->from(self::tableName())->where(['url'=>$url,'sid'=>__SID__])->andWhere(['not in','item_id',$item_id])->count(1) >0){
			$url .= '-' . ($item_id > 0 ? $item_id : rand(1,999));
		}
		return $url;
	}
    public static function updateSlug($url,$item_id,$route='',$item_type=0,$item = []){
    	$rel = isset($item['rel']) ? $item['rel'] : '';
    	if($item_id>0){
    		if((new Query())->from(self::tableName())->where(['sid'=>__SID__,'item_id'=>$item_id,'item_type'=>$item_type])->count(1) == 0){
    			return Yii::$app->db->createCommand()->insert(self::tableName(),[
    					'url'=>$url,    			
    					'item_id'=>$item_id,
    					'item_type'=>$item_type,
    					'route'=>$route,
    					'rel'=>$rel,
    					'sid'=>__SID__]
    			)->execute();
    		}else{
    			return Yii::$app->db->createCommand()->update(self::tableName(),[
    					'url'=>$url,'route'=>$route,'rel'=>$rel,
    			],['item_id'=>$item_id,'item_type'=>$item_type,'sid'=>__SID__])->execute();
    		}
    	}
    }
}
