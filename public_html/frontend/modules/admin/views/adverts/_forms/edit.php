<?php
use app\modules\admin\models\Menu;
use app\modules\admin\models\Box;

$type = 'adverts';
 
?>
<div class="table-responsive ">
<div class="fl100 edit-form">
<form id="editFormContent" method="post" class="form-horizontal edit-form-left" role="form" enctype="multipart/form-data">
<input type="hidden" class="currentTab" name="currentTab" value="" />
<input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
<input type="hidden" name="formSubmit" value="true"  />
 <input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>" />
<input type="hidden" name="f[type]" value="<?php echo getParam('type_id',0);?>"  />
<input type="hidden" name="f[image]" value="" />

    <!-- Nav tabs -->
<ul class="nav form-edit-tab nav-tabs" role="tablist">

  <li role="presentation" class="active"><a href="#tab-general" role="tab" data-toggle="tab">Thông tin chung</a></li>
  <li role="presentation" class=""><a href="#tab-help" role="tab" data-toggle="tab">Hướng dẫn</a></li>

</ul>

<!-- Tab panes -->
<div class="tab-content edit-form-content-tab">

  <div role="tabpanel" class="tab-pane active" id="tab-general">

        <div class="p-content">
        <div class="row">
        <div class="col-sm-12 adv_type_group <?php echo isset($v['adv_type']) && $v['adv_type'] == 2 ? 'adv_type_group_2' : 'adv_type_group_1' ;?>">
        <input type="hidden" name="f[is_active]" value="<?php echo isset($v['is_active']) ? $v['is_active'] : 1 ;?>"/>
        <div class="form-group group-sm34 radius0">
          <label for="inputicon" class="col-sm-12 control-label">Loại quảng cáo</label>
          <div class="col-sm-12">
            <select onchange="change_adv_html_type(this);" name="biz[adv_type]" class="form-control chosen-select-no-single">
            <option <?php echo isset($v['adv_type']) && $v['adv_type'] == 1 ? 'selected' : '' ;?> value="1">Sử dụng hình ảnh</option>
            <option <?php echo isset($v['adv_type']) && $v['adv_type'] == 2 ? 'selected' : '' ;?> value="2">Sử dụng mã html</option>             
            </select>
          </div>
        </div>
<?php
echo Ad_edit_show_text_field($v,[
		'field'=>'title',
		'label'=>getTextTranslate(54,ADMIN_LANG),
		'class'=>'required'
]);
echo Ad_edit_show_text_field($v,[
		'field'=>'link',
		'label'=>'Link',
		'class'=>'adv_type_1 adv_type'
]);
         
echo Ad_edit_show_select_field($v,[
		'field'=>'target',
		'field_name'=>'biz[target]',
		'label'=>'Link target',
		'class'=>' select2',
		//'field_name'=>'type',
		//'multiple'=>true,
		'default_value'=>'_self',
		'data'=>[
				['id'=>'_blank','title'=>'_blank'],
				['id'=>'_parent','title'=>'_parent'],
				['id'=>'_top','title'=>'_top'],
],
		'data-disabled'=>[],
		'data-selected'=>[isset($v['target']) ? $v['target'] : ''],
		'option-value-field'=>'id',
		'option-title-field'=>'title',
]);

echo Ad_edit_show_select_field($v,[
		'field'=>'category_id',
		 
		'label'=>'Menu link',
		'class'=>' select2',
		//'field_name'=>'type',
		//'multiple'=>true,
		'default_value'=>0,
		'data'=>Menu::getAll() ,
		'data-disabled'=>[],
		'data-selected'=>[isset($v['category_id']) ? $v['category_id'] : 0],
		'option-value-field'=>'id',
		'option-title-field'=>'title',
]);

