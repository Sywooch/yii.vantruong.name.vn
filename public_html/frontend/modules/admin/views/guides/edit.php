<?php 
//view(cu([CONTROLLER_TEXT.'/edit'],true) . (3 > 0 ? '/edit'.afGetUrl(['id'=>7]) : ''),true);

?>

<div class="table-responsive ">
<div class="fl100 edit-form">
<form id="editFormContent" method="post" class="form-horizontal edit-form-left" role="form" enctype="multipart/form-data">
<input type="hidden" class="currentTab" name="currentTab" value="" />
<input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
<input type="hidden" name="formSubmit" value="true"  />
<input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"  />
<input type="hidden" name="f[type_id]" value="<?php echo CONTROLLER_CODE;?>"  />
    <!-- Nav tabs -->
     
<ul class="nav form-edit-tab nav-tabs" role="tablist">
<?php 
echo '<li role="presentation" class="active"><a href="#tab-general" role="tab" data-toggle="tab">'.getTextTranslate(68,ADMIN_LANG).'</a></li>
<li role="presentation" class=""><a href="#tab-panel-seasons" role="tab" data-toggle="tab">Cài đặt mùa / Đầu tuần cuối tuần</a></li>
<li role="presentation" class=""><a href="#tab-panel-prices" role="tab" data-toggle="tab">Bảng giá</a></li> 		
<li role="presentation" class=""><a href="#tab-help" role="tab" data-toggle="tab">'.getTextTranslate(73,ADMIN_LANG).'</a></li>';
 
  if(isset($v['url'])){
  	echo '<li><a rel="link_target" target="_blank" href="'.cu(DS.$v['url']).'" >'.getTextTranslate(74,ADMIN_LANG).'</a></li>';
  }
  ?>
</ul>

<!-- Tab panes -->
<div class="tab-content edit-form-content-tab">
<div role="tabpanel" class="tab-panel active" id="tab-general">
<div class="p-content">
<?php 
echo Ad_edit_show_text_field($v,[
		'field'=>'name',
		'label'=>getTextTranslate(54,ADMIN_LANG),
		'class'=>'required'
]);


echo '<div class="form-group group-sm34">
          <label  class="col-sm-12 control-label">Địa danh</label>
          <div class="col-sm-12"><select name="places[]" class="js-select-data-ajax form-control" multiple data-placeholder="Chọn 1 hoặc nhiều địa danh" data-role="load_dia_danh" style="width:100%">';
$l1 =$model->get_existed_place(isset($v['id']) ? $v['id'] : 0,2);
if(!empty($l1)){
	foreach ($l1 as $k1=>$v1){
		echo '<option selected value="'.$v1['id'].'">'.$v1['name'].'</option>';
	}
}else{
	echo '<option></option>';
}
 

echo '</select></div>
         </div>';
echo Ad_edit_show_image_field($v,[
		'field'=>'icon',
		'label'=>'Icon/Thumbnail',
		'class'=>'',
		'placeholder'=>'Url hình ảnh',
		'field_name'=>'icon',
			
]);
//echo Ad_edit_show_check_field([
//		'field'=>[
				//'f[is_active]'=>['value'=>isset($v['is_active']) ? $v['is_active'] : 1,'label'=>getTextTranslate(57,ADMIN_LANG),'boolean'=>true],
					
		//]
//]);
?>   
</div></div> 
<?php
//
$fp = dirname(__FILE__) . '/_forms/_tab_season.php';
include_once $fp;
//
$fp = dirname(__FILE__) . '/_forms/_tab_prices.php';
include_once $fp;
?>
<div role="tabpanel" class="tab-panel" id="tab-help">
   <div class="fl100">
        <div class="p-content f12e help-panel-<?php echo Yii::$app->controller->id;?>">
            
        </div>
    </div>
</div>
</div>

</form>
</div>
</div>