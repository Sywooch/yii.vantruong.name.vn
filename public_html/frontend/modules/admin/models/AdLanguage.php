<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class AdLanguage extends \yii\db\ActiveRecord
{
	public static function getBooleanFields(){
		return [
				//'is_active',
				//'root_active',
				//'default'
		];
	}
    /**
     * @inheritdoc
     */
	public static $key = 'LANGUAGE';
    public static function tableName()
    {
        return '{{%site_configs}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
        ];
    }

    public static function getDefaultLang(){
    	return array(
    			array('id'=>1,'code'=>'vi_VN','title'=>'Tiếng Việt','cname'=>'Vietnamese','default'=>1,'is_active'=>1,'root_active'=>1),
    			array('id'=>2,'code'=>'en_US','title'=>'Tiếng Anh','cname'=>'English','default'=>0,'is_active'=>1,'root_active'=>1),
    			array('id'=>3,'code'=>'th_TH','title'=>'Tiếng Thái','cname'=>'Thai','default'=>0,'is_active'=>0,'root_active'=>0),
    			array('id'=>4,'code'=>'la_LA','title'=>'Tiếng Lào','cname'=>'Lao','default'=>0,'is_active'=>0,'root_active'=>0),
    			array('id'=>5,'code'=>'id_ID','title'=>'Tiếng Indonesia','cname'=>'Vietnamese','default'=>0,'is_active'=>0,'root_active'=>0),
    	);
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            
        ];
    }

    public function getID(){
    	$sql = "select max(id) +1 from ".self::tableName();
    	return Yii::$app->db->createCommand($sql)->queryScalar();
    }     
     
    public static function getItem($id=0,$o=[]){    	
    	$item = static::find()
    	->where(['id'=>$id, 'sid'=>__SID__]);
    
    	$item = $item->asArray()->one();
    	 
    	return $item;
    }
    /*
     * 
     */
    public static function getUserDefaultLanguage(){
    	$l = self::getList();
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			if(isset($v['default']) && $v['default'] == 1){
    				return $v;
    			}
    		}
    	}
    	return [];
    }
    
    public static function getLanguage($code = DEFAULT_LANG){
     
    	$l = self::getList();
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			if(isset($v['code']) && $v['code'] == $code){
    				return $v;
    			}
    		}
    	}
    	return [];
    }
    
    public static function getList($o = []){
    	$sql = "select a.bizrule from ".self::tableName()." as a where a.code='".self::$key."'";
    	$sql .= " and a.sid=".__SID__;
    	$r = djson(Yii::$app->db->createCommand($sql)->queryScalar());
    	if(empty($r)) $r = self::getDefaultLang();
    	if(isset($o['is_active']) && !empty($r)){
    		foreach ($r as $k=>$v){
    			if(isset($v['is_active']) && $v['is_active'] == $o['is_active']){}else{unset($r[$k]);}
    		}
    	}
    	 
    	return $r;
    }
}
