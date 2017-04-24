<?php 
if(!Yii::$app->user->can([ROOT_USER])){
	header("Location:".ADMIN_ADDRESS.DS.CONTROLLER_TEXT); 
}
?> 

<div class="table-responsive " style="padding-top: 0px">

<div class="fl100 edit-form">
 <form id="editFormContent" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
 <input type="hidden" class="currentTab" name="currentTab" value="" />
 <input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
 <input type="hidden" name="formSubmit" value="true"  />
 <input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"  />
 

    <!-- Nav tabs -->
<ul class="nav form-edit-tab nav-tabs" role="tablist">

  <li role="presentation" class="active"><a href="#tab-genaral" role="tab" data-toggle="tab">Thông tin chung</a></li>
 
 
  <li role="presentation" class=""><a href="#tab-help" role="tab" data-toggle="tab">Hướng dẫn</a></li>

</ul>

<!-- Tab panes -->
<div class="tab-content edit-form-content-tab">

  <div role="tabpanel" class="tab-panel active" id="tab-genaral">

        <div class="p-content">
        <div class="row">
        <div class="col-sm-8">
 	 
         <div class="form-group">
          <label for="inputSeoTitle" class="col-sm-2 control-label">Tiêu đề</label>
          <div class="col-sm-10">

            <?php
            if(Yii::$app->controller->action->id == 'add'){
            	echo '<input id="text0001" class="form-control required tagsinput" placeholder="Tên viết cách nhau bởi dấu phẩy" name="f[name]"/>';
            }else 
              echo '<input type="text" name="f[name]" class="form-control required" placeholder="Tiêu đề " value="'.(isset($v['name']) ? $v['name']  : '').'" />';
            ?>

          </div>
         </div>
         
         <div class="form-group">
          <label for="inputSeoTitle" class="col-sm-2 control-label">Tên viết tắt</label>
          <div class="col-sm-10">

            <?php
            if(Yii::$app->controller->action->id == 'add'){ 
            	echo '<input class="form-control tagsinput" placeholder="Tên viết cách nhau bởi dấu phẩy" name="f[short_name]" />';
            }else
              echo '<input type="text" name="f[short_name]" class="form-control" placeholder="Tên viết tắt" value="'.(isset($v['short_name']) ? $v['short_name']  : '').'" />';
            ?>

          </div>
         </div>
         
    <div class="form-group">
          <label for="inputSeoTitle" class="col-sm-2 control-label">Code</label>
          <div class="col-sm-10">

            <?php
            if(Yii::$app->controller->action->id == 'add'){
            	echo '<input id="text0002" class="form-control tagsinput" placeholder="Mã viết cách nhau bởi dấu phẩy" name="f[code]" />';
            }else
              echo '<input type="text" name="f[code]" class="form-control" placeholder="Mã" value="'.(isset($v['code']) ? $v['code']  : '').'" />';
            ?>

          </div>
         </div>    
         
         <div class="form-group">
          <label for="inputLink" class="col-sm-2 control-label">Danh mục cha</label>
          <div class="col-sm-6">
            <select name="f[parent_id]" class="hide ajax-chosen-select-ajax" data-role="load_locatition" style="width:100%">
		      
		      
		      <option value="0" <?php echo isset($v['parent_id']) && $v['parent_id'] == 0 ? 'selected="selected"' : '';?> >- Quốc gia -</option>
		     <?php 
		     //$l = $model->getAll(Yii::$app->request->get()); 
		     
		     $item = \app\modules\admin\models\Local::getItem(isset($v['parent_id']) ? $v['parent_id'] : 0);
		     if(!empty($item)){
		     	echo '<option selected value="'.$item['id'].'">'.showLocalName(uh($item['title']),$item['type_id'] ).'</option>';
		     }
		      
		     ?>
		      </select>
		      
          </div>
          <div class="col-sm-4">
          <label><input type="checkbox" name="update_lft"/> Reset lft</label>
          </div>
        </div>
        <div class="form-group">
          <label for="inputLink" class="col-sm-2 control-label">Loại địa danh</label>
          <div class="col-sm-6">
            
		      <select name="f[type_id]"  class="form-control select2 " data-search="hidden" data-placeholder="Loại địa danh" style="width: 100%">
		       
		     <?php 
		     $l = getLocalType();
		      
		     if(!empty($l)){
		     	foreach ($l as $k1=>$v1){
		     		echo '<option value="'.$v1['id'].'" '.(isset($v['type_id']) && $v['type_id'] == $v1['id'] ? 'selected="selected"' : '').'>'.uh($v1['title']).'</option>';
		     	}
		     }
		     ?>
		      </select>
		      
          </div>
        </div>
    <div class="form-group">
          <label for="inputLink" class="col-sm-2 control-label">Vùng miền</label>
          <div class="col-sm-6">
            
		      <select name="f[region]"  class="form-control select2 " data-placeholder="Vùng miền" style="width: 100%">
		      <option value=""   > --- Không chọn ---</option>
		     <?php 
		     $l = array(array('id'=>'N','name'=>'Miền bắc'),array('id'=>'C','name'=>'Miền trung'),array('id'=>'S','name'=>'Miền nam'));
		      
		     if(!empty($l)){
		     	foreach ($l as $k1=>$v1){
		     		echo '<option value="'.$v1['id'].'" '.(isset($v['region']) && $v['region'] == $v1['id'] ? 'selected="selected"' : '').'>'.uh($v1['name']).'</option>';
		     	}
		     }
		     ?>
		      </select>
		      
          </div>
        </div>
       
          
 

         </div>
 



    </div></div></div>
 
 
 
  <div role="tabpanel" class="tab-panel" id="tab-help" >
   
 
 
    <div class="fl100">
        <div class="p-content f12e">
           <table class="table table-bordered table-hover vmiddle">
           <thead><tr>
           <th class="center w200p">Quận / Huyện / TP / TX</th>
           <th class="center" colspan="2">Xã / Phường / Thị trấn</th>
           </tr></thead>
           <tbody>
