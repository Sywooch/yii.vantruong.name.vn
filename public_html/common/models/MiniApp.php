<?php
namespace common\models;
class MiniApp{
    private static $config ;
	private $link = null;
	private static $instance;
	private $MySQLi;
    public function __construct($config=null){ 
        self::$config = $config;
         
    }
    public function connectDataBase(){
    	
    	$dsn = explode(';', self::$config['dsn']);
    	 
		$this->link = mysqli_connect(
				(str_replace('mysql:host=', '', $dsn[0])) ,
				(self::$config['username']),
				(self::$config['password']),
				(str_replace('dbname=', '', $dsn[1]))
		) or die (mysqli_errno($this->link));				
        $this->link->set_charset("utf8");
        //view($this->link,true);
		return $this->link;
	}
	public static function getMySQLiObject(){
		return $this->link;
	}
	
	public function query($q){
		return $this->link->query($q);
	}
	
	public function esc($str=''){
		
		return $this->link->real_escape_string(htmlspecialchars($str)); 
	}
	public function escape($value){
		if(is_array($value)) return $value;
		return $value == "" ? $value : mysqli_real_escape_string($this->link,$value);
	}
    private function insertTrash($table, $item_id = 0){
      $bizrule = json_encode(array(
        'server'=>$_SERVER,
        'session'=>$_SESSION,
      ));
      if(is_array($item_id)){
         foreach($item_id as $k){
           $r = $this->execute("insert into `trash`(`table_name`,`item_id`,bizrule) values('$table','$k','$bizrule')");
         }
      }else{
        $r = $this->execute("insert into `trash`(`table_name`,`item_id`,bizrule) values('$table','$item_id','$bizrule')");
      }
      return $r;

    }
   
    public function delete($table,$id=0,$option=0){
    	 
    	if($option == 0){
    		$sql = "update `$table` set state=-5 where id=$id";
    		$a = $this->execute($sql);
    		$this->insertTrash($table,$id);
    	}elseif($option == 1){
    		if (is_numeric($id)){
    			$sql = "delete from `$table` where id=$id";
    		}elseif (is_array($id) && !empty($id) && isset($id[0]) && is_numeric($id[0])){
    			$sql = "delete from `$table` where id in(".implode(',', $id).")";
    		}elseif(is_array($id) && !empty($id)){
    			$sql = "delete from `$table` where 1 ";
    			foreach($id as $k=>$v){
    				$sql .= " and `$k` in(" . (is_array($v) ? implode(',', $v) : (is_numeric($v) ? $v : "'$v'")) .")";
    			}
    		}
    		$a = $this->execute($sql);
    	}
  //view($sql);
    	return $a;
    }

    public function deleteAll($table,$id=0,$option=0){
       if($option == 0){
         $sql = "update `$table` set state=-5 where id in($id)";
       }else{
         $sql = "delete from `$table` where id in($id)";
       }
       //echo $sql;
       $this->insertTrash($table,$id);
       return $this->execute($sql);
    }

    public function update($table,$data=array(),$con=array()){
        if(empty($data) || empty($con)) return false;
        $sql = "UPDATE `$table` as a SET "; $i=0;

        foreach($data as $k=>$v){
        	$u = $this->escape($v);
        	if(substr($u, 0,1) == '@' && substr($u, -1) == '@'){
        		$u = substr($u, 1,strlen($u)-2);
        		$sql .= "a.$k=$u";
        	}else{
        		$sql .= "a.$k='".$u."'";
        	}
           
           if($i<count($data)-1) $sql .= ',';
           $i++;
        }
        $sql .= " WHERE 1";
        if(is_array($con) && !empty($con)){
	        foreach($con as $k=>$v){
	          $sql .= " and a.$k=" . (is_numeric($v) ? $v : "'$v'");
	        }
        }elseif(is_numeric($con) && $con > 0){
        	$sql .= " and a.id=$con";
        } 
        // view($sql);
        return $this->execute($sql);
    }
    
