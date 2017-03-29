<div class="table-responsive " style="padding-top: 0px">

<div class="fl100 edit-form">
<form id="editFormContent" method="post" class="form-horizontal edit-form-left" role="form" enctype="multipart/form-data">
<input type="hidden" class="currentTab" name="currentTab" value="" />
<input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
<input type="hidden" name="formSubmit" value="true"  />
<input type="hidden" name="f[type_id]" value="<?php echo CONTROLLER_CODE;?>"  />
<input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"/>
    <!-- Nav tabs -->
<ul class="nav form-edit-tab nav-tabs" role="tablist">

  <li role="presentation" class="active"><a href="#tab-genaral" role="tab" data-toggle="tab">Thông tin chung</a></li>
<?php 
echo '<li role="presentation" class=""><a href="#tab-detail" role="tab" data-toggle="tab">Giới thiệu về chủ thể</a></li>';
?> 
 
  <li role="presentation" class="hide"><a href="#tab-help" role="tab" data-toggle="tab">Hướng dẫn</a></li>

</ul>

<!-- Tab panes -->
<div class="tab-content edit-form-content-tab">

  <div role="tabpanel" class="tab-panel active" id="tab-genaral">

        <div class="p-content">
        <div class="row">
        <div class="col-sm-12">
 <label class="fl100 control-label">Mã học viên</label> <div class="fl100">       
 <div class="input-group input-group-sm group-sm30">
<?php 
$setting = isset(Yii::$site['settings']['customers'][TYPE_ID_TEA]['code']) ? Yii::$site['settings']['customers'][TYPE_ID_TEA]['code'] : [];
$auto_code =  isset($v['auto_code']) ? $v['auto_code'] : (!isset($v['code']) && isset($setting['auto_code']) ? $setting['auto_code'] : '');
//view(splitName(['full_name'=>'Tran van X']));
?> 
 <input data-parent="2" name="f[code]" onchange="checkCustomerCode(this);" data-toggle="tooltip" title='Mã này dùng để đăng nhập hệ thống quản lý lớp học'
 placeholder="Mã học viên" value="<?php echo isset($v['code']) ? $v['code'] : '';?>"
 type="text" <?php echo $auto_code == 'on' ? 'disabled' : '';?> class="form-control input-sm finput-code <?php echo $auto_code == 'on' ? '' : 'required';?>">
      <span data-toggle="tooltip" title="Mã sinh tự động theo quy tắc được cài đặt trong vùng thiết lập ban đầu" class="input-group-addon ">
      <label class="mgb0"> <input name="biz[auto_code]" <?php echo $auto_code== 'on' ? 'checked' : '';?> data-function="reverse" onchange="enabledInput(this);" data-target=".finput-code" type="checkbox" aria-label="Tự động sinh mã">
        Tự động sinh mã</label>
      </span>
      
    </div> 
    
    </div> 
<?php 
 
echo '<div class="form-group group-sm30">
		<div class="col-sm-1 col-xs-2"><div class="row">
		<label class="col-sm-12 control-label">Danh xưng</label>
		<div class="col-sm-12"><select class="form-control select2" data-search="hidden" name="f[gender]">
		<option '.(isset($v['gender']) && ($v['gender']==1) ? 'selected' : '').' value="1">Ông</option>
		<option '.(isset($v['gender']) && ($v['gender']==0) ? 'selected' : '').' value="0">Bà</option>
		<option '.(isset($v['gender']) && ($v['gender']==3) ? 'selected' : '').' value="3">Khác</option>
		</select></div>
		</div></div>
		<div class="col-sm-3  col-xs-4"><div class="row">
		<label class="col-sm-12 control-label">Họ đệm</label>
		<div class="col-sm-12"><input type="text" name="f[lname]" class="form-control input-sm" placeholder="Họ và tên đệm - để trống sẽ tự động cắt từ tên sang" value="'.(isset($v['lname']) ? uh($v['lname']) : '').'"></div>
		</div></div>
		<div class="col-sm-3 col-xs-4"><div class="row">
		<label class="col-sm-12 control-label">Tên</label>
		<div class="col-sm-12"><input type="text" name="f[fname]" class="form-control input-sm required" placeholder="Tên học viên" value="'.(isset($v['fname']) ? uh($v['fname']) : '').'"></div>
		</div></div>
		<div class="col-sm-5 col-xs-2"><div class="row">
		<label class="col-sm-12 control-label">Tên thường gọi</label>
		<div class="col-sm-12"><input type="text" name="f[short_name]" class="form-control input-sm " placeholder="Nghệ danh / Biệt danh" value="'.(isset($v['short_name']) ? uh($v['short_name']) : '').'"></div>
		</div></div>
		</div>';
echo '<div class="form-group">
				<label class="col-sm-12 control-label">Ngày tháng năm sinh</label>
	
				<div class="col-sm-2 col-xs-3"><div class="row">
		 
		<div class="col-sm-12 group-sm30">