<?php 
 
if(isset($v['id']) && $v['id'] > 0 && $v['level'] == 1){
	foreach ($model->getList(['parent_id'=>$v['id'],'p'=>1])['listItem'] as $k2=>$v2){
		if($model->count_all_child_lft($v2['lft'],$v2['rgt']) == 0){
		echo '<tr><td class="center '.($k2%2==0 ? 'bgreen white' : 'red').'" rowspan="4"><b class="underline ">'.uh($v2['title']).'</b>
<select name="new['.$v2['id'].'][type_id]" class="form-control">';
		foreach (getLocalType() as $v1){
			echo '<option '.($v1['id'] == $v2['type_id'] ? 'selected' : '').' value="'.$v1['id'].'">'.$v1['title'].'</option>';
		}
		echo '</select>
<input type="hidden" name="new['.$v2['id'].'][level]" value="'.($v2['level'] + 1).'" />
 </td></tr>
 			<tr><td class="center w100p bold '.($k2%2==0 ? 'bgreen white' : '').'">Phường</td><td class="'.($k2%2==0 ? 'bgreen white' : '').'"><input type="text" name="new['.$v2['id'].'][ph]" class="form-control tagsinput" placeholder="Danh sách phường"></td></tr>
            <tr><td class="center w100p bold '.($k2%2==0 ? 'bgreen white' : '').'"  >Xã</td><td class="'.($k2%2==0 ? 'bgreen white' : '').'" ><input type="text" name="new['.$v2['id'].'][xa]" class="form-control tagsinput" placeholder="Danh sách xã"></td></tr>
           
            <tr><td class="center bold '.($k2%2==0 ? 'bgreen white' : '').'" >Thị trấn</td><td class="'.($k2%2==0 ? 'bgreen white' : '').'" ><input type="text" name="new['.$v2['id'].'][tt]" class="form-control tagsinput" placeholder="Danh sách thị trấn"></td></tr>';
	}}
}
?>           
           
           </tbody>
           </table>
        </div>
    </div>
  </div>

</div>


</form>
</div>



</div> 