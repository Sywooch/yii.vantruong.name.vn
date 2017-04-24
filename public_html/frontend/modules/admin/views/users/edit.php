<?php 

?> 
<div class="table-responsive " style="padding-top: 0px">

<div class="fl100 edit-form">
 <form id="editFormContent" method="post" class="form-horizontal edit-form-left" role="form" enctype="multipart/form-data">
 <input type="hidden" class="currentTab" name="currentTab" value="" />
 <input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
 <input type="hidden" name="formSubmit" value="true"  />
 
<input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"  />
    <!-- Nav tabs -->
<ul class="nav form-edit-tab nav-tabs" role="tablist">

  <li role="presentation" class="active"><a href="#tab-genaral" role="tab" data-toggle="tab">Thông tin chung</a></li>
 
  

</ul>

<!-- Tab panes -->
<div class="tab-content edit-form-content-tab ">

  <div role="tabpanel" class="tab-panel active" id="tab-genaral">

        <div class="p-content">
        <div class="row">
        <div class="col-sm-12">
        
        <div class="form-group">
          <label class="col-sm-12 control-label">Họ tên</label>
          <div class="col-sm-12">

            <?php
             
              echo '<input type="text" name="f[fullName]" class="form-control required" placeholder="Họ tên nhân viên" value="'.(isset($v['lname']) ? $v['lname'] . ' ' . $v['fname']  : '').'" />';
            ?>

          </div>
         </div>
  
      <div class="form-group"><div class="col-sm-6 col-xs-12 group-sm34 radius0"><div class="row">
          <label for="inputLink" class="col-sm-12 control-label">Vị trí</label>
          <div class="col-sm-12">
            
		      <select name="g[group_id][]" multiple="multiple" class="form-control select2 " data-placeholder="Vị trí" style="width: 100%">
		      <option value="0">- Khác -</option>
		     <?php 
		      
		     $l = $permission->getList(array('is_active'=>1));
		     
		     if(!empty($l['listItem'])){
		     	foreach ($l['listItem'] as $k1=>$v1){
		     		echo '<option value="'.$v1['id'].'" '.(!empty($v) && in_array($v1['id'], $v['group_id']) ? 'selected="selected"' : '').'>'.uh(($v1['title'] != "" ? $v1['title'] : $v1['name'])).'</option>';
		     	}
		     }
		     ?>
		      </select>
		      
          </div></div></div>
          <div class="col-sm-6 group-sm34 col-xs-12 radius0"><div class="row">
          <label for="inputLink" class="col-sm-12 control-label">Tỉnh thành</label>
          <div class="col-sm-12 group-sm34 radius0">
            
		      <select name="f[local_id]"  class="form-control select2 " data-placeholder="Danh mục tỉnh thành" style="width: 100%">
		      <option value="0" <?php echo isset($v['local_id']) && $v['local_id'] == 0 ? 'selected="selected"' : '';?> > --- Không chọn ---</option>
		     <?php 
		      
		     $l = $local->getList(['level'=>2,'limit'=>10000]); 
		      
		     if(!empty($l['listItem'])){
		     	foreach ($l['listItem'] as $k1=>$v1){
		     		echo '<option value="'.$v1['id'].'" '.(isset($v['local_id']) && $v['local_id'] == $v1['id'] ? 'selected="selected"' : '').'>'.spc($v1['level']).uh($v1['name']).'</option>';
		     	}
		     }
		     ?>
		      </select>
		      
          </div>
          </div></div>
        </div>
        
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
              echo '<input data-action="checkUserExisteds" data-id="'.getParam('id',array('num'=>true)).'" onblur="checkUserExisteds(this);" data-field="email" type="email" name="f[email]" class="form-control required" placeholder="Email" value="'.(isset($v['email']) ? $v['email']  : '').'" />';
            ?>
			<p id="error-respon" class="pd15" style="display: none;margin-top:10px; "></p>
          </div>
         </div>
       
         
         <div class="form-group">
          <label class="col-sm-12 control-label">Số tài khoản</label>
          <div class="col-sm-12">

            <?php
           // if($this->getAction() == 'add'){
            //	echo '<textarea id="text0002" class="form-control  " placeholder="Tên viết cách nhau bởi dấu ;" name="f[short_name]"></textarea>';
           // }else
              echo '<input type="text" name="biz[accountNumber]" class="form-control" placeholder="Số tài khoản ngân hàng" value="'.(isset($v['accountNumber']) ? $v['accountNumber']  : '').'" />';
            ?>
			
          </div>
         </div>
         
         <div class="form-group">
          <label  class="col-sm-12 control-label">Tên ngân hàng</label>
          <div class="col-sm-12">

            <?php
           // if($this->getAction() == 'add'){
            //	echo '<textarea id="text0002" class="form-control  " placeholder="Tên viết cách nhau bởi dấu ;" name="f[short_name]"></textarea>';
           // }else
              echo '<input type="text" name="biz[accountName]" class="form-control" placeholder="Tên ngân hàng giao dịch" value="'.(isset($v['accountName']) ? $v['accountName']  : '').'" />';
            ?>

          </div>
         </div>
    
         
      
    
        
         <?php 
         
         echo  getCheckBox(array(
         		'type'=>'n02',
            'name'=>array('f[is_active]',Yii::$app->controller->action->id == 'edit' ? 'reset_password' : 'reset_password'),
            'value'=>array(isset($v['is_active']) ? $v['is_active'] : 1 , 0),
            'label'=>array('Kích hoạt',Yii::$app->controller->action->id == 'edit' ? 'Reset mật khẩu' : 'Gửi mật khẩu tới email đăng ký.')
        )) ;?>
 
 <p class="help-block">Mật khẩu reset sẽ gửi về địa chỉ email đã đăng ký ở mục trên.</p>
         </div>
         <div class="col-sm-4"></div>



    </div></div></div>
 
 
 
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