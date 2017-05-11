<?php 
use yii\helpers\Url;
use frontend\assets\AppAsset;
use yii\db\Query;
use yii\base\View;
AppAsset::register($this);
switch (getParam('action')){
	case 'execute_cronjobs':
		// Thong bao het han
		\common\models\Cronjobs::setExpiredShopsNotification();
		\common\models\Cronjobs::executeAllTodayJobs();
		\common\models\Cronjobs::clearJobExecuted();
		//
		exit;
		break;
	case 'load_taskscheduler':
		foreach (\app\modules\admin\models\TaskScheduler::getTodayTask() as $k=>$v){
			$state = 1;
			switch ($v['code']) {
				case 'ACTIVE_ARTICLE':
					Yii::$app->db->createCommand()->update('articles',['is_active'=>1],['id'=>$v['item_id'],'sid'=>__SID__])->execute();
					break;
				case 'DEACTIVE_ARTICLE':
					Yii::$app->db->createCommand()->update('articles',array('is_active'=>0),array('id'=>$v['item_id'],'sid'=>__SID__))->execute();
					break;
				case 'UPDATE_ARTICLE_PRICE':
					if(isset($v['price2'])){
						Yii::$app->db->createCommand()->update('articles',array('price2'=>$v['price2']),array('id'=>$v['item_id'],'sid'=>__SID__))->execute();
					}
					if(isset($v['price3'])){
						Yii::$app->db->createCommand()->update('articles',array('price3'=>$v['price3']),array('id'=>$v['item_id'],'sid'=>__SID__))->execute();
					} 
					break;
				case 'UPDATE_EXCHANGE_RATES':
					$state = -1;
					\app\modules\admin\models\TaskScheduler::update_exchange_rates();
					$sql = "update task_scheduler set time=DATE_ADD(now(), INTERVAL 1 HOUR) where code='".$v['code']."' and sid=".__SID__;
					Yii::$app->db->createCommand()->update('task_scheduler',[
							'time'=>new yii\db\Expression('DATE_ADD(now(), INTERVAL 1 HOUR)')
					],[
							'code'=>$v['code'],
							'sid'=>__SID__
					])->execute();
					//Yii::$app->db->createCommand($sql)->execute();
					break;
				default:
						
					break;
			}
			if($state !== -1) Yii::$app->db->createCommand()->update('task_scheduler',array('state'=>$state),array('item_id'=>$v['item_id'],'sid'=>__SID__))->execute();
		}
		//echo json_encode($task);
		//var_dump(get_exchangerates());
		break;
}
switch (post('action')){
	case 'loadChildsProvinces':
		$html = '';
		$parent_id = post('parent_id',-1);
		if(!is_numeric($parent_id)) $parent_id = -1;
		$selected = post('selected',0);
		$l = (new Query())->from('local')->where(['parent_id'=>$parent_id])->orderBy(['lft'=>SORT_ASC])->all();
		// 
		$local_id = 0;
		if(post('level') == 2){
			$html .= '<option></option>';
			$local_id = $parent_id;
		}
		//
		if(!empty($l)){
		foreach ($l as $k=>$v){
			$html .= '<option '.($selected == $v['id'] ? 'selected' : '').' value="'.$v['id'].'">'.showLocalName(uh($v['title']),$v['type_id']).'</option>';
			if($k==0 && $local_id == 0){
				$local_id = $v['id'];
			}
		}}else{
			$local_id = $parent_id;
		}
		echo json_encode([
			'html'=>$html,
			'local_id'=>$local_id,
			'selected'=>$selected	
		]+$_POST);
		exit;
		break;
	case 'set_subcribes':
		$f = post('f',[]);
		$f['email'] = strtolower($f['email']);
		if(!empty($f)){
			if((new Query())->from(['{{%emails_subscribes}}'])->where(['email'=>$f['email'],'sid'=>__SID__])->count(1) == 0){
				$f['sid'] = __SID__;
				$notis = [
						'title'=>'<span class="bold underline italic green">'.$f['email'].'</span> đăng ký nhận tin từ hệ thống',
				];
				Yii::$app->db->createCommand()->insert('{{%emails_subscribes}}',$f)->execute();
				$multiple = false;				 
				\app\models\Notifications::insertNotification($notis,$multiple);
			}
		}
		echo json_encode([
		'event'=>'reload',
		'delay'=>5000,
		'callback'=>true,
		'callback_function'=>'showModal(\'Thông báo\',\'Chúc mừng bạn đã đăng ký thành công.\')',
		]);exit;
		break;
	case 'checkUserExisted':
		$val = post('value'); 
		$field = post('field','username'); 
		$id = post('id',0);
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
	case 'reg_newsletter':
		echo json_encode(array(
				'modal'=>true,'modal_content'=>'Đăng ký thành công.','delay'=>3000
				 
		));
		exit;
		break;
	case 'send_contact_request':
		$model = new \frontend\models\ContactForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
		$f = post('f',[]);
		if(isset($_POST['ContactForm'])){
			$f = post('ContactForm');
			if(isset($f['verifyCode'])){
				unset($f['verifyCode']);
			}
			
		}
		
		$f = splitName($f); 
		$fx = Yii::$app->zii->getConfigs('CONTACTS');
		$fx1 = Yii::$app->zii->getConfigs('EMAILS_RESPON');
		$from_email = isset($f['guest']['email']) ? $f['guest']['email'] : $f['email'];
		$from_name = isset($f['guest']['full_name']) ? $f['guest']['full_name'] : $f['lname'] . ' ' . $f['fname'];
		$regex = array(
				'{LOGO}' => isset(Yii::$site['logo']['logo']['image']) ? '<img src="'.Yii::$site['logo']['logo']['image'].'" style="max-height:100px"/>' : '',
				'{DOMAIN}' => __DOMAIN__,
				//'{ORDER_NUMBER}'=>$order_code,
				'{ORDER_TIME}' => date("d/m/Y H:i:s"),
				'{CUSTOMER_NAME}' =>$from_name,
				'{CUSTOMER_ADDRESS}' => isset($f['guest']['address']) ? $f['guest']['address'] : $f['address'],
				'{CUSTOMER_EMAIL}' => $from_email,
				'{CUSTOMER_PHONE}' => isset($f['guest']['phone']) ? $f['guest']['phone'] : $f['phone'],
				// f[receiver]
				'{CUSTOMER_RECEIVER}' => (isset($f['receiver']) && $f['receiver'] == 'on' ? '' :
						(isset($f['guest2']) && !empty($f['guest2']) ? 'Người nhận:&nbsp;<strong>'.$f['guest2']['full_name'].'</strong>&nbsp;- Địa chỉ:&nbsp;<strong>'.$f['guest2']['address'].'</strong>&nbsp;- ĐT:&nbsp;<strong>'.$f['guest2']['phone'].'</strong>' : '') ),
				'{ORDER_TAX_INFOMATION}' => (isset($f['tax']) && $f['tax'] == 'on' ? '<b>Thôn tin xuất hóa đơn:</b></br>
							Tên công ty: '.$f['company']['name'].'<br/>
							Địa chỉ thuế: '.$f['company']['address'].'<br/>
							Mã số thuế: '.$f['company']['tax'].'<br/>
							' : ''),
				//
				//'{ORDER_LINK}'=>$order_link,
				//'{PRODUCTS_LIST}' => $plist,
				'{ORDER_OTHER_REQUEST}' => isset($f['other_request']) ? $f['other_request'] : '',
				'{ORDER_PAYMETHOD}' => isset($f['pay']['method']) ? $f['pay']['method'] : '',
				'{ORDER_PAY_METHOD}' => isset($f['pay']['method']) ? $f['pay']['method'] : '',
				'{ORDER_TRANSPORT}' => isset($f['transport']) ? $f['transport']: '',
				'{CURRENT_COMPANY_NAME}'=> $fx['name'],
				'{CURRENT_COMPANY_INFOMATION}' => !empty($fx) ? '<p>'.$fx['name'].'</p> 
					<p>Địa chỉ: '.$fx['address'].'</p>
					<p>Điện thoại: '.$fx['phone'].'</p>'.($fx['hotline'] != "" ? '<p>Hotline: '.$fx['hotline'].'</p>' : '').'
					<p>Email: '.$fx['email'].'</p>' : '',
				'{REQUEST_CONTENT}'=>isset($f['body']) ? $f['body'] : '',
				'{USER_AGENT_IP}' => getClientIP()
		
		);
		 
		$text1 = Yii::$app->zii->getTextRespon(['code'=>'RP_CONTACT', 'show'=>false]);
		
		
		$form1 = replace_text_form($regex, uh($text1['value']));
		
		$fx['sender'] = $fx['email'];
		$fx['short_name']  = $fx['short_name'] != "" ? $fx['short_name'] : $fx['name'];
		if(isset($fx1['RP_CONTACT'])){
			$fx['email'] = $fx1['RP_CONTACT']['email'] != "" ? $fx1['RP_CONTACT']['email'] : $fx['email'];
		}
		//
		
	 
		//
		Yii::$app->zii->sendEmail(array(
				'subject'=>replace_text_form($regex , $text1['title']) ."  (".date("H:i d/m/Y").")",
				'body'=>$form1,
				'from'=>$from_email,
				
				'fromName'=>$from_name,
				'replyTo'=>$from_email,
				'replyToName'=>$from_name,
				'to'=>$fx['email'],'toName'=>$fx['short_name']
		));
		$notis = [
				'title'=>'Bạn có thư liên hệ mới vui lòng check hòm thư.',
				 
				//'uid'=>Yii::$app->user->id
		];
		\app\models\Notifications::insertNotification($notis);
		echo json_encode([
			'callback'=>true,
			'callback_function'=>'showModal(\'Thông báo\',\'Gửi liên hệ thành công. Chúng tôi sẽ phản hồi lại trong thời gian sớm nhất. Xin cảm ơn !\');window.setTimeout(function(){window.location=\'/\'},3000);',
			'delay'=>3000,
			'redirect'=>'/'	
		]);exit;
		}
		break;
	case 'submit-orders':
		$l = Yii::$app->zii->getCart();
		 
		 
		$f = post('f',[]);
		 
		$plist = '<table  cellspacing="0" style="border:1px solid #555;" width="100%">
			<tr style="background-color:#f1f1f1; height:26px;">
                            <td align="center" style="border:1px solid #ddd;"><b>M&atilde; số</b></td>
                            <td style="border:1px solid #ddd;"><b>T&ecirc;n</b></td>
                            <td align="center" style="border:1px solid #ddd;"><b>Số lượng</b></td>
                            <td align="right" style="border:1px solid #ddd;"><b>Đơn gi&aacute;</b></td>
                            <td align="right" style="border:1px solid #ddd;"><b>Th&agrave;nh tiền</b></td>
			</tr>';$totalPrice = 0; $list = array();
		if($l['totalItem'] > 0){
			foreach($l['listItem'] as $k=>$v){
				$subTotal = $v['item']['price2'] * $v['amount'];
				$list[$k] = array(
						'id'=>$v['item']['id'],
						'code'=>$v['item']['code'],
						'name'=>$v['item']['title'],
						'title'=>$v['item']['title'],
						'url'=>$v['item']['url'],
						'price'=>$v['item']['price2'],
						'amount'=>$v['amount'],
						'link'=>cu(DS.$v['item']['url'],true),
						'currency'=>isset($v['currency']) ? $v['currency'] : 1
				);
				$totalPrice += $subTotal;
				$plist .= '<tr style="height:26px;">
                        <td align="center" style="border:1px solid #ddd;">'.$v['item']['code'].'</td>
                        <td style="border:1px solid #ddd;"><a href="'.cu(DS.$v['item']['url'],true).'">'.uh($v['item']['title']).'</a></td>
                        <td align="center" style="border:1px solid #ddd;">'.$v['amount'].'</td>
                        <td align="right" style="border:1px solid #ddd;">'.number_format($v['item']['price2']).'</td>
                        <td align="right" style="border:1px solid #ddd;"><b>'.number_format($subTotal).' VNĐ</b></td>
                    </tr>';
			}
		}
		 
		$plist .= '<tr style="height:26px">
                            <td align="right" colspan="4" style="border:1px solid #ddd;">
                                    <b>Tổng tiền h&agrave;ng:</b></td>
                            <td align="right" style="border:1px solid #ddd;">
                                    <b><font color="#000088">'.number_format($totalPrice).' VNĐ</font></b></td>
                    </tr></table>';
		// check member & create
		 
		$memID = 0;
		$_m = load_model('members');// new Member();
			
		$mem = $_m->get_item(array(
				'email'=>isset($f['email']) ? $f['email'] : '',
				'phone'=>isset($f['phone']) ? $f['phone'] : '',
		));
		 
		$fx = Yii::$app->zii->getConfigs('CONTACTS');
		$logo = '<a href="'.removeLastSlashes(Url::home(true)).'" target="_blank">'.getImage(['h'=>80, 'src'=>(isset(Yii::$site['logo']['logo']['image']) ? Yii::$site['logo']['logo']['image'] : '')],true).'</a>';
		if(!empty($mem)){
			$memID = $mem['id'];
		}else{
			$password = randString(6);
			$f['guest']['password'] = eString($password);
			$f['guest']['password_hash'] = Yii::$app->security->generatePasswordHash($password);
			$f['guest']['updated_at'] = time();
			$f['guest']['auth_key'] = Yii::$app->security->generateRandomString();
			$f['guest']['status'] = \common\models\Members::STATUS_ACTIVE;
			$memID = $_m->quick_insert($f['guest']);
			if(isset($f['create_member']) && cbool($f['create_member']) == 1){
			$search = array(
					'{LOGO}'=>$logo,
					'{DOMAIN}' => __DOMAIN__,
					'{CUSTOMER_NAME}' => $f['guest']['full_name'],
					'{CUSTOMER_EMAIL}' => $f['guest']['email'],
					'{CUSTOMER_PASSWORD}' => $password,
	
			);
			$text = Yii::$app->zii->getTextRespon(array('code'=>'RP_SENDMEMPASS', 'show'=>false));
			$form = replace_text_form($search, uh($text['value']));
			Yii::$app->zii->sendEmail(array(
					'subject'=>replace_text_form($search, $text['title'])  ,
					'body'=>$form,
					'from'=>$fx['email'],
					'fromName'=>$fx['short_name'],
					'replyTo'=>$fx['email'],
					'replyToName'=>$fx['short_name'],
					'to'=>$f['guest']['email'],'toName'=>$f['guest']['full_name']
			));
			}
		}
		// Thêm vào cơ sở dữ liệu
		//view($_POST,true);
		$m = load_model('orders');
		$order_code = $m->gen_order_code();
			
		$f['listItem'] = $list;
		$biz = $f;
		$data = array(
				'sid'=>__SID__,
				'code'=>$order_code,
				'total_price'=>$totalPrice,
				'mem_id'=>$memID,
				'check_sum'=>md5($order_code . $f['guest']['email']),
				'bizrule'=>cjson($biz)
		);
		$orderID = Yii::$app->zii->insert($m->tableName(),$data);// $m->insert($data);
			
		$order_link = removeLastSlashes(Url::home(true)) . '/orders/vieworder?code='.$order_code.'&check_sum='.md5($order_code . $f['guest']['email']);
		// Send email
		$regex = array(
				'{LOGO}' => $logo,
				'{DOMAIN}' => __DOMAIN__,
				'{ORDER_NUMBER}'=>$order_code,
				'{ORDER_TIME}' => date("d/m/Y H:i:s"),
				'{CUSTOMER_NAME}' => $f['guest']['full_name'],
				'{CUSTOMER_ADDRESS}' => $f['guest']['address'],
				'{CUSTOMER_EMAIL}' => $f['guest']['email'],
				'{CUSTOMER_PHONE}' => $f['guest']['phone'],
				// f[receiver]
				'{CUSTOMER_RECEIVER}' => (isset($f['receiver']) && $f['receiver'] == 'on' ? '' :
						(isset($f['guest2']) && !empty($f['guest2']) ? 'Người nhận:&nbsp;<strong>'.$f['guest2']['full_name'].'</strong>&nbsp;- Địa chỉ:&nbsp;<strong>'.$f['guest2']['address'].'</strong>&nbsp;- ĐT:&nbsp;<strong>'.$f['guest2']['phone'].'</strong>' : '') ),
				'{ORDER_TAX_INFOMATION}' => (isset($f['tax']) && $f['tax'] == 'on' ? '<b>Thôn tin xuất hóa đơn:</b></br>
							Tên công ty: '.$f['company']['name'].'<br/>
							Địa chỉ thuế: '.$f['company']['address'].'<br/>
							Mã số thuế: '.$f['company']['tax'].'<br/>
							' : ''),
				//
				'{ORDER_LINK}'=>$order_link,
				'{PRODUCTS_LIST}' => $plist,
				'{ORDER_OTHER_REQUEST}' => isset($f['other_request']) ? $f['other_request'] : '',
				'{ORDER_PAYMETHOD}' => isset($f['pay']['method']) ? $f['pay']['method'] : '',
				'{ORDER_PAY_METHOD}' => isset($f['pay']['method']) ? $f['pay']['method'] : '',
				'{ORDER_TRANSPORT}' => isset($f['transport']) ? $f['transport']: '',
				'{CURRENT_COMPANY_NAME}'=> $fx['name'],
				'{CURRENT_COMPANY_INFOMATION}' => !empty($fx) ? '<p>'.$fx['name'].'</p>
					<p>Địa chỉ: '.$fx['address'].'</p>
					<p>Điện thoại: '.$fx['phone'].'</p>'.($fx['hotline'] != "" ? '<p>Hotline: '.$fx['hotline'].'</p>' : '').'
					<p>Email: '.$fx['email'].'</p>' : '',
				'{USER_AGENT_IP}' => $_SERVER['REMOTE_ADDR']
	
		);
		$text1 = Yii::$app->zii->getTextRespon(array('code'=>'RP_ORDER_ADMIN', 'show'=>false));
		$text2 = Yii::$app->zii->getTextRespon(array('code'=>'RP_ORDER_CUS', 'show'=>false));
		$form1 = replace_text_form($regex, uh($text1['value']));
		$form2 = replace_text_form($regex, uh($text2['value']));
		$fx1 = Yii::$app->zii->getConfigs('EMAILS_RESPON');
		$fx['sender'] = $fx['email'];
		$fx['short_name']  = $fx['short_name'] != "" ? $fx['short_name'] : $fx['name'];
		if(isset($fx1['RP_ORDER_ADMIN'])){
			$fx['email'] = $fx1['RP_ORDER_ADMIN']['email'] != "" ? $fx1['RP_ORDER_ADMIN']['email'] : $fx['email'];
		}
	
		if(Yii::$app->zii->sendEmail(array(
				'subject'=>replace_text_form($regex , $text1['title']) ."  (".date("H:i d/m/Y").")",
				'body'=>$form1,
				'from'=>$f['guest']['email'],
				//'from'=>'noreply.thaochip@gmail.com',
				'fromName'=>$fx['short_name']  . ' - ' . $f['guest']['full_name'],
				'replyTo'=>$f['guest']['email'],
				'replyToName'=>$f['guest']['full_name'],
				'to'=>$fx['email'],'toName'=>$fx['short_name']
		))){
			// Send to customer
			Yii::$app->zii->sendEmail(array(
					'subject'=>replace_text_form($regex, $text2['title']) ,
					'body'=>$form2,
					'from'=>$fx['sender'],
					'fromName'=>$fx['short_name'] != "" ? $fx['short_name'] : $fx['name'],
					'replyTo'=>$fx['email'],
					'replyToName'=>$fx['short_name'],
					'to'=>$f['guest']['email'],'toName'=>$f['guest']['full_name']
			));
			$msg = "Đặt hàng thành công! Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.";
		}else{
				
			$msg = "Không thành công. Vui lòng liên hệ số hotline để được trợ giúp.";
		}
	
		$notis = [
				'title'=>'Bạn có đơn hàng mới <span class="underline italic">'.$order_code.'</span>',
				'link'=>ADMIN_ADDRESS . \app\modules\admin\models\AdminMenu::get_menu_link('orders') .DS.'edit?id=' .($orderID),
				//'uid'=>Yii::$app->user->id
		];
		\app\models\Notifications::insertNotification($notis);
		//echo json_encode(array('modal'=>true,'modal_content'=>$msg,'event'=>'reload','delay'=>3000));
		Yii::$app->zii->unsetCart();
		echo json_encode(array(
				'modal'=>true,'modal_content'=>$msg,'event'=>'preview_order',
				'text'=>str_replace(array(''),array(""),$form2)
		));
		exit;
		break;
	case 'update_cart':
		$id = post('id',0);
		$role = post('role');
		$amount = post('amount');
		$behavior = post('behavior');
		$item = [];
 
		$a = Yii::$app->zii->updateCart($behavior,$id,$amount);
		$c = Yii::$app->zii->getCart();
		echo json_encode(array(
				'status'=>$a,
				'role'=>$role,
				'item'=>$item,
				'cart'=>array(
						'totalPrice'=>number_format($c['totalPrice']),
						'totalItem'=>number_format($c['totalItem']),
						'changeSubTotal'=>isset($_SESSION[__SITE_NAME__]['cart'][$id]['total']) ? number_format($_SESSION[__SITE_NAME__]['cart'][$id]['total']) : 0,
						'totalPriceText' => docso($c['totalPrice'])
				)
		));
		exit;
		break;
	
	case 'changeLanguage':
		 
		$language = \app\modules\admin\models\AdLanguage::getLanguage(post('lang'));
		 
		$config = Yii::$app->session['config'];
		$config['language'] = $language;
		// view(post('lang'));
		Yii::$app->session->set('config',$config);		
		//view(Yii::$app->session->get('config'));
		exit;
		break;
		
}