<select onchange="changeDayOfYear(this);" data-role="0" data-target=".select-input-month" class="form-control chosen-select" data-search="hidden" name="birth[year]">';
$year = isset($v['birth']) ? date('Y',strtotime($v['birth'])) : date('Y') - 20;
for ($i=1930;$i<date('Y')+1;$i++){
	echo '<option '.($year==$i ? 'selected' : '').' value="'.$i.'">Năm '.$i.'</option>';
}
 
echo '</select></div>
		</div></div>	<div class="col-sm-2 col-xs-3"><div class="row">
		 
		<div class="col-sm-12 group-sm30"> 
      		<select onchange="changeDayOfYear(this);" data-role="1" data-year="'.$year.'" data-target=".select-input-day" class="select-input-month form-control select2" data-search="hidden" name="birth[month]">';
$month = isset($v['birth']) ? date('m',strtotime($v['birth'])) : 1;
for ($i=1;$i<13;$i++){
	echo '<option '.($month==$i? 'selected' : '').' value="'.$i.'">Tháng '.$i.'</option>';
}
 
echo '</select></div>
		</div></div>
		 
<div class="col-sm-2 col-xs-3"><div class="row">
		 
		<div class="col-sm-12 group-sm30"><select data-target=".select-input-birth" data-month="'.$month.'" data-year="'.$year.'" onchange="changeDayOfYear(this);" data-role="2" data-disable-search="true" class="form-control chosen-select-no-search select-input-day" data-search="hidden" name="birth[day]">';
$n = isset($v['birth']) ? date('d',strtotime($v['birth'])) : 1;
for ($i=1;$i<32;$i++){
	echo '<option '.($n==$i? 'selected' : '').' value="'.$i.'">Ngày '.danhso($i,2).'</option>';
}
 
echo '</select></div>
		</div></div>
<input type="hidden" name="f[birth]" value="'.(isset($v['birth']) ? date('Y-m-d',strtotime($v['birth'])) : $year . '-' . $month . '-' . $n).'" class="select-input-birth"/>			
				
		</div>';
$local = Yii::$app->zii->parseCountry(isset($v['local_id']) ? $v['local_id'] : 0); 
echo '<div class="form-group group-sm30">
		<div class="col-sm-2 col-xs-3"><div class="row">
		<label class="col-sm-12 control-label">Quốc gia</label>
		<div class="col-sm-12"><select data-target_input=".input-local_id" data-target=".select-input-provinces" onchange="loadChildsProvinces(this)" class="form-control ajax-chosen-select-ajax" data-role="chosen-load-country" name="l[country]">
		'.(!empty($local) ? '<option selected value="'.$local['country']['id'].'">'.uh($local['country']['title']).'</option>' : '').'
		 
		</select></div>
		</div></div>
 
<div class="col-sm-2 col-xs-3"><div class="row">
		<label class="col-sm-12 control-label">Tỉnh / Thành phố</label>
		<div class="col-sm-12"><select data-level="1" data-target_input=".input-local_id" data-parent_id="'.(isset($local['country']['id']) ? $local['country']['id'] : 0).'" data-target=".select-input-districts" onchange="loadChildsProvinces(this)" data-placeholder="Chọn tỉnh / thành phố" class="select-input-provinces form-control ajax-chosen-select-ajax" data-role="chosen-load-country" name="l[province]"> 
		'.(!empty($local) && !empty($local['province']) ? '<option selected value="'.$local['province']['id'].'">'. showLocalName(uh($local['province']['title']),$local['province']['type_id']).'</option>' : '').'
		</select></div>
		</div></div>

<div class="col-sm-2 col-xs-3"><div class="row">
		<label class="col-sm-12 control-label">Quận / Huyện</label>
		<div class="col-sm-12"><select data-level="2" data-target_input=".input-local_id" data-parent_id="'.(isset($local['province']['id']) ? $local['province']['id'] : 0).'" data-target=".select-input-wards" onchange="loadChildsProvinces(this)" data-placeholder="Chọn quận / huyện" class="select-input-districts form-control ajax-chosen-select-ajax" data-role="chosen-load-country" name="l[district]">
	 '.(!empty($local) && !empty($local['district']) ? '<option selected value="'.$local['district']['id'].'">'. showLocalName(uh($local['district']['title']),$local['district']['type_id']).'</option>' : '').'
		</select></div>
		</div></div>
<div class="col-sm-2 col-xs-3"><div class="row">
		<label class="col-sm-12 control-label">Phường / Xã</label>
		<div class="col-sm-12"><select data-level="3" data-target_input=".input-local_id" data-parent_id="'.(isset($local['district']['id']) ? $local['district']['id'] : 0).'" onchange="loadChildsProvinces(this)" data-placeholder="Chọn phường / xã" class="select-input-wards form-control ajax-chosen-select-ajax" data-role="chosen-load-country" name="l[ward]">
		'.(!empty($local) && !empty($local['ward']) ? '<option '.($local['ward']['id'] == -1 ? 'disabled' : '').' selected value="'.$local['ward']['id'].'">'. showLocalName(uh($local['ward']['title']),$local['ward']['type_id']).'</option>' : '').'
		</select></div>
		</div></div>
