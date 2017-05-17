<?php

/* @var $this \yii\web\View */ 
/* @var $content string */ 
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use app\modules\admin\models\AdminMenu;
use yii\helpers\Url;
use frontend\assets\AdminAsset;
use app\modules\admin\models\AdLanguage;
AdminAsset::register($this);
$this->title = 'Quản trị hệ thống ';
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<meta charset="<?= Yii::$app->charset ?>">
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="nofollow" name="robots"/>
<meta http-equiv="content-type" content="application/vnd.ms-excel; charset=utf-8">
<meta http-equiv="Content-Language" content="vi" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="language" content="vietnamese" />
<meta name="robots" content="noindex,nofollow">
<link rel="shortcut icon" href="<?php echo isset(Yii::$site['logo']['favicon']['image']) ? Yii::$site['logo']['favicon']['image'] : ''; ?>" type="image/x-icon" />
<?= Html::csrfMetaTags() ?>
<title><?= Html::encode($this->title) ?></title>
<?php $this->head() ?>
</head>
<body>
<?php 
if(Yii::$app->controller->id == 'default'){
$adverts = Yii::$app->zii->getAdvert(array('code'=>'ADV_NEWYEAR','is_all'=>1));
//var_dump($adverts);
if(!empty($adverts)){
	echo '<div class="index_adv_top_box">';
	foreach ($adverts as $adv){
		if($adv['html'] != ""){
			echo uh($adv['html']);
		}else
			echo '<a href="'.$adv['link'].'" class="adv_item"><img src="'.getImage(['src'=>$adv['image'],'output'=>'src','w'=>1920]).'" alt=""/></a>';
	}
	echo '</div>';
}
 
$adverts = Yii::$app->zii->getAdvert(array('code'=>'ADV_NEWYEAR_BOTTOM' ,'is_all'=>1));
//var_dump($adverts);
if(!empty($adverts)){
	echo '<div class="index_adv_bottom_box">';
	foreach ($adverts as $adv){
		if($adv['html'] != ""){
			echo uh($adv['html']);
		}else
			echo '<a href="'.$adv['link'].'" class="adv_item"><img src="'.getImage(['src'=>$adv['image'],'output'=>'src','w'=>1920]).'" alt=""/></a>';
	}
	echo '</div>';
}
}
?>
<div class="bs-main-content">
<?php $this->beginBody() ?>

<div class="header no-print" id="header">
<div class="top_nav">
          <div class="nav_menu">
           
     <?php
  
    NavBar::begin([
        'brandLabel' => false,
        //'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'nav-w100',
        		 
        ],
    	'renderInnerContainer'=>false,
    	'containerOptions'=>['class'=>'main-menu']
    ]);     
    echo AdminMenu::get_all_menu();
    NavBar::end();
    ?> 
     </div>
     <?php
     $l = array();
       
       
       if(!empty($l)){
        echo '<div class="tree_bar fl100"><ul class=" Breadcrumb  ul_tree_bar inline style-none">';
         echo '<li><a href="'.ADMIN_ADDRESS.'">'.getTextTranslate(37,ADMIN_LANG).'</a></li>';
         foreach($l as $k=>$v){
           if($v['title'] != '&nbsp;') echo '<li><a href="#">'.$v['title'].'</a></li>';
         }
         if(in_array(__CONTROLLER__,array('menu','content'))){
         $param = getParam('type') ;
         if($param != ""){
			switch (__LANG__){
				case 'en_US':
					
					echo '<li><a href="#">'.$param.' categorys</a></li>';
					break;
				default: 
					echo '<li><a href="#">Danh mục '. $param.'</a></li>';
					break;
			}
              
         }
       }echo '</ul></div>';
       }              
     ?> 
 
    
      
