<?php
use yii\db\Query;
use app\modules\admin\models\Siteconfigs;
use app\modules\admin\models\Content;
use app\modules\admin\models\Menu;
use app\modules\admin\models\AdminMenu;
 
switch (Yii::$app->request->get('action')){
	case 'load_domains':
		$r = [];
		$l = \app\modules\admin\models\Domains::find()
		->where(['>','state',-2])
		->andWhere(['sid'=>__SID__])
		->andFilterWhere(['like','domain',getParam('term')])
		->asArray()->all();
		if(!empty($l)){
			foreach ($l as $k=>$v){
				$r[$k]['id'] = $v['domain'];
				$r[$k]['label'] = uh($v['domain']);
				$r[$k]['value'] = uh($v['domain']);
			}
		}
		echo cjson($r);exit;
		break;
	case 'load_dia_danh':
		$r = [];
		$l = \app\modules\admin\models\DeparturePlaces::find()->where(['>','state',-2])
		->andWhere(['sid'=>__SID__])
		->andFilterWhere(['like','name',getParam('term')])
		->asArray()->all();
		if(!empty($l)){
			foreach ($l as $k=>$v){
				$r[$k]['id'] = $v['id'];
				$r[$k]['label'] = uh($v['name']);
				$r[$k]['value'] = uh($v['name']);
			}
		}
		echo cjson($r);exit;
		break;
	case 'load_foods':
		$r = [];
		$l = \app\modules\admin\models\Foods::find()->where(['>','state',-2])
		->andFilterWhere(['like','title',getParam('term')])
		->andWhere(['sid'=>__SID__])
		->asArray()->all();
		if(!empty($l)){
			foreach ($l as $k=>$v){
				$r[$k]['id'] = $v['id'];
				$r[$k]['label'] = uh($v['title']);
				$r[$k]['value'] = uh($v['title']);
			}
		}
		echo cjson($r);exit;
		break;
	case 'countNotifis':		
		$noti = (new Query())->from('notifications')->where(['state'=>-1,'sid'=>__SID__])->count(1);
		$r = array('unview'=>$noti,'b'=>2);
		echo cjson($r);exit;
		break; 
	case 'getNotifis':
		 
		$l = (new Query())->from(['a'=>'notifications'])->where(['sid'=>__SID__])->andWhere(['>','state',-2])->limit(15)->orderBy([
				'time'=>SORT_DESC
		])->all();
		$html = '<div class="notification-scroll">';
		if(!empty($l)){
			foreach ($l as $k=>$v){
				$user = app\modules\admin\models\Users::getUserName($v['uid']);
				$html .= '<li class="item-state-'.$v['state'].'">
                      <a class="state-'.$v['state'].'" data-id="'.$v['id'].'" onclick="return changeNotificationState(this);" href="'.($v['link'] != "" ? $v['link'] : '#').'">
                        <span class="image hide"></span>
                        <span>
                          <span>'.$v['title'].'</span>
                          <p class="time fa fa-user">'.(trim($user) != "" ? '<span class="italic underline">' . trim($user) . '</span> - ': '').count_time_post($v['time']).'</p>
                        </span>
                        <span class="message">
						'.uh($v['note']).'
                        </span>
                      </a>
                    </li>';
					
			}
		}else{
			$html .= '<li><a><span><span></span><p class="time fa fa-user"></p></span><span class="message">Bạn chưa có thông báo nào ...</span></a></li>';
		}
		$html .= '</div><li onclick="return false;"><div class="text-center w100"><a><strong>Xem thêm thông báo</strong><i class="fa fa-angle-right"></i></a></div></li>';
		$r = array('html'=>$html);
		Yii::$app->db->createCommand()->update('notifications',['state'=>0],['sid'=>__SID__,'state'=>-1])->execute();
		echo json_encode($r); exit;
		break;
}
//////////////////////////////////////////////////////////////////////
switch (Yii::$app->request->post('action')){
	case 'quick-add-more-distance-to-supplier-price':
		$f = post('f',[]);
		$supplier_id = post('supplier_id');
		$new = post('new',[]);
		
		if(!empty($new)){
			foreach ($new as $n){
				if($n['title'] != ""){
				$n['sid'] = __SID__;
				$n['type_id'] = post('controller_code');
				$f[] = Yii::$app->zii->insert('distances',$n);
				}
			}
		}
		 
		if(!empty($f)){
			foreach ($f as $n){
				//distances_to_suppliers
				if((new Query())->from('distances_to_suppliers')->where(['item_id'=>$n,'supplier_id'=>$supplier_id])->count(1) == 0){
					Yii::$app->db->createCommand()->insert('distances_to_suppliers',[
							'item_id'=>$n,'supplier_id'=>$supplier_id
					])->execute();
				}
			}
		}
		echo json_encode([
			'event'=>'hide-modal',
			'callback'=>true,
			'callback_function'=>'reloadAutoPlayFunction();'
		]);exit;
		break;
	case 'add-more-distance-to-supplier-price':
		$supplier_id = post('supplier_id',0);
		
		$item = \app\modules\admin\models\Customers::getItem($supplier_id);
		//$places = \app\modules\admin\models\Customers::getSupplierPlace($supplier_id);
		$ePlace = $eID = $eP = [];
		foreach (\app\modules\admin\models\Customers::getSupplierPlace($supplier_id) as $p){
			$ePlace[] = $p['id'];
			$eP[] = $p['name'];
		}
		foreach (\app\modules\admin\models\Cars::get_list_distance_from_price($supplier_id) as $p){
			$eID[] = $p['id'];
		}
		//
		$type_id = post('controller_code',$item['type_id']);
		 
		$distances = (new Query())->from(['a'=>'distances'])->where(['a.sid'=>__SID__,'a.is_active'=>1])
		->andWhere(['>','a.state',-1]);
		if($type_id>0) $distances->andWhere(['a.type_id'=>$type_id]);
		if(!empty($eID)){
			$distances->andWhere(['not in','a.id',$eID]);
		}
		if(!empty($ePlace)){
			$distances->andWhere(['a.id'=>(new Query())->from('distance_to_places')->where([
					'place_id'=>$ePlace
			]+($type_id >0 ? ['type_id'=>$type_id] : []))->select('distance_id')]);
		}
		$distances = $distances->orderBy(['a.title'=>SORT_ASC])->all(); 
		//
		
		$html = '';
		$html .= '<div class="form-group"><div class="col-sm-12">';
		///$html .= \app\modules\admin\models\Menu::getMenuPosition(\app\modules\admin\models\Menu::getItem(post('category_id',0))); 
		$html .= '</div></div>';
		$html .= '<div class="form-group">';
		$html .= '<div class="col-sm-12"><p class="fl100 help-block">Các chặng xe thuộc địa danh <b class="underline red">'.implode('</b> | <b class="underline red">', $eP).'</b> có thể thêm vào báo giá:</p>';
		$html .= '<select data-placeholder="Chọn chặng xe" multiple name="f[]" id="chosen-load-distances" data-place="'.implode(',', $ePlace).'" data-type_id="'.$type_id.'" data-existed="'.implode(',', $eID).'" data-index="" data-target=".ajax-result-price-distance" role="load_distances" class="form-control ajax-chosen-select-ajax">';
		if(!empty($distances)){
			foreach ($distances as $k=>$v){
				$html .= '<option value="'.$v['id'].'">'.uh($v['title']).'</option>';
			}
		}
		$html .= '</select>';
		$html .= '</div><p class="col-sm-12 help-block hide">*** Lưu ý: Danh sách chặng sẽ lấy theo địa danh đã chọn ở tab "Thông tin chung".</p></div>';
		 
		$html .= '<div class="form-group quick-addnew-form">'; 
		$html .= '<div class="col-sm-12">';
		$html .= '<label>Nếu chặng vận chuyển chưa tồn tại bạn có thể thêm nhanh tại đây:</label>';
		$html .= '<input name="new[0][title]" type="text" class="form-control" value="" placeholder="Nhập tên chặng">';
		$html .= '<input name="new[0][distance]" type="text" class="form-control inline-block mgt10 w50 ajax-number-format" value="" placeholder="Khoảng cách (km)">';
		$html .= '<input name="new[0][overnight]" type="text" class="form-control inline-block mgt10 w50 ajax-number-format" value="" placeholder="Lưu đêm">';
		$html .= '</div></div><hr/>';
		$html .= '<div class="form-group quick-addnew-form">';
		$html .= '<div class="col-sm-12">';
		
		$html .= '<input name="new[1][title]" type="text" class="form-control" value="" placeholder="Nhập tên chặng">';
		$html .= '<input name="new[1][distance]" type="text" class="form-control inline-block mgt10 w50 ajax-number-format" value="" placeholder="Khoảng cách (km)">';
		$html .= '<input name="new[1][overnight]" type="text" class="form-control inline-block mgt10 w50 ajax-number-format" value="" placeholder="Lưu đêm">';
		$html .= '</div></div>';
			
			
		//
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		echo json_encode([
				'html'=>$html,
				'callback'=>true,
				'callback_function'=>'jQuery(\'[data-toggle="tooltip"]\').tooltip();'
		]);exit;
		break;
	case 'quick_change_menu_price_currency':
		$a = [
				'item_id',
				//'season_id',
				//'weekend_id',
				//'group_id',
				'supplier_id',
				'package_id',
				'quotation_id',
				'nationality_id',
					
		];
		$con = [];
		foreach ($a as $b){
			$$b = post($b,0);
			$con[$b] = $$b;			
		}
		Yii::$app->db->createCommand()->update(Yii::$app->zii->getTablePrice(post('controller_code'),post('price_type',1)),['currency'=>post('value')],$con)->execute();
		exit;
		break;
	case 'quick_change_menus_price_default':
		$a = [
				'item_id',
				//'season_id',
				//'weekend_id',
				//'group_id',
				'supplier_id',
				'package_id',
				'quotation_id',
				'nationality_id',
					
		];
		$con = [];
		foreach ($a as $b){
			$$b = post($b,0);
			$con[$b] = $$b;			
		}
		Yii::$app->db->createCommand()->update(Yii::$app->zii->getTablePrice(post('controller_code'),post('price_type',1)),['is_default'=>0],
				[
						'package_id'=>$package_id,
						'supplier_id'=>$supplier_id,
						'quotation_id'=>$quotation_id,
						'nationality_id'=>$nationality_id
				])->execute();
		Yii::$app->db->createCommand()->update(Yii::$app->zii->getTablePrice(post('controller_code'),post('price_type',1)),['is_default'=>1],$con)->execute();
		
		switch (post('controller_code')){
			case TYPE_ID_HOTEL:
			case TYPE_ID_SHIP_HOTEL:
				Yii::$app->db->createCommand()->update('rooms_to_hotel',['is_default'=>0],
				[
						//'package_id'=>$package_id,
						'parent_id'=>$supplier_id,
						//'quotation_id'=>$quotation_id,
						//'nationality_id'=>$nationality_id
				])->execute();
				Yii::$app->db->createCommand()->update('rooms_to_hotel',['is_default'=>0],
						[
								'room_id'=>$item_id,
								'parent_id'=>$supplier_id,
								//'quotation_id'=>$quotation_id,
								//'nationality_id'=>$nationality_id
						])->execute();
				
				break;
		}
		
		exit;
		break;	
		
	case 'quick_change_menu_price':
		$a = [
			'item_id',
			'season_id',
			'weekend_id',
			'group_id',
			'supplier_id',
			'package_id',
			'quotation_id',
			'nationality_id',	
			
		];
		$con1 = [];
		foreach ($a as $b){
			$$b = post($b,0);
			$con1[$b] = $$b;
			if($$b>0){
				$con[$b] = $$b;
			}
		}		
		
		$time_id = post('time_id',-1);
		$con1['time_id'] = $time_id;
		if($time_id > -1){
			$con['time_id'] = $time_id;
		}
		//
		Yii::$app->db->createCommand()->delete('menus_to_prices',$con)->execute();
		//
		$con1['price1'] = cprice(post('value')) > 0 ? cprice(post('value')) : 0;
		$con1['currency'] = post('currency',1);
		//
		Yii::$app->db->createCommand()->insert('menus_to_prices',$con1)->execute();
		exit;
		break;
	case 'quick_change_supplier_service_price':
		$supplier_type = post('supplier_type');
		$field = post('field','price1');
		$a = [
			'item_id',
			'season_id',
			'weekend_id',
			'group_id',
			'supplier_id',
			'package_id',
			'quotation_id',
			'nationality_id',	
			//'parent_group_id'	
			
		];
		if(isset($_POST['vehicle_id'])){
			$a[] = 'vehicle_id';
		}
		if(isset($_POST['parent_group_id'])){
			$a[] = 'parent_group_id';
		}
		$con1 = [];
		foreach ($a as $b){
			$$b = post($b,0);
			if($$b > -1){
				$con1[$b] = $$b;
			}
			if($$b>-1){
				$con[$b] = $$b;
			}
		}		
		
		$time_id = post('time_id',-1);
		$con1['time_id'] = $time_id;
		if($time_id > -1){
			$con['time_id'] = $time_id;
		}
		$t = '';
		 
		$t = Yii::$app->zii->getTablePrice($supplier_type,post('price_type',1));
		//
		
		//Yii::$app->db->createCommand()->delete($t,$con)->execute();
		
		//
		$con1[$field] = cprice(post('value')) > 0 ? cprice(post('value')) : 0;
		if(isset($_POST['currency'])){
			$con1['currency'] = post('currency',1);
		}
		//
		//echo (new Query())->from($t)->where($con)->createCommand()->getRawSql();exit; 
		
		if((new Query())->from($t)->where($con)->count(1) == 0){
			 
			Yii::$app->db->createCommand()->insert($t,$con1)->execute();	
			echo $t;
			echo json_encode($con);
			echo json_encode($con1);
		}else{
			 
			if($field == 'price2'){
				$con1 = ['price2'=>cprice(post('value')) > 0 ? cprice(post('value')) : 0]; 
			}
			foreach ($con as $k=>$v){
				unset($con1[$k]);
			}
			echo $t;
			echo json_encode($con); 
			echo json_encode($con1);
			//exit;
			echo Yii::$app->db->createCommand()->update($t,$con1,$con)->execute();
		}
		exit;
		break;	
		
	case 'quick-quick_change_category_position': 
		$pos = post('pos');
    	Yii::$app->db->createCommand()->delete('{{%items_to_posiotion}}',['item_id'=>post('category_id',0)])->execute();
    	 
    	if(!empty($pos)){
    		foreach ($pos as $p=>$v){
    			Yii::$app->db->createCommand()->insert('{{%items_to_posiotion}}',[
    					'position_id'=>$p,
    					'item_id'=>post('category_id',0),
    					'type'=>0
    			])->execute();    			
    		}
    	}
		$xP = implode(' | ', Menu::getPosition(post('category_id',0),0));
		$html = '<a href="#" data-category_id="'.post('category_id',0).'" data-action="quick_change_category_position" class="cate-pos-'.post('category_id',0).'" onclick="open_ajax_modal(this);return false;" data-title="Chỉnh sửa vị trí hiển thị danh mục">'.($xP != "" ? $xP : '-').'</a>';
		echo json_encode([
			'event'=>'hide-modal', 
			'callback'=>true,
			'callback_function'=>'jQuery(\'.cate-pos-'.post('category_id',0).'\').replaceWith(\''.$html.'\');'
		]);exit;
	break;
	case 'quick_change_category_position':
		$html = '';
		$html .= '<div class="form-group"><div class="col-sm-12">'; 
		$html .= \app\modules\admin\models\Menu::getMenuPosition(\app\modules\admin\models\Menu::getItem(post('category_id',0))); 
		$html .= '</div></div>';
		
		
		 
			
		//
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		echo json_encode([
			'html'=>$html,
			'callback'=>true,
			'callback_function'=>'jQuery(\'[data-toggle="tooltip"]\').tooltip();'
		]);exit;
		break;
	case 'quick-add-more-position':
		$new = post('new',[]);
		if(!empty($new) && Yii::$app->user->can([ROOT_USER])){
			foreach ($new as $v){
				if($v['name'] != "" && $v['title'] != "" && (new Query())->from('{{%positions}}')->where(['name'=>$v['name'],'type'=>$v['type']])->count(1) == 0){
					$v['sid'] = __SID__;
					Yii::$app->db->createCommand()->insert('{{%positions}}',$v)->execute();
					Yii::$app->db->createCommand()->insert('positions_to_users',[
							'position_id'=>$v['name'],
							'user_id'=>__SID__,
							'type_id'=>$v['type']
					])->execute();
				}elseif ($v['name'] != "" && $v['title'] != ""){
					Yii::$app->db->createCommand()->insert('positions_to_users',[
							'position_id'=>$v['name'],
							'user_id'=>__SID__,
							'type_id'=>$v['type'],
							'position'=>1
					])->execute();
				}
			}
		}
		echo json_encode([
				'event'=>'hide-modal'
		]);exit;
		break;
	case 'add-more-position':			
		$html = ''; 
 
		$html .= '<div class="form-group"><table class="table table-bordered vmiddle"><thead><tr><th class="center w150p">Code</th><th class="center">Tiêu đề</th></tr></thead><tbody>';
		 
		for($i=0; $i<3;$i++){
		
			$html .= '<tr><input type="hidden" name="new['.$i.'][type]" value="'.post('type',0).'"/>
    		<td class="pr"><input onblur="check_input_required(this);" type="text" class="sui-input w100 form-control input-sm input-condition-required input-destination-required" value="" name="new['.$i.'][name]" placeholder=""/></td>
    		<td>
    		<input onblur="check_input_required(this);" type="text" class="sui-input w100 form-control input-sm input-condition-required input-destination-required" value="" name="new['.$i.'][title]" placeholder="">
    		</td>';
			$html .= '</tr>';			
		
		}
		$html .= '</tbody></table></div>';
		
		
		$html .= '<div class="group-sm34"><p>Danh sách đối tượng đã thêm</p>';
		$html .= '<table class="table vmiddle table-hover table-bordered">';
		$html .= '<thead><tr>
    				<th class="center">Code</th>
					<th class="center">Tiêu đề</th>				 
    				<th class="coption"></th>
    				</tr></thead>';
		$html .= '<tbody class="">';
		
		$l = \app\modules\admin\models\Menu::_getMenuPosition(0);
		if(!empty($l) && Yii::$app->user->can([ROOT_USER])){
			$role = [
					'type'=>'single',
					'table'=>'positions',
					//'controller'=>Yii::$app->controller->id,
					'action'=>'Ad_quick_change_item'
			];
			foreach ($l as $k=>$v){
				$role['id']=$v['id'];
				$role['action'] = 'Ad_quick_change_item';
				$html .= '<tr class="tr-item-odr-'.$v['name'].'">'.Ad_list_show_qtext_field($v,[
						'field'=>'name',
						'class'=>'aleft',
						'decimal'=>0,
						'role'=>$role
				]).'
    	 '.Ad_list_show_qtext_field($v,[
						'field'=>'title',
						'class'=>'aleft',
						'decimal'=>0,
						'role'=>$role
				]).'
    	 
    				<td class="center pr">
    						 
    						<a data-modal-target=".mymodal1" data-trash="0" data-action="open-confirm-dialog" data-title="Xác nhận xóa package !" data-class="modal-sm" data-confirm-action="quick_delete_position_user" data-position_id="'.$v['name'].'" data-type="'.$v['type'].'" onclick="return open_ajax_modal(this);" class="btn btn-link delete_item icon" data-toggle="tooltip" data-placement="top" title="Toàn bộ dữ liệu đã nhập liên quan đến bản ghi này sẽ bị xóa.">Xóa</a>
    						</td>';
		    	$html .= '</tr>';
			}}else{
				  
			}
		
			$html .= '</tbody></table>';
			$html .= '</div>';
			
		//
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
	
		echo json_encode([
			'html'=>$html,
			'callback'=>true,
			'callback_function'=>'jQuery(\'[data-toggle="tooltip"]\').tooltip();'
		]);exit;
	
		break;
	case 'quick-add-more-quotation-price-to-supplier':
		//
		$supplier_id = post('supplier_id',0);
		$child_id = post('child_id',[]);
		$new = post('new',[]);
		if(!empty($new)){
			foreach ($new as $k=>$v){
				if(trim($v['title']) != ""){
					$v['sid'] = __SID__;
					$v['supplier_id'] = $supplier_id;
					$v['from_date'] = ctime(['string'=>$v['from_date']]);
					$v['to_date'] = ctime(['string'=>$v['to_date']]);
					$v['to_date'] = date('Y-m-d 23:59:59',strtotime($v['to_date']));
					$quotation_id = Yii::$app->zii->insert('supplier_quotations',$v);
					$child_id[] = $quotation_id;
					 
				}
			}
		}
		if(!empty($child_id)){
			foreach ($child_id as $package_id){
				Yii::$app->db->createCommand()->insert('supplier_quotations_to_supplier',[
						'supplier_id'=>$supplier_id,
						'quotation_id'=>$package_id
				])->execute();
			}
		}
		//
		if(post('update_quotation') == 'on'){
			$controller_code = post('controller_code',post('type_id'));
			switch ($controller_code){
				case TYPE_ID_VECL: case TYPE_ID_GUIDES:
					$incurred_prices = \app\modules\admin\models\Customers::getSupplierQuotations($supplier_id);
					if(!empty($incurred_prices)){
						foreach ($incurred_prices as $k=>$v){
							Yii::$app->db->createCommand()->update(Yii::$app->zii->getTablePrice($controller_code,post('price_type',1)),['quotation_id'=>$v['id']],[
									'supplier_id'=>$supplier_id,
									'quotation_id'=>0
							])->execute();
							break;
						}
					}
					break;
			}
		}
		echo json_encode([
				'event'=>'hide-modal',
				'callback'=>true,
	
				'callback_function'=>'reloadAutoPlayFunction();',
				'p'=>$_POST
		]);exit;
		break;
	case 'add-more-quotation-price-to-supplier':
		 
		$supplier_id = post('supplier_id',0);
		$html = '';
		//$m = new app\modules\admin\models\PackagePrices();
		//$existed = post('existed',[]);
		//view($existed,true);
		//$l4 = $m->getList(['not_in'=>$existed]) ;
		$html .= '<div class="form-group hide">';
		$html .= '<div class="group-sm34 col-sm-12"><select data-placeholder="Chọn 1 hoặc nhiều báo giá đã có" name="child_id[]" multiple data-role="chosen-load-package" class="form-control ajax-chosen-select-ajax" style="width:100%">';
		//if(!empty($l4['listItem'])){
		foreach (\app\modules\admin\models\Customers::getAvailabledQuotations($supplier_id) as $k4=>$v4){
	
			$html .= '<option value="'.$v4['id'].'">'.$v4['title'].' </option>';
	
		}
		//}
		
			
		$html .= '</select></div>';
		$html .= '</div>
				<p class="help-block italic hide">*** Bạn có thể chọn giá trị có sẵn hoặc thêm mới ở ô nhập bên dưới.</p>
				';
		$html .= '<div class="">';
			
		$html .= '<div class="group-sm34"> ';
		$html .= '<table class="table vmiddle table-hover table-bordered"><thead><tr>
    				<th center>Tiêu đề</th>				 
					<th class="center">Thời gian áp dụng</th>
    				<th class="center">Thời gian kết thúc</th> 
    				</tr></thead>';
		$html .= '<tbody class="">';
	
		for($i=0; $i<3;$i++){
	
			$html .= '<tr>
    				<td class="pr"><input onblur="check_input_required(this);" type="text" class="sui-input w100 form-control input-sm input-condition-required input-destination-required" value="" name="new['.$i.'][title]" placeholder="Tiêu đề"/></td>
    		<td>
    						<input data-format="DD/MM/YYYY" onblur="check_input_required(this);" type="text" class="sui-input w100 form-control input-sm ajax-datepicker input-condition-required input-destination-required" value="" name="new['.$i.'][from_date]" placeholder="Thời gian bắt đầu">
    						</td> 	
    						<td>
    						<input data-format="DD/MM/YYYY" onblur="check_input_required(this);" type="text" class="sui-input w100 form-control input-sm ajax-datepicker input-condition-required input-destination-required" value="" name="new['.$i.'][to_date]" placeholder="Thời gian kết thúc">
    						</td> 			
    						';
			$html .= '</tr>';
	
	
	
		}
			
		$html .= '</tbody></table>';
		$html .= '</div>';
	
	
		$html .= '<div class="group-sm34"><p class="f12e underline">Danh sách các báo giá đã thêm:</p>';
		$html .= '<table class="table vmiddle table-hover table-bordered">';
		$html .= '<thead><tr>
    				<th class="center">Tiêu đề</th>				 
					<th class="center">Thời gian áp dụng</th>
				<th class="center">Thời gian kết thúc</th>
    				<th class="coption center">Trạng thái</th><th class="coption"></th>
    				</tr></thead>';
		$html .= '<tbody class="">';
	
		$l = \app\modules\admin\models\Customers::getSupplierQuotations($supplier_id);
		
		 
		if(!empty($l)){
			$role = [
					'type'=>'single',
					'table'=>'supplier_quotations',
					//'controller'=>Yii::$app->controller->id,
					'action'=>'Ad_quick_change_item'
			];
			foreach ($l as $k=>$v){
				$role['id']=$v['id'];
				$role['action'] = 'Ad_quick_change_item';
				$html .= '<tr class="tr-item-odr-'.$supplier_id.'-'.$v['id'].'">'.Ad_list_show_qtext_field($v,[
						'field'=>'title',
						'class'=>'  aleft',
						'decimal'=>0,
						'role'=>$role 
				]).'
    	'.Ad_list_show_qtext_field($v,[
						'field'=>'from_date',
    					'value'=>date("d/m/Y H:i:s",strtotime($v['from_date'])),
						'class'=>'input-sm ajax-datetimepicker',
						//'decimal'=>0,
    			'readonly'=>true,
						'role'=>$role + ['field_type'=>'date']
				]).'
    	'.Ad_list_show_qtext_field($v,[
						'field'=>'to_date',
    					'value'=>date("d/m/Y H:i:s",strtotime($v['to_date'])),
						'class'=>'input-sm ajax-datetimepicker',
						//'decimal'=>0,
						'readonly'=>true,
						'role'=>$role + ['field_type'=>'date']
				]). Ad_list_show_checkbox_field($v,[
        		'field'=>'is_active',
        		'class'=>'ajax-switch-btn switchBtn ',
        		//'decimal'=>0,
        		'role'=>$role
		]).'
    				<td class="center pr">
    						 
    						<a data-modal-target=".mymodal1" data-trash="0" data-action="open-confirm-dialog" data-title="Xác nhận xóa dữ liệu !" data-class="modal-sm" data-confirm-action="quick_delete_quotation_supplier" data-quotation_id="'.$v['id'].'" data-supplier_id="'.$supplier_id.'" onclick="return open_ajax_modal(this);" class="btn btn-link delete_item icon" data-toggle="tooltip" data-placement="top" title="Toàn bộ dữ liệu đã nhập cho báo giá này sẽ bị xóa. Lưu ý: không thể phục hồi dữ liệu đã xóa.">Xóa</a>
    						</td>';
				$html .= '</tr>';
			}}else{
				
				 
					$html .= '<tr><td colspan="5"><p><b class="red ">Bạn chưa sử dụng báo giá nào.</b></p>
					
						<p class="help-block italic red "><b class="underline">Lưu ý: </b> Khi cài đặt báo giá, các đơn giá (đã nhập trước đó) mà không thuộc 1 báo giá nào sẽ bị xóa.</p></td></tr>';
					
				 
			}
			
			$c = (new Query())->from(Yii::$app->zii->getTablePrice(post('controller_code'),post('price_type',1)))->where([
					'quotation_id'=>0,
					'supplier_id'=>$supplier_id
			])->count(1);
			//	exit;
			if($c>0){
				$html .= '<tr><td colspan="5"><p><b class="red ">Opp! Chúng tôi nhận thấy rằng có <span class="underline">'.number_format($c).'</span> đơn giá đã nhập cho đơn vị này nhưng chưa nằm trong báo giá nào.</b></p>
				
					<p class="bold green">Bạn có muốn cập nhật vào báo giá [đầu tiên] trong danh sách thêm mới bên trên không ?</p>
					<label><input name="update_quotation" type="checkbox"/> Cập nhật ngay</label>
							<p class="help-block italic red "><b class="underline">Lưu ý: </b> Khi cài đặt báo giá, các đơn giá (đã nhập trước đó) mà không thuộc 1 báo giá nào sẽ bị xóa.</p></td></tr>';
			}
			$html .= '</tbody></table>';
			$html .= '</div>';
	
			$html .= '</div>';
			//
			$html .= '<div class="modal-footer">';
			$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
			$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
			$html .= '</div>';
			$_POST['action'] = 'quick-' . $_POST['action'];
			foreach ($_POST as $k=>$v){
				$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
			}
			///
	
			echo json_encode([
					'html'=>$html,
					'callback'=>true,
					'callback_function'=>'jQuery(\'[data-toggle="tooltip"]\').tooltip();reload_app(\'switch-btn\');'
			]);exit;
	
			break;
	case 'quick-add-more-vehicle-to-supplier':
		$supplier_id = post('supplier_id'); $type_id = post('type_id');
		$new = post('new',[]);$new1 = post('new1',[]);
		$f = post('f',[]);
		
		if(!empty($new)){
			foreach ($new as $k=>$v){
				//
				if(trim($v['title'])!=""){
					//$v['type_id'] = $type_id;
					$v['sid'] = __SID__;
					$v['type'] = 3;
					$quantity = $v['quantity']; unset($v['quantity']);
					$v['id'] = \app\modules\admin\models\VehiclesCategorys::getID();
					Yii::$app->db->createCommand()->insert('vehicles_categorys',$v)->execute();
					$v['type'] = 1;
					$v['pmax'] = $new1[$k]['pmax'];
					$v['pmin'] = $new[$k]['pmin'];
					Yii::$app->zii->insert('vehicles_categorys',$v);
					$f[$v['id']]['quantity'] = $quantity;
				}
			}
		} 
		$pxxx = [];
		if(!empty($f)){
			foreach ($f as $vehicle_id => $v){ 
				if($v['quantity']>0){ 
					$quantity = $v['quantity'];				
					if((new Query())->from('vehicles_to_cars')->where([
							'vehicle_id'=>$vehicle_id,
							'parent_id'=>$supplier_id
					])->count(1)==0){
						Yii::$app->db->createCommand()->insert('vehicles_to_cars',[
								'vehicle_id'=>$vehicle_id,
								'parent_id'=>$supplier_id,
								'quantity'=>$quantity
						])->execute() ;
					}
				}
			}
		}
		echo json_encode([
			'event'=>'hide-modal',
			'callback'=>true,
				'p'=>$pxxx,
			'callback_function'=>'console.log(data);reloadAutoPlayFunction();'
		]);exit;
		break;
	case 'quick-add-more-vehicle-to-supplier-price':
		$f = post('f',[]);
		$supplier_id = post('supplier_id',0); 
		$parent_group_id = \app\modules\admin\models\Cars::getParentGroupID();
		$parent_group_id > 1 ? $parent_group_id : 1;
		// Lấy ds báo giá
		$quotations = \app\modules\admin\models\Customers::getSupplierQuotations($supplier_id,[
				'order_by'=>['a.to_date'=>SORT_DESC,'a.title'=>SORT_ASC],
				'is_active'=>1
		]);
		// Lay package
		$packages = \app\modules\admin\models\PackagePrices::getPackages($supplier_id);
		// Lay nhom quoc tich
		$nationalitys = \app\modules\admin\models\NationalityGroups::get_supplier_group($supplier_id,true);
		//
		if(empty($nationalitys)){
			$nationalitys = [
				['id'=>0,'title'=>'Mặc định']	
			];
		}
		
		if(!empty($quotations)){
			foreach ($quotations as $quotation){
				foreach ($packages as $package){
					foreach ($nationalitys as $nationality){
						foreach ($f as $id){
							Yii::$app->db->createCommand()->insert(Yii::$app->zii->getTablePrice($controller_code,post('price_type',1)),[
									'parent_group_id'=>$parent_group_id,
									'item_id'=>$id,
									'supplier_id'=>$supplier_id,
									'nationality_id'=>$nationality['id'],
									'quotation_id'=>$quotation['id'],
									'package_id'=>$package['id'],
									'pmax'=>9999
									
							])->execute();
						}
					}
				}
			}
		}
		
		echo json_encode([
				'event'=>'hide-modal',
				'callback'=>true,
		
				'callback_function'=>'console.log(data);reloadAutoPlayFunction();'
		]);exit;
		break;
		break;
	case 'add-more-vehicle-to-supplier-price':
		$html = ''; $supplier_id = post('supplier_id',0);
		//$html .= '<div class="form-group">';
		//$html += '<label class="control-label col-sm-2" for="inputLoaithu">Kỳ nộp</label>';
		//$html .= '<div class="col-sm-12">';
		//$html .= '<select data-id="'.$supplier_id.'" data-supplier_id="'.$supplier_id.'" id="select-chon-xe" data-type="'.(post('type_id')).'" data-type_id="'.(post('type_id')).'" data-existed="'.(post('existed')).'" data-target="#bang_list_chon_xe" onchange="get_list_vehicles_makers(this);"  data-role="select_vehicles_makers" class="form-control chosen-select"><option value="0">-- Hãng phương tiện --</option>';
		//	foreach (\app\modules\admin\models\VehiclesMakers::getAll(['type_id'=>post('type_id')]) as $k1=>$v1){
		//		$html .= '<option value="'.$v1['id'].'">'.uh($v1['title']).'</option>';
		//	}
				
		//$html .= '</select></div></div>';
		
		$html .= '<div class="form-group"  style="margin-bottom:0">';
		 
		$html .= '<div class="col-sm-12 ">';
		$html .= '<table class="table table-hover table-bordered vmiddle table-striped  " style="margin-bottom:0"> <thead><tr> 
				<th rowspan="2" class="w50p">#</th> <th rowspan="2">Tên phương tiện</th>
		<th rowspan="2" style="width:150px"></th>
		
		<th class="center w100p">Chọn</th>
		  
	
		</tr>
		</thead></table>';
		$html .= '<div class="div-slim-scroll" data-height="260"><table class="table table-hover table-bordered vmiddle table-striped"><tbody id="bang_list_chon_xe" class="ajax-result-after-insert ">'; 
		 
		$listVehicles = \app\modules\admin\models\Cars::getListCars($supplier_id);
		if(!empty($listVehicles)){
			foreach ($listVehicles as $k1=>$v1){
				$html .= '<tr><td class="w50p">'.($k1+1).'</td>
						<td >'.uh($v1['title']).'</td>
						<td style="width:150px"></td>
						<td class="w100p center"><input type="checkbox" name="f[]" value="'.$v1['id'].'" class="">
    								 
    								</td></tr>';
			}
		}
		$html .= '</tbody></table></div><div class="show_error_out"></div>';
		//$html .= '<p class="help-block">Nếu chưa có trong danh sách phương tiện có thể thêm mới tại ô dưới đây</p>';
		//$html .= '<p class="help-block">Nếu chưa có trong danh sách phương tiện <a data-type_id="'+($this.attr('data-type_id'))+'" class="bold pointer" onclick="quick_add_more_vehicle_category(this);">click vào đây</a> để thêm mới.</p>';
		$html .= '</div> ';
		$html .= '</div>';
	 
		
		///////////////////////////////////////////
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-success"><i class="fa-plus fa"></i> Thêm vào bảng giá</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		
		echo json_encode([
				'event'=>'hide-modal', 
				'html'=>$html,
				'callback'=>true,
				'callback_function'=>'reload_app(\'chosen\');loadScrollDiv();'
				
		]);exit;
		break;	
		
	case 'add-more-vehicle-to-supplier':
		$html = ''; $supplier_id = post('supplier_id',0);
		$html .= '<div class="form-group">';
		//$html += '<label class="control-label col-sm-2" for="inputLoaithu">Kỳ nộp</label>';
		$html .= '<div class="col-sm-12">';
		$html .= '<select data-id="'.$supplier_id.'" data-supplier_id="'.$supplier_id.'" id="select-chon-xe" data-type="'.(post('type_id')).'" data-type_id="'.(post('type_id')).'" data-existed="'.(post('existed')).'" data-target="#bang_list_chon_xe" onchange="get_list_vehicles_makers(this);"  data-role="select_vehicles_makers" class="form-control chosen-select"><option value="0">-- Hãng phương tiện --</option>';
			foreach (\app\modules\admin\models\VehiclesMakers::getAll(['type_id'=>post('type_id')]) as $k1=>$v1){
				$html .= '<option value="'.$v1['id'].'">'.uh($v1['title']).'</option>';
			}
				
		$html .= '</select></div></div>';
		
		$html .= '<div class="form-group"  style="margin-bottom:0">';
		 
		$html .= '<div class="col-sm-12 ">';
		$html .= '<table class="table table-hover table-bordered vmiddle table-striped  " style="margin-bottom:0"> <thead><tr> 
				<th rowspan="2" class="w50p">#</th> <th rowspan="2">Tên phương tiện</th>
		<th rowspan="2" style="width:150px">Hãng</th>
		
		<th class="center w100p">Số lượng</th>
		  
	
		</tr>
		</thead></table>';
		$html .= '<div class="div-slim-scroll" data-height="260"><table class="table table-hover table-bordered vmiddle table-striped"><tbody id="bang_list_chon_xe" class="ajax-result-after-insert ">'; 
		$listVehicles = \app\modules\admin\models\VehiclesCategorys::getAvailableVehicle([
				'limit'=>1000,
				'type_id'=>post('type_id'),
				'supplier_id'=>$supplier_id
		]);
		if(!empty($listVehicles)){
			foreach ($listVehicles as $k1=>$v1){
				$html .= '<tr><td class="w50p">'.($k1+1).'</td>
						<td >'.uh($v1['title']).'</td>
						<td style="width:150px">'.uh($v1['maker_name']).'</td>
						<td class="w100p"><input type="text" name="f['.$v1['id'].'][quantity]" value="" class="form-control center input-sm ajax-number-format">
    								 
    								</td></tr>';
			}
		}
		$html .= '</tbody></table></div><div class="show_error_out"></div>';
		$html .= '<p class="help-block">Nếu chưa có trong danh sách phương tiện có thể thêm mới tại ô dưới đây</p>';
		//$html .= '<p class="help-block">Nếu chưa có trong danh sách phương tiện <a data-type_id="'+($this.attr('data-type_id'))+'" class="bold pointer" onclick="quick_add_more_vehicle_category(this);">click vào đây</a> để thêm mới.</p>';
		$html .= '</div> ';
		$html .= '</div>';
		$html .= '<div class="form-group"><div class="col-sm-12">';
		$html .= '<table class="table table-hover table-bordered vmiddle table-striped"> <thead><tr>
				<th class="center mw100p" rowspan="2">Hãng / Loại</th>
				<th class="center " rowspan="2">Tên phương tiện</th>
				<th class="center mw100p" rowspan="2">Số ghế ngồi</th>
				<th class="center" colspan="2">Khách Inbound</th>
				<th class="center" colspan="2">Khách Nội Địa</th>
				<th class="center mw100p" rowspan="2">Số lượng</th>
				</tr>
				<tr><th class="center mw100p">Tối thiểu</th><th class="center mw100p">Tối đa</th><th class="center mw100p">Tối thiểu</th><th class="center mw100p">Tối đa</th></tr></thead><tbody>';
 
		for($i=0; $i<3; $i++){ 
		$html .= '<tr><td>';
		$html .= '<select data-id="'.$supplier_id.'" data-supplier_id="'.$supplier_id.'"  data-role="select_vehicles_makers" name="new['.$i.'][maker_id]" class="form-control chosen-select">';
		foreach (\app\modules\admin\models\VehiclesMakers::getAll(['type_id'=>post('type_id')]) as $k1=>$v1){
			$html .= '<option value="'.$v1['id'].'">'.uh($v1['title']).'</option>';
		}
		
		$html .= '</select></td>
				  <td class="center"><input onblur="check_input_required(this);" type="text" name="new['.$i.'][title]" value="" class="form-control input-sm input-condition-required input-destination-required inline-block"/></td>
				  <td class="center"><input onblur="check_input_required(this);" type="text" name="new['.$i.'][seats]" value="" class="form-control input-sm center ajax-number-format input-condition-required input-destination-required inline-block"/></td>';
		$html .= '<td class="center"><input onblur="check_input_required(this);" type="text" name="new1['.$i.'][pmin]" value="" class="form-control input-sm center ajax-number-format input-condition-required input-destination-required inline-block"/></td>';
		$html .= '<td class="center"><input onblur="check_input_required(this);" type="text" name="new1['.$i.'][pmax]" value="" class="form-control input-sm center ajax-number-format input-condition-required input-destination-required inline-block"/></td>';
		$html .= '<td class="center"><input onblur="check_input_required(this);" type="text" name="new['.$i.'][pmin]" value="" class="form-control input-sm center ajax-number-format input-condition-required input-destination-required inline-block"/></td>';
		$html .= '<td class="center"><input onblur="check_input_required(this);" type="text" name="new['.$i.'][pmax]" value="" class="form-control input-sm center ajax-number-format input-condition-required input-destination-required inline-block"/></td>';
		$html .= '<td class="center"><input onblur="check_input_required(this);" type="text" name="new['.$i.'][quantity]" value="" class="form-control input-sm center ajax-number-format input-condition-required input-destination-required inline-block"/></td>';
		$html .= '</tr>';
		}
		$html .= '</tbody></table>';
		$html .= '</div></div>';
		
		///////////////////////////////////////////
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		
		echo json_encode([
				'event'=>'hide-modal', 
				'html'=>$html,
				'callback'=>true,
				'callback_function'=>'reload_app(\'chosen\');loadScrollDiv();'
				
		]);exit;
		break;
	case 'loadSupplierListVehicle':
		$html = ''; $supplier_id = post('id');
		
		
		
		$lx = \app\modules\admin\models\Cars::getListCars($supplier_id);
		$html .= ' 
       <table class=" table table-hover table-bordered vmiddle table-striped"> <thead>
		<tr> <th rowspan="2">#</th> <th rowspan="2">Tên phương tiện</th>
		<th rowspan="2" style="min-width:150px">Hãng</th>
		 
		<th class="center w100p">Số lượng</th>
		 
		<th class="center">Kích hoạt</th>
		<th class="center" title="">Mặc định (tính giá)</th>
		<th class="center"></th></tr> 
		</thead> <tbody> ';
       $itype = 'text' ;
       $existed_id =array();
       if(!empty($lx)){
       	$l2 = \app\modules\admin\models\Cars::get_vehicles_makers();
       	
       foreach ($lx as $k1=>$v1){
       	$existed_id[] = $v1['id'];
       	$v1['is_active'] = isset($v1['is_active']) ? $v1['is_active'] : 0;
       	 
       	$html .= '<tr> <th scope="row">'.($k1+1).'</th> 
		 <td>'.$v1['title'].'</td> <td>'.$v1['maker_title'].'</td> 
       	 
		<td class="center"><input data-field="quantity" data-supplier_id="'.$supplier_id.'" data-vehicle_id="'.$v1['id'].'" onblur="quick_change_supplier_list_vehicle(this);" type="'.$itype.'" name="c['.$k1.'][quantity]" value="'.(isset($v1['quantity']) ? $v1['quantity'] : 0).'" class="form-control input-sm center numberFormat mw100p inline-block"/></td>
 		 
		<td class="center">'.getCheckBox(array(
            'name'=>'c['.$k1.'][is_active]',
            'value'=>$v1['is_active'],
            'type'=>'singer',
            'class'=>'switchBtn ajax-switch-btn',
			'attrs'=>[
					'data-field'=>"is_active", 'data-supplier_id'=>$supplier_id,
					'data-vehicle_id'=>$v1['id'],
					'onchange'=>"setCheckboxBool(this);quick_change_supplier_list_vehicle(this);", 
			]	
             
        )).'
       			</td>
		<td class="center">
		<input data-field="is_default" data-supplier_id="'.$supplier_id.'" data-vehicle_id="'.$v1['id'].'" onchange="quick_change_supplier_list_vehicle(this);"  type="radio" value="'.$v1['id'].'" '.($v1['is_default'] == 1 ? 'checked' : '').' name="ckc_default['.$v1['seats'].']"/>		
				</td>
 		<td class="center"><input type="hidden" name="c['.$k1.'][id]" value="'.$v1['id'].'"/><input type="hidden" name="existed[]" value="'.$v1['id'].'"/><i data-class="modal-sm" data-supplier_id="'.$supplier_id.'" data-vehicle_id="'.$v1['id'].'" title="Xóa" data-title="Xác nhận xóa ?" data-action="open-confirm-dialog" data-confirm-action="delete-supplier-vehicle" class="glyphicon glyphicon-trash pointer" data-name="delete_car_id" onclick="open_ajax_modal(this);"></i></td>         
        </tr> ';
       }}
       $html .= '<tr class="ajax-html-result-before-list-vehicles"><td colspan="7" class=" aright "><button data-supplier_id="'.$supplier_id.'" data-action="add-more-vehicle-to-supplier" data-class="w80" data-type_id="'.post('code').'" data-existed="" data-quantity="1" data-count="'.count($lx).'" data-title="Thêm phương tiện" onclick="open_ajax_modal(this);" data-name="c" title="Thêm phương tiện" type="button" class="btn btn-success btn-sm btn-add-more"><i class="glyphicon glyphicon-plus"></i> Thêm phương tiện</button></td></tr>'; 
       $html .= '</tbody>
                    
        </table>  
                     
        ';
		echo json_encode([
			'html'=>$html,
			'callback'=>true,
			'target'=>post('target'),
			'callback_function'=>'reload_app(\'switch-btn\');',	
		]);exit;
		break;
	case 'loadSupplierHotelPrice1': 
		
		
		echo json_encode([
			'html'=>getSupplierPricesList(post('supplier_id',0)),
			//'callback'=>true,
			//'callback_function'=>'console.log(data)', 
		]+$_POST);exit;
		
		 
		break;	
	case 'loadSupplierVehiclePrices': 
		
		
		echo json_encode([
			'html'=>getSupplierVehiclePrices(post('supplier_id',0)),
			//'callback'=>true,
			//'callback_function'=>'console.log(data)', 
		]+$_POST);exit;
		
		 
		break;		
		
	case 'loadSupplierVehiclePrices2': 
		
		
		echo json_encode([
			'html'=>getSupplierVehiclePrices2(post('supplier_id',0)),
			//'callback'=>true,
			//'callback_function'=>'console.log(data)', 
		]+$_POST);exit;
		
		 
		break;		
			
	case 'loadSupplierHotelPrice':
		$html = ''; $supplier_id = post('id',0);
		// Lay package
		$packages = \app\modules\admin\models\PackagePrices::getPackages($supplier_id);
		// Lay nhom quoc tich
		$nationalitys = \app\modules\admin\models\NationalityGroups::get_supplier_group($supplier_id);
		// Lay mua co tinh gia truc tiep
		$incurred_prices_list = \app\modules\admin\models\Customers::getCustomerSeasons($supplier_id,[
				'price_type'=>[0]
		]);
		// Lay danh sach cuoi tuan ngay thuong tinh gia truc tiep
		$incurred_prices_weekend_list = \app\modules\admin\models\Customers::getCustomerWeekend([
				'price_type'=>[0],
				'supplier_id'=>$supplier_id
		]);
		$l3 = \app\modules\admin\models\Hotels::getListRooms($supplier_id);
		// Lay nhom phong
		$room_groups = \app\modules\admin\models\Seasons::get_rooms_groups($supplier_id);
		//$html .= json_encode($nationalitys); 
		$setting_room = false;
		if(!empty($packages)){
		foreach ($packages as $package){
			if(!empty($nationalitys)){
				foreach ($nationalitys as $kb=>$vb){
					//
					$html .= '<div class="col-sm-12 "><div class="row"><p class="grid-sui-pheader bold aleft"><i style="font-weight: normal;">Bảng giá ';
					if($package['id']>0){
						$html .= '<b class="italic green underline">' .$package['title'] .'</b> ';
					}
					$html .= ' - áp dụng cho <b class="italic underline">' .$vb['title'] .'</b> ';
					$html .= '</i></p></div></div>';
					//////////////////////////////////
					$html .= '<div class="fl100 pr auto_height_price_list" >
       	<table class="table table-prices table-hover table-bordered vmiddle table-striped"> <thead> 
      	<tr><th rowspan="3" style="min-width:200px">Tiêu đề</th>		 
		<th rowspan="3" class="center cposition" style="min-width:110px">Giá niêm yết</th>';
       
        
         
		if(!empty($incurred_prices_list)){
			foreach ($incurred_prices_list as $k=> $in){
				$price_type = isset($in['supplier']['price_type']) ? $in['supplier']['price_type'] : 0;
				if(in_array($price_type, [0]) && !in_array($in['type_id'],[3,4])){
				$html .= '<th colspan="'.(count($room_groups) * (!empty($incurred_prices_weekend_list) ? count($incurred_prices_weekend_list) : 1)).'" class="center ">' . $in['title'] . '</th>';
				}
			}
		}
		$html .= '<th rowspan="3" class="center w80p">Tiền tệ</th></tr>';
		$html .= '<tr>';
		if(!empty($incurred_prices_list)){
			foreach ($incurred_prices_list as $k=> $in){
				$price_type = isset($in['supplier']['price_type']) ? $in['supplier']['price_type'] : 0;
		if(in_array($price_type, [0]) && !in_array($in['type_id'],[3,4]) && !empty($room_groups)){
			foreach ($room_groups as $room){
				if($room['id'] == 0) $setting_room = true;
				$html .= '<th colspan="'.count($incurred_prices_weekend_list).'" class="center"><a data-class="w70" data-parent_id="'.($supplier_id).'" data-supplier_id="'.($supplier_id).'" data-id="'.$room['id'].'" data-action="add-more-room-group" data-title="Thiết lập nhóm phòng" onclick="open_ajax_modal(this);" class="pointer hover_underline">'.(($room['id'] == 0 ? '<span class="red">' : ''). $room['title'].($room['note'] != "" ? '<p><i class="f11p font-normal">('.$room['note'].')</i></p>' : '').($room['id'] == 0 ? '</span>' : '')).'</a></th>';
			}}
			}}
			$html .= '</tr>';
	 	
	 	if(!empty($incurred_prices_list)){
	 		foreach ($incurred_prices_list as $k=> $in){
	 			$price_type = isset($in['supplier']['price_type']) ? $in['supplier']['price_type'] : 0;
	 			if(in_array($price_type, [0]) && !in_array($in['type_id'],[3,4]) && !empty($room_groups)){
	 				foreach ($room_groups as $room){
	 					if(!empty($incurred_prices_weekend_list)){
	 						$class = count($incurred_prices_weekend_list) == 1 ? 'hide' : '';
	 						foreach ($incurred_prices_weekend_list as $w){
	 							//$price_type = isset($w['supplier']['price_type']) ? $w['supplier']['price_type'] : 0;
	 							//if(in_array($price_type, [0]))
	 							$html .= '<th colspan="1" class="center '.$class.'" style="min-width:110px"><p data-class="w70" class="pointer" title="Xem ở mục \'Cài đặt cuối tuần\'">'.$w['title'].'</p></th>';
	 						}
	 					}
	 					
	 				}}
	 		}}
	 		$html .= '</tr>';
		 
	 		$html .= '</thead> <tbody class="rstgs_'.cbool($setting_room).'"> ';
       $itype = 'text' ; 
      //  view($setting_room);
       
       if(!empty($l3) && !$setting_room ){ 
       foreach ($l3 as $k1=>$v1){
       	//$v1['is_active'] = isset($v1['is_active']) ? $v1['is_active'] : 0;
       	
       	$p = \app\modules\admin\models\Hotels::get_price($v1['id'],$supplier_id,$vb['id'],$package['id']); 
       	
       	$html .= '<tr> 
       	 
		<td>'.$v1['title'].'</td> 
		 
		<td class="center"><input type="'.$itype.'" name="prices['.$package['id'].']['.$vb['id'].']['.$v1['id'].'][price2]" value="'.(isset($p['price2']) ? $p['price2'] : '').'" class="form-control input-sm aright numberFormat w100 inline-block input-currency-price-'.$package['id'] .'-'.$v1['id'].'" data-decimal="'.Yii::$app->zii->showCurrency(isset($p['currency']) ? $p['currency'] : 1,3).'"/></td>';
       	if(!empty($incurred_prices_list)){
       		foreach ($incurred_prices_list as $k=> $in){
       			$price_type = isset($in['supplier']['price_type']) ? $in['supplier']['price_type'] : 0;
       			if(in_array($price_type, [0])  && !in_array($in['type_id'],[3,4]) && !empty($room_groups)){
       			foreach ($room_groups as $room){
       				if(!empty($incurred_prices_weekend_list)){
       					foreach ($incurred_prices_weekend_list as $w){
       						$price_type = isset($w['supplier']['price_type']) ? $w['supplier']['price_type'] : 0;
       						if(in_array($price_type, [0]))
       							$html .= '<td class="center"><input type="text" name="prices['.$package['id'].']['.$vb['id'].']['.$v1['id'].'][list_child]['.$in['id'].']['.$room['id'].']['.$w['id'].'][price1]" value="'.(isset($p[$in['id']][$room['id']][$v1['id']][$w['id']]['price1']) ? $p[$in['id']][$room['id']][$v1['id']][$w['id']]['price1'] : '').'" class="form-control input-sm aright numberFormat w100 inline-block input-currency-price-'.$package['id'] .'-'.$v1['id'].'" data-decimal="'.Yii::$app->zii->showCurrency(isset($p['currency']) ? $p['currency'] : 1,3).'"/></td>';
       					}
       				}
       			}}
       		}
       	}
       	$html .= '<td class="center ">';
 		 
		 
       	$html .= '<select data-target-input=".input-currency-price-'.$package['id'] .'-'.$v1['id'].'" data-decimal="'.Yii::$app->zii->showCurrency(isset($p['currency']) ? $p['currency'] : 1,3).'" onchange="get_decimal_number(this);" class="ajax-select2-no-search sl-cost-price-currency form-control select2-hide-search input-sm" data-search="hidden" name="prices['.$package['id'].']['.$vb['id'].']['.$v1['id'].'][currency]">';
			//if(isset($v['currency']['list']) && !empty($v['currency']['list'])){
			foreach(Yii::$app->zii->getUserCurrency()['list'] as $k2=>$v2){
				$html .= '<option value="'.$v2['id'].'" '.(isset($p['currency']) && $p['currency'] == $v2['id'] ? 'selected' : '').'>'.$v2['code'].'</option>';
			}
			//}
			 
			$html .= '</select>';
		 
			$html .= '<input type="hidden" name="prices['.$package['id'].']['.$vb['id'].']['.$v1['id'].'][item_id]" value="'.$v1['id'].'"/></td> 	 		 
 		       
        </tr> ';
       }}elseif ($setting_room){
       	$html .= '<tr><td colspan="100%"><p class="help-block red underline">Bạn cần cài đặt nhóm phòng (FIT & GIT) trước khi nhập giá</p></td></tr>';
       }
       // 
       $html .= '</tbody></table></div>';
					
					//////////////////////////////////
				}
			}
		}}
		$html .= '<div class="col-sm-12 aright" style="margin-top: 15px; margin-bottom: 15px; ">';
		$html .= '<button data-class="w80" data-action="add-more-package-price-to-supplier" data-title="Thêm package" data-existed="" type="button" data-id="'.($supplier_id).'" data-target=".ajax-result-nationality-group" onclick="open_ajax_modal(this);" class="btn btn-warning btn-add-more2" type="button"><i class="glyphicon glyphicon-plus"></i> Quản lý báo giá</button>';
		$html .= '<button data-class="w60" data-action="add-more-package-price-to-supplier" data-title="Thêm package" data-existed="" type="button" data-id="'.($supplier_id).'" data-target=".ajax-result-nationality-group" onclick="open_ajax_modal(this);" class="btn btn-warning btn-add-more2" type="button"><i class="glyphicon glyphicon-plus"></i> Thêm package</button>'; 
		$html .= '<button data-class="w60" data-action="add-more-nationality-group-to-supplier" data-title="Thêm nhóm quốc tịch" data-existed="" type="button" data-id="'.($supplier_id).'" data-target=".ajax-result-nationality-group" onclick="open_ajax_modal(this);" class="btn btn-warning btn-add-more" type="button"><i class="glyphicon glyphicon-plus"></i> Thêm nhóm quốc tịch</button>
		</div> ';
		echo json_encode([
				'html'=>$html,
				'target'=>post('target')
		]);exit;
		break;
	case 'open-confirm-dialog':
		$html = '';
		
		$html .= '<div class=""><p clas="help-block">'.post('confirm-text','Bạn có chắc chắn xóa bản ghi này ?').'</p><p>&nbsp;</p></div>';
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-ok"></i> Xác nhận</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		//$r['event'] = $_POST['action'];
		echo json_encode([
				'html'=>$html,
				]); exit;
		break;
	case 'quick-open-confirm-dialog':
		$callback = false; $callback_function = ''; $event = 'hide-modal'; $modal = '.mymodal';
		switch (post('confirm-action')){
			case 'quick-remove-supplier-seasons':
				$callback = true;
				$supplier_id = post('supplier_id');
				$season_id = post('season_id');
				$callback_function = 'reloadAutoPlayFunction();';
				Yii::$app->db->createCommand()->delete('seasons_categorys',['id'=>post('season_id'),'owner'=>post('supplier_id')])->execute();				
				Yii::$app->db->createCommand()->delete('seasons_categorys_to_suppliers',['season_id'=>post('season_id'),'supplier_id'=>post('supplier_id')])->execute();
				
				
				$c = \app\modules\admin\models\Customers::getItem($supplier_id);
				switch ($c['type_id']){
					case TYPE_ID_VECL: case TYPE_ID_HOTEL:
						Yii::$app->db->createCommand()->delete(Yii::$app->zii->getTablePrice($c['type_id']),[
								'season_id'=>$season_id,
								'supplier_id'=>$supplier_id
						])->execute();
						Yii::$app->db->createCommand()->delete(Yii::$app->zii->getTablePrice($c['type_id']),[
								'weekend_id'=>$season_id,
								'supplier_id'=>$supplier_id
						])->execute();
						
						if($c['type_id'] == TYPE_ID_VECL){
							Yii::$app->db->createCommand()->delete(Yii::$app->zii->getTablePrice($c['type_id'],2),[
									'season_id'=>$season_id,
									'supplier_id'=>$supplier_id
							])->execute();
							Yii::$app->db->createCommand()->delete(Yii::$app->zii->getTablePrice($c['type_id'],2),[
									'weekend_id'=>$season_id,
									'supplier_id'=>$supplier_id
							])->execute();
						}
						break;
				}
				
				
				break;
			case 'delete-supplier-vehicle':
				$callback = true;
				$callback_function = 'reloadAutoPlayFunction();';
				Yii::$app->db->createCommand()->delete('vehicles_to_cars',['parent_id'=>post('supplier_id'),'vehicle_id'=>post('vehicle_id')])->execute();
				break;
			case 'quick_delete_package_supplier':
				$supplier_id = post('supplier_id',0);
				$package_id = post('package_id',0);
				//
				Yii::$app->db->createCommand()->delete('package_to_supplier',['supplier_id'=>$supplier_id,'package_id'=>$package_id])->execute();
				if(post('trash') == 0){
					Yii::$app->db->createCommand()->delete('package_prices',['supplier_id'=>$supplier_id,'id'=>$package_id])->execute();
				}
				//
				switch (post('controller_code')){
					case TYPE_ID_REST:
						Yii::$app->db->createCommand()->delete('menus_to_prices',['supplier_id'=>$supplier_id,'package_id'=>$package_id])->execute();				
						break;
				}
				//
				$event = 'hide-modal';
				$modal = '.mymodal1';
				$callback = true;
				$callback_function = 'jQuery(\'tr.tr-item-odr-'.$supplier_id.'-'.$package_id.'\').remove();';
				break;
			case 'quick_delete_quotation_supplier':
				$supplier_id = post('supplier_id',0);
				$quotation_id = post('quotation_id',0);
				//
				Yii::$app->db->createCommand()->delete('supplier_quotations_to_supplier',['supplier_id'=>$supplier_id,'quotation_id'=>$quotation_id])->execute();
				if(post('trash') == 0){
					Yii::$app->db->createCommand()->delete('supplier_quotations',['supplier_id'=>$supplier_id,'id'=>$quotation_id])->execute();
				}
				//
				
				$event = 'hide-modal';
				$modal = '.mymodal1';
				$callback = true;
				$callback_function = 'jQuery(\'tr.tr-item-odr-'.$supplier_id.'-'.$quotation_id.'\').remove();';
				break;	
			case 'quick_delete_position_user':
				if(Yii::$app->user->can([ROOT_USER])){
				$position_id = post('position_id',0);
				$type = post('type',0);
				//
				Yii::$app->db->createCommand()->delete('positions_to_users',['position_id'=>$position_id,
						'user_id'=>__SID__,'type_id'=>$type])->execute();
				 
				
				$event = 'hide-modal';
				$modal = '.mymodal1';
				$callback = true;
				$callback_function = 'jQuery(\'tr.tr-item-odr-'.$position_id.'\').remove();';
				}
				break;	
			case 'quick_change_menu_price_remove':
				$event = 'hide-modal';
				$modal = '.mymodal';
				$callback = true;
				//
				$supplier_id = post('supplier_id');
				$supplier = \app\modules\admin\models\Customers::getItem($supplier_id);
				//
				$table = Yii::$app->zii->getTablePrice($supplier['type_id'],post('price_type',1));
				$con = [
						'supplier_id'=>$supplier_id,
						'item_id'=>post('item_id'),
				];
				if(post('parent_group_id',0)>0){
					$con['parent_group_id'] = post('parent_group_id',0);
				}
				if($table !== false){ 
				//
				switch ($supplier['type_id']){
					case TYPE_ID_HOTEL: case TYPE_ID_SHIP_HOTEL:
						Yii::$app->db->createCommand()->delete('rooms_to_hotel',[
								'parent_id'=>$supplier_id,
								'room_id'=>post('item_id'),
						])->execute();
						break;
					case TYPE_ID_REST:
						Yii::$app->db->createCommand()->delete('menus_to_suppliers',[
							'supplier_id'=>$supplier_id,
							'menu_id'=>post('item_id'),
						])->execute();
						break;
					case TYPE_ID_VECL:
						if(post('price_type',1) == 2){
							Yii::$app->db->createCommand()->delete('distances_to_suppliers',[
									'supplier_id'=>$supplier_id,
									'item_id'=>post('item_id'),
							])->execute();
						}
						break;
				
				}
				
				
				Yii::$app->db->createCommand()->delete($table,$con)->execute();
				}
				$pieces = [
						post('supplier_id'),
						post('quotation_id'),
						post('package_id'),
						post('nationality_id'),
						post('item_id'),
				];
				
				$callback_function = 'reloadAutoPlayFunction();'; 
				break;
				
		} 
		echo json_encode([
				'html'=>$_POST,
				'callback'=>$callback,
				'callback_function'=>$callback_function,
				'event'=>$event,
				'modal_target'=>$modal,
		]); exit;
		break;
		break;
	case 'quick_change_supplier_season':
		$con = [
			'season_id'=>post('season_id',0),
			'supplier_id'=>post('supplier_id',0),
			//'type_id'=>post('type_id',0),
		];
		if(post('parent_id')>0){
			$con['parent_id'] = post('parent_id'); 
		}
		$new_value = post('new_value');
		switch (post('type')){
			case 'number':
				$new_value = cprice($new_value);
				break;
		}
		Yii::$app->db->createCommand()->update('seasons_categorys_to_suppliers',[
				post('field')=>$new_value
		],$con)->execute();
		exit;
		break;
		
	case 'quick_change_supplier_list_vehicle':
		$con = [
			'vehicle_id'=>post('vehicle_id',0),
			'parent_id'=>post('supplier_id',0),
			//'type_id'=>post('type_id',0),
		];
		if(post('parent_id')>0){
			$con['supplier_id'] = post('supplier_id'); 
		}
		$new_value = post('new_value');
		switch (post('type')){
			case 'number':
				$new_value = cprice($new_value);
				break;
		}
		if(in_array(post('field'), ['is_default'])){
			Yii::$app->db->createCommand()->update('vehicles_to_cars',[
					post('field')=>0
			],['parent_id'=>post('supplier_id',0)])->execute();
			$new_value = 1;
		}
		if(in_array(post('field'), ['is_active'])){
			 
			$new_value = cbool($new_value);
		}
		
		
		Yii::$app->db->createCommand()->update('vehicles_to_cars',[
				post('field')=>$new_value
		],$con)->execute();
		exit;
		break;	
		
	case 'removeSupplierSeason':
		Yii::$app->db->createCommand()->delete('seasons_to_suppliers',[
			'season_id'=>post('season_id'),
			'supplier_id'=>post('supplier_id'),
			'parent_id'=>post('parent_id'),
			'type_id'=>10
		])->execute();
		break;
	case 'change_location_append':
		$html = '';
		switch (post('value')){
			case 1:
				foreach (\app\modules\admin\models\NationalityGroups::get_supplier_group(post('supplier_id',0)) as $k=>$v){
					$c = (new Query())->from('seasons_to_private_suppliers')->where([
							'season_category_id'=>post('season_id',0),
							'object_id'=>post('value',0),
							'supplier_id'=>post('supplier_id',0),
							'group_id'=>$v['id'],
					])->count(1);
					$html .= '<option '.($c > 0 ? 'selected' : '').' value="'.$v['id'].'">'.uh($v['title']).'</option>';
				}
				break;
			case 2:
				foreach (\app\modules\admin\models\Local::getAllCountry() as $k=>$v){
					$c = (new Query())->from('seasons_to_private_suppliers')->where([
							'season_category_id'=>post('season_id',0),
							'object_id'=>post('value',0),
							'supplier_id'=>post('supplier_id',0),
							'group_id'=>$v['id'],
					])->count(1);
					$html .= '<option '.($c > 0 ? 'selected' : '').' value="'.$v['id'].'">'.uh($v['name']).'</option>';
				}
				break;
		}
		echo json_encode([
			'html'=>$html,
		]);exit;
		break;
	case 'change_seasons_private_suppliers':
		
		Yii::$app->db->createCommand()->delete('seasons_to_private_suppliers',[
			'season_category_id'=>post('season_id',0),
			'object_id'=>post('object_id',0),
			'supplier_id'=>post('supplier_id',0),
							 
		])->execute();
		if(!empty(post('value'))){
			foreach (post('value') as $v){
				Yii::$app->db->createCommand()->insert('seasons_to_private_suppliers',[
					'season_category_id'=>post('season_id',0),
					'object_id'=>post('object_id',0),
					'supplier_id'=>post('supplier_id',0),
					'group_id'=>$v,
				])->execute();
			}
		}
		echo json_encode([
			'value'=>$_POST,
			'callback'=>true,
			'callback_function'=>'console.log(data)'
		]);
		
		exit;
		break;
	case 'set_default_supplier_room':
		Yii::$app->db->createCommand()->update('rooms_to_hotel',[
			'is_default'=>0
		],['parent_id'=>post('supplier_id')])->execute();
		Yii::$app->db->createCommand()->update('rooms_to_hotel',[
				'is_default'=>1
		],['room_id'=>post('room_id'),'parent_id'=>post('supplier_id')])->execute();
		exit;
		break;
	case 'loadSupplierListRooms':
		$html = '';
		$id = post('id',0);
		$controller = post('controller');
		$model = load_model($controller);
		$code = post('code');
		$tab = post('tab',0);
		$v = $model->getItem($id);
		$l3 = \app\modules\admin\models\Hotels::getListRooms(isset($v['id']) ? $v['id'] : 0);
		//
		 
		$html .= '<table class="table table-hover table-bordered vmiddle table-striped"> <thead> 
       
      
        <tr> <th class="w50p">#</th><th rowspan="2">Tiêu đề</th>
		<th class="center ">Mô tả</th>  
		<th class="center cposition">Số chỗ</th>
		 
		<th class="center cposition">Số lượng</th>
	 <th class="center cposition">Mặc định</th>
		<th class="center cposition"></th></tr>
	
		</thead> <tbody class=""> ';
       $itype = 'text' ;
        $existed = array();
       if(!empty($l3)){
       	$led = \app\modules\admin\models\AdminMenu::get_menu_link('rooms_categorys',TYPE_CODE_ROOM_HOTEL);
       	
       foreach ($l3 as $k1=>$v1){
       	$v1['is_active'] = isset($v1['is_active']) ? $v1['is_active'] : 0;
       	$existed[] = $v1['id'];
       	$html .= '<tr> <th scope="row">'.($k1+1).'</th>
       	 
		<td><a href="'.$led.'" target="_blank">'.$v1['title'].'</a></td> 
		<td>'.$v1['note'].'</td> 
		<td class="center">'.$v1['seats'].'</td> 
 		<td class="center"><input type="'.$itype.'" name="h['.$v1['id'].'][quantity]" value="'.(isset($v1['quantity']) ? $v1['quantity'] : 0).'" class="form-control input-sm center numberFormat mw100p inline-block"/><input type="hidden" name="h['.$v1['id'].'][id]" value="'.($v1['id']).'"/></td> 		 		 
 		<td class="center"><input data-role="radio-option-ckc102" onchange="setRadioBool(this);set_default_supplier_room(this)" data-supplier_id="'.$id.'" data-room_id="'.$v1['id'].'" type="radio" '.(isset($v1['is_default']) && $v1['is_default'] == 1 ? 'checked' : '').' class="radio-option-ckc102" name="h['.$v1['id'].'][is_default]" /></td>
 		<td class="center"><input type="hidden" name="existed[]" value="'.$v1['id'].'"/>
 				
 				<i data-supplier_id="'.$id.'" 
					data-item_id="'.$v1['id'].'"
					data-confirm-text="<span class=red>Lưu ý: Bản ghi <b class=underline>'.$v1['title'].'</b> sẽ bị xóa khỏi toàn bộ các báo giá.</span>"
					class="pointer glyphicon glyphicon-trash btn-delete-item" data-id="'.$v1['id'].'" data-name="remove_menu" data-confirm-action="quick_change_menu_price_remove" data-action="open-confirm-dialog" data-class="modal-sm" data-title="Xác nhận xóa." onclick="open_ajax_modal(this);"></i>
							
							</td>         
        </tr> ';
       }}
       $html .= '<tr class="ajax-result-quick-add-more-before"><td colspan="6"></td><td class="center"><button data-class="" data-title="Thêm phòng khách sạn '.(isset($v['title']) ? ' <b class=red>'.$v['title'].'</b>' : '' ).' " data-action="add-more-room-to-hotel" data-existed="'.implode(',', $existed).'" data-count="'.count($l3).'" type="button" data-type_id="'.$code.'" data-id="'.(isset($v['id']) ? $v['id'] : 0).'" data-target=".ajax-result-more-room-hotel" title="Thêm phòng khách sạn" onclick="open_ajax_modal(this);" class="btn btn-default btn-add-more"> <i class="glyphicon glyphicon-plus"></i></button></td></tr>'; 
       $html .= '</tbody></table>'; 
		
		
		echo json_encode([
				'html'=>$html,
				//'callback'=>true,
				//'callback_function'=>'jQuery(\'.input-change-location-append\').change();reload_app(\'chosen\');'
		]+$_POST);exit;
		break;
	case 'loadSupplierSeasons':
		$html = '';
		$id = post('id',0);
		$supplier_id = post('supplier_id',post('id',0));
		$controller = post('controller');
		$model = load_model($controller);
		$code = post('code');
		$tab = post('tab',0);
		/*/
		$incurred_prices = $model->get_incurred_category($code,[2,3,4],[
				'supplier_id'=>$id,
				'type_id'=>20
		]);
		/*/
		$incurred_prices = \app\modules\admin\models\Customers::getCustomerSeasons($id);
		$isWeekend = 0;
		$existed = array();
		$html .= '<ul class="nav form-edit-tab-level2 nav-tabs" role="tablist">';
		if(!empty($incurred_prices)){
			$i3 = 0;
				
		
			foreach ($incurred_prices as $k3=> $v3){
				if(in_array($v3['type_id'], [3,4])){
					$isWeekend = 1;
				}
				$html .= '<li data-type_id="'.$v3['type_id'].'" role="presentation" class="pr list-with-remove-btn'.($i3++ == $tab ? ' active' : '').'"><a href="#tab-price-distance-'.$k3.'" role="tab" data-toggle="tab"><b>'.$v3['title'].'</b></a>'.(in_array($v3['type_id'], [0]) ? '' : '<i class="fa fa-remove small_remove_item pointer" data-confirm-action="quick-remove-supplier-seasons" data-supplier_id="'.$id.'" data-season_id="'.$v3['id'].'" data-action="open-confirm-dialog" data-class="modal-sm" data-title="Xác nhận xóa ?" onclick="open_ajax_modal(this)" title="Xóa"></i>').'</li>';
			}
		
			
			
		}
		$html .= '<li role="presentation" class="pr list-with-remove-btn"><a data-weekend="'.$isWeekend.'" data-class="w60" data-type_id="'.$code.'" data-action="add-more-season-category-to-supplier" data-title="Thêm mùa dịch vụ" title="Thêm mùa dịch vụ" data-id="'.($id).'" data-supplier_id="'.($supplier_id).'" data-target=".ajax-result-nationality-group" onclick="open_ajax_modal(this);" class="btn btn-link"><i class="glyphicon glyphicon-plus"></i></a></li>';
		
		$html .= '</ul>';
		$i3 = 0;
		$html .= '<div class="tab-content">';
		foreach ($incurred_prices as $k3=>$v3){
			 
				
			$price_type = $v3['price_type'];
			//view($price_type);
			$html .= '<div role="tabpanel" class="tab-panel tab2-panel '.($i3++ == $tab ? 'active' : '').'" id="tab-price-distance-'.$k3.'">';
			$html .= '<div class="mg5">';
			
			$html .= '<table class="table table-bordered vmiddle" style="margin-top:10px"><thead><tr class="hide"><th class="" style="width:200px"></th><th></th><th></th></tr><thead><tbody>
					<tr>
					<td class="bold" style="width:200px">Phương thức tính giá</td>
					<td colspan="2">
					<label style="margin-right:15px">
			<div><select data-tab-target=".input-ajax-auto-load-seasons-detail" data-tab="'.$k3.'" data-supplier_id="'.$id.'" data-season_id="'.$v3['id'].'" data-type_id="20" data-field="price_type" onchange="quick_change_supplier_season(this);change_season_price_type(this);" name="seasons['.$v3['id'].'][price_type]" class="form-control input-sm select2" data-search="hidden"> ';
			foreach (\app\modules\admin\models\Seasons::get_incurred_charge_type($code) as $cx=>$vx){
				$html .= '<option '.($price_type == $vx['id'] ? 'selected' : '').' value="'.$vx['id'].'">'.$vx['title'].'</option>';
			}
			$html .= '</select></div>
			</label>
					</td>
					</tr>
					
					<tr class="input-incurred-season-price" style="'.($price_type == 0 ? 'display:none' : '').'">
					<td class="bold">Giá trị</td>
					<td colspan="2">
					
					<label class="input-incurred-season-price" style="margin-right:15px;'.($price_type == 0 ? 'display:none' : '').'" ><input data-supplier_id="'.$id.'" data-season_id="'.$v3['id'].'" data-type_id="20" data-field="price_incurred" data-type="number" onblur="quick_change_supplier_season(this);" type="text" data-decimal="2" name="seasons['.$v3['id'].'][price_incurred]" class="h28 sui-input aright bold red input-incurred-season-price number-format" data-old="'.($price_type == 0 ? '' : (isset($v3['price_incurred']) ? $v3['price_incurred'] : '')).'" value="'.($price_type == 0 ? '' : (isset($v3['price_incurred']) ? $v3['price_incurred'] : '')).'" placeholder="Giá phát sinh" style="'.($price_type == 0 ? 'display:none' : '').'"/></label>
					<label class="input-incurred-season-parent-id w150p" style="margin-right:15px;'.($price_type == 1 ? 'display:inline-block' : 'display:none').'" ><select data-supplier_id="'.$id.'" data-season_id="'.$v3['id'].'" data-type_id="20" data-field="parent_id" onchange="quick_change_supplier_season(this);" data-placeholder="Tính giá theo" style="" class="form-control sui-input input-sm select2" data-search="hidden" name="seasons['.$v3['id'].'][parent_id]"><option value="0">--</option>';
			foreach ($incurred_prices as $k5=>$v5){
				if($v3['id'] != $v5['id']){
					$html .= '<option '.(isset($v3['parent_id']) && $v3['parent_id'] == $v5['id'] ? 'selected' : '').' value="'.$v5['id'].'">Giá '.$v5['title'].'</option>';
				}
			}
			
			$html .= '</select></label>
					
					
					<label class="input-incurred-season-currency" style="margin-right:15px; '.($price_type == 2 ? 'display:inline-block' : 'display:none').'" >';
			//if(isset(\ZAP\Zii::$site['other_setting']['currency']['list'])){
			$html .= '<select data-decimal="0" data-supplier_id="'.$id.'" data-season_id="'.$v3['id'].'" data-type_id="20" data-field="currency" onchange="quick_change_supplier_season(this);get_decimal_number(this);" class="sl-cost-price-currency form-control input-sm select2" data-search="hidden" name="seasons['.$v3['id'].'][currency]">';
			//if(isset($v['currency']['list']) && !empty($v['currency']['list'])){
			foreach(Yii::$app->zii->getUserCurrency()['list'] as $k2=>$v2){
				$html .= '<option value="'.$v2['id'].'" '.(isset($v3['currency']) && $v3['currency'] == $v2['id'] ? 'selected' : '').'>'.$v2['code'].'</option>';
			}
			//}
		
			$html .= '</select></label>';
					
			$html .= '<label class="input-incurred-season-currency" style="margin-right:15px; '.($price_type == 2 ? 'display:inline-block' : 'display:none').'" >/</label>
				<label class="input-incurred-season-currency" style="min-width:160px; margin-right:15px; '.($price_type == 2 ? 'display:inline-block' : 'display:none').'" >';
			//if(isset(\ZAP\Zii::$site['other_setting']['currency']['list'])){
			$html .= '<select data-supplier_id="'.$id.'" data-season_id="'.$v3['id'].'" data-type_id="20" data-field="unit_price" onchange="quick_change_supplier_season(this);" data-search="hidden" class="sl-cost-price-currency form-control select2 input-sm" name="seasons['.$v3['id'].'][unit_price]">';
			//if(isset($v['currency']['list']) && !empty($v['currency']['list'])){
			foreach(\app\modules\admin\models\Seasons::get_unit_prices() as $k2=>$v2){
				$html .= '<option value="'.$v2['id'].'" '.(isset($v3['unit_price']) && $v3['unit_price'] == $v2['id'] ? 'selected' : '').'>'.$v2['title'].'</option>';
			}
			//}
			
			$html .= '</select>';
			//}
			
			$html .= '</label>
							</td>
					</tr>';
			if(in_array($v3['type_id'], [3,4,5]) && $price_type >0){		
			$html .= '<tr>
					<td class="bold">Áp dụng mùa riêng</td>
					<td colspan="2"><label class=" w150p" style=" " ><select data-supplier_id="'.$id.'" data-season_id="'.$v3['id'].'" data-type_id="20" data-field="sub_id" onchange="quick_change_supplier_season(this)" style="" class="form-control input-sub_id-change-price sui-input input-sm select2" data-search="hidden" name="seasons['.$v3['id'].'][sub_id]"><option value="0">--</option>';
			foreach ($incurred_prices as $k5=>$v5){
				if($v3['id'] != $v5['id']){
					$html .= '<option '.(isset($v3['sub_id']) && $v3['sub_id'] == $v5['id'] ? 'selected' : '').' value="'.$v5['id'].'">'.$v5['title'].'</option>';
				}
			}
			$html .= '</select></label>
					
					 
					
					
					</td>
					</tr>';
			}
			$html .= '<tr>
					<td class="bold">Đối tượng áp dụng</td>
					<td style="width:200px">
					<select data-supplier_id="'.$id.'" data-season_id="'.$v3['id'].'" data-type_id="20" data-field="object_id" onchange="quick_change_supplier_season(this);change_location_append(this);" name="seasons['.$v3['id'].'][object_id]" class="form-control input-sm select2 input-change-location-append" data-search="hidden">
					<option value="0">Tất cả</option>
					<option '.(isset($v3['object_id']) && $v3['object_id'] == 1 ? 'selected' : '').' value="1">Nhóm quốc tịch</option>
					<option '.(isset($v3['object_id']) && $v3['object_id'] == 2 ? 'selected' : '').' value="2">Quốc gia</option>
					</select>
					</td>
					<td> 
					
					 ';
			///if(Yii::$app->user->can([ROOT_USER])){		
			$html .= '<div class="input-group group-sm30">
     
					 <select  data-supplier_id="'.$id.'" data-season_id="'.$v3['id'].'" onchange="change_seasons_private_suppliers(this)" data-object_id="'.(isset($v3['object_id']) ? $v3['object_id'] : 0).'" data-placeholder="Nhóm quốc tịch hoặc các quốc gia được áp dụng" multiple class="form-control sui-input input-sm chosen-select input-location-appended" data-role="chosen-load-country" data-search="hidden" name="seasons['.$v3['id'].'][sub_id]">';
			
			 
			$html .= '</select> 
					
					 
      <span class="input-group-btn">
        <button data-class="w60" data-action="add-more-nationality-group-to-supplier" data-title="Thêm nhóm quốc tịch" data-existed="" type="button" data-id="'.($id).'" data-target=".ajax-result-nationality-group" onclick="open_ajax_modal(this);" class="btn btn-sm btn-success btn-add-more add-more-nationality-group-to-supplier" type="button" title="Thêm mới nhóm quốc tịch"><i class="glyphicon glyphicon-plus"></i></button>
      </span>
    </div><!-- /input-group -->';
			//}
			
			$html .= '</td></tr></tbody></table>';
			
			 
			 
			$html .'</div>';
			///}else{
				
			//}
			$html .= '<table class="table vmiddle table-hover table-bordered"><thead><tr>';
			$html .= '<th rowspan="2" class="w200p center">Thời gian bắt đầu</th>';
				
			$html .= '<th rowspan="2" class="center w200p">Thời gian kết thúc</th>';
			$html .= '<th rowspan="2" class="center w200p">Buổi</th>';
			$html .= '<th rowspan="2" class="center ">Tiêu đề</th>';
			$html .= '<th rowspan="2" class="center w50p">Xóa</th>';
			$html .= '</tr></thead><tbody class="ajax-result-price-distance-'.$k3.'">';
			
			if(in_array($v3['type_id'], [3,4,5])){
				$l4 = \app\modules\admin\models\Seasons::get_list_weekend_by_parent($id, $v3['id']);
				//view($l4);
				if(!empty($l4)){
					foreach ($l4 as $k4=>$v4){
						$existed[] = $v4['id'];
						$html .= '<tr class="tr-distance-id-'.$v4['id'].'">';
						$html .= '<td class="center">'.$v4['from_time'].' '.read_date($v4['from_date']).'</td>';
						$html .= '<td class="center">'.$v4['to_time'].' '.read_date($v4['to_date']).'</td>';
						$html .= '<td class="center">'.($v4['part_time']>-1 ? showPartDay($v4['part_time']) : '-').'</td>';
						$html .= '<td class="center">'.$v4['title'].'<input type="hidden" value="'.$v4['id'].'" name="seasons['.$v3['id'].'][list_child]['.$v4['id'].'][id]"/></td>';
						$html .= '<td class="center"><i data-season_id="'.$v4['id'].'" data-supplier_id="'.$id.'" data-parent_id="'.$v3['id'].'" title="Xóa" data-name="delete_price_distance_id" onclick="return removeSupplierSeason(this);" data-target=".tr-distance-id-'.$v4['id'].'" class="pointer glyphicon glyphicon-trash"></i></td>';
						$html .= '</tr>';
					}
						
				}
			}else {
				$l4 = \app\modules\admin\models\Seasons::get_list_seasons_by_parent($id, $v3['id']);
				if(!empty($l4)){
					foreach ($l4 as $k4=>$v4){
						$existed[] = $v4['id'];
						$html .= '<tr class="tr-distance-id-'.$v4['id'].'">';
						$html .= '<td class="center">'.date("d/m/Y",strtotime($v4['from_date'])).'</td>';
						$html .= '<td class="center">'.date("d/m/Y",strtotime($v4['to_date'])).'</td>';
						$html .= '<td class="center">-</td>';
						$html .= '<td><a>'.$v4['title'].'</a><input type="hidden" value="'.$v4['id'].'" name="seasons['.$v3['id'].'][list_child]['.$v4['id'].'][id]"/></td>';
						$html .= '<td class="center"><i data-season_id="'.$v4['id'].'" data-supplier_id="'.$id.'" data-parent_id="'.$v3['id'].'" title="Xóa" data-name="delete_price_distance_id" onclick="return removeSupplierSeason(this);" data-target=".tr-distance-id-'.$v4['id'].'" class="pointer glyphicon glyphicon-trash"></i></td>';
						$html .= '</tr>';
					}
		
				}
			}
			$html .= '</tbody></table></div>';
		
			$html .= '<div class="aright btn-list-add-more-1"><button data-part_time="" data-tab="'.$k3.'" data-class="w60" data-type_id="'.$v3['type_id'].'" data-season_id="'.$v3['id'].'" data-action="sadd-more-season-to-supplier" data-title="Thêm mùa cao điểm, lễ tết, cuối tuần" data-existed="'.implode(',', $existed).'" type="button" data-id="'.($id).'" data-target=".ajax-result-price-distance-'.$k3.'" title="Thêm" onclick="open_ajax_modal(this);" class="btn btn-warning btn-add-more"><i class=" glyphicon glyphicon-plus"></i> Thêm mới</button></div>';
			$html .= '</div>';
		}
		
		$html .= '</div>';
		//
		 
		echo json_encode([
			'html'=>$html,
			'callback'=>true,
				'callback_function'=>'jQuery(\'.input-change-location-append\').change();reload_app(\'chosen\');'
		]+$_POST);exit;	
		break;
	case 'quick-sadd-more-season-to-supplier':		
		$callback = false; $callback_function = ''; $event = 'hide-modal';
		//
		$f = post('f',[]);
		$new = post('new',[]);		
		$child_id = isset($f['child_id']) ? $f['child_id'] : [];
		//
		//if(!empty($new)){
			switch (post('type_id')){
				case SEASON_TYPE_WEEKEND: case SEASON_TYPE_WEEKDAY:// NT - CT
					
					if(!empty($new)){
							foreach ($new as $n){
								 
								if(trim($n['from_time']) != "" && trim($n['to_time']) != "" && trim($n['title']) != ""){
									$child_id[] = Yii::$app->zii->insert('weekend',[
											'title'=>$n['title'],
											'sid'=>__SID__,
											'from_date'=>$n['from_date'],
											'to_date'=>$n['to_date'],
											'from_time'=>isset($n['from_time']) ? $n['from_time'] : '00:00:00',
											'to_time'=>isset($n['to_time']) ? $n['to_time'] : '23:59:59', 
											//'parent_id'=>post('season_id'),
											'type_id'=>post('type_id'),
											'part_time'=>isset($n['part_time']) ? $n['part_time'] : -1
									]);
								}
							}
						} 	
						
					 
					
					break;
				case SEASON_TYPE_TIME:// NT - CT 
					
					if(!empty($new)){
							foreach ($new as $n){
								 
								if(trim($n['title']) != ""){
									$child_id[] = Yii::$app->zii->insert('weekend',[
											'title'=>$n['title'],
											'sid'=>__SID__,
											'from_date'=>$n['from_date'],
											'to_date'=>$n['to_date'],
											'from_time'=>isset($n['from_time']) ? $n['from_time'] : '00:00:00',
											'to_time'=>isset($n['to_time']) ? $n['to_time'] : '23:59:59', 
											//'parent_id'=>post('season_id'),
											'type_id'=>post('type_id'),
											'part_time'=>isset($n['part_time']) ? $n['part_time'] : -1
									]);
								}
							}
						} 	
						
					 
					
					break;	
					
				default: // Khoang thoi gian
					if(!empty($new)){
					foreach ($new as $n){
						if($n['title'] != "" && check_date_string($n['from_date'])){
							$n['to_date'] =  ctime(['string'=>$n['to_date']]);
							$n['to_date'] = date("Y-m-d 23:59:59",strtotime($n['to_date']));
							$child_id[] = Yii::$app->zii->insert('seasons',[
									//'parent_id'=>post('id'),
									'type_id'=>post('type_id'), 
									'title'=>$n['title'],
									'sid'=>__SID__,
									'from_date'=>ctime(['string'=>$n['from_date']]),
									'to_date'=>$n['to_date'],
							]);
						}
					}
					}
					
					break;
			//}
					
		}
		if(!empty($child_id)){
			foreach ($child_id as $c){
				if((new Query())->from('seasons_to_suppliers')->where([
						'season_id'=>$c,
						'parent_id'=>post('season_id'),
						'supplier_id'=>post('id'),
						'type_id'=>post('type_id'), 
				])->count(1) == 0){
					Yii::$app->db->createCommand()->insert('seasons_to_suppliers',[
							'season_id'=>$c,
							'parent_id'=>post('season_id'),
							'supplier_id'=>post('id'),
							'type_id'=>post('type_id'), 
					])->execute();
				}
			}
		}
		//
		$callback = true;
		$callback_function = 'jQuery(\'.input-ajax-auto-load-seasons-detail\').attr(\'data-tab\','.post('tab',0).');reloadAutoPlayFunction();';
		echo json_encode([
				'event'=>$event,
				'callback'=>$callback,
				'callback_function'=>$callback_function,
		]+[$child_id]);exit;
		break;
	case 'sadd-more-season-to-supplier':
			$r = array(); $r['html'] = '';
			$m = new \app\modules\admin\models\Seasons();
			$type_id = isset($_POST['type_id']) ? $_POST['type_id'] : 2;
			///view($type_id);
			//
			$existed = post('existed');
			//
			$l4 = in_array($type_id,[3,4,5]) ? $m->get_weekend(['limit'=>100,'type_id'=>$type_id,'not_in'=>($existed != "" ? explode(',', $existed) : [])]) : $m->getList(['limit'=>100,'not_in'=>($existed != "" ? explode(',', $existed) : [])]);
			$r['html'] = '<div class="form-group">';
			$r['html'] .= '<div class="group-sm34 col-sm-12"><select name="f[child_id][]" multiple data-existed="'.$existed.'" data-role="chosen-load-season" class="form-control ajax-chosen-select-ajax" style="width:100%">';
			switch ($type_id){
				case SEASON_TYPE_WEEKEND: case SEASON_TYPE_WEEKDAY: case SEASON_TYPE_TIME:
					foreach (\app\modules\admin\models\Seasons::getAvailableWeekend([
						'parent_id'=>post('season_id'),
						'type_id'=>$type_id,
						'supplier_id'=>post('id')
					]) as $k4=>$v4){
						$r['html'] .= '<option value="'.$v4['id'].'">['.$v4['title'].'] '.$v4['from_time'] . ' ' . read_date($v4['from_date']). ' -> ' . $v4['to_time'] . ' ' . read_date($v4['to_date']) .'</option>';
					}
					break;
				default:
					foreach (\app\modules\admin\models\Seasons::getAvailableSeason([
							'parent_id'=>post('season_id'),
							//'type_id'=>$type_id,
							'supplier_id'=>post('id')
					]) as $k4=>$v4){
						$r['html'] .= '<option value="'.$v4['id'].'">'.$v4['title'].' ('.date("d/m/Y",strtotime($v4['from_date'])) .' - ' . date("d/m/Y",strtotime($v4['to_date'])) .')</option>';
					}
					break;
			}
			/* 
			if(!empty($l4['listItem'])){
				foreach ($l4['listItem'] as $k4=>$v4){break;
					if(in_array($type_id,[3,4,5])){
						$r['html'] .= '<option value="'.$v4['id'].'">['.$v4['title'].'] '.$v4['from_time'] . ' ' . read_date($v4['from_date']). ' -> ' . $v4['to_time'] . ' ' . read_date($v4['to_date']) .'</option>';
					}else{
						$r['html'] .= '<option value="'.$v4['id'].'">'.$v4['title'].' ('.date("d/m/Y",strtotime($v4['from_date'])) .' - ' . date("d/m/Y",strtotime($v4['to_date'])) .')</option>';
					}
				}
			}
			*/
			$r['html'] .= '</select></div>';
			$r['html'] .= '</div><p class="help-block italic ">*** Bạn có thể chọn giá trị có sẵn hoặc thêm mới ở ô nhập bên dưới</p><hr>';
			$r['html'] .= '<div class="">';
				
			$r['html'] .= '<div class="group-sm34">';
			$r['html'] .= '<table class="table vmiddle table-hover table-bordered">';
			$r['html'] .= '<tbody class="">';
		
			for($i=0; $i<3;$i++){
				switch ($type_id){
					case SEASON_TYPE_WEEKEND: case SEASON_TYPE_WEEKDAY:
						$r['html'] .= '<tr>
    					<td><select class="form-control input-sm ajax-select2-no-search"  name="new['.$i.'][from_date]">';
						for($j = 0;$j<7;$j++){
							$r['html'] .= '<option value="'.$j.'">'.read_date($j).'</option>';
						}
						$r['html'] .= '</select></td>
    					<td><input type="text" class="sui-input form-control input-sm ajax-timepicker" value="" name="new['.$i.'][from_time]" placeholder="Thời gian bắt đầu"/></td>
    					<td><select class="form-control input-sm ajax-select2-no-search" name="new['.$i.'][to_date]">';
						for($j = 0;$j<7;$j++){
							$r['html'] .= '<option value="'.($j == 0 ? 7 : $j).'">'.read_date($j).'</option>';
						}
						$r['html'] .= '</select></td>
    					<td class="center "><input type="text" class="sui-input w100 form-control input-sm ajax-timepicker" value="" name="new['.$i.'][to_time]" placeholder="Thời gian kết thúc"/></td>
    					<td class=""><input type="text" class="sui-input w100 form-control input-sm" value="" name="new['.$i.'][title]" placeholder="Tiêu đề"/></td>';
						$r['html'] .= '</tr>';
						break;
					case SEASON_TYPE_TIME:
						$r['html'] .= '<tr>
    					<td><select class="form-control input-sm ajax-select2-no-search"  name="new['.$i.'][from_date]">';
						for($j = 0;$j<7;$j++){
							$r['html'] .= '<option value="'.$j.'">'.read_date($j).'</option>';
						}
						$r['html'] .= '</select></td>
    					 
    					<td><select class="form-control input-sm ajax-select2-no-search" name="new['.$i.'][to_date]">';
						for($j = 0;$j<7;$j++){
							$r['html'] .= '<option value="'.($j == 0 ? 7 : $j).'">'.read_date($j).'</option>';
						}
						$r['html'] .= '</select></td>
    					<td><select class="form-control input-sm ajax-select2-no-search" name="new['.$i.'][part_time]">';
						for($j = 0;$j<4;$j++){
							$r['html'] .= '<option value="'.$j.'">'.showPartDay($j).'</option>';
						}
						$r['html'] .= '</select></td>
    					<td class=""><input type="text" class="sui-input w100 form-control input-sm" value="" name="new['.$i.'][title]" placeholder="Tiêu đề"/></td>';
						$r['html'] .= '</tr>';
						break;
					default:
						$r['html'] .= '<tr>
    					<td class="pr"><input onblur="addrequired_input(this);" type="text" class="sui-input form-control input-sm ajax-datepicker" value="" name="new['.$i.'][from_date]" placeholder="Thời gian bắt đầu"/></td>
    					<td class="center pr"><input onblur="addrequired_input(this);" type="text" class="sui-input w100 form-control input-sm ajax-datepicker" value="" name="new['.$i.'][to_date]" placeholder="Thời gian kết thúc"/></td>
    					<td class="center "><input onblur="addrequired_input(this);" type="text" class="sui-input w100 form-control input-sm" value="" name="new['.$i.'][title]" placeholder="Tiêu đề"/> </td>';
						$r['html'] .= '</tr>';
						break;
				}
				 
			}
				
			$r['html'] .= '</tbody></table>';
			$r['html'] .= '</div>';
			$r['html'] .= '</div>';
			//
			$r['html'] .= '<div class="modal-footer">';
			$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
			$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
			$r['html'] .= '</div>';
			$_POST['action'] = 'quick-' . $_POST['action'];
			foreach ($_POST as $k=>$v){
				$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
			}
			///
				
			$r['event'] = $_POST['action'];
			$r['existed'] = $existed;
			echo json_encode($r);exit;
				
			break;
	case 'generateSitemap':
		$html = Yii::$app->zii-> generateSitemap($_POST);
		echo json_encode([
				'html'=>$html
		]);exit;
		break;
	case 'checkExistedTourProgramCode':
		//$state = true;
		$id = post('id',0);
		$code = unMark(post('code'),'',false);
		$state = $code != "" ? ((new Query())->from('tours_programs')->where(['code'=>$code])->andWhere(['not in','id',$id])->count(1) > 0 ? false : true) : false;
		echo json_encode([
			'state'=>$state,
			'code'=>$code,	
			'alert'=>$state ? '' : 'Mã tour không hợp lệ hoặc đã được sử dụng.',	
		]+$_POST);
		exit;
		break;
	case 'change_selected_tour_service_group':
		$html = '';
  		$place_id = post('place_id',0);$id = post('id');
  		
		
		switch ($id){
			case TYPE_ID_HOTEL: case TYPE_ID_REST:	case TYPE_ID_SHIP_HOTEL:		 
				$model = load_model('customers');
				
				$l = $model->getList([
						'type_id'=>$id,
						'p'=>1,
						'place_id'=>$place_id,
						'not_in'=>post('selected',[])
				]); 
				//$html .= json_encode($l);
				if(!empty($l['listItem'])){
					foreach($l['listItem'] as $k=>$v){
						// Lay package
						$packages = \app\modules\admin\models\PackagePrices::getPackages($v['id']);
						if(empty($packages)){
							$packages = [['id'=>0,'title'=>'']];
						}
						foreach ($packages as $package){
							$html .= '<li data-package_id="'.$package['id'].'" data-type_id="'.$id.'" data-id="'.$v['id'].'" data-supplier_id="'.$v['id'].'" class="ui-state-highlight">'.($package['id']>0 ? '<i class="green underline">['.uh($package['title']).']</i>&nbsp;' : '').''.uh($v['name']).'</li>';
						}
					}
				}
				break;
			case TYPE_CODE_DISTANCE:
				$model = load_model('distances');
				
				$l = $model->getAll(6, [ 
						'limit'=>100,
						'place_id'=>$place_id,
						'not_in'=>post('selected'),
						'p'=>1
				]);
				//$html .= json_encode($l);
				if(!empty($l)){
					foreach($l as $k=>$v){
						$html .= '<li data-type_id="'.$id.'" data-id="'.$v['id'].'" class="ui-state-highlight">'.uh($v['title']).'</li>';
					}
				}
				break;
			case TYPE_ID_SCEN:
				$model = load_model('tickets');					
				$l = $model->getList([
						'limit'=>100,
						'p'=>1,
						'place_id'=>$place_id,
						'filter_text'=>post('value'),
						'not_in'=>post('selected')
				]);
				//$html .= json_encode($l);
				if(!empty($l['listItem'])){
					foreach($l['listItem'] as $k=>$v){
						$html .= '<li data-type_id="'.$id.'" data-id="'.$v['id'].'" class="ui-state-highlight">'.uh($v['title']).'</li>';
					}
				}
				break;
			case TYPE_ID_GUIDES:
				$model = load_model('guides');
				$l = $model->getGuidesByPlace([
						'limit'=>100,
						'p'=>1,
						'place_id'=>$place_id,
						'filter_text'=>post('value'),
						'not_in'=>post('selected')
				]);
				//$html .= json_encode($l);
				if(!empty($l)){
					foreach($l as $k=>$v){
						$html .= '<li data-type_id="'.$id.'" data-id="'.$v['id'].'" class="ui-state-highlight">'.uh($v['title']).' &nbsp;<i class="underline font-normal green">['.uh($v['supplier_name']).']</i></li>';
					}
				}
				
				break;
		}
		$html .= '';		
		echo json_encode([
				'html'=>$html,
				//'code'=>$code,
				//'alert'=>$state ? '' : 'Mã tour không hợp lệ hoặc đã được sử dụng.',
		]+$_POST);
		exit;
		break;
	case 'quick_search_tour_service':
		$html = '';
		$id = post('type_id',0);
		$place_id = post('place_id',0);
		switch ($id){
			case TYPE_ID_HOTEL: case TYPE_ID_REST: case TYPE_ID_VECL:
				$model = load_model('customers');
			
				$l = $model->getList([
						'type_id'=>$id,
						'p'=>1,
						'is_active'=>1,
						'place_id'=>$place_id,
						'filter_text'=>post('value'),
						'not_in'=>post('selected')
				]);
				//$html .= json_encode($l);
				if(!empty($l['listItem'])){
					foreach($l['listItem'] as $k=>$v){
						$html .= '<li data-type_id="'.$id.'" data-id="'.$v['id'].'" class="ui-state-highlight">'.uh($v['name']).'</li>';
					}
				}
				break;
			case TYPE_CODE_DISTANCE:
				$model = load_model('distances');
			
				$l = $model->getAll(TYPE_ID_VECL, [
						'limit'=>100,
						'p'=>1,
						'place_id'=>$place_id,
						'filter_text'=>post('value'),
						'not_in'=>post('selected')
				]);
				//$html .= json_encode($l);
				if(!empty($l)){
					foreach($l as $k=>$v){
						$html .= '<li data-type_id="'.$id.'" data-id="'.$v['id'].'" class="ui-state-highlight">'.uh($v['title']).'</li>';
					}
				}
				break;
			case TYPE_ID_SCEN:
				$model = load_model('tickets');
			
				$l = $model->getList([
						'limit'=>100,
						'p'=>1,
						'place_id'=>$place_id,
						'filter_text'=>post('value'),
						'not_in'=>post('selected')
				]);
				//$html .= json_encode($l);
				if(!empty($l['listItem'])){
					foreach($l['listItem'] as $k=>$v){
						$html .= '<li data-type_id="'.$id.'" data-id="'.$v['id'].'" class="ui-state-highlight">'.uh($v['title']).'</li>';
					}
				}
				break;	
				
			case TYPE_ID_GUIDES:
				$model = load_model('guides');
				$l = $model->getGuidesByPlace([
						'limit'=>100,
						'p'=>1,
						'place_id'=>$place_id,
						'filter_text'=>post('value'),
						'not_in'=>post('selected')
				]);
				//$html .= json_encode($l);
				if(!empty($l)){
					foreach($l as $k=>$v){
						$html .= '<li data-type_id="'.$id.'" data-id="'.$v['id'].'" class="ui-state-highlight">'.uh($v['title']).' <i class="underline font-normal green">['.uh($v['supplier_name']).']</i></li>';
					}
				}
				break;	
			 
			case TYPE_CODE_VEHICLE:
				 
				$l = \app\modules\admin\models\Cars::getListCars(post('supplier_id'),[
						'limit'=>100,
						'p'=>1,
						'place_id'=>$place_id,
						'filter_text'=>post('value'),
						'not_in'=>post('selected'),
						'is_active'=>1
				]);
				//$html .= json_encode($l);
				if(!empty($l)){
					foreach($l as $k=>$v){
						$html .= '<li data-type_id="'.$id.'" data-id="'.$v['id'].'" class="ui-state-highlight">
								<div class="col-sm-8 col-index-1 col-border-right">'.uh($v['title']).' <i class="underline font-normal green">['.uh($v['maker_title']).']</i></div>
								<div class="col-sm-4 col-index-2"><input type="text" class="form-control center number-format selected_quantity" data-name="selected_quantity[]" placeholder="Số lượng"/></div>
								</li>';
					}
				}
				break;	
					 
		}
		echo json_encode([
				'html'=>$html,
				'callback'=>true,
				'callback_function'=>'load_number_format();'
				]+$_POST);
		exit;
		break;
		
	case 'quick-add-more-distance-supplier':
		$item_id = post('id');
		$day = post('day');
		$time = post('time');
		$nationality = post('nationality');
		$guest = post('guest');
		
		
		$selected_value = post('selected_value',[]);
		 
		$i = 0;
		if(!empty($selected_value)){
			$selected_type_id = post('selected_type_id');
			foreach ($selected_value as $k => $id){
				$type_id = $selected_type_id[$k];
				
				Yii::$app->zii->chooseVehicleAuto([
						'totalPax'=>$guest,
						'nationality'=>$nationality,
						'supplier_id'=>$id,
						'auto'=>true,
						'update'=>true,
						'item_id'=>$item_id
				]);
				 
			}
		}
		echo json_encode([
				'event'=>'hide-modal',
				'callback'=>true,
				'callback_function'=>'reloadAutoPlayFunction();'
		]);
		exit;
		break;	
	case 'quick-add-tours-services':
		$item_id = post('id');
		$day = post('day');
		$time = post('time');
		
		
		
		$selected_value = post('selected_value',[]);
		//view($selected_value);
		Yii::$app->db->createCommand()->delete('tours_programs_services_days',[
				'item_id'=>$item_id,
				'day_id'=>$day,
				'time_id'=>$time,
		])->execute();
		$i = 0;
		if(!empty($selected_value)){
			$selected_type_id = post('selected_type_id');
			$selected_package_id = post('selected_package_id');
			foreach ($selected_value as $k => $id){
				$type_id = $selected_type_id[$k];
				//if(!empty($v)){
				//	foreach ($v as $id){
				Yii::$app->db->createCommand()->insert('tours_programs_services_days',[
						'item_id'=>$item_id,
						'type_id'=>$type_id,
						'service_id'=>$id,
						'day_id'=>$day,
						'time_id'=>$time,
						'position'=>$k,
						'package_id'=>(isset($selected_package_id[$k]) ? $selected_package_id[$k] : 0 ),
				])->execute();
				//}
				//}
			}
		}
		echo json_encode([
				'event'=>'hide-modal',
				'callback'=>true,
				'callback_function'=>'reloadAutoPlayFunction();'
		]);
		exit;
		break;
	case 'quick-add-tours-distance-services':
		$item_id = post('id');
		$supplier_id = post('supplier_id',0);
		$time = post('time');
		
		
		
		$selected_value = post('selected_value',[]);
		//view($selected_value);
		Yii::$app->db->createCommand()->delete('tours_programs_services_distances',[
				'item_id'=>$item_id,
				'supplier_id'=>$supplier_id,
				//'time'=>$time,
				])->execute();
		$i = 0;
		if(!empty($selected_value)){
			$selected_type_id = post('selected_type_id');
			foreach ($selected_value as $k => $id){
				$type_id = $selected_type_id[$k];
				//if(!empty($v)){
				//	foreach ($v as $id){
						Yii::$app->db->createCommand()->insert('tours_programs_services_distances',[
								'item_id'=>$item_id,
								'type_id'=>$type_id,
								'service_id'=>$id,
								'supplier_id'=>$supplier_id,
								//'time'=>$time,
								'position'=>$k
						])->execute();	
					//}
				//}
			}
		}
		echo json_encode([
			'event'=>'hide-modal',
			'callback'=>true,
			'callback_function'=>'reloadAutoPlayFunction();'
		]);
		exit;
		break;
	case 'add-tours-distance-services':
		$id = post('id',0);
		$day = post('day',0);
		$supplier_id = post('supplier_id',0);
		$time = post('time',0);		
		$html = '';
		
		$html .= '<p class="help-block">Bạn đang chọn dịch vụ vận chuyển</p>
				<table class="table table-bordered vmiddle"><thead><tr>
				<th class="center bold col-ws-2">Dịch vụ</th>
				<th class="center bold col-ws-4">Dịch vụ đã chọn</th>
				<th class="center bold col-ws-4">Dịch vụ có thể chọn</th>
				</tr></thead><tbody>';
		$html .= '<tr class="vtop">
				<td class="">
				<ul class="style-none ul-style-l01">';
		foreach (showListChooseService() as $k=>$v){
			$html .= $v['id'] == TYPE_CODE_DISTANCE ? '<li class="'.($v['id'] == TYPE_CODE_DISTANCE ? 'li-service-first-child' : '').'"><a data-day="'.$day.'" data-time="'.$time.'" data-id="'.$v['id'].'" onclick="return change_selected_tour_service_group(this);" href="#">'.$v['title'].'</a></li>' : '';
		}
		$services = \app\modules\admin\models\ToursPrograms::getProgramDistanceServices($id,$supplier_id);
		$html .= '</ul>
				
				</td>
				<td class="">
				
				<ul id="sortable1" class="connectedSortable style-none">';
		if(!empty($services)){
			foreach ($services as $kv=>$sv){
				$html .= '<li data-type_id="'.$sv['type_id'].'" data-id="'.$sv['id'].'" class="ui-state-default">'.(isset($sv['title']) ? uh($sv['title']) : uh($sv['name'])).(isset($sv['supplier_name']) ? ' <i class="underline font-normal green">['.uh($sv['supplier_name']).']</i>' : '').'
									<input value="'.$sv['id'].'" type="hidden" class="selected_value_'.$sv['type_id'].' selected_value_'.$sv['type_id'].'_'.$day.'_'.$time.'" name="selected_value[]"/>
									<input value="'.$sv['type_id'].'" type="hidden" class="selected_value_'.$sv['type_id'].'" name="selected_type_id[]"/>											</li>';
			}
		}
		$place = [];
		if(post('place_id') > 0){
			$place = \app\modules\admin\models\DeparturePlaces::getItem(post('place_id'));
		}
				$html .= '</ul>
				</td>
				<td class="">
<div class="div-quick-search-service">
						<div class="fl w50">
						<select data-placeholder="Chọn địa danh" onchange="quick_search_tour_service(\'.input-quick-search-service\');" data-action="load_dia_danh" data-role="load_dia_danh" class="form-control input-sm ajax-chosen-select-ajax input-quick-search-local">';
				if(!empty($place)){
					$html .= '<option value="'.$place['id'].'" selected>'.$place['name'].'</option>'; 
				}
				$html .= '</select>
						</div><div class="fl w50">
						<input data-time="'.$time.'" data-day="'.$day.'" data-type_id="'.TYPE_CODE_DISTANCE.'" type="text" onkeyup="quick_search_tour_service(this);" onkeypress="return disabledFnKey(this);" placeholder="Tìm kiếm nhanh" class="form-control input-quick-search-service"/></div></div>				
<div class="fl100"><div class="available_services div-slim-scroll" data-height="auto">			
 
<ul id="sortable2" class="connectedSortable style-none">
   
</ul></div></div>
				
				</td>
				</tr>';
		
		
		$html .= '</tbody></table>';
		
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Đóng lại</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		//$r['event'] = $_POST['action'];
		echo json_encode([
				'html'=>$html,
				'event'=>$_POST['action'],
				'callback'=>true,
				'callback_function'=>'loadScrollDiv();loadSelectTagsinput1();jQuery(\'.li-service-first-child a\').click();jQuery("#sortable2").sortable({connectWith: ".connectedSortable",
receive:function(event,ui){
				(ui.item).addClass(\'ui-state-highlight\').removeClass(\'ui-state-default\')
				.find(\'input\').remove()
},
				
}).disableSelection();
jQuery("#sortable1").sortable({connectWith: ".connectedSortable",
receive:function(event,ui){
				$type_id = ui.item.attr(\'data-type_id\');
				$id = ui.item.attr(\'data-id\');
				(ui.item).removeClass(\'ui-state-highlight\').addClass(\'ui-state-default\')
				.append(\'<input value="\'+$id+\'" type="hidden" class="selected_value_\'+$type_id+\' selected_value_\'+$type_id+\'_'.$day.'_'.$time.' " name="selected_value[]"/><input value="\'+$type_id+\'" type="hidden" class="selected_value_\'+$type_id+\'" name="selected_type_id[]"/>\')
},
change:function(event,ui){
				//console.log(ui.item.index())
				//(ui.item).removeClass(\'ui-state-highlight\').addClass(\'ui-state-default\')
},
	start: function(event, ui) {
     
        console.log("Start position: " + ui.item.index());
    },
				stop: function(event, ui) {
     
        console.log("New position: " + ui.item.index());
    }
}).disableSelection();'
				//'alert'=>$state ? '' : 'Mã tour không hợp lệ hoặc đã được sử dụng.',
		]+$_POST);
		exit;
		break;
	case 'add-tours-services':
		$id = post('id',0);
		$day = post('day',0);
		$time = post('time',0);		
		$html = '';
		
		$html .= '<p class="help-block">Bạn đang chọn dịch vụ cho ngày thứ <b class="red">'.(post('day',0)+1).'</b> - buổi <b class="green underline">'.showPartDay(post('time',0)).'</b></p>
				<table class="table table-bordered vmiddle"><thead><tr>
				<th class="center bold col-ws-2">Dịch vụ</th>
				<th class="center bold col-ws-4">Dịch vụ đã chọn</th>
				<th class="center bold col-ws-4">Dịch vụ có thể chọn</th>
				</tr></thead><tbody>';
		$html .= '<tr class="vtop">
				<td class="">
				<ul class="style-none ul-style-l01">';
		foreach (showListChooseService() as $k=>$v){
			$html .= $v['id'] != TYPE_CODE_DISTANCE ? '<li class="'.($k==0 ? 'li-service-first-child' : '').'"><a data-day="'.$day.'" data-time="'.$time.'" data-id="'.$v['id'].'" onclick="return change_selected_tour_service_group(this);" href="#">'.$v['title'].'</a></li>' : '';
		}
		$services = \app\modules\admin\models\ToursPrograms::getProgramServices($id,$day,$time);
		$html .= '</ul>
				
				</td>
				<td class="">
				
				<ul id="sortable1" class="connectedSortable style-none">';
		if(!empty($services)){
			foreach ($services as $kv=>$sv){
				$package = \app\modules\admin\models\PackagePrices::getItem($sv['package_id']);
				$html .= '<li data-type_id="'.$sv['type_id'].'" data-id="'.$sv['id'].'" class="ui-state-default">'.(!empty($package) ? '<i class="underline green">['.uh($package['title']).']</i>&nbsp;' : '').(isset($sv['title']) ? uh($sv['title']) : uh($sv['name'])).(isset($sv['supplier_name']) ? ' <i class="underline font-normal green">['.uh($sv['supplier_name']).']</i>' : '').'
						<input value="'.$sv['id'].'" type="hidden" class="selected_value_'.$sv['type_id'].' selected_value_'.$sv['type_id'].'_'.$day.'_'.$time.'" name="selected_value[]"/>
						<input value="'.$sv['type_id'].'" type="hidden" class="selected_value_'.$sv['type_id'].'" name="selected_type_id[]"/>
						<input value="'.$sv['package_id'].'" type="hidden" class="selected_value_'.$sv['type_id'].'" name="selected_package_id[]"/>
						</li>';
			}
		}
		$place = [];
		if(post('place_id') > 0){
			$place = \app\modules\admin\models\DeparturePlaces::getItem(post('place_id'));
		}
				$html .= '</ul>
				</td>
				<td class="">
<div class="div-quick-search-service">
						<div class="fl w50">
						<select data-placeholder="Chọn địa danh" onchange="quick_search_tour_service(\'.input-quick-search-service\');" data-action="load_dia_danh" data-role="load_dia_danh" class="form-control input-sm ajax-chosen-select-ajax input-quick-search-local">';
				if(!empty($place)){
					$html .= '<option value="'.$place['id'].'" selected>'.$place['name'].'</option>'; 
				}
				$html .= '</select>
						</div><div class="fl w50">
						<input data-time="'.$time.'" data-day="'.$day.'" data-type_id="'.TYPE_ID_HOTEL.'" type="text" onkeyup="quick_search_tour_service(this);" onkeypress="return disabledFnKey(this);" placeholder="Tìm kiếm nhanh" class="form-control input-quick-search-service"/></div></div>				
<div class="fl100"><div class="available_services div-slim-scroll" data-height="auto">				
 
<ul id="sortable2" class="connectedSortable style-none">
   
</ul></div></div>
				
				</td>
				</tr>';
		
		
		$html .= '</tbody></table>';
		
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Đóng</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		//$r['event'] = $_POST['action'];
		echo json_encode([
				'html'=>$html,
				'event'=>$_POST['action'],
				'callback'=>true,
				'callback_function'=>'loadScrollDiv(); loadSelectTagsinput1();jQuery(\'.li-service-first-child a\').click();jQuery("#sortable2").sortable({connectWith: ".connectedSortable",
receive:function(event,ui){
				(ui.item).addClass(\'ui-state-highlight\').removeClass(\'ui-state-default\')
				.find(\'input\').remove()
},
				
}).disableSelection();
jQuery("#sortable1").sortable({connectWith: ".connectedSortable",
receive:function(event,ui){
				$type_id = ui.item.attr(\'data-type_id\');
				$id = ui.item.attr(\'data-id\');
				$package_id = ui.item.attr(\'data-package_id\');
				(ui.item).removeClass(\'ui-state-highlight\').addClass(\'ui-state-default\')
				.append(\'<input value="\'+$package_id+\'" type="hidden" class="selected_value_\'+$type_id+\'" name="selected_package_id[]"/><input value="\'+$id+\'" type="hidden" class="selected_value_\'+$type_id+\' selected_value_\'+$type_id+\'_'.$day.'_'.$time.' " name="selected_value[]"/><input value="\'+$type_id+\'" type="hidden" class="selected_value_\'+$type_id+\'" name="selected_type_id[]"/>\')
},
change:function(event,ui){
				//console.log(ui.item.index())
				//(ui.item).removeClass(\'ui-state-highlight\').addClass(\'ui-state-default\')
},
	start: function(event, ui) {
     
        console.log("Start position: " + ui.item.index());
    },
				stop: function(event, ui) {
     
        console.log("New position: " + ui.item.index());
    }
}).disableSelection();'
				//'alert'=>$state ? '' : 'Mã tour không hợp lệ hoặc đã được sử dụng.',
		]+$_POST);
		exit;
		break;
		
	case 'add-more-distance-supplier':
		
		$id = post('id',0);
		$day = post('day',0);
		$time = post('time',0);		
		$html = '';
		
		$html .= '
				<table class="table table-bordered vmiddle"><thead><tr>
				 
				<th class="center bold col-ws-6">Danh sách đã chọn</th>
				<th class="center bold col-ws-6">Danh sách có thể chọn</th>
				</tr></thead><tbody>';
		$html .= '<tr class="vtop">
				 
				<td class="">
				
				<ul id="sortable1" class="connectedSortable style-none">';
		if(!empty($services)){
			foreach ($services as $kv=>$sv){
				$html .= '<li data-type_id="'.$sv['type_id'].'" data-id="'.$sv['id'].'" class="ui-state-default">'.(isset($sv['title']) ? uh($sv['title']) : uh($sv['name'])).(isset($sv['supplier_name']) ? ' <i class="underline font-normal green">['.uh($sv['supplier_name']).']</i>' : '').'
									<input value="'.$sv['id'].'" type="hidden" class="selected_value_'.$sv['type_id'].' selected_value_'.$sv['type_id'].'_'.$day.'_'.$time.'" name="selected_value[]"/>
									<input value="'.$sv['type_id'].'" type="hidden" class="selected_value_'.$sv['type_id'].'" name="selected_type_id[]"/>											</li>';
			}
		}
		$place = [];
		if(post('place_id') > 0){
			$place = \app\modules\admin\models\DeparturePlaces::getItem(post('place_id'));
		}
				$html .= '</ul>
				</td>
				<td class="">
<div class="div-quick-search-service">
						<div title="Chọn địa danh" class="fl w50">
						<select data-placeholder="Chọn địa danh" onchange="quick_search_tour_service(\'.input-quick-search-service\');" data-action="load_dia_danh" data-role="load_dia_danh" class="form-control input-sm ajax-chosen-select-ajax input-quick-search-local">';
				if(!empty($place)){
					$html .= '<option value="'.$place['id'].'" selected>'.$place['name'].'</option>'; 
				}
				$html .= '</select>
						</div><div class="fl w50">
						<input data-time="'.$time.'" data-day="'.$day.'" data-type_id="'.TYPE_ID_VECL.'" type="text" onkeyup="quick_search_tour_service(this);" onkeypress="return disabledFnKey(this);" placeholder="Tìm kiếm nhanh" class="form-control input-quick-search-service"/></div></div>				
<div class="fl100"><div class="available_services div-slim-scroll" data-height="auto">				
 
<ul id="sortable2" class="connectedSortable style-none">
   
</ul></div>	</div>			
				</td>
				</tr>';
		
		
		$html .= '</tbody></table>';
		
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Đóng lại</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		//$r['event'] = $_POST['action'];
		echo json_encode([
				'html'=>$html,
				'event'=>$_POST['action'],
				'callback'=>true,
				'callback_function'=>'loadScrollDiv();quick_search_tour_service(\'.input-quick-search-service\');loadSelectTagsinput1();jQuery(\'.li-service-first-child a\').click();jQuery("#sortable2").sortable({connectWith: ".connectedSortable",
receive:function(event,ui){
				(ui.item).addClass(\'ui-state-highlight\').removeClass(\'ui-state-default\')
				.find(\'input\').remove()
},
				
}).disableSelection();
jQuery("#sortable1").sortable({connectWith: ".connectedSortable",
receive:function(event,ui){
				$type_id = ui.item.attr(\'data-type_id\');
				$id = ui.item.attr(\'data-id\');
				(ui.item).removeClass(\'ui-state-highlight\').addClass(\'ui-state-default\')
				.append(\'<input value="\'+$id+\'" type="hidden" class="selected_value_\'+$type_id+\' selected_value_\'+$type_id+\'_'.$day.'_'.$time.' " name="selected_value[]"/><input value="\'+$type_id+\'" type="hidden" class="selected_value_\'+$type_id+\'" name="selected_type_id[]"/>\')
},
change:function(event,ui){
				//console.log(ui.item.index())
				//(ui.item).removeClass(\'ui-state-highlight\').addClass(\'ui-state-default\')
},
	start: function(event, ui) {
     
        console.log("Start position: " + ui.item.index());
    },
				stop: function(event, ui) {
     
        console.log("New position: " + ui.item.index());
    }
}).disableSelection();'
				//'alert'=>$state ? '' : 'Mã tour không hợp lệ hoặc đã được sử dụng.',
		]+$_POST);
		exit;
		break;	
	case 'quick-quick-edit-supplier-services':
		//
		$selected_value = post('selected_value');
		$selected_quantity = post('selected_quantity');
		$supplier_id = post('supplier_id');
		$item_id = post('item_id');
		//
		Yii::$app->db->createCommand()->delete('tours_programs_to_suppliers',['supplier_id'=>$supplier_id,'item_id'=>$item_id])->execute();
		//
		if(!empty($selected_value)){
			foreach ($selected_value as $k=>$v){
				if($selected_quantity[$k]>0){
					Yii::$app->db->createCommand()->insert('tours_programs_to_suppliers',[
							'supplier_id'=>$supplier_id,
							'item_id'=>$item_id,
							'vehicle_id'=>$v,
							'quantity'=>$selected_quantity[$k],
							'type_id'=>TYPE_ID_VECL
					])->execute();
				}
			}
		}
		//
		echo json_encode([
			'event'=>'hide-modal',
			'callback'=>true,
			//'post'=>$_POST,
			'callback_function'=>'reloadAutoPlayFunction();'
		]); exit;
		break;
	case 'quick-edit-supplier-services':
		 
		$id = post('id',0);
		$day = post('day',0);
		$time = post('time',0);
		
		$services = Yii::$app->zii->chooseVehicleAuto([
				//'totalPax'=>post('total_pax',0),
				'nationality'=>post('nationality',0),
				'supplier_id'=>post('supplier_id'),
				'item_id'=>post('item_id'),
				////'auto'=>true,
				//'update'=>true,
		]);
		 
		$html = '';
		
		$html .= '
				<table class="table table-bordered vmiddle"><thead><tr>
			
				<th class="center bold col-ws-6">Danh sách đã chọn</th>
				<th class="center bold col-ws-6">Danh sách có thể chọn</th>
				</tr></thead><tbody>';
		$html .= '<tr class="vtop">
			
				<td class="">
		<div class="col-sm-8 bold col-border-left col-border-top col-border-right pd8">Tên phương tiện</div>
		<div class="center col-sm-4 bold col-border-right col-border-top pd8">Số lượng</div>
				<ul id="sortable1" class="connectedSortable style-none">';
		if(!empty($services)){
			foreach ($services as $kv=>$sv){
				$sv['type_id'] = TYPE_CODE_VEHICLE;
				$html .= '<li data-type_id="'.$sv['type_id'].'" data-id="'.$sv['id'].'" class="ui-state-default">
						
						<div class="col-sm-8 col-index-1 col-border-right">'.uh($sv['title']).' <i class="underline font-normal green">['.uh($sv['maker_title']).']</i></div>
								<div class="col-sm-4 col-index-2"><input type="text" class="form-control center number-format selected_quantity" name="selected_quantity[]" data-name="selected_quantity[]" placeholder="Số lượng" value="'.$sv['quantity'].'"/></div>
								
									<input value="'.$sv['id'].'" type="hidden" class="selected_value_'.$sv['type_id'].' selected_value_'.$sv['type_id'].'_'.$day.'_'.$time.'" name="selected_value[]"/>
									<input value="'.$sv['type_id'].'" type="hidden" class="selected_value_'.$sv['type_id'].'" name="selected_type_id[]"/>											
				 							
				</li>';
			}
		}
		$place = [];
		if(post('place_id') > 0){
			$place = \app\modules\admin\models\DeparturePlaces::getItem(post('place_id'));
		}
		$html .= '</ul>
				</td>
				<td class="">
<div class="div-quick-search-service">
						<div title="Chọn địa danh" class="hide">
						<select data-placeholder="Chọn địa danh" onchange="quick_search_tour_service(\'.input-quick-search-service\');" data-action="load_dia_danh" data-role="load_dia_danh" class="form-control input-sm ajax-chosen-select-ajax input-quick-search-local"></select>
						</div><div class="fl w100">
						<input data-supplier_id="'.post('supplier_id',0).'" data-time="'.$time.'" data-day="'.$day.'" data-type_id="'.TYPE_CODE_VEHICLE.'" type="text" onkeyup="quick_search_tour_service(this);" onkeypress="return disabledFnKey(this);" placeholder="Tìm kiếm nhanh" class="form-control input-quick-search-service"/></div></div>
<div class="fl100"><div class="available_services div-slim-scroll" data-height="auto">	
		
<ul id="sortable2" class="connectedSortable style-none">
  
</ul></div></div>
				</td>
				</tr>';
		
		
		$html .= '</tbody></table>';
		
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Đóng lại</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		//$r['event'] = $_POST['action']; 
		echo json_encode([
				'html'=>$html,
				'event'=>$_POST['action'],
				'callback'=>true,
				'callback_function'=>'loadScrollDiv();quick_search_tour_service(\'.input-quick-search-service\');loadSelectTagsinput1();jQuery(\'.li-service-first-child a\').click();jQuery("#sortable2").sortable({connectWith: ".connectedSortable",
receive:function(event,ui){
				(ui.item).addClass(\'ui-state-highlight\').removeClass(\'ui-state-default\')
				.find(\'input.selected_value_\'+$type_id+\'\').remove();
				$iq = ui.item.find(\'input.selected_quantity\');
				$iq.removeAttr(\'name\');
},
		
}).disableSelection();
jQuery("#sortable1").sortable({connectWith: ".connectedSortable",
receive:function(event,ui){
				$type_id = ui.item.attr(\'data-type_id\');
				$id = ui.item.attr(\'data-id\');
				(ui.item).removeClass(\'ui-state-highlight\').addClass(\'ui-state-default\')
				.append(\'<input value="\'+$id+\'" type="hidden" class="selected_value_\'+$type_id+\' selected_value_\'+$type_id+\'_'.$day.'_'.$time.' " name="selected_value[]"/><input value="\'+$type_id+\'" type="hidden" class="selected_value_\'+$type_id+\'" name="selected_type_id[]"/>\')
				.append(\'\');
				$iq = ui.item.find(\'input.selected_quantity\');
				$iq.attr(\'name\',$iq.attr(\'data-name\'));
},
change:function(event,ui){
				//console.log(ui.item.index())
				//(ui.item).removeClass(\'ui-state-highlight\').addClass(\'ui-state-default\')
},
	start: function(event, ui) {
   
        console.log("Start position: " + ui.item.index());
    },
				stop: function(event, ui) {
   
        console.log("New position: " + ui.item.index());
    }
}).disableSelection();'
				//'alert'=>$state ? '' : 'Mã tour không hợp lệ hoặc đã được sử dụng.',
		]+$_POST);
		exit;
		break;
	case 'getExchangeRateToday':
		$price = Yii::$app->zii->getExchangeRate(post('from'),post('to'));
		echo json_encode([
				'price'=>$price
		]);exit;
		
		break;
	case 'quick-change-exchange-rate':
		$price = post('price');
		$item_id = post('item_id');
		foreach ($price as $from=>$v){
			foreach ($v as $to=>$value){
				if((new Query())->from('tours_programs_exchange_rate')->where([
						'item_id'=>$item_id,
						'from_currency'=>$from,
						'to_currency'=>$to
				])->count(1) == 0){
					Yii::$app->db->createCommand()->insert('tours_programs_exchange_rate',[
							'item_id'=>$item_id,
							'from_currency'=>$from,
							'to_currency'=>$to,
							'value'=>$value
					])->execute();
				}else{
					Yii::$app->db->createCommand()->update('tours_programs_exchange_rate',[
						'value'=>$value	
					],[
							'item_id'=>$item_id,
							'from_currency'=>$from,
							'to_currency'=>$to,
							
					])->execute();
				}
			}
		}
		echo json_encode([
				'event'=>'hide-modal',
				'callback'=>true,
				'callback_function'=>'reloadAllTourProgramPrices();'
		]);
		exit;
		break;
	
	case 'change-exchange-rate':
		 
		$currency = post('currency');
		$symbol = Yii::$app->zii->showCurrency($currency,1);
		$decimal_number = Yii::$app->zii->showCurrency($currency,3);
		$item_id = post('item_id');
		$html = '';
		 
		$html .= '
				<table class="table table-bordered vmiddle"><thead><tr>
				<th class="center bold col-ws-3">Tiền tệ</th>
				<th class="center bold col-ws-4">Tỷ giá</th>
				<th class="center bold col-ws-4">Tỷ giá hôm nay</th>
				 <th class="center bold col-ws-1"></th>
				</tr></thead><tbody>';
		//if(!empty($services)){
			foreach (Yii::$app->zii->getUserCurrency()['list'] as $k1=>$v1){ 
				if($v1['id'] != $currency){ 
				$html .= '<tr class="">
			
				<td class="center bold">
		 '.($v1['code'] . ' - ' . $symbol).'
				</td>
				<td class="center bold ">
		 <input type="text" name="price['.$v1['id'].']['.$currency.']" value="'.Yii::$app->zii->getItemExchangeRate(
		 		[ 
		 		'item_id'=>$item_id,		
		 		'from'=>$v1['id'],
		 		'to'=>$currency,		
		 		'time'=>post('time')
		 ]).'" class="form-control aright number-format input-currency-exchange-rate" data-decimal="4"/>
				</td>
		 		<td class="center"><b>'.number_format(Yii::$app->zii->getExchangeRate($v1['id'],$currency),4).'</b></td>
				 <td class="center"><i data-from="'.$v1['id'].'" data-to="'.$currency.'" onclick="getExchangeRateToday(this);" title="Lấy tỷ giá hôm nay" class="fa fa-refresh pointer"></i></td>
				</tr>';
				}
			}
		//}
		 
		$html .= '</tbody></table>';
		
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Đóng</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		//$r['event'] = $_POST['action']; 
		echo json_encode([
				'html'=>$html,
				'event'=>$_POST['action'],
				'callback'=>true,
				'callback_function'=>'load_number_format();'
				//'alert'=>$state ? '' : 'Mã tour không hợp lệ hoặc đã được sử dụng.',
		]+$_POST);
		exit;
		break;	
	case 'quick-qedit-service-detail':
		$price = post('price') > 0 ? post('price') : 0;
		if((new Query())->from('tours_programs_suppliers_prices')->where([
				'supplier_id'=>post('supplier_id'),
				'vehicle_id'=>post('vehicle_id'),
				'item_id'=>post('item_id'),
				'service_id'=>post('service_id'),
		])->count(1) == 0){
			Yii::$app->db->createCommand()->insert('tours_programs_suppliers_prices',[
				'supplier_id'=>post('supplier_id'),
				'vehicle_id'=>post('vehicle_id'),
				'item_id'=>post('item_id'),
				'service_id'=>post('service_id'),
				'price1'=>$price	,
				'distance'=>post('distance',1),	
			])->execute();
		}else{
			Yii::$app->db->createCommand()->update('tours_programs_suppliers_prices',[
					 
					'price1'=>$price,
					'distance'=>post('distance',1),
			],[
					'supplier_id'=>post('supplier_id'),
					'vehicle_id'=>post('vehicle_id'),
					'item_id'=>post('item_id'),
					'service_id'=>post('service_id'),
					
				 
			])->execute();
		}
		
		
		echo json_encode([
				//'html'=>$html,
				'event'=>'hide-modal',
				'callback'=>true,
				'callback_function'=>'reloadAutoPlayFunction();'
		]+$_POST);
		exit;
		break;
	case 'reloadDistanceServicePriceAuto':
		$prices = Yii::$app->zii->calcDistancePrice([
				'supplier_id'=>post('supplier_id'),
				'vehicle_id'=>post('vehicle_id'),
				'distance_id'=>post('service_id'),
				//'item_id'=>post('item_id')
		]);
		echo json_encode($prices);exit;
		break;
	case 'qedit-service-detail':
		 
		$id = post('id',0);
		$day = post('day',0);
		$time = post('time',0);
		
		$services = \app\modules\admin\models\ToursPrograms::getProgramDistanceServices(post('item_id'),post('supplier_id'),[
				//'vehicle_id'=>post('vehicle_id')
		]);
		 
		$prices = Yii::$app->zii->calcDistancePrice([
				'supplier_id'=>post('supplier_id'),
				'vehicle_id'=>post('vehicle_id'),
				'distance_id'=>post('service_id'),
				'item_id'=>post('item_id')
		]);
		$html = '';
		
		$html .= '
				<table class="table table-bordered vmiddle"><thead><tr>
				<th class="center bold col-ws-2">Nhà cung cấp</th>
				<th class="center bold col-ws-3">Loại phương tiện</th>
				<th class="center bold col-ws-3">Chặng di chuyển</th>
				<th class="center bold col-ws-1">Khoảng cách (km)</th>
				<th class="center bold col-ws-2">Đơn giá</th>
				<th class="center bold col-ws-1"></th>
				</tr></thead><tbody>';
		//if(!empty($services)){
		//	foreach ($services as $kv=>$sv){
				$html .= '<tr class="">
			
				<td class="">
		 '.(isset($prices['supplier']['name']) ? $prices['supplier']['name'] : '').'
				</td>
				<td class="">
		 '.(isset($prices['vehicle']['title']) ? $prices['vehicle']['title'] : '').'
				</td>
				<td class="">
		 		  '.(isset($prices['distance']['title']) ? $prices['distance']['title'] : '').'
				</td>
				<td class="center">
		  '.($prices['price_type'] == 1 ? '<input name="distance" value="'.$prices['distance']['distance'].'" type="text" class="input-distance-service-distance form-control bold center aright number-format"/>' : '-').'
				
		  		</td>
				<td class=""> 
						<input name="price" value="'.$prices['price'].'" type="text" class="input-distance-service-price form-control bold aright number-format"/>
				</td>
				<td class="center"> 
						<i data-vehicle_id="'.post('vehicle_id').'" data-service_id="'.post('service_id').'" data-supplier_id="'.post('supplier_id').'" onclick="reloadDistanceServicePriceAuto(this)" class="fa fa-refresh f12e pointer" title="Tính giá tự động theo số liệu hệ thống"></i>
				</td>
				</tr>';
		//	}
		//}
		
		
		
		$html .= '</tbody></table>';
		
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Đóng</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		//$r['event'] = $_POST['action']; 
		echo json_encode([
				'html'=>$html,
				'event'=>$_POST['action'],
				'callback'=>true,
				'callback_function'=>'load_number_format();'
				//'alert'=>$state ? '' : 'Mã tour không hợp lệ hoặc đã được sử dụng.',
		]+$_POST);
		exit;
		break;	
	case 'qedit-service-detail-day':
		 
		$id = post('id',0);
		$day_id = post('day_id',0);
		$time_id = post('time_id',-1);
		
		$services = \app\modules\admin\models\ToursPrograms::getProgramDistanceServices(post('item_id',0),post('supplier_id',0),[
				//'vehicle_id'=>post('vehicle_id')
		]);
		 
		$prices = Yii::$app->zii->calcDistancePrice([
				'supplier_id'=>post('supplier_id'),
				'vehicle_id'=>post('vehicle_id'),
				'distance_id'=>post('service_id'),
				'item_id'=>post('item_id')
		]);
		$html = '';
		
		$html .= '
				<table class="table table-bordered vmiddle"><thead><tr>
				<th class="center bold col-ws-6">Dịch vụ / Nhà cung cấp DV</th>
				<th class="center bold col-ws-1">Loại DV</th>
				<th class="center bold col-ws-1">ĐVT</th>
				<th class="center bold col-ws-1">Số lượng</th>
				<th class="center bold col-ws-2">Đơn giá</th>
				<th class="center bold col-ws-1"></th>
				</tr></thead><tbody>';
		//if(!empty($services)){
		//	foreach ($services as $kv=>$sv){
				$html .= '<tr class="">
			
				<td class="">
		 '.(isset($prices['supplier']['name']) ? $prices['supplier']['name'] : '').'
				</td>
				<td class="">
		 '.(isset($prices['vehicle']['title']) ? $prices['vehicle']['title'] : '').'
				</td>
				<td class="">
		 		  '.(isset($prices['distance']['title']) ? $prices['distance']['title'] : '').'
				</td>
				<td class="center">
		  '.($prices['price_type'] == 1 ? '<input name="distance" value="'.$prices['distance']['distance'].'" type="text" class="input-distance-service-distance form-control bold center aright number-format"/>' : '-').'
				
		  		</td>
				<td class=""> 
						<input name="price" value="'.$prices['price'].'" type="text" class="input-distance-service-price form-control bold aright number-format"/>
				</td>
				<td class="center"> 
						<i data-vehicle_id="'.post('vehicle_id').'" data-service_id="'.post('service_id').'" data-supplier_id="'.post('supplier_id').'" onclick="reloadDistanceServicePriceAuto(this)" class="fa fa-refresh f12e pointer" title="Tính giá tự động theo số liệu hệ thống"></i>
				</td>
				</tr>';
		//	}
		//}
		
		
		
		$html .= '</tbody></table>';
		
		$html .= '<div class="modal-footer">';
		$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Đóng</button>';
		$html .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		//$r['event'] = $_POST['action']; 
		echo json_encode([
				'html'=>$html,
				'event'=>$_POST['action'],
				'callback'=>true,
				'callback_function'=>'load_number_format();'
				//'alert'=>$state ? '' : 'Mã tour không hợp lệ hoặc đã được sử dụng.',
		]+$_POST);
		exit;
		break;		
	case 'loadTourProgramDistances':
		$html = ''; 
		 
		$model = load_model('tours_programs');
		$id = post('id',0);
		$supplier_id = post('supplier_id',0); 
		$item = \app\modules\admin\models\ToursPrograms::getItem($id);		
		$j=-1;$i=-1;
		foreach (Yii::$app->zii->getTourProgramSuppliers($id) as $k=>$v){
			 
			$selected_car = Yii::$app->zii->chooseVehicleAuto([
					'totalPax'=>post('total_pax',0),
					'nationality'=>post('nationality',0),
					'supplier_id'=>$v['id'], 
					'item_id'=>$id,
					////'auto'=>true,
					//'update'=>true,
			]);
			$services = \app\modules\admin\models\ToursPrograms::getProgramDistanceServices($id,$v['id']);
			$colspan1 = count($services)+1;
			$colspan2 = (($colspan1) * count($selected_car)) + 1;
			
			
			
			$html .= '<tr><td rowspan="'.$colspan2.'" class="center"><button class="btn btn-sm btn-label btn-primary" type="button" data-item_id="'.$id.'" data-nationality="'.$item['nationality'].'" data-action="quick-edit-supplier-services" data-supplier_id="'.$v['id'].'" data-class="w90" onclick="open_ajax_modal(this);return false;" data-title="Chỉnh sửa thông tin <b class=red>'.($v['name']).'</b>">'.($v['name']).'</button>';
			
			$html .= '<input type="hidden" value="'.$v['id'].'" class="selected_value_'.TYPE_ID_VECL.' selected_value_'.TYPE_ID_VECL.'_0_0" name="selected_value[]"/>';
			
			$html .= '</td></tr>';
			//for($j=0;$j<4;$j++){
			foreach ($selected_car as $k3=>$car){
				$html .= '<tr><td class="center" rowspan="'.($k3 == count($selected_car)-1 ? ($colspan1) : $colspan1).'" colspan="2"><a data-item_id="'.$id.'" data-nationality="'.$item['nationality'].'" data-action="quick-edit-supplier-services" data-supplier_id="'.$v['id'].'" data-class="w90" href="#" onclick="open_ajax_modal(this);return false;" data-title="Chỉnh sửa thông tin <b class=red>'.($v['name']).'</b>"><span class="label label-danger f12p">'.$car['title'].'</span></a></td>';
				$html .= '<td class="center" rowspan="'.($k3 == count($selected_car)-1 ? ($colspan1) : $colspan1).'" colspan="1"><a data-item_id="'.$id.'" data-nationality="'.$item['nationality'].'" data-action="quick-edit-supplier-services" data-supplier_id="'.$v['id'].'" data-class="w90" href="#" onclick="open_ajax_modal(this);return false;" data-title="Chỉnh sửa thông tin <b class=red>'.($v['name']).'</b>"><span class="badge">'.$car['quantity'].'</span></a></td>';
				
				$html .= '</tr>';
				if(!empty($services)){
					foreach ($services as $kv=>$sv){
						//
						$distance = isset($sv['distance']) && $sv['distance'] > 0 ? $sv['distance'] : -1;
						$prices = Yii::$app->zii->calcDistancePrice([
								'supplier_id'=>$v['id'],
								'vehicle_id'=>$car['id'],
								'distance_id'=>$sv['id'],
								'item_id'=>$id,
						]);
						// 
						$html .= '<tr><td colspan="5"><p><a data-item_id="'.$id.'" data-vehicle_id="'.$car['id'].'" data-supplier_id="'.$v['id'].'" data-service_id="'.$sv['id'].'" data-class="w80" data-action="qedit-service-detail" data-title="Chỉnh sửa dịch vụ" href="#" onclick="open_ajax_modal(this);return false;">  ' .(isset($sv['title']) ? uh($sv['title']) : uh($sv['name'])).(isset($sv['supplier_name']) ? ' <i class="underline font-normal green">['.uh($sv['supplier_name']).']</i>' : '').'
									<input value="'.$sv['id'].'" type="hidden" class="selected_value_'.$sv['type_id'].' selected_value_'.$sv['type_id'].'_'.$i.'_'.$j.'" name="selected_value[]"/>
									<input value="'.$sv['type_id'].'" type="hidden" class="selected_value_'.$sv['type_id'].'" name="selected_type_id[]"/>
											</a></p></td>';
						$html .= '<td class="center" colspan="1">'.($prices['price_type'] == 1 ? '<a data-item_id="'.$id.'" data-vehicle_id="'.$car['id'].'" data-supplier_id="'.$v['id'].'" data-service_id="'.$sv['id'].'" data-class="w80" data-action="qedit-service-detail" data-title="Chỉnh sửa dịch vụ" href="#" onclick="open_ajax_modal(this);return false;">'. number_format($prices['distance']['distance']) .'</a>' : '-').'</td>';
						$html .= '<td class="center" colspan="1"><a data-item_id="'.$id.'" data-vehicle_id="'.$car['id'].'" data-supplier_id="'.$v['id'].'" data-service_id="'.$sv['id'].'" data-class="w80" data-action="qedit-service-detail" data-title="Chỉnh sửa dịch vụ" href="#" onclick="open_ajax_modal(this);return false;">'.number_format($prices['price']).'</a></td>';
						$html .= '<td class="center" colspan="1"><b class="underline">'.number_format($prices['total_price']).'</b></td>';
						$html .= '</tr>';
					}
				}
			}
				
				 
			//}
					
				//	$html .= '<td class="center" colspan="1">'.$selected_car['quantity'].'</td>';
				//	$html .= '<td class="center" colspan="1">'.$selected_car['quantity'].'</td>';
				//	$html .= '<td class="center" colspan="1">'.$selected_car['quantity'].'</td>';
				
 
				
				$html .= '<tr><td colspan="12" class="pr vtop">
						<p class=" aright bgef"> 
							<button data-place_id="'.$v['place_id'].'" data-class="w90" data-action="add-tours-distance-services" data-title="Chọn thêm dịch vụ / Hành trình - <b class=red>'.$v['name'].'</b>" data-id="'.$id.'" data-supplier_id="'.$v['id'].'" data-time="'.$j.'" onclick="open_ajax_modal(this);" data-toggle="tooltip" data-placement="left" title="Chọn thêm / xóa dịch vụ cho '.$v['name'].'" class="btn btn-primary input-sm" type="button"><i class="glyphicon glyphicon glyphicon-pencil"></i> Thêm/ xóa chặng di chuyển</button></p>
						</td></tr>';
		 
		}
		$html .= '<tr><td colspan="12" class="pr vtop">
						<p class=" aright "> 
							<button data-nationality="'.post('nationality').'" data-guest="'.post('guest').'" data-class="w90" data-action="add-more-distance-supplier" data-title="Chọn thêm nhà xe" data-id="'.$id.'" onclick="open_ajax_modal(this);" title="Chọn thêm nhà xe" class="btn btn-warning input-sm" type="button"><i class="glyphicon glyphicon glyphicon-plus"></i> Chọn thêm nhà xe</button></p>
						</td></tr>';
		echo json_encode([
		'html'=>$html,
		]+$_POST);exit; 
		break;	
	case 'loadTourProgramDetail':
		$html = ''; 
		//$model = load_model('tours_programs');
		$id = post('id',0);
		$day = post('day',0);
		$html = loadTourProgramDetail([
				'day'=>$day, 'id'=>$id
		]); 
		echo json_encode([
				'html'=>$html,
		]+$_POST);exit;
		
		$v = \app\modules\admin\models\ToursPrograms::getItem($id);
		
		for($i = 0; $i<$day;$i++){
			$colspan2 = \app\modules\admin\models\ToursPrograms::countProgramServicesPerDay([
					'item_id'=>$id,
					'day'=>$i
			]);
			
			$colspan2  += 9;
			$html .= '<tr><td rowspan="'.$colspan2.'" class="center"><span class="label label-danger f12p">Ngày '.($i+1).'</span></td></tr>';
			for($j=0;$j<4;$j++){
				$services = \app\modules\admin\models\ToursPrograms::getProgramServices($id,$i,$j);
				
				$rowspan1 = max(count($services),0) + 1;
				switch ($j){
					case 1: $class='btn-success';break;
					case 2: $class='btn-info';break;
					case 3: $class='btn-warning';break;
					default: $class='btn-primary';break;
				}
				$html .= '<tr><td rowspan="'.$rowspan1.'" class="center"><button data-class="w90" data-action="add-tours-services" data-title="Chọn thêm dịch vụ / Hành trình" data-id="'.$id.'" data-day="'.$i.'" data-time="'.$j.'" onclick="open_ajax_modal(this);" title="Chọn thêm / xóa dịch vụ" class="w50p btn '.$class.' btn-label btn-sm first-letter-upper" type="button">'.showPartDay($j).'</button></td></tr>';
				 
				if(!empty($services)){
					foreach ($services as $kv=>$sv){
						
						$prices = Yii::$app->zii->getServiceDetailPrices([
								'item_id'=>$id,
								'day'=>$i,
								'time'=>$j,
								'service_id'=>$sv['id'],
								'type_id'=>$sv['type_id'],
								'nationality'=>$v['nationality'],
								'total_pax'=>$v['guest']
						]);
						 
						if(!empty($prices)){
							$price = Yii::$app->zii->getServicePrice($prices['price1'],[
									'item_id'=>$id,
									//'price'=>$prices['price1'],
									'from'=>$prices['currency'],
									'to'=>$v['currency']
							]);
						}
						$html .= '<tr><td colspan="4"><p><a data-type_id="'.$sv['type_id'].'">' .(isset($sv['title']) ? uh($sv['title']) : uh($sv['name'])).(isset($sv['supplier_name']) ? ' <i class="underline font-normal green">['.uh($sv['supplier_name']).']</i>' : '').'
								<input value="'.$sv['id'].'" type="hidden" class="selected_value_'.$sv['type_id'].' selected_value_'.$sv['type_id'].'_'.$i.'_'.$j.'" name="selected_value[]"/>
								<input value="'.$sv['type_id'].'" type="hidden" class="selected_value_'.$sv['type_id'].'" name="selected_type_id[]"/>											
										</a></p></td>
										<td class="center " colspan="2">'.getServiceType($sv['type_id']).'</td> 
										<td class="center">'.getServiceUnitPrice($sv['type_id']).'</td>
										<td class="center">'.number_format($prices['quantity']).'</td>
										<td class="center" ><span data-decimal="'.(isset($price['decimal']) ? $price['decimal'] : 0).'" class="number-format '.(isset($price['changed']) && $price['changed'] ? 'red underline' : '').'" title="'.(isset($price['changed']) && $price['changed'] ? $price['old_price'] : '').'">'.(isset($price['price']) ? $price['price'] : '-').'</span></td>
										<td class="center" ><span data-decimal="'.(isset($price['decimal']) ? $price['decimal'] : 0).'" class="bold underline number-format " >'.(isset($price['price']) ? $price['price'] * $prices['quantity'] : '-').'</span></td>
										</tr>';
					}
				}
				 
					
					$html .= '<tr><td colspan="11" class="pr vtop">
							<p class=" aright bgef"><i>Thêm/ xóa dịch vụ </i>
							<button data-class="w90" data-action="add-tours-services" data-title="Chọn thêm dịch vụ / Hành trình" data-id="'.$id.'" data-day="'.$i.'" data-time="'.$j.'" onclick="open_ajax_modal(this);" title="Chọn thêm / xóa dịch vụ" class="btn btn-primary input-sm" type="button"><i class="glyphicon glyphicon glyphicon-pencil"></i></button></p>
							</td>';
					
					$html .= '</tr>';
					
			}
					
				
		}
		
		echo json_encode([
		'html'=>$html,
		]+$_POST);exit; 
		break;
	case 'loadSupplierHightway':
		$id = post('id',0);
		if($id == 0) {echo json_encode(['html'=>'<p class="help-block">Bạn cần lưu dữ liệu trước khi thêm chặng cao tốc</p>']+$_POST);exit;}
		$existed = [];
		$html = '<div class="col-sm-12 "><div class="row"><p class="grid-sui-pheader bold aleft"><i style="font-weight: normal;">Chặng cao tốc - Đò phà</i></p></div></div>';
		
		
		$model = load_model('distances');
		$v= $model->getItem($id);
		$l3 = $model->get_list_seats();
		$l4 = $model->get_list_hight_way ($id);
		
		$existed = []; 
		if(!empty($l3)){
		$html .= '<div class="col-sm-12 "><div class="row"><table class="table table-bordered vmiddle mgt15"><thead>
		<tr>';
		$html .= '<th rowspan="2">Tiêu đề</th>';
		if(!empty($l3)){
			foreach ($l3 as $t3){
				$html .= '<th class="center mw120p">Xe '.$t3.' chỗ</th>';		
				 
			}
		}
		$html .= '<th rowspan="2" class="center w100p">Tiền tệ</th>';
		$html .= '<th rowspan="2" class="center w100p">Bắt buộc</th>';
		$html .= '<th rowspan="2" class="center w100p">Khứ hồi</th>';
		$html .= '<th rowspan="2" class="center w50p">Xóa</th>';
		$html .= '</tr>
		</thead><tbody>';
		if(!empty($l4)){
			foreach ($l4 as $k4=>$v4){
				//
				//view($v4);
				$currency = 1;
		
				if(!in_array($v4['id'] , $existed)) $existed[] = $v4['id'];
				//
				$html .= '<tr class="tr-distance-id-'.$v4['id'].'"><td><a href="'.(\app\modules\admin\models\AdminMenu::get_menu_link('hight_way',$v4['type_id'])).'/edit?id='.$v4['id'].'#tab-panel-prices" target="_blank">'.$v4['title'].'</a></td>';
				if(!empty($l3)){
					foreach ($l3 as $c3=>$t3){
						$p4 = isset($v4['prices'][$t3]) ? $v4['prices'][$t3] : array() ;//:  $this->model()->get_hight_way_prices($v4['id'],$t3);
						//view($p4);
						if($c3==0 && !empty($p4)){
							$currency = $p4['currency'];
							//$dactive[$v4['id']] = $p4['is_active'];
						}
						$html .= '<td class="aright bold">'.(isset($p4['price1']) ? number_format($p4['price1'],$this->app()->showCurrency($currency,3)) : '').'</td>';
		
					}
				}
				$html .= '<td class="center w100p"><input type="hidden" name="hight_way['.$v4['id'].'][id]" value="'.$v4['id'].'"/>'.$this->app()->showCurrency($currency).'</td>';
				$html .= '<td class="center">'.getCheckBox(array(
						'name'=>'hight_way['.$v4['id'].'][is_required]',
						'value'=>$v4['is_required'],
						'type'=>'singer',
						'class'=>'switchBtn ajax-switch-btn',
						//'cvalue'=>true,
						 
				)).'</td>';
				$html .= '<td class="center">'.getCheckBox(array(
						'name'=>'hight_way['.$v4['id'].'][around]',
						'value'=>$v4['around'],
						'type'=>'singer',
						'class'=>'switchBtn ajax-switch-btn',
						//'cvalue'=>true,
						'checked'=>$v4['around'] == 2 ? true : false
						 
				)).'</td>';
				$html .= '<td class="center"><i title="Xóa" data-id="'.$v4['id'].'" data-name="delete_high_way" onclick="addToRemove(this);" data-target=".tr-distance-id-'.$v4['id'].'" class="pointer glyphicon glyphicon-trash btn-delete-item"></i></td>';
				$html .= '</tr>';
		
			}}
		$html .= '</tbody></table>';
		
		//$html .= '<p class="aright mgt15">';
		 
		//$html .= '<button data-required-save="true" data-type_id="'.TYPE_CODE_ROOM_TRAIN.'" data-existed="'.implode(',', $existed).'" data-supplier_id="'.$id.'" data-title="Thêm giường, ghế" type="button" onclick="open_ajax_modal(this);" data-class="" data-action="add-more-room-to-supplier" class="btn btn-warning btn-data-required-save"><i class="fa fa-plus"></i> Thêm giường, ghế</button></p><p>&nbsp;</p>';
		$html .= '<div class="aright btn-list-add-more-1"><button data-class="w90" data-title="Thêm cao tốc - tàu phà '.(isset($v['title']) ? 'chặng: <b class=red>'.$v['title'].'</b>' : '' ).' " data-action="add-more-hight-way" data-existed="'.implode(',', $existed).'" data-count="'.count($existed).'" type="button" data-type_id="'.TYPE_CODE_HIGHT_WAY.'" data-colspan="'.count($l3).'" data-id="'.$id.'" data-name="dprice" data-target=".ajax-result-more-hight-way" title="Thêm cao tốc" onclick="open_ajax_modal(this);" class="btn btn-warning btn-add-more"><i class=" glyphicon glyphicon-plus"></i> Thêm cao tốc</button></div>';
		$html .= '</div></div>';
		}
		echo json_encode([
				'html'=>$html,
				'callback'=>true, 
				'callback_function'=>'reload_app(\'switch-btn\');'
		]+$_POST);exit;
		break;
	case 'quick-add-more-hight-way':
		$r['event'] = 'hide-modal';
		$r['post'] = $_POST;
		$id = post('id',0);
		$items = post('items');
		if(!empty($items)){
			foreach ($items as $item){
				Yii::$app->db->createCommand()->insert('distance_to_places',[
						'distance_id'=>$id,
						'place_id'=>$item,
						'type_id'=>TYPE_CODE_HIGHT_WAY 
				])->execute();
			}
		}
		$r['callback']=true;
		$r['callback_function']='loadHtmlData(jQuery(\'.ajax-load-prices\'));';
		echo json_encode($r);exit;
		break;
	case 'add-more-hight-way':
		$r = array(); $r['html'] = '';
		$supplier_id = post('supplier_id',0);
		$m = load_model('distances'); //
		
			
		
		$r['html'] .= '<div class="form-group">
      
          <div class="col-sm-12">
	 <table class="table table-bordered vmiddle mgt15"><thead>
		<tr>
		<th class="center w50p">#</th>
		<th class="center">Tiêu đề </th>
		<th class="center w150p">Chọn</th>
		
		</tr>
		</thead><tbody>';
		foreach ($m->getAll(TYPE_CODE_HIGHT_WAY,['not_in'=>post('existed',[])]) as $k1=>$v1){
			$r['html'] .= '<tr>
		<td class="center">'.($k1+1).'</td>
		<td class=""><a>'.uh($v1['title']) . '</a></td>
		<td class="center "><input type="checkbox" name="items[]" value="'.$v1['id'].'" class=""></td>
		
		</tr>';
		}
		$r['html'] .= '</tbody></table>   </div>
         </div>';
		
		
		
		
		$r['html'] .= '<div class="modal-footer">';
		$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
		$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$r['html'] .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		$r['event'] = $_POST['action'];
		echo json_encode($r);exit;
		
		break;
	case 'loadSupplierRooms':
		$id = post('id',0);
		$existed = [];
		$html = '';
		$m = load_model('rooms_categorys');
		$html .= '<table class="table table-bordered vmiddle mgt15"><thead>
		<tr>
		<th class="center w50p">#</th>
		<th class="center">Tiêu đề </th>
		<th class="center w150p">Số lượng</th>
		<th class="center w50p"></th>
		</tr>
		</thead><tbody>';
		foreach ($m->getRoomBySupplier($id) as $k1=>$v1){
			$existed[] = $v1['id'];
			$html .= '<tr>
		<td class="center">'.($k1+1).'</td>
		<td class=""><a>'.uh($v1['title']) . ($v1['note'] != "" ? ' ('.uh($v1['note']).')' : '').'</a></td>
		<td class="center "><input name="items['.$v1['id'].'][quantity]" class="form-control center input-sm number-format ajax-number-format" value="'.$v1['quantity'].'"></td>
		<td class="center"><i class="pointer glyphicon glyphicon-trash btn-delete-item" data-id="'.$v1['id'].'" data-name="remove_items" onclick="addToRemove(this);"></i></td>
		</tr>';
		}
		$html .= '</tbody></table>';
		
		$html .= '<p class="aright mgt15">';
		//
		$html .= '<button data-required-save="true" data-type_id="'.TYPE_CODE_ROOM_TRAIN.'" data-load="new" data-existed="'.implode(',', $existed).'" data-supplier_id="'.$id.'" data-title="Thêm giường, ghế" type="button" onclick="open_ajax_modal(this);" data-class="" data-action="add-more-room-to-supplier" class="btn btn-warning btn-data-required-save"><i class="fa fa-plus"></i> Thêm giường, ghế</button></p><p>&nbsp;</p>';
		
		
		
		echo json_encode(['html'=>$html] + $_POST);exit;
		break;
		
	case 'loadSupplierRoutes':
		$id = post('id',0);
		$existed = [];
		$html = '';
		$m = load_model('distances');
		$html .= '<table class="table table-bordered vmiddle mgt15"><thead>
		<tr>
		<th class="center w50p">#</th>
		<th class="center">Tiêu đề </th>
		 
		<th class="center w50p"></th>
		</tr>
		</thead><tbody>';
		foreach ($m->getItemBySupplier($id) as $k1=>$v1){
			$existed[] = $v1['id'];
			$html .= '<tr>
		<td class="center">'.($k1+1).'</td>
		<td class=""><a>'.uh($v1['title']) .'</a></td>
		 
		<td class="center"><i class="pointer glyphicon glyphicon-trash btn-delete-item" data-id="'.$v1['id'].'" data-name="remove_routes" onclick="addToRemove(this);"></i></td>
		</tr>';
		}
		$html .= '</tbody></table>';
		
		$html .= '<p class="aright mgt15">';
		 
		$html .= '<button data-required-save="true" data-type_id="'.TYPE_ID_TRAIN.'" data-load="new" data-existed="'.implode(',', $existed).'" data-supplier_id="'.$id.'" data-title="Thêm tuyến" type="button" onclick="open_ajax_modal(this);" data-class="" data-action="add-more-route-to-supplier" class="btn btn-warning btn-data-required-save"><i class="fa fa-plus"></i> Thêm tuyến</button></p><p>&nbsp;</p>';
		
		
		
		echo json_encode(['html'=>$html] + $_POST);exit;
		break;	
	case 'loadSupplierPrices':
			$id = post('id',0);
			$existed = [];
			$html = '';
			$m = load_model('distances');
			$packages = $m->getItemBySupplier($id);
			$roomsModel = load_model('rooms_categorys');
			$model = load_model('services_provider');
			$rooms = $roomsModel->getRoomBySupplier($id);
			$seasons = \app\modules\admin\models\Seasons::get_incurred_category_for_price(TYPE_ID_TRAIN,[2],['supplier_id'=>$id]);
			if(empty($seasons)){
				$seasons = [
					['id'=>0,'title'=>''],	
				];
			}
			$existed_seasons = $existed_items = [];
			
			foreach ($packages as $package){
			
			$html .= '<div class="col-sm-12 mgt15"><div class="row"><p class="grid-sui-pheader bold aleft"><i style="font-weight: normal;">
        Bảng giá '; 
        if($package['id']>0){
        	//$html .= '<b class="italic green underline">' .$package['title'] .'</b> ';	
        }
        $html .=' - áp dụng cho tuyến <b class="italic underline">' .$package['title'] .'</b> 
 
 
 </i><i data-name="remove_routes" data-id="' .$package['id'] .'" onclick="addToRemove(this);" class="fa fa-trash pointer btn-remove btn-delete-item"></i></p></div></div>';
			
			
			
			$html .= '<table class="table table-bordered vmiddle mgt15"><thead>
		<tr>
	 
		<th rowspan="3" class="center">Ga đi - Ga đến</th>
		<th rowspan="3" class="center w100p">Cự ly (Km)</th>
		<th colspan="'.(count($rooms) * count($seasons)).'" class="center">Loại ghế</th>
		<th rowspan="3" class="center w80p" style="max-width:80px">Tiền tệ</th>
		<th rowspan="3" class="center w50p"></th>
		</tr>';
					
		 
		
		$html .= '<tr class="'.(count($seasons) == 1 ? 'hide' : '').'">';
		if(!empty($seasons)){
			foreach ($seasons as $season){
				if(!in_array($season['id'], $existed_seasons)){
					$existed_seasons[] = $season['id'];
				}
				$html .= '<th colspan="'.(count($rooms)).'" class="center">'.uh($season['title']).'</th>';
			}
		}
		$html .= '<tr>';
		foreach ($seasons as $season){
		if(!empty($rooms)){
			foreach ($rooms as $room){
				if(!in_array($room['id'], $existed_items)){
					$existed_items[] = $room['id'];
				}
				$html .= '<th class="center" title="'.uh($room['note']).'"><a>'.uh($room['title']).'</a></th>';
			}
		}
		}
		 
		$html .='</tr>';	
		
		$html .= '</thead><tbody>';
		$i = 0;
		foreach ($model->getTrainDistanceBySupplier(['parent_id'=>$id,'package_id'=>$package['id']]) as $k1=>$v1){
				 
				$p = $model->getTrainPrice($v1[0],$v1[1],$id,$package['id']); 
				 
				$html .= '<tr>
	 
		<td class=""><a class="truncate">'.uh($v1['distance']) .'</a></td>
		<td class="">
				<input type="hidden" value="'.$v1[0].'" name="prices['.$package['id'].']['.$i.'][station_from]" class=""/>
				<input type="hidden" value="'.$v1[1].'" name="prices['.$package['id'].']['.$i.'][station_to]" class=""/>
				<input type="text" onblur="addFormEditField(this)" data-old="'.($p['distance']).'" value="'.($p['distance']).'" name="prices['.$package['id'].']['.$i.'][distance]" class="form-control input-sm ajax-number-format center"/></td>';
				
				foreach ($seasons as $season){
					if(!empty($rooms)){
						foreach ($rooms as $room){							
							$html .= '<td class="center"><input onblur="addFormEditField(this)" type="text" data-old="'.(isset($p[$season['id']][$room['id']]['price1']) ? $p[$season['id']][$room['id']]['price1'] : '').'" value="'.(isset($p[$season['id']][$room['id']]['price1']) ? $p[$season['id']][$room['id']]['price1'] : '').'" data-decimal="'.Yii::$app->zii->showCurrency((isset($p['currency']) && $p['currency'] ? $p['currency'] : 2),3).'" name="prices['.$package['id'].']['.$i.'][list]['.$season['id'].']['.$room['id'].']" class="form-control input-sm ajax-number-format aright input-price-'.$k1.'"/></td>';
						}
					}
				}
				$html .= '<td class="center">';
				$html .= '<select data-decimal="'.Yii::$app->zii->showCurrency((isset($p['currency']) && $p['currency'] ? $p['currency'] : 0),3).'" data-target-input=".input-price-'.$k1.'" onchange="get_decimal_number(this);addFormEditField(this)" class="ajax-select2-no-search sl-cost-price-currency form-control ajax-select2 input-sm" data-search="hidden" name="prices['.$package['id'].']['.$i.'][currency]">';

				foreach(Yii::$app->zii->getUserCurrency()['list'] as $k2=>$v2){
					$html .= '<option value="'.$v2['id'].'" '.(isset($p['currency']) && $p['currency'] == $v2['id'] ? 'selected' : '').'>'.$v2['code'].'</option>';
				}
				 
				$html .= '</select>';
				$html .= '</td>';
		$html .= '<td class="center"><i class="pointer glyphicon glyphicon-trash btn-delete-item" data-id="'.$k1.'" data-name="remove_package_routes['.$package['id'].']" onclick="addToRemove(this);"></i></td>
		</tr>';
		$i++;
			}
			
			$html .= '</tbody></table>';
		
			$html .= '<p class="aright mgt15">';
			 
			$html .= '<button data-seasons="'.implode(',', $existed_seasons).'" data-items="'.implode(',', $existed_items).'" data-required-save="true" data-package_id="'.$package['id'].'" data-type_id="'.TYPE_ID_TRAIN.'" data-supplier_id="'.$id.'" data-title="Thêm chặng" type="button" onclick="open_ajax_modal(this);" data-class="w80" data-action="add-more-station-to-distance" class="btn btn-warning btn-data-required-save"><i class="fa fa-plus"></i> Thêm chặng</button></p><hr>';
		
			}
		$html .= '<hr>';
		$html .= '<p class="aright mgt15">';
		// 
		$html .= '<button data-required-save="true" data-type_id="'.TYPE_ID_TRAIN.'" data-load="new" data-existed="'.implode(',', $existed).'" data-supplier_id="'.$id.'" data-title="Thêm tuyến" type="button" onclick="open_ajax_modal(this);" data-class="" data-action="add-more-route-to-supplier" class="btn btn-primary btn-data-required-save"><i class="fa fa-plus"></i> Thêm tuyến</button></p><p>&nbsp;</p>';
		
			echo json_encode(['html'=>$html] + $_POST);exit;
			break;
	case 'quick-add-more-room-to-supplier':
		$supplier_id = post('supplier_id',0);
		$existed = post('existed',[]);
		if(!is_array($existed) && $existed != ""){
			$existed = explode(',', $existed);
		}
		
		foreach (post('items',[]) as $k1=>$v1){
			if(cprice($v1['quantity']>0)){
				Yii::$app->db->createCommand()->insert('rooms_to_hotel',['parent_id'=>$supplier_id,'room_id'=>$k1,'quantity'=>cprice($v1['quantity'])])->execute();
			}
		}
		
		$r['event'] = 'hide-modal';
		$r['callback'] = true;
		$r['callback_function'] = 'loadHtmlData(jQuery(\'.ajax-load-rooms\'));loadHtmlData(jQuery(\'.ajax-load-prices\'))';
		$r['supplier_id'] = $supplier_id;
		echo json_encode($r);exit;
		break;
		
	case 'quick-add-more-route-to-supplier':
		$supplier_id = post('supplier_id',0);
		$existed = post('existed',[]);
		if(!is_array($existed) && $existed != ""){
			$existed = explode(',', $existed);
		}
		
		foreach (post('items',[]) as $k1=>$v1){
			if(cbool($v1) == 1){
				Yii::$app->db->createCommand()->insert('distances_to_suppliers',['supplier_id'=>$supplier_id,'item_id'=>$k1])->execute();
			}
		}
		
		$r['event'] = 'hide-modal';
		$r['callback'] = true;
		$r['callback_function'] = 'loadHtmlData(jQuery(\'.ajax-load-routes\'));loadHtmlData(jQuery(\'.ajax-load-prices\'))';
		$r['supplier_id'] = $supplier_id;
		echo json_encode($r);exit;
		break;	
	case 'add-more-route-to-supplier':
		$r = array(); $r['html'] = '';		 
		$supplier_id = post('supplier_id',0);
		$m = load_model('distances'); // 
		
		 
		
		$r['html'] .= '<div class="form-group">
           
          <div class="col-sm-12">
	 <table class="table table-bordered vmiddle mgt15"><thead>
		<tr>
		<th class="center w50p">#</th>
		<th class="center">Tiêu đề </th>
		<th class="center w150p">Chọn</th>
	 
		</tr>
		</thead><tbody>';
		foreach ($m->getAll(TYPE_ID_TRAIN,['not_in'=>post('existed',[])]) as $k1=>$v1){ 
		$r['html'] .= '<tr>
		<td class="center">'.($k1+1).'</td>
		<td class=""><a>'.uh($v1['title']) . '</a></td>
		<td class="center "><input type="checkbox" name="items['.$v1['id'].']" class=""></td>
		 
		</tr>';
		}
		$r['html'] .= '</tbody></table>   </div>
         </div>';
		
	 
	 
		
		$r['html'] .= '<div class="modal-footer">';
		$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
		$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$r['html'] .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		$r['event'] = $_POST['action'];
		echo json_encode($r);exit;
		break;	
	case 'quick_search_station_for_add_supplier':
		$val = post('val');
		$stationModel = load_model('stations');
		//$stations = $stationModel->getAll(['type_id'=>TYPE_ID_TRAIN,'filter_text'=>$val]);
		$r = [];
		foreach ( $stationModel->getAll(['type_id'=>TYPE_ID_TRAIN,'filter_text'=>trim($val)]) as $k=>$v){
			$r[] = $v['id'];
		}
		echo json_encode($r); exit;
		break;
		
	case 'quick-search-room-train':
		//$val = post('val');
		$stationModel = load_model('rooms_categorys');
		//$stations = $stationModel->getAll(['type_id'=>TYPE_ID_TRAIN,'filter_text'=>$val]);
		$r = [];
		foreach ( $stationModel->getAllRooms(TYPE_CODE_ROOM_TRAIN,['filter_text'=>trim(post('q'))]) as $k=>$v){
			$r[] = $v['id'];
		}
		echo json_encode($r); exit;
		break;	
		
	case 'quick-add-more-station-to-distance':
		$items1 = post('items1');
		$supplier_id = post('supplier_id',0);
		$items2 = post('items2',[]);
		//
		if(!empty($items2)){
			foreach (array_merge($items2,[$items1]) as $item){
				if((new Query())->from('distances_to_stations')->where(['item_id'=>$supplier_id,'station_id'=>$item])->count(1) == 0){
					Yii::$app->db->createCommand()->insert('distances_to_stations',['item_id'=>$supplier_id,'station_id'=>$item])->execute();	
				}					
			}
			//
			$seasons = explode(',', post('seasons'));
			$items = explode(',',post('items'));
			$package_id = post('package_id');
			$type_id = post('type_id');
			//
			foreach ($seasons as $season){
				foreach ($items as $item){
					foreach ($items2 as $item2){
						if($items1 != $item2 && (new Query())->from('trains_to_prices')->where([
								'station_from'=>$items1,
								'station_to'=>$item2,
								'item_id'=>$item,
								'season_id'=>$season,
								'parent_id'=>$supplier_id,
								'package_id'=>$package_id,
								'type_id'=>$type_id
								
								
								
						])->count(1) == 0){
							Yii::$app->db->createCommand()->insert('trains_to_prices',[
								'station_from'=>$items1,
								'station_to'=>$item2,
								'item_id'=>$item,
								'season_id'=>$season,
								'parent_id'=>$supplier_id,
								'package_id'=>$package_id,
								'type_id'=>$type_id								
						])->execute();
						}
					}
				}
			}
		}
		//
		echo json_encode([
				'event'=>'hide-modal',
				'callback'=>true,
			//	'item2'=>$_POST,
				'callback_function' => 'loadHtmlData(jQuery(\'.ajax-load-prices\'))'
		]); exit;
		break;
	case 'add-more-station-to-distance':
		$r = array(); $r['html'] = '';		 
		$supplier_id = post('supplier_id',0);
		$m = load_model('distances'); // 
		
		$stationModel = load_model('stations'); 
		$stations = $stationModel->getAll(['type_id'=>TYPE_ID_TRAIN]);
		 	
		
		$r['html'] .= '<div class="form-group">
           
          <div class="col-sm-12">
	 <table class="table table-bordered vmiddle mgt15"><thead>
		
		</thead><tbody><tr>
		<th class="center w150p">Ga đi</th>
		<th class=""><select name="items1" class="chosen-select" data-role="load-station">';
		
		if(!empty($stations)){
		foreach ($stations as $ks=>$s){
		 
			$r['html'] .= '<option value="'.$s['id'].'">'.uh($s['title']).'</option>';
		}} 
		$r['html'] .= '</select></th>
		<th class="center w150p">Chọn</th>
	 
		</tr><tr class="vtop">
		<th rowspan="'.(count($stations)+1).'" class="center" style="vertical-align:top !important ">Ga đến</th>
		 
	 <th><input onkeyup="quick_search_station_for_add_supplier(this)" class="form-control input-sm" placeholder="Tìm kiếm nhanh"/></th><th></th>
		</tr>';
		
		if(!empty($stations)){
			foreach ($stations as $ks=>$s){
				$r['html'] .= '<tr class="quick-search-tr-item quick-search-tr-item-'.$s['id'].'">
	 
		<th class=""><a>'.uh($s['title']).'</a></th>
		<th class="center"><input type="checkbox" value="'.$s['id'].'" name="items2[]"/></th>
	 
		</tr>';
			}
		}
		$r['html'] .= '</tbody></table>   </div>
         </div>';
		
	 
	 
		
		$r['html'] .= '<div class="modal-footer">';
		$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
		$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$r['html'] .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		$r['event'] = $_POST['action'];
		echo json_encode($r);exit;
		break;	
	case 'add-more-room-to-supplier':
		$r = array(); $r['html'] = '';		 
		$supplier_id = post('supplier_id',0);
		$m = load_model('rooms_categorys'); // 
		
		 
		
		$r['html'] .= '<div class="form-group">
           
          <div class="col-sm-12">
	 <table class="table table-bordered vmiddle mgt15"><thead>
		<tr>
		<th class="center w50p">#</th>
		<th class="center">Tiêu đề </th>
		<th class="center w150p">Số lượng</th>
	 
		</tr>
		</thead><tbody>';
		$r['html'] .= '<tr >
		<td class="center"></td>
		<td class=""><input data-action="quick-search-room-train" onkeyup="quick_filter_text_value(this)" class="form-control input-sm" placeholder="Tìm kiếm nhanh"/></td>
		<td class="center "> </td>
		
		</tr>';
		foreach ($m->getAllRooms(TYPE_CODE_ROOM_TRAIN,['not_in'=>post('existed',[])]) as $k1=>$v1){ 
		$r['html'] .= '<tr class="quick-search-tr-item quick-search-tr-item-'.$v1['id'].'">
		<td class="center">'.($k1+1).'</td>
		<td class=""><a>'.uh($v1['title']) . ($v1['note'] != "" ? ' ('.uh($v1['note']).')' : '').'</a></td>
		<td class="center "><input name="items['.$v1['id'].'][quantity]" class="form-control center input-sm number-format ajax-number-format" value=""></td>
		 
		</tr>';
		}
		$r['html'] .= '</tbody></table>   </div>
         </div>';
		
	 
	 
		
		$r['html'] .= '<div class="modal-footer">';
		$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
		$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$r['html'] .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		$r['event'] = $_POST['action'];
		echo json_encode($r);exit;
		break;
	case 'quick-add-more-guides':
		$model = load_model('guides');
		 
		$f = post('f');$biz = post('biz',[]);
		$f['bizrule'] = cjson($biz);
		 
		$guide_id = post('guide_id',0);
		$supplier_id = post('supplier_id',0);
		$existed = post('existed',[]);
		if(!is_array($existed) && $existed != ""){
			$existed = explode(',', $existed);
		}
		//
		
		$remove_menu = post('remove_menu',[]);
		
		if($guide_id == 0){ // Thêm mới
			$f['sid'] = __SID__;
			$guide_id = Yii::$app->zii->insert($model->tableGuide(),$f);
	 
			Yii::$app->db->createCommand()->insert($model->tableToSupplier(),['guide_id'=>$guide_id,'supplier_id'=>$supplier_id])->execute();
			//exit;
			$insert_menu = true;
		}else {
			Yii::$app->zii->update($model->tableGuide(),$f,['id'=>$guide_id,'sid'=>__SID__]);
			$insert_menu = false;
		}
		
		
		// Cập nhật giá
		//Yii::$app->db->createCommand()->delete($menuModel->tableToPrice(),['item_id'=>$menu_id,'parent_id'=>$supplier_id])->execute();
		//Yii::$app->db->createCommand()->insert($menuModel->tableToPrice(),['item_id'=>$menu_id,'parent_id'=>$supplier_id,'price1'=>cprice($prices['price1']),'currency'=>$prices['currency']])->execute();
		//
		//if(!empty($remove_menu)){
		//	Yii::$app->db->createCommand()->delete($model->tableToSupplier(),['supplier_id'=>$supplier_id,'guide_id'=>$remove_menu])->execute();
		//}
		//if($insert_menu) Yii::$app->db->createCommand()->insert($menuModel->tableToSupplier(),['menu_id'=>$menu_id,'supplier_id'=>$supplier_id])->execute();
		//
		
		$r['event'] = $_POST['action'];
		$r['supplier_id'] = $supplier_id;
		echo json_encode($r);exit;
		break;
	case 'add-more-guides':
	
		$r = array(); $r['html'] = '';
		$menu_id = post('guide_id',0);
		$menuModel = load_model('guides');
		$supplier_id = post('supplier_id',0);
		
		
		$item = $menu_id >0 ? $menuModel->getGuide($menu_id) : [];
		//view($item,true);
		
		$r['html'] .= '<div class="form-group">
          <label class="col-sm-12 control-label aleft">Tiêu đề</label>
          <div class="col-sm-12">
	<input name="f[title]" class="form-control input-sm required" value="'.(!empty($item) ? uh($item['title']) : '').'"/>
		
            </div>
         </div>';
		
		$r['html'] .= '<div class="form-group">
          <label class="col-sm-12 control-label aleft">Mô tả ngắn</label>
          <div class="col-sm-12">
	<input name="biz[info]" class="form-control input-sm " value="'.(!empty($item) ? uh($item['info']) : '').'"/>
		
            </div>
         </div>';
		$r['html'] .= '<div class="form-group edit-form-left"><div class="col-sm-6"><div class="row">
  
          <div class="col-sm-12">
'.Ad_edit_show_select_field([],[
				'field'=>'language',
				'label'=>'Ngôn ngữ',
				'class'=>'select2 ',
				//'field_name'=>'category_id[]',
				//'multiple'=>true,
				'attrs'=>[
						'data-search'=>'hidden'
				],
				'data'=>\app\modules\admin\models\AdLanguage::getList(),
				'data-selected'=>[!empty($item) ? $item['language'] : DEFAULT_LANG],
				'option-value-field'=>'code',
				'option-title-field'=>'title',
		]).'
		
            </div></div></div>
		
		
		
		
				<div class="col-sm-6"><div class="row">
		
<div class="col-sm-12">
		 
</div></div></div>
		
		
         </div>';
 
				$r['html'] .= '<div class="modal-footer">';
				$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
				$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
				$r['html'] .= '</div>';
				$_POST['action'] = 'quick-' . $_POST['action'];
				foreach ($_POST as $k=>$v){
					$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
				}
				///		
				$r['event'] = $_POST['action'];
				echo json_encode($r);exit;
		break;
	case 'loadGuidePrices':
		$id = post('id',0);
		$menus = load_model('guides');
		$l = $menus->getGuides(['supplier_id'=>$id]);
		 
		//
		$packageModel = load_model('package_prices');	
		$servicesProvider = load_model('services_provider');
		$packages = $packageModel->getPackages($id);		
		$m = load_model('nationality_groups');
		$nationalitys = $m->get_supplier_group($id);
		if(empty($nationalitys)){
			$nationalitys = [
				['id'=>0,'title'=>'']	
			];
		}
	 	$html = '';
		//
		 
		$incurred_list = \app\modules\admin\models\Seasons::get_incurred_category_for_price(TYPE_ID_REST,[2],[
				'supplier_id'=>$id,
				'type_id'=>20,
				'price_type'=>0
		]);
		$ckc_incurred = true;
		if(empty($incurred_list)){
			$incurred_list = [[
				'id'=>0,'title'=>''	
			]];
			$ckc_incurred = false;
		}
		$incurred_prices_weekend_list = \app\modules\admin\models\Seasons::get_incurred_category_for_price(TYPE_ID_REST,[3,4],[
				'supplier_id'=>$id,
				'type_id'=>20,
				'price_type'=>0
		]);
		$room_groups = \app\modules\admin\models\Seasons::get_rooms_groups($id);
		 
		if(empty($incurred_prices_weekend_list)){
			$incurred_prices_weekend_list = [[
					'id'=>0,'title'=>''
			]];
			 
		}
		$existed_nationality = [];$existed = [];
		foreach ($packages as $package){
			if(!empty($nationalitys)){
				
				foreach ($nationalitys as $kb=>$vb){
					$existed_nationality[] = $vb['id'];
					$html .= '<div class="col-sm-12 mgt15"><div class="row pr"><p class="grid-sui-pheader bold aleft"><i style="font-weight: normal;">';
					
					$html .= 'Bảng giá ';
        if($package['id']>0){
        	$html .= '<b class="italic green underline">' .$package['title'] .'</b> ';	
        }
        $html .= $vb['id'] > 0 ? ' - áp dụng cho <b class="italic underline">' .$vb['title'] .'</b> ' : ''; 
					
					$html .= '</i>'.($vb['id'] > 0 ? '<i data-name="remove_nationality" data-id="'.$vb['id'].'" onclick="addToRemove(this);" class="fa fa-trash pointer btn-remove btn-delete-item"></i>' : "").'</p></div></div>';
$colspan = count($room_groups) * (count($incurred_prices_weekend_list)>0 ? count($incurred_prices_weekend_list) : 1);					
					$html .= '<div class="col-sm-12"><div class="row"><table class="table table-bordered vmiddle ">
<thead>
<tr><th rowspan="4" class="center w50p"></th>
<th rowspan="4" class="center">Tiêu đề</th>
<th rowspan="4" class="center">Ngôn ngữ</th>
<th colspan="'.($colspan*count($incurred_list)).'" class="center underline">Đơn giá</th>
<th rowspan="4" class="w100p center">Tiền tệ</th><th rowspan="4" class="w100p"></th>		
</tr>							
							<tr class="'.($colspan*count($incurred_list) > 1 && $ckc_incurred ? '' : 'hide').'">';
if(!empty($incurred_list)){
	foreach ($incurred_list as $in){
	$html .= '<th colspan="'.($colspan).'" class="center w200p">'.$in['title'].'</th>';
	}
}
 
$html .= '
</tr><tr class="hide">';
if(!empty($incurred_list)){
	foreach ($incurred_list as $in){
if(!empty($room_groups)){
	foreach ($room_groups as $room){
		$html .= '<th colspan="'.(count($incurred_prices_weekend_list)).'" class="center w200p"><a data-class="w70" data-parent_id="'.(isset($v['id']) ? $v['id'] : 0).'" data-id="'.$room['id'].'" data-action="add-more-room-group" data-title="Thiết lập nhóm phòng" onclick="open_ajax_modal(this);" class="pointer hover_underline">'.$room['title'].($room['note'] != "" ? '<p><i class="f11p font-normal">('.$room['note'].')</i></p>' : '').'</a></th>';
	}
}
	}}	
	$html .= '
</tr><tr class="'.(count($incurred_prices_weekend_list) > 1 ? '' : 'hide').'">';
	if(!empty($incurred_list)){
		foreach ($incurred_list as $in){
			if(!empty($room_groups)){
				foreach ($room_groups as $room){
					if(!empty($incurred_prices_weekend_list)){
						foreach ($incurred_prices_weekend_list as $weekend){
							$html .= '<th class="center w200p"><i>'.$weekend['title'].'</i></th>';
						}
					}
				}
			}
		}}
		
$html .='</tr></thead>
<tbody >';
				 
					if(!empty($l)){
						foreach ($l as $k1=>$v1){
							 
							$existed[] = $v1['id'];
							$p = $menus->get_price($v1['id'],$id,$vb['id'],$package['id']); 
							$html .= '<tr>
<td class="center">'.($k1+1).'</td>
<td><a class="pointer" data-supplier_id="'.$id.'" data-guide_id="'.$v1['id'].'" data-title="Chỉnh sửa hướng dẫn viên" onclick="open_ajax_modal(this);" data-class="w90" data-action="add-more-guides">'.uh($v1['title']).'</a></td>
<td class="center">'.(Yii::$app->zii->showLang($v1['language'])).'</td>';
							if(!empty($incurred_list)){
		foreach ($incurred_list as $in){
			if(!empty($room_groups)){
				foreach ($room_groups as $room){
					if(!empty($incurred_prices_weekend_list)){ 
						foreach ($incurred_prices_weekend_list as $w){
							$html .= '<td class="center"><input onblur="addFormEditField(this)" type="text" name="prices['.$package['id'].']['.$vb['id'].']['.$v1['id'].'][list_child]['.$in['id'].']['.$room['id'].']['.$w['id'].'][price1]" value="'.(isset($p[$in['id']][$room['id']][$v1['id']][$w['id']]['price1']) ? $p[$in['id']][$room['id']][$v1['id']][$w['id']]['price1'] : '').'" data-old="'.(isset($p[$in['id']][$room['id']][$v1['id']][$w['id']]['price1']) ? $p[$in['id']][$room['id']][$v1['id']][$w['id']]['price1'] : '').'" class="form-control input-sm aright number-format w100 inline-block input-currency-price-'.$v1['id'].'" data-decimal="'.Yii::$app->zii->showCurrency((isset($p['currency']) && $p['currency'] ? $p['currency'] : 1),3).'"/></td>';
						}
					}
				}
			}
		}}
$html .= '<td class="center">';
$html .= '<select data-target-input=".input-currency-price-'.$v1['id'].'" data-decimal="'.Yii::$app->zii->showCurrency((isset($p['currency']) && $p['currency'] ? $p['currency'] : 1),3).'" onchange="get_decimal_number(this);addFormEditField(this)" class="ajax-select2-no-search sl-cost-price-currency form-control ajax-select2 input-sm" data-search="hidden" name="prices['.$package['id'].']['.$vb['id'].']['.$v1['id'].'][currency]">';
//if(isset($v['currency']['list']) && !empty($v['currency']['list'])){
foreach(Yii::$app->zii->getUserCurrency()['list'] as $k2=>$v2){
	$html .= '<option value="'.$v2['id'].'" '.(isset($p['currency']) && $p['currency'] == $v2['id'] ? 'selected' : '').'>'.$v2['code'].'</option>';
}
//}

$html .= '</select>';
$html .= '</td>';		
$html .= '<td class="center">
<i class="pointer glyphicon glyphicon-trash btn-delete-item" data-id="'.$v1['id'].'" data-name="remove_menu" onclick="addToRemove(this);"></i>
</td>
</tr> ';

						}
					}
					
					$html .= '</tbody></table></div></div>';
				}
			}
		}
		 
		//
		

		$html .= '<p class="aright mgt15">';
		// 
		$html .= '<button data-required-save="true" data-load="new" data-existed="'.implode(',', $existed).'" data-supplier_id="'.$id.'" data-title="Thêm hướng dẫn" type="button" onclick="open_ajax_modal(this);" data-class="w90" data-action="add-more-guides" class="btn btn-warning btn-data-required-save"><i class="fa fa-plus"></i> Thêm hướng dẫn</button></p><p>&nbsp;</p>';
	
		
		
		echo $html; exit;
		break;
	case 'loadMenus':
		echo json_encode(['html'=>getSupplierPricesList(post('supplier_id',0))]);exit;
		$id =$supplier_id= post('id',0);
		$menus = load_model('menus');
		$l = $menus->getMenus(['supplier_id'=>$id]);
		$quotations = \app\modules\admin\models\Customers::getSupplierQuotations($supplier_id,[
				'order_by'=>['a.to_date'=>SORT_DESC,'a.title'=>SORT_ASC],
				'is_active'=>1
		]);
		//
		$packageModel = load_model('package_prices');	
		$servicesProvider = load_model('services_provider');
		 	
		$m = load_model('nationality_groups');
		 
	 	$html = '';

	 	$html .= getPriceHeaderButton($supplier_id,
	 			[
	 			'controller_code'=>TYPE_ID_REST,
	 			'type_id'=>TYPE_ID_REST,
	 			'quotation'=>true,'package'=>true,
	 			'nationality'=>true,'group'=>true,
	 			
	 			'menu'=>true
	 	]); 
		$ckc_incurred =  true;
		 
		$existed_nationality = [];$existed = [];
		
		
		// Lay package
		$packages = \app\modules\admin\models\PackagePrices::getPackages($supplier_id);
		// Lay nhom quoc tich
		$nationalitys = \app\modules\admin\models\NationalityGroups::get_supplier_group($supplier_id);
		// Lay mua co tinh gia truc tiep
		$incurred_list = $incurred_prices_list = \app\modules\admin\models\Customers::getCustomerSeasons($supplier_id,[
				'price_type'=>[0],'type_id'=>2
		]);
		// Lay danh sach cuoi tuan ngay thuong tinh gia truc tiep
		$incurred_prices_weekend_list = \app\modules\admin\models\Customers::getCustomerWeekend([
				'price_type'=>[0],
				'supplier_id'=>$supplier_id,
				'return_type'=>'for_price',
		]);
		
		// Lay danh sach buổi tinh gia truc tiep
		$incurred_prices_weekend_list_time = \app\modules\admin\models\Customers::getCustomerWeekendTime([
				'price_type'=>[0],
				'supplier_id'=>$supplier_id,
				'return_type'=>'for_price',
		]);
		$l3 = \app\modules\admin\models\Hotels::getListRooms($supplier_id);
		// Lay nhom phong
		$room_groups = \app\modules\admin\models\Seasons::get_rooms_groups($supplier_id);
if(!empty($quotations)){
	foreach ($quotations as $q=>$quotation){
		$html .= '<div class="col-sm-12 mgt15 quotation-block" style=""><div class="row pr"><p class="grid-sui-pheader bold aleft">
				'.$quotation['title'].'<i> - Áp dụng từ <span class="  underline">'.date('d/m/Y H:i:s',strtotime($quotation['from_date'])).' - '.date('d/m/Y H:i:s',strtotime($quotation['to_date'])).'</span></i></p></div>';
			
		
		$html .= '<div class="row-10">';
		
		foreach ($packages as $package){
			if(!empty($nationalitys)){
				
				foreach ($nationalitys as $kb=>$vb){
					$existed_nationality[] = $vb['id'];
					$html .= '<div class="col-sm-12 mgt15"><div class="row pr"><p class="grid-sui-pheader bold aleft"><i style="font-weight: normal;">';
					
					
        if($package['id']>0){
        	$html .= 'Gói dịch vụ ';
        	$html .= '<b class="italic green underline">' .$package['title'] .'</b> ';	
        }else{
        	$html .= 'Bảng giá ';
        }
        $html .= ' - áp dụng cho <b class="italic underline">' .$vb['title'] .'</b> '; 
					
					$html .= '</i><i data-name="remove_nationality" data-id="'.$vb['id'].'" onclick="addToRemove(this);" class="fa fa-trash pointer hide btn-remove btn-delete-item"></i></p></div></div>';
$colspan = count($room_groups) * (count($incurred_prices_weekend_list)>0 ? count($incurred_prices_weekend_list) : 1);					
					$html .= '<div class="col-sm-12"><div class="row"><table class="table table-bordered vmiddle ">
<thead>
<tr><th rowspan="5" class="center w50p"></th>
<th rowspan="5" class="center" style="min-width:200px">Tiêu đề</th>
<th colspan="'.($colspan*count($incurred_list) *count($incurred_prices_weekend_list_time) ).'" class="center underline ">Bảng giá</th>
<th rowspan="5" class="w100p center" title="Chuyển đổi nhanh loại tiền tệ">Tiền tệ <hr><select
		data-target=".select-currency-'.$quotation['id'].'-'.$package['id'].'-'.$vb['id'].'"
		data-decimal="0" onchange="get_decimal_number(this);change_multi_currency_price(this);" class="sl-cost-price-currency form-control select2 input-sm" data-search="hidden" >';
//if(isset($v['currency']['list']) && !empty($v['currency']['list'])){
foreach(Yii::$app->zii->getUserCurrency()['list'] as $k2=>$v2){
	$html .= '<option value="'.$v2['id'].'">'.$v2['code'].'</option>';
}
//}

$html .= '</select></th><th rowspan="5" class="w100p">Mặc định</th>		<th rowspan="5" class="w100p"></th>	  
</tr>							
							<tr class="'.(count($incurred_list) > 1 && $ckc_incurred ? '' : 'hide').'">';
if(!empty($incurred_list)){
	foreach ($incurred_list as $in){
	$html .= '<th colspan="'.($colspan * count($incurred_prices_weekend_list_time)).'" class="center w200p">'.$in['title'].'</th>';
	}
}

$html .= '
</tr><tr class="'.(count($room_groups) > 1 ? ' ' : 'hide').'">';
if(!empty($incurred_list)){
	foreach ($incurred_list as $in){
if(!empty($room_groups)){
	foreach ($room_groups as $room){
		$html .= '<th colspan="'.(count($incurred_prices_weekend_list) * count($incurred_prices_weekend_list_time)).'" class="center w200p"><a data-class="w70" data-supplier_id="'.$supplier_id.'" data-parent_id="'.(isset($v['id']) ? $v['id'] : 0).'" data-id="'.$room['id'].'" data-action="add-more-room-group" data-title="Thiết lập nhóm phòng" onclick="open_ajax_modal(this);" class="pointer hover_underline">'.$room['title'].($room['note'] != "" ? '<p><i class="f11p font-normal">('.$room['note'].')</i></p>' : '').'</a></th>';
	}
}
	}}	
	$html .= '</tr>';
$html .= '<tr class="'.(count($incurred_prices_weekend_list) > 1 ? '' : 'hide').'">';
	if(!empty($incurred_list)){
		foreach ($incurred_list as $in){
			if(!empty($room_groups)){
				foreach ($room_groups as $room){
					if(!empty($incurred_prices_weekend_list)){
						foreach ($incurred_prices_weekend_list as $weekend){
							$html .= '<th colspan="'.(count($incurred_prices_weekend_list_time)).'" class="center w200p"><i>'.$weekend['title'].'</i></th>';
						}
					}
				}
			}
		}}
		
$html .='</tr>';
$html .= '<tr class="'.(count($incurred_prices_weekend_list) > 1 ? '' : 'hide').'">';
if(!empty($incurred_list)){
	foreach ($incurred_list as $in){
		if(!empty($room_groups)){
			foreach ($room_groups as $room){
				if(!empty($incurred_prices_weekend_list)){
					foreach ($incurred_prices_weekend_list as $weekend){
						if(!empty($incurred_prices_weekend_list_time)){
							foreach ($incurred_prices_weekend_list_time as $weekend_time){
								$html .= '<th class="center w150p"><i>'.$weekend_time['title'].'</i></th>';
							}
						}
					}
				}
			}
		}
	}}

	$html .='</tr>';

$html .= '</thead><tbody >';
					
					if(!empty($l)){
						foreach ($l as $k1=>$v1){
							$existed[] = $v1['id'];
							//$p = $menus->get_price($v1['id'],$id,$vb['id'],$package['id']);
							$currency = 1;
							$tr = [
									$supplier_id,
									$quotation['id'],
									$package['id'],
									$vb['id'],
									$v1['id']
							];
							$html .= '<tr class="tr-price-'.implode('-', $tr).'">
<td class="center">'.($k1+1).'</td>
<td><a class="pointer" data-supplier_id="'.$id.'" data-menu_id="'.$v1['id'].'" data-title="Chỉnh sửa thực đơn" onclick="open_ajax_modal(this);" data-class="w90" data-action="add-more-menu-supplier">'.uh($v1['title']).'</a></td>';
							if(!empty($incurred_list)){
		foreach ($incurred_list as $in){
			if(!empty($room_groups)){
				foreach ($room_groups as $room){
					if(!empty($incurred_prices_weekend_list)){ 
						foreach ($incurred_prices_weekend_list as $w){
							if(!empty($incurred_prices_weekend_list_time)){
								foreach ($incurred_prices_weekend_list_time as $weekend_time){
									$price = \app\modules\admin\models\Menus::getMenuPrice([
											'item_id'=>$v1['id'],
											'season_id'=>$in['id'],
											'weekend_id'=>$w['id'],
											'group_id'=>$room['id'],
											'supplier_id'=>$supplier_id,
											'package_id'=>$package['id'],
											'quotation_id'=>$quotation['id'],
											'time_id'=>$weekend_time['id'],
											'nationality_id'=>$vb['id']
									]);
									if(!empty($price)) $currency = $price['currency'];
									$html .= '<td class="center"><input 
											data-supplier_id="'.$supplier_id.'"
											data-quotation_id="'.$quotation['id'].'"
											data-package_id="'.$package['id'].'"
											data-nationality_id="'.$vb['id'].'"		
											data-item_id="'.$v1['id'].'"	
											data-season_id="'.$in['id'].'"
											data-group_id="'.$room['id'].'"	
											data-weekend_id="'.$w['id'].'"
											data-time_id="'.$weekend_time['id'].'"		
											onblur="quick_change_menu_price(this);" 													
											type="text" name="prices['.$package['id'].']['.$vb['id'].']['.$v1['id'].'][list_child]['.$in['id'].']['.$room['id'].']['.$w['id'].'][price1]" 
											value="'.(isset($price['price1']) ? $price['price1'] : '').'" 
											data-old="'.(isset($price['price1']) ? $price['price1'] : '').'" 
											class="form-control input-sm aright number-format w100 inline-block input-currency-price-'.$v1['id'].'" data-decimal="'.Yii::$app->zii->showCurrency($currency,3).'"/></td>';
								}
							}
						}
					}
				}
			}
		}}
$html .= '<td class="center">';
$html .= '<select 
					data-supplier_id="'.$supplier_id.'"
					data-quotation_id="'.$quotation['id'].'"
					data-package_id="'.$package['id'].'"
					data-nationality_id="'.$vb['id'].'" 
					data-item_id="'.$v1['id'].'"							
					data-decimal="'.Yii::$app->zii->showCurrency($currency,3).'" data-target-input=".input-currency-price-'.$v1['id'].'" onchange="get_decimal_number(this);quick_change_menu_price_currency(this);" class="ajax-select2-no-search sl-cost-price-currency form-control ajax-select2 input-sm select-currency-'.$quotation['id'].'-'.$package['id'].'-'.$vb['id'].'" data-search="hidden" name="prices['.$package['id'].']['.$vb['id'].']['.$v1['id'].'][currency]">';
//if(isset($v['currency']['list']) && !empty($v['currency']['list'])){
foreach(Yii::$app->zii->getUserCurrency()['list'] as $k2=>$v2){
	$html .= '<option value="'.$v2['id'].'" '.($currency == $v2['id'] ? 'selected' : '').'>'.$v2['code'].'</option>';
}
//}

$html .= '</select>';
$html .= '</td>';	

$html .= '<td class="center"><input type="checkbox" name="set_default['.$quotation['id'].']['.$package['id'].']" value="'.$v1['id'].'"/></td>';

$html .= '<td class="center">
<i data-supplier_id="'.$supplier_id.'"
					data-quotation_id="'.$quotation['id'].'"
					data-package_id="'.$package['id'].'"
					data-nationality_id="'.$vb['id'].'" 
					data-item_id="'.$v1['id'].'"
					data-confirm-text="<span class=red>Lưu ý: Thực đơn <b class=underline>'.$v1['title'].'</b> sẽ bị xóa khỏi toàn bộ các báo giá.</span>"
					class="pointer glyphicon glyphicon-trash btn-delete-item" data-id="'.$v1['id'].'" data-name="remove_menu" data-confirm-action="quick_change_menu_price_remove" data-action="open-confirm-dialog" data-class="modal-sm" data-title="Xác nhận xóa." onclick="open_ajax_modal(this);"></i>
</td>
</tr> ';
						}
					}
					
					$html .= '</tbody></table></div></div>';
				}
			}
		}
		 
		//
		

		$html .= '</div></div>';
		}
		
		} else{
			$html .= '<div class="col-sm-12"><p class="help-block red ">Bạn cần tạo báo giá trước khi nhập giá.</p></div>';
		}
		
		echo $html; exit;
		break;
	case 'quick-add-more-menu-supplier':
		$menuModel = load_model('menus');
		$foodModel = load_model('foods');
		$f = post('f');$biz = post('biz',[]);
		$f['bizrule'] = cjson($biz);
		$prices = post('prices');
		$menus = post('menus',[]);
		$menu_id = post('menu_id',0);
		$supplier_id = post('supplier_id',0);
		$existed = post('existed',[]);
		if(!is_array($existed) && $existed != ""){
			$existed = explode(',', $existed);
		}
		//
		$remove_menu = post('remove_menu',[]);
		
		if($menu_id == 0){ // Thêm mới
			$f['sid'] = __SID__;
			$f['supplier_id'] = $supplier_id;
			if((new Query())->from(['a'=>'menus'])
					//->innerJoin(['b'=>'menus_to_suppliers'],'a.id=b.menu_id')
					->where([
					'a.title'=>$f['title'],'a.supplier_id'=>$supplier_id
			])->count(1) == 0){
				$menu_id = Yii::$app->zii->insert($menuModel->tableName(),$f);
			}else{
				$menu_id = (new Query())->select('a.id')->from(['a'=>'menus'])->where(['a.title'=>$f['title'],'a.supplier_id'=>$supplier_id])->scalar();
			}
			
			
			
			$insert_menu = true;
		}else {
			Yii::$app->zii->update($menuModel->tableName(),$f,['id'=>$menu_id,'sid'=>__SID__]);
			$insert_menu = false;
		}
	 
		// Cập nhật giá
		//Yii::$app->db->createCommand()->delete($menuModel->tableToPrice(),['item_id'=>$menu_id,'parent_id'=>$supplier_id])->execute();
		//Yii::$app->db->createCommand()->insert($menuModel->tableToPrice(),['item_id'=>$menu_id,'parent_id'=>$supplier_id,'price1'=>cprice($prices['price1']),'currency'=>$prices['currency']])->execute();
		// 
		if(!empty($remove_menu)){
			Yii::$app->db->createCommand()->delete($menuModel->tableToSupplier(),['supplier_id'=>$supplier_id,'menu_id'=>$remove_menu])->execute();
		} 
		if($insert_menu) Yii::$app->db->createCommand()->insert($menuModel->tableToSupplier(),['menu_id'=>$menu_id,'supplier_id'=>$supplier_id])->execute();
		//
		
		Yii::$app->db->createCommand()->delete($foodModel->tableToMenu(),['menu_id'=>$menu_id])->execute();
		
		//view($menus,true);
		if(!empty($menus)){
			foreach ($menus as $menu){ 
				Yii::$app->db->createCommand()->insert($foodModel->tableToMenu(),['menu_id'=>$menu_id,'food_id'=>$menu])->execute();
			}
		}
		//
		$category_id = post('category_id',[]);
		Yii::$app->db->createCommand()->delete(\app\modules\admin\models\Menus::tableToCategory(),['and',
				['not in', 'category_id',$category_id],
				['item_id'=>$menu_id] 
		])->execute();
		if(!empty($category_id)){
			foreach ($category_id as $c){
				if((new Query())->from(\app\modules\admin\models\Menus::tableToCategory())->where(['category_id'=>$c])->count(1) == 0){
					Yii::$app->db->createCommand()->insert(\app\modules\admin\models\Menus::tableToCategory(),[
							'category_id'=>$c,
							'item_id'=>$menu_id,
					])->execute();
				}
			}
		}
		//
		$r['event'] = $_POST['action'];
		$r['supplier_id'] = $supplier_id;
		echo json_encode($r);exit;
		break;
	case 'changeMenusType':
		$type_id = post('type_id',0);
		$item_id = post('item_id',0);
		$html = '';
		$categorys = \app\modules\admin\models\FoodsCategorys::getAll(['type_id'=>$type_id]);
		$existed = \app\modules\admin\models\Menus::getItemCategorys($item_id);
		if(!empty($categorys)){
			foreach ($categorys as $k=>$v){
				$html .= '<option '.(in_array($v['id'], $existed) ? 'selected' : '').' value="'.$v['id'].'">'.uh($v['title']).'</option>';
			}
		}
		echo json_encode([
				'html'=>$html,
				
		]);exit;
		break;
	case 'add-more-menu-supplier':
		$r = array(); $r['html'] = '';
		$menu_id = post('menu_id',0);
		$menuModel = load_model('menus');
		$supplier_id = post('supplier_id',0);
		
		
		$item = $menu_id >0 ? $menuModel->getMenus(['supplier_id'=>$supplier_id,'menu_id'=>$menu_id])[0] : [];
		//view($item,true);
	 
		$r['html'] .= '<div class="form-group">
          <label class="col-sm-12 control-label aleft">Tiêu đề</label>
          <div class="col-sm-12">
	<input name="f[title]" onkeypress="if (event.keyCode==13){return nextFocus(this);}" class="form-control input-sm required" value="'.(!empty($item) ? uh($item['title']) : '').'"/> 
        
            </div>
         </div>';
		
		$r['html'] .= '<div class="form-group">
          <label class="col-sm-12 control-label aleft">Mô tả ngắn</label>
          <div class="col-sm-12">
	<input name="biz[info]" class="form-control input-sm " onkeypress="if (event.keyCode==13){return nextFocus(this);}" value="'.(!empty($item) ? uh($item['info']) : '').'"/>
		
            </div>
         </div>';
		$r['html'] .= '<div class="form-group edit-form-left"><div class="col-sm-8"><div class="row">
   
          <div class="col-sm-3">
'.Ad_edit_show_select_field([],[
			'field'=>'type_id',
			'label'=>'Loại thực đơn',
			'class'=>'select2 ',
			//'field_name'=>'category_id[]',
			//'multiple'=>true,
			'attrs'=>[
					'data-search'=>'hidden',
					'data-menu_id'=>!empty($item) ? $item['id'] : 0,
					'data-item_id'=>!empty($item) ? $item['id'] : 0,
					'data-target'=>'.group-select-menus-category',
					'data-target2'=>'.group-menus-category',
					'onchange'=>'changeMenusType(this)',
			],
			'data'=>[
					['id'=>1,'title'=>'Set menu'],
					['id'=>2,'title'=>'Buffet'],
			],
			'data-selected'=>[!empty($item) ? $item['type_id'] : 1],
			'option-value-field'=>'id',
			'option-title-field'=>'title',
	]).'
	
            </div>
					<div class="col-sm-9">
'.Ad_edit_show_select_field_group([],[
			//'field'=>'type_id',
			'label'=>'Danh mục món ăn',
			'class'=>'chosen-select group-menus-category group-select-menus-category',
			'default_value'=>0,
			'field_name'=>'category_id[]',
			'multiple'=>true,
			'attrs'=>[
					'data-search'=>'hidden',
					'data-placeholder'=>'Chọn danh mục món ăn',
					'data-type_id'=>!empty($item) ? $item['type_id'] : 0,
					'data-menu_id'=>!empty($item) ? $item['id'] : 0,
					'data-item_id'=>!empty($item) ? $item['id'] : 0,
					'onchange'=>'addSelectedMenusCategory(this)',
					'data-target'=>'.group-button-menus-category'
					
			],
			'data'=>\app\modules\admin\models\FoodsCategorys::getAll(['type_id'=>!empty($item) ? $item['type_id'] :1]),
			'data-selected'=>\app\modules\admin\models\Menus::getItemCategorys(!empty($item) ? $item['id'] : 0),
			'option-value-field'=>'id',
			'option-title-field'=>'title',
		'groups'=>[
				'attrs'=>[
						'onclick'=>'',
						'title'=>'Thêm danh mục món ăn',
						'data-toggle'=>'tooltip',
						'data-placement'=>'top',
						'data-type_id'=>!empty($item) ? $item['type_id'] : 1,
						'data-menu_id'=>!empty($item) ? $item['id'] : 0,
						'data-item_id'=>!empty($item) ? $item['id'] : 0,
						'onclick'=>'open_ajax_modal(this)',
						'data-action'=>'add-more-menus-categorys',
						'data-modal-target'=>'.mymodal1',
						'data-title'=>'Thêm danh mục món ăn',
						'data-existed'=>implode(',', \app\modules\admin\models\Menus::getItemCategorys(!empty($item) ? $item['id'] : 0)),
				],
				'class'=>'btn-success group-menus-category group-button-menus-category',
				'label'=>'<i class="fa fa-plus"></i>',
		],
	]).'
	
            </div>
					</div></div>
			 
	
	
	
				<div class="col-sm-4"><div class="row">
	
<div class="col-sm-12">
		'. Ad_edit_show_select_field([],[
					'field'=>'time',
					'label'=>'Thời gian',
					'class'=>'ajax-select2 ',
					'default_value'=>0,
					//'field_name'=>'category_id[]',
					//'multiple'=>true,
					'attrs'=>[
							'data-search'=>'hidden'
					],
					'data'=>[
							['id'=>1,'title'=>'Sáng'],
							['id'=>2,'title'=>'Trưa'],
							['id'=>3,'title'=>'Tối'],
					],
					'data-selected'=>[!empty($item) ? $item['time'] : 0],
					'option-value-field'=>'id',
					'option-title-field'=>'title',
			]).'
</div></div></div>
	
			 
         </div>';
	
			$r['html'] .= '<div class="form-group">
          <label class="col-sm-12 control-label aleft">Danh sách món ăn</label>
          <div class="col-sm-12">
	<table class="table table-bordered vmiddle">
<thead><th class="center w50p">#</th><th class="center">Tên món ăn</th><th class="center w50p"></th></thead>
<tbody class="ajax-result-add-more-foods">';
			$menu_exitsted = [];$k = 0;
if(isset($item['foods']) && !empty($item['foods'])){
	foreach ($item['foods'] as $k=>$v){
		$menu_exitsted[] = $v['id'];
		$r['html'] .= '<tr><td class="center">'.($k+1).'
							<input type="hidden" name="menus[]" value="'.$v['id'].'"/>
							</td>
		<td class=""><a data-modal-target=".mymodal1" class="pointer after-event-'.$v['id'].'" data-id="'.$v['id'].'" data-title="Sửa món ăn" onclick="open_ajax_modal(this);" data-action="quick-edit-food">'.uh($v['title']).'</a></td>
		<td class="center"><i onclick="removeTrItem(this)" class="fa fa-trash f12px pointer"></i></td>
	
		</tr>';
	}
}
		$r['html'] .= '</tbody>
		</table>
    <p class="aright "><button data-class="w80" data-count="'.(isset($item['foods']) ? count($item['foods']) : 0).'" data-existed="'.(implode(',', $menu_exitsted)).'" data-title="Thêm món ăn" data-modal-target=".mymodal1" data-action="add-foods-to-menu" onclick="open_ajax_modal(this);" type="button" class="btn btn-warning btn-add-foods-to-menu"><i class="fa fa-plus"></i> Thêm món ăn</button></p>
            </div>
         </div>';
	
	
			$r['html'] .= '<div class="modal-footer">';
			$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
			$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
			$r['html'] .= '</div>';
			$_POST['action'] = 'quick-' . $_POST['action'];
			foreach ($_POST as $k=>$v){
				$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
			}
			///
				
			$r['event'] = $_POST['action'];
			$r['callback'] = true;  $r['callback_function'] = 'reloadTooltip();';
			echo json_encode($r);exit;
			break;
	case 'quick-add-more-menus-categorys':
		$item_id = post('item_id',0);
		$type_id = post('type_id',1);
		$f = post('f',[]);
		$biz = post('biz',[]);
		$f['sid']= __SID__; $html = '';
		$f['type_id']= $type_id;
		$f['bizrule'] = json_encode($biz);
		$selected = explode(',',  post('existed',[]));
		if((new Query())->from(\app\modules\admin\models\FoodsCategorys::tableName())->where(['title'=>$f['title'],'type_id'=>$type_id,'sid'=>__SID__])->count(1) == 0){
			$id = Yii::$app->zii->insert(\app\modules\admin\models\FoodsCategorys::tableName(),$f);
			$item = \app\modules\admin\models\FoodsCategorys::getItem($id);
			$s = true;
			$html .= '<option value="'.$id.'">'.uh($f['title']).'</option>';
		}else{
			$s = false;
			$item = (new Query())->from(\app\modules\admin\models\FoodsCategorys::tableName())->where(['title'=>$f['title'],'type_id'=>$type_id,'sid'=>__SID__])->one();
			$id = $item['id'];
		}
		$selected[] = $id;
		echo json_encode([
			'event'=>'hide-modal',
			'modal_target'=>'.mymodal1',	
			'item'=>!empty($item) ? $item : false,
			'callback'=>true,
			'callback_function'=>'jQuery(\'.group-select-menus-category\').append(\''.$html.'\').val(['.implode(',', $selected).']).trigger(\'chosen:updated\')'
		]);exit;
		break;
	case 'add-more-menus-categorys':
		$r = array(); $r['html'] = '';
		$menu_id = post('menu_id',0);
		$menuModel = load_model('menus');
		$supplier_id = post('supplier_id',0);
		
		
		 
	 
		$r['html'] .= '<div class="form-group">
          <label class="col-sm-12 control-label aleft">Tiêu đề</label>
          <div class="col-sm-12">
	<input name="f[title]" onkeypress="if (event.keyCode==13){return nextFocus(this);}" class="form-control input-sm required" value=""/> 
        
            </div>
         </div>';
		
		$r['html'] .= '<div class="form-group">
          <label class="col-sm-12 control-label aleft">Mô tả ngắn</label>
          <div class="col-sm-12">
	<input name="biz[info]" class="form-control input-sm " onkeypress="if (event.keyCode==13){return nextFocus(this);}" value=""/>
		
            </div>
         </div>';
		$r['html'] .= '<div class="form-group edit-form-left"><div class="col-sm-12"><div class="row">
   
           
					 
					</div></div>
			 
	
	
	
				 
	
			 
         </div>';
	
			 
	
	
			$r['html'] .= '<div class="modal-footer">';
			$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
			$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
			$r['html'] .= '</div>';
			$_POST['action'] = 'quick-' . $_POST['action'];
			foreach ($_POST as $k=>$v){
				$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
			}
			///
				
			$r['event'] = $_POST['action'];
			//$r['callback'] = true;  
			//$r['callback_function'] = '';
			echo json_encode($r);exit;
			break;
	
	case 'quick-quick-edit-food':
		$id = post('id',0);
		$f = post('f',[]);
		Yii::$app->db->createCommand()->update(\app\modules\admin\models\Foods::tableName(),$f,['id'=>$id])->execute();
		$r['new_value'] = $f['title'];
		$r['id']=$id;
		$r['event'] = $_POST['action'];
		echo json_encode($r);exit;
		break;
	case 'quick-edit-food':
			$r = array(); $r['html'] = '';
			$id = post('id',0);
			$item = \app\modules\admin\models\Foods::getItem($id);	
			
			$r['html'] .= '<div class="form-group">
          <label class="col-sm-12 control-label aleft">Tiêu đề</label>
          <div class="col-sm-12">
	<input name="f[title]" class="form-control input-sm required" value="'.(!empty($item) ? uh($item['title']) : '').'"/>
			
            </div>
         </div>';
		 
			
					 
			
			
					$r['html'] .= '<div class="modal-footer">';
					$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
					$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
					$r['html'] .= '</div>';
					$_POST['action'] = 'quick-' . $_POST['action'];
					foreach ($_POST as $k=>$v){
						$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
					}
					///
			
					$r['event'] = $_POST['action'];
					//	$r['existed'] = $existed;
					echo json_encode($r);exit;
					break;
	case 'quick-add-foods-to-menu':
		$f = post('f',[]); $child_id = post('child_id',[]);
		$new = post('new',[]);
		$count = post('count',0);
		$existed = explode(',', post('existed'));
		$m = load_model('foods');
		if(!empty($new)){
			foreach ($new as $v){
				if($v['title'] != "" && (new Query())->from($m->tableName())->where(['title'=>$v['title'],'sid'=>__SID__])->count(1) == 0){
					$v['sid'] = __SID__;
					$child_id[] = Yii::$app->zii->insert($m->tableName(),$v);
				}
			}
		}
		
		$new2 = explode(';', post('new2',''));
		if(!empty($new2)){
			foreach ($new2 as $k=>$v){
				if(trim($v) != ""){
					$item = (new Query())->from($m->tableName())->where(['title'=>trim($v),'sid'=>__SID__])->one();
					if(!empty($item)){
						$id = $item['id'];
					}else{
						$id = Yii::$app->zii->insert($m->tableName(),['title'=>trim($v),'sid'=>__SID__]);
					}
					$child_id[] = $id;
				}
			}
		}
		
		$r = [];
		$r['html']  = ''; 
		if(!empty($child_id)){
			$l = $m->getList(['listItem'=>false,'limit'=>1000,'in'=>$child_id]);
			
			if(!empty($l)){
				foreach ($l as $k=>$v){
					$count++;
					$existed[] = $v['id'];
					$r['html'] .= '<tr><td class="center">'.($count).'
							<input type="hidden" name="menus[]" value="'.$v['id'].'"/>
							</td>
		<td class="">'.uh($v['title']).'</td>
		<td class="center"><i onclick="removeTrItem(this)" class="fa fa-trash f12px pointer"></i></td>
	
		</tr>';
					
				}
			}
		}  
		 
		
		
		$r['event'] = $_POST['action'];
		$r['existed'] = $existed;
		$r['count'] = $count;
		echo json_encode($r);exit;
		break;
	case 'add-foods-to-menu':
		$r = array(); $r['html'] = '';
		$existed = post('existed');
		
		
		$r['html'] .= '<div class="form-group hide">
          <label class="col-sm-12 control-label aleft">Chọn các món ăn có sẵn</label>
          <div class="col-sm-12 group-sm34">
 <select name="child_id[]" multiple data-existed="'.$existed.'" data-role="chosen-load-foods" class="form-control ajax-chosen-select-ajax" style="width:100%">';
$m = load_model('foods');
$l = $m->getList(['listItem'=>false,'limit'=>100,'not_in'=>$existed]);
if(!empty($l)){
	foreach ($l as $k=>$v){
		$r['html'] .= '<option value="'.$v['id'].'">'.uh($v['title']).'</option>';
	}
}
        $r['html'] .= '</select></div></div>';
		 
$r['html'] .= '<div class="form-group"> 
          <label class="col-sm-12 control-label hide aleft">Thêm món ăn </label>
          <div class="col-sm-12">
		<input data-action="load_foods" data-delimiter=" " class="form-control input-sm autocomplete tagsinput1" type="text" name="new2" value="" placeholder="Nhập tên món ăn"/>
		
		<p class="help-block italic f11px">Chọn từ danh sách có sẵn hoặc nhập mới cách nhau bởi dấu chấm phẩy ";"</p>
		
	<table class="table table-bordered vmiddle hide">
<thead><th class="center w50p">#</th><th class="center">Tên món ăn</th> </thead>
<tbody>';
 
for($i=0;$i<5;$i++){
	//$r['html'] .= '<tr><td class="center">'.($i+1).'</td><td class=""><input class="form-control input-sm" type="text" name="new['.$i.'][title]" value="" placeholder="Tên món ăn"/></td></tr>';
}

		
		
		 
		
				
$r['html'] .= '</tbody>		
		</table>
        
            </div>
         </div>';


$r['html'] .= '<div class="modal-footer">';
$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
$r['html'] .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
			
		$r['event'] = $_POST['action'];
	//	$r['existed'] = $existed;
		echo json_encode($r);exit;
		break;
	case 'changeNotificationState':
		$id = post('id',0);
		Yii::$app->db->createCommand()->update('notifications',['state'=>1],['sid'=>__SID__,'id'=>$id])->execute();
		exit;
		break;
	case 'changeOrderPrice':
		$discount = post('discount',0);
		$vat = post('vat',0);
		$currency = post('currency',1);
		$price = post('price',0);
		$p = ($price - $discount) * (($vat+100))/100;
		
		echo json_encode([
				'price' => $p,
				'price_after'=>Yii::$app->zii->showPrice($p,$currency),
				'price_text'=>docso($p)
		]); 
		exit;
		break;
 
	case 'setDefaultCurrency':
		$v = Yii::$app->zii->getCurrency(post('id',1));	 
		echo json_encode($v); exit;
		break;
	case 'get_item_link':
	
		$url = post('url','');
			
		echo cu(parse_url_post($url),true);
		exit;
		break;
	case 'get_local_not_in_group':
		$r = []; $html = '';
		$id = post('id');
		$not_in = post('not_in');
		$m = load_model('nationality_groups');
		$l = $m->get_all_local_other($id,$not_in);
		if(!empty($l)){
			foreach ($l as $k=>$v){
				$html .= '<option selected value="'.$v['id'].'">'.$v['name'].'</option>';
			}
		}
		$r['html'] = $html;
		echo json_encode($r); exit;
		break;
	case 'quick-add-more-ticket':
		$f = post('f',0);
		$id = post('id',0);
		$supplier_id = post('supplier',0);
		if($id>0) $f['id'] = $id;
		$prices = isset($_POST['prices']) ? $_POST['prices'] : [];
		if(!empty($f)){
			$m = load_model('tickets');
			if(isset($f['id']) && $f['id'] > 0){
				$id = $f['id']; unset($f['id']);
				Yii::$app->db->createCommand()->update($m->tableName(),$f,['id'=>$id])->execute();
			}else{
				//
				$f['sid'] = __SID__;
				 
				Yii::$app->db->createCommand()->insert($m->tableName(),$f)->execute();
				$id = Yii::$app->db->createCommand("select max(id) from ".$m->tableName())->queryScalar();
			}
			//
			if($supplier_id>0){
				Yii::$app->db->createCommand()->insert($m->tableToSupplier(),[
						'item_id'=>$id,
						'supplier_id'=>$supplier_id
				])->execute();
			}
			
			// update price
			Yii::$app->db->createCommand()->delete($m->tableToPrices(),['item_id'=>$id])->execute();
			Yii::$app->db->createCommand()->delete($m->tableToNationalityGroup(),['ticket_id'=>$id])->execute();
			if(!empty($prices)){
				foreach ($prices as $k=>$v){
					// $k = $season_id
					if(!empty($v)){
						foreach ($v as $k1=>$v1){
							// $k1 = $group_id
							Yii::$app->db->createCommand()->insert($m->tableToNationalityGroup(),['ticket_id'=>$id,'group_id'=>$k1])->execute();
							//view($a);
							// $currency = $v1['currency]
							if(!empty($v1['list'])){
								foreach ($v1['list'] as $k2=>$v2){
									// $k2 = guest_id
									Yii::$app->db->createCommand()->insert($m->tableToPrices(),[
											'group_id'=>$k1,
											'guest_group_id'=>$k2,
											'season_id'=>$k,
											'price1'=>cprice($v2['price1']),
											'currency'=>$v1['currency'],
									'item_id'=>$id])->execute();
	
								}
							}
						}
					}
				}
			}
		}
		//$r['event'] = 'reload';
		//$r['delay'] = 2000;
		$r['modal'] = true;
		$r['modal_content'] = 'Thao tác thành công.';
		echo json_encode($r);exit;
		break;
	case 'add-more-nationality-group-to-tickets':
			$r = array(); $r['html'] = '';
			$m =load_model('nationality_groups');
			$type_id = isset($_POST['type_id']) ? $_POST['type_id'] : 2;
			///view($type_id);
			//
			$existed = post('existed');
			//
			$l4 = $m->get_all_supplier_group(0,['not_in'=>$existed,'state'=>2]) ;
			$r['html'] = '<div class="form-group">';
			$r['html'] .= '<div class="group-sm34 col-sm-12"><select name="f[child_id][]" multiple data-existed="'.$existed.'" data-role="chosen-load-season" class="form-control ajax-chosen-select-ajax" style="width:100%">';
			if(!empty($l4)){
				foreach ($l4 as $k4=>$v4){
		
					$r['html'] .= '<option value="'.$v4['id'].'">'.$v4['title'].' ('.$v4['count_local'] .' quốc gia)</option>';
		
				}
			}
			$r['html'] .= '</select></div>';
			$r['html'] .= '</div><p class="help-block italic ">*** Bạn có thể chọn giá trị có sẵn hoặc thêm mới ở ô nhập bên dưới.</p><hr>';
			$r['html'] .= '<div class="">';
		
			$r['html'] .= '<div class="group-sm34"><p>Thêm mới nhóm</p>';
			$r['html'] .= '<table class="table vmiddle table-hover table-bordered">';
			$r['html'] .= '<tbody class="">';
		
			for($i=0; $i<1;$i++){
		
				$r['html'] .= '<tr>
    				<td class="pr"><input type="text" class="sui-input w100 form-control input-sm" value="" name="new['.$i.'][title]" placeholder="Tên nhóm"/></td>';
				$r['html'] .= '</tr>';
					
				$r['html'] .= '<tr><td>';
				$r['html'] .= '<div class="form-group">
          <label class="col-sm-12 control-label aleft">Quốc gia trong nhóm</label>
          <div class="col-sm-12">
		
              <select name="new['.$i.'][local_id][]" multiple data-existed="'.$existed.'" data-role="chosen-load-country" class="form-control ajax-chosen-select-ajax" style="width:100%">
   
		
		
          </select>
              		<label class="mgt15"><input data-action="get_local_not_in_group" data-id="'.post('id').'" onchange="get_local_not_in_group(this)" type="checkbox" /> Chọn tất cả các quốc gia</label>
              		</div>
         </div>';
		
				$r['html'] .= '</td></tr>';
		
			}
		
			$r['html'] .= '</tbody></table>';
			$r['html'] .= '</div>';
		
		
			$r['html'] .= '<div class="group-sm34"><p>Danh sách các nhóm đã thêm</p>';
			$r['html'] .= '<table class="table vmiddle table-hover table-bordered">';
			$r['html'] .= '<thead><tr>
    				<th>Tên nhóm</th>
    				<th class="coption"></th>
    				</tr></thead>';
			$r['html'] .= '<tbody class="">';
			$l = $m -> get_supplier_group(post('id'));
			if(!empty($l)){
				foreach ($l as $k=>$v){
		
					$r['html'] .= '<tr>
    				<td class="pr"><a>'.$v['title'].' <i>('.$v['count_local'].' quốc gia)</i></a></td>
    				<td class="center">
    						<a target="_blank" href="'.AdminMenu::get_menu_link('nationality_groups').'?supplier_id='.post('id').'" class="btn btn-link edit_item  icon">Sửa</a>
    						<a data-action="quick_delete_nationality_group_supplier" data-group_id="'.$v['id'].'" data-supplier_id="'.post('id').'" onclick="return quick_delete_nationality_group_supplier(this)" class="btn btn-link delete_item icon" data-title="Xóa bản ghi này ?" title="">Xóa</a>
    						</td>';
					$r['html'] .= '</tr>';
				}}
					
				$r['html'] .= '</tbody></table>';
				$r['html'] .= '</div>';
		
				$r['html'] .= '</div>';
				//
				$r['html'] .= '<div class="modal-footer">';
				$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
				$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
				$r['html'] .= '</div>';
				$_POST['action'] = 'quick-' . $_POST['action'];
				foreach ($_POST as $k=>$v){
					$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
				}
				///
					
				$r['event'] = $_POST['action'];
				$r['existed'] = $existed;
				echo json_encode($r);exit;
					
				break;
	case 'quick-add-more-nationality-group-to-tickets':
		$r = [];
	 
		$f = isset($_POST['f']) ? $_POST['f'] : array();
		$child_id = isset($f['child_id']) ? $f['child_id'] : [];
		$new = isset($_POST['new']) ? $_POST['new'] : array();
		$m = load_model('nationality_groups');
		//$supplier_id = post('id');
		 
		if(!empty($new)){
			foreach ($new as $k=>$v){
				if(trim($v['title'] != "")){
					
					Yii::$app->db->createCommand()->insert($m->tableName(),['title'=>$v['title'],'state'=>2,'sid'=>__SID__])->execute();
					$group_id = Yii::$app->db->createCommand("select max(id) from ".$m->tableName())->queryScalar();
					 
					//Yii::$app->db->createCommand()->insert($m->table_to_supplier(),['group_id'=>$group_id],['supplier_id',$supplier_id]);
					if(isset($v['local_id']) && !empty($v['local_id'])){
						foreach ($v['local_id'] as $k1=>$v1){
							Yii::$app->db->createCommand()->insert($m->tableToLocal(),['group_id'=>$group_id,'local_id'=>$v1])->execute();
						}
					}
					$child_id[] = $group_id;
				}
			}
		}
		$r['html'] = '';
		if(!empty($child_id)){
			$m1 = load_model('guest_groups');
			$guests = $m1->getAll();
			$l = $m->getListByID($child_id);
			if(!empty($l)){
				foreach ($l as $k=>$v){
					$r['html'] .= '<tr><td>'.$v['title'].'</td>';
					if(!empty($guests)){
						foreach ($guests as $g){
							$r['html'] .= '<td><input data-decimal="'.Yii::$app->zii->showCurrency(isset($p['currency']) && $p['currency'] ? $p['currency'] : 1,3).'" name="prices[0]['.$v['id'].'][list]['.$g['id'].'][price1]" class="form-control bold input-sm aright ajax-number-format input-currency-price-'.$v['id'].'" /></td>';
						}
					}
					$r['html'] .= '<td class="group-sm30">';
					//if(isset(\ZAP\Zii::$site['other_setting']['currency']['list'])){
						$r['html'] .= '<select data-target-input=".input-currency-price-'.$v['id'].'" data-decimal="'.Yii::$app->zii->showCurrency((isset($p['currency']) && $p['currency'] ? $p['currency'] : 1),3).'" onchange="get_decimal_number(this);" class="ajax-select2-no-search sl-cost-price-currency form-control select2-hide-search input-sm" name="prices[0]['.$v['id'].'][currency]">';
	
						foreach(Yii::$app->zii->getUserCurrency()['list'] as $k2=>$v2){
							$r['html'] .= '<option value="'.$v2['id'].'" '.(isset($p['currency']) && $p['currency'] == $v2['id'] ? 'selected' : '').'>'.$v2['code'].'</option>';
						}
	
						$r['html'] .= '</select>';
					//}
					$r['html'] .= '</td><td class="center"><input type="hidden" value="'.$v['id'].'" class="input-hidden-id"/><i class="glyphicon glyphicon-trash pointer" onclick="removeTrItem(this);reload_existed_btn(this);"></i></td></tr>';
				}
			}
		}
	
		//$r['target'] = $_POST['target'];
		$r['event'] = $_POST['action'];
		$r['existed'] = $child_id;
		echo json_encode($r);exit;
		break;
	case 'ajax-form-add-new':
		$r = array(); $r['html'] = '';
		$existed = post('existed');
		$id = post('id',0);// isset($_POST['id']) && $_POST['id'] > 0 ? $_POST['id'] : 0;
		
		//
		switch (post('controller')){
			case 'tickets':
				$m2 = load_model('tickets');
				$p2 = load_model('departure_places');
				$item = $m2->getItem($id);
				
				$place_id = post('place',0);
				if($place_id > 0) $item['place_id'] = $place_id;
				
				$r['html'] = '<div class="form-group"><label class="bold col-sm-12">Địa danh</label>';
				$r['html'] .= '<div class="group-sm34 col-sm-12"><select name="f[place_id]" data-existed="'.$existed.'" data-role="chosen-load-place" class="form-control ajax-chosen-select-ajax" style="width:100%">';
				if(isset($item['place_id']) && $item['place_id'] > 0){
					$place = $p2->getItem($item['place_id']);
					if(!empty($place)){
						$r['html'] .= '<option selected value="'.$place['id'].'">'.$place['name'].'</option>';
					}
				}
				$r['html'] .= '</select></div>';
				$r['html'] .= '</div>';
			  
				 
				$r['html'] .= '<div class="group-sm34">
		    				<label class="bold">Tiêu đề</label>
    <input type="text" class="form-control required" placeholder="Tiêu đề" value="'.(isset($item['title']) ? uh($item['title']) : '').'" name="f[title]">
	
		    				';
				$r['html'] .= '<div class="mgt15"><label class="bold">Bảng giá</label><table class="table vmiddle table-hover table-bordered">';
				$r['html'] .= '<thead><tr><th>Nhóm khách</th>';
				 
				$m = load_model('guest_groups');
				$guests = $m->getAll();
				if(!empty($guests)){
					foreach ($guests as $g){
						$r['html'] .= '<th class="center">'.$g['title'].'</th>';
					}
				}
				 
				$r['html'] .= '<th class="center">Tiền tệ</th><th class="center"></th>';
				$r['html'] .= '</tr></thead>';
	
				$r['html'] .= '<tbody class="ajax-load-group-nationality">';
	
				$existed = [];
				$l = $m2->getNationalityGroup(post('id')); 
				if(!empty($l)){
					foreach ($l as $k=>$v){
						$p = $m2->get_prices($id,$v['id']);
						//view($p);
						$existed[] = $v['id'];
						$r['html'] .= '<tr><td>'.$v['title'].'</td>';
						if(!empty($guests)){
							foreach ($guests as $g){
								$r['html'] .= '<td><input value="'.(isset($p[$g['id']]['price1']) ? $p[$g['id']]['price1'] : '').'" data-decimal="'.Yii::$app->zii->showCurrency(isset($p[$g['id']]['currency']) ? $p[$g['id']]['currency'] : 1,3).'" name="prices[0]['.$v['id'].'][list]['.$g['id'].'][price1]" class="form-control bold input-sm aright ajax-number-format input-currency-price-'.$v['id'].'" /></td>';
							}
						}
						$r['html'] .= '<td class="group-sm30">';
						//if(isset(\ZAP\Zii::$site['other_setting']['currency']['list'])){
							$r['html'] .= '<select data-target-input=".input-currency-price-'.$v['id'].'" data-decimal="'.Yii::$app->zii->showCurrency(isset($p[$g['id']]['currency']) ? $p[$g['id']]['currency'] : 1,3).'" onchange="get_decimal_number(this);" class="ajax-select2-no-search sl-cost-price-currency form-control select2-hide-search input-sm" name="prices[0]['.$v['id'].'][currency]">';
	
							foreach(Yii::$app->zii->getUserCurrency()['list'] as $k2=>$v2){
								$r['html'] .= '<option value="'.$v2['id'].'" '.(isset($p[$g['id']]['currency']) && $p[$g['id']]['currency'] == $v2['id'] ? 'selected' : '').'>'.$v2['code'].'</option>';
							}
	
							$r['html'] .= '</select>';
						//}
						$r['html'] .= '</td><td class="center"><input type="hidden" value="'.$v['id'].'" class="input-hidden-id"/><i class="glyphicon glyphicon-trash pointer" onclick="removeTrItem(this);reload_existed_btn(this);"></i></td></tr>';
					}
				}
				$r['html'] .= '</tbody></table>';
	
	
			  
				$r['html'] .= '</div></div>';
	
				$r['html'] .= '<p class="aright btn-list-add-more-1">
		    				<button data-class="w60" data-action="add-more-nationality-group-to-tickets" data-title="Thêm nhóm quốc tịch" data-existed="'.implode(',', $existed).'" type="button" data-id="0" data-target=".ajax-result-nationality-group" onclick="open_ajax_modal(this);" data-modal-target=".mymodal1" class="btn btn-warning btn-add-more"><i class="glyphicon glyphicon-plus"></i> Thêm nhóm quốc tịch</button>
		    				</p><hr/>';
	
				$_POST['action'] = 'quick-add-more-ticket';
				break;
		}
	
		 
	
		//
		$r['html'] .= '<div class="modal-footer">';
		$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$r['html'] .= '</div>';
	
		foreach ($_POST as $k=>$v){
			$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		$r['supplier_id'] = post('supplier');
		$r['event'] = $_POST['action'];
		$r['existed'] = $existed;
		echo json_encode($r);exit;
	
		break;
	case 'load_distance_to_element':
		//$q = $_POST['data']['q'];
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
		$sql .= " limit 200";
		$l = Yii::$app->db->createCommand($sql)->queryAll();
		$r = '';
		if(!empty($l)){
			foreach ($l as $k=>$v){
				$r .= '<option value="'.$v['id'].'">'.$v['title'].'</option>';
			}
		}
		echo $r; exit;
		break;
	case 'add_new_cost_distance':
		$f = post('f',[]);
		$fn = post('fn',[]);
		$existed = isset($_POST['existed']) ? $_POST['existed'] : '';
		$existed = $existed != "" ? explode(',', $existed) : array();
		$index = post('index') >0 ? post('index') : 0;
		$html = '';$m = load_model('cars');
		// Check them moi
	
		if(!empty($fn)){
			foreach ($fn as $k=>$v){
				$v['title'] = trim($v['title']);
				if(strlen($v['title'])>2){
					$d = load_model('distances');
					if(Yii::$app->zii->countTable($m->tableDistances(),array(
							'title'=>$v['title'],
							'sid'=>__SID__,
							'type_id'=>$f['type_id']
					)) == 0){
						$v['sid'] = __SID__;
						$v['type_id'] = $f['type_id'];
						$nid = $d->get_max_id($d->tableName());
						Yii::$app->db->command()->insert($m->tableDistances(),$v+array('id',$nid))->execute();
						$f['distance_id'][] = $nid;
						//view($nid,true);
						//
						if(isset($f['place_id']) && strlen($f['place_id'])>0){
							foreach (explode(',', $f['place_id']) as $t){
								Yii::$app->db->command()->insert('distance_to_places',array(
										'distance_id'=>$nid,
										'place_id'=>$t,
										'type_id',$f['type_id']))->execute();
							}
						}
					}else{
	
					}
				}
			}
		}
		//
			 
		$r = array();
		//
		$l3 = $m->get_list_cars_by_seats($f['id'],array('is_active'=>1));
		if(isset($f['distance_id']) && !empty($f['distance_id']) && !empty($l3)){
			$l4 = $m->get_distance_by_id($f['distance_id']);
			if(!empty($l4)){
				foreach ($l4 as $k4=>$v4){
					$existed[] = $v4['id']; $index++;
					$currency[$v4['id']] = $dactive[$v4['id']] = 1;
					foreach ($l3 as $k3=>$v3){
						if(!isset($r[$k3])) $r[$k3] = '';
						$r[$k3] .= '<tr class="tr-distance-id-'.$v4['id'].'"><td>'.$v4['title'].'</td>';
						if(!empty($v3)){
							foreach ($v3 as $c3=>$t3){
									
								$r[$k3] .= '<td class="center"><input name="dprice['.$k3.']['.$v4['id'].']['.$t3['id'].'][price1]" class=" sui-input sui-input-focus w100 ajax-number-format aright sl-cost-price input-currency-price-'.$v4['id'].'-'.$k3.'" data-decimal="'.Yii::$app->zii->showCurrency(isset($currency[$v4['id']]) ? $currency[$v4['id']] : 1,3).'" value="" /></td>';
								$r[$k3] .= '<td class="center"><input name="dprice['.$k3.']['.$v4['id'].']['.$t3['id'].'][price2]" class=" sui-input sui-input-focus w100 ajax-number-format aright sl-cost-price input-currency-price-'.$v4['id'].'-'.$k3.'" data-decimal="'.Yii::$app->zii->showCurrency(isset($currency[$v4['id']]) ? $currency[$v4['id']] : 1,3).'" value="" /></td>';
							}
						}
						$r[$k3] .= '<td class="center w100p">';
						//if(isset(Zii::$site['other_setting']['currency']['list'])){
							$r[$k3] .= '<select data-target-input=".input-currency-price-'.$v4['id'].'-'.$k3.'" data-decimal="'.Yii::$app->zii->showCurrency(isset($currency[$v4['id']]) ? $currency[$v4['id']] : 1,3).'" onchange="get_decimal_number(this);" class="ajax-select2-no-search sl-cost-price-currency form-control select2-hide-search input-sm" name="dcurrency['.$k3.']['.$v4['id'].'][currency]">';
							//if(isset($v['currency']['list']) && !empty($v['currency']['list'])){
							foreach(Yii::$app->zii->getUserCurrency()['list'] as $k2=>$v2){
								$r[$k3] .= '<option value="'.$v2['id'].'" '.(isset($currency[$v4['id']]) && $currency[$v4['id']] == $v2['id'] ? 'selected' : '').'>'.$v2['code'].'</option>';
							}
							//}
	
							$r[$k3] .= '</select>';
						//}
						$r[$k3] .= '</td>';
						$r[$k3] .= '<td class="center">'.getCheckBox(array(
								'name'=>'dactive['.$k3.']['.$v4['id'].'][is_active]',
								'value'=>$dactive[$v4['id']],
								'type'=>'singer',
								'class'=>'switchBtn ajax-switch-btn',
								//'cvalue'=>true,
								 
						)).'</td>';
						$r[$k3] .= '<td class="center"><i title="Xóa" data-name="delete_price_distance_id" onclick="remove_item_class(this);" data-confirm="Lưu ý: Chặng '.$v4['title'].' sẽ không còn được áp dụng cho toàn bộ các phương tiện của doanh nghiệp này. Bạn có chắc chắn ?" data-target=".tr-distance-id-'.$v4['id'].'" class="pointer glyphicon glyphicon-trash"></i></td>';
						$r[$k3] .= '</tr>';
	
					}
				}}
		}
			
		echo json_encode(array('event'=>post('action'),'r'=>$r,'index'=>$index,'target_class'=>'ajax-result-price-distance-','existed'=>implode(',',  $existed)));
		exit;
		break;
		 
	case 'auto_load_car_list':
		$id = $_POST['id'];
		$m = load_model('cars');
		$lx = $m->getListCars($id);
		$sloption = '<select name="'.$_POST['name'].'" class="ajax-select2 sui-input sui-input-focus w100 numberFormat center sl-cost-car_id">';
		if(!empty($lx)){
			foreach ($lx as $k2=>$v2){
				$sloption .= '<option value="'.$v2['id'].'">'.$v2['title'].'</option>';
			}
		}
		$sloption .= '</select>';
		echo $sloption;
		exit;
		break;
	case 'quick_add_more_vehicle_category':
		$m = app\modules\admin\controllers\Vehicles_categorysController(); 
		$id = $m->add(false);
		$item = $m->model->getItem($id);
		$html = '';
		if(!empty($item)){
			$html = '';
		}
		echo json_encode(array('id'=>$id,'html'=>$html, 'event'=>$_POST['action']));
		exit;
		break;
	case 'check_vehicle_category_existed':
		 
		$val = post('val');
		$id = post('id',0);
		$a = $id > 0 ?  
		(new Query())->from('vehicles_categorys')->where(['title'=>$val,'sid'=>__SID__])
		->andWhere(['not in','id',$id])
		->count(1) : 
		(new Query())->from('vehicles_categorys')->where(['title'=>$val,'sid'=>__SID__])->count(1);
		echo json_encode(array(
				'state'=>$a > 0 ? true : false
		));
		exit;
		break;
	case 'set_quantity_vehicles_categorys':
		$f = isset($_POST['f']) ? $_POST['f'] : array();
		$existed_id = isset($_POST['existed_id']) ? $_POST['existed_id'] : '';
		$existed_id = $existed_id != "" ? explode(',', $existed_id) : array();
		$index = post('index') >0 ? post('index') : 0;
		$html = '';
		if(!empty($f)){
			foreach ($f as $k=>$v){
					
				if($v['quantity'] > 0){
					$index++;
					$existed_id[] = $v['id'];
					$html .= '<tr><td></td>';
					$html .= '<td>'.$v['title'].'</td>';
					$html .= '<td>'.$v['maker_name'].'</td>';
					$html .= '<td><input type="text" name="c['.$index.'][quantity]" value="'.$v['quantity'].'" class="form-control center input-sm ajax-number-format"/><input type="hidden" name="c['.$index.'][id]" value="'.$v['id'].'"/><input type="hidden" name="c['.$index.'][title]" value="'.$v['title'].'"/><input type="hidden" name="c['.$index.'][maker_id]" value="'.$v['maker_id'].'"/><input type="hidden" name="c['.$index.'][maker_name]" value="'.$v['maker_name'].'"/><input type="hidden" name="c['.$index.'][is_active]" value="1"/></td><td></td><td></td>';
					$html .= '</tr>';
				}
			}
		}
		echo json_encode(array('event'=>post('action'),'html'=>$html,'index'=>$index,'target_class'=>'ajax-html-result-before-list-vehicles','existed_id'=>implode(',',  $existed_id)));
		exit;
		break;
	case 'get_list_vehicles_makers':
		$id = post('id',0);
		$index = post('index',0);
		$html = '';
		$m = load_model('vehicles_categorys');
		$l = $m->getAvailableVehicle(array(
				'limit'=>1000, 'maker_id'=>$id,
				'type_id'=>$_POST['type_id'],
				'supplier_id'=>post('supplier_id')
		));
		if(!empty($l)){
			foreach ($l as $k=>$v){
				$index += $k;
				$html .= '<tr><td class="w50p">'.($k+1).'</td>';
				$html .= '<td>'.$v['title'].'</td>';
				$html .= '<td class="w150p">'.$v['maker_name'].'</td>';
				$html .= '<td class="w100p"><input type="text" name="f['.$v['id'].'][quantity]" value="" class="form-control center input-sm ajax-number-format"/>
    								 
    								</td>';
				$html .= '</tr>';
			}
		}
		//$html .= '</tbody></table>';
		echo $html;
		exit;
		break;
	case 'quick-add-more-room-group':
		$r = [];
		$f = post('f',[]);
		$new = post('new',[]);
		$supplier_id = post('supplier_id',post('id',0));
		$delete_price_distance_id = post('delete_price_distance_id',[]);
		if(!empty($delete_price_distance_id)){
			Yii::$app->db->createCommand()->delete('{{%rooms_groups}}',array('id'=>$delete_price_distance_id,'sid'=>__SID__))->execute();
		}
		if(!empty($f)){
			foreach ($f as $k=>$v){
				Yii::$app->db->createCommand()->update('{{%rooms_groups}}',$v,['id'=>$v['id'],'sid'=>__SID__])->execute();
			}
		}
		if(!empty($new)){
			foreach ($new as $k=>$v){
				if($v['pmin'] > 0 && $v['pmax'] > $v['pmin'] && $v['title'] != ""){
					$v['sid'] = __SID__;
					if(isset($v['supplier_id'])) unset($v['supplier_id']);
					$id = Yii::$app->zii->insert('{{%rooms_groups}}',$v);
					if($id>0){
						
					}
				}
			}
		}
		if(post('update_quotation') == 'on'){
			$controller_code = post('controller_code',post('type_id'));
			switch ($controller_code){
				case TYPE_ID_VECL:
					$incurred_prices = \app\modules\admin\models\Seasons::get_rooms_groups($supplier_id,false);
					if(!empty($incurred_prices)){
						foreach ($incurred_prices as $k=>$v){
							Yii::$app->db->createCommand()->update(Yii::$app->zii->getTablePrice($controller_code,post('price_type',1)),['group_id'=>$v['id']],[
									'supplier_id'=>$supplier_id,
									'group_id'=>0
							])->execute();
							break;
						}
					}
					break;
			}
		}
		//$r['target'] = $_POST['target'];
		$r['event'] = 'hide-modal';
		$r['existed'] = $_POST;
		$r['callback']=true;
		$r['callback_function'] = 'reloadAutoPlayFunction();console.log(data)';
		echo json_encode($r);exit;
		break;
	case 'add-more-room-group':
		$r = array(); $r['html'] = '';
		$supplier_id = post('supplier_id',post('id',0));
		$m =load_model('rooms_categorys');
		$type_id = isset($_POST['type_id']) ? $_POST['type_id'] : 2;
		///view($type_id);
		//
		$existed = post('existed');
		//
		//$l4 = in_array($type_id,[3,4]) ? $m->get_weekend(['limit'=>100,'not_in'=>($existed != "" ? explode(',', $existed) : [])]) : $m->getList(['limit'=>100,'not_in'=>($existed != "" ? explode(',', $existed) : [])]);
		$r['html'] = '<div class="form-group"><div class="form_quick_remove_item"></div>';
		$r['html'] .= '<div class="group-sm34 col-sm-12">';
		//$r['html'] .= '<div class="group-sm34">';
		$r['html'] .= '<table class="table vmiddle table-hover table-bordered"><thead><tr>';
		$r['html'] .= '<th class="center w150p">Từ</th><th class="center w150p">Đến</th><th class="center">Tên nhóm</th><th class="center">Ghi chú</th>';
		$r['html'] .= '<th class="w50p"></th></tr></thead><tbody class="">';

		$l = \app\modules\admin\models\Seasons::get_rooms_groups($supplier_id,false);
	 
		if(!empty($l)){
			foreach ($l as $k=>$v){ 
				$r['html'] .= '<tr>
    					<td><input type="text" class="sui-input form-control input-sm w100 center ajax-number-format" value="'.$v['pmin'].'" name="f['.$k.'][pmin]" placeholder="Số lượng tối thiểu"/></td>
    					<td><input type="text" class="sui-input form-control input-sm w100 center ajax-number-format" value="'.$v['pmax'].'" name="f['.$k.'][pmax]" placeholder="Số lượng tối đa"/></td>
    					<td class="center "><input type="text" class="sui-input w100 form-control input-sm" value="'.$v['title'].'" name="f['.$k.'][title]" placeholder="Tên nhóm"/></td>
    					<td class="center "><input type="text" class="sui-input w100 form-control input-sm" value="'.$v['note'].'" name="f['.$k.'][note]" placeholder="Ghi chú"/><input type="hidden" value="'.$v['id'].'" name="f['.$k.'][id]"/> </td>
    					<td class="center"><i title="Xóa" data-name="delete_price_distance_id" data-name="removed_group_id" onclick="add_delete_item('.$v['id'].',this);removeTrItem(this);" class="pointer glyphicon glyphicon-trash"></i></td>
    							';
				$r['html'] .= '</tr>';
			}
		} 
		 
		$r['html'] .= '</tbody></table>';
		//$r['html'] .= '</div>';
		$r['html'] .= '</div>';
		$r['html'] .= '</div><p class="help-block italic ">*** Bạn có thể thêm mới nhóm ở ô nhập bên dưới. Sau khi lưu, load lại trình duyệt để nhận dữ liệu mới.</p><hr>';
		$r['html'] .= '<div class="">';
		 
		$r['html'] .= '<div class="group-sm34">';
		$r['html'] .= '<table class="table vmiddle table-hover table-bordered"><thead><tr>';
		$r['html'] .= '<th class="center w150p">Từ</th><th class="center w150p">Đến</th><th class="center">Tên nhóm</th><th class="center">Ghi chú</th>';
		$r['html'] .= '</tr></thead><tbody class="">';
		 
		for($i=0; $i<3;$i++){
	
			$r['html'] .= '<tr>
    					<td><input type="text" class="sui-input form-control input-sm w100 center ajax-number-format input-condition-required input-destination-required" value="" name="new['.$i.'][pmin]" placeholder="Số lượng tối thiểu"/></td>
    					<td><input type="text" class="sui-input form-control input-sm w100 center ajax-number-format input-condition-required input-destination-required" value="" name="new['.$i.'][pmax]" placeholder="Số lượng tối đa"/></td>
    					<td class="center "><input type="text" class="sui-input w100 form-control input-sm input-condition-required input-destination-required" value="" name="new['.$i.'][title]" placeholder="Tên nhóm"/></td>
    					<td class="center "><input type="text" class="sui-input w100 form-control input-sm" value="" name="new['.$i.'][note]" placeholder="Ghi chú"/><input type="hidden" value="'.$supplier_id.'" name="new['.$i.'][parent_id]" /></td>';
			$r['html'] .= '</tr>';
	
		}
		//$controller_code
		switch (post('controller_code')){
			case TYPE_ID_VECL:
				$c  = (new Query())->from(Yii::$app->zii->getTablePrice(TYPE_ID_VECL,post('price_type',1)))->where([
				'supplier_id'=>$supplier_id,
				'group_id'=>0
				])->count(1);
				if($c>0){
					$r['html'] .= '<tr><td colspan="5"><p><b class="red ">Opp! Chúng tôi nhận thấy rằng có <span class="underline">'.number_format($c).'</span> đơn giá đã nhập cho đơn vị này nhưng chưa thuộc nhóm nào.</b></p>
		
					<p class="bold green">Bạn có muốn cập nhật vào nhóm [đầu tiên] trong danh sách nhóm của nhà cung cấp này không ?</p>
					<label><input name="update_quotation" type="checkbox"/> Cập nhật ngay</label>
							<p class="help-block italic red "><b class="underline">Lưu ý: </b> Khi cài đặt nhóm, các đơn giá (đã nhập trước đó) mà không thuộc 1 nhóm nào sẽ bị xóa.</p></td></tr>';
				}
				break;
		}
		 
		$r['html'] .= '</tbody></table>';
		$r['html'] .= '</div>';
		$r['html'] .= '</div>';
		//
		$r['html'] .= '<div class="modal-footer">';
		$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Đóng</button>';
		$r['html'] .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		 
		$r['event'] = $_POST['action'];
		$r['existed'] = $existed;
		echo json_encode($r);exit;
		 
		break;
	case 'quick-add-more-nationality-group-to-supplier':
		$r = [];
		$f = isset($_POST['f']) ? $_POST['f'] : array();
		$child_id = isset($f['child_id']) ? $f['child_id'] : [];
		$new = isset($_POST['new']) ? $_POST['new'] : array();
		$m = load_model('nationality_groups');
		 
		$supplier_id = post('supplier_id',post('id',0));
		//view($new,true);
		if(!empty($new)){
			foreach ($new as $k=>$v){
				if(trim($v['title'] != "")){
					Yii::$app->db->createCommand()->insert($m->tableName(),['title'=>$v['title'],'sid'=>__SID__])->execute();				 
					$group_id = Yii::$app->db->createCommand("select max(id) from ".$m->tableName())->queryScalar();					
					if(isset($v['local_id']) && !empty($v['local_id'])){
						foreach ($v['local_id'] as $k1=>$v1){
							Yii::$app->db->createCommand()->insert($m->tableToLocal(),['group_id'=>$group_id,'local_id'=>$v1])->execute();
						}
					} 
					$child_id[] = $group_id;
				}
			}
		}
	 
		if(!empty($child_id)){
			foreach ($child_id as $group_id){
				Yii::$app->db->createCommand()->insert($m->tableToSupplier(),['group_id'=>$group_id,'supplier_id'=>$supplier_id])->execute();
				
			}
		}
		if(post('update_quotation') == 'on'){
			$l = \app\modules\admin\models\NationalityGroups::get_supplier_group($supplier_id);
			if(!empty($l)){
				foreach ($l as $k=>$v){
					Yii::$app->db->createCommand()->update(Yii::$app->zii->getTablePrice(post('controller_code'),post('price_type',1)),[
							'nationality_id'=>$v['id']
					],[
							'supplier_id'=>$supplier_id,
							'nationality_id'=>0
					])->execute();
					break;
				}
			}
		}
		$r['callback'] = true;
		$r['callback_function'] = 'reloadAutoPlayFunction();';
		$r['event'] = 'hide-modal';
		//$r['existed'] = $existed;
		echo json_encode($r);exit;
		break;
		 
	 
	case 'quick_delete_nationality_group_supplier':
		/*
		$m =load_model('nationality_groups');
		$sql = "delete a,b,c from {$m->tableName()} as a
		left join {$m->table_to_local()} as b on a.id=b.group_id
		left join {$m->table_to_supplier()} as c on a.id=c.group_id
		where a.state=1 and c.supplier_id=".post('supplier_id') . " and c.group_id=".post('group_id');
		Yii::$app->db->createCommand($sql)->execute();
		$sql = "delete c from {$m->table_to_supplier()} as c where c.supplier_id=".post('supplier_id') . " 
				and c.group_id=".post('group_id');
		Yii::$app->db->createCommand($sql)->execute();
		*/
		Yii::$app->db->createCommand()->delete('nationality_groups_to_supplier',[
			'group_id'=>post('group_id',0),
			'supplier_id'=>post('supplier_id',0),
		])->execute();
		exit;
		break;
	case 'quick-sadd-more-package-price-to-supplier':
		//
		$supplier_id = post('supplier_id',0);
		$child_id = post('child_id',[]);
		$new = post('new',[]);
		if(!empty($new)){
			foreach ($new as $k=>$v){
				if(trim($v['title']) != ""){ 
					$v['sid'] = __SID__;
					$v['supplier_id'] = $supplier_id;
					$child_id[] = Yii::$app->zii->insert('package_prices',$v);
				}
			}
		}
		if(!empty($child_id)){
			foreach ($child_id as $package_id){
				Yii::$app->db->createCommand()->insert('package_to_supplier',[
						'supplier_id'=>$supplier_id,
						'package_id'=>$package_id
				])->execute();
			}
		}
		//
		if(post('update_quotation') == 'on'){
			$controller_code = post('controller_code',post('type_id'));
			switch ($controller_code){
				case TYPE_ID_VECL:
					$incurred_prices = \app\modules\admin\models\PackagePrices::getPackages($supplier_id,false); 
					if(!empty($incurred_prices)){
						foreach ($incurred_prices as $k=>$v){
							Yii::$app->db->createCommand()->update(Yii::$app->zii->getTablePrice($controller_code,post('price_type',1)),['package_id'=>$v['id']],[
									'supplier_id'=>$supplier_id,
									'package_id'=>0
							])->execute();
							break;
						}
					}
					break;
			}
		}
		echo json_encode([
			'event'=>'hide-modal',
			'callback'=>true,
				
			'callback_function'=>'reloadAutoPlayFunction();console.log(data)',
				'p'=>$_POST
		]);exit;
		break;
		
	case 'sadd-more-package-price-to-supplier':
		 		
		$supplier_id = post('supplier_id',0);
		$html = '';
		//$m = new app\modules\admin\models\PackagePrices();		
		//$existed = post('existed',[]);	
		//view($existed,true);
		//$l4 = $m->getList(['not_in'=>$existed]) ;	  
		$html .= '<div class="form-group">';
		$html .= '<div class="group-sm34 col-sm-12"><select data-placeholder="Chọn 1 hoặc nhiều package đã có" name="child_id[]" multiple data-role="chosen-load-package" class="form-control ajax-chosen-select-ajax" style="width:100%">';
		//if(!empty($l4['listItem'])){
		foreach (\app\modules\admin\models\PackagePrices::getAvailabledPackages($supplier_id) as $k4=>$v4){
	
			$html .= '<option value="'.$v4['id'].'">'.$v4['title'].' </option>';
	
		}
		//}
		 
		$html .= '</select></div>';
		$html .= '</div>
				<p class="help-block italic ">*** Bạn có thể chọn giá trị có sẵn hoặc thêm mới ở ô nhập bên dưới.</p> 
				<hr>';
		$html .= '<div class="">';
		 
		$html .= '<div class="group-sm34"><p>Thêm mới package</p>';
		$html .= '<table class="table vmiddle table-hover table-bordered">';
		$html .= '<tbody class="">';
	 
		for($i=0; $i<3;$i++){
	
			$html .= '<tr>
    				<td class="pr"><input type="text" class="sui-input w100 form-control input-sm" value="" name="new['.$i.'][title]" placeholder="Tên nhóm"/></td>';
			$html .= '</tr>';
			 
			 
	
		}
		 
		$html .= '</tbody></table>';
		$html .= '</div>';
	
	
		$html .= '<div class="group-sm34"><p>Danh sách các nhóm đã thêm</p>';
		$html .= '<table class="table vmiddle table-hover table-bordered">';
		$html .= '<thead><tr>
    				<th>Tên nhóm</th>
    				<th class="coption"></th>
    				</tr></thead>';
		$html .= '<tbody class="">';
 
		$l = \app\modules\admin\models\PackagePrices::getPackages($supplier_id,false); 
		if(!empty($l)){
			$role = [
					'type'=>'single',
					'table'=>\app\modules\admin\models\PackagePrices::tableName(),
					//'controller'=>Yii::$app->controller->id,
					'action'=>'Ad_quick_change_item'
			];
			foreach ($l as $k=>$v){
				$role['id']=$v['id'];
				$role['action'] = 'Ad_quick_change_item';
				$html .= '<tr class="tr-item-odr-'.$supplier_id.'-'.$v['id'].'">'.Ad_list_show_qtext_field($v,[
        		'field'=>'title',
        		'class'=>'number-format aleft',
        		'decimal'=>0,
        		'role'=>$role
        ]).'
    				 
    				<td class="center pr">
    						<i data-controller_code="'.post('controller_code').'" data-modal-target=".mymodal1" data-trash="1" data-action="open-confirm-dialog" data-title="Xác nhận xóa package !" data-class="modal-sm" data-confirm-action="quick_delete_package_supplier" data-package_id="'.$v['id'].'" data-supplier_id="'.$supplier_id.'" onclick="return open_ajax_modal(this);" class="pointer fa fa-trash-o f12e" data-toggle="tooltip" data-placement="top" title="Tạm xóa, sau này có thể thêm trở lại từ ô chọn bên trên. Toàn bộ đơn giá đã nhập cho package này sẽ bị xóa."></i>
    						<a data-controller_code="'.post('controller_code').'" data-modal-target=".mymodal1" data-trash="0" data-action="open-confirm-dialog" data-title="Xác nhận xóa package !" data-class="modal-sm" data-confirm-action="quick_delete_package_supplier" data-package_id="'.$v['id'].'" data-supplier_id="'.$supplier_id.'" onclick="return open_ajax_modal(this);" class="btn btn-link delete_item icon" data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn bản ghi này. Toàn bộ dữ liệu đã nhập cho package này sẽ bị xóa.">Xóa</a>
    						</td>';
				$html .= '</tr>';
			}}else{
				$html .= '<tr><td colspan="2"><p><b class="red ">Bạn chưa sử dụng package nào.</b></p>
						
						<p class="help-block italic red "><b class="underline">Lưu ý: </b> Khi cài đặt package, các đơn giá (đã nhập trước đó) mà không thuộc 1 package nào sẽ bị xóa.</p></td></tr>';
			}
			switch (post('controller_code')){
				case TYPE_ID_VECL:
					$c  = (new Query())->from(Yii::$app->zii->getTablePrice(TYPE_ID_VECL,post('price_type',1)))->where([
					'supplier_id'=>$supplier_id,
					'package_id'=>0
					])->count(1);
					if($c>0){
						$html .= '<tr><td colspan="5"><p><b class="red ">Opp! Chúng tôi nhận thấy rằng có <span class="underline">'.number_format($c).'</span> đơn giá đã nhập cho đơn vị này nhưng chưa thuộc nhóm nào.</b></p>
			
					<p class="bold green">Bạn có muốn cập nhật vào nhóm [đầu tiên] trong danh sách nhóm của nhà cung cấp này không ?</p>
					<label><input name="update_quotation" type="checkbox"/> Cập nhật ngay</label>
							<p class="help-block italic red "><b class="underline">Lưu ý: </b> Khi cài đặt nhóm, các đơn giá (đã nhập trước đó) mà không thuộc 1 nhóm nào sẽ bị xóa.</p></td></tr>';
					}
					break;
			}
			$html .= '</tbody></table>';
			$html .= '</div>';
	
			$html .= '</div>';
			//
			$html .= '<div class="modal-footer">';
			$html .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
			$html .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
			$html .= '</div>';
			$_POST['action'] = 'quick-' . $_POST['action'];
			foreach ($_POST as $k=>$v){
				$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
			}
			///
			 
			echo json_encode([
					'html'=>$html,
					'callback'=>true,
					'callback_function'=>'jQuery(\'[data-toggle="tooltip"]\').tooltip();'
			]);exit;
			 
			break;	
		
	case 'add-more-package-price-to-supplier':
		$r = array(); $r['html'] = '';		
		$m = new app\modules\admin\models\PackagePrices();		
		$existed = post('existed',[]);	
		//view($existed,true);
		$l4 = $m->getList(['not_in'=>$existed]) ;	  
		$r['html'] = '<div class="form-group">';
		$r['html'] .= '<div class="group-sm34 col-sm-12"><select name="f[child_id][]" multiple data-existed="'.$existed.'" data-role="chosen-load-package" class="form-control ajax-chosen-select-ajax" style="width:100%">';
		if(!empty($l4['listItem'])){
			foreach ($l4['listItem'] as $k4=>$v4){
	
				$r['html'] .= '<option value="'.$v4['id'].'">'.$v4['title'].' </option>';
	
			}
		}
		$r['html'] .= '</select></div>';
		$r['html'] .= '</div><p class="help-block italic ">*** Bạn có thể chọn giá trị có sẵn hoặc thêm mới ở ô nhập bên dưới.</p><hr>';
		$r['html'] .= '<div class="">';
		 
		$r['html'] .= '<div class="group-sm34"><p>Thêm mới package</p>';
		$r['html'] .= '<table class="table vmiddle table-hover table-bordered">';
		$r['html'] .= '<tbody class="">';
	 
		for($i=0; $i<3;$i++){
	
			$r['html'] .= '<tr>
    				<td class="pr"><input type="text" class="sui-input w100 form-control input-sm" value="" name="new['.$i.'][title]" placeholder="Tên nhóm"/></td>';
			$r['html'] .= '</tr>';
			 
			 
	
		}
		 
		$r['html'] .= '</tbody></table>';
		$r['html'] .= '</div>';
	
	
		$r['html'] .= '<div class="group-sm34"><p>Danh sách các nhóm đã thêm</p>';
		$r['html'] .= '<table class="table vmiddle table-hover table-bordered">';
		$r['html'] .= '<thead><tr>
    				<th>Tên nhóm</th>
    				<th class="coption"></th>
    				</tr></thead>';
		$r['html'] .= '<tbody class="">';
 
		$l = $m -> getPackages(post('id')); 
		if(!empty($l)){
			$role = [
					'type'=>'single',
					'table'=>$m->tableName(),
					//'controller'=>Yii::$app->controller->id,
					'action'=>'Ad_quick_change_item'
			];
			foreach ($l as $k=>$v){
				$role['id']=$v['id'];
				$role['action'] = 'Ad_quick_change_item';
				$r['html'] .= '<tr>'.Ad_list_show_qtext_field($v,[
        		'field'=>'title',
        		'class'=>'number-format aleft',
        		'decimal'=>0,
        		'role'=>$role
        ]).'
    				 
    				<td class="center">
    						
    						<a data-action="quick_delete_package_supplier" data-id="'.$v['id'].'" data-supplier_id="'.post('id').'" onclick="return quick_delete_package_supplier(this)" class="btn btn-link delete_item icon" data-title="Xóa bản ghi này ?" title="">Xóa</a>
    						</td>';
				$r['html'] .= '</tr>';
			}}
			 
			$r['html'] .= '</tbody></table>';
			$r['html'] .= '</div>';
	
			$r['html'] .= '</div>';
			//
			$r['html'] .= '<div class="modal-footer">';
			$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
			$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
			$r['html'] .= '</div>';
			$_POST['action'] = 'quick-' . $_POST['action'];
			foreach ($_POST as $k=>$v){
				$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
			}
			///
			 
			$r['event'] = $_POST['action'];
			$r['existed'] = $existed;
			echo json_encode($r);exit;
			 
			break;
	case 'add-more-nationality-group-to-supplier':
		$r = array(); $r['html'] = '';
		
		$m = new \app\modules\admin\models\NationalityGroups();
		$type_id = isset($_POST['type_id']) ? $_POST['type_id'] : 2;
		$supplier_id = post('supplier_id',post('id',0));
		//
		$existed = post('existed');
		
		$l4 = $m->get_all_supplier_group($supplier_id,['not_in'=>$existed]) ;
		$r['html'] = '<div class="form-group">';
		$r['html'] .= '<div class="group-sm34 col-sm-12"><select name="f[child_id][]" multiple data-existed="'.$existed.'" data-role="chosen-load-season" class="form-control ajax-chosen-select-ajax" style="width:100%">';
		if(!empty($l4)){
			foreach ($l4 as $k4=>$v4){
	
				$r['html'] .= '<option value="'.$v4['id'].'">'.$v4['title'].' ('.$v4['count_local'] .' quốc gia)</option>';
	
			}
		}
		$r['html'] .= '</select></div>';
		$r['html'] .= '</div><p class="help-block italic ">*** Bạn có thể chọn giá trị có sẵn hoặc thêm mới ở ô nhập bên dưới. Quản lý danh sách nhóm <a class="btn-link bold f12e underline red" target="_blank" href="'.AdminMenu::get_menu_link('nationality_groups').'?supplier_id='.$supplier_id.'">tại đây</a></p><hr>';
		$r['html'] .= '<div class="">';
		 
		$r['html'] .= '<div class="group-sm34"><p>Thêm mới nhóm</p>';
		$r['html'] .= '<table class="table vmiddle table-hover table-bordered">';
		$r['html'] .= '<tbody class="">';
	 
		for($i=0; $i<1;$i++){
	
			$r['html'] .= '<tr>
    				<td class="pr"><input type="text" class="sui-input w100 form-control input-sm" value="" name="new['.$i.'][title]" placeholder="Tên nhóm"/></td>';
			$r['html'] .= '</tr>';
			 
			$r['html'] .= '<tr><td>';
			$r['html'] .= '<div class="form-group">
          <label class="col-sm-12 control-label aleft">Quốc gia trong nhóm</label>
          <div class="col-sm-12">
    
              <select name="new['.$i.'][local_id][]" multiple data-existed="'.$existed.'" data-role="chosen-load-country" class="form-control ajax-chosen-select-ajax" style="width:100%">
    	
    
    
          </select>
              		<label class="mgt15"><input data-action="get_local_not_in_group" data-id="'.$supplier_id.'" onchange="get_local_not_in_group(this)" type="checkbox" /> Các quốc gia chưa thuộc nhóm nào</label>
              		</div>
         </div>';
	
			$r['html'] .= '</td></tr>';
	
		}
		 
		$r['html'] .= '</tbody></table>';
		$r['html'] .= '</div>';
	
	
		$r['html'] .= '<div class="group-sm34"><p>Danh sách các nhóm đã thêm</p>';
		$r['html'] .= '<table class="table vmiddle table-hover table-bordered">';
		$r['html'] .= '<thead><tr>
    				<th>Tên nhóm</th>
    				<th class="coption"></th>
    				</tr></thead>';
		$r['html'] .= '<tbody class="">';

		$l = $m -> get_supplier_group($supplier_id);
		if(!empty($l)){
			foreach ($l as $k=>$v){
	
				$r['html'] .= '<tr>
    				<td class="pr"><a>'.$v['title'].' <i>('.$v['count_local'].' quốc gia)</i></a></td>
    				<td class="center">
    						<a target="_blank" href="'.AdminMenu::get_menu_link('nationality_groups').'?supplier_id='.$supplier_id.'" class="btn btn-link edit_item  icon">Sửa</a>
    						<a data-action="quick_delete_nationality_group_supplier" data-group_id="'.$v['id'].'" data-supplier_id="'.$supplier_id.'" onclick="return quick_delete_nationality_group_supplier(this)" class="btn btn-link delete_item icon" data-title="Xóa bản ghi này ?" title="">Xóa</a>
    						</td>';
				$r['html'] .= '</tr>';
			}}
			
			switch (post('controller_code')){
				case TYPE_ID_VECL: case TYPE_ID_GUIDES:
					$c  = (new Query())->from(Yii::$app->zii->getTablePrice(post('controller_code'),post('price_type',1)))->where([
						'supplier_id'=>$supplier_id,
						'nationality_id'=>0
					])->count(1);
					if($c>0){
					$r['html'] .= '<tr><td colspan="5"><p><b class="red ">Opp! Chúng tôi nhận thấy rằng có <span class="underline">'.number_format($c).'</span> đơn giá đã nhập cho đơn vị này nhưng chưa thuộc nhóm nào.</b></p>
				
					<p class="bold green">Bạn có muốn cập nhật vào nhóm [đầu tiên] trong danh sách nhóm của nhà cung cấp này không ?</p>
					<label><input name="update_quotation" type="checkbox"/> Cập nhật ngay</label>
							<p class="help-block italic red "><b class="underline">Lưu ý: </b> Khi cài đặt nhóm quốc tịch, các đơn giá (đã nhập trước đó) mà không thuộc 1 nhóm nào sẽ bị xóa.</p></td></tr>';
					}
					break;
			}
			 
			$r['html'] .= '</tbody></table>';
			//
			
			//
			$r['html'] .= '</div>';
	
			$r['html'] .= '</div>';
			//
			$r['html'] .= '<div class="modal-footer">';
			$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
			$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
			$r['html'] .= '</div>';
			$_POST['action'] = 'quick-' . $_POST['action'];
			foreach ($_POST as $k=>$v){
				$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
			}
			///
			 
			$r['event'] = $_POST['action'];
			$r['existed'] = $existed;
			echo json_encode($r);exit;
			 
			break;
			
	case 'quick-add-more-package-price-to-supplier':	
		$m = load_model('package_prices');
		$new = post('new',[]);
		$f = post('f');
		$f['child_id'] = isset($f['child_id']) ? $f['child_id']: [];
		if(!empty($new)){
			foreach ($new as $k=>$v){
				 
					if(trim($v['title']) != ""){						
						 		
						Yii::$app->db->createCommand()->insert($m->tableName(),[
								'title'=>$v['title'],
								'sid'=>__SID__,
								'supplier_id'=>post('id',0),
						
						])->execute();
						$new_id = Yii::$app->db->createCommand("select max(id) from ".$m->tableName())->queryScalar();
						$f['child_id'][] = $new_id;
						//
						
						//
					}
		
			}
		}
		if(!empty($f['child_id'])){
			foreach ($f['child_id'] as $c){
				Yii::$app->db->createCommand()->insert($m->tableToSupplier(),['package_id'=>$c,'supplier_id'=>post('id')])->execute();
			}
		}
		 
		$r['event'] = 'hide-modal';
		$r['callback']=true;
		$r['callback_function'] = 'reloadAutoPlayFunction();';
		echo json_encode($r);exit;
		break;
	case 'quick-add-more-season-to-supplier':
		$r = $existed = array();
		$m = new app\modules\admin\models\Seasons();
		$f = isset($_POST['f']) ? $_POST['f'] : array();
		//$existed = isset($_POST['existed']) ? $_POST['existed'] : array();
		//
		$type_id = isset($_POST['type_id']) ? $_POST['type_id'] : 2;
		 
		 
		$r['html'] = '';
		$id = $_POST['id']; $season_id = $_POST['season_id'];
		$new = isset($_POST['new']) ? $_POST['new'] : array();
	
	
		$i=0;
		if(!empty($new)){
			foreach ($new as $k=>$v){
				if(in_array($type_id,[3,4,5])){
					if(trim($v['from_time']) != "" && trim($v['to_time']) != ""){
						//$v['type_id'] = Zii::$db->getField($m->tableCategory(), 'type_id',['id'=>$season_id]);
						$v['type_id'] = (new Query())->select(['type_id'])->from($m->tableCategory())->where(['id'=>$season_id])->scalar();
					 
						Yii::$app->db->createCommand()->insert($m->tableWeekend(),[
								'title'=>$v['title'],
								'sid'=>__SID__,
								'from_date'=>$v['from_date'],
								'to_date'=>$v['to_date'],
								'from_time'=>$v['from_time'],
								'to_time'=>$v['to_time'],
								'parent_id'=>$season_id,'type_id'=>$v['type_id']
						])->execute();
						 
						$new_id = Yii::$app->db->createCommand("select max(id) from ".$m->tableWeekend())->queryScalar();
						if($new_id > 0){
							//$existed[] = $new_id;
							if(isset($f['child_id'])) $f['child_id'][] = $new_id;
							else $f['child_id'] = [$new_id];
							 
						}
					}
				}else{
					if(trim($v['title']) != ""){
					
						Yii::$app->db->createCommand()->insert($m->tableName(),[
								'title'=>$v['title'],
								'sid'=>__SID__,
								'from_date'=>ctime(['string'=>$v['from_date']]),
								'to_date'=>ctime(['string'=>$v['to_date']]),
								'parent_id'=>$season_id,'type_id'=>10
						])->execute();
						$new_id = Yii::$app->db->createCommand("select max(id) from ".$m->tableName())->queryScalar();
						if($new_id > 0){
							//$existed[] = $new_id;
							if(isset($f['child_id'])) $f['child_id'][] = $new_id;
							else $f['child_id'] = [$new_id];
							 
						}
					}
				}
	
			}
		}
		$i=0;
		if(isset($f['child_id']) && !empty($f['child_id'])){
			if(in_array($type_id,[3,4,5])){
				$l4 = $m->get_weekend(['in'=>$f['child_id'],'not_in'=>$existed]);
				if(!empty($l4['listItem'])){
					foreach ($l4['listItem'] as $k=>$v){
						$existed[] = $v['id'];
						$r['html'] .= '<tr class="tr-distance-id-'.$v['id'].'">';
						$r['html'] .= '<td class="center">'.$v['from_time'].' '.read_date($v['from_date']).'</td>';
						$r['html'] .= '<td class="center">'.$v['to_time'].' '.read_date($v['to_date']).'</td>';
						$r['html'] .= '<td><a>'.$v['title'].'</a><input type="hidden" value="'.$v['id'].'" name="seasons['.$season_id.'][list_child]['.$v['id'].'][id]"/></td>';
						$r['html'] .= '<td class="center"><i title="Xóa" data-name="delete_price_distance_id" onclick="removeTrItem(this);" data-target=".tr-distance-id-'.$v['id'].'" class="pointer glyphicon glyphicon-trash"></i></td>';
						$r['html'] .= '</tr>';
					}
				}
			}else{
				$l4 = $m->getList(['in'=>$f['child_id'],'not_in'=>$existed]);
				if(!empty($l4['listItem'])){
					foreach ($l4['listItem'] as $k=>$v){
						$existed[] = $v['id'];
						$r['html'] .= '<tr class="tr-distance-id-'.$v['id'].'">';
						$r['html'] .= '<td class="">'.date("d/m/Y",strtotime($v['from_date'])).'</td>';
						$r['html'] .= '<td class="center">'.date("d/m/Y",strtotime($v['to_date'])).'</td>';
						$r['html'] .= '<td><a>'.$v['title'].'</a><input type="hidden" value="'.$v['id'].'" name="seasons['.$season_id.'][list_child]['.$v['id'].'][id]"/></td>';
						$r['html'] .= '<td class="center"><i title="Xóa" data-name="delete_price_distance_id" onclick="removeTrItem(this);" data-target=".tr-distance-id-'.$v['id'].'" class="pointer glyphicon glyphicon-trash"></i></td>';
						$r['html'] .= '</tr>';
					}
				}
			}
		}
	
	
	
		$r['target'] = $_POST['target'];
		$r['event'] = $_POST['action'];
		$r['existed'] = $existed;
		echo json_encode($r);exit;
		break;
	case 'add-more-season-to-supplier':
		$r = array(); $r['html'] = '';
		$m = new app\modules\admin\models\Seasons();
		$type_id = isset($_POST['type_id']) ? $_POST['type_id'] : 2;
		///view($type_id);
		//
		$existed = post('existed');
		//
		$l4 = in_array($type_id,[3,4,5]) ? $m->get_weekend(['limit'=>100,'type_id'=>$type_id,'not_in'=>($existed != "" ? explode(',', $existed) : [])]) : $m->getList(['limit'=>100,'not_in'=>($existed != "" ? explode(',', $existed) : [])]);
		$r['html'] = '<div class="form-group">';
		$r['html'] .= '<div class="group-sm34 col-sm-12"><select name="f[child_id][]" multiple data-existed="'.$existed.'" data-role="chosen-load-season" class="form-control ajax-chosen-select-ajax" style="width:100%">';
		if(!empty($l4['listItem'])){
			foreach ($l4['listItem'] as $k4=>$v4){
				if(in_array($type_id,[3,4,5])){
					$r['html'] .= '<option value="'.$v4['id'].'">['.$v4['title'].'] '.$v4['from_time'] . ' ' . read_date($v4['from_date']). ' -> ' . $v4['to_time'] . ' ' . read_date($v4['to_date']) .'</option>';
				}else{
					$r['html'] .= '<option value="'.$v4['id'].'">'.$v4['title'].' ('.date("d/m/Y",strtotime($v4['from_date'])) .' - ' . date("d/m/Y",strtotime($v4['to_date'])) .')</option>';
				}
			}
		}
		$r['html'] .= '</select></div>';
		$r['html'] .= '</div><p class="help-block italic ">*** Bạn có thể chọn giá trị có sẵn hoặc thêm mới ở ô nhập bên dưới</p><hr>';
		$r['html'] .= '<div class="">';
		 
		$r['html'] .= '<div class="group-sm34">';
		$r['html'] .= '<table class="table vmiddle table-hover table-bordered">';
		$r['html'] .= '<tbody class="">';
	
		for($i=0; $i<3;$i++){
			if(in_array($type_id,[3,4,5])){
				$r['html'] .= '<tr>
    					<td><select class="form-control input-sm ajax-select2-no-search"  name="new['.$i.'][from_date]">';
				for($j = 0;$j<7;$j++){
					$r['html'] .= '<option value="'.$j.'">'.read_date($j).'</option>';
				}
				$r['html'] .= '</select></td>
    					<td><input type="text" class="sui-input form-control input-sm ajax-timepicker" value="" name="new['.$i.'][from_time]" placeholder="Thời gian bắt đầu"/></td>
    					<td><select class="form-control input-sm ajax-select2-no-search" name="new['.$i.'][to_date]">';
				for($j = 0;$j<7;$j++){
					$r['html'] .= '<option value="'.($j == 0 ? 7 : $j).'">'.read_date($j).'</option>';
				}
				$r['html'] .= '</select></td>
    					<td class="center "><input type="text" class="sui-input w100 form-control input-sm ajax-timepicker" value="" name="new['.$i.'][to_time]" placeholder="Thời gian kết thúc"/></td>
    					<td class=""><input type="text" class="sui-input w100 form-control input-sm" value="" name="new['.$i.'][title]" placeholder="Tiêu đề"/></td>';
				$r['html'] .= '</tr>';
			}else{
				$r['html'] .= '<tr>
    					<td class="pr"><input onblur="addrequired_input(this);" type="text" class="sui-input form-control input-sm ajax-datepicker" value="" name="new['.$i.'][from_date]" placeholder="Thời gian bắt đầu"/></td>
    					<td class="center pr"><input onblur="addrequired_input(this);" type="text" class="sui-input w100 form-control input-sm ajax-datepicker" value="" name="new['.$i.'][to_date]" placeholder="Thời gian kết thúc"/></td>
    					<td class="center "><input onblur="addrequired_input(this);" type="text" class="sui-input w100 form-control input-sm" value="" name="new['.$i.'][title]" placeholder="Tiêu đề"/> </td>';
				$r['html'] .= '</tr>';
			}
		}
		 
		$r['html'] .= '</tbody></table>';
		$r['html'] .= '</div>';
		$r['html'] .= '</div>';
		//
		$r['html'] .= '<div class="modal-footer">';
		$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
		$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$r['html'] .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		 
		$r['event'] = $_POST['action'];
		$r['existed'] = $existed;
		echo json_encode($r);exit;
		 
		break;
		
		
		
		
	case 'quick-add-more-season-category-to-supplier':
		$m =load_model('seasons');
		$f = post('f',[]);
		$new = isset($_POST['new']) ? $_POST['new'] : [];
		$supplier_id = post('supplier_id',post('id',0));
		$new_cate = post('new_cate',[]);
		$code = \app\modules\admin\models\Seasons::get_incurred_charge_type(post('type_id'))[0]['id'];
		if(!empty($new_cate)){
			foreach ($new_cate as $c){
				Yii::$app->db->createCommand()->insert($m->table_category_to_supplier(),[
						'season_id'=>$c,
						'supplier_id'=>$supplier_id,
						'price_type'=>$code
				])->execute();
			}
		}
		 
		if($f['title'] != ""){
			$season_category_id = Yii::$app->zii->insert($m->tableCategory(),array(
					'title'=>$f['title'],
					'code'=>'CUSTOMIZE',
					'type_id'=>$f['type_id'],
					'position'=>254,
					'sid'=>__SID__,
					'owner'=>$supplier_id
			));
			Yii::$app->db->createCommand()->insert($m->table_category_to_supplier(),[
					'season_id'=>$season_category_id,	
					'supplier_id'=>post('id'),
					'price_type'=>$code
			])->execute();
			if(!empty($new) && $season_category_id>0){
				foreach ($new as $k=>$v){
					switch ($f['type_id']){
						case SEASON_TYPE_TIME:
							if(trim($v['title']) != ""){
								$season_id = Yii::$app->zii->insert('weekend',[
										'title'=>$v['title'],
										'sid'=>__SID__,
										'from_date'=>$v['from_date'],
										'to_date'=>$v['to_date'],
										//'from_time'=>isset($v['from_time']) ? $v['from_time'] : '00:00:00',
										//'to_time'=>isset($v['to_time']) ? $v['to_time'] : '00:00:00',
										'part_time'=>isset($v['part_time']) ? $v['part_time'] : 0,
										//'parent_id'=>$season_category_id,
										'type_id'=>$f['type_id']
								]);
								Yii::$app->db->createCommand()->insert($m->tableToSuppliers(),[
										'season_id'=>$season_id,
										'parent_id'=>$season_category_id,
										'supplier_id'=>$supplier_id,
										'type_id'=>$f['type_id']])->execute();
							}
							break;
						case SEASON_TYPE_WEEKEND: case SEASON_TYPE_WEEKDAY: 
							if(trim($v['title']) != "" && trim($v['from_time']) != "" && trim($v['to_time']) != ""){
								$season_id = Yii::$app->zii->insert('weekend',[
										'title'=>$v['title'],
										'sid'=>__SID__,
										'from_date'=>$v['from_date'],
										'to_date'=>$v['to_date'],
										'from_time'=>isset($v['from_time']) ? $v['from_time'] : '00:00:00',
										'to_time'=>isset($v['to_time']) ? $v['to_time'] : '00:00:00',
										//'part_time'=>isset($v['part_time']) ? $v['part_time'] : 0,
										//'parent_id'=>$season_category_id,
										'type_id'=>$f['type_id']
								]);
								Yii::$app->db->createCommand()->insert($m->tableToSuppliers(),[
										'season_id'=>$season_id,
										'parent_id'=>$season_category_id,
										'supplier_id'=>$supplier_id,
										'type_id'=>$f['type_id']])->execute();
							}
							break;
						default :
							if($v['from_date'] != "" && $v['to_date'] != ""){
									
								$season_id = Yii::$app->zii->insert($m->tableName(),[
										//'parent_id'=>$season_category_id,
										'title'=>$v['title'],
										'type_id'=>$f['type_id'],
										'sid'=>__SID__,
										'from_date'=>ctime(['string'=>$v['from_date']]),
										'to_date'=>ctime(['string'=>$v['to_date'],'format'=>'Y-m-d']) . ' 23:59:59',
								]);
							
								Yii::$app->db->createCommand()->insert($m->tableToSuppliers(),[
										'season_id'=>$season_id,
										'parent_id'=>$season_category_id,
										'supplier_id'=>$supplier_id,
										'type_id'=>$f['type_id']])->execute();
							
							}
							break;
					}
					
				}
			}
		}
		
		if(post('update_quotation') == 'on'){
			$controller_code = post('controller_code',post('type_id'));
			switch ($controller_code){
				case TYPE_ID_VECL:
				$incurred_prices = \app\modules\admin\models\Customers::getCustomerSeasons($supplier_id);
				if(!empty($incurred_prices)){
					foreach ($incurred_prices as $k=>$v){
						if(!in_array($v['type_id'] ,[3,4,5])){
						Yii::$app->db->createCommand()->update(Yii::$app->zii->getTablePrice($controller_code,1),['season_id'=>$v['id']],[
								'supplier_id'=>$supplier_id,
								'season_id'=>0
						])->execute(); 
						Yii::$app->db->createCommand()->update(Yii::$app->zii->getTablePrice($controller_code,2),['season_id'=>$v['id']],[
								'supplier_id'=>$supplier_id,
								'season_id'=>0
						])->execute();
						
						break;
						}
					}
				}
				break;
			}
		}
	
		if(post('update_weekend') == 'on'){
			$controller_code = post('controller_code',post('type_id'));
			switch ($controller_code){
				case TYPE_ID_VECL:
				$incurred_prices = \app\modules\admin\models\Customers::getCustomerSeasons($supplier_id);
				if(!empty($incurred_prices)){
					foreach ($incurred_prices as $k=>$v){
						if(in_array($v['type_id'] ,[3,4])){
						Yii::$app->db->createCommand()->update(Yii::$app->zii->getTablePrice($controller_code,1),['weekend_id'=>$v['id']],[
								'supplier_id'=>$supplier_id,
								'weekend_id'=>0
						])->execute(); 
						Yii::$app->db->createCommand()->update(Yii::$app->zii->getTablePrice($controller_code,2),['weekend_id'=>$v['id']],[
								'supplier_id'=>$supplier_id,
								'weekend_id'=>0
						])->execute();
						break;
						}
					}
				}
				break;
			}
		}
		
		
		$r['event'] = 'hide-modal';
		$r['callback'] = true;
		$r['callback_function'] = 'reloadAutoPlayFunction();';
		//$r['existed'] = $existed;
		echo json_encode($r);exit;
		break;
		
		
	case 'add-more-season-category-to-supplier':
		$r = array(); $r['html'] = '';
		$m =load_model('seasons');
		$type_id = isset($_POST['type_id']) ? $_POST['type_id'] : 2;
		$supplier_id = post('supplier_id',0);
		$controller_code = post('controller_code',post('type_id'));
		
		//
		$existed = post('existed');
		//
		 
		//$r['html'] .= '</div><p class="help-block italic ">*** Bạn có thể chọn giá trị có sẵn hoặc thêm mới ở ô nhập bên dưới</p><hr>';
	
		$r['html'] .= '<div class="form-group"><div class="col-sm-12"><select name="new_cate[]" data-placeholder="Chọn các mùa có sẵn trên hệ thống" class="ajax-chosen-select-ajax" multiple>';
		
foreach (\app\modules\admin\models\Seasons::getAvailableSeasons($type_id,post('id',0)) as $k1=>$v1){
	$r['html'] .= '<option value="'.$v1['id'].'">'.uh($v1['title']).'</option>';
}
		
		$r['html'] .= '</select></div></div>';
		
		$r['html'] .= '<p class="help-block italic ">*** Bạn có thể chọn giá trị có sẵn hoặc thêm mới ở ô nhập bên dưới</p><hr>';
		
		$r['html'] .= '<div class="">
				<div class="option-list1 inline-block">
				<label class="pointer mgr15"><input class="input-rs-031719" onchange="change_form_add_supplier_season_category(this)" type="radio" value="2" name="f[type_id]" checked/> Khoảng thời gian</label>
				<label class="pointer mgr15"><input onchange="change_form_add_supplier_season_category(this)" type="radio" value="3" name="f[type_id]" /> Cuối tuần</label>
				<label class="pointer mgr15"><input onchange="change_form_add_supplier_season_category(this)" type="radio" value="4" name="f[type_id]" /> Ngày thường</label>
				<label class="pointer mgr15"><input onchange="change_form_add_supplier_season_category(this)" type="radio" value="5" name="f[type_id]" /> Buổi trong ngày</label>
				</div>
				';
		 
		$r['html'] .= '<div class="group-sm34">';
		$r['html'] .= '<table class="table vmiddle table-hover table-bordered">';
		$r['html'] .= '<tbody class="ajax-rs-0301">';
		 
		$r['html'] .= '</tbody></table>';
		$r['html'] .= '</div>';
		
		$r['html'] .= '<div class="group-sm34">';
		$r['html'] .= '<table class="table vmiddle table-hover table-bordered">';
		$r['html'] .= '<tbody class="s-rs-0301">';
		switch ($controller_code){
			case TYPE_ID_VECL:
				
				$c  = (new Query())->from(Yii::$app->zii->getTablePrice($controller_code,1))->where([
				'supplier_id'=>$supplier_id,
				'season_id'=>0
				])->count(1);
				$c1  = (new Query())->from(Yii::$app->zii->getTablePrice($controller_code,2))->where([
						'supplier_id'=>$supplier_id,
						'season_id'=>0
				])->count(1);
		$c = $c + $c1;
				if($c > 0){
					
					$r['html'] .= '<tr><td colspan="5"><p><b class="red ">Opp! Chúng tôi nhận thấy rằng có <span class="underline">'.number_format($c).'</span> đơn giá đã nhập cho đơn vị này nhưng chưa thuộc nhóm nào.</b></p>
		
					<p class="bold green">Bạn có muốn cập nhật vào nhóm [đầu tiên] trong danh sách nhóm của nhà cung cấp này không ?</p>
					<label><input name="update_quotation" type="checkbox"/> Cập nhật ngay</label>
							<p class="help-block italic red "><b class="underline">Lưu ý: </b> Khi cài đặt nhóm, các đơn giá (đã nhập trước đó) mà không thuộc 1 nhóm nào sẽ bị xóa.</p></td></tr>';
				}
				if(post('weekend') == 1){
					$c  = (new Query())->from(Yii::$app->zii->getTablePrice($controller_code,1))->where([
							'supplier_id'=>$supplier_id,
							'weekend_id'=>0
							
					])->count(1);
					$c  += (new Query())->from(Yii::$app->zii->getTablePrice($controller_code,2))->where([
							'supplier_id'=>$supplier_id,
							'weekend_id'=>0
								
					])->count(1);
					if($c > 0){
							
						$r['html'] .= '<tr><td colspan="5"><p><b class="red ">Opp! Chúng tôi nhận thấy rằng có <span class="underline">'.number_format($c).'</span> đơn giá đã nhập cho đơn vị này nhưng chưa thuộc <b class="green underline">nhóm ngày</b> nào.</b></p>
					
						<p class="bold green">Bạn có muốn cập nhật vào nhóm [đầu tiên] trong danh sách <b class="green underline">nhóm ngày</b> của nhà cung cấp này không ?</p>
						<label><input name="update_weekend" type="checkbox"/> Cập nhật ngay</label>
								<p class="help-block italic red "><b class="underline">Lưu ý: </b> Khi cài đặt nhóm, các đơn giá (đã nhập trước đó) mà không thuộc 1 nhóm nào sẽ bị xóa.</p></td></tr>';
					}
				}
				break;
		}
			
		$r['html'] .= '</tbody></table>';
		$r['html'] .= '</div>';
		
		$r['html'] .= '</div>';
		//
		$r['html'] .= '<div class="modal-footer">';
		$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$r['html'] .= '</div>';
		$_POST['action'] = 'quick-' . $_POST['action'];
		foreach ($_POST as $k=>$v){
			$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		///
		 
		$r['event'] = $_POST['action'];
		$r['existed'] = $existed;
		$r['callback'] = true;
		$r['callback_function'] = 'change_form_add_supplier_season_category(jQuery(\'.input-rs-031719\'))';
		echo json_encode($r);exit;
		 
		break;
	case 'quick-add-more-room-to-hotel':
		$r = $existed = array();	
		$m = new \app\modules\admin\models\Hotels();
		$f = post('f',[]);
		$child_id = [];
		$supplier_id = post('supplier_id',post('id',0));
		//
	
		if(!isset($c['child_id'])) $c['child_id'] = [];
		if(!empty($f)){
			foreach ($f as $k=>$v){
				if(cprice($v['quantity'])>0){
					$c['child_id'][] = $v['id'];
					$child_id[] = $v['id']; 
					//foreach ($child_id as $c){
					if((new Query())->from('rooms_to_hotel')->where([
							'parent_id'=>$supplier_id,
							'room_id'=>$v['id'],
					])->count(1) == 0){
						Yii::$app->db->createCommand()->insert('rooms_to_hotel',[
								'parent_id'=>$supplier_id,
								'room_id'=>$v['id'],
								'quantity'=>cprice($v['quantity'])
						])->execute();
					}
					//}
				}
			}
		}
		//
		 
		$r['html'] = '';
		 
		$new = post('new',[]);
		if(!empty($new)){
			foreach ($new as $k=>$v){
				if(trim($v['title']) != "" && (new Query())->from($m->tableRoomCategory())->where(array('sid'=>__SID__ ,'title'=>trim($v['title'])))->count(1) == 0){
					//	
					 
					Yii::$app->db->createCommand()->insert($m->tableRoomCategory(),[
							'title'=>$v['title'],
							'sid'=>__SID__,
							'seats'=>$v['seats']
					])->execute();
					 
					$new_id = Yii::$app->db->createCommand("select max(id) from ".$m->tableRoomCategory())->queryScalar();
					if($new_id>0){
						$c['child_id'][] = $new_id;
						$f[$new_id]['quantity'] = $v['quantity'];
						
						if((new Query())->from('rooms_to_hotel')->where([
								'parent_id'=>$supplier_id,
								'room_id'=>$new_id,
						])->count(1) == 0){
							Yii::$app->db->createCommand()->insert('rooms_to_hotel',[
									'parent_id'=>$supplier_id,
									'room_id'=>$new_id,
									'quantity'=>cprice($v['quantity'])
							])->execute();
						}
					}
				}
			}
		}
		 
		echo json_encode([
				'event'=>'hide-modal',
				'callback'=>true,
				'callback_function'=>'reloadAutoPlayFunction();',
		]);exit;
		break;
	case 'quick_find_room_category':
		$m = new \app\modules\admin\models\RoomsCategorys();
		$l = $m->getList([
				'limit'=>1000,
				'filter_text'=>post('val'),
		]);
		$r = [];
		if(!empty($l['listItem'])){
			foreach ($l['listItem'] as $v){
				$r[] = $v['id'];
			}
		}
		echo json_encode(['state'=>true,'list'=>$r]); exit;
		break;
	case 'add-more-room-to-hotel':
		$r = array(); $r['html'] = '';
		//		
		$supplier_id = post('supplier_id',post('id',0)); 
		$m = new \app\modules\admin\models\RoomsCategorys(); 
		$existed = post('existed');
		$l4 = $m->getList([
				'limit'=>1000,'count'=>false,
				'order_by'=>'a.title, a.seats',
				'not_in'=>strlen($existed) > 0 ? explode(',', $existed) : [],
		]);
		//
		$l4 = \app\modules\admin\models\RoomsCategorys::getAvailabledRooms([
				'supplier_id'=>$supplier_id,
				'order_by'=>'a.title, a.seats',
				'type_id'=>-1 //TYPE_CODE_ROOM_HOTEL
		]);
		
		 
		 
		$r['html'] .= '<div class="fl100" data-height="auto">';
		$r['html'] .= '<input type="text" onkeypress="if (event.keyCode==13){return false;};" onkeyup="if (event.keyCode==13){return false;};return quick_find_room_category(this);" placeholder="Tìm kiếm nhanh" value="" class="form-control input-sm keyup_event mgb5"/>
				<table class="table vmiddle table-hover table-bordered mgb-1" ><thead><tr>';
		$r['html'] .= '<th rowspan="2">Tiêu đề</th>';
		$r['html'] .= '<th rowspan="2" class="center w100p">Số chỗ</th>';
		$r['html'] .= '<th rowspan="2" class="center w100p">Số lượng</th>';
			
		$r['html'] .= '</tr></thead></table>';
		$r['html'] .= '<div class="group-sm34 div-slim-scroll" data-height="auto">';
	
		$r['html'] .= '<table class="table vmiddle table-hover table-bordered"></thead><tbody class="">';
		//$r['html'] .= '<tr>
    	//				<td><input type="text" onkeypress="if (event.keyCode==13){return false;};" onkeyup="if (event.keyCode==13){return false;};return quick_find_room_category(this);" placeholder="Tìm kiếm nhanh" value="" class="form-control input-sm keyup_event"/></td>
    	//				<td class="w100p"></td><td class="w100p"></td>';
		//$r['html'] .= '</tr>';
		if(!empty($l4)){
			foreach ($l4 as $k=>$v){
				$r['html'] .= '<tr class=" tr_item tr_item_'.$v['id'].'">
    					<td>'.$v['title'].'</td>
    							<td class="center w100p">'.$v['seats'].'</td>
    					<td class="w100p"><input type="text" class="sui-input w100 form-control input-sm ajax-number-format center" value="" name="f['.$v['id'].'][quantity]" placeholder=""/><input type="hidden" value="'.$v['id'].'" name="f['.$v['id'].'][id]"/> </td>';
				$r['html'] .= '</tr>';
			}}
			 
			$r['html'] .= '</tbody></table>';
			$r['html'] .= '</div>';
			$r['html'] .= '</div>';
			//
			$r['html'] .= '<div class="fl100"><p class="help-block">*** Nếu chưa có trong CSDL bạn có thể thêm nhanh tại đây.</p>';
			$r['html'] .= '<div class="group-sm34">';
	
			$r['html'] .= '<table class="table vmiddle table-hover table-bordered">';
			//$r['html'] .= '<th rowspan="2">Tiêu đề</th>';
			//$r['html'] .= '<th rowspan="2">Số chỗ</th>';
			//$r['html'] .= '<th rowspan="2" class="center w100p">Số lượng</th>';
			 
			$r['html'] .= '<tbody class="">';
			 
			for($i=0; $i<3;$i++){
				$r['html'] .= '<tr>
    					<td><input type="text" class="sui-input w100 form-control input-sm" value="" name="new['.$i.'][title]" placeholder="Tiêu đề"/></td>
    					<td class="center w100p"><input type="text" class="sui-input w100 form-control input-sm ajax-number-format center" value="" name="new['.$i.'][seats]" placeholder="Số chỗ"/></td>
    					<td class="center w100p"><input type="text" class="sui-input w100 form-control input-sm ajax-number-format center" value="" name="new['.$i.'][quantity]" placeholder="Số lượng"/> </td>';
				$r['html'] .= '</tr>';
			}
	
			$r['html'] .= '</tbody></table>';
			$r['html'] .= '</div>';
			$r['html'] .= '</div>';
			//
			$r['html'] .= '<div class="modal-footer">';
			$r['html'] .= '<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> Chọn</button>';
			$r['html'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
			$r['html'] .= '</div>';
			$_POST['action'] = 'quick-' . $_POST['action'];
			foreach ($_POST as $k=>$v){
				$r['html'] .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
			}
			///
	
			$r['event'] = $_POST['action'];
			$r['existed'] = $existed;
			$r['callback'] = true;
			$r['callback_function'] = 'loadScrollDiv(); ';
			echo json_encode($r);exit;
	
			break;
	 
	 
	case 'set_quantity_currency':
		$f = isset($_POST['f']) ? $_POST['f'] : array();
		$existed_id = isset($_POST['existed_id']) ? $_POST['existed_id'] : '';
		$existed_id = $existed_id != "" ? explode(',', $existed_id) : array();
		//$count = post('count',0);
		$index = post('index') >0 ? post('index') : 0;
		$html = '';
		if(!empty($f)){
			foreach ($f as $k=>$v){
				if(isset($v['is_active']) && $v['is_active'] == 'on'){
					//$index++;
					$existed_id[] = $v['id'];
					$html .= '<tr><td class="center">'.($index+1).'<input type="hidden" name="f[currency][list]['.($index).'][id]" value="'.$v['id'].'" />
    									<input type="hidden" name="f[currency][list]['.($index).'][id]" value="'.$v['id'].'" />
    									<input type="hidden" name="f[currency][list]['.($index).'][title]" value="'.$v['title'].'" />
    									<input type="hidden" name="f[currency][list]['.($index).'][code]" value="'.$v['code'].'" />
    									<input type="hidden" name="f[currency][list]['.($index).'][decimal_number]" value="'.$v['decimal_number'].'" />
										<input type="hidden" name="f[currency][list]['.($index).'][symbol]" value="'.$v['symbol'].'" />
    									</td>';
					$html .= '<td>'.$v['title'].'</td>';
					$html .= '<td class="center">'.$v['code'].'</td>';
					$html .= '<td class="center">'.$v['symbol'].'</td>';
					$html .= '<td class="center"><select class="form-control input-sm select2 " data-search="hidden" name="f[currency][list]['.$index.'][display]">
          <option value="1">Hiển thị sau số tiền (10,000đ)</option>
          <option value="-1">Hiển thị trước số tiền ($10,000)</option>
          </select></td>';
					$html .= '<td class="center"><select class="form-control input-sm select2 " data-search="hidden" name="f[currency][list]['.$index.'][display_type]">  
          <option value="1">Hiển thị mã quốc tế ('.$v['code'].')</option>
          <option value="2">Hiển thị symbol ('.$v['symbol'].')</option>
          </select></td>';
					$html .= '<td class="center"><input onchange="setDefaultCurrency(this)" '.($index++ == 0 ? 'checked' : '').' type="radio" name="f[currency][default]" value="'.$v['id'].'"/></td>';
					$html .= '<td class="center"><i class="glyphicon glyphicon-trash pointer" onclick="removeTrItem(this)"></i></td>';
					$html .= '</tr>';
				}
			}
		}
		echo json_encode(array('event'=>post('action'),'html'=>$html,'index'=>$index,'target_class'=>'ajax-html-result-before-list-vehicles','existed_id'=>implode(',',  $existed_id)));
		exit;
		break;
	case 'load_all_currency':
			
		$index = post('index') >0 ? post('index') : 0;
		$html = '<table class="table table-hover table-bordered vmiddle table-striped"> <thead>';
		$html .= '<tr> <th rowspan="2">#</th> <th rowspan="2">Loại tiền tệ</th>
		<th rowspan="2" style="min-width:150px">Mã quốc tế</th>
	
		<th class="center mw100p">Chọn</th>
		 </tr>
		</thead> <tbody>';
		$m = new app\modules\admin\models\Currency();
		$l = $m->getList(array(
				'limit'=>1000,
				//'maker_id'=>$id,
				//'type_id'=>$_POST['type_id'],
				'not_in'=>post('existed',[])
		));
		if(!empty($l['listItem'])){
			foreach ($l['listItem'] as $k=>$v){
				$index += $k;
				$html .= '<tr><td>'.($k+1).'</td>';
				$html .= '<td>'.$v['title'].'</td>';
				$html .= '<td>'.$v['code'].'</td>';
				$html .= '<td class="center"><input type="checkbox" name="f['.$index.'][is_active]" />
    								<input type="hidden" name="f['.$index.'][id]" value="'.$v['id'].'"/>
    								<input type="hidden" name="f['.$index.'][title]" value="'.$v['title'].'"/>
    								<input type="hidden" name="f['.$index.'][code]" value="'.$v['code'].'"/>
    								<input type="hidden" name="f['.$index.'][decimal_number]" value="'.$v['decimal_number'].'"/>
    								<input type="hidden" name="f['.$index.'][symbol]" value="'.$v['symbol'].'"/>
    								</td>';
				$html .= '</tr>';
			}
		}
		$html .= '</tbody></table>';
		 
		echo $html;
		exit;
		break;
	case 'set_default_ftp_server':
		$id = $_POST['id']; $val = $_POST['val'];
		Yii::$app->db->createCommand()->update('{{%server_config}}',array('is_active'=>0),array('sid'=>__SID__))->execute();
		Yii::$app->db->createCommand()->update('server_config',array('is_active'=>$val),array('id'=>$id,'sid'=>__SID__))->execute();
		//echo cjson($_POST);
		exit;
		break;
	case 'quick_update_seo':
		$id = post('id'); $table = $_POST['table'];$biz = post('biz');
		
		Siteconfigs::updateBizrule($table == 1 ? Content::tableName() : Menu::tableName(),['id'=>$id,'sid'=>__SID__],$biz) ;
		echo json_encode(['event'=>'quick_update_seo','target'=>ADMIN_ADDRESS,'delay'=>0]);
		exit;
		break;
	case 'parseSeoUrl':
		$url = $_POST['val'];
		$u = (parse_url($url));
		$url = '';
		$path = explode('/', $u['path']);
		if(!empty($path)){
			$path = array_reverse($path);
			foreach ($path as $p){
				if($p != ""){
					$url = $p; break;
				}
			}
			$m = new app\modules\admin\models\Slugs();
			$item= $m->getContentItem($url);
			echo json_encode(array('state'=>!empty($item),'item'=>$item));
		}
		exit();
		break;
	case 'changeFilterCode':
		$val = $_POST['val'];		
		$v = (new Query())->select('code')->from(['{{%filters}}'])->where(['id'=>$val])->one();
		if(!empty($v)){
			echo json_encode(array('code'=>$v['code'],'state'=>true, ));
		}else{
			echo json_encode(array('code'=>'normal','state'=>false ));
		}
		exit;
		break;
	case 'addNewTab':
		$tab = $_POST['tab'];
		$c_type = $_POST['c_type'];
		$role = $_POST['role'];
		$id = $_POST['id'];
	
	
		$html = '<div role="tabpanel" class="tab-pane" id="'.$tab.'"><div class="p-content"><div class="row"><div class="col-sm-12"><div class="form-group"><label class="col-sm-12 control-label">Tiêu đề</label><div class="col-sm-12"><input data-id="0" type="text" name="tab['.$role.'][title]" class="form-control" id="inputTitleTab'.$role.'" placeholder="Title" value="Tab '.($role + 1).'" /><input type="hidden" name="tab['.$role.'][id]" class="form-control" value="0" />   </div> </div>';
		if($c_type === true || $c_type == 'true'){
			$lc = app\modules\admin\models\Content::getTabCategorys();
			$html .= '<div class="form-group '.(count($lc) < 2 ? 'hide' : '').'"><label class="col-sm-12 control-label">Kiểu form</label><div class="col-sm-12">';
			 
			$html .= '<select data-id="'.$id.'" data-role="'.($role).'" onchange="changeFormLessionType(this);" name="tab['.($role).'][type]" class="form-control input-sm">';
			if(!empty($lc)){
				foreach($lc as $kx=>$vx){
					$html .= '<option '.('text' == $vx['type'] ? 'selected' : '').' value="'.$vx['type'].'">'.$vx['name'].'</option>';
				}
			}
			$html .= '</select>';
			$html .= '</div></div>';
		}
		$html .= '</div><div class="col-sm-12"><div class="form-group"><div class="col-sm-12 ajax_result_form_change'.$role.'"><textarea data-id="0" name="tab_biz['.$role.'][text]" class="form-control" id="xckc_'.$tab.'"  ></textarea>  </div> </div></div></div></div></div>';
		echo $html;
		exit;
		break;
	case 'selectDeparturePlace':
		$tour_type = $_POST['val'];
		$label = $tour_type == 1 ? getTextTranslate(21) : getTextTranslate(22);
		$html = $html1 = '';
		$m = new app\modules\admin\models\Content(); 
		$l = $m->getDeparturePlace(array(
				'is_destination'=>1,'type'=>$tour_type
		));
	
		if(!empty($l)){
			$html = '<optgroup label="'.$label.'">';
			foreach($l as $k1=>$v1){
				$html .= '<option  value="'.$v1['id'].'">+&nbsp;'. $v1['name'].'</option> ';
			}
			$html .= '</optgroup>';
		}
		if($tour_type == 2){
			$l = $m->getDeparturePlace(array(
					'is_start1'=>1,//'type'=>$tour_type
			));
		}else {
			$l = $m->getDeparturePlace(array(
					'is_start'=>1,//'type'=>$tour_type
			));
		}
			
		if(!empty($l)){
			//$html1 = '<optgroup label="'.$label.'">';
			foreach($l as $k1=>$v1){
				$html1 .= '<option  value="'.$v1['id'].'">+&nbsp;'. $v1['name'].'</option> ';
			}
			//$html1 .= '</optgroup>';
		}
	
			
			
		echo json_encode(array('s'=>$html1,'d'=>$html));
		//echo $html;
		exit;
		break;
	case 'checkCustomercode':
		$id = post('id');  $field = post('field');$val = post('val');
		$m = new app\modules\admin\models\Customers();
		$v = array(
				'name'=>'',
				'phone'=>'',
				'address'=>''
		);
		$ckc = $state = true;
		if($field == 'email'){
			$ckc = validateEmail($val);
			//view($ckc);
		}
		if($ckc){
			  
			$v = (new Query())->from(['a'=>$m->tableName()])->where(["a.$field"=>$val,'a.sid'=>__SID__])->andWhere(['not in','a.id',$id])->one();
			if(!empty($v)){
				$state = true;
			}else{
				$state = false;
			}
		}
		echo json_encode( array('state'=>$state, 'data'=> $v)); exit;
		break;
		
	case 'checkExistedAuthItem':
		$id = post('id');  $field = post('field');$val = post('val');
		 
		$ckc = $state = true;
	  
		$v = (new Query())->from(['a'=>'user_groups'])->where(["a.name"=>$val,'sid'=>__SID__])->andWhere(['not in','a.id',$id])->one();
		if(!empty($v)){
			$state = true;
		}else{
			$state = false;
		}
		 
		echo json_encode( array('state'=>$state, 'data'=> $v)); exit;
		break;	
	case 'quick_update_default_language':
		$f = isset($_POST['f']) ? $_POST['f'] : DEFAULT_LANG;
		$language = app\modules\admin\models\AdLanguage::getList();		
		if(!empty($language)){
			foreach ($language as $k=>$x){
				if($x['code'] == $f){
					$language[$k]['default'] = 1;
				}else{
					$language[$k]['default'] = 0;
				}
			}
		}
		 
		unset(Yii::$app->session['config']['language']);
		Siteconfigs::updateData('LANGUAGE', $language);
		//\ZAP\Zii::$db->update('site_configs',array('bizrule'=>cjson($cfg)),array('code'=>'LANGUAGE','sid'=>__SID__));
		echo json_encode(array('event'=>'edit_user_success','delay'=>0));
		exit;
		break;
	case 'checkOldPassword':
		$userPassword = $_POST['val'];
		 
		$t = Yii::$app->user->validatePassword($userPassword);
		 
		echo json_encode(array('state'=>$t,'delay'=>0));
		exit;
		break;
	 
	case 'quick_update_user':
		$f = isset($_POST['f']) ? $_POST['f'] : array();
		$biz = isset($_POST['biz']) ? $_POST['biz'] : array();
		//$m = $this->loadModel('users');
		if(!empty($biz)){
			Yii::$app->db->createCommand()->update('{{%users}}',['bizrule'=>json_encode($biz)],['id'=>Yii::$app->user->id])->execute();
			if(isset($biz['avatar'])){
				$_SESSION['config']['adLogin']['avatar'] = $biz['avatar'];
			}
		}
		if(!empty($f))	Yii::$app->db->createCommand()->update('{{%users}}',$f,['id'=>Yii::$app->user->id])->execute();
		echo json_encode(array('event'=>'edit_user_success','delay'=>0));exit;
		break;
	case 'ajax_uploads':
		 
		if (isset($_FILES['myfile'])) {
			$fileName = $_FILES['myfile']['name'];
			$fileType = $_FILES['myfile']['type'];
			$fileError = $_FILES['myfile']['error'];
			$fileStatus = array(
					'status' => 0,
					'message' => ''
			);
	
			$fType = explode('.',$fileName);$fType = strtolower($fType[count($fType)-1]);
			$file_extensions = file_extension_upload($fType);
			if ($fileError== 1) { //Lỗi vượt dung lượng
				$fileStatus['message'] = 'Dung lượng quá giới hạn cho phép';
			} elseif (!in_array(strtolower($fType), $file_extensions)) { //Kiểm tra định dạng file
				$fileStatus['message'] = 'Không cho phép định dạng '.$fileType;
			} else { //Không có lỗi nào
				//move_uploaded_file($_FILES['myfile']['tmp_name'], 'uploads/'.$fileName);
				$fx = explode('/', $fileType);
				$file = Yii::file()->upload_files($_FILES['myfile'],array('rename'=>false));
				$fileStatus['status'] = 1;
				$fileStatus['message'] = "Bạn đã upload $fileName thành công";
				$fileStatus['image'] = $file;
			}
			echo json_encode($fileStatus);
			exit();
		}
		break;
	case 'quick_edit_field': // Chưa sửa hết
		$f = $_POST['f'];
		if(isset($f['table']) && $f['id']){
			$table = $f['table'];
			$id = $f['id'];
			if(isset($f['_target'])){
				$target = $f['_target'];
				unset($f['_target']);
			}else{
				$target = '';
			}
			$con = array('id'=>$id);
	
			if (isset($f['sid'])){				
				array_merge($con,array('sid'=>$f['sid']));
				unset($f['sid']);
			}
			unset($f['id']); unset($f['table']);
			
			Yii::$app->db->createCommand()->update($table,$f,$con)->execute();
			
			echo json_encode(array('event'=>'quick_edit_field','target'=>$target,'title'=>$f['title']));
		}
		exit;
		break;
	case 'setdefaultTemplete':
		$id = post('id');
		$lang = post('lang');
		Yii::$app->db->createCommand()->update('{{%temp_to_shop}}',['state'=>2],['sid'=>__SID__,'lang'=>$lang])->execute();
		Yii::$app->db->createCommand()->update('{{%temp_to_shop}}',['state'=>1],['temp_id'=>$id, 'sid'=>__SID__,'lang'=>$lang])->execute();
		 
		break;
	case 'checkDomain':
		$val = post('val'); $id = post('id');
		$msg = 'Có thể sử dụng domain này'; $state = true;
		if(!validate_domain($val)){
			$msg = 'Domain không hợp lệ';
			$state = false;
		}else{
			if((new Query())->from('{{%domain_pointer}}')->where(['domain'=>$val])->andWhere(['not in','id',$id])->count(1) > 0){
				$msg = 'Domain <b>'.$val.'</b> đã được sử dụng';$state = false;
			}
		}
		echo json_encode(array('domain'=>$val,'state'=>$state,'msg'=>$msg));
		exit;
		break;
	case 'set_main_domain':
		$val = post('val');
		view($_POST);
		Yii::$app->db->createCommand()->update('{{%domain_pointer}}',['is_default'=>2],['sid'=>__SID__])->execute();
		Yii::$app->db->createCommand()->update('{{%domain_pointer}}',['is_default'=>1],['id'=>$val, 'sid'=>__SID__])->execute();
		echo json_encode(array('state'=>true));
		exit;
		break;
	case 'templete_load_child':
		$id = post('id');
		$m = new app\modules\admin\models\Templete;
		$l = $m->getList(array('limit'=>10000,'parent_id'=>$id));
		$html = '';
		if(!empty($l['listItem'])){
			foreach($l['listItem'] as $k1=>$v1){
				$html .= '<option value="'.$v1['id'].'">['.$v1['name'].'] '.uh($v1['title']).'</option>';
			}
		}
		echo $html;
		exit;
		break;
	case 'checkUserExisteds':
		$val = $_POST['value']; $field = $_POST['field']; $id = $_POST['id'];
		$ckc = true;
		switch ($field){
			case 'email':
				$ckc = validateEmail($val);
				break;
		}
		$sql = "select count(1) from users as a where a.$field='$val' and a.id not in($id) and a.sid=".__SID__;
		echo json_encode(array('state'=> $ckc ? Yii::$app->db->createCommand($sql)->queryScalar() : 1 ));
		exit;
		break;	
	case 'quick_update_user_password':
		if(!Yii::$app->user->isGuest){
			$f = isset($_POST['f']) ? $_POST['f'] : array();
			$f['password_hash'] = Yii::$app->security->generatePasswordHash($f['password_hash']);
			$f['updated_at']= time(); $f['last_modify'] = date("Y-m-d H:i:s");
			if(!empty($f))	Yii::$app->db->createCommand()->update('{{%users}}',$f,['id'=>Yii::$app->user->id])->execute();
			echo json_encode(array('event'=>'edit_user_success','delay'=>0));exit;
		}else{
			$f = post('f');
			$token = isset($f['password_reset_token']) ? $f['password_reset_token'] : '';
			$f['last_modify'] = date("Y-m-d H:i:s");
			$f['updated_at']=time();
			$f['password_hash'] = Yii::$app->security->generatePasswordHash($f['password_hash']);
			$f['password_reset_token'] = '';
			$user_type = 'users';
				
			if(isset($f['user_type'])){
				$user_type = $f['user_type'] ;
				unset($f['user_type']);
			}
			$link = $user_type == 'members' ? SITE_ADDRESS . '/members' : ADMIN_ADDRESS;
			$m = new app\modules\admin\models\Users();
			if($m->validate_resetpassword($token)){
				Yii::$app->db->createCommand()->update($m->tableName(),$f,array('password_reset_token'=>$token,'sid'=>__SID__))->execute();
			}
			//elseif(MEMBER_LOGIN_ID > 0){
			//	\ZAP\Zii::$db->update($user_type,$f,array('id'=>MEMBER_LOGIN_ID));
			//}
			echo json_encode(array('modal'=>true,'modal_content'=>'Thành công !', 'event'=>post('afterAction'),'target'=>$link,'delay'=>3000));
			exit;
		}
			break;
	case 'forgot':
		$f = post('f');
		$user_type = isset($f['user_type']) ? $f['user_type'] : 'users';
		$m = new app\modules\admin\models\Users();
		$u = $user_type == 'members' ?  $m->findMember($f['email']) : $m->getItem($f['email']);
			
		if(!empty($u)){
			$password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
			if($user_type == 'members'){
				$link = SITE_ADDRESS . '/members/forgot?action=reset&password_reset_token='.$password_reset_token;
			}else $link = yii\helpers\Url::to([DS.Yii::$app->controller->module->id.DS.'forgot','action'=>'reset','password_reset_token'=>$password_reset_token],true);
			$t = true;
			$search = array(
					'{LOGO}',
					'{DOMAIN}',
					'{USER}',
					'{LINK}',
						
			);
			$replace = array(
					'',
					__DOMAIN__,
					$f['email'],
					$link,
		
			);
		
			$text = Yii::$app->getTextRespon(array('code'=>'RP_FORGOT', 'show'=>false));
			$fx = Yii::$app->getConfigs('CONTACTS');
			$form = str_replace($search, $replace, uh($text['value']));
			Yii::$app->sendEmail(array(
					'subject'=>str_replace($search, $replace, $text['title'])  ,
					'body'=>$form,
					'from'=>$fx['email'],
					'fromName'=>$fx['short_name'],
					'replyTo'=>$fx['email'],
					'replyToName'=>$fx['short_name'],
					'to'=>$f['email'],'toName'=>$u['lname'] .' ' . $u['fname']
			));
			Yii::$app->db->createCommand()->update($user_type,['password_reset_token'=>$password_reset_token],['id'=>$u['id'],'email'=>$f['email'],'sid'=>__SID__])->execute();
			//view($a);
		}else $t = false;
		echo json_encode(array('event'=>'forgot','email'=>$f['email'], 'state'=>$t,'delay'=>0));exit;
		break;
	case 'get_decimal_number':
		$id = post('id',0);		
		$m = new \app\modules\admin\models\Currency(); 
		$item = $m->getItem($id);
		$d = 2;
		if(!empty($item)){
				$d = $item['decimal_number'];
		}
		echo $d;
		exit;
		break;
}

 