<input type="hidden" name="f[local_id]" value="'.(isset($v['local_id']) ? $v['local_id'] : 0).'" class="input-local_id"/>		 
		</div>';

echo Ad_edit_show_text_field($v,[
		'field'=>'address',
		'label'=>getTextTranslate(137,ADMIN_LANG),
		'class'=>'',
		'placeholder'=>'Địa chỉ'
]);

echo Ad_edit_show_text_field($v,[
		'field'=>'phone',
		'label'=>getTextTranslate(138,ADMIN_LANG),
		'class'=>'',
		'placeholder'=>'Nhập số điện thoại'
]);

echo Ad_edit_show_text_field($v,[
		'field'=>'email',
		'label'=>'Email',
		'class'=>'required',
		'input_type'=>'email',
		'attrs'=>['onchange'=>"checkCustomerCode(this);",'data-field'=>'email','data-toggle'=>'tooltip','title'=>'Email liên hệ, reset mật khẩu.'],
		'placeholder'=>'Nhập email'
]);
 
echo Ad_edit_show_image_field($v,[
		'field'=>'icon',
		'label'=>'Icon/Thumbnail',
		'class'=>'',
		'placeholder'=>'Url hình ảnh',
		'field_name'=>'icon',
		
]);

?> 
 
         
 
         
       
    
			
			<?php
			echo '<div class="form-group"><label  class="col-sm-12 control-label"></label><div class="col-sm-12 customers_fileds_list">';
			if(isset($v['fileds']) && !empty($v['fileds'])){
				
				foreach ($v['fileds'] as $k1=>$v1){
					echo '<div class="block-example2"><i title="Xóa" onclick="removeTrItem(this,1);" class="remove_item glyphicon glyphicon-trash pointer"></i>';
					echo '<label  class="control-label bold">Tiêu đề</label>';
					echo '<input type="text" name="biz[fileds]['.$k1.'][name]" class="form-control" placeholder="" value="'.(isset($v1['name']) ? $v1['name']  : '').'" />';
					echo '<label  class="control-label bold">Nội dung</label>';
					echo '<input type="text" name="biz[fileds]['.$k1.'][text]" class="form-control" placeholder="" value="'.(isset($v1['text']) ? $v1['text']  : '').'" />';
					//echo '<label  class="control-label bold">Email</label>';
					//echo '<input type="email" name="biz[contacts]['.$k1.'][email]" class="form-control" placeholder="" value="'.(isset($v1['email']) ? $v1['email']  : '').'" />';
					echo '</div>'; 
				}
				
			}
			echo '</div></div>';
			?>
			
        <div class="form-group">
          <label  class="col-sm-12 control-label"></label>
          <div class="col-sm-12">
          <button data-toggle="tooltip" title="Lưu trữ thêm các trường thông tin về chủ thể." type="button" data-count="<?php echo isset($v['fileds']) && !empty($v['fileds']) ? count($v['fileds']) : 0; ?>" onclick="add_more_fileds_list(this);" class="btn btn-default btn-warning"><i class="glyphicon glyphicon-plus"></i> Thêm trường khác</button>
          
          </div>
         </div>   
       <div class="hide"><?php 
       echo Ad_edit_show_check_field([
       		'field'=>[
       				'f[is_active]'=>['value'=>isset($v['is_active']) ? $v['is_active'] : 1,'label'=>getTextTranslate(57,ADMIN_LANG),'boolean'=>true],
       					
       		]
       ]);
       ?></div>
         

         </div>
          


    </div></div></div>
 

<div role="tabpanel" class="tab-panel" id="tab-detail">
<div class="p-contentx fl100">
<div class="col-sm-12  ">
<input name="biz[text_title]" value="<?php echo isset($v['text_title']) ? uh($v['text_title']) : '';?>" class="form-control" data-toggle="tooltip" title="Tiêu đề bài viết" placeholder="Tiêu đề bài viết"/>
</div></div>
<div class="p-contentx fl100">
 
<div class="col-sm-6">
 
 

        <textarea data-toggle="tooltip" name="biz[info]" title="<?php echo getTextTranslate(84);?>" class="form-control inputProductInfo" id="exampleInputInfo" placeholder="Miêu tả ngắn về bài viết" ><?php echo isset($v['info']) ? uh($v['info']) : '';?></textarea>
       
</div>
<div class="col-sm-6  ">
 
 
     <textarea name="biz[summary]" data-toggle="tooltip" title="Viết tóm lược về bài viết" class="form-control inputProductInfo" placeholder="Viết tóm lược về bài viết" ><?php echo isset($v['summary']) ? uh($v['summary']) : '';?></textarea>
 
</div>
</div>
<div class="p-contentx fl100">
<div class="col-sm-12 col8respon">
            <?php

          echo ckeditor('detail-tab-0',array(
                'attr'=> array('class'=>'','name'=>'biz[text]')    ,
                'upload'=>true,
                'toolbar' =>  'Full',
                'value' =>isset($v['text']) ?  uh($v['text'],2) : '',
                'h'=>350,
          ));

          ?>

          </div></div>

</div>


</div>


</form>
</div>



</div> 