<?php
namespace common\models;
use Yii;
use yii\db\Query;
class Cronjobs extends \yii\db\ActiveRecord
{
	public static function getBooleanFields(){
		return [
				//'is_active',	
		];
	}
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cronjobs}}';
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
    
    public static function deleteScheduler($code,$item_id,$sid = __SID__){
    	Yii::$app->db->createCommand()->delete(self::tableName(),[
    			'code'=>$code,
    			'item_id'=>$item_id,
    			'sid'=>$sid
    	])->execute();
    }
    public static function updateScheduler($o = []){
    	$code = $o['code'];
    	$item_id = isset($o['item_id']) ? $o['item_id'] : 0;
    	$state = isset($o['state']) ? $o['state'] : -1;
    	$time = isset($o['time']) ? $o['time'] : date("Y-m-d H:i:s");
    	$bizrule = isset($o['bizrule']) ? $o['bizrule'] : [];
    	$sid = isset($o['sid']) ? $o['sid'] : __SID__;
    	//Yii::$app->db->createCommand()->delete(self::tableName(),['sid'=>$sid,'code'=>$code,'item_id'=>$item_id,])->execute();
    	// 
    	 
    	if(check_date_string($time)){   
    		Yii::$app->db->createCommand()->delete(self::tableName(),[
    				'sid'=>$sid,'code'=>$code,
    				'item_id'=>$item_id])->execute();
    	if((new Query())->from(['a'=>self::tableName()])->where(['sid'=>$sid,'code'=>$code,'item_id'=>$item_id,'time'=>$time])->count(1) == 0){    		
    		 
    		$a = Yii::$app->db->createCommand()->insert(self::tableName(),[
    				'sid'=>$sid,'code'=>$code,
    				'item_id'=>$item_id,
    				'state'=>$state,
    				'time'=>$time,
    				//'bizrule'=>jsonify($bizrule)
    		]+(!empty($bizrule) ? ['bizrule'=>jsonify($bizrule)] : []))->execute();
    		 
    	}else{
    		return Yii::$app->db->createCommand()->update(self::tableName(),[    				
    				'state'=>$state,    				    	
    		]+(!empty($bizrule) ? ['bizrule'=>jsonify($bizrule)] : []),['sid'=>$sid,'code'=>$code,'item_id'=>$item_id,'time'=>$time])->execute();
    	}
    	}
    }

    public function getID(){
    	$sql = "select max(id) +1 from ".self::tableName();
    	return Yii::$app->db->createCommand($sql)->queryScalar();
    }     
     
    public static function getAll($o = [],$r=[]){
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : 0;
    	$level = isset($o['level']) ? $o['level'] : -1;
    	$query = new Query();
    	$query->from(['a'=>self::tableName()])    	
    	->where(['a.parent_id'=>$parent_id])
    	->orderBy(['a.name'=>SORT_ASC]);
    	if($level>-1){
    		$query->andWhere(['<','a.level',$level]);
    	}
    	//if($level>1) view($query->createCommand()->getSql(),true);
    	$l = $query->all();
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			$r[] = $v;
    			$o['parent_id'] = $v['id'];
    			$r = self::getAll($o,$r);
    		}
    	}
    	return $r;
    }
     
    public function count_all_child($id = 0,$c = 0){
    	$m = Yii::$app->db->createCommand("select a.id,a.parent_id from ".self::tableName()." as a where a.parent_id=$id" )->queryAll();
    	$c += count($m);
    	if(!empty($m)){
    		foreach ($m as $k=>$v){
    			$c = $this->count_all_child($v['id'],$c);
    		}
    	}
    	return $c;
    }
    
    public static function getTodayTask(){
    	$query = static::find()->where([
    			'and',['state'=>-1],
    			['<',(new \yii\db\Expression('UNIX_TIMESTAMP(time)')),time()]
    	]);
    	//if(!Yii::$app->user->can(ROOT_USER)){
    	$query->andWhere(['sid'=>__SID__]);
    	//}
    	return $query->asArray()->all();
    }
    public static function update_exchange_rates(){
    	$d = get_exchangerates();
    	 
    	if(isset($d->Exrate)){    		
    		$g_currency = Yii::$app->zii->getCurrency();    	
    		//view($d->DateTime);
    		$time_update = date("Y-m-d H:i:s",strtotime($d->DateTime));
    		if((new \yii\db\Query())->from('exchange_rate')->where(array('from_date'=>$time_update))->count(1) == 0){    			 
    			foreach ($d->Exrate as $v){
    				//view($v);
    				if((new \yii\db\Query())->from('currency')->where(['code'=>$v['CurrencyCode'].''])->count(1) == 0){
    					$id = Yii::$app->zii->insert('currency',array(
    							'code'=>$v['CurrencyCode'],
    							'symbol'=>$v['CurrencyCode'],
    							'name'=>$v['CurrencyName'],
    							'title'=>$v['CurrencyName'],
    					));
    
    				}else{
    					//Zii::$db->update('currency',array('name'=>$v['CurrencyName']),array('code'=>$v['CurrencyCode']));
    				}
    				// view($v['Sell']);
    				// Cập nhật tỷ giá
    				$item = Yii::$app->zii->getCurrencyByCode($v['CurrencyCode']);      				 
    				if(!empty($item)){
    					$ex  = Yii::$app->zii->get_exrate(array(
    							'from' => $item['id'],
    							'to'=>$g_currency['id'],
    							'return'=>'last'
    					));
    					
    					//view($ex);
    					
    					if(!empty($ex) && $ex['value'] == $v['Sell']){
    						// Khi tỷ giá không tăng
    					}else{
    						// Khi tỷ giá biến động
    						Yii::$app->db->createCommand()->insert('exchange_rate',array(
    								'from_currency'=>$item['id'],
    								'value'=>$v['Sell'],
    								'from_date'=>$time_update,
    								'to_currency'=>$g_currency['id']))->execute(); 
    					}
    				}
    			}
    		}}
    }
    
    /*
     * 
     */
    
    public function getAllTodayJobs(){
    	return (new Query())->from(['a'=>self::tableName()])->where([
    			'and',['a.state'=>-1],
    			['<',(new \yii\db\Expression('UNIX_TIMESTAMP(a.time)')),time()]
    	])->all();
    }
    
    public static function clearJobExecuted($time = 30){
    //	$a = ['a.state'=>1];
    	if(date('H') == 3){
	    	Yii::$app->db->createCommand()->delete(self::tableName(),['and',['state'=>1],
	    			['<',(new \yii\db\Expression('UNIX_TIMESTAMP(time)')),time()-($time*86400)]
	    	])->execute();
    	}
    }
    
    public static function setExpiredShopsNotification(){
    	 
    	if(
    			date('H')>0 && date('H')<4 && 
    			!isset($_COOKIE['setExpiredShopsNotification'])
    			){ 
    	 
    	$to_date = mktime(0,0,0,date('m')+2,date('d'),date('Y'));
    	$l = (new Query())->select(['a.to_date','a.id'])->from(['a'=>\app\modules\admin\models\Shops::tableName()])->where([
    			'<',(new \yii\db\Expression('UNIX_TIMESTAMP(a.to_date)')),$to_date
    	])->all();
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			//view($v); 
    			self::setNotificationDateExpired(countDownDayExpired($v['to_date']),$v['id']);
    		}
    	}
 
    	setcookie("setExpiredShopsNotification", 1, time()+82800);
    	
    	 
    	}
    }
    
    private static function setNotificationDateExpired($time_left = 999, $sid = 0){
    	if($time_left>60) return false;
    	$session = Yii::$app->session;
    	$state = false;
    	if (!$session->has('time_cookie_sexpired_'.$sid)) {
    		switch (true){
    			case ($time_left < 60 && $time_left > 30): // Thông báo lần 1
    				$state = 1;
    				break;
    			case ($time_left > 15 && $time_left < 31): // Thông báo lần 2
    				$state = 2;
    				break;
    			case ($time_left > 5 && $time_left < 16): // Thông báo lần 3
    				$state = 3;
    				break;
    			case ($time_left > 2 && $time_left < 6): // Thông báo lần 4
    				$state = 4;
    				break;
    			case ($time_left > -1 && $time_left < 3): // Thông báo lần 5
    				$state = 5;
    				break;
    			case ($time_left > -5 && $time_left < -1): // Thông báo tạm ngưng dịch vụ
    				$state = 6;
    				break;
    			case ($time_left > -16 && $time_left < -14): // Thông báo ngừng toàn bộ dịch vụ
    				$state = 7;
    				break;
    		}
    		if((new \yii\db\Query())->from(['a'=>self::tableName()])->where([
    				'type_code'=>SHOP_EXPIRED,'sid'=>$sid,'item_id'=>$state
    		])->count(1) == 0){
    			Yii::$app->db->createCommand()->insert(self::tableName(),[
    					'type_code'=>SHOP_EXPIRED,'sid'=>$sid,'item_id'=>$state
    			])->execute();
    		}
    
    		$session->set('time_cookie_sexpired_'.$sid, $state);
    			
    	}
    
    }
    
    public static function executeAllTodayJobs(){
    	foreach (self::getAllTodayJobs() as $k=>$v){
    		$state = 1;
    		//if(!Yii::$app->user->can(ROOT_USER)) break;
    		switch ($v['type_code']) {
    			case SHOP_EXPIRED: // Tài khoản hết hạn
    				
    				if(date('H') > 3 || date('H') <2){
    					$state = -1;
    					break;
    				}
    				if(isset($_COOKIE['sented_email_expired_'.$v['sid']]) && $_COOKIE['sented_email_expired_'.$v['sid']] ==  1){
    					$state = -1;
    					break;
    				}
    				 
    				$text1 = Yii::$app->zii->getTextRespon([
    				'code'=>'RP_SHOP_EXPRIED',
    				'sid'=>$v['sid'],
    				'show'=>false]);
    				//
    				$fx = Yii::$app->zii->getConfigs('CONTACTS',__LANG__,$v['sid']);
    				$user = \app\modules\admin\models\Users::getAdminUser($v['sid']);
    				$domain = \app\modules\admin\models\Users::getMainDomain($v['sid']);
    				$shop = \app\modules\admin\models\Shops::getItem($v['sid']);
    				//
    				$regex = [
    						//'{LOGO}' => isset(Yii::$site['logo']['logo']['image']) ? '<img src="'.Yii::$site['logo']['logo']['image'].'" style="max-height:100px"/>' : '',
    						'{DOMAIN}' => $domain,
    						'{COMPANY_NAME}'=>$fx['name'],
    						'{COMPANY_ADDRESS}'=>$fx['name'],
    						'{TIME_SENT}'=>date('d/m/Y H:i:s'),
    						'{ADMIN_NAME}'=>$user['lname'] . ' ' . $user['fname'],
    						'{ADMIN_ADDRESS}' => $user['address'] != "" ? $user['address'] : $fx['address'],
    						'{ADMIN_EMAIL}'=>$user['email'],
    						'{ADMIN_PHONE}'=>$user['phone'],
    						'{SERVICES_LIST}'=>'<table cellspacing="0" cellpadding="0" border="0" class="table table-bordered " style="width:100%"><thead> <tr>
<th style="border:1px solid orange;background:#FF9800;text-align:center;color:white "><div style="padding:8px">Tên dịch vụ</div></th>
<th style="border:1px solid orange;background:#FF9800;text-align:center;color:white "><div style="padding:8px">Ngày hết hạn</div></th>   </tr> </thead> <tbody>
<tr>
<td style="border:1px solid orange; "><div style="padding:8px">Tài khoản: <a target="_blank" href="http://'.($domain).'">'.($domain).'</a></div></td>
<td style="border:1px solid orange; text-align:center"><div style="padding:8px">'.date('d-m-Y', strtotime($shop['to_date'])).'</div></td>
</tr> </tbody> </table>'
			
    				];
    		
    				$form1 = replace_text_form($regex, uh($text1['value']));
    		
    				$fx1 = Yii::$app->zii->getConfigs('EMAILS_RESPON',__LANG__,$v['sid']);
    				//view($fx1,true);
    				$fx['sender'] = $fx['email'];
    				$fx['short_name']  = isset($fx['short_name']) && $fx['short_name'] != "" ? $fx['short_name'] : (isset($fx['name']) ? $fx['name'] : '');
    				$fx['email'] = isset($fx['email']) ? $fx['email'] : false;
    				if(isset($fx1['RP_CONTACT'])){
    					$fx['email'] = $fx1['RP_CONTACT']['email'] != "" ? $fx1['RP_CONTACT']['email'] : (isset($fx['email']) ? $fx['email'] : false);
    				}
    				//view($form1);
    				//view($fx,true);
    				$sented = false;
    				if($fx['email'] !== false) {
    					$sented = Yii::$app->zii->sendEmail([    				
	    				'subject'=>replace_text_form($regex , $text1['title'])  ,
	    				'body'=>$form1,
	    				'from'=>'info@codedao.info',
	    				//'from'=>'noreply.thaochip@gmail.com',
	    				'fromName'=>$fx['short_name'],
	    				//'replyTo'=>'zinzin',
	    				//'replyToName'=>$f['guest']['full_name'],
	    				'to'=>$fx['email'],
	    				//'to'=>'zinzinx8@gmail.com',
	    				'toName'=>$fx['short_name'],
	    				'sid'=>$v['sid']
	    				]);
    					
    				}
    				//
    				if($sented){
    				$notis = [
    						'title'=>'Thông báo gia hạn dịch vụ '.$domain,
    						'sid'=>$v['sid'],
    						'note'=>'Ngày hết hạn: '.date('d-m-Y', strtotime($shop['to_date'])),
    						 
    				];
    				\app\models\Notifications::insertNotification($notis);
    				}
    				//
    				if(!$sented){
    					\common\models\SystemLogs::writeLog([
    							'code'=>'MAIL_ERROR_LOGS',
    							'sid'=>$v['sid'],
    							'user_id'=>Yii::$app->user->id > 0 ? Yii::$app->user->id : 0,
    							'bizrule'=>json_encode([
    									'from'=>'info@codedao.info',
    									'to'=>$fx['email'],
    									'subject'=>replace_text_form($regex , $text1['title'])  ,
    									//'body'=>$messageBody,
    									'ip'=>getClientIP(),
    									'sent_status'=>$sented,
    									'domain'=>$domain
    							]),
    					]);
    					$state = -1;
    				}
    				set_cookie('sented_email_expired_'.$v['sid'],1,7200);
    				
    		
    				break;
    		}
    		if($state !== -1) {
    			Yii::$app->db->createCommand()->update(\common\models\Cronjobs::tableName(),
    					['state'=>$state,'last_modify'=>date('Y-m-d H:i:s')],
    					['type_code'=>$v['type_code'],'item_id'=>$v['item_id'],'sid'=>$v['sid']])->execute();
    		}
    	}
    }
}
