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

	public static function getTempleteName($cached =  true){
		$config = Yii::$app->session->get('config');	
		$c = __SID__ .'_'. PRIVATE_TEMPLETE;
		
		if(isset($config['templete'][$c][__LANG__]['name']) && $config['templete'][$c][__LANG__]['name'] != ""){	
			return $config['templete'][$c][__LANG__];
		}else{		
			$r = [];
			if(PRIVATE_TEMPLETE>0){
				$r = static::find()
				->select(['a.*'])
				->from(['a'=>'{{%templetes}}'])				 
				->where(['a.id'=>PRIVATE_TEMPLETE])->asArray()->one();
				
			}
			if(empty($r)){			
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
			}
			$config['templete'][$c][__LANG__] = $r;	
			Yii::$app->session->set('config', $config);
			return $r;
		}
	}
}