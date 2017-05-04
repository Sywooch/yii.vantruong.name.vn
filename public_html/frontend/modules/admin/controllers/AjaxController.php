<?php

namespace app\modules\admin\controllers;

use yii\web\Controller;
use common\controllers\CrsController; 
use Yii;
use app\modules\admin\models\AdModule;
use yii\db\Query;
use app\modules\admin\models\Siteconfigs;
 
/**
 * Default controller for the `admin` module
 */
class AjaxController extends CrsController
{
    /**
     * Renders the index view for the module
     * @return string
     */
	public $layout = 'ajax';
	public function actionIndex()
    {    	    
    	//$a = new \app\modules\admin\models\AdModule();
    	//view($a);
		//echo 'ss';
    	return $this->render('index');
    }
    
    public function actionCities(){
    	switch (getParam('action')){
    		case 'load_dia_danh':
    			$l = \app\modules\admin\models\DeparturePlaces::getList([
    				'p'=>1,
    				'limit'=>1000
    			]);
    			if(!empty($l['listItem'])){
    				$r = [];
    				foreach ($l['listItem'] as $k=>$v){
    					$r[$k]['value'] = $v['id'];
    					$r[$k]['text'] = $v['name'];
    					$r[$k]['continent'] = $v['name'];
    				}
    				echo json_encode($r);
    			}
    			break;
    	}
    	/*
    	echo '[ { "value": 1 , "text": "Amsterdam"   , "continent": "Europe"    },
  { "value": 2 , "text": "London"      , "continent": "Europe"    },
  { "value": 3 , "text": "Paris"       , "continent": "Europe"    },
  { "value": 4 , "text": "Washington"  , "continent": "America"   },
  { "value": 5 , "text": "Mexico City" , "continent": "America"   },
  { "value": 6 , "text": "Buenos Aires", "continent": "America"   },
  { "value": 7 , "text": "Sydney"      , "continent": "Australia" },
  { "value": 8 , "text": "Wellington"  , "continent": "Australia" },
  { "value": 9 , "text": "Canberra"    , "continent": "Australia" },
  { "value": 10, "text": "Beijing"     , "continent": "Asia"      },
  { "value": 11, "text": "New Delhi"   , "continent": "Asia"      },
  { "value": 12, "text": "Kathmandu"   , "continent": "Asia"      },
  { "value": 13, "text": "Cairo"       , "continent": "Africa"    },
  { "value": 14, "text": "Cape Town"   , "continent": "Africa"    },
  { "value": 15, "text": "Kinshasa"    , "continent": "Africa"    }
]';*/
    exit;
    }
    public function actionHelps(){
      
    	if($_SERVER['REQUEST_METHOD'] == 'POST'){
    		switch (post('action')){
    			case 'get_item':
    				$id = post('id',0);$rid = post('rid',0);
    				$m = new \app\modules\admin\models\Helps;
    				$v = $m->getItem($id);
    				 
    				$text = '';
    				$text .= '<li><div class="f12e help-detail">'.'<h1 class="help-t-title">'.$v['title'].'</h1>'.uh($v['text']).'</div></li>';
    
    				//
    				$m1 = new \app\modules\admin\models\HelpsCategorys();
    				$lid = $m1->get_all_child_id($rid);
    				$l = $m -> getList(array(
    						'parent_id'=>$lid,'count'=>false,'limit'=>10000,
    						'other'=>array($id),
    						'is_active'=>1
    				));
    				$li = ''; 
    				if(!empty($l['listItem'])){
    					foreach ($l['listItem'] as $k => $v){
    						$li .= '<li title="'.$v['info'].'" class=""><a onclick="return show_help(this);" data-rid="'.$rid.'" data-id="'.$v['id'].'" href="?help_id='.$v['id'].'" class="item-name line-clamp l2">'.($k+1).'. '.$v['title'].'</a></li>';
    					}
    				}
    				$text .= $li;
    				$v['text'] = $text;
    				$v['link'] = '?help_id='.$v['id'];
    				echo json_encode($v,JSON_UNESCAPED_UNICODE);
    				exit;
    				break;
    			case 'get_helps':
    				$id = post('id',0); 
    				$url =post('url');
    				$state = false;
    				$m = new \app\modules\admin\models\Helps(); 
    			 
    				$m1 = new \app\modules\admin\models\HelpsCategorys();
 
    				//
    				$pos = strpos($url, '?');
    				$help_id = 0;
    				if($pos !== false){
    					$param = substr($url, $pos+1);
    					if($param !== false){
    						$param = explode('&', str_replace('&amp;', '&', $param));
    						if(!empty($param)){
    							foreach ($param as $p){
    								if(strpos($p, 'help_id=') !== false){
    									$help_id = substr($p, strpos($p, '=')+1);
    									$help_id = is_numeric($help_id) ? $help_id : 0;
    									break;
    								}
    							}
    						}
    					}
    				}
    				//view($_POST,true);
    				//
    				$li = '';
    				//
    			 
    				if($help_id > 0){
    					$v = $m->getItem($help_id); 
    					$text = '';
    					$text .= '<li><div class="f12e help-detail">'.'<h1 class="help-t-title">'.$v['title'].'</h1>'.uh($v['text']).'</div></li>';
    					 
    					//
    					//$m1 = load_model('helps_categorys');
    					$lid = $m1->get_all_child_id($id); 
    					$l = $m -> getList(array(
    							'parent_id'=>$lid,'count'=>false,'limit'=>10000,
    							'other'=>array($help_id),
    							'is_active'=>1
    					));
    					$li = '';
    					 
    					if(!empty($l['listItem'])){
    						foreach ($l['listItem'] as $k => $v){
    							$v['info'] = isset($v['info']) ? uh($v['info']) : '';
    							 
    							$li .= '<li title="'.$v['info'].'" class=""><a onclick="return show_help(this);" data-rid="'.$id.'" data-id="'.$v['id'].'" href="?help_id='.$v['id'].'" class="item-name line-clamp l2">'.($k+1).'. '.$v['title'].'</a></li>';
    						}
    					} 
    					$text .= $li;
    					$li = $text;
    					$state = true;
    					
    				}else{
    					 
    					$lid = $m1->get_all_child_id($id); 
    					$l = $m -> getList(array(
    							'parent_id'=>$lid,'count'=>false,'limit'=>10000,'is_active'=>1
    					));
    					//
    					
    					if(!empty($l['listItem'])){
    						$state = true;
    						foreach ($l['listItem'] as $k => $v){
    							$li .= '<li title="'.$v['info'].'" class=""><a onclick="return show_help(this);" data-rid="'.$id.'" data-id="'.$v['id'].'" href="?help_id='.$v['id'].'" class="item-name line-clamp l2">'.($k+1).'. '.$v['title'].'</a></li>';
    						}
    					}else{
    						$li .= '<p class="help-block italic f12e">Không tìm thấy dữ liệu</p>';
    					}
    				}
    				echo json_encode(array('text'=>$li,'state'=>$state,));
    				break;
    		}
    	}exit;
    }
    public function actionUpdate(){
    	if(Yii::$app->request->getMethod() == 'POST'){
    		switch(Yii::$app->request->post('action')){
    			case 'Ad_quick_change_config_item':
    				//
    				$post = Yii::$app->request->post();
    				$l = Siteconfigs::getItem($post['key'],__LANG__);
    				$child = $post['child'];
    				$state = false;
    				if(isset($l[$child])){
    					foreach ($l[$child] as $k=>$v){
    						if($v['id'] == $post['id']){
    							$l[$child][$k][$post['field']] = $post['value'];
    							$state = true;
    							break;
    						}
    					}
    					Siteconfigs::updateData($post['key'],$l,__LANG__);
    				}
    				echo json_encode(['hide_class'=>0,'state'=>$state,'status'=>$state]);
    				break;
    			case 'Ad_quick_change_attr':
    				$post = Yii::$app->request->post(); 
    				$con =  ['item_id'=> $post['item_id'],'attr_id'=>$post['attr_id']];
    				$f = [$post['field']=>$post['value']];
    				if((new Query())->from($post['table'])->where($con)->count(1) == 0){
    					Yii::$app->db->createCommand()->insert($post['table'],[$post['field']=>$post['value']]+$con)->execute();
    				}else{
    					Yii::$app->db->createCommand()->update($post['table'],[$post['field']=>$post['value']],$con)->execute();
    				}
    				echo json_encode(['hide_class'=>0,'state'=>true,'status'=>true]);
    				break;
    			case 'Ad_quick_change_item':
    				$post = Yii::$app->request->post();
    				
    				//$id = explode(',', isset($post['id']) ? $post['id'] : '');
    				
    				$identity_field = isset($post['identity_field']) ? $post['identity_field'] : 'id';
    				$identity_value = isset($post['identity_value']) ? $post['identity_value'] : $post['id'];
    				$id = explode(',', $identity_value);
    				switch (post('field_type')){
    					case 'date': case 'time': case 'datetime':
    						$post['value'] = ctime(['string'=>$post['value']]);
    						break;
    				}
    				
    				$state = false;
    				if(!empty($id)){
    					$inserted = false;
    					if(isset($post['insert']) &&  $post['insert'] == 'true'){
    						if((new Query())->from($post['table'])->where([
    								$identity_field=>$id]+(post('lang') != "" ? ['lang'=>post('lang')] : []))->count(1) == 0){
    							$state = Yii::$app->db->createCommand()->insert($post['table'],[
    									$post['field']=>$post['value'],
    									$identity_field=>$id[0]
    									
    							]+(post('lang') != "" ? ['lang'=>post('lang')] : []))->execute();
    							 
    							$inserted = true;
    						}
    					}
    					if(!$inserted){
	    					$state = Yii::$app->db->createCommand()->update($post['table'],[$post['field']=>$post['value']],[
	    							$identity_field=>$id
	    					]+(post('lang') != "" ? ['lang'=>post('lang')] : []))->execute();
    					}
    				} 
    				echo json_encode([
    						'hide_class'=>$id,
    						'state'=>$state>0 ? true : false,
    						'status'=>$state>0 ? true : false,
    						//'p'=>$_POST,
    						//'c'=>$post,
    						//'i'=>$inserted,
    						//'s'=>Yii::$app->db->createCommand()->update($post['table'],[$post['field']=>$post['value']],[
	    					//		'id'=>$id
	    					//]+(post('lang') != "" ? ['lang'=>post('lang')] : []))->getRawSql()
    				]);
    				break;
    			case 'Ad_quick_change_item_user_group':
    				$post = Yii::$app->request->post();
    				$user_id = $post['user_id'];
    				$group_id = $post['group_id'];
    				$state = false;
    				if($user_id>0 && $group_id>0){
    					$state = Yii::$app->db->createCommand()->update($post['table'],[$post['field']=>$post['value']],[
    							'user_id'=>$user_id,'group_id'=>$group_id
    					])->execute();
    				//	view($_POST);
    				}
    				
    				echo json_encode(['hide_class'=>'','state'=>$state>0 ? true : false,'status'=>$state>0 ? true : false]);
    				break;	
    			case 'Ad_quick_change_item_ad_translate':
    				$post = Yii::$app->request->post();
    				$id = post('id'); $lang = post('lang');
    				if($id > 0 && $lang != ""){
    					$ad = new \app\modules\admin\models\AdModule();
    					$url = $ad->getUrl(unMark($post['value']),$id,$lang);
    					$state = true;
    					if(Yii::$app->db->createCommand()->update($post['table'],[$post['field']=>$post['value'],'url'=>$url],[
    								'id'=>$id,'lang'=>$lang
    						])->execute() == 0){
    						$item = $ad->getCItem($id,ADMIN_LANG); 
    						$item['title'] = $post['value'];
    						$item['url'] = $url;
    						$item['lang'] = $lang;    						
    						$state = Yii::$app->db->createCommand()->insert($post['table'],$item)->execute();
    					}else{
    						
    					}
    					
    				}
    				echo json_encode(['hide_class'=>'','state'=>$state>0 ? true : false,'status'=>$state>0 ? true : false]);
    				break;
    		}
    	}
    }
    
