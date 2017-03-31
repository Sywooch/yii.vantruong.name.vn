<div class="table-responsive ">

<div class="fl100 edit-form">
 <form id="editFormContent" method="post" class="form-horizontal edit-form-left " role="form" enctype="multipart/form-data">
 <input type="hidden" class="currentTab" name="currentTab" value="" />
 <input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
 <input type="hidden" name="formSubmit" value="true"  />
 <input type="hidden" name="f[type]" value="<?php echo getParam('type');?>"  />
<input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"/>
    <!-- Nav tabs -->
<ul class="nav form-edit-tab nav-tabs" role="tablist">
  <li role="presentation" class="active"><a href="#tab-general" role="tab" data-toggle="tab"><?php echo getTextTranslate(68,ADMIN_LANG);?></a></li>
  <li role="presentation" class=""><a href="#tab-detail" role="tab" data-toggle="tab"><?php echo getTextTranslate(69,ADMIN_LANG);?></a></li>
  <li role="presentation" class=""><a href="#tab-seo" role="tab" data-toggle="tab"><?php echo getTextTranslate(71,ADMIN_LANG);?></a></li>
  <li role="presentation" class=""><a href="#tab-help" role="tab" data-toggle="tab"><?php echo getTextTranslate(73,ADMIN_LANG);?></a></li>

</ul>

<!-- Tab panes -->
<div class="tab-content edit-form-content-tab">
  <div role="tabpanel" class="tab-panel active" id="tab-general">

        <div class="p-content">
        <div class="row"><div class="col-sm-12">
<?php 
echo Ad_edit_show_text_field($v,[
		'field'=>'title',
		'label'=>getTextTranslate(54,ADMIN_LANG),
		'class'=>'required'
]);
echo Ad_edit_show_text_field($v,[
		'field'=>'short_title',
		'label'=>"Tiêu đề ngắn",
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
echo Ad_edit_show_image_field($v,[
		'field'=>'icon',
		'label'=>'Icon/Thumbnail',
		'class'=>'',
		'placeholder'=>'Url hình ảnh',
		'field_name'=>'icon',
		 
]);
echo Ad_edit_show_select_field($v,[
		 
		'label'=>'Khóa học',
		'class'=>'select2',
		'field_name'=>'course_id[]',
		'multiple'=>true,
		'data'=>\app\modules\admin\models\Content::getListItem(['type'=>FORM_TYPE_COURSES]),
		'data-disabled'=>[],
		'data-selected'=>\app\modules\admin\models\Content::getItemCourseCategorys(isset($v['id']) ? $v['id'] : 0),
		'option-value-field'=>'id',
		'option-title-field'=>'title',
		'attrs'=>[
				'data-placeholder'=>'Chọn khóa học'
				
],
		
]);
echo Ad_edit_show_text_field($v,[
		'field'=>'position',
		'label'=>getTextTranslate(56,ADMIN_LANG),
		'class'=>'number-format',
		'placeholder'=>'Thứ tự xắp xếp',
		'default_value'=>999
]);
echo Ad_edit_show_check_field([
		'field'=>[
				'f[is_active]'=>['value'=>isset($v['is_active']) ? $v['is_active'] : 1,'label'=>getTextTranslate(57,ADMIN_LANG),'boolean'=>true],
				 
]
]);
	

?>        
</div></div></div></div>
<?php include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'../../_forms/_forms/_tab_detail.php');?>
<?php include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'../../_forms/_forms/_tab_seo.php');?>
<?php include_once(dirname(__FILE__). DIRECTORY_SEPARATOR.'../../_forms/_forms/_tab_help.php');?>
<div role="tabpanel" class="tab-panel" id="settings">
        <div class="p-content">

        </div>
</div>
  
</div></form></div></div> 