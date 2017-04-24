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
 
 
  <li role="presentation" class="hide"><a href="#tab-help" role="tab" data-toggle="tab">Hướng dẫn</a></li>

</ul>

<!-- Tab panes -->
<div class="tab-content edit-form-content-tab">

  <div role="tabpanel" class="tab-panel active" id="tab-genaral">

        <div class="p-content">
        <div class="row">
        <div class="col-sm-12">
        
 
<?php 
echo Ad_edit_show_text_field($v,[
		'field'=>'code',
		'label'=>'Mã số',
		'class'=>'required',		 
		'attrs'=>['onchange'=>"checkCustomerCode(this);",'data-field'=>'code'],
		'placeholder'=>'Nhập mã số, mã này là duy nhất cho 1 đối tượng'
]);
echo Ad_edit_show_text_field($v,[
		'field'=>'name',
		'label'=>getTextTranslate(54,ADMIN_LANG),
		'class'=>'required',
		'placeholder'=>'Tên khách hàng | Công ty'
]);
echo Ad_edit_show_text_field($v,[
		'field'=>'short_name',
		'label'=>'Tên viết tắt',
		'class'=>' ',
		'placeholder'=>'Tên viết tắt / tên ngắn của khách hàng | công ty'
]);
// /view($locals,true);
echo Ad_edit_show_select_field($v,[
		'field'=>'local_id',
		'label'=>getTextTranslate(224,ADMIN_LANG),
		'class'=>'select2',
		//'field_name'=>'category_id[]',
		//'multiple'=>true,
		'default_value'=>0,
		'data'=>$locals,
		'data-disabled'=>[],
		'data-selected'=>[isset($v['local_id']) ? $v['local_id'] : 0],
		'option-value-field'=>'id',
		'option-title-field'=>'name',
]);

echo Ad_edit_show_select_field($v,[
		'field'=>'type_code',
		'label'=>'Loại khách hàng',
		'class'=>'select2',
		//'field_name'=>'category_id[]',
		//'multiple'=>true,
		//'default_value'=>0,
		'data'=>$model->get_customer_type_code(),
		'data-disabled'=>[],
		'data-selected'=>[isset($v['type_code']) ? $v['type_code'] : 0],
		'option-value-field'=>'id',
		'option-title-field'=>'title',
]);

echo Ad_edit_show_text_field($v,[
		'field'=>'address',
		'label'=>getTextTranslate(137,ADMIN_LANG),
		'class'=>''
]);

echo Ad_edit_show_text_field($v,[
		'field'=>'phone',
		'label'=>getTextTranslate(138,ADMIN_LANG),
		'class'=>''
]);

echo Ad_edit_show_text_field($v,[
		'field'=>'email',
		'label'=>'Email',
		'class'=>'',
		'input_type'=>'email',
		'attrs'=>['onchange'=>"checkCustomerCode(this);",'data-field'=>'email']
]);
echo Ad_edit_show_text_field($v,[
		'field'=>'tax',
		'label'=>'MST',
		'class'=>'',
		 
		//'attrs'=>['onchange'=>"checkCustomerCode(this);"]
]);
echo Ad_edit_show_text_field($v,[
		'field'=>'tax_address',
		'field_name'=>'biz[tax_address]',
		'label'=>'Địa chỉ thuế',
		'class'=>'',
			 
]);
echo Ad_edit_show_text_field($v,[
		'field'=>'pax',
		'field_name'=>'biz[pax]',
		'label'=>'Số lượng người',
		'placeholder'=>'Số lượng nhân viên / công nhân',

]);