     public function insert($table=null,$a = array(),$ident = array('id','NULL')){
			if($table==null) return false;
            if(!empty($a) && is_array($a)){
                
            }else{
                
            }
			$sql = "INSERT INTO `$table`(`".$ident[0]."`";
			if(!empty($a) && count($a)>0){
				foreach($a as $k=>$v){
				$sql .= ",`$k`";	
				}
			}
			$sql .= ") VALUES(".($ident[1] === 'NULL' ? "NULL" : "'".$ident[1]."'");
			if(!empty($a) && count($a)>0){
				foreach($a as $k=>$v){
				$sql .= ",". (is_numeric($v) ? $v :  
						"'".$this->escape($v)."'"); 
				}
			}
			$sql .=")";
			//view(0=='NULL');
			//view($sql); 
			$res = self::execute($sql); 
			if($res !== false)	return self::queryScalar("select MAX(".$ident[0].") from $table");
			return false;
	}
	public function get_max($o = array()){
		$table = isset($o['table']) ? $o['table'] : false;
		$field = isset($o['field']) ? $o['field'] : 'id';
		$con = isset($o['con']) ? $o['con'] : array();
		$sid = isset($o['sid']) ? $o['sid'] : __SID__;
		$sql = "select max(a.$field) from `$table` as a where 1";
		if($sid>0 && !isset($con['sid'])){
			$con['sid'] = $sid;
		}
		if(!empty($con)){
			foreach ($con as $k=>$v){
				$sql .= " and a.$k='$v'";
			}
		}
		return $this->queryScalar($sql);
	}
	public function get_row_table($o = array()){
		$table = isset($o['table']) ? $o['table'] : false;
		$field = isset($o['field']) ? $o['field'] : 'a.*';
		$con = isset($o['con']) ? $o['con'] : array();
		$sql = "select " . (is_array($field) ? implode(',', $field) : $field) ." from `$table` as a where 1";
		$sid = isset($o['sid']) ? $o['sid'] : __SID__;
		if($sid>0 && !isset($con['sid'])){
			$con['sid'] = $sid;
		}
		//if($sid == -1 && isset($con['sid'])) unset($con['sid']); 
		if(!empty($con)){
			foreach ($con as $k=>$v){
				$sql .= " and a.$k='$v'";
			}
		}
		return $this->queryRow($sql);
	}
	public function queryUpdate($table=null,$a = array(),$con){
			if($table==null) return false;
            if(!empty($a) && is_array($a)){
                
            }else{
                
            }
			$sql = "update  `$table` set ";
			if(!empty($a) && count($a)>0){
			 $i = 0;
				foreach($a as $k=>$v){
    				$sql .= "`$k`='".$this->escape($v)."'";
                    if($i++ < count($a)-1) $sql .= ',';	
				}
			}
			$sql .= " where $con"; 
            // var_dump(self::execute($sql));echo $sql; exit;
              
			return self::execute($sql);
			 
	}
		