    public function actionSelect2_ajax(){
    	return $this->render('select2_ajax', [
    			
    	]);
    }
    public function actionChosen_ajax(){
    	return $this->render('chosen_ajax', [
    			
    	]);
    }
    public function actionDelete()
    {    	
    	if(Yii::$app->request->getMethod() == 'POST'){
    		switch(Yii::$app->request->post('action')){
    			case 'Ad_quick_delete_user_item':
    				$post = Yii::$app->request->post();
    				$id = explode(',', $post['id']);
    				//$state = ;
    				//
    				$state = \app\modules\admin\models\Users::removeUser($id);
    				//
    				echo json_encode(['hide_class'=>$id,'state'=>$state>0 ? true : false,'status'=>$state>0 ? true : false]);
    				break;
    			case 'Ad_quick_delete_config_item':
    				$post = Yii::$app->request->post();
    				$id = explode(',', $post['id']);
    				$state = false;
    				$l = Siteconfigs::getItem($post['key'],__LANG__);
    				$child = $post['child'];
    				$state = false;
    				if(isset($l[$child])){
    					foreach ($l[$child] as $k=>$v){
    						if($v['id'] == $post['id']){
    							unset($l[$child][$k]);
    							$state = true;
    							break;
    						}
    					}
    					Siteconfigs::updateData($post['key'],$l,__LANG__);
    				}    				
    				echo json_encode(['hide_class'=>$id,'state'=>$state ,'status'=>$state ]);
    				break;
    			case 'Ad_quick_delete_item':
    			case 'Ad_quick_change_item':
    				$post = Yii::$app->request->post();
    				//$id = explode(',', $post['id']);
    				$identity_field = isset($post['identity_field']) ? $post['identity_field'] : 'id';
    				$identity_value = isset($post['identity_value']) ? $post['identity_value'] : $post['id'];
    				$id = explode(',', $identity_value);
    				$field_check_index = isset($post['field_check_index']) ? explode(',',$post['field_check_index']) : $id;
    				// Update state
    				$update_state = isset($post['update_state']) && $post['update_state'] == 0 ? false : true;
    				
    				$state = false;
    				if(!empty($id)){
    					if($update_state){
	    					$state = Yii::$app->db->createCommand()->update($post['table'],['state'=>-5],[
	    							$identity_field=>$id
	    					])->execute();
    					}
    					switch (post('controller')){
    						case 'menu':
    							Yii::$app->db->createCommand()->update('slugs',['state'=>-5],[
    									'item_id'=>$id,'item_type'=>0
    							])->execute();
    							break;
    						case 'content':
    							Yii::$app->db->createCommand()->update('slugs',['state'=>-5],[
    									'item_id'=>$id,'item_type'=>1
    							])->execute();
    							break;
    					}
    					if(post('delete_option') == 'remove'){
    						$state = Yii::$app->db->createCommand()->delete($post['table'],[
    								$identity_field=>$id,
    								//'state'=>-5
    						]+($update_state ? ['state'=>-5] : []))->execute();
    					}
    				}
    				echo json_encode(['hide_class'=>$field_check_index,'state'=>$state>0 ? true : false,'status'=>$state>0 ? true : false]);
    				exit;
    				break;
    			case 'Ad_quick_delete_item_tem_to_shop':
    				$post = Yii::$app->request->post();
    				$user_id = explode(',', $post['id']);
    				
    				$state = false;
    				if(!empty($user_id)){
    					$state = Yii::$app->db->createCommand()->delete($post['table'],[
    							'temp_id'=>$user_id,'sid'=>__SID__
    					])->execute();    					
    				}
    				echo json_encode(['hide_class'=>$user_id,'state'=>$state>0 ? true : false,'status'=>$state>0 ? true : false]);
    				break;	
    			case 'Ad_quick_delete_item_user_group':
    				$post = Yii::$app->request->post();
    				$user_id = explode(',', $post['user_id']);
    				//$user_id = $post['user_id'];
    				$group_id = $post['group_id'];
    				$state = false;
    				//view($post,true);exit;
    				$m = new \app\modules\admin\models\Users();
    				$item = $m->getItem($post['user_id'],['group_id'=>$post['group_id']]);
    				if(!empty($item)){
    					$per1 = Yii::$app->authManager->createRole($item['groups'][0]['name']);
    					Yii::$app->authManager->revoke($per1, $user_id[0]); 
    				}
    				if(!empty($user_id)){
    					$state = Yii::$app->db->createCommand()->delete($post['table'],[
    							'user_id'=>$user_id,'group_id'=>$group_id
    					])->execute();
    					//Yii::$app->db->createCommand()->delete('{{%auth_assignment}}',['item_name'=>$item['name']])->execute();
    				}
    				echo json_encode(['hide_class'=>$user_id,'state'=>$state>0 ? true : false,'status'=>$state>0 ? true : false]);
    				exit;
    				break;	
    			case 'Ad_quick_delete_permission_item':
    				$post = Yii::$app->request->post();
    				$id = explode(',', $post['id']);
    				$state = true;
    				$m = new \app\modules\admin\models\Permission();
    				$item = $m->getItem($post['id']); 
    				if(!empty($item)){
    					
    					$auth_item = 'group-'.__SID__.'-'.$post['id'];
    					Yii::$app->db->createCommand()->delete('auth_assignment',[
    							'item_name'=>$item['name'],'user_id'=>(new Query())->select('user_id')->from('user_to_shop')->where(['sid'=>__SID__])
    					])->execute();
    					if($item['name'] == $auth_item){
    						if(!empty(Yii::$app->authManager->getRole($item['name']))){
	    						$per1 = Yii::$app->authManager->createRole($item['name']);
	    						Yii::$app->authManager->remove($per1);
    						}
    					}
    					
    				}
    				$state = Yii::$app->db->createCommand()->delete($post['table'],[
    						'id'=>$id,'sid'=>__SID__
    				])->execute();
    				
    				echo json_encode(['hide_class'=>$id,'state'=>$state>0 ? true : false,'status'=>$state>0 ? true : false]);
    				exit;
    				break;
    		}
    	}
    }
    
}
