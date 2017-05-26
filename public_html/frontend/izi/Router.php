<?php
/**
 * 
 * @link http://iziweb.vn
 * @copyright Copyright (c) 2016 iziWeb
 * @email zinzinx8@gmail.com
 *
 */
namespace app\izi;
use Yii;
class Router extends \yii\db\ActiveRecord
{
	public static function tableName(){
	 	return '{{%slugs}}';
	}
		
	public static function getShopFromDomain($domain = __DOMAIN__){
		return static::find()
		->select(['a.sid','b.status','b.code','a.is_admin','a.module','b.to_date'])
		->from(['a'=>'{{%domain_pointer}}'])
		->innerJoin(['b'=>'{{%shops}}'],'a.sid=b.id')
		->where(['a.domain'=>__DOMAIN__])->asArray()->one();		
	}

	public static function getTempleteName(){
		$config = Yii::$app->session->get('config');	
		if(isset($config['templete'][__SID__][__LANG__]['name']) && $config['templete'][__SID__][__LANG__]['name'] != ""){	
			return $config['templete'][__SID__][__LANG__];
		}else{			 			
			$r = static::find()
			->select(['a.*'])
			->from(['a'=>'{{%templetes}}'])
			->innerJoin(['b'=>'{{%temp_to_shop}}'],'a.id=b.temp_id')
			->where(['b.state'=>1,'b.sid'=>__SID__,'b.lang'=>__LANG__])->asArray()->one();						 
			if(empty($r)){
				$r = static::find()
				->select(['a.*'])
				->from(['a'=>'{{%templetes}}'])
				->innerJoin(['b'=>'{{%temp_to_shop}}'],'a.id=b.temp_id')
				->where(['b.state'=>1,'b.sid'=>__SID__])->asArray()->one();
			}
			$config['templete'][__SID__][__LANG__] = $r;	
			Yii::$app->session->set('config', $config);
			return $r;
		}
	}
}