	public function queryAll($sql = false,$o = array())
	{
		
		 // $biz = isset($o['bizrule']) && $o['bizrule'] == false ? false : true;
		//  $cnt = isset($o['content']) && $o['content'] == false ? false : true;
            $a = array('bizrule','content');
			if(!$sql) return false;	
			$list=array();		 
			$res = mysqli_query($this->link,$sql) or die(DESIGN_MODE ? mysqli_error($this->link). ' on line '.__LINE__  . '/ ' .$sql : $this->showError());///
			if(mysqli_num_rows($res) > 0){
				while($rows = mysqli_fetch_assoc ($res)){
				  foreach($a as $k){
                    if(isset($rows[$k])){
                    	//$j = djson($rows[$k]);
                    	$j = json_decode($rows[$k],1);
                    	if(!is_array($j)){
                    		$j = djson($rows[$k]);
                    	}
                    	if(is_array($j) && !empty($j)){
                        	$rows += $j;
                    	}
                        unset($rows[$k]);
                    }
                  }
                  $list[] = $rows;
				}
			}		
			self::explain_sql($sql);	 
			return $list;		 
	}
	public function queryAAll($sql = false,$o = array())
	{
 
		if(!$sql) return false;
		$list=array();
		$res = mysqli_query($this->link,$sql) or die(DESIGN_MODE ? mysqli_error($this->link). ' on line '.__LINE__  . '/ ' .$sql : $this->showError() );///
		if(mysqli_num_rows($res) > 0){
			while($rows = mysqli_fetch_assoc ($res)){
				 
				$list[] = $rows;
			}
		}
		 
		return $list;
	}
		public function execute($sql)
		{
			//view($sql);
		//var_dump($this->link->query($sql));
			return mysqli_query($this->link,$sql);	
		}
		public function countTable($table, $con=array()){
			//if(empty($con)) return false;
			
			$sql = "select count(1) from `$table` as a  ";
			$i=0;
		
			$sql .= " WHERE 1";
			foreach($con as $k=>$v){
				if(is_array($v)){
					if(!is_numeric($v[0]) && in_array($v[0], array('>','<','=','>=','<='))){
						
						$sql .= " and a.$k ". $v[0] .$v[1];
					}else{
						switch ($v[0]){
							case 'not':
								unset($v[0]);
								$sql .= " and a.$k not in(".implode(',', $v).")";
								
								break;
							default:$sql .= " and a.$k in(".implode(',', $v).")";
								break;
						}
						
						
					}
				}else $sql .= " and a.$k='$v'";
			}
			//echo $sql;
			return $this->queryScalar($sql);
		}
		public function getField($table,$field, $con=array()){
			//if(empty($con)) return false;
			
			$sql = "select a.$field from `$table` as a  ";
			
			$sql .= " WHERE 1";
			foreach($con as $k=>$v){
				if(is_array($v)){
					if(!is_numeric($v[0]) && in_array($v[0], array('>','<','=','>=','<='))){
		
						$sql .= " and a.$k ". $v[0] .$v[1];
					}else{
						$sql .= " and a.$k in(".implode(',', $v).")";
		
					}
				}else $sql .= " and a.$k='$v'";
			}
				
			return $this->queryScalar($sql);
		}
 
		public function queryRow($sql=false)
		{			
			if(!$sql) return false;
            $a = array('bizrule','content');
			$res = mysqli_query($this->link,$sql) or die(DESIGN_MODE ? mysqli_error($this->link). ' on line '.__LINE__ . $sql : '');///	
			self::explain_sql($sql);	
			if(mysqli_num_rows($res) > 0){
				$rows = mysqli_fetch_assoc ($res);
                foreach($a as $k){
                    if(isset($rows[$k])){
                        $j = json_decode($rows[$k],1);
                        if(!is_array($j)){
                        	$j = djson($rows[$k]);
                        }
                        //view($rows[$k]);
                       // $j = djson($rows[$k]);
                    	if(is_array($j) && !empty($j)){
                        	$rows += $j;
                    	}
                        unset($rows[$k]);
                    }
                }
				unset($res);
				return $rows;
			}						
			return array();
		}
		
		public function queryARow($sql=false)
		{
			
			if(!$sql) return false;
			//$a = array('bizrule','content');
			$res = mysqli_query($this->link,$sql) or die(DESIGN_MODE ? mysqli_error($this->link). ' on line '.__LINE__ . $sql : '');///
			self::explain_sql($sql);
			if(mysqli_num_rows($res) > 0){
				$rows = mysqli_fetch_assoc ($res);
				 
				return $rows;
			}
			return array();
		}
		
		
		public function queryScalar($sql=false)
		{
			//view($sql);
			if(!$sql) return false;	
			$list=false;		 
			$res = mysqli_query($this->link,$sql) or die(DESIGN_MODE ? mysqli_error($this->link). ' on line '.__LINE__   . $sql: '');///		
			if(mysqli_num_rows($res) > 0){
				$rows = mysqli_fetch_array($res,MYSQLI_NUM);
				return $rows[0];
			}			
			//self::explain_sql($sql); 
			return $list;
		}

