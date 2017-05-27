<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class Siteconfigs extends \yii\db\ActiveRecord
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
	private static $key = false;
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
    public static function getAdminVersion(){
    	return array(
    			'v1'=>'Version 1.0',
    			'v2'=>'Version 2.0',
    			//array('id'=>'direct','title'=>'Upload'),
    			//array('id'=>3,'name'=>'Tour khởi hành theo yêu cầu'),
    			//array('id'=>4,'name'=>'Tour khác'),
    	);
    }
    public static function getList($key = CONTROLLER_CODE){
    	//view(CONTROLLER_CODE);
    	$v = self::getItem('SITE_CONFIGS');
    	 
    	if(!empty($v) && isset($v[$key])) return $v[$key];
    }
    
    public static function getItem($key,$lang=false){	 
    	return static::find()->where(['code'=>$key,'sid'=>__SID__]+($lang !== false ? ['lang'=>$lang] :[]))->asArray()->one();
    }
    
    public static function updateData($key,$data,$lang = false){
    	 if(is_array($data)) $data = json_encode($data,JSON_UNESCAPED_UNICODE);
    	if((new Query())->from(self::tableName())->where(['code'=>$key,'sid'=>__SID__]+($lang !== false ? ['lang'=>$lang] :[]))->count(1) == 0){
    		$a = Yii::$app->db->createCommand()->insert(self::tableName(),['bizrule'=>$data,'sid'=>__SID__,'code'=>$key]+($lang !== false ? ['lang'=>$lang] :[]))->execute();
    	}else {
    		$a = Yii::$app->db->createCommand()->update(self::tableName(),['bizrule'=>$data],['sid'=>__SID__,'code'=>$key]+($lang !== false ? ['lang'=>$lang] :[]))->execute();
    	}
    	return $a;    	     	
    }
    public static function getSiteConfigs($key){
    	$b = (new Query())->from(['a'=>self::tableName()])->where(['a.code'=>'SITE_CONFIGS','a.sid'=>__SID__,'a.lang'=>__LANG__])->one();
    	return isset($b[$key]) ? $b[$key] : [];
    }
    public static function updateSiteConfigs($key,$value){
    	$b = (new Query())->from(['a'=>self::tableName()])->where(['a.code'=>'SITE_CONFIGS','a.sid'=>__SID__,'a.lang'=>__LANG__])->one();    	
    	$crm = explode('/', $key);
    	//view($value);
    	//view($b,true); 
    	switch (count($crm)){
    		case 2:
    			$b[$crm[0]][$crm[1]] = $value;
    			break;
    		case 3: 
    			$b[$crm[0]][$crm[1]][$crm[2]] = $value;
    			break;
    		default:
    			$b[$key] = $value;
    			break;
    	}    	
    	//view($b,true);
    	$biz = cjson($b);  
    	//view($biz,true);
    	if((new Query())->from(['a'=>self::tableName()])->where([
    			'a.code'=>'SITE_CONFIGS',
    			'a.sid'=>__SID__,
    			'a.lang'=>__LANG__    			
    	])->count(1) > 0){    		
    		$a = Yii::$app->db->createCommand()->update(self::tableName(),['bizrule'=>$biz],['code'=>'SITE_CONFIGS','sid'=>__SID__,'lang'=>__LANG__])->execute();
    		 
    	}else{
    		Yii::$app->db->createCommand()->insert(self::tableName(),['bizrule'=>$biz,'code'=>'SITE_CONFIGS','sid'=>__SID__,'lang'=>__LANG__])->execute();
    	}
    	 
    }
    public static function updateBizrule($table = '',$con = [], $biz = []){
    	 
    	$b = (new Query())->select('bizrule')->from(['a'=>$table])->where($con)->one();
    	if(is_array($biz)){
    		if(!empty($biz)){
    			foreach ($biz as $k=>$v){
    				$b[$k] = $v;
    			}
    		}
    		if((new Query())->from($table)->where($con)->count(1) == 0){
    			$con['bizrule']=json_encode($b);
    			return Yii::$app->db->createCommand()->insert($table,$con)->execute();
    		}
    		return Yii::$app->db->createCommand()->update($table,['bizrule'=>json_encode($b)],$con)->execute();
    	}    	   
    }
 
}
