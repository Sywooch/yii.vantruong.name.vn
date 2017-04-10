<?php 
 
?> 
<div class="table-responsive " style="padding-top: 0px">

<div class="fl100 edit-form">
 <form id="editFormContent" method="post" class="form-horizontal edit-form-left " role="form" enctype="multipart/form-data">
 <input type="hidden" class="currentTab" name="currentTab" value="" />
 <input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
 <input type="hidden" name="formSubmit" value="true"  />
 <input type="hidden" name="f[type_id]" value="<?php echo CONTROLLER_CODE;?>"  />
 <input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"/>

    <!-- Nav tabs -->
<ul data-target=".tab-content-panel" class="nav form-edit-tab nav-tabs" role="tablist">

<li role="presentation" class="active"><a href="#tab-genaral" role="tab" data-toggle="tab">Thông tin chung</a></li>
<?php 
if(Yii::$app->controller->action->id == 'edit'){
	echo '<li role="presentation" class=""><a href="#tab-panel-list-rooms" role="tab" data-toggle="tab">Danh sách phòng</a></li>
<li role="presentation" class=""><a href="#tab-panel-seasons" role="tab" data-toggle="tab">Cài đặt mùa / Cuối tuần</a></li>';
}
//if(Yii::$app->user->can(ROOT_USER)){
//	echo '<li role="presentation" class=""><a href="#tab-panel-genaral-price" role="tab" data-toggle="tab">Bảng giá</a></li>';
//}
	//if(Yii::$app->user->can(ROOT_USER)){
	echo '<li role="presentation" class=""><a href="#tab-panel-genaral-price1" role="tab" data-toggle="tab">Bảng giá</a></li>';
	//}
?>

<li role="presentation" class=""><a href="#tab-help" role="tab" data-toggle="tab">Hướng dẫn</a></li>

</ul>

