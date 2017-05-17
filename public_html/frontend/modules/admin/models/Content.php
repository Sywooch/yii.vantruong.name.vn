<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class Content extends \yii\db\ActiveRecord
{
	public static function getBooleanFields(){
		return [
				'is_active',	
		];
	}
	public static function jsonFields(){
		return [
				'content'
		];
	}
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%articles}}';
    }
    
    public static function tableToCategorys()
    {
    	return '{{%items_to_category}}';
    }
    public static function tableToAttrs()
    {
    	return '{{%articles_to_attrs}}';
    }
	public static function tableToFilters()
    {
    	return '{{%articles_to_filters}}';
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
    	return (new Query())->select('max(id) +1')->from(self::tableName())->scalar();    	 
    }     
     
    public static function getItemProducer($id = 0){
    	return (new Query())->select(['a.*'])->from(['a'=>'{{%producers}}'])
    	->innerJoin(['b'=>'{{%items_to_producer}}'],'a.id=b.join_id')
    	->where(['b.item_id'=>$id,'b.type'=>0])->one();
    	
    }
    public static function getItemSupplier($id = 0){
    	return (new Query())->select(['a.*'])->from(['a'=>'{{%customers}}'])
    	->innerJoin(['b'=>'{{%items_to_producer}}'],'a.id=b.join_id')
    	->where(['b.item_id'=>$id,'b.type'=>1])->one();
    	 
    }
    public static function getItemMadeIn($id = 0){
    	return (new Query())->select(['a.*'])->from(['a'=>'{{%products_made_in}}'])
    	->innerJoin(['b'=>'{{%items_to_producer}}'],'a.id=b.join_id')
    	->where(['b.item_id'=>$id,'b.type'=>2])->one();
    
    }
    
    
    public function getTourHotel(){
    	switch (ADMIN_LANG){
    		case 'vi_VN':
    			return array(
    			//array('id'=>1,'name'=>'1 sao'),
    			array('id'=>2,'name'=>'2 sao'),
    			array('id'=>3,'name'=>'3 sao','default'=>1),
    			array('id'=>4,'name'=>'4 sao'),
    			array('id'=>5,'name'=>'5 sao'),
    			);
    			break;
    		default:
    			return array(
    			//array('id'=>1,'name'=>'1 star'),
    			array('id'=>2,'name'=>'2 star'),
    			array('id'=>3,'name'=>'3 star','default'=>1),
    			array('id'=>4,'name'=>'4 star'),
    			array('id'=>5,'name'=>'5 star'),
    			);
    			break;
    	}
    	 
    }
    public function getDeparturePlace($o = array()){
    	$type = isset($o['type']) ? $o['type'] : -1;
    	$dtype = isset($o['dtype']) ? $o['dtype'] : 2;
    	$is_start = isset($o['is_start']) ? $o['is_start'] : -1;
    	$item_id = isset($o['item_id']) ? $o['item_id'] : -1;
    	$is_destination = isset($o['is_destination']) ? $o['is_destination'] : -1;
    	$xdepart = isset($o['xdepart']) && $o['xdepart'] ? true : false;
    	$sql = "SELECT a.*  
    			".($xdepart ? ",b.price" : '')."
        			FROM {{%departure_places}} as a";
    	$sql .= $xdepart ? " inner join {{%tours_to_destinations}} as b on a.id=b.destination_id " : "";
    	$sql .= " where a.lang='".__LANG__."' and a.sid=".__SID__. ($xdepart ? " and b.type=$dtype and b.item_id=$item_id" : '');
    	$sql .= $type > -1 ? " and a.type=$type" : '';
    	$sql .= $is_start > -1 ? " and a.is_start=1" : '';
    	$sql .= $is_destination > -1 ? " and a.is_destination=1" : '';
    	$sql .= " order by a.position,a.type, a.name";    	 
    	return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public static function getTourType(){
    
    	return array(
    			array('id'=>1,'name'=>getTextTranslate(21)),
    			array('id'=>2,'name'=>getTextTranslate(22)),
    	);
    }
    public static function getItemCategorys($id,$o=[]){
    	$return_type = !is_array($o) ? $o : (isset($o['return_type']) ? $o['return_type'] : 1);
    	$field = isset($o['field']) ? $o['field'] : 'title';
    	$query = new Query();
    	$query
    	->select(['a.id','a.title'])
    	->from(['a'=>Menu::tableName()])
    	->innerJoin(['b'=>self::tableToCategorys()],'a.id=b.category_id')
		->where(['b.item_id'=>$id,'a.sid'=>__SID__])
		->andWhere(['>','a.state',-2])	;
    	//view($query->createCommand()->getSql());
    	$l = $query->all();
    	//view($l);
    	switch ($return_type){
    		case 1: return $l; break;
    		default: 
    			$r = [];
    			if(!empty($l)){
    			foreach ($l as $k=>$v){
    				$r[] = uh($v[$field]);
    			}}
    			return $r;
    			break;
    	}
    }
    public static function getAttrs($id=0){
    	$query = new Query();
    	$item = $query->from(self::tableToAttrs())->where(['item_id'=>$id, ])->all();
    	$r = [];
    	if(!empty($item)){
    		foreach ($item as $i){
    			$r[$i['attr_id']] = $i;
    		}
    	}
    	 
    	return $r;
    }
    public static function getItemFilters($id = 0){    	
    	$l = (new Query())->from(self::tableToFilters())->where(['item_id'=>$id])->all();
    	$rs = array();
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			$rs[] = $v['filter_id'];
    		}
    	}
    	unset($l);
    	return $rs;
    }
    public static function getItemCourseCategorys($item_id,$o = 1){
    	$l = static::find()
    	->from(['a'=>self::tableName()])
    	->select(['a.*'])
    	->innerJoin(['b'=>'item_to_courses'],'a.id=b.course_id')
    	//	->innerJoin(['c'=>FoodsCategorys::tableName()],'c.id=b.category_id')
    	->where(['b.item_id'=>$item_id,'a.sid'=>__SID__])
    	->orderBy(['a.position'=>SORT_ASC,'a.title'=>SORT_ASC])
    	->asArray()->all();
    	$r = [];
    	if(!empty($l)){
    		foreach($l as $k=>$v){
    			$r[] = $o == 1 ? $v['id'] : uh($v['title']);
    		}
    	}
    	return $r;
    }
    
    public static function getItemBranches($item_id,$o = 1){
    	$l = static::find()
    	->from(['a'=>Branches::tableName()])
    	->select(['a.*'])
    	->innerJoin(['b'=>'items_to_branches'],'a.id=b.bran_id')
    	//	->innerJoin(['c'=>FoodsCategorys::tableName()],'c.id=b.category_id')
    	->where(['b.item_id'=>$item_id,'a.sid'=>__SID__])
    	->orderBy(['a.position'=>SORT_ASC,'a.name'=>SORT_ASC])
    	->asArray()->all();
    	$r = [];
    	if(!empty($l)){
    		foreach($l as $k=>$v){
    			$r[] = $o == 1 ? $v['id'] : uh($v['name']);
    		}
    	}
    	return $r;
    }
    /*
     * 
     */
    public static function getItem($id=0,$o=[]){
    	$type = isset($o['type']) ? $o['type'] : false;
    	$query = static::find()
    	->select(['a.*'])
    	->from(['a'=>self::tableName()])
    	->where(['id'=>$id, 'sid'=>__SID__]);
    	switch ($type){
    		case 'tours':
    			$query->innerJoin(['b'=>'{{%tours_attrs}}'],"a.id=b.item_id");    
    			$query->addSelect(['b.*']);
    			break;
    	}
    	$item = $query->asArray()->one();
    	if(!empty($item)){
    		// Producer
    		$producer = self::getItemProducer($item['id']);
    		// Supplier
    		$supplier = self::getItemSupplier($item['id']);
    		// Made in
    		$madein = self::getItemMadeIn($item['id']);
    		// Filters
    		$filters = self::getItemFilters($item['id']);
    		$c = isset($item['content']) ? djson($item['content']) : [];
    		return $item + self::getAttrs($item['id']) + (!empty($c) ? $c : [])
    		+ (!empty($producer) ? ['producer'=>$producer] : [])
    		+ (!empty($supplier) ? ['supplier'=>$supplier] : [])
    		+ (!empty($madein) ? ['made_in'=>$madein] : [])
    		+ (!empty($filters) ? ['filters'=>$filters] : [])
    		;
    	}
    }
    
    
    public static function getListItem($o = []){
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	->where(['a.sid'=>__SID__])
    	->andWhere(['>','a.state',-2]);
    	$in = isset($o['in']) ? $o['in'] : false;
    	$type = isset($o['type']) ? $o['type'] : false;
    	if($in !== false){
    		$query->andWhere(['in','a.id',$in]);
    	}
    	if($type!== false){
    		$query->andWhere(['in','a.type',$type]);
    	}
    	return $query->asArray()->all();
    }
    
    public static function getList($o = []){
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.time'=>SORT_DESC,'a.id'=>SORT_DESC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);    
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';    	
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : -1;
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$category_id = isset($o['category_id']) ? $o['category_id'] : 0;
    	$type = isset($o['type']) ? $o['type'] : false;
    	$offset = ($p-1) * $limit;
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	->where(['a.sid'=>__SID__])
    	->andWhere(['>','a.state',-2]);
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['or',
    				['like', 'a.title', $filter_text],
    				['like', 'a.code', $filter_text],
    				]);
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
    	switch ($type){
    		case 'tours':
    			$query->innerJoin(['b'=>'{{%tours_attrs}}'],"a.id=b.item_id");
    			break;
    	}
    	
    	if(!checkAuthByUser(['form-'.$type.'-view-all'])){
    		$query->andWhere(['a.owner'=>Yii::$app->user->id]);
    	}
    	if($category_id > 0){
    		$query->andWhere(['in','a.id',(new Query())->select(['item_id'])->from('items_to_category')->where(['category_id'=>$category_id])]);
    	} 
    	$c = 0;
    	if($count){
    		$query->select('count(1)');
    		$c = $query->scalar();
    	}
    	$query->select(['a.*','(select concat(lname,\' \',fname) from {{%users}} where id=a.owner) as post_by_name']);
    	switch ($type){
    		case 'tours':
    			$query->addSelect(['b.*']);    	    			
    			break;
    	}
    	
    	$query->orderBy($order_by)
    	->offset($offset)
    	->limit($limit);
    	$l = $query->asArray()->all();
    	if(!empty($l)){
    		foreach($l as $k=>$v){
    			
    			$l[$k] += self::getAttrs($v['id']);
    		}
    	}
    	//
    	return [
    			'listItem'=>$l,
    			'total_records'=>$c,
    			'total_pages'=>ceil($c/$limit),
    			'limit'=>$limit,
    			'p'=>$p,
    	];
    	
    }
    
    public static function updateCategory($id = 0){
    	$category_id =  post('category_id',[]);
    	if(!is_array($category_id)) $category_id = array($category_id);
    	Yii::$app->db->createCommand()->delete(self::tableToCategorys(),['item_id'=>$id])->execute();    	
    	if(!empty($category_id)){
    		foreach ($category_id as $c){
    			Yii::$app->db->createCommand()->insert(self::tableToCategorys(),['item_id'=>$id,'category_id'=>$c])->execute();
    		}
    	}
    }
    public static function updateBranches($id = 0){
    	$biz = post('biz',[]);
    	$brans = isset($biz['branches']) ? $biz['branches'] : [];
    	 
    	Yii::$app->db->createCommand()->delete('items_to_branches',['item_id'=>$id])->execute();
    	if(!empty($brans)){
    		foreach ($brans as $c){
    			Yii::$app->db->createCommand()->insert('items_to_branches',['item_id'=>$id,'bran_id'=>$c])->execute();
    		}
    	}
    }
    public function updateArticleAttr($id = 0){
    	
    	$attrs = post('attrs',[]);    	
    	TaskScheduler::deleteScheduler([
    			'ACTIVE_ATTR_ARTICLE',
    			'DEACTIVE_ATTR_ARTICLE'
    	], $id);
    	if(!empty($attrs)){
    		foreach ($attrs as $k=>$v){    			 
    			$val = isset($v['state']) && cbool($v['state']) == 1 ? 1 : 0;
    			$v['from_date'] = isset($v['from_date']) ? $v['from_date'] : '0000-00-00 00:00:00';
    			$v['to_date'] = isset($v['to_date']) ? $v['to_date'] : '0000-00-00 00:00:00';
    			//
    			if(check_date_string($v['from_date']) || check_date_string($v['to_date'])){
    				if(!check_date_string($v['from_date'])) $v['from_date'] = date("d/m/Y H:i:s");
    				if(check_date_string($v['to_date']) && ctime(['string'=>$v['from_date'],'return_type'=>1]) > ctime(['string'=>$v['to_date']])){
    					$v['from_date'] = $v['to_date'];
    				}
    				$val = 1;
    				if(ctime(['string'=>$v['to_date']]) > time()){
    					$val = 1;
    				}elseif(check_date_string($v['to_date'])){
    					$val = 0;
    				}
    				if(ctime(['string'=>$v['from_date']]) > time()){
    					$val = 0;
    				}else{
    					//ctime(['string'=>$v['from_date']])
    				}
    			}
    		 
    			//
    			if((new Query())->from(self::tableToAttrs())->where([
    					'item_id'=>$id,'attr_id'=>$k
    			])->count(1) == 0){    				
    				Yii::$app->db->createCommand()->insert(self::tableToAttrs(),[
    						'item_id'=>$id,
    						'state'=>$val,
    						'attr_id'=>$k,
    						'from_date'=>ctime(['string'=> $v['from_date']]),
    						'to_date'=>ctime(['string'=>$v['to_date']])
    				])->execute();
    			}else{    				 
    				Yii::$app->db->createCommand()->update(self::tableToAttrs(),[    						
    						'state'=>$val,
    						'from_date'=>ctime(['string'=>$v['from_date']]),
    						'to_date'=>ctime(['string'=>$v['to_date']])
    				],[
    					'item_id'=>$id,'attr_id'=>$k
    			])->execute();
    			}
    			
    			$code = 'ACTIVE_ATTR_ARTICLE';
    			TaskScheduler::updateScheduler([
    					'code'=>$code,
    					'item_id'=>$id,
    					'sid'=>__SID__,
    					'time'=>ctime(['string'=>$v['from_date']]),
    					'bizrule'=>[
    							'table'=>self::tableToAttrs(),
    							'fields'=>['state'=>1],
    							'conditions'=>[
    								'item_id'=>$id,'attr_id'=>$k	
    							]
    					]
    			]);
    			
    			$code = 'DEACTIVE_ATTR_ARTICLE';
    			TaskScheduler::updateScheduler([
    					'code'=>$code,
    					'item_id'=>$id,
    					'sid'=>__SID__,
    					'time'=>ctime(['string'=>$v['to_date']]),
    					'bizrule'=>[
    							'table'=>self::tableToAttrs(),
    							'fields'=>['state'=>0],
    							'conditions'=>[
    									'item_id'=>$id,'attr_id'=>$k
    							]
    					]
    			]);
    			//view(ctime(['string'=>$v['from_date']])); 
    		}
    	}
    	
    }
    public static function getListAttrsByType($type = ''){
    	return (new Query())->from('{{%articles_attrs}}')->where([
    			'>','state',-2
    	])->andWhere([
    			'type'=>[$type,'all']
    	])->orderBy('title')->all();
    }
    public static function getDetailTabs($id = 0, $return_mode = 0){    	
    	$l = (new Query())->from(['a'=>'{{%tab_details}}'])->where(['a.item_id'=>$id])->all();
    	switch($return_mode){
    		case 1:// return id only
    			$rs = array();
    			if(!empty($l)){
    				foreach($l as $k=>$v){
    					$rs[] = $v['id'];
    				}
    			}
    			return $rs;
    			break;
    		default: return $l; break;
    	}
    }
    public function updateTabs($id = 0){
    	if(isset($_POST['tab'])){
    		$tabs = isset($_POST['tab']) ? $_POST['tab'] : array();
    		$biz = isset($_POST['tab_biz']) ? $_POST['tab_biz'] : array();
    		$existed = self::getDetailTabs($id,1);
    		if(!empty($tabs)){
    			foreach($tabs as $k=>$v){
    				if(isset($v['id']) && $v['id'] > 0){
    					$tid = $v['id']; unset($v['id']);    					
    					Yii::$app->db->createCommand()->update('{{%tab_details}}',$v,['id'=>$tid])->execute();
    					$key = array_search($tid, $existed);
    					if($key !== false){
    						unset($existed[$key]);
    					}
    				}else{
    					$v['item_id'] = $id;
    					if(isset($v['id'])) unset($v['id']);
    					//$tid = Zii::$db->insert('tab_details',$v);
    					Yii::$app->db->createCommand()->insert('{{%tab_details}}',$v)->execute();
    					$tid = Yii::$app->db->createCommand("select max(id) from {{%tab_details}}")->queryScalar();
    				}
    				//biz update
    				$b = cjson(isset($biz[$k]) ? $biz[$k] : array());
    				Yii::$app->db->createCommand()->update('{{%tab_details}}',['bizrule' => $b],['id'=>$tid])->execute();
    			}
    		}
    		if(!empty($existed)){
    			Yii::$app->db->createCommand()->delete('{{%tab_details}}',['id'=>$existed])->execute();
    			 
    		}
    	}
    }
    public function updateFilters($id = 0){
    	$filters = isset($_POST['filters']) ? $_POST['filters'] : array();
    	Yii::$app->db->createCommand()->delete('{{%articles_to_filters}}',['item_id'=>$id])->execute();    	
    	if(!empty($filters)){
    		foreach ($filters as $k=>$v){    			
    			Yii::$app->db->createCommand()->insert('{{%articles_to_filters}}',['filter_id'=>$v,'item_id'=>$id])->execute();
    		}
    	}
    }
    
    public function updatePrice($id = 0){
    	$d = isset($_POST['d']) ? $_POST['d'] : array();
    	$prices = isset($_POST['prices']) ? $_POST['prices'] : [];
    	Yii::$app->db->createCommand()->delete('{{%item_to_prices}}',['item_id'=>$id])->execute();
    	Yii::$app->db->createCommand()->delete('{{%task_scheduler}}',['item_id'=>$id,'code'=>'UPDATE_ARTICLE_PRICE','sid'=>__SID__])->execute();
    	$tour_style = isset($t['tour_style']) ? $t['tour_style'] : -1;
    	
    	if(!empty($prices)){
    		foreach ($prices as $k=>$v){
    			$v['time'] = $time = ctime(['string'=>$v['time']]);
    			$v['bizrule'] = cjson(isset($v['bizrule']) ? ($v['bizrule']) : []);
    			Yii::$app->db->createCommand()->insert('{{%item_to_prices}}',$v)->execute();
    			if(strtotime($time) > time()){
    				Yii::$app->db->createCommand()->insert('{{%task_scheduler}}',[
    						'code'=>'UPDATE_ARTICLE_PRICE',
    						'item_id'=>$id,
    						'sid'=>__SID__,
    						'time'=>$time,
    						'bizrule'=>cjson(array(
    								'price2' =>	cprice($v['price2']),
    						)),
    				])->execute();
    				 
    			}
    		}
    	}    	 
    }
    public function updateProducers($id = 0){
    	if($id > 0){
    		$producers = isset($_POST['producers']) ? $_POST['producers'] : [];
    		Yii::$app->db->createCommand()->delete('{{%items_to_producer}}',['item_id'=>$id])->execute();
    		if(!empty($producers)){    			    		
    			// Cập nhật hãng sx
    			/*    			 
    			 * 0: Hãng sx 
    			 * 1: Nhà CC
    			 * 2: Xuất xứ
    			*/ 
    			foreach ($producers as $k=>$v){
    				if($v>0){
    				Yii::$app->db->createCommand()->insert('{{%items_to_producer}}',[    						
    						'item_id'=>$id,
    						'join_id'=>$v,
    						'type'=>$k,    						 
    				])->execute();
    				}
    			}
    			 
    		}
    	}
    }
    
    public function updateTask($id=0){
    	$task = isset($_POST['task']) ? $_POST['task'] : array();
    	if($id >0){
    		if(!empty($task)){
	    		if(isset($task['active_from_date']) && check_date_string($task['active_from_date'])){    			 
	    			$code = 'ACTIVE_ARTICLE';
	    			TaskScheduler::updateScheduler([
	    					'code'=>$code,
	    					'item_id'=>$id,
	    					'sid'=>__SID__,
	    					'time'=>ctime(['string'=>$task['active_from_date']])
	    			]);
	    		}
	    		if(isset($task['active_to_date']) && check_date_string($task['active_to_date'])){
	    			$code = 'DEACTIVE_ARTICLE';
	    			TaskScheduler::updateScheduler([
	    					'code'=>$code,
	    					'item_id'=>$id,
	    					'sid'=>__SID__,
	    					'time'=>ctime(['string'=>$task['active_from_date']])
	    			]);
	    		}
	    		 
	    		if(ctime(array('string'=>$task['active_from_date'],'return_type'=>1)) > time()+300){
	    			Yii::$app->db->createCommand()->update(self::tableName(),['is_active'=>0],['id'=>$id,'sid'=>__SID__])->execute();
	    		}	    		 	    			    	
    		}
    		 
    		Siteconfigs::updateBizrule(self::tableName(),['id'=>$id,'sid'=>__SID__],$task);
    		//view()
    		return true;
    	}
    	return false;
    }
    public function updateAttrType($id, $type){
    	$f = post($type,[]);
    	if(!empty($f)){
    	switch ($type){
    		case 'tours':
    			if((new Query())->from('{{%tours_attrs}}')->where(['item_id'=>$id])->count(1) == 0){
    				Yii::$app->db->createCommand()->insert('{{%tours_attrs}}',$f + ['item_id'=>$id])->execute();
    			}else{
    				Yii::$app->db->createCommand()->update('{{%tours_attrs}}',$f,['item_id'=>$id])->execute();
    			}
    			 
    			break;
    	}
    	}
    	 
    }
    public static function getTabCategorys($type = 'text'){     	
    	return (new Query())->from ('{{%tab_categorys}}')->where(['sid'=>__SID__,'f_type'=>$type])->orderBy(['position'=>SORT_ASC])->all();
    }
     
    public function get_selected_filters($id = 0){
    	$sql = "select a.* from {{%filters}} as a where a.state>-2 and a.is_active=1 and a.code='tour_type' and a.id in(select filter_id from {{%articles_to_filters}} where item_id=$id)";
    	return Yii::$app->db->createCommand($sql)->queryAll();
    }
    
    public static function getDefaultTabs($type){
    	$a = (new Query())->select('bizrule')->from('{{%tab_detail_example}}')->where(['sid'=>__SID__,'type'=>$type])->scalar();
    	if(!is_array($a)){
    		$a = djson($a);
    	}
    	return $a;
    }
    public static function getTourStyle(){
    	switch (ADMIN_LANG){
    		case 'vi_VN':
    			return array(
    			array('id'=>1,'name'=>'Tour cố định ngày'),
    			array('id'=>2,'name'=>'Tour khởi hành hàng ngày'),
    			array('id'=>5,'name'=>'Tour khởi hành hàng tuần'),
    			array('id'=>3,'name'=>'Tour khởi hành theo yêu cầu'),
    			//array('id'=>4,'name'=>'Tour khác'),
    			);
    			break;
    		default:
    			return array(
    			array('id'=>1,'name'=>'Tour fixed date'),
    			array('id'=>2,'name'=>'Tour daily'),
    			array('id'=>5,'name'=>'Tour weekly'),
    			array('id'=>3,'name'=>'Tour on demand'),
    			//array('id'=>4,'name'=>'Tour other'),
    			);
    			break;
    	}
    }
    public static function get_item_prices($o = array()){
    	$id = 	isset($o['id']) ? $o['id'] : (isset($o['item_id']) ? $o['item_id'] : (is_numeric($o) ? $o : 0));
    	$type = isset($o['type']) ? $o['type'] : 0;
    	$time = isset($o['time']) ? $o['time'] : false;
    	$local = isset($o['local']) && $o['local'] == true ? true : false;
    	$price_type = isset($o['price_type']) ? $o['price_type'] : -1;
    	$group_id = isset($o['group_id']) && is_numeric($o['group_id'])  ? $o['group_id'] : (isset($o['group']) && is_numeric($o['group']) ? $o['group'] : -1);
    	//view($group_id);
    
    	$is_active = isset($o['is_active']) ? $o['is_active'] : 1;
    	$query = isset($o['query']) ? 'query'. $o['query'] : 'queryAll';
    	$return = isset($o['return']) ? $o['return'] : true;
    	$sql = "select a.*";
    	$sql .= $local ? "" : '';
    	$sql .= " from item_to_prices as a inner join articles as b on a.item_id=b.id";
    	$sql .= $local ? " left outer join departure_places as c on a.group_id=c.id" : '';
    	$sql .= " where a.item_id=$id and b.sid=".__SID__;
    	$sql .= $price_type > -1 ? " and a.price_type=$price_type" : '';
    	$sql .= $type > -1 ? " and a.type=$type" : '';
    	$sql .= $group_id > -1 ? " and a.group_id=$group_id" : '';
    	$sql .= $is_active > -1 ? " and a.is_active=$is_active" : '';
    	if($time !== false){
    		if($time == 'now'){
    			$sql .= " and unix_timestamp(a.time) > ".time();
    		}else{
    			$time = convertTime($time,'Y-m-d',1);
    			$sql .= " and dayofyear(a.time)=".(date('z',$time)+1);
    			$sql .= " and year(a.time)=".date('Y',$time);
    		}
    	}
    	$sql .= $local ? " order by c.name" : '';
    	//view($sql);
    	$l = Yii::$app->db->createCommand($sql)->queryAll();
    	if(is_array($return) && !empty($return)){
    		switch ($return['type']){
    			case 'array':
    				$rs = array();
    				if(!empty($l)){
    					foreach ($l as $k=>$v){
    						$rs[] = $v[$return['field']];
    					}
    				}
    				return $rs;
    				break;
    		}
    	}
    	return $l;
    }
    public function getGroupsSplit(){
    	$sql = "select * from {{%filters}} as a where a.parent_id>0 and a.state>-1 and a.is_active=1 and a.code='groups_tours' and a.sid=".__SID__ . " and a.lang='".__LANG__."'";
    	$sql .= " order by a.position,a.title";
    	return Yii::$app->db->createCommand($sql)->queryAll();
    	return array(
    			array('id'=>1,'name'=>'Nhóm 5 người','from'=>5,'to'=>9),
    			array('id'=>2,'name'=>'Nhóm 10 người','from'=>10,'to'=>14),
    			array('id'=>3,'name'=>'Nhóm 15 người','from'=>15,'to'=>19),
    			array('id'=>4,'name'=>'Nhóm 20 người','from'=>20,'to'=>24)
    	);
    }
}
