<?php
namespace app\izi;

class Slug extends \yii\db\ActiveRecord
{
	public static function tableName(){
		return '{{%slugs}}';
	}
	
	public static function findByUrl($url = ''){
		return static::find()->where(['url'=>$url,'sid'=>__SID__])->asArray()->one();
	}
	public static function adminFindByUrl($url = ''){
		return static::find()
		->from('{{%admin_menu}}')
		->where(['url'=>$url,'lang'=>ADMIN_LANG])->asArray()->one();
	}
}
