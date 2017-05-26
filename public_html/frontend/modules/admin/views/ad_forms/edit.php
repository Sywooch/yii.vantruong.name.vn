<div class="table-responsive ">

<div class="fl100 edit-form">
 <form id="editFormContent" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
 <input type="hidden" class="currentTab" name="currentTab" value="" />
 <input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
 <input type="hidden" name="formSubmit" value="true"  />
 <input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"/>
    <!-- Nav tabs -->
<ul class="nav form-edit-tab nav-tabs" role="tablist">
  <li role="presentation" class="active"><a href="#tab-general" role="tab" data-toggle="tab">Thông tin chung</a></li>
<li role="presentation" class=" "><a href="#tab-temps" role="tab" data-toggle="tab">Lĩnh vực</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content edit-form-content-tab">
  <div role="tabpanel" class="tab-panel active" id="tab-general">
    <div class="p-content">
        <div class="row">
         <div class="col-sm-6 col-xs-12">

         <div class="form-group">
          <label for="inputTitle" class="col-sm-12 aleft control-label">Title</label>
          <div class="col-sm-12">
            <input type="text" name="f[title]" class="form-control required" id="inputTitle" placeholder="Title" value="<?php echo isset($v['title']) ? $v['title'] : '';?>" />
          </div>
        </div>
         
        <div class="form-group">
          <label for="inputName" class="col-sm-12 aleft control-label">Code</label>
          <div class="col-sm-12">
            <input type="text" name="f[code]" class="form-control" id="inputName" placeholder="Code" value="<?php echo isset($v['code']) ? $v['code'] : '';?>" />
          </div>
        </div>

<div class="form-group">
          
          <div class="col-sm-6 col-xs-12 group-sm34"><label for="inputPosition" class="  aleft control-label">Tab</label>
            <select name="f[tabs]" class="form-control select2 select2-hide-search" data-search="hidden">
            <option <?php echo isset($v['tabs']) && $v['tabs'] == 0 ? 'selected' : '';?> value="0">--</option>
            <option <?php echo isset($v['tabs']) && $v['tabs'] == 1 ? 'selected' : '';?> value="1">Mặc định</option>
            <option <?php echo isset($v['tabs']) && $v['tabs'] == 2 ? 'selected' : '';?> value="2">Tab mở rộng</option>
            </select>
          </div>
          <div class="col-sm-6 col-xs-12"><div class="row">
          <label for="inputPosition" class="col-sm-12 aleft control-label">Position</label>
          <div class="col-sm-12">
            <input type="text" name="f[position]" class="form-control number-format" id="inputPosition" placeholder="Position" value="<?php echo isset($v['position']) ? $v['position'] : 0;?>" />
          </div></div>
           
        </div>
          
        </div>

        
        <?php echo getCheckBox(array(
            'name'=>['f[is_active]','f[is_content]'],
            'value'=>[isset($v['is_active']) ? $v['is_active'] : 1,isset($v['is_content']) ? $v['is_content'] : 0],
            'label'=>['Active','Content'],'type'=>'n02'
        ));?> 

         </div>
         <div class="col-sm-6"></div>



        </div>
    </div>
  </div>
   
   <div role="tabpanel" class="tab-panel" id="tab-temps">
    <div class="fl100">
         
<div class="p-content f12e">
           <?php 
           echo '<div class="checkbox m-level-0"><label><input class="prs_ckc_0" onchange="check_all_item(this)" data-role="prs_ckc_0" '.(isset($v['is_all']) && $v['is_all'] == 1 ? 'checked' : '').' name="is_all" type="checkbox">Tất cả</label></div>';
          
           if(!empty($templetes['listItem'])){
           	foreach($templetes['listItem'] as $k1=>$v1){
           		 
           		echo '<div class="checkbox m-level-1"><label><input class="prs_ckc_0"  data-role="prs_ckc_0"  data-id="'.$v1['id'].'" '.(in_array($v1['id'], $r) ? 'checked' : '').' name="forms[0][temp_id][]" value="'.$v1['id'].'" type="checkbox"> '.uh($v1['title']).'</label></div>';
           		 
           		 
           	}
          }
           ?>
        
        </div>
    </div></div>
  <div role="tabpanel" class="tab-pane" id="messages">
    <div class="fl100">
        <div class="p-content">

        </div>
    </div>
  </div>
  <div role="tabpanel" class="tab-pane" id="settings">
    <div class="fl100">
        <div class="p-content">

        </div>
    </div>
  </div>
</div>


</form>
</div>



</div>

