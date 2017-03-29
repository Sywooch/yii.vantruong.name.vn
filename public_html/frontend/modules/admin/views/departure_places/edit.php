<div class="table-responsive " style="padding-top: 0px">

<div class="fl100 edit-form">
<form id="editFormContent" method="post" class="form-horizontal edit-form-left" role="form" enctype="multipart/form-data">
<input type="hidden" class="currentTab" name="currentTab" value="" />
<input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
<input type="hidden" name="formSubmit" value="true"  />
<input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>" /> 
<!-- Nav tabs -->
<ul class="nav form-edit-tab nav-tabs" role="tablist">

<li role="presentation" class="active"><a href="#tab-genaral" role="tab" data-toggle="tab">Thông tin chung</a></li>
<?php 
if(isset($v['is_start']) && $v['is_start'] == 1){
	echo '<li role="presentation" class=""><a href="#tab-destination" role="tab" data-toggle="tab">Điểm đến</a></li>';
}
if(isset($v['is_destination']) && $v['is_destination'] == 1){
	echo '<li role="presentation" class=""><a href="#tab-depart" role="tab" data-toggle="tab">Điểm khởi hành</a></li>';
}
?>  
 
 
  <li role="presentation" class=""><a href="#tab-help" role="tab" data-toggle="tab">Hướng dẫn</a></li>

</ul>

<!-- Tab panes -->
<div class="tab-content edit-form-content-tab">

  <div role="tabpanel" class="tab-panel active" id="tab-genaral">

        <div class="p-content">
        <div class="row">
        <div class="col-sm-12">
 
         <div class="form-group">
          <label for="inputSeoTitle" class="col-sm-12 control-label">Tiêu đề</label>
          <div class="col-sm-12">

            <?php
            if(Yii::$app->controller->action->id == 'add'){
            	echo '<textarea id="text0001" class="form-control required" placeholder="Tên viết cách nhau bởi dấu ;" name="f[name]"></textarea>';
            }else 
              echo '<input type="text" name="f[name]" class="form-control required" placeholder="Tiêu đề " value="'.(isset($v['name']) ? $v['name']  : '').'" />';
            ?>

          </div>
         </div>
          
         
         <div class="form-group">
          <label for="inputSeoTitle" class="col-sm-12 control-label">Tên viết tắt</label>
          <div class="col-sm-12">

            <?php
            if(Yii::$app->controller->action->id == 'add'){
            	echo '<textarea id="text0002" class="form-control  " placeholder="Tên viết cách nhau bởi dấu ;" name="f[short_name]"></textarea>';
            }else
              echo '<input type="text" name="f[short_name]" class="form-control" placeholder="Tên viết tắt" value="'.(isset($v['short_name']) ? $v['short_name']  : '').'" />';
            ?>
 
          </div>
         </div>
         
        
         
         <div class="form-group">
         <div class="col-sm-6"><div class="row"><label for="inputLink" class="col-sm-12 control-label">Danh mục</label>
          <div class="col-sm-12">
            
		      <select name="f[type]"  class="form-control select2 " data-placeholder="Danh mục" style="width: 100%">
		      
		     <?php 
		      
		     if(!empty($locals_type)){
		     	foreach ($locals_type as $k1=>$v1){
		     		echo '<option value="'.$v1['id'].'" '.(isset($v['type']) && $v['type'] == $v1['id'] ? 'selected="selected"' : '').'>'. uh($v1['name']).'</option>';
		     	}
		     }
		     ?>
		      </select>
		      
          </div></div></div>
         <div class="col-sm-6"><div class="row"><label for="inputLink" class="col-sm-12 control-label">Tỉnh thành</label>
          <div class="col-sm-12">
            
		      <select name="f[parent_id]"  class="form-control select2 " data-placeholder="Danh mục" style="width: 100%">
		      <option value="0" <?php echo isset($v['parent_id']) && $v['parent_id'] == 0 ? 'selected="selected"' : '';?> > --- Không chọn ---</option>
		     <?php 
		      
		     if(!empty($locals)){
		     	foreach ($locals as $k1=>$v1){
		     		echo '<option value="'.$v1['id'].'" '.(isset($v['parent_id']) && $v['parent_id'] == $v1['id'] ? 'selected="selected"' : '').'>'.spc($v1['level']).uh($v1['name']).'</option>';
		     	}
		     }
		     ?>
		      </select>
		      
          </div></div></div>
          
        </div>
        
         
    
       
         <?php echo getCheckBox(array(
            'name'=>array('f[is_active]','f[is_start]','f[is_start1]','f[is_destination]'),
            'value'=>array( 
            		isset($v['is_active']) ? $v['is_active'] : 1, 
            		isset($v['is_start']) ? $v['is_start'] : 0,
            		isset($v['is_start1']) ? $v['is_start1'] : 0,
            		isset($v['is_destination']) ? $v['is_destination'] : 0),
         		 
            'label'=>array('Kích hoạt','Điểm KH','Điểm KH (QT)','Điểm đến'),'type'=>'n02'
        ));?>
 

         </div>
         
    </div></div></div>
 
 
 <div role="tabpanel" class="tab-panel" id="tab-destination" style="">
 <div class="fl100">
        <div class="p-content f12e">
           <?php 
 
$f = $l['listItem'];
	if(!empty($f)){ 
		echo '<div class="clear	">';
		foreach ($f as $kf=>$vf){
			$vf['level'] = isset($vf['level']) ? $vf['level'] : 0;
			echo '<div class="inline-block vtop fl" style="width:33%"><div class="checkbox m-level-'.($vf['level']+1).'"><label><input data-role="prs_ckc_forms" data-parent="0" data-id="'.$vf['id'].'" '.(in_array($vf['id'], $selected1) ? 'checked' : '').' name="fDestination[]" value="'.$vf['id'].'" type="checkbox"><b class="'.($vf['type'] == 1 ? 'green' : 'red').'">'.uh($vf['name']).'</b></label></div>';			 
			echo '</div>';
		}
		echo '</div>';
	}
//}
?>
        </div>
    </div>
 
 </div>
 
 <div role="tabpanel" class="tab-panel" id="tab-depart" style="">
 <div class="fl100">
        <div class="p-content f12e">
           <?php 
 
$f = $l2['listItem'];
	if(!empty($f)){ 
		echo '<div class="clear	">';
		foreach ($f as $kf=>$vf){
			$vf['level'] = isset($vf['level']) ? $vf['level'] : 0;
			echo '<div class="inline-block vtop fl" style="width:33%"><div class="checkbox m-level-'.($vf['level']+1).'"><label><input data-role="prs_ckc_forms" data-parent="0" data-id="'.$vf['id'].'" '.(in_array($vf['id'], $selected2) ? 'checked' : '').' name="fDeparture[]" value="'.$vf['id'].'" type="checkbox"><b class="'.($vf['type'] == 1 ? 'green' : 'red').'">'.uh($vf['name']).'</b></label></div>';			 
			echo '</div>';
		}
		echo '</div>';
	}
//}
?>
        </div>
    </div>
 
 </div>
  <div role="tabpanel" class="tab-panel" id="tab-help" >
   
 
 
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