<?php 
if(isset($_POST['role'])){
	switch ($_POST['role']){
		case 'load_dia_danh':
			$r = [];
			$l = \app\modules\admin\models\DeparturePlaces::find()->where(['>','state',-2])
			->andWhere(['sid'=>__SID__])
			->andFilterWhere(['like','name',post('data')['q']])
			->asArray()->all();
			$results = [];
			if(!empty($l)){
				foreach($l as $k=>$v){
					$results[] = array('id' => $v['id'], 'text' => $v['name']);
				}
			}
			echo json_encode(array('q' => post('data')['q'], 'results' => $results));
			exit;
			break;
		case 'chosen-load-foods':
			$q = post('data')['q'];
			$m = load_model('foods');
			$existed = post('existed');
			$l = $m->getList(['listItem'=>false,'filter_text'=>$q,'not_in'=>$existed]);
			$results = [];
			if(!empty($l)){
				foreach($l as $k=>$v){
					$results[] = array('id' => $v['id'], 'text' => $v['title']);					
				}
			}
			echo json_encode(array('q' => $q, 'results' => $results)); exit;
			break;
		case 'load_cost_category':
			$q = $_POST['data']['q'];
			//echo json_encode(array('q' => $q, 'results' => array('id' => 1, 'text' => 'sss'))); exit;
			$sql = "select * from cost_category where state>0 and is_active=1 and (id=".((int)$q)." or name like '%".$q."%') limit 100";
			//$results = array('id' => 1, 'text' => $sql);
			//echo json_encode(array('q' => $q, 'results' => $results));
			//exit;
			if(trim($q)!=""){
				$states = Yii::$app->db->createCommand($sql)->queryAll();
		
			}else{$states = null;}
		
			if(count($states)>0){
				$results = array();
				foreach($states as $i => $state){
					//if( @stripos($state,$q) === 0 ){
					$results[] = array('id' => $state['id'], 'text' => $state['name']);
					//}
				}
				echo json_encode(array('q' => $q, 'results' => $results));
			}exit;
			break;
		case 'select_hotel':
			$q = $_POST['data']['q'];
			//echo json_encode(array('q' => $q, 'results' => array('id' => 1, 'text' => 'sss'))); exit;
			$sql = "select * from hotels where sid=".__SID__." and state>0 and is_active=1 and (name like '%".$q."%' or phone like '%".$q."%' or address like '%".$q."%') limit 100";
			//$results = array('id' => 1, 'text' => $sql);
			//echo json_encode(array('q' => $q, 'results' => $results));
			//exit;
			if(trim($q)!=""){
				$states = Yii::$app->db->createCommand($sql)->queryAll();
		
			}else{$states = null;}
		
			if(count($states)>0){
				$results = array();
				foreach($states as $i => $state){
					//if( @stripos($state,$q) === 0 ){
					$results[] = array('id' => $state['id'], 'text' => $state['name']);
					//}
				}
				echo json_encode(array('q' => $q, 'results' => $results));
			}exit;
			break;
		case 'select_car':
			$q = $_POST['data']['q'];
			//echo json_encode(array('q' => $q, 'results' => array('id' => 1, 'text' => 'sss'))); exit;
			$sql = "select id,name from cars where sid=".__SID__." and state>0 and is_active=1 and (name like '%".$q."%' or phone like '%".$q."%' or address like '%".$q."%') limit 100";
			//$results = array('id' => 1, 'text' => $sql);
			//echo json_encode(array('q' => $q, 'results' => $results));
			//exit;
			if(trim($q)!=""){
				$states = Yii::$app->db->createCommand($sql)->queryAll();
		
			}else{$states = null;}
		
			if(count($states)>0){
				$results = array();
				foreach($states as $i => $state){
					//if( @stripos($state,$q) === 0 ){
					$results[] = array('id' => $state['id'], 'text' => $state['name']);
					//}
				}
				echo json_encode(array('q' => $q, 'results' => $results));
			}exit;
			break;
		case 'select_vehicles_makers':
			$q = $_POST['data']['q'];
			$type = isset($_POST['dtype']) && $_POST['dtype'] > 0 ? $_POST['dtype'] : 0;
			//echo json_encode(array('q' => $q, 'results' => array('id' => 1, 'text' => 'sss'))); exit;
			$sql = "select id,title from vehicles_makers where state>0";
			$sql .= $type > 0 ? " and type_id=$type" : "";
			$sql .= " and (title like '%".$q."%') limit 100";
			$results = array(0=>array('id'=>0,'text'=>'- Tất cả các hãng -'));
			if(trim($q)!=""){
				$states = Yii::$app->db->createCommand($sql)->queryAll();
		
			}else{$states = null;}
		
			if(count($states)>0){
				 
				foreach($states as $i => $state){
					//if( @stripos($state,$q) === 0 ){
					$results[] = array('id' => $state['id'], 'text' => $state['title']);
					//}
				}
				echo json_encode(array('q' => $q, 'results' => $results));
			}exit;
			break;
		case 'load_cost_category':
			$q = $_POST['data']['q'];
			//echo json_encode(array('q' => $q, 'results' => array('id' => 1, 'text' => 'sss'))); exit;
			if(trim($q)!=""){
				$states = Zii::$db->queryAll("select * from cost_category where state>0 and is_active=1 and (id=".((int)$q)." or name like '%".$q."%' limit 100");
		
			}else{$states = null;}
			//$results = array('id' => 1, 'text' => "select * from cost_category where state>0 and is_active=1 and (id=".((int)$q)." or name like '%".$q."%' limit 100");
			echo json_encode(array('q' => $q, 'results' => $results));
			exit;
			if(count($states)>0){
				$results = array();
				foreach($states as $i => $state){
					//if( @stripos($state,$q) === 0 ){
					$results[] = array('id' => $state['id'], 'text' => $state['name']);
					//}
				}
				echo json_encode(array('q' => $q, 'results' => $results));
			}exit;
			break;
		case 'load_articles':
			$q = $_POST['data']['q'];
			//echo json_encode(array('q' => $q, 'results' => array('id' => 1, 'text' => 'sss'))); exit;
			if(trim($q)!=""){
				$states = Zii::$db->queryAll("select id,`title`,`url`,`code` from articles where state>0 and lang='".__LANG__."' and sid=".__SID__." and (id=".((int)$q)." or title like '%".$q."%' or url like '%".unMark($q)."%') limit 100");
				 
			}else{$states = null;}
			if(count($states)>0){
				$results = array();
				foreach($states as $i => $state){
					//if( @stripos($state,$q) === 0 ){
					$results[] = array('id' => $state['id'], 'text' =>'[' . $state['code'] . '] ' . $state['title']);
					//}
				}
				echo json_encode(array('q' => $q, 'results' => $results));
			}exit;
			break;
		case 'load_filters':
			$q = $_POST['data']['q'];
			//echo json_encode(array('q' => $q, 'results' => array('id' => 1, 'text' => 'sss'))); exit;
			if(trim($q)!=""){
				$states = Zii::$db->queryAll("select * from filters as a where a.state>0 and sid=".__SID__." and a.lang='".__LANG__."' and (a.name like '%".$q."%' or a.title like '%".($q)."%') limit 100");
				 
			}else{$states = null;}
			////$results = array('id' => 0, 'text' =>  "select * from filters as a where a.state>0 and sid=".__SID__." and (a.name like '%".$q."%' or a.code like '%".unMark($q)."%') limit 100");
			if(count($states)>0){
				$results = array();
				foreach($states as $i => $state){
					//if( @stripos($state,$q) === 0 ){
					$results[] = array('id' => $state['id'], 'text' =>  $state['title']);
					//}
				}
				echo json_encode(array('q' => $q, 'results' => $results));exit;
			}
			break;
		case 'chosen-load-place':
			$q = $_POST['data']['q'];
			$sql = "select * from departure_places as a where a.state>0 and a.sid=".__SID__;
			//$sql .= $group_id > 0 ? " and id not in(select user_id from user_to_group where group_id=$group_id)" : '';
			$sql .= " and (a.name like '%".$q."%' or a.short_name like '%".($q)."%' or a.cname like '%".($q)."%') limit 100";
			$l = Yii::$app->db->createCommand($sql)->queryAll();
			//$data[] = array('id' => 0, 'text' =>$sql,'description'=>'');
			$data = array(array(
					'id'=>0,'text'=>'- Tất cả các địa danh -'
			));
			if(!empty($l)){
				foreach($l as $k=>$v){
					$data[] = array('id' => $v['id'], 'text' => $v['name'] ,'description'=>'');
				}
			}
			echo json_encode(array('q' => $q, 'results' => $data));exit;
			break;
		case 'chosen-load-hight-way':
			 
			$q = $_POST['data']['q'];
			$place = post('place');
			$existed = post('existed');
			$type_id = isset($_POST['type_id']) && $_POST['type_id'] > 0 ? $_POST['type_id'] : 0;
			//echo json_encode(array('q' => $q, 'results' => array('id' => 1, 'text' => 'sss'))); exit;
			$sql = "select a.id,a.title from distances as a where a.state>0 and a.is_active=1";
			$sql .= $type_id > 0 ? " and a.type_id=$type_id" : "";
			$sql .= $existed !="" ? " and a.id not in($existed)" : "";
			//
			if($place != ""){
				$sql .= " and a.id in (select distance_id from distance_to_places where place_id in ($place) ".($type_id > 0 ? " and type_id=$type_id" : '').")";
			}
			//
			$sql .= " and (a.title like '%".$q."%') limit 100";
			$results = array();
			if(trim($q)!=""){
				$states =  Yii::$app->db->createCommand($sql)->queryAll();
		
			}else{$states = null;}
		
			if(count($states)>0){
				 
				foreach($states as $i => $state){
					//if( @stripos($state,$q) === 0 ){
					$results[] = array('id' => $state['id'], 'text' => $state['title']);
					//}
				}
		
			}
			echo json_encode(array('q' => $q, 'results' => $results));exit;
			break;
		case 'load_distances':
			 
			$q = $_POST['data']['q'];
			$place = post('place');
			$existed = post('existed');
			$type_id = isset($_POST['type_id']) && $_POST['type_id'] > 0 ? $_POST['type_id'] : 0;
			//echo json_encode(array('q' => $q, 'results' => array('id' => 1, 'text' => 'sss'))); exit;
			$sql = "select a.id,a.title from distances as a where a.state>0 and a.is_active=1";
			$sql .= $type_id > 0 ? " and a.type_id=$type_id" : "";
			$sql .= $existed !="" ? " and a.id not in($existed)" : "";
			//
			if($place != "" && $place != 'null'){
				$sql .= " and a.id in (select distance_id from distance_to_places where place_id in ($place) ".($type_id > 0 ? " and type_id=$type_id" : '').")";
			}
			//
			$sql .= " and (a.title like '%".$q."%') limit 100";
			$results = array(
					///	array('id'=>0,'text'=>$sql)
					 
			);
			if(trim($q)!=""){
				$states =  Yii::$app->db->createCommand($sql)->queryAll();
		
			}else{$states = null;}
		
			if(count($states)>0){
				 
				foreach($states as $i => $state){
					//if( @stripos($state,$q) === 0 ){
					$results[] = array('id' => $state['id'], 'text' => $state['title']);
					//}
				}
		
			}
			echo json_encode(array('q' => $q, 'results' => $results));exit;
			break;
		case 'chosen-load-country':
			$q = $_POST['data']['q'];
			$m = load_model('local');
			$r = []; 
			$l = $m->getList(['parent_id'=>post('parent_id',0),'filter_text'=>$q, 'p'=>1, 'elevel'=>post('level',-1)]);
			if(!empty($l['listItem'])){				 
				foreach($l['listItem'] as $k => $v){
					$r[] = array('id' => $v['id'], 'text' => showLocalName(uh($v['name']),$v['type_id']) );
				}
			
			}
			echo json_encode(array('q' => $q, 'results' => $r));
			break;
		case 'load_locatition':
			$q = $_POST['data']['q'];
			$data = [];
			$m = new app\modules\admin\models\Local();
			$l = $m->find_local(array('text'=>$q));
			if(!empty($l)){
				foreach($l as $k=>$v){
					$data[] = array('id' => $v['id'],'disabled'=>isset($v['disabled']) ? $v['disabled'] : false, 'text' =>spc($v['level']). showLocalName($v['name'],$v['type_id']) ,'description'=>'');
				}
			}
			echo json_encode(array('q' => $q, 'results' => $data));
			break;
	}
}
exit;