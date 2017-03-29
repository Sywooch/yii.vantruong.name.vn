
<div class="table-responsive ">

<div class="fl100 edit-form">
 <form id="editFormContent" method="post" class="form-horizontal edit-form-left " role="form" enctype="multipart/form-data">
 <input type="hidden" class="currentTab" name="currentTab" value="" />
 <input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
 <input type="hidden" name="formSubmit" value="true"  />
<input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"/>
<ul class="nav form-edit-tab nav-tabs" role="tablist">
  <li role="presentation" class="active"><a href="#tab-general" role="tab" data-toggle="tab">Thông tin chung</a></li>

</ul>

<!-- Tab panes -->
<div class="tab-content edit-form-content-tab">
  <div role="tabpanel" class="tab-panel active" id="tab-general">
    <div class="p-content">
        <div class=" row">
         <div class="col-sm-12">
 
         <div class="form-group">
          <label for="inputTitle" class="col-sm-12 control-label">Tiêu đề</label>
          <div class="col-sm-12">
          
            <input type="text" name="f[label]" class="form-control input-sm required" placeholder="Tiêu đề" value="<?php echo !empty($v) ?  dString($v['label']) : '';?>" />
          </div>
        </div>
        <div class="form-group">
          <label for="inputTitle" class="col-sm-12 control-label">IP</label>
          <div class="col-sm-12 ">          
            <input type="text" name="f[host_address]" class="form-control input-sm required" placeholder="IP host" value="<?php echo !empty($v) ? dString($v['host_address']) : '';?>" />
          </div>
        </div>
        <div class="form-group">
          <label for="inputTitle" class="col-sm-12 control-label">Cổng kết nối</label>
          <div class="col-sm-12 ">          
            <input type="text" name="f[host_port]" class="form-control input-sm required" placeholder="Cổng kết nối" value="<?php echo !empty($v) ? $v['host_port'] : 21;?>" />
          </div>
        </div> 
        <div class="form-group">
          <label for="inputTitle" class="col-sm-12 control-label">Domain</label>
          <div class="col-sm-12 ">
          
            <input type="text" name="f[web_address]" class="form-control input-sm required" placeholder="Domain trả về link file" value="<?php echo !empty($v) ? dString($v['web_address']) : '';?>" />
          </div>
        </div>
  
        <div class="form-group">
          <label for="inputTitle" class="col-sm-12 control-label">Thư mục</label>
          <div class="col-sm-12 ">
          
            <input type="text" name="f[root_directory]" class="form-control input-sm required" placeholder="Thư mục chứa file" value="<?php echo !empty($v) ? dString($v['root_directory']) : '';?>" />
          </div>
        </div>
        <div class="form-group">
          <label for="inputTitle" class="col-sm-12 control-label">Tên đăng nhập</label>
          <div class="col-sm-12 ">
          
            <input data-min="4" autocomplete="off" type="text" name="f[username]" class="form-control input-sm required" placeholder="Tên đăng nhập" title="Tên đăng nhập có từ 4 ký tự" value="<?php echo !empty($v) ? dString($v['username']) : '';?>" />
          </div>
        </div>
  		 <div class="form-group">
          <label for="inputTitle" class="col-sm-12 control-label">Mật khẩu</label>
          <div class="col-sm-12 ">          
            <input data-min="6" autocomplete="off" type="password" name="fx[password]" class="form-control input-sm spassrequired input_password" placeholder="<?php echo !empty($v) ? 'Lần thay đổi cuối: ' . count_time_post($v['last_modify']) : 'Mật khẩu';?>" value="" />
          </div>
        </div>
        <div class="form-group">
          <label for="inputTitle" class="col-sm-12 control-label">Xác nhận mật khẩu</label>
          <div class="col-sm-12 ">
          
            <input data-min="6" type="password" name="fx[repassword]" class="form-control input-sm spassrequired input_repassword" placeholder="Xác nhận mật khẩu" value="" />
          </div>
        </div>
		 
		 <?php echo getCheckBox(array(
            'name'=>array('f[is_active]','f[ssl_mode]','f[update_source]') ,
            'value'=>array(isset($v['is_active']) ? $v['is_active'] : 0 ,isset($v['ssl_mode']) ? $v['ssl_mode'] : 0,isset($v['update_source']) ? $v['update_source'] : 0),
            'label'=>array('Kích hoạt','SSL','Update Source'),'type'=>'n02'
        ));?>
         </div>
         



        </div>
    </div>
  </div>
  <div role="tabpanel" class="tab-panel" id="profile">
    <div class="fl100">
        <div class="p-content">

        </div>
    </div></div>
  <div role="tabpanel" class="tab-panel" id="messages">
    <div class="fl100">
        <div class="p-content">

        </div>
    </div>
  </div>
  <div role="tabpanel" class="tab-panel" id="settings">
    <div class="fl100">
        <div class="p-content">

        </div>
    </div>
  </div>
</div>


</form>
</div>



</div>
 
