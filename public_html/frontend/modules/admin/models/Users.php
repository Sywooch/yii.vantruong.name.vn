<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
use common\models\User;
use app\modules\admin\models\Local;
class Users extends User
{
	public static function getBooleanFields(){
		return [
				'is_active',	
		];
	}
	private $_permission;
		
	public function setPermission($property){
		$this->_permission = $property;
	}
	public function getPermission(){
         return $this->_permission;
     }
    /**
     * @inheritdoc
     */
    public static function tableGroup(){
    	return '{{%user_groups}}';
    }
    public static function tableToGroup(){
    	return '{{%user_to_group}}';
    }
	public static function tableToShop(){
    	return '{{%user_to_shop}}';
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
     
    public function update_groups($id){
    	// group
    	$g = Yii::$app->request->post('g');
    	Yii::$app->db->createCommand()->delete(self::tableToGroup(),['user_id'=>$id])->execute();
    	Yii::$app->authManager->revokeAll ( $id );
    	if(!empty($g['group_id'])){
    		$rows = [];
    		foreach ($g['group_id'] as $x){    			
    			$rows[] = [$x,$id];
    			// auth 
    			$group_name = Yii::$app->db->createCommand("select name from ".self::tableGroup() ." where id=$x")->queryScalar();
    			Yii::$app->authManager->assign(Yii::$app->authManager->createRole($group_name), $id);
    		}
    		Yii::$app->db->createCommand()->batchInsert(self::tableToGroup(),['group_id','user_id'],$rows)->execute();
    	}
    	// shop
    	$query = new Query;
    	// compose the query
    	$c = $query->from(self::tableToShop())
    	->where(['user_id'=>$id,'sid'=>__SID__])
    	->count(1);     
    	if($c == 0){
    		Yii::$app->db->createCommand()->insert(self::tableToShop(),['user_id'=>$id,'sid'=>__SID__,'state'=>1])->execute();
    	}
    	if(post('reset_password') == 'on'){
    		$this->updatePassword($id);
    	}  
    }
    public static function updatePassword($id,$sid = __SID__){
    	$item = self::getItem($id,['sid'=>$sid]);
    	$shop = Shops::getItem($sid);
    	$f = [];
    	if(!empty($item) && !empty($shop)){
    		$password = randString(6);
    		$f['password_hash'] = Yii::$app->security->generatePasswordHash($password);
    		$f['updated_at'] = time();
    		$f['auth_key'] = Yii::$app->security->generateRandomString();
    		
    		Yii::$app->db->createCommand()->update(self::tableName(),$f+['status'=>\common\models\User::STATUS_ACTIVE],['id'=>$id,'sid'=>$sid])->execute();
    		//
    		$search = array(
    				'{LOGO}',
    				'{DOMAIN}',
    				'{USER}',
    				'{USER_NAME}',
    				'{USER_PASSWORD}',
    				'{ADMIN_LINK}',
    					
    		);
    		$replace = array(
    				'',
    				$shop['domain'],
    				isset($item['fullName']) && $item['fullName'] != "" ? $item['fullName'] : $item['email'],
    				$item['username'] != "" ? $item['username'] : $item['email'],
    				$password,
    				'http://' . $shop['domain'] . DS.Yii::$app->controller->module->id,
    					
    		);
    		$text = Yii::$app->getTextRespon(array('code'=>'RP_SENDPASS', 'show'=>false));
    		//view($text); exit;
    		$form = str_replace($search, $replace, uh($text['value'],2));
    		$fx = Yii::$app->getConfigs('CONTACTS');
    		Yii::$app->sendEmail([
    				'subject'=>str_replace($search, $replace, $text['title'])  ,
    				'body'=>$form,
    				'from'=>$fx['email'],
    				'fromName'=>$fx['short_name'],
    				'replyTo'=>$fx['email'],
    				'replyToName'=>$fx['short_name'],
    				'to'=>$item['email'],'toName'=>$item['lname'] . ' ' . $item['fname']
    		]);
    	}
    }
    
    public static function updatePrimaryEmail($email,$sid){
    	if(validateEmail($email) && $sid>0){
    		$item = (new Query())->from(self::tableName())->where(['email'=>$email,'sid'=>$sid])->one();
    		 
    		if(!empty($item)){
    			//
    			$user_id = $item['id'];
    			// Cập nhật bảng user
    			Yii::$app->db->createCommand()->update(self::tableName(),['state'=>1,'status'=>10,'is_active'=>1],['id'=>$item['id'],'sid'=>$sid])->execute();
    			//
    			Yii::$app->authManager->revokeAll ( $item['id'] );
    			Yii::$app->db->createCommand()->update(self::tableToShop(),['state'=>2],['sid'=>$sid])->execute();
    			if((new Query())->from(self::tableToShop())->where(['user_id'=>$item['id'],'sid'=>$sid])->count(1) == 0){
    				Yii::$app->db->createCommand()->insert(self::tableToShop(),['state'=>1,'user_id'=>$item['id'],'sid'=>$sid])->execute();
    			}else{
    				Yii::$app->db->createCommand()->update(self::tableToShop(),['state'=>1],['user_id'=>$item['id'],'sid'=>$sid])->execute();
    			}
    			 
    			Yii::$app->authManager->assign(Yii::$app->authManager->createRole(ADMIN_USER), $item['id']);
    			 
    		}else{
    			Yii::$app->db->createCommand()->insert(self::tableName(),['email'=>$email,'sid'=>$sid,'status'=>10,'is_active'=>1])->execute();
    			$user_id = Yii::$app->db->createCommand("select max(id) from ".self::tableName())->queryScalar();
    			 
    			if($user_id>0){
    				Yii::$app->db->createCommand()->insert(self::tableToShop(),['state'=>1,'user_id'=>$user_id,'sid'=>$sid])->execute();
    				Yii::$app->authManager->assign(Yii::$app->authManager->createRole(ADMIN_USER), $user_id);
    				self::updatePassword($user_id,$sid);
    			
    			}
    		}
    		// Cập nhật nhóm admin
    		// Lấy admin group id
    		$g = (new Query())->from(self::tableGroup())->where(['sid'=>$sid,'name'=>ADMIN_USER])->one();
    		if(!empty($g)){
    			if((new Query())->from(self::tableToGroup())->where(['group_id'=>$g['id'],'user_id'=>$user_id])->count(1) == 0){
    				Yii::$app->db->createCommand()->insert(self::tableToGroup(),['group_id'=>$g['id'],'user_id'=>$user_id])->execute();
    			}
    		}
    		//
    		Yii::$app->db->createCommand()->update(self::tableToShop(),['state'=>2],['sid'=>$sid])->execute();
    		Yii::$app->db->createCommand()->update(self::tableToShop(),['state'=>1],['user_id'=>$user_id,'sid'=>$sid])->execute();
    		//
    		if(!(isset($item['username']) && $item['username'] == ADMIN_USER)){
    			Yii::$app->db->createCommand()->update(self::tableName(),['username'=>ADMIN_USER,'type'=>ADMIN_USER],['id'=>$user_id, 'sid'=>$sid])->execute();
    		}
    		 
    	}
    }
    /*
     * 
     */
    
    public function validate_resetpassword($token){
    	$c = self::isPasswordResetTokenValid($token);
    	if($c){
    		$query = new Query();
    		$c = $query->from(self::tableName())->where(['password_reset_token'=>$token,'sid'=>__SID__])->count(1);
    		if($c > 0) return true;    		
    	}
    	return false;
    }
    
    public static function getItem($id=0,$o=[]){
    	$sid = isset($o['sid']) ? $o['sid'] : __SID__;
    	$is_email = validateEmail($id);
    	$item = static::find()
    	->where(['sid'=>$sid]);
    	if($is_email){
    		$item->andWhere(['email'=>$id]);
    	}else{
    		$item->andWhere(['id'=>$id]);
    	}
    	$item = $item->asArray()->one();
    	if(!empty($item)){
    		$group_id = isset($o['group_id']) ? $o['group_id'] : 0;
    		$sql = "select * from ".self::tableGroup()." as a where a.id in(select group_id from ".self::tableToGroup()." where user_id=".$item['id'].")";
    		$sql .= $group_id > 0 ? " and a.id=$group_id" : "";
    		$g = Yii::$app->db->createCommand($sql)->queryAll();
    		 
    		$v = array(); $vID = array();
    		if(!empty($g)){
    			foreach ($g as $x){
    				$v[] = $x['title'];
    				$vID[] = $x['id'];
    			}
    		}
    		$item['group_id'] = $vID;$item['groups'] = $g;
    		$item['groupName'] = implode(' | ', $v);
    	}
    	//view($item);
    	return $item;
    }
    public static function getListPermission($id=0){
    	$query = static::find()
    	->select(['a.*','b.state as state'])
    	->from(['a'=>self::tableName()])
    	->innerJoin(['b'=>self::tableToGroup()],'a.id=b.user_id')
    	->where(['a.sid'=>__SID__,'b.group_id'=>$id])
    	->andWhere(['>','a.state',-2])
    	//->andWhere(['in','a.id',(new Query())->select('user_id')->from(self::tableToGroup())->where(['group_id' => $id])])
    	;
    	//view($query->createCommand()->getSql());
    	$l = $query->asArray()->all();
    	return $l;
    }
    public static function getList($o = []){
    	$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
    	$order_by = isset($o['order_by']) ? $o['order_by'] : ['fname'=>SORT_ASC];
    	$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : Yii::$app->request->get('p',1);    
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';    	
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$type_id = isset($o['type_id']) ?  $o['type_id'] : -1;
    	$offset = ($p-1) * $limit;
    	$query = static::find()
    	->from(['a'=>self::tableName()])
    	->leftJoin(['b'=>Local::tableName()],'a.local_id=b.id')
    	->where(['a.sid'=>__SID__])
    	->andWhere(['not in','a.id',[Yii::$app->user->id]])
    	->andWhere(['>','a.state',-2]);
    	if(strlen($filter_text) > 0){
    		$query->andFilterWhere(['like', 'name', $filter_text]);
    	}
    	if(is_numeric($type_id) && $type_id > -1){
    		$query->andWhere(['a.type_id'=>$type_id]);
    	}
    	if(is_numeric($parent_id) && $parent_id > -1){
    		$query->andWhere(['a.parent_id'=>$parent_id]);
    	}
    	$c = 0;
    	if($count){
    		$query->select('count(1)');
    		//view($query->createCommand()->getSql());
    		$c = $query->scalar();
    	}
    	$query->select(['a.*','fullName'=>'concat(a.lname ,\' \',a.fname)'])
    	->orderBy($order_by)
    	->offset($offset)
    	->limit($limit);
    	 
    	$l = $query->asArray()->all();
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			$sql = "select * from ".self::tableGroup()." as a where a.id in(select group_id from ".self::tableToGroup()." where user_id=".$v['id'].")";
    			$g = Yii::$app->db->createCommand($sql)->queryAll();
    			$l[$k]['groups'] = $g; $v = array(); $vID = array();
    			if(!empty($g)){
    				foreach ($g as $x){
    					$v[] = $x['title'];
    					$vID[] = $x['id'];
    				}
    			}
    			$l[$k]['group_id'] = $vID;
    			$l[$k]['groupName'] = implode(' | ', $v);
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
    
    public static function removeUser($id = 0){
    	$item = self::getItem($id);
    	if(!empty($item)){
	    	// Loai bo khoi bang to shop
	    	Yii::$app->db->createCommand()->delete('user_to_shop',['user_id'=>$item['id'],'sid'=>__SID__])->execute();
	    	// Loai bo khoi bang to group
	    	Yii::$app->db->createCommand()->delete('user_to_group',['user_id'=>$item['id']])->execute();
	    	// Loai bo phân quyền
	    	Yii::$app->authManager->revokeAll ($item['id'] );
	    	// 
	    	return Yii::$app->db->createCommand()->update('users',['state'=>-5],['id'=>$item['id']])->execute();
    	}
    	return 0;
    }
    
    public static function getUserName($id){
    	return Yii::$app->db->createCommand("select concat(lname , ' ' ,fname) from users where id=$id")->queryScalar();
    }
    
    public static function getAdminUser($sid = __SID__){
    	$query = (new Query())->from (['a'=>self::tableName()])
    	->innerJoin(['b'=>self::tableToShop()],'a.id=b.user_id')
    	->where([
    			'b.state'=>1,
    			'a.sid'=>__SID__,
    			'b.sid'=>__SID__,
    	])->one();
    	return $query;
    }
}