?> 
 
         
          <div class="form-group">
          <label  class="col-sm-12 control-label">Ngân hàng GD</label>
          <div class="col-sm-12">
          <button type="button" data-target=".bank_account_list" data-count="<?php echo isset($v['bank']) && !empty($v['bank']) ? count($v['bank']) : 0; ?>" onclick="add_more_bank_list(this);" class="btn btn-default btn-warning"><i class="glyphicon glyphicon-plus"></i> Thêm ngân hàng</button>
          
          </div>
         </div>
         <div class="form-group">
          <label  class="col-sm-12 control-label"></label>
          <div class="col-sm-12 bank_account_list">
			
			<?php
			if(isset($v['bank']) && !empty($v['bank'])){
				foreach ($v['bank'] as $k1=>$v1){
					echo '<div class="block-example2"><i title="Xóa" onclick="removeTrItem(this,1);" class="remove_item glyphicon glyphicon-trash pointer"></i>';
					echo '<label  class="control-label bold">Số tài khoản</label>';
					echo '<input type="text" name="biz[bank]['.$k1.'][account]" class="form-control" placeholder="" value="'.(isset($v1['account']) ? $v1['account']  : '').'" />';
					echo '<label  class="control-label bold">Tên ngân hàng</label>';
					echo '<input type="text" name="biz[bank]['.$k1.'][name]" class="form-control" placeholder="" value="'.(isset($v1['name']) ? $v1['name']  : '').'" />';
					echo '<label  class="control-label bold">Chi nhánh</label>';
					echo '<input type="email" name="biz[bank]['.$k1.'][branch]" class="form-control" placeholder="" value="'.(isset($v1['branch']) ? $v1['branch']  : '').'" />';
					echo '</div>'; 
				}
			}
			
			?>
			
          </div>
         </div>
         
         
    <div class="form-group">
          <label  class="col-sm-12 control-label">Người liên hệ</label>
          <div class="col-sm-12">
          <button type="button" data-count="<?php echo isset($v['contacts']) && !empty($v['contacts']) ? count($v['contacts']) : 0; ?>" onclick="add_more_contacts_list(this);" class="btn btn-default btn-warning"><i class="glyphicon glyphicon-plus"></i> Thêm người liên hệ</button>
          
          </div>
         </div>
         <div class="form-group">
          <label  class="col-sm-12 control-label"></label>
          <div class="col-sm-12 contacts_list">
			
			<?php
			if(isset($v['contacts']) && !empty($v['contacts'])){
				foreach ($v['contacts'] as $k1=>$v1){
					echo '<div class="block-example2"><i title="Xóa" onclick="removeTrItem(this,1);" class="remove_item glyphicon glyphicon-trash pointer"></i>';
					echo '<label  class="control-label bold">Họ tên</label>';
					echo '<input type="text" name="biz[contacts]['.$k1.'][name]" class="form-control" placeholder="" value="'.(isset($v1['name']) ? $v1['name']  : '').'" />';
					echo '<label  class="control-label bold">Số điện thoại</label>';
					echo '<input type="text" name="biz[contacts]['.$k1.'][phone]" class="form-control" placeholder="" value="'.(isset($v1['phone']) ? $v1['phone']  : '').'" />';
					echo '<label  class="control-label bold">Email</label>';
					echo '<input type="email" name="biz[contacts]['.$k1.'][email]" class="form-control" placeholder="" value="'.(isset($v1['email']) ? $v1['email']  : '').'" />';
					echo '</div>'; 
				}
			}
			
			?>
			
          </div>
         </div>
         
        <div class="form-group">
          <label  class="col-sm-12 control-label"></label>
          <div class="col-sm-12">
          <button type="button" data-count="<?php echo isset($v['fileds']) && !empty($v['fileds']) ? count($v['fileds']) : 0; ?>" onclick="add_more_fileds_list(this);" class="btn btn-default btn-warning"><i class="glyphicon glyphicon-plus"></i> Thêm trường khác</button>
          
          </div>
         </div>
    <div class="form-group">
          <label  class="col-sm-12 control-label"></label>
          <div class="col-sm-12 customers_fileds_list">
			
			<?php
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
			
			?>
			
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
 

</div>


</form>
</div>



</div> 