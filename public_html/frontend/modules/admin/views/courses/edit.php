<div class="table-responsive ">
<div class="fl100 edit-form">
<form id="editFormContent" method="post" class="form-horizontal edit-form-left" role="form" enctype="multipart/form-data">
<input type="hidden" class="currentTab" name="currentTab" value="" />
<input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
<input type="hidden" name="formSubmit" value="true"  />
<input type="hidden" name="f[type]" value="<?php echo CONTROLLER_CODE;?>"  />
<input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"  />
<ul class="nav form-edit-tab nav-tabs" role="tablist">
<?php
echo '<li role="presentation" class="active"><a href="#tab-general" role="tab" data-toggle="tab">'.getTextTranslate(68,ADMIN_LANG).'</a></li>';
echo '<li role="presentation" class=""><a href="#tab-detail" role="tab" data-toggle="tab">'.getTextTranslate(69).'</a></li>';
?>
</ul>

<div class="tab-content edit-form-content-tab">
<div role="tabpanel" class="tab-panel active" id="tab-general">
<div class="p-content">
<?php 
echo Ad_edit_show_text_field($v,[
		'field'=>'title',
		'label'=>getTextTranslate(54,ADMIN_LANG),
		'class'=>'required'
]);
echo Ad_edit_show_image_field($v,[
		'field'=>'icon',
		'label'=>'Icon/Thumbnail',
		'class'=>'',
		'placeholder'=>'Url hình ảnh',
		'field_name'=>'icon',
			
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
echo '<div class="form-group group-sm34"><label class="col-sm-12 control-label">Địa điểm / Cơ sở</label>
<div class="col-sm-12"><select data-type="select" data-select="select2" name="biz[branches][]" class="form-control input-sm select2" style="width: 100%" multiple="multiple">';
foreach (\app\modules\admin\models\Branches::getAll() as $k1=>$v1){ 
	echo '<option '.(isset($v['branches']) && in_array($v1['id'], $v['branches']) ? 'selected="selected" ' : '').'value="'.$v1['id'].'">'.uh($v1['name']).'</option> ';
}

echo '</select></div></div>';
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
</div></div> 
<?php include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'/_forms/_tab_detail.php');?>
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