<?php 
/*/$query = new yii\db\Query();
$query->andFilterWhere([
		'or',
		['like', 'profiles.first_name','a'],
		['like', 'profiles.last_name', 'f'],
]);
//view($query->createCommand()->getSql());
 * 
 */
//getSupplierPricesList(105);
?>
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
switch (CONTROLLER_CODE){
	case 5:
		if(isset($v['id']) && $v['id']>0){
		echo '<li role="presentation" class=""><a href="#tab-panel-seasons" role="tab" data-toggle="tab">Cài đặt mùa / Đầu tuần cuối tuần</a></li>';
		echo '<li role="presentation" class=""><a href="#tab-menus" role="tab" data-toggle="tab">Thực đơn</a></li>';
		}
		break;
	case 7:
		echo '<li role="presentation" class=""><a href="#tab-panel-seasons" role="tab" data-toggle="tab">Cài đặt mùa</a></li>';			
		echo '<li role="presentation" class=""><a href="#tab-panel-rooms" role="tab" data-toggle="tab">Cài đặt Khoang / Ghế</a></li>';
		echo '<li role="presentation" class="hide"><a href="#tab-panel-routes" role="tab" data-toggle="tab">Cài đặt tuyến</a></li>';
		echo '<li role="presentation" class=""><a href="#tab-panel-prices" role="tab" data-toggle="tab">Bảng giá</a></li>';
		break;
	case 9:
		echo '<li role="presentation" class=""><a href="#tab-tickets" role="tab" data-toggle="tab">Danh sách vé</a></li>';
		
		break;	
		
}
?> 
 
  <li role="presentation" class="hide"><a href="#tab-help" role="tab" data-toggle="tab">Hướng dẫn</a></li>

</ul>

<!-- Tab panes -->
<div class="tab-content edit-form-content-tab">

  <div role="tabpanel" class="tab-panel active" id="tab-genaral">

        <div class="p-content">
 
        
        <div class="form-group">
          <label class="col-sm-12 control-label">Mã số</label>
          <div class="col-sm-12">

            <?php
            //if($this->getAction() == 'add'){
            //	echo '<textarea id="text0001" class="form-control required" placeholder="Tên viết cách nhau bởi dấu ;" name="f[name]"></textarea>'; 
           // }else 
              echo '<input data-alert="Mã <b class=red>{VAL}</b> đã được sử dụng." onchange="checkCustomerCode(this);" type="text" name="f[code]" class="form-control check_error required" placeholder="Mã số" data-id="'.(isset($v['id']) ? $v['id'] : 0).'" value="'.(isset($v['code']) ? $v['code']  : '').'" />';
            ?>
			 
          </div>
         </div>
 
         <div class="form-group">
          <label class="col-sm-12 control-label">Tiêu đề</label>
          <div class="col-sm-12">

            <?php
            //if($this->getAction() == 'add'){
            //	echo '<textarea id="text0001" class="form-control required" placeholder="Tên viết cách nhau bởi dấu ;" name="f[name]"></textarea>';
           // }else 
              echo '<input type="text" name="f[name]" class="form-control required" placeholder="Tiêu đề" value="'.(isset($v['name']) ? $v['name']  : '').'" />';
            ?>

          </div>
         </div>                 
         
         <div class="form-group">
          <label for="inputLink" class="col-sm-12 control-label">Tỉnh thành</label>
          <div class="col-sm-12 group-sm34">
            
		      <select name="f[local_id]"  class="form-control js-select-data-ajax" data-placeholder="Danh mục tỉnh thành" style="width: 100%" data-role="load_locatition">
		      <option value="0" <?php echo isset($v['local_id']) && $v['local_id'] == 0 ? 'selected="selected"' : '';?> > -- </option>
		     <?php 
		     $l = load_model('local');
		     $l = $l->getItem(isset($v['local_id']) ? $v['local_id'] : 0,array('show_parent'=>true));
		     if(!empty($l)){
		     	echo '<option value="'.$l['id'].'" selected >'.$l['name'].'</option>';
		     }else{
		     	
		     }
		      
		     ?>
		      </select>
		      
          </div>
        </div>
<?php
$place_id=0;
         echo '<div class="form-group group-sm34">
          <label  class="col-sm-12 control-label">Địa danh</label>
          <div class="col-sm-12"><select name="places[]" class="js-select-data-ajax form-control" multiple data-placeholder="Chọn 1 hoặc nhiều địa danh" data-role="load_dia_danh" style="width:100%">';
          $l1 =$model->get_existed_place($id,2);
          if(!empty($l1)){
          	foreach ($l1 as $k1=>$v1){
          		$place_id = $v1['id'];
          		echo '<option selected value="'.$v1['id'].'">'.$v1['name'].'</option>';
          	}
          }else{
          	echo '<option></option>';
          }
         
            
          echo '</select></div>
         </div>';


        ?>        