echo Ad_edit_show_select_field($v,[
		'field'=>'box_id',
	 
		'label'=>'Box link',
		'class'=>' select2',
		//'field_name'=>'type',
		//'multiple'=>true,
		'default_value'=>0,
		'data'=>Box::getAll() ,
		'data-disabled'=>[],
		'data-selected'=>[isset($v['box_id']) ? $v['box_id'] : 0],
		'option-value-field'=>'id',
		'option-title-field'=>'title',
]);
echo Ad_edit_show_select_field($v,[
		'field'=>'style',
		'field_name'=>'biz[style]',
		'label'=>'Style',
		'class'=>' select2',
		//'field_name'=>'type',
		//'multiple'=>true,
		'default_value'=>0,
		'data'=>[
				['id'=>1,'title'=>'Style 1'],
				['id'=>2,'title'=>'Style 2'],
				['id'=>3,'title'=>'Style 3'],
				['id'=>4,'title'=>'Style 4'],
				
				['id'=>5,'title'=>'Style 5'],
				['id'=>6,'title'=>'Style 6'],
				['id'=>7,'title'=>'Style 7'],
				['id'=>8,'title'=>'Style 8'],
				['id'=>9,'title'=>'Style 9'],
				['id'=>10,'title'=>'Style 10'],
				
] ,
		'data-disabled'=>[],
		'data-selected'=>[isset($v['style']) ? $v['style'] : 0],
		'option-value-field'=>'id',
		'option-title-field'=>'title',
		
]);
        ?>
         

        
        
         
        
        <div class="form-group ">
          <label class="col-sm-12 control-label">Màu nền</label><div class="col-sm-12"> 
          <div class="input-group colorpicker-component Ccolorpicker">
    <input type="text" value="<?php echo isset($v['background_color']) ? $v['background_color'] : '';?>" name="biz[background_color]" class="form-control" />
    <span class="input-group-addon"><i></i></span>
</div> 
        </div></div>

         
        
<?php
echo Ad_edit_show_text_field($v,[
		'field'=>'icon_class',
		'label'=>'Icon class',
		'class'=>'',
		'field_name'=>'biz[icon_class]',
]);
echo Ad_edit_show_image_field($v,[
		'field'=>'icon',
		'label'=>'Hình ảnh',
		'class'=>'',
		'placeholder'=>'Url hình ảnh',
		'field_name'=>'icon',
			
]);
          
echo Ad_edit_show_image_field($v,[
		'field'=>'thumbnail',
		'label'=>'Ảnh thumbnail',
		'class'=>'',
		'placeholder'=>'Url hình ảnh thumbnail (để trống sẽ cắt tự động từ ảnh trên)',
		'field_name'=>'thumbnail',
		
]);

        ?>


        <?php
         echo '<div class="form-group adv_type_2 adv_type">
          <label   class="col-sm-12 control-label">Mã html</label>
          <div class="col-sm-12">

               
              <textarea name="biz[html]" class="form-control" rows="10">'.(isset($v['html']) ? uh($v['html']) : '').'</textarea>
              
          </div>
         </div>';


        ?>
        
        <?php
         echo '<div class="form-group adv_type_1 adv_type">
          <label  class="col-sm-12 control-label">Mô tả</label>
          <div class="col-sm-12">

               
              <textarea name="f[info]" class="form-control">'.(isset($v['info']) ? uh($v['info']) : '').'</textarea>

          </div>
         </div>';

         echo Ad_edit_show_text_field($v,[
         		'field'=>'position',
         		'default_value'=>999,
         		'label'=>getTextTranslate(56,ADMIN_LANG),
         		'class'=>'number-format',
         		'placeholder'=>'Thứ tự xắp xếp'
         ]);
         echo Ad_edit_show_check_field([
         		'field'=>[
         				'f[is_active]'=>['value'=>isset($v['is_active']) ? $v['is_active'] : 1,'label'=>getTextTranslate(57,ADMIN_LANG),'boolean'=>true],
         					
         		]
         ]);
        ?>
        
        
 
 


         </div>
          
    </div></div></div>

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

