 
<div class="table-responsive ">
<div class="fl100 edit-form">
<form id="editFormContent" method="post" class="form-horizontal edit-form-left " role="form" enctype="multipart/form-data">
<input type="hidden" class="currentTab" name="currentTab" value="" />
<input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
<input type="hidden" name="formSubmit" value="true"  />
<input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"/>
<input type="hidden" name="f[type_id]" value="<?php echo CONTROLLER_CODE;?>"/>
    <!-- Nav tabs -->
<ul class="nav form-edit-tab nav-tabs" role="tablist">
<?php 
echo '<li role="presentation" class="active"><a href="#tab-general" role="tab" data-toggle="tab">'.getTextTranslate(68,ADMIN_LANG).'</a></li>';
switch (CONTROLLER_CODE){
	case 6:
		echo '<li role="presentation" class=""><a href="#tab-hight-way" role="tab" data-toggle="tab">Cao tốc</a></li>';
		break;
}
echo '<li role="presentation" class=""><a href="#tab-help" role="tab" data-toggle="tab">'.getTextTranslate(73,ADMIN_LANG).'</a></li>';
 
 
?> 
</ul>

<!-- Tab panes -->
<div class="tab-content edit-form-content-tab">

  <div role="tabpanel" class="tab-pane active" id="tab-general">

        <div class="p-content">
        <div class="row">
        <div class="col-sm-12">
<?php 
echo Ad_edit_show_text_field($v,[
		'field'=>'title',
		'label'=>getTextTranslate(54,ADMIN_LANG),
		'class'=>'required ' . (Yii::$app->controller->action->id == 'add' ? 'tagsinput' : ''),
		'placeholder'=>'Nhập cách nhau dấu phẩy để thêm nhiều'
]);

?>         
    
 <?php
         echo '<div class="form-group group-sm34">
          <label  class="col-sm-12 control-label">Địa danh</label>
          <div class="col-sm-12"><select name="places[]" class="js-select-data-ajax form-control" multiple data-placeholder="Chọn 1 hoặc nhiều địa danh" role="load_dia_danh" style="width:100%">';
          $l1 =$model->get_existed_place($id);
          if(!empty($l1)){
          	foreach ($l1 as $k1=>$v1){
          		echo '<option selected value="'.$v1['id'].'">'.$v1['name'].'</option>';
          	}
          }else{
          	echo '<option></option>';
          }
         
            
          echo '</select></div>
         </div>';


        ?>
        <?php
         echo '<div class="form-group  ">
          <label  class="col-sm-12 control-label"><b class="red">Km</b> tính tour</label>
          <div class="col-sm-12">
            <input type="text" maxlength="10" name="f[distance]" class="form-control numberFormat" value="'.(isset($v['distance']) ? $v['distance'] : 0).'" placeholder="khoảng cách" />
          </div>
         </div>';


        ?>
        <?php
         echo '<div class="form-group  ">
          <label  class="col-sm-12 control-label">Lưu đêm</label>
          <div class="col-sm-12">
            <input type="text" maxlength="3" name="f[overnight]" class="form-control numberFormat" value="'.(isset($v['overnight']) ? $v['overnight'] : 0).'" placeholder="Lưu đêm" />
          </div>
         </div>';

         echo Ad_edit_show_image_field($v,[
         		'field'=>'icon',
         		'label'=>'Icon/Thumbnail',
         		'class'=>'',
         		'placeholder'=>'Url hình ảnh',
         		'field_name'=>'icon',
         			
         ]);
         echo Ad_edit_show_text_field($v,[
         		'field'=>'position',
         		'label'=>getTextTranslate(56,ADMIN_LANG),
         		'class'=>'number-format',
         		'placeholder'=>'Thứ tự xắp xếp',
         		'default_value'=>999,
         ]);
         echo Ad_edit_show_check_field([
         		'field'=>[
         				'f[is_active]'=>['value'=>isset($v['is_active']) ? $v['is_active'] : 1,'label'=>getTextTranslate(57,ADMIN_LANG),'boolean'=>true],
         					
         		]
         ]);
        ?>
        
 
 


         </div>
          
    </div></div></div>
<?php 
switch (CONTROLLER_CODE){
	case 6:
	include_once '_forms/tab_hight_way.php';	
		break;
}


?>
  <div role="tabpanel" class="tab-pane" id="tab-help">
    <div class="fl100">
        <div class="p-content f12e">
           Đang cập nhật !
        </div>
    </div>
  </div>

</div>


</form>
</div>



</div>