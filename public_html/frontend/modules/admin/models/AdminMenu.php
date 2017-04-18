<?php

namespace app\modules\admin\models; 

use Yii;
use yii\helpers\Url;
use app\models\Forms;
use yii\db\Query;
/**
 * This is the model class for table "admin_menu".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $route
 * @property integer $position
 * @property integer $is_active
 * @property integer $type
 * @property integer $level
 * @property integer $is_fix
 * @property string $lang
 * @property string $child_code
 * @property string $url 
 * @property string $check_sum
 * @property string $bizrule
 * @property integer $state
 * @property string $title
 * @property integer $is_all
 * @property integer $is_home
 * @property integer $is_permission
 * @property integer $is_invisibled
 * @property integer $lft
 * @property integer $rgt
 */
class AdminMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'lang'], 'required'],
            [['id', 'parent_id', 'position', 'is_active', 'type', 'level', 'is_fix', 'state', 'is_all', 'is_home', 'is_permission', 'is_invisibled', 'lft', 'rgt'], 'integer'],
            [['bizrule'], 'string'],
            [['route'], 'string', 'max' => 64],
            [['lang'], 'string', 'max' => 6],
            [['url', 'title'], 'string', 'max' => 255],
            [['check_sum'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'route' => Yii::t('app', 'Route'),
            'position' => Yii::t('app', 'Position'),
            'is_active' => Yii::t('app', 'Is Active'),
            'type' => Yii::t('app', 'Type'),
            'level' => Yii::t('app', 'Level'),
            'is_fix' => Yii::t('app', 'Is Fix'),
            'lang' => Yii::t('app', 'Lang'),
            'child_code' => Yii::t('app', 'Code'),
            'url' => Yii::t('app', 'Url'),
            'check_sum' => Yii::t('app', 'Check Sum'),
            'bizrule' => Yii::t('app', 'Bizrule'),
            'state' => Yii::t('app', 'State'),
            'title' => Yii::t('app', 'Title'),
            'is_all' => Yii::t('app', 'Is All'),
            'is_home' => Yii::t('app', 'Is Home'),
            'is_permission' => Yii::t('app', 'Is Permission'),
            'is_invisibled' => Yii::t('app', 'Is Invisibled'),
            'lft' => Yii::t('app', 'Lft'),
            'rgt' => Yii::t('app', 'Rgt'),
        ];
    }
    public static function get_bread_crumbs(){     
    	if(defined('CONTROLLER_RGT')){
    	$l = static::find()
    	->select(['title','url','route'])
    	->where(
    			['<=','lft',CONTROLLER_LFT]
    			//
    			)
    			->andWhere(['lang'=>ADMIN_LANG,'is_active'=>1])
    			->andWhere(['>=','rgt',CONTROLLER_RGT])
    	 
    			//->andWhere(['not in','id',(new Query())->select('module_id')->from('temp_to_modules')->where(['temp_id' => __TCID__,'type_id'=>1])])
    			->orderBy(['lft'=>SORT_ASC])
    			->asArray()->all();
    	
		$r = '';    			
    	if(!empty($l)){
    		$r .= '<div class="tree_bar fl100"><ul class="Breadcrumb ul_tree_bar inline style-none"><li><a href="'.(__IS_ADMIN__ && !__DOMAIN_ADMIN__ ? cu([DS.Yii::$app->controller->module->id]) : cu('/')).'">'.Yii::t('yii', 'Home').'</a></li>';
    		foreach ($l as $k=>$v){
				$r .= '<li><a href="'.($v['route'] == '#' ? '#' : cu([ $v['url'].DS])).'">'.uh($v['title']).'</a></li>';
    		}
    		//
    		if(getParam('type') != ""){
    			//$x = Yii::$app->db->createCommand("SELECT * FROM {{%forms}} where code='".getParam('type')."'")->queryOne();
    			$x = (new Query())->from('{{%forms}}')->where(['code'=>getParam('type')])->one(); 
    			if(!empty($x)){
    				$r .= '<li><a href="#">'.uh($x['title']).'</a></li>';
    			}
    		}
    		//
    		$r .= '</ul></div>';
    	}
    	return $r;
    	}
    }
    
    public static function getAllParents($id){
    	$item = self::getItem($id);
    	if(!empty($item)){
    		$l = static::find()
    		->select(['title','url','route'])
    		->where(
    				['<=','lft',$item['lft']]
    				//
    				)
    				->andWhere(['lang'=>ADMIN_LANG,'is_active'=>1])
    				->andWhere(['>=','rgt',$item['rgt']])
    
    				//->andWhere(['not in','id',(new Query())->select('module_id')->from('temp_to_modules')->where(['temp_id' => __TCID__,'type_id'=>1])])
    		->orderBy(['lft'=>SORT_ASC])
    		->asArray()->all();
    		 
    		 
    		return $l;
    	}
    }
    
    public static function get_menu_link($param, $code = '',$absolute = false) {
    	$sql = "select a.url from ".self::tableName()." as a where a.is_active=1 and a.lang='".ADMIN_LANG."'";
    	$sql .= is_numeric($param) ? " and a.id=$param" : " and a.route='$param'";
    	$sql .= $code != "" ? " and a.child_code='$code'" : " and a.child_code in('','#')";
    	//view($sql);
    	return cu([Yii::$app->db->createCommand($sql)->queryScalar().DS],$absolute);
    }
    public static function update_all_level_menu($parent_id=0){
    	$l = static::find()
    	->where(['parent_id'=>$parent_id])
    	//->andWhere(['>','state',-2])
    	->asArray()->all();
    	if(!empty($l)){
    		foreach ($l as $k=>$v){
    			//$l1 = static::find()
    			//->where(['parent_id'=>$parent_id])
    			//->andWhere(['>','state',-2])
    			//->asArray()->all();
    			Yii::$app->db->createCommand("update admin_menu set `level`=".($v['level']+1)." where parent_id=".$v['id'])-> execute();
    			self::update_all_level_menu($v['id']);
    		}
    	}
    }
    public static function getList(){
    	$l = static::find()
    	->where(['lang'=>ADMIN_LANG])
    	->andWhere(['>','state',-2])
    	->orderBy(['lft'=>SORT_ASC,'title'=>SORT_ASC])->asArray()->all();
    	return $l;
    }
    public static function getItem($id = 0){
    	$l = static::find()
    	->where(['id'=>$id, 'lang'=>ADMIN_LANG])
    	->andWhere(['>','state',-2])
    	//->orderBy(['lft'=>SORT_ASC,'title'=>SORT_ASC])
    	->asArray()->one();
    	return $l;
    }
    public static function get_all_child_id($id = 0, $include_id = true){
    	$v = self::getItem($id);
    	$r = $include_id ? [$id] : [];
    	if(!empty($v)){
    		$l = static::find()
    		->where(['lang'=>ADMIN_LANG])
    		->andWhere(['>','state',-2])
    		->andWhere(['>','lft',$v['lft']])
    		->andWhere(['<','rgt',$v['rgt']])
    		//->orderBy(['lft'=>SORT_ASC,'title'=>SORT_ASC])
    		->asArray()->all();
    		if(!empty($l)){
    			foreach ($l as $k=>$v){
    				$r[] = $v['id'];
    			}
    		}
    	}
    	return $r;
    }
    public static function getUserFormID(){
    	$query = static::find()->from('module_to_group')->select('module_id')->where([
    			'group_id'=>self::getUserGroups(Yii::$app->user->id),'type'=>2
    	])->asArray()->all();
    	$r = [];
    	if(!empty($query)){
    		foreach ($query as $k=>$v){
    			$r[] = $v['module_id'];
    		}
    	}
    	return $r;
    }
    public static function getUserModulesID($type = 0){
    	$query = static::find()->from('module_to_group')->select('module_id')->where([
    			'group_id'=>self::getUserGroups(Yii::$app->user->id),'type'=>$type
    	])->asArray()->all();
    	//var_dump(self::getUserGroups(Yii::$app->user->id));
    	$r = [];
    	if(!empty($query)){
    	foreach ($query as $k=>$v){
    		$r[] = $v['module_id'];
    	}
    	}
    	return $r;
    }
    public static function getUserGroups($id){
    	$r = [];
    	//var_dump(Yii::$app->authManager->getRolesByUser($id));
    	foreach (Yii::$app->authManager->getRolesByUser($id) as $k=>$v){
    		$r[] = (new Query())->select('id')->from('user_groups')->where(['name'=>$k])->scalar();
    	}
    	return $r;
    }
    public static function get_all_menu(){
    	$menuItems = []; 
    	$m = '';
    	$modules = self::getUserModulesID();    
    	//var_dump($modules);
    	$forms = self::getUserModulesID(2);
    	$sites = self::getUserModulesID(1);
    	$query = static::find()
    	->from (['a'=>self::tableName()])
    	->select(['a.*',"(select count(1) from ".self::tableName()." where state>-2 and lft>".self::tableName().".lft and rgt<".self::tableName().".rgt and lang='".ADMIN_LANG."') as count_child"])
    	->where(['or',['is_all'=>1],['in','id',(new Query())->select('module_id')->from('temp_to_modules')->where(['temp_id' => __TCID__,'type_id'=>0])]])
    			->andWhere(['parent_id'=>0,'lang'=>ADMIN_LANG,'is_invisibled'=>0,'is_active'=>1])
    			->andWhere(['>','state',-2])
    			->andWhere(['type'=>Yii::$app->user->can(ROOT_USER) ? [0,1,2,3] : [0,1,2]])
    			->andWhere(['not in','id',(new Query())->select('module_id')->from('temp_to_modules')->where(['temp_id' => __TCID__,'type_id'=>1])]);
		if(!Yii::$app->user->can([ROOT_USER,ADMIN_USER])){
			$query->andWhere(['or',['a.is_permission'=>0],['a.id'=>$modules]]);
			//var_dump($query->createCommand()->getSql());
		}
    	$l = $query->orderBy(['position'=>SORT_ASC,'title'=>SORT_ASC])->asArray()->all();
    	$query = Forms::find()
    	->where(['in', 'id', (new Query())->select('form_id')->from('form_to_temps')->where(['temp_id' => __TCID__,'type_id'=>0])]);
    	$query->andWhere(['is_active'=>1]);
    	if(!Yii::$app->user->can([ROOT_USER,ADMIN_USER])){
    		$query->andWhere(['id'=>$forms]);
    		//var_dump($query->createCommand()->getSql());
    	}
    	$f = $query->orderBy(['position'=>SORT_ASC])
    	->asArray()->all();
    	
    	if(!empty($l)){
    		$m .= '<div class="row"><ul class="nav navbar-nav superfish style-none sf-menu main-menu">';
    		foreach ($l as $k=>$v){
    			switch ($v['route']){
    				case '#': $link = '#'; break;
    				case '/': $link = ADMIN_ADDRESS; break;
    				default: $link = cu([ $v['url'].DS]); break;
    			}    			   			
    			 
    			$m .= '<li><a href="'.$link.'">'.(isset($v['icon_class']) ? '<i class="fa '.$v['icon_class'].'"></i>' : '').''.$v['title'].'</a>';
    			
    			$query = static::find()
    			->select(['a.*',"(select count(1) from ".self::tableName()." where state>-2 and lft>a.lft and rgt<a.rgt and lang='".ADMIN_LANG."') as count_child"])
    			->from (['a'=>self::tableName()])
    			->where(
    					['or',['is_all'=>1],['in','id',(new Query())->select('module_id')->from('temp_to_modules')->where(['temp_id' => __TCID__,'type_id'=>0])]]
    					//
    					)
    					->andWhere(['parent_id'=>$v['id'],'lang'=>ADMIN_LANG,'is_invisibled'=>0,'is_active'=>1])
    					->andWhere(['>','state',-2])
    					->andWhere(['type'=>Yii::$app->user->can(ROOT_USER) ? [0,1,2,3] : [0,1,2]])
    					->andWhere(['not in','id',(new Query())->select('module_id')->from('temp_to_modules')->where(['temp_id' => __TCID__,'type_id'=>1])]);
    					if(!Yii::$app->user->can([ROOT_USER,ADMIN_USER])){
    						$query->andWhere(['or',['a.is_permission'=>0],['a.id'=>$modules]]);
    					}
    					$l1 = $query->orderBy(['position'=>SORT_ASC,'title'=>SORT_ASC])->asArray()->all();
    			 
    			if(!empty($l1)){
    				$m .= '<ul>';
    				foreach ($l1 as $k1=>$v1){
    					switch ($v1['route']){
    						case '#': $link = '#'; break;
    						case '/': $link = ADMIN_ADDRESS; break;
    						case 'menu': case 'content': $link = '#'; break;
    						default: $link = cu([$v1['url'].DS]); break;
    					}
    					
    					$m .= '<li><a href="'.$link.'">'.(isset($v1['icon_class']) ? '<i class="fa '.$v1['icon_class'].'"></i>' : '').''.$v1['title'].'</a>';
    					//
    					//
    					 
    					switch($v1['type']){
    						case 1: // menu
    						case 2: // Content
    							 
    							if(!empty($f)){
    								$m .= '<ul class="ul-type-'.$v1['type'].'">';
    								if($v1['type']==1){
    									if(Yii::$app->user->can([ADMIN_USER,ROOT_USER])){
    										$link =  cu([ $v1['url'].DS]);
    										$m .= '<li><a class="iconv2" href="'.$link.'"><i class="fa fa-list-ul"></i>';
    										$m .= 'Toàn bộ danh mục';
    										$m .= '</a></li>';
    									}
    								}
    								foreach($f as $kl=>$vl){
    									//if(checkPermission($vl['id'],2)){
    									   
    										if(($vl['is_sub']== 1 && $v1['type']==1) || ($vl['is_content'] == 1) ){
    											$link =  cu([$v1['url'].DS,'type'=>($vl['code'] == "" ? 'other' : $vl['code'])]);
    											$m .= '<li><a href="'.$link.'" class="iconv2"><i class="fa ft fa-list"></i>';
    											switch (ADMIN_LANG){
    												case 'vi_VN':
    													$m .= 'Danh mục '.strtolower($vl['title']) ; 
    													//$m .=  $vl['ctitle'] . ' categorys';
    													break;
    												default:
    														
    													$m .=  $vl->ctitle . ' categorys';
    													break;
    											}
    											$m .= '</a></li>';
    										}
    									//}
    								}
    								$m .= '</ul>';
    							}
    							break;
    					}
    					//	
    					//
    					$query = static::find()
    					->select(['a.*',"(select count(1) from ".self::tableName()." where state>-2 and lft>a.lft and rgt<a.rgt and lang='".ADMIN_LANG."') as count_child"])
    					->from (['a'=>self::tableName()])
    					->where(
    							['or',['is_all'=>1],['in','id',(new Query())->select('module_id')->from('temp_to_modules')->where(['temp_id' => __TCID__,'type_id'=>0])]]
    					//		//
    							)
    							->andWhere(['parent_id'=>$v1['id'],'lang'=>ADMIN_LANG,'is_invisibled'=>0,'is_active'=>1])
    							->andWhere(['>','state',-2])
    							->andWhere(['type'=>Yii::$app->user->can(ROOT_USER) ? [0,1,2,3] : [0,1,2]])
    							->andWhere(['not in','id',(new Query())->select('module_id')->from('temp_to_modules')->where(['temp_id' => __TCID__,'type_id'=>1])]);
    							if(!Yii::$app->user->can([ROOT_USER,ADMIN_USER])){
    								$query->andWhere(['or',['a.is_permission'=>0],['a.id'=>$modules]]);
    							}
    							$l2 = $query->orderBy(['position'=>SORT_ASC,'title'=>SORT_ASC])->asArray()->all();
    					
    					if(!empty($l2)){
    						$m .= '<ul>';
    						foreach ($l2 as $k2=>$v2){
    							switch ($v2['route']){
    								case '#': $link = '#'; break;
    								case '/': $link = ADMIN_ADDRESS; break;
    								default: $link = cu([ $v2['url'].DS]); break;
    							}
    							 
    							$m .= '<li><a class="iconv2" href="'.$link.'">'.(isset($v2['icon_class']) ? '<i class="fa '.$v2['icon_class'].'"></i>' : '').''.$v2['title'].'</a>';
    							$query = static::find()
    							->select(['a.*',"(select count(1) from ".self::tableName()." where state>-2 and lft>a.lft and rgt<a.rgt and lang='".ADMIN_LANG."') as count_child"])
    							->from (['a'=>self::tableName()])
    							->where(
    									['or',['is_all'=>1],['in','id',(new Query())->select('module_id')->from('temp_to_modules')->where(['temp_id' => __TCID__,'type_id'=>0])]]
    									)
    									->andWhere(['parent_id'=>$v2['id'],'lang'=>ADMIN_LANG,'is_invisibled'=>0,'is_active'=>1])
    									->andWhere(['>','state',-2])
    									->andWhere(['type'=>Yii::$app->user->can(ROOT_USER) ? [0,1,2,3] : [0,1,2]])
    									->andWhere(['not in','id',(new Query())->select('module_id')->from('temp_to_modules')->where(['temp_id' => __TCID__,'type_id'=>1])]);
    									if(!Yii::$app->user->can([ROOT_USER,ADMIN_USER])){
    										$query->andWhere(['or',['a.is_permission'=>0],['a.id'=>$modules]]);
    									}
    									$l3 = $query->orderBy(['position'=>SORT_ASC,'title'=>SORT_ASC])->asArray()->all();
    								
    							if(!empty($l3)){
    								$m .= '<ul>';
    								foreach ($l3 as $k3=>$v3){
    									switch ($v3['route']){
    										case '#': $link = '#'; break;
    										case '/': $link = ADMIN_ADDRESS; break;
    										default: $link = cu([ $v3['url'].DS]); break;
    									}
    									 
    									$m .= '<li><a class="iconv2" href="'.$link.'">'.(isset($v3['icon_class']) ? '<i class="fa '.$v3['icon_class'].'"></i>' : '').''.$v3['title'].'</a>';
    									//
    									$query = static::find()
    									->select(['a.*',"(select count(1) from ".self::tableName()." where state>-2 and lft>a.lft and rgt<a.rgt and lang='".ADMIN_LANG."') as count_child"])
    									->from (['a'=>self::tableName()])
    									->where(
    											['or',['is_all'=>1],['in','id',(new Query())->select('module_id')->from('temp_to_modules')->where(['temp_id' => __TCID__,'type_id'=>0])]]
    											//
    											)
    											->andWhere(['parent_id'=>$v3['id'],'lang'=>ADMIN_LANG,'is_invisibled'=>0,'is_active'=>1])
    											->andWhere(['>','state',-2])
    											->andWhere(['type'=>Yii::$app->user->can(ROOT_USER) ? [0,1,2,3] : [0,1,2]])
    											->andWhere(['not in','id',(new Query())->select('module_id')->from('temp_to_modules')->where(['temp_id' => __TCID__,'type_id'=>1])]);
    											if(!Yii::$app->user->can([ROOT_USER,ADMIN_USER])){
    												$query->andWhere(['or',['a.is_permission'=>0],['a.id'=>$modules]]);
    											}
    											$l4 = $query->orderBy(['position'=>SORT_ASC,'title'=>SORT_ASC])->asArray()->all();
    										
    									if(!empty($l4)){
    										$m .= '<ul>';
    										foreach ($l4 as $k4=>$v4){
    											switch ($v4['route']){
    												case '#': $link = '#'; break;
    												case '/': $link = ADMIN_ADDRESS; break;
    												default: $link = cu([ $v4['url'].DS]); break;
    											}
    											
    											$m .= '<li><a class="iconv2" href="'.$link.'">'.(isset($v4['icon_class']) ? '<i class="fa '.$v4['icon_class'].'"></i>' : '').''.$v4['title'].'</a>';
    											//
    									
    											//
    											$m .= '</li>'; // li2
    										}
    										$m .= '</ul>';
    									}
    									//
    									$m .= '</li>'; // li2
    								}
    								$m .= '</ul>';
    							}
    							//
    							$m .= '</li>'; // li2
    						}
    						$m .= '</ul>';
    					}
    					//
    					$m .= '</li>'; // li1
    				}
    				$m .= '</ul>';
    			} 
    			 
    			
    			
    			$m .= '</li>'; // Li 0
    		}
    		$m .= '</ul></div>';
    	}
    	return $m;
    }
}
