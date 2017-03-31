<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
global $lftxx;
$lftxx =0;
class Menu extends \yii\db\ActiveRecord
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
        return '{{%site_menu}}';
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

    public function getID(){
    	$sql = "select max(id) +1 from ".self::tableName();
    	return Yii::$app->db->createCommand($sql)->queryScalar();
    }     
     
    public static function getItemToPosition($id,$type=0){
    	return Yii::$app->db->createCommand("select * from {{%items_to_posiotion}} where item_id=$id and type=$type")->queryAll();
    }
    public static function getItem($id=0,$o=[]){    	
    	$item = static::find()
    	->where(['id'=>$id, 'sid'=>__SID__,'lang'=>__LANG__]);
    
    	$item = $item->asArray()->one();
    	if(!empty($item)){
    		 
    		$pos = self::getItemToPosition($item['id'],0);
    		if(!empty($pos)){
    			foreach ($pos as $p){
    				$item[$p['position_id']] = $p['value'];
    			}
    		}
    		 
    	}
    	return $item;
    }
    public static function getAll($o=[]){
    	$type = isset($o['type']) ? $o['type'] : false;
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	->where(['a.sid'=>__SID__,'lang'=>__LANG__])
    	->orderBy(['lft'=>SORT_ASC, 'position'=>SORT_ASC,'title'=>SORT_ASC])
    	->andWhere(['>','a.state',-2]);
    	if($type !== false){
    		$query->andWhere(['a.type'=>$type]);
    	}
    	 
    	return $query->asArray()->all();
    }
    
    public static function getListPermission($id=0){
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	->where(['a.sid'=>__SID__,'is_active'=>1 ])
    	->orderBy([ 'a.title'=>SORT_ASC])
    	->andWhere(['>','a.state',-2]);
    	$l = $query->asArray()->all();
    
    	return $l;
    }
    /*
     * 
     */
    public static function getList($o = []){
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
    	->where(['a.sid'=>__SID__,'a.lang'=>__LANG__])
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
    	
    	if(!Yii::$app->user->can([ROOT_USER,ADMIN_USER])){
    		$sites = AdminMenu::getUserModulesID(1);
    		$query->andWhere(['a.id'=>$sites]);
    		//var_dump($query->createCommand()->getSql());
    	}
    	$c = 0;
    	if($count){
    		$query->select('count(1)');
    		$c = $query->scalar();
    	}
    	$query->select(['a.*','count_child'=>'(select count(1) from '.self::tableName()." where lft>a.lft and rgt<a.rgt and state>-2 and lang='".__LANG__."' and sid=a.sid)"])
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
    
    
    public static function update_lft($id = 0,$r = array() ){
    	global $lftxx;// echo  $lftxx .'<br/>';
     
    	$sql = "select a.parent_id,a.id,a.title,a.lft,a.rgt from ".self::tableName()." as a where a.sid=".__SID__." and a.parent_id=$id and a.state>-2 and a.lang='".__LANG__."'";
    	$sql .= " order by a.position, a.title";
    	$l = Yii::$app->db->createCommand($sql)->queryAll();
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    
    			$v['lft_c'] = $lftxx;
    			$v['childs'] = self::count_all_child($v['id']);
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
    			//view(Yii::$app->db->createCommand()->update(self::tableName(),[
    			//		'lft'=>$v['lft_c'],
    			//		'rgt'=>$v['rgt_c']
    			//],array('id'=>$v['id']))->getSql());
    			//view($v);
    		//	exit;
    			$r = self::update_lft($v['id'],$r );
    		}
    	}
    	return $r;
    }
    
    public static function count_all_child($id = 0,$c = 0){
    	$m = Yii::$app->db->createCommand("select a.id,a.parent_id from ".self::tableName()." as a where a.parent_id=$id and a.state>-2 and a.lang='".__LANG__."' and a.sid=".__SID__ )->queryAll();
    	$c += count($m);
    	if(!empty($m)){
    		foreach ($m as $k=>$v){
    			$c = self::count_all_child($v['id'],$c);
    		}
    	}
    	return $c;
    }
    public static function updateAllLevel($id = 0,$parent_id=-1){
    	if($parent_id > -1 && $id>0){ break;
    		if($parent_id > 0){
    			$lv = Yii::$app->db->createCommand("select a.level+1 from ".self::tableName()." as a where id=".$parent_id)->queryScalar();
    			Yii::$app->db->createCommand("update ".self::tableName() ." set level=$lv where id=".$id)->execute();
    				
    		}else {
    			Yii::$app->db->createCommand()->update(self::tableName(),[
    					'level'=>0,    					
    			],array('id'=>$id))->execute();
    		}
    	}
    	$sql = "select a.parent_id,a.level,a.id,a.title,a.lft,a.rgt from ".self::tableName()." as a where a.sid=".__SID__." and a.parent_id=$id and a.state>-2 and a.lang='".__LANG__."'";
    	$sql .= " order by a.parent_id, a.lft,a.position, a.title";
    	$l = Yii::$app->db->createCommand($sql)->queryAll();
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			if($v['parent_id'] > 0){
    				$lv = Yii::$app->db->createCommand("select a.level+1 from ".self::tableName()." as a where id=".$v['parent_id'])->queryScalar();
    				Yii::$app->db->createCommand("update ".self::tableName() ." set level=$lv where id=".$v['id'])->execute();
    				 
    				
    			}else{
    				 
    				Yii::$app->db->createCommand()->update(self::tableName(),[
    						'level'=>0,
    						//'rgt'=>$v['rgt_c']
    				],array('id'=>$v['id']))->execute();
    			}
    			self::updateAllLevel($v['id']);
    		}
    	}
    	 
    }
    public function get_menu_departure($id = 0, $type = 0){
    	$sql = "select a.* from {{%departures_to_categorys}} as a where a.category_id=$id and a.type=$type";
    	$l = Yii::$app->db->createCommand($sql)->queryAll();
    	$rs = array();
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			$rs[] = $v['departure_id'];
    		}
    	}
    	return $rs;
    }
    
	public static function getUrl($url, $id=0,$lang=__LANG__){
    	if($id > 0){}else{
    		$sql = "select max(id)+1 from ".self::tableName();
    		$id = Yii::$app->db->createCommand($sql)->queryScalar();
    	}
    	$id = $id > 0 ? $id : 0;
    	$sql = "select count(1) from {{%slugs}} as a where a.url = '$url' and a.item_id not in($id) and a.sid=".__SID__;
    	while (Yii::$app->db->createCommand($sql)->queryScalar() > 0){
    		$url .= '-' . $id;
    		$sql = "select count(1) from {{%slugs}} as a where a.url = '$url' and a.item_id not in($id) and a.sid=".__SID__;
    	}
    	return $url;
    }
    
    public static function _getMenuPosition($type = 0){
    	$query = (new Query())->from(['b'=>'{{%positions_to_users}}'])
    	->innerJoin(['a'=>'{{%positions}}'],'a.name=b.position_id')
    	->where([
    			'a.sid'=>[0,__SID__],
    			'a.type'=>$type,
    			'b.type_id'=>$type,
    			'b.user_id'=>__SID__
    	])->andWhere(['>','a.state',0]);
    	$l = $query->orderBy(['b.position'=>SORT_ASC, 'a.title'=>SORT_ASC])->all();
    	if(!empty($l)){
    		return $l;
    	}else{
    	//
    	$query = (new Query())->from(['a'=>'{{%positions}}'])->where([
    			'a.sid'=>[0,__SID__],
    			'a.type'=>$type
    	])->andWhere(['>','a.state',0]);
    	$l = $query->orderBy(['a.title'=>SORT_ASC])->all();
    	foreach ($l as $k=>$v){
    		Yii::$app->db->createCommand()->insert('positions_to_users',[
    				'position_id'=>$v['name'],
    				'user_id'=>__SID__,
    				'type_id'=>$v['type'],
    				'position'=>min($v['sid'],9)
    		])->execute();
    	}
    	return $l;
    	}
    }
    public static function getMenuPosition($c = array()){
    	//echo 'ss';
    	//$l = Zii::getConfigs('SITE_MENU')->position;
    	$l = self::_getMenuPosition();
    	//view($l);
    	$rs = '<div class="position-list-check-input inline-block mgr15">';
    	if(!empty($l)){
    		foreach($l as $k=>$v){
    			//if($v->status == 1){
    			$rs .= '<div class="checkbox clist-position inline-block"><label><input class="ckc_'.$v['name'].'" type="checkbox" '.(isset($c[$v['name']]) && $c[$v['name']] == 1 ? 'checked="checked"' : '').' name="pos['.$v['name'].']" /> '.$v['title'].'</label></div>' ;
    			//}
    		}
    	}
    	$rs .= '</div>';
    	if(Yii::$app->user->can([ROOT_USER])){
    		$rs .= '<button data-type="0" data-action="add-more-position" data-title="Thêm position" onclick="open_ajax_modal(this);" type="button" class="btn btn-sm btn-success"><i class="fa fa-plus"></i></button>';
    	}
    	return $rs;
    }
    public static function get_all_child_id($id = 0, $include_id = true){
    	$v = self::getItem($id);
    	$r = [$id];
    	if(!empty($v)){
    		$l = static::find()
    		->where(['lang'=>ADMIN_LANG])
    		->andWhere(['>','state',-2])
    		->andWhere(['>','lft',$v['lft']])
    		->andWhere(['<','rgt',$v['rgt']])
    		//->orderBy(['lft'=>SORT_ASC,'title'=>SORT_ASC])
    		->asArray()->all();
    		if(!empty($l)){
    			foreach ($l as $k=>$v){
    				$r[] = $v['id'];
    			}
    		}
    	}
    	return $r;
    }
    public static function getPosition($id,$return_type = 1){
    	$l = static::find()
    	->select(['a.title'])
    	->from(['a'=>'positions'])
    	->innerJoin(['b'=>'{{%items_to_posiotion}}'],'a.name=b.position_id')
    	->where(['a.type'=>0, 'b.item_id'=>$id,'b.type'=>0,'b.value'=>1])->asArray()->all();
    	 
    	if($return_type == 1 ) return $l;
    	$r = [];
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			$r[] = $v['title'];
    		}
    	}
    	return $r;
    }
    public function updatePosition($id = 0, $pos = array()){
    	$pos = post('pos');
    	Yii::$app->db->createCommand()->delete('{{%items_to_posiotion}}',['item_id'=>$id])->execute();
    	 
    	if(!empty($pos)){
    		foreach ($pos as $p=>$v){
    			Yii::$app->db->createCommand()->insert('{{%items_to_posiotion}}',[
    					'position_id'=>$p,
    					'item_id'=>$id,
    					'type'=>0
    			])->execute();    			
    		}
    	}
    	 
    }
    public function updateDestination($id = 0){    	
    	Yii::$app->db->createCommand()->delete('{{%departures_to_categorys}}',['category_id'=>$id])->execute();
    	if(isset($_POST['fDestination']) && !empty($_POST['fDestination'])){
    		foreach ($_POST['fDestination'] as $v){    			
    			Yii::$app->db->createCommand()->insert('{{%departures_to_categorys}}',[
    					'category_id'=>$id,
    					'departure_id'=>$v,
    					'type'=>1
    			])->execute();
    		}
    	}
    	if(isset($_POST['fDeparture']) && !empty($_POST['fDeparture'])){
    		foreach ($_POST['fDeparture'] as $v){
    			Yii::$app->db->createCommand()->insert('{{%departures_to_categorys}}',[
    					'category_id'=>$id,
    					'departure_id'=>$v,
    					'type'=>0
    			])->execute();
    		}
    	}
    }
}