<ul class="nav navbar-nav notification_nav">
                

                <li data-role="presentation" class="dropdown notification messages hide">
                  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-comments-o badge-0"></i>
                    <span class="badge badge-0 bg-red">0</span>
                  </a>
                  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" data-role="menu">
                    <li>
                      <a>
                        <span class="image"><img src="<?php echo __RSDIR__;?>/images/noimage.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <a>
                        <span class="image"><img src="<?php echo __RSDIR__;?>/images/noimage.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <a>
                        <span class="image"><img src="<?php echo __RSDIR__;?>/images/noimage.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <a>
                        <span class="image"><img src="<?php echo __RSDIR__;?>/images/noimage.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <div class="text-center w100">
                        <a>
                          <strong>Xem thêm tin nhắn</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                      </div>
                    </li>
                  </ul>
                </li>
             
               <li onclick="get_notifications(this)" data-toggle="tooltip" data-placement="bottom" title="Thông báo" class="dropdown item-notifications notification n-alert">
                  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i data-role="presentation" class="fa fa-bullhorn badge-0"></i>
                    <span class="badge alert-count badge-0 bg-red"></span>
                  </a>
                  <ul class="dropdown-menu list-unstyled msg_list" data-role="menu"><li class="ajax-loading-data">Đang tải dữ liệu</li></ul>
                  
                </li> 
                <li data-toggle="tooltip" data-placement="bottom" title="Tài khoản" data-role="presentation" class="dropdown  notification">
                  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-user badge-0"></i>
                    <span class="badge bg-red hide">0</span>
                  </a>
                  <ul class="dropdown-menu list-unstyled msg_list " data-role="menu">
                    <li class="">
                      <a href="<?php echo AdminMenu::get_menu_link('settings','profile');?>">
                        <span class="image"><?php echo getImage(array('src'=>isset(Yii::$app->session['config']['adLogin']['avatar']) ? Yii::$app->session['config']['adLogin']['avatar'] : NO_IMAGE,'w'=>30));?></span>
                        <span>
                          <span>Thông tin tài khoản</span>
                          <span class="time"></span>
                        </span>
                        <span class="message italic">
                          Thay đổi thông tin tài khoản, mật khẩu,...
                        </span>
                      </a>
                    </li>
                    <li>
                      <a href="<?php echo AdminMenu::get_menu_link('settings','setting');?>?tab=setting">
                        <span class="image"><img src="<?php echo __RSDIR__;?>/images/settings_30x30.png" alt="" /></span>
                        <span>
                          <span>Cài đặt hệ thống</span>
                          <span class="time"></span>
                        </span>
                        <span class="message italic">
                          Thiết lập hệ thống
                        </span>
                      </a>
                    </li>
                     
                    <li>
                      <div class="text-center w100">
                      <?php 
                      echo Html::beginForm(['logout/'], 'post')
            . Html::submitButton(
                '<strong>Đăng xuất</strong>
                          <i class="fa fa-sign-out"></i>',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm();
                      ?> 
                      </div>
                    </li>
                  </ul>
                </li>
                 <li data-role="presentation" class="dropdown notification">
                  <a href="javascript:;" class="dropdown-toggle info-number sicon-language" data-toggle="dropdown" aria-expanded="false">
                    <i class="flag <?php echo __LANG__;?> badge-0"></i>
                    <span class="badge bg-red hide">0</span>
                  </a>
                  <ul class="dropdown-menu list-unstyled msg_list language sicon-language" data-role="menu">
                  <?php 
     $cfg = AdLanguage::getList(['is_active'=>1]);
     if(!empty($cfg)){
     	// view($cfg,true);
     	foreach ($cfg as $x){
     		
     		$x['name'] = isset($x['title']) ? $x['title'] : (isset($x['name']) ? $x['name'] : ""); 
     		if(count($cfg) ==1 || (isset($x['is_active']) && $x['is_active'] == 1 && $x['code'] != __LANG__)){
     			echo '<li class="">
                      <a onclick="changeLanguage(\''.$x['code'].'\',this)" data-lang="'.__LANG__.'" rel="nofollow" class="flag '.$x['code'].'">
                        <span class="image  "></span>
                        <span>
                          <span>'.$x['name'].'</span>
                          <span class="time"></span>
                        </span>
                       
                      </a>
                    </li>';
     			//echo '<li><a onclick="changeLanguage(\''.$x['code'].'\',this)" data-lang="'.__LANG__.'" rel="nofollow" class="flag '.$x['code'].'">'.$x['name'].'</a></li>';
     		}
     	}
     	 
     }
     ?>
                    
                
                     
                  </ul>
                </li>
              </ul>
              
           
</div>
<?php
echo '<div data-time="'.SHOP_TIME_LEFT.'" data-alert="'.date('d/m/Y',strtotime(SHOP_TIME_LIFE)).'" class="progcess-bar-life-time"></div>';
//echo '';
echo AdminMenu::get_bread_crumbs();
//
echo '<div class="sys-alert-time-left" data-time="'.SHOP_TIME_LEFT.'" data-alert="'.date('d/m/Y',strtotime(SHOP_TIME_LIFE)).'"></div>';
//var_dump(Yii::$app->session['config']);
switch (Yii::$app->controller->action->id){
	case 'add':case 'create':case 'edit': case 'update':
		echo '<div class="list-btn-hd"><div class="list-btn aright">';
		if(isset(Yii::$_category['configs']['module']['edit_button']['print']) && Yii::$_category['configs']['module']['edit_button']['print'] == 1){
			echo '<a href="'.cu([ __RCONTROLLER__ . DS]).'/print'.afGetUrl().'" class="btn btn-add btn-sm btn-warning"><i class="glyphicon glyphicon-print"></i> In</a>';
		}
		if(isset(Yii::$_category['configs']['module']['edit_button']['add']) && Yii::$_category['configs']['module']['edit_button']['add'] == 1){
			echo '<a href="'.cu([ __RCONTROLLER__]).'/add'.afGetUrl().'" class="btn btn-add btn-sm btn-success"><i class="glyphicon glyphicon-plus"></i> Thêm mới</a>';
		}
		if(isset(Yii::$_category['configs']['module']['edit_button']['save']) && Yii::$_category['configs']['module']['edit_button']['save'] == 1){
			echo '<button type="button" data-target="#editFormContent" onclick="submitForm(this);" class="btn btn-sm btn-success submitForm submitFormBtn" data-role="1"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		}
		if(isset(Yii::$_category['configs']['module']['edit_button']['save_add']) && Yii::$_category['configs']['module']['edit_button']['save_add'] == 1){
			echo '<button type="button" data-target="#editFormContent" onclick="submitForm(this);" class="btn btn-sm btn-success submitForm submitFormBtn" data-role="2"><i class="glyphicon glyphicon-file"></i> Lưu lại & Thêm mới</button>';
		}
		if(isset(Yii::$_category['configs']['module']['edit_button']['save_copy']) && Yii::$_category['configs']['module']['edit_button']['save_copy'] == 1){
			echo '<button type="button" data-target="#editFormContent" onclick="submitForm(this);" class="btn btn-sm btn-success submitForm submitFormBtn" data-role="3"><i class="glyphicon glyphicon-copy"></i> Lưu lại & Copy</button>';
		}
		if(isset(Yii::$_category['configs']['module']['edit_button']['save_close']) && Yii::$_category['configs']['module']['edit_button']['save_close'] == 1){
			echo '<button type="button" data-target="#editFormContent" onclick="submitForm(this);" class="btn btn-sm btn-success submitForm submitFormBtn" data-role="4"><i class="glyphicon glyphicon-ok"></i> Lưu lại & Đóng</button>';
		}
		echo '<div class="btn-export-wtime inline-block"></div>';
		if(isset(Yii::$_category['configs']['module']['edit_button']['cancel']) && Yii::$_category['configs']['module']['edit_button']['cancel'] == 1){
			echo '<button type="button" data-target="#editFormContent" onclick="submitForm(this);" class="btn btn-sm btn-danger submitForm" data-role="5"><i class="glyphicon glyphicon-share-alt"></i> Quay lại</button>';
		}
		echo '</div></div>';
		break;
		case 'index':
			 
			if(isset(Yii::$_category['configs']['module']['index_button'])){
				echo '<div class="list-btn-hd"><div class="list-btn aright">';
				if(isset(Yii::$_category['configs']['module']['index_button']['save']) && Yii::$_category['configs']['module']['index_button']['save'] == 1){
					//view(self::$_category['configs']);
					echo '<button type="button" data-target="#editFormContent" onclick="submitForm(this);" class="btn btn-sm btn-success submitForm submitFormBtn" data-role="1"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
				}
				if(isset(Yii::$_category['configs']['module']['index_button']['add_ajax']) && Yii::$_category['configs']['module']['index_button']['add_ajax'] == 1){
					echo '<a '.showJqueryAttr($_GET).' data-title="Thêm mới '.(strtolower(__CATEGORY_NAME__)).'" data-action="ajax-form-add-new" data-class="w90" data-controller-id="'.CONTROLLER_ID.'" data-controller-text="'.CONTROLLER_TEXT.'" data-controller="'.(Yii::$app->controller->id).'" onclick="open_ajax_modal(this);return false;" data-href="'.cu([ __RCONTROLLER__."/add"]).'" class="btn btn-add btn-sm btn-success"><i class="glyphicon glyphicon-plus"></i> Thêm mới</a>';
				}
				if(isset(Yii::$_category['configs']['module']['index_button']['add']) && Yii::$_category['configs']['module']['index_button']['add'] == 1){
					echo '<a href="'.cu([ __RCONTROLLER__."/add"]+$_GET).'" class="btn btn-add btn-sm btn-success"><i class="glyphicon glyphicon-plus"></i> Thêm mới</a>';
				}
				if(isset(Yii::$_category['configs']['module']['index_button']['cancel']) && Yii::$_category['configs']['module']['index_button']['cancel'] == 1){
					echo '<a href="'.cu([ __RCONTROLLER__]).'" class="btn btn-add btn-sm btn-danger"><i class="glyphicon glyphicon-share-alt"></i> Quay lại</a>';
				}
				if(isset(Yii::$_category['configs']['module']['index_button']['home']) && Yii::$_category['configs']['module']['index_button']['home'] == 1){
					echo '<a href="'.ADMIN_ADDRESS.'" class="btn btn-warning  btn-sm"><i class="glyphicon glyphicon-home"></i> Về trang chủ</a>';
				}
				  
				echo '</div></div>';
			}
			//
			//view(cu([__RCONTROLLER__.DS]));
			if(isset(Yii::$_category['configs']['module']['search_form']['state']) && Yii::$_category['configs']['module']['search_form']['state'] == 1){
			
				 
				echo '<div class="bs-docs-example tooltip-demo fl100">
<form data-module="'.Yii::$app->controller->module->id .'" class="form-inline frm_quick_filter" method="get" action="'.cu([__RCONTROLLER__.DS]).afGetUrl().'">'.(getParam('type') != "" ? '<input type="hidden" name="type" value="'.getParam('type').'" /> ' : '');
				if(isset(Yii::$_category['configs']['module']['search_form']['text']) && Yii::$_category['configs']['module']['search_form']['text'] == 1){
					echo '<div class="form-group group-filter-text">
    <input type="text" name="filter_text" value="'.getParam('filter_text').'" class="form-control txt_filter_text" placeholder="'.getTextTranslate(41,ADMIN_LANG).'">
  </div>';
				}
				if(isset(Yii::$_category['configs']['module']['search_form']['all_category']) && Yii::$_category['configs']['module']['search_form']['all_category'] == 1){
					$_all_categorys = app\modules\admin\models\Menu::getAll(getParam('type'));
					if(!empty($_all_categorys)){
						 
						echo '<div style="min-width:200px" class="form-group form-group-search mgl-1 group-sm30"><select name="category_id" class="select2 input-sm form-control">';
						echo '<option value="0"> - '.getTextTranslate(115,ADMIN_LANG).' - </option>';
						foreach ($_all_categorys as $k=>$v){
							echo '<option '.(getParam('category_id') == $v['id'] ? 'selected' : '').' value="'.$v['id'].'">'.spc($v['level']).uh($v['title']).'</option>';
						}
						echo '</select></div>';
					}
				}
			
				if(isset(Yii::$_category['configs']['module']['search_form']['local_id']) && Yii::$_category['configs']['module']['search_form']['local_id'] == 1){
			
					echo '<div class="form-group form-group-search mgl-1 w200p group-sm30"><select name="local_id" class="hide ajax-chosen-select-ajax" data-role="load_locatition" style="width:100%">';
					echo '<option value="0"> - Tỉnh thành - </option>';
					if(getParam('local_id')>0){
						//$m = load_model("departure_places");
						//$item = $m->getItem(getParam('local_id'));
						$item = \app\modules\admin\models\Local::getItem(getParam('local_id'));
						if(!empty($item)){
							echo '<option selected value="'.$item['id'].'">'.showLocalName(uh($item['name']),$item['type_id']).'</option>'; 
						}
					}
					//foreach ($_all_categorys as $k=>$v){
					//	echo '<option '.(getParam('categoryID') == $v['id'] ? 'selected' : '').' value="'.$v['id'].'">'.$spc[$v['level']].uh($v['title']).'</option>';
					//}
					//echo '<option></option>';
					echo '</select></div>';
			
				}
			
				if(isset(Yii::$_category['configs']['module']['search_form']['place_id']) && Yii::$_category['configs']['module']['search_form']['place_id'] == 1){
			
					echo '<div class="form-group form-group-search mgl-1 w200p group-sm30"><select name="place_id" class="js-select-data-ajax form-control select2-hidden-accessible" data-role="load_dia_danh" style="width:100%">';
					echo '<option value="0"> - Địa danh - </option>';
					if(getParam('place_id')>0){
						$m = load_model("departure_places");
						$item = $m->getItem(getParam('place_id'));
						if(!empty($item)){
							echo '<option selected value="'.$item['id'].'">'.uh($item['name']).'</option>';
						}
					}
					//foreach ($_all_categorys as $k=>$v){
					//	echo '<option '.(getParam('categoryID') == $v['id'] ? 'selected' : '').' value="'.$v['id'].'">'.$spc[$v['level']].uh($v['title']).'</option>';
					//}
					//echo '<option></option>';
					echo '</select></div>';
			
				}
				if(isset(Yii::$_category['configs']['module']['search_form']['attr']) && Yii::$_category['configs']['module']['search_form']['attr'] == 1){
					switch (__CONTROLLER__){
						case 'vehicles_makers':
							$_all_attrs = array(
							array('name'=>6,'title'=>'Ô tô'),
							array('name'=>7,'title'=>'Tàu hỏa'),
							array('name'=>8,'title'=>'Tàu thuyền'),
							array('name'=>10,'title'=>'Máy bay'),
							);
							$name = 'type_id';
							break;
						default:
							$name = 'attr';
							$_all_attrs = [];// $this->admin()->model->getAttr(getParam('type'));
							break;
					}
			
			
					if(!empty($_all_attrs)){
						$spc = array(
								'',
								'&nbsp;&nbsp;&nbsp;+&nbsp;',
								'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+&nbsp;',
								'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+&nbsp;',
								'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+&nbsp;',
			
						);
						echo '<div class="form-group form-group-search mgl-1 group-sm30"><select name="'.$name.'" class="select2 input-sm form-control">';
						echo '<option value=""> - '.getTextTranslate(94,ADMIN_LANG).' - </option>';
						foreach ($_all_attrs as $k=>$v){
							echo '<option '.(getParam($name) == $v['name'] ? 'selected' : '').' value="'.$v['name'].'">&nbsp;&nbsp;&nbsp;+&nbsp;'. uh($v['title']).'</option>';
						}
						echo '</select></div>';
					}
				}
			
				echo '<button type="submit" class="btn btn-sm btn-default btn-sd"><i class="glyphicon glyphicon-search"></i> '.getTextTranslate(18,ADMIN_LANG).'</button>
			
</form></div>';
			}
			
			
			break;
}
//
?>

</div>

<?= $content; ?>

</div></div> 

<?php $this->endBody() ?>

<?php 
if(0>1 && Yii::$app->controller->id == 'default'){?>
<!-- Inline scripts -->
<script type='text/javascript'>
//<![CDATA[
var pictureSrc ="/hoa_dao.png"; //the location of the snowflakes
var pictureWidth = 25; //the width of the snowflakes
var pictureHeight = 25; //the height of the snowflakes
var numFlakes = 10; //the number of snowflakes
var downSpeed = 0.01; //the falling speed of snowflakes (portion of screen per 100 ms)
var lrFlakes = 10; //the speed that the snowflakes should swing from side to side


if( typeof( numFlakes ) != 'number' || Math.round( numFlakes ) != numFlakes || numFlakes < 1 ) { numFlakes = 10; }

//draw the snowflakes
for( var x = 0; x < numFlakes; x++ ) {
if( document.layers ) { //releave NS4 bug
document.write('<layer id="snFlkDiv'+x+'"><imgsrc="'+pictureSrc+'" height="'+pictureHeight+'"width="'+pictureWidth+'" alt="*" border="0"></layer>');
} else {
document.write('<div style="position:absolute; z-index:9999;"id="snFlkDiv'+x+'"><img src="'+pictureSrc+'"height="'+pictureHeight+'" width="'+pictureWidth+'" alt="*"border="0"></div>');
}
}

//calculate initial positions (in portions of browser window size)
var xcoords = new Array(), ycoords = new Array(), snFlkTemp;
for( var x = 0; x < numFlakes; x++ ) {
xcoords[x] = ( x + 1 ) / ( numFlakes + 1 );
do { snFlkTemp = Math.round( ( numFlakes - 1 ) * Math.random() );
} while( typeof( ycoords[snFlkTemp] ) == 'number' );
ycoords[snFlkTemp] = x / numFlakes;
}

//now animate
function flakeFall() {
if( !getRefToDivNest('snFlkDiv0') ) { return; }
var scrWidth = 0, scrHeight = 0, scrollHeight = 0, scrollWidth = 0;
//find screen settings for all variations. doing this every time allows for resizing and scrolling
if( typeof( window.innerWidth ) == 'number' ) { scrWidth = window.innerWidth; scrHeight = window.innerHeight; } else {
if( document.documentElement && (document.documentElement.clientWidth ||document.documentElement.clientHeight ) ) {
scrWidth = document.documentElement.clientWidth; scrHeight = document.documentElement.clientHeight; } else {
if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
scrWidth = document.body.clientWidth; scrHeight = document.body.clientHeight; } } }
if( typeof( window.pageYOffset ) == 'number' ) { scrollHeight = pageYOffset; scrollWidth = pageXOffset; } else {
if( document.body && ( document.body.scrollLeft ||document.body.scrollTop ) ) { scrollHeight = document.body.scrollTop;scrollWidth = document.body.scrollLeft; } else {
if(document.documentElement && (document.documentElement.scrollLeft ||document.documentElement.scrollTop ) ) { scrollHeight =document.documentElement.scrollTop; scrollWidth =document.documentElement.scrollLeft; } }
}
//move the snowflakes to their new position
for( var x = 0; x < numFlakes; x++ ) {
if( ycoords[x] * scrHeight > scrHeight - pictureHeight ) { ycoords[x] = 0; }
var divRef = getRefToDivNest('snFlkDiv'+x); if( !divRef ) { return; }
if( divRef.style ) { divRef = divRef.style; } var oPix = document.childNodes ? 'px' : 0;
divRef.top = ( Math.round( ycoords[x] * scrHeight ) + scrollHeight ) + oPix;
divRef.left = ( Math.round( ( ( xcoords[x] * scrWidth ) - (pictureWidth/2)) + ( ( scrWidth / ( ( numFlakes + 1 ) * 4 ) ) * (Math.sin( lrFlakes * ycoords[x] ) - Math.sin( 3 * lrFlakes * ycoords[x]) ) ) ) + scrollWidth ) + oPix;
ycoords[x] += downSpeed;
}
}

//DHTML handlers
function getRefToDivNest(divName) {
if( document.layers ) { return document.layers[divName]; } //NS4
if( document[divName] ) { return document[divName]; } //NS4 also
if( document.getElementById ) { return document.getElementById(divName); } //DOM (IE5+, NS6+, Mozilla0.9+, Opera)
if( document.all ) { return document.all[divName]; } //Proprietary DOM - IE4
return false;
}

window.setInterval('flakeFall();',100);
//]]>
</script>
<script type="text/javascript" src="<?php echo __RSDIR__;?>/js/exceptions.js?ver=4.3.3" ></script>
 
<?php }?> 
</body>
</html>
<?php $this->endPage() ?>
