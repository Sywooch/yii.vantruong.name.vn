<?php
$data  = [];
$q = strip_tags(post('q'));
 
switch (Yii::$app->request->post('role')){
	 
	case 'load_articles':
		$filter_id = post('filter_id');
		$sql = "select a.id,a.code,a.url,a.title from {{%articles}} as a";
		$sql .= " where a.state>-2 and a.lang='".__LANG__."' and a.sid=".__SID__;
	
		$sql .= $filter_id > 0 ? " and a.id not in(select item_id from {{%articles_to_filters}} where filter_id=$filter_id)" : "";
	
		$sql .= " and (a.code like '%".$q."%' or a.title like '%".($q)."%' or a.short_title like '%".($q)."%' or a.url like '%".unMark($q)."%' )";
		$sql .= " order by a.position,a.title limit 100";
		$l = Yii::$app->db->createCommand($sql)->queryAll();
		//$data[]	= ['id'=>0,'text'=>$sql];
		if(!empty($l)){
			foreach($l as $k=>$v){
				$data[] = array('id' => $v['id'], 'text' =>'<b class="red">['. $v['code'].']</b><b> ' . $v['title'] .'</b>','description'=> '<i></i>');
			}
		}
		break;
	case 'load_locatition':
		
		$m = new app\modules\admin\models\Local();
		$l = $m->find_local(array('text'=>$q)); 
		if(!empty($l)){			 
			foreach($l as $k=>$v){
				$data[] = array('id' => $v['id'],'disabled'=>isset($v['disabled']) ? $v['disabled'] : false, 'text' =>spc($v['level']). showLocalName($v['name'],$v['type_id']) ,'description'=>'');
			}
		}
		break;
	case 'load_dia_danh':
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
		break;
	case 'load_user':
		$group_id = post('group_id');
		$sql = "select id,username,email,phone from {{%users}} as a where state>0 and a.sid=".__SID__;
		//$sql .= " and type='normal'";
		//$sql .= " and parent_id=".MEMBER_LOGIN_ID;
		$sql .= $group_id > 0 ? " and id not in(select user_id from {{%user_to_group}} where group_id=$group_id)" : '';
		$sql .= " and (username like '%".$q."%' or email like '%".($q)."%' or phone like '%".($q)."%') limit 100";
		$l = Yii::$app->db->createCommand($sql)->queryAll();
		if(!empty($l)){
			foreach($l as $k=>$v){
				$data[] = array('id' => $v['id'], 'text' => $v['username'] != "" ? $v['username']  : $v['email'],'description'=>$v['email'] . ' / ' . $v['phone']);
			}
		}
		break;
	case 'load_customers':
		//$group_id = post('group_id');
		$sql = "select id,name,email,phone from customers where state>0 and sid=".__SID__;
		//$sql .= $group_id > 0 ? " and id not in(select user_id from user_to_group where group_id=$group_id)" : '';
		$sql .= " and (code like '%".$q."%' or name like '%".$q."%' or email like '%".($q)."%' or phone like '%".($q)."%') limit 100";
		$l = Zii::$db->queryAll($sql);
		//$data[] = array('id' => 0, 'text' =>$sql,'description'=>'');
		if(!empty($l)){
			foreach($l as $k=>$v){
				$data[] = array('id' => $v['id'], 'text' => $v['name'] != "" ? $v['name']  : $v['email'],'description'=>$v['email'] . ' / ' . $v['phone']);
			}
		}
		break;
	case 'load_filters':
			
		$sql = "select a.*,(select title from filters where id=a.parent_id) as parent_name from filters as a where a.state>0 and a.sid=".__SID__ ." and a.lang='".__LANG__."' and a.code not in('".implode("','",app\modules\admin\models\Filters::filter_not_inclued())."')";
		//$data[] = array('id' => 0, 'text' => $sql ,'description'=>'');
		$sql .= " and (a.name like '%".$q."%' or a.title like '%".($q)."%') limit 100";
		$l = Yii::$app->db->createCommand($sql)->queryAll();
			
		if(!empty($l)){
			foreach($l as $k=>$v){
				$data[] = array('id' => $v['id'], 'text' => $v['title'] . ($v['parent_name'] != "" ? ' ('.$v['parent_name'].')' : '') ,'description'=>'');
			}
		}
		break;
	 
	
	case 'load_costs':
		//$group_id = post('group_id');
		//$data = array();
		$type = post('type');
		$sql = "select * from costs where state>0 and sid=".__SID__;
		$sql .= $type > 0 ? " and type=$type" : '';
		//$sql .= $group_id > 0 ? " and id not in(select user_id from user_to_group where group_id=$group_id)" : '';
		$sql .= " and (name like '%".$q."%' or unit like '".($q)."' or price like '".($q)."') limit 100";
	
		$l = Zii::$db->queryAll($sql);
		//$data[] = array('id' => 0, 'text' =>$sql,'description'=>'');
		if(!empty($l)){
			foreach($l as $k=>$v){
				$data[] = array('id' => $v['id'], 'text' =>$v['name'],'description'=>$v['price'] > 0 ? (number_format($v['price']).'đ' . ($v['unit'] != "" ?  ' / ' . $v['unit'] : '')) : '' );
			}
		}
		break;
	case 'load_tour_type':
		$sql = "select id,name from contract_type where state>0 and sid=".__SID__;
		$sql .= " and (name like '%".$q."%') limit 100";
		$l = Zii::$db->queryAll($sql);
		if(!empty($l)){
			foreach($l as $k=>$v){
				$data[] = array('id' => $v['id'], 'text' =>  $v['name'] ,'description'=>'');
			}
		}
		break;
	 
}
echo json_encode($data);
exit;
 