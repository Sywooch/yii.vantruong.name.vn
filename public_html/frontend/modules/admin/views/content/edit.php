<div class="table-responsive ">

<div class="fl100 edit-form">
 <form id="editFormContent" method="post" class="form-horizontal edit-form-left" role="form" enctype="multipart/form-data">
 <input type="hidden" class="currentTab" name="currentTab" value="" />
 <input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
 <input type="hidden" name="formSubmit" value="true"  />
 <input type="hidden" name="f[type]" value="<?php echo getParam('type');?>"  />
<input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"/>
    <!-- Nav tabs -->
<ul class="nav form-edit-tab nav-tabs" role="tablist">
<?php 
echo '<li role="presentation" class="active"><a href="#tab-general" role="tab" data-toggle="tab">'.getTextTranslate(68).'</a></li>
  
  <li role="presentation" class=""><a href="#tab-detail" role="tab" data-toggle="tab">'.getTextTranslate(69).'</a></li>
  
  <li role="presentation" class=""><a href="#tab-images" role="tab" data-toggle="tab">'.getTextTranslate(70).'</a></li>
  <li role="presentation" class=""><a href="#tab-option" role="tab" data-toggle="tab">'.getTextTranslate(72).'</a></li>
 
  <li role="presentation" class=""><a href="#tab-seo" role="tab" data-toggle="tab">'.getTextTranslate(71).'</a></li>
  <li role="presentation" class=""><a href="#tab-help" role="tab" data-toggle="tab">'.getTextTranslate(73).'</a></li>';
 
  if($this->getAction() == 'edit'){
  	echo '<li><a rel="link_target" target="_blank" href="'.$this->createUrl(DS.$v['url']).'" >'.getTextTranslate(74).'</a></li>';
  }
  ?>
	
</ul>

<!-- Tab panes -->
<div class="tab-content edit-form-content-tab">
  <div role="tabpanel" class="tab-panel active" id="tab-general">

        <div class="p-content">
        <div class="row">
         <div class="col-sm-12 col8respon">
<?php 
echo Ad_edit_show_text_field($v,[
		'field'=>'title',
		'label'=>getTextTranslate(54,ADMIN_LANG),
		'class'=>'required'
]);
echo Ad_edit_show_text_field($v,[
		'field'=>'code',
		'label'=>'Code',
		'class'=>''
]);
echo Ad_edit_show_select_field($v,[
		//'field'=>'title',
		'label'=>getTextTranslate(65,ADMIN_LANG),
		'class'=>'required select2',
		'field_name'=>'category_id[]',
		'multiple'=>true,
		'data'=>$all_menu,
		'data-disabled'=>[],
		'data-selected'=>$categorys,
		'option-value-field'=>'id',
		'option-title-field'=>'title',
]);
echo Ad_edit_show_text_field($v,[
		'field'=>'price2',
		'label'=>getTextTranslate(29,ADMIN_LANG),
		'class'=>'number-format',
		'placeholder'=>''
]);

echo Ad_edit_show_text_field($v,[
		'field'=>'position',
		'label'=>getTextTranslate(56,ADMIN_LANG),
		'class'=>'number-format',
		'placeholder'=>'Thứ tự xắp xếp'
]);

echo Ad_edit_show_check_field([
		'field'=>'is_active',
		'value'=>isset($v['is_active']) ? $v['is_active'] : 1,
		'type'=>'time',
		'label'=>getTextTranslate(57,ADMIN_LANG),
		'active_from_date'=>isset($v['active_from_date']) ? $v['active_from_date'] : '',
		'active_to_date'=>isset($v['active_to_date']) ? $v['active_to_date'] : '',
]);
?>
          
</div></div></div></div>
<?php include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'_forms/_forms/_tab_detail.php');?>
<?php include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'/_forms/_forms/_tab_seo.php');?>
<?php include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'/_forms/_forms/_tab_help.php');?>
<?php include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'/_forms/_forms/_tab_images.php');?>
<?php include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'/_forms/_forms/_tab_options.php');?>  
</div>


</form>
</div>



</div>