		public function run($builder,$param = 'queryAll')
		{			
			return self::$param($builder);
		}
		
		//public function query($builder,$param = 'queryAll')
		//{			
		//	return self::$param($builder);
		//}
		 
		private function explain_sql($sql){
			 
		 if(isset($_SESSION['test']) && $_SESSION['test'] === true && $sql !== false){
			$rsx = mysqli_query($this->link,'Explain '.$sql);
			$list1 = @mysqli_fetch_assoc($rsx);
			$sqlx = "insert into explain_sql (`select_type`,`table`,`type`,`possible_keys`,`key`,`key_len`,`ref`,`rows`,`Extra`,`sql`,`is_admin`,`sl_type`,uid) values ('".$list1['select_type']."','".$list1['table']."','".$list1['type']."','".$list1['possible_keys']."','".$list1['key']."','".$list1['key_len']."','".$list1['ref']."','".$list1['rows']."','".$list1['Extra']."','".addslashes($sql)."','".__IS_ADMIN."','APP','".__UID."')"; 
			mysqli_query($this->link,$sqlx) or die(mysqli_error($this->link) . $sqlx); 
			  		 
		}
		}
        public function getSID (){
        	$s = $_SERVER;
        	$ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
        	$sp = strtolower($s['SERVER_PROTOCOL']);
        	$protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
        	$port = $s['SERVER_PORT'];
        	$port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
        	$host = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : $s['SERVER_NAME'];
        	
        	$url = $protocol . '://' . $host . $port . ($s['REQUEST_URI'] ? $s['REQUEST_URI'] : $_SERVER['HTTP_X_ORIGINAL_URL']);
        	$pattern = array('/index\.php\//','/index\.php/');
        	$replacement = array('','');
        	
        	$url = preg_replace($pattern, $replacement, $url);
        	$a = parse_url($url);
        	$domain = $a['host'];
        	$sql = "select sid from `domain_pointer` where domain='$domain'";
        	return $this->queryScalar($sql);
        }
        public function getHost($type = 'IMG', $ctype = 'FTP',$sid = __SID__){
            $sql = "select * from server_config as a where a.state>-2 and a.server_type='$type' and a.connect_type='$ctype' and a.sid=$sid and a.is_active=1 limit 1";
            $v = $this->queryRow($sql);
            if(empty($v) && $sid == __SID__){
            	return $this->getHost($type,$ctype,0);
            }
            return  $v;
        }
        public function get_host_by_id($id = 0){
        	$sql = "select * from server_config as a where a.state>-2 and a.id=$id limit 1";
        	$v = $this->queryRow($sql);        	 
        	return  $v;
        }
        public function getSCode(){
        	$sql = "select code from shops where id=".(defined(__SID__) ? __SID__ : $this->getSID());
        	return $this->queryScalar($sql);
        }
        public function getText($id=0,$lang=__LANG__ ){
        	$sql="SELECT a.value FROM `text_translate` as a WHERE a.id=$id";
        	$sql.=" AND a.lang='".$lang."'";
        	$v = $this->queryARow($sql);
        	if(!empty($v)){
        		return uh($v['value']);
        	}elseif (__LANG__ != SYSTEM_LANG){
        		//view(Zii::$_isAdmin);
        		return $this->getText($id,SYSTEM_LANG);
        	}
        }
        public function showError(){
        	return false; 
        }
        public function _getConfigs($code = false, $sid = 0 ,$lang = 'vi_VN'){
        	$langx = $lang == false ? 'all' : $lang;
        	$code = $code !== false ? $code : 'SITE_CONFIGS';
        	 
        	$sql = "select a.bizrule from site_configs as a where a.code='$code'";
        	$sql .= " and a.sid=".$sid ;
        	$sql .= $lang !== false ? " and a.lang='$lang'" : '';
        	$l = djson($this->queryScalar($sql),true);
        	 
        	return $l;
        }
        public function close(){
        	mysqli_close($this->link);
        }
}