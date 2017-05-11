<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "members".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $type
 * @property string $fname
 * @property string $lname
 * @property string $address
 * @property integer $local_id
 * @property string $email
 * @property string $phone
 * @property string $phone_login
 * @property integer $is_active
 * @property string $code
 * @property integer $status
 * @property string $time
 * @property string $check_sum
 * @property integer $state
 * @property integer $group_id
 * @property integer $parent_id
 * @property integer $sid
 * @property string $last_modify
 * @property string $bizrule
 * @property string $token
 * @property string $gender
 * @property string $birth
 * @property string $facebook_id
 * @property string $google_id
 */
class Members extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customers}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['local_id', 'is_active', 'status', 'state', 'group_id', 'parent_id', 'sid'], 'integer'],
            [['phone_login', 'token', 'facebook_id', 'google_id'], 'required'],
            [['time', 'last_modify', 'birth'], 'safe'],
            [['bizrule'], 'string'],
            [['username', 'email'], 'string', 'max' => 100],
            [['password', 'fname', 'lname', 'code'], 'string', 'max' => 64],
            [['type', 'phone'], 'string', 'max' => 15],
            [['address'], 'string', 'max' => 300],
            [['phone_login'], 'string', 'max' => 16],
            [['check_sum', 'token', 'facebook_id', 'google_id'], 'string', 'max' => 32],
            [['gender'], 'string', 'max' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'type' => 'Type',
            'fname' => 'Fname',
            'lname' => 'Lname',
            'address' => 'Address',
            'local_id' => 'Local ID',
            'email' => 'Email',
            'phone' => 'Phone',
            'phone_login' => 'Phone Login',
            'is_active' => 'Is Active',
            'code' => 'Code',
            'status' => 'Status',
            'time' => 'Time',
            'check_sum' => 'Check Sum',
            'state' => 'State',
            'group_id' => 'Group ID',
            'parent_id' => 'Parent ID',
            'sid' => 'Sid',
            'last_modify' => 'Last Modify',
            'bizrule' => 'Bizrule',
            'token' => 'Token',
            'gender' => 'Gender',
            'birth' => 'Birth',
            'facebook_id' => 'Facebook ID',
            'google_id' => 'Google ID',
        ];
    }
    
    public function tableUG(){
    	return '{{%user_groups}}';
    }
    public function tableU2G(){
    	return '{{%user_to_group}}';
    }
    public function tableUT(){
    	return '{{%user_type}}';
    }
    public function tableU2S(){
    	return '{{%user_to_shop}}';
    }
    public function quick_insert($f){
    	$fn = '';
    	if(isset($f['fullName'])){
    		$fn = $f['fullName'];
    		unset($f['fullName']);
    	}
    	if(isset($f['fullname'])){
    		$fn = $f['fullname'];
    		unset($f['fullname']);
    	}
    	if(isset($f['full_name'])){
    		$fn = $f['full_name'];
    		unset($f['full_name']);
    	}
    	if(!isset($f['sid'])) $f['sid'] = __SID__;
    	if(!isset($f['is_active'])) $f['is_active'] = 1;
    	//
    	$pos = strrpos(trim($fn), ' ');
    	$f['fname'] = $pos !== false ? substr($fn, $pos+1) : $fn;
    	$f['lname'] = $pos !== false ? substr($fn,0,$pos) : '';
    	Yii::$app->db->createCommand()->insert($this->tableName(),$f);
    	return Yii::$app->db->createCommand("select max(id) from ".$this->tableName())->queryScalar();
    }
    public function get_item($o = array()){
    	$id = isset($o['id']) && $o['id'] > 0 ? $o['id'] : 0;
    	$email = isset($o['email']) && validateEmail($o['email']) ? $o['email'] : false;
    	$phone = isset($o['phone']) && strlen($o['phone']) > 5 ? filter_phone_number($o['phone']) : false;
    	$sql = "select a.* from {$this->tableName()} as a where a.state>-1 and a.is_active=1 and a.sid=".__SID__;
    	$sql .= " and(0=1";
    	$sql .= $id > 0 ? " or a.id=$id" : '';
    	$sql .= $email !== false ? " or a.email='$email'" : '';
    	$sql .= $phone !== false ? " or a.phone_login='$phone'" : '';
    	$sql .= ")";
    	return Yii::$app->db->createCommand($sql)->queryOne();
    }
    public function getModule($id = 0,$type = 0){
    	$sql = "select module_id from module_to_group where group_id=$id and type=$type and sid=".__SID__;
    	$l = Yii::$app->db->createCommand($sql)->queryAll();
    	$r = array();
    	if(!empty($l)){
    		foreach($l as $k=>$v){
    			$r[] = $v['module_id'];
    		}
    	}
    	return $r;
    }
    public function getListUser($id = 0){
    	$id = $id > 0 ? $id : 0;
    	$sql = "select a.id,a.username,a.email,a.phone,b.state from users as a inner join user_to_group as b on a.id = b.user_id and b.group_id=$id";
    	return Yii::$app->db->createCommand($sql)->queryAll();
    }
    
    public function getItem($id = 0){
    	 
    	$id = validateEmail($id) ? $id :($id > 0 ? $id : getParam('id',array('num'=>true)));
    	$sql = "select a.*,concat(a.lname,' ',a.fname) as fullName,b.name as localName from {$this->tableName()} as a left outer join `local` as b on a.local_id=b.id ";
    	 
    	$sql .=  " where a.sid=".__SID__;
    	$sql .= !in_array(MEMBER_LOGIN_TYPE, array('admin','root')) ? " and a.type not in('admin','root')" : '';
    	$sql .= validateEmail($id) ? " and a.email='$id'" : " and a.id=$id";
    	//view($sql);
    	$l = Yii::$app->db->createCommand($sql)->queryOne();
    
    	 
    	return $l;
    }
    public function getList($o = array()){
    	$limit = isset($o['limit']) ? $o['limit'] : 30;
    	$select = isset($o['select']) ? $o['select'] : 'a.*';
    	$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
    	$is_active = isset($o['is_active']) ? $o['is_active'] : -1;
    	$type = isset($o['type']) ?  $o['type'] : getParam('type');
    	$p = isset($o['p']) ? $o['p'] : getParam('p',array('num'=>true,'min'=>1,'default'=>1));
    	$count  = isset($o['count']) && $o['count'] == false ? false   : true;
    	$option = array('biz'=>true,'content'=>(isset($o['content']) && $o['content'] == false ? false   : true));
    	 
    	$filter_text = getParam('filter_text');
    	 
    	//////////
    	$start = ($p-1) * $limit;
    	 
    	$sql = "select a.*,concat(a.lname,' ',a.fname) as fullName,b.name as localName from {$this->tableName()} as a left outer join `local` as b on a.local_id=b.id  where a.type not in('admin','root') and a.sid=".__SID__;
    	$sql .= $filter_text != "" ? " and (a.fullName like '%".($filter_text)."%')" : '';
    	$sql .= $is_active > -1 ? " and a.is_active=$is_active" : '';
    	$sql .= " order by a.fname COLLATE utf8_persian_ci";
    	$sql .= " limit $start,$limit";
    	// 	echo $sql;
    	$l = Yii::$app->db->createCommand($sql)->queryAll();
    	$total_record = $limit;
    	$total_pages = 1;
    	///////////
    	if($count){
    		$sql = "select count(1) from {$this->tableName()} as a left outer join `local` as b on a.local_id=b.id  where a.type not in('admin','root') and a.sid=".__SID__;
    		$sql .= $filter_text != "" ? " and (concat(a.lname,' ',a.fname) like '%".($filter_text)."%')" : '';
    		$sql .= $is_active > -1 ? " and a.is_active=$is_active" : '';
    		$total_record = Yii::$app->db->createCommand($sql)->queryScalar();
    		$total_pages = ceil($total_record/$limit);
    	}
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			$sql = "select * from user_groups as a where a.id in(select group_id from user_to_group where user_id=".$v['id'].")";
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
    	$rs =array(
    			'total_record'=>(int)$total_record,
    			'total_pages'=>(int)$total_pages,
    			'p'=>$p,
    			'limit'=>$limit,
    			'listItem'=>$l,
    	);
    	 
    	return $rs;
    }
     
}