<!-- Tab panes -->
<div class="tab-content tab-content-panel edit-form-content-tab">

  <div role="tabpanel" class="tab-panel active" id="tab-genaral">

        <div class="p-content">
        <div class="row">
        <div class="col-sm-12">
 	  <div class="form-group">
          <label for="inputLink" class="col-sm-12 control-label">Tỉnh thành</label>
          <div class="col-sm-12 group-sm34">
            
		      <select name="f[local_id]"  class="form-control js-select-data-ajax " data-placeholder="Danh mục tỉnh thành" style="width: 100%" data-role="load_locatition">
		      <option value="0" <?php echo isset($v['local_id']) && $v['local_id'] == 0 ? 'selected="selected"' : '';?> > -- </option>
		     <?php 
		     $l = new app\modules\admin\models\Local();
		     $l = $l->getItem(isset($v['local_id']) ? $v['local_id'] : 0,array('show_parent'=>true));
		     if(!empty($l)){
		     	echo '<option value="'.$l['id'].'" selected >'.$l['name'].'</option>';
		     }else{
		     	
		     }
		     $spc = array(
		     		'',
		     		'&nbsp;&nbsp;&nbsp;+&nbsp;',
		     		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+&nbsp;',
		     		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+&nbsp;',
		     		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+&nbsp;',
		     
		     );
		      
		     ?>
		      </select>
		      
          </div>
        </div>
        
        <?php
         echo '<div class="form-group group-sm34">
          <label  class="col-sm-12 control-label">Địa danh</label>
          <div class="col-sm-12"><select name="places[]" class="js-select-data-ajax form-control" multiple data-placeholder="Chọn 1 hoặc nhiều địa danh" data-role="load_dia_danh" style="width:100%">';
          $l1 =$model->get_existed_place($id,2);
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
        <div class="form-group group-sm34">
          <label for="inputLink" class="col-sm-12 control-label">Tiêu chuẩn</label>
          <div class="col-sm-3">
            
		      <select name="f[rating]" class="form-control select2" data-search="hidden" data-placeholder="Xếp hạng khách sạn" style="width: 100%">
		      <option value="0" <?php echo isset($v['rating']) && $v['rating'] == 0 ? 'selected="selected"' : '';?> >Khác</option>
		   
		      <option value="2" <?php echo isset($v['rating']) && $v['rating'] == 2 ? 'selected="selected"' : '';?> >2 sao</option>
		      <option value="3" <?php echo isset($v['rating']) && $v['rating'] == 3 ? 'selected="selected"' : '';?> >3 sao</option>
		      <option value="4" <?php echo isset($v['rating']) && $v['rating'] == 4 ? 'selected="selected"' : '';?> >4 sao</option>
		      <option value="5" <?php echo isset($v['rating']) && $v['rating'] == 5 ? 'selected="selected"' : '';?> >5 sao</option>
		       
		      </select>
		      
          </div>
        </div>
        
         <div class="form-group">
          <label for="inputSeoTitle" class="col-sm-12 control-label">Tiêu đề</label>
          <div class="col-sm-12">

            <?php
              echo '<input type="text" name="f[name]" class="form-control required" placeholder="Tiêu đề " value="'.(isset($v['name']) ? $v['name']  : '').'" />';
            ?>

          </div>
         </div>
         <div class="form-group">
          <label for="inputSeoTitle" class="col-sm-12 control-label">Tên viết tắt</label>
          <div class="col-sm-12">

            <?php
              echo '<input type="text" name="f[short_name]" class="form-control " placeholder="Tiêu đề ngắn" value="'.(isset($v['short_name']) ? $v['short_name']  : '').'" />';
            ?>

          </div>
         </div>
         
         <div class="form-group">
          <label for="inputSeoTitle" class="col-sm-12 control-label">Địa chỉ</label>
          <div class="col-sm-12">

            <?php
              echo '<input type="text" name="f[address]" class="form-control" placeholder="Địa chỉ" value="'.(isset($v['address']) ? $v['address']  : '').'" />';
            ?>

          </div>
         </div>
         
         <div class="form-group">
          <label for="inputSeoTitle" class="col-sm-12 control-label">Điện thoại</label>
          <div class="col-sm-12">

            <?php
              echo '<input type="text" name="f[phone]" class="form-control" placeholder="Điện thoại" value="'.(isset($v['phone']) ? $v['phone']  : '').'" />';
            ?>

          </div>
         </div>
         
         <div class="form-group">
          <label class="col-sm-12 control-label">Email</label>
          <div class="col-sm-12">

            <?php
            
              echo '<input data-field="email" data-id="'.(isset($v['id']) ? $v['id'] : 0).'" onchange="checkCustomerCode(this);" type="email" name="f[email]" class="form-control" placeholder="Email" value="'.(isset($v['email']) ? $v['email']  : '').'" />';
            ?>

          </div>
         </div>
          
         <div class="form-group">
          <label for="inputSeoTitle" class="col-sm-12 control-label">MST</label>
          <div class="col-sm-12">

            <?php
              echo '<input type="text" name="biz[tax]" class="form-control" placeholder="Mã số thuế" value="'.(isset($v['tax']) ? $v['tax']  : '').'" />';
            ?>

          </div>
         </div>
       <div class="form-group">
          <label for="inputSeoTitle" class="col-sm-12 control-label">Địa chỉ thuế</label>
          <div class="col-sm-12">

            <?php
              echo '<input type="text" name="biz[tax_address]" class="form-control" placeholder="Địa chỉ thuế" value="'.(isset($v['tax_address']) ? $v['tax_address']  : '').'" />';
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
         <div class="hide"><?php echo getCheckBox(array(
            'name'=>'f[is_active]',
            'value'=>isset($v['is_active']) ? $v['is_active'] : 1,
            'label'=>'Kích hoạt'
        ));?></div>
         
 

         </div>
    



    </div></div></div>
<?php 
include_once '_forms/tab_list.php';
//include_once '_forms/tab_price.php';
include_once '_forms/tab_season.php';
//if(Yii::$app->user->can(ROOT_USER)){
//	include_once '_forms/tab_price2.php';
///}
//if(Yii::$app->user->can(ROOT_USER)){ 
	include_once '_forms/tab_prices3.php';
//}
?> 
  <div role="tabpanel" class="tab-panel" id="tab-help" style="height: 500px">
   
 
 
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

 