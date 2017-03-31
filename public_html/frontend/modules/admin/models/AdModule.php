<?php

namespace app\modules\admin\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "admin_menu".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $route
 * @property string $child_code
 * @property integer $position
 * @property integer $is_active
 * @property integer $type
 * @property integer $level
 * @property integer $is_fix
 * @property string $lang
 * @property string $url
 * @property string $check_sum
 * @property string $bizrule
 * @property integer $state
 * @property string $title
 * @property integer $is_all
 * @property integer $is_home
 * @property integer $is_permission
 * @property integer $is_invisibled
 * @property integer $lft
 * @property integer $rgt
 */
class AdModule extends \yii\db\ActiveRecord
{
	public static function getBooleanFields(){
		return [
				'is_active',
				'is_fix',
				'is_all',
				'is_permission',
				'is_home',
				//'is_desktop',
				'is_invisibled',
				//'is_active',
	
		];
	}
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_menu}}';
    }
    public static function tableToModules()
    {
        return '{{%temp_to_modules}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'lang'], 'required'],
            [['id', 'parent_id', 'position', 'is_active', 'type', 'level', 'is_fix', 'state', 'is_all', 'is_home', 'is_permission', 'is_invisibled', 'lft', 'rgt'], 'integer'],
            [['bizrule'], 'string'],
            [['route', 'child_code'], 'string', 'max' => 64],
            [['lang'], 'string', 'max' => 6],
            [['url', 'title'], 'string', 'max' => 255],
            [['check_sum'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'route' => 'Route',
            'child_code' => 'Child Code',
            'position' => 'Position',
            'is_active' => 'Is Active',
            'type' => 'Type',
            'level' => 'Level',
            'is_fix' => 'Is Fix',
            'lang' => 'Lang',
            'url' => 'Url',
            'check_sum' => 'Check Sum',
            'bizrule' => 'Bizrule',
            'state' => 'State',
            'title' => 'Title',
            'is_all' => 'Is All',
            'is_home' => 'Is Home',
            'is_permission' => 'Is Permission',
            'is_invisibled' => 'Is Invisibled',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
        ];
    }

    public function getID(){
    	$sql = "select max(id) +1 from ".self::tableName();
    	return Yii::$app->db->createCommand($sql)->queryScalar();
    }
    
    
    public static function getUrl($url, $id=0,$lang=ADMIN_LANG){
    	if($id > 0){}else{
    		$sql = "select max(id)+1 from ".self::tableName();
    		$id = Yii::$app->db->createCommand($sql)->queryScalar();
    	}
    	$id = $id > 0 ? $id : 0;
    	$sql = "select count(1) from ".self::tableName(). " as a where a.url = '$url' and a.id not in($id) and a.state>0 and a.lang='".$lang."'";
    	while (Yii::$app->db->createCommand($sql)->queryScalar() > 0){
    		$url .= '-' . $id;
    		$sql = "select count(1) from ".self::tableName(). " as a where a.url = '$url' and a.id not in($id) and a.state>0 and a.lang='".$lang."'";
    	}
    	return $url;
    }
    
    public function updateForms($id = 0){
    	$sql = "delete from temp_to_modules where module_id=$id";
    	Yii::$app->db->createCommand($sql)->execute();
    	$f = post('forms'); //view($f); exit;
    	if(!empty($f)){
    		$rows = [];
    		foreach ($f as $v){
    			$rows[] = [$v,$id];
    		}
    		Yii::$app->db->createCommand()->batchInsert('temp_to_modules',['temp_id','module_id'],$rows)->execute();
    	}
    	//
    	$f = post('forms1');
    	if(!empty($f)){
    		$rows = [];
    		foreach ($f as $v){
    			$rows[] = [$v,$id,1];
    		}
    		Yii::$app->db->createCommand()->batchInsert('temp_to_modules',['temp_id','module_id','type_id'],$rows)->execute();
    	}
    }
    public function update_lft($id = 0,$r = array()){
    	global $lftxx;
    
    	$sql = "select a.parent_id,a.id,a.title,a.lft,a.rgt from ".self::tableName()." as a where a.parent_id=$id and a.state>-2 and a.lang='".ADMIN_LANG."'";
    	$sql .= " order by a.position, a.title";
    	$l = Yii::$app->db->createCommand($sql)->queryAll();
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    
    			$v['lft_c'] = $lftxx;
    			$v['childs'] = $this->count_all_child($v['id']);
    			//view($k .'/' . $lftxx . '/' . $v['childs']);
    			if($v['childs'] > 0){
    				$rgt = $v['childs'] * 2 +1 +$lftxx;
    
    			}else{
    				$rgt = ++$lftxx;
    				if($k == count($l)-1) ++$lftxx;
    			}
    			$lftxx++;
    
    
    
    			$v['rgt_c'] = $rgt;
    			$r[] = $v;
    			Yii::$app->db->createCommand()->update(self::tableName(),[
    					'lft'=>$v['lft_c'],
    					'rgt'=>$v['rgt_c']
    			],array('id'=>$v['id']))->execute();
    			//view($lftxx);
    			$r = $this->update_lft($v['id'],$r);
    		}
    	}
    	return $r;
    }
    public function count_all_child($id = 0,$c = 0){
    	$m = Yii::$app->db->createCommand("select a.id,a.parent_id from ".self::tableName()." as a where a.parent_id=$id and a.state>-2 and a.lang='".ADMIN_LANG."'" )->queryAll();
    	$c += count($m);
    	if(!empty($m)){
    		foreach ($m as $k=>$v){
    			$c = $this->count_all_child($v['id'],$c);
    		}
    	}
    	return $c;
    }
    /**
     * @inheritdoc
     * @return AdminMenuQuery the active query used by this AR class.
     */
    
    public static function getAllParentID($id = 0){
    	$item = self::getItem($id); 
    	$l = static::find()->from(['a'=>self::tableName()])->select(['a.id','a.lft','.a.rgt'])
    	->where(['>','a.state',-2])
    	->andWhere(['<=','a.lft',$item['lft']])
    	->andWhere(['>=','a.rgt',$item['rgt']])->asArray()->all();
    	$r = [];
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			if(!in_array($v['id'], $r)) $r[] = $v['id'];
    		}
    	}
    	return $r;
    }
    
    
    public static function find()
    {
        return new AdminMenuQuery(get_called_class());
    }
    public static function getItem($id=0,$o=[]){
    
    	$item = static::find()
    	->where(['id'=>$id]);
    
    	$item = $item->asArray()->one();
    	 
    	return $item;
    }
    public static function getCItem($id=0,$lang = ADMIN_LANG){         
    	return Yii::$app->db->createCommand("select * from ".self::tableName() ." where id=$id and lang='".$lang."'")->queryAOne();
    }
    /*
     * 
     */
    public static function getListPermission($id=0,$o=[]){
    	$auth_child = isset($o['auth_child']) ? $o['auth_child'] : '';
    	$l = static::find()
    	->from(['a'=>self::tableName()])
    	->select(['a.*'])
    	->where(['lang'=>ADMIN_LANG ,'is_permission'=>1,'is_fix'=>0]);
    	//if(!Yii::$app->user->can([ROOT_USER])){
    	$l->andWhere(['>','state',-2])
    	->andWhere(['is_permission'=>1,'is_fix'=>0])
    	->andWhere(['type'=>Yii::$app->user->can(ROOT_USER) ? [0,1,2] : [0,1,2]])
    	->andWhere(['or',['is_all'=>1],['in','id',(new Query())->select('module_id')->from(self::tableToModules())->where(['temp_id' => __TCID__,'type_id'=>0])]])
    	->andWhere(['not in','route',['#','']])
    	->andWhere(['not in','id',(new Query())->select('module_id')->from(self::tableToModules())->where(['temp_id' => __TCID__,'type_id'=>1])]);
    	//}else{
    		
    	//}
    	$l = $l->orderBy(['title'=>SORT_ASC,'lft'=>SORT_ASC])->asArray()->all();    	
    	return $l;
    }
    public static function getList($o=[]){
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	->select(['a.*','count_child'=>'(select count(1) from '.self::tableName()." where lft>a.lft and rgt<a.rgt and state>-2 and lang='".ADMIN_LANG."')"])
    	->where(['lang'=>ADMIN_LANG])
    	->andWhere(['>','state',-2]);
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['like', 'a.title', $filter_text]); 
    	}
    	$l = $query->orderBy(['lft'=>SORT_ASC,'title'=>SORT_ASC])->asArray()->all();
    	return $l;
    }
    
    public static function getList1($o = []){
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['lft'=>SORT_ASC, 'position'=>SORT_ASC,'title'=>SORT_ASC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : -1;
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$type = isset($o['type']) ? $o['type'] : false;
    	$offset = ($p-1) * $limit;
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	->where(['lang'=>ADMIN_LANG])
    	->andWhere(['>','a.state',-2]);
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['like', 'title', $filter_text]);
    	}
    	if(is_numeric($type_id) && $type_id > -1){
    		$query->andWhere(['a.type_id'=>$type_id]);
    	}
    	if(is_numeric($parent_id) && $parent_id > -1){
    		$query->andWhere(['a.parent_id'=>$parent_id]);
    	}
    	if(is_numeric($is_active) && $is_active > -1){
    		$query->andWhere(['a.is_active'=>$is_active]);
    	}
    	if($type !== false){
    		$query->andWhere(['a.type'=>$type]);
    	}
    	$c = 0;
    	if($count){
    		$query->select('count(1)');
    		$c = $query->scalar();
    	}
    	$query->select(['a.*','count_child'=>'(select count(1) from '.self::tableName()." where lft>a.lft and rgt<a.rgt and state>-2 and lang='".ADMIN_LANG."' )"])
    	->orderBy($order_by)
    	->offset($offset)
    	->limit($limit);
    	$l = $query->asArray()->all();
    	//
    	return [
    			'listItem'=>$l,
    			'total_records'=>$c,
    			'total_pages'=>ceil($c/$limit),
    			'limit'=>$limit,
    			'p'=>$p,
    	];
    	 
    }
    
}