<?php 
echo '<div class="form-group">
      <label class="col-sm-12 control-label">Loại khách hàng</label>
      <div class="col-sm-12 group-sm34">';
$l1 = Yii::$app->zii->get_customer_type_code();
if(!empty($l1)){
	echo '<select class="form-control select2-hide-search" name="f[type_code]">';
	foreach ($l1 as $k1=>$v1){
		echo '<option '.(isset($v['type_code']) && $v['type_code'] == $v1['id'] ? 'selected' : '').' value="'.$v1['id'].'">'.$v1['title'].'</option>';
	}
	echo '</select>';
}
echo '</div></div>';
?> 
         <div class="form-group">
          <label class="col-sm-12 control-label">Địa chỉ</label>
          <div class="col-sm-12">

            <?php
            //if($this->getAction() == 'add'){
            //	echo '<textarea id="text0003" class="form-control  " placeholder="Địa chỉ viết cách nhau bởi dấu ;" name="f[address]"></textarea>';
            //}else
              echo '<input type="text" name="f[address]" class="form-control" placeholder="Địa chỉ" value="'.(isset($v['address']) ? $v['address']  : '').'" />';
            ?>

          </div>
         </div>
         <div class="form-group">
          <label class="col-sm-12 control-label">Điện thoại</label>
          <div class="col-sm-12">

            <?php
           // if($this->getAction() == 'add'){
            //	echo '<textarea id="text0002" class="form-control  " placeholder="Tên viết cách nhau bởi dấu ;" name="f[short_name]"></textarea>';
           // }else
              echo '<input type="text" name="f[phone]" class="form-control" placeholder="Điện thoại" value="'.(isset($v['phone']) ? $v['phone']  : '').'" />';
            ?>

          </div>
         </div>
        <div class="form-group">
          <label class="col-sm-12 control-label">Email</label>
          <div class="col-sm-12">

            <?php
           // if($this->getAction() == 'add'){
            //	echo '<textarea id="text0002" class="form-control  " placeholder="Tên viết cách nhau bởi dấu ;" name="f[short_name]"></textarea>';
           // }else
              echo '<input data-field="email" data-id="'.(isset($v['id']) ? $v['id'] : 0).'" onchange="checkCustomerCode(this);" type="email" name="f[email]" class="form-control" placeholder="Email" value="'.(isset($v['email']) ? $v['email']  : '').'" />';
            ?>

          </div>
         </div>
         
         <div class="form-group">
          <label class="col-sm-12 control-label">MST</label>
          <div class="col-sm-12">

            <?php
           // if($this->getAction() == 'add'){
            //	echo '<textarea id="text0002" class="form-control  " placeholder="Tên viết cách nhau bởi dấu ;" name="f[short_name]"></textarea>';
           // }else
              echo '<input type="text" name="f[tax]" class="form-control" placeholder="Mã số thuế" value="'.(isset($v['tax']) ? $v['tax']  : '').'" />';
            ?>

          </div>
         </div>
         
         <div class="form-group">
          <label class="col-sm-12 control-label">Địa chỉ thuế</label>
          <div class="col-sm-12">

            <?php
           // if($this->getAction() == 'add'){
            //	echo '<textarea id="text0002" class="form-control  " placeholder="Tên viết cách nhau bởi dấu ;" name="f[short_name]"></textarea>';
           // }else
              echo '<input type="text" name="biz[tax_address]" class="form-control" placeholder="Địa chỉ thuế" value="'.(isset($v['tax_address']) ? $v['tax_address']  : '').'" />';
            ?>

          </div>
         </div>
   
         
         <div class="form-group">
          <label class="col-sm-12 control-label">Số lượng người</label>
          <div class="col-sm-12">

            <?php           
              echo '<input type="text" name="biz[pax]" class="form-control" placeholder="Số lượng nhân viên / công nhân" value="'.(isset($v['pax']) ? $v['pax']  : '').'" />';
            ?>

          </div>
         </div>
         
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
					echo '<div class="block-example2">';
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
					echo '<div class="block-example2">';
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
					echo '<div class="block-example2">';
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
       <div class="hide"><?php echo getCheckBox(array(
            'name'=>'f[is_active]',
            'value'=>isset($v['is_active']) ? $v['is_active'] : 1,
            'label'=>'Kích hoạt','type'=>'n02'
        ));?></div>
         

         </div>
 </div>
<?php 
switch (CONTROLLER_CODE){
	case 5:case 7:
		$fp = dirname(__FILE__) . '/_forms/_tab_season.php';
		include_once $fp;
		break;
}
$fp = dirname(__FILE__) . '/_forms/_file'.CONTROLLER_CODE.'.php';
 
if(file_exists($fp)){
	include_once $fp;
}
 
?> 

</div>


</form>
</div>



</div> 