<?php 
 
if(!empty($lx['listItem'])){
	echo '<div class="hide" id="ajax_select_htype"><select data-search="hidden" class="chosen from-control input-sm" name="f[0][parent_id]" style="width:100%">';
	foreach ($lx['listItem'] as $k=>$v){
		echo '<option  value="'.$v['id'].'">'.$v['title'].'</option>';
	}
	echo '</select></div>';
}
 
if(!empty($lx1['listItem'])){
	echo '<div class="hide" id="ajax_select_htype_3"><select data-search="hidden" class="chosen from-control input-sm" name="f1[0][parent_id]" style="width:100%">';
	foreach ($lx1['listItem'] as $k=>$v){
		echo '<option  value="'.$v['id'].'">'.$v['title'].'</option>';
	}
	echo '</select></div>';
} 
?> 
<div class="table-responsive ">

<div class="fl100 edit-form">
 <form id="editFormContent" method="post" class="form-horizontal edit-form-left " role="form" enctype="multipart/form-data">
 <input type="hidden" class="currentTab" name="currentTab" value="" />
 <input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
 <input type="hidden" name="formSubmit" value="true"  />
 <input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"/>
 <div class="delete_item hide"></div>

    <!-- Nav tabs -->
<ul class="nav form-edit-tab nav-tabs" role="tablist">
<li role="presentation" class="active"><a href="#tab-category" role="tab" data-toggle="tab">Danh mục</a></li>
	<li role="presentation" class=""><a href="#tab-general" role="tab" data-toggle="tab">Thiết lập mùa / ngày lễ</a></li>
	<li role="presentation" class=""><a href="#tab-weeken" role="tab" data-toggle="tab">Thiết lập cuối tuần</a></li>
	<?php 
	if(getParam('tab') == 'setting' && getParam('category_id')>0){
		echo '<li role="presentation" class=""><a href="#tab-setting" role="tab" data-toggle="tab">Thiết lập </a></li>';
	}
	?>
</ul>
<!-- Tab panes -->
<div class="tab-content edit-form-content-tab">

  <div role="tabpanel" class="tab-panel " id="tab-general">
        <div class="p-content">
        <div class="row">
        <div class="col-sm-12"> 
  
        <div class="form-group ">
       <table class="table table-hover table-bordered vmiddle table-striped"> <thead> 
       
       <?php
       echo '<tr> <th>#</th> <th >Từ ngày</th>
		<th class=" ">Đến ngày</th>  
		<th class=" ">Tiêu đề</th>  
		 
		<th class="center ">Mặc định</th> 
		<th class="center"></th></tr> 
		</thead> <tbody> ';
       $itype = 'text' ;
       if(!empty($l['listItem'])){
       foreach ($l['listItem'] as $k=>$v){
       	  
       	echo '<tr> <th scope="row">'.($k+1).'</th> 
 		<td><input data-format="DD/MM/YYYY" type="'.$itype.'" name="f['.$k.'][from_date]" value="'.date("d/m/Y H:i:s",strtotime($v['from_date'])).'" class="form-control input-sm datepicker"/></td> 
		<td><input data-format="DD/MM/YYYY" type="'.$itype.'" name="f['.$k.'][to_date]" value="'.date("d/m/Y H:i:s",strtotime($v['to_date'])).'" class="form-control input-sm datepicker"/></td> 
		<td><input type="'.$itype.'" name="f['.$k.'][title]" value="'.$v['title'].'" class="form-control input-sm"/></td> 
		 
		 <input type="hidden" name="f['.$k.'][type_id]" value="'.SEASON_TYPE_SERVICE.'">  
		<td class="center"><input type="checkbox" name="f['.$k.'][is_default]" '.($v['is_default'] == 1 ? 'checked' : '').'/></td>  
 		<td class="center"><input type="hidden" name="f['.$k.'][id]" value="'.$v['id'].'"/><input type="hidden" name="existed[]" value="'.$v['id'].'"/><i title="Xóa" class="glyphicon glyphicon-trash pointer" onclick="add_delete_item('.$v['id'].');removeTrItem(this);"></i></td>         
        </tr> ';
       }}
       echo '<tr><td colspan="5"></td><td class="center"><button data-count="'.($l['total_records']).'" onclick="add_more_holidays(this);" title="Thêm ngày nghỉ" type="button" class="btn btn-default input-sm"><i class="glyphicon glyphicon-plus"></i></button></td></tr>'; 
       echo '</tbody>';
       ?>               
        </table>  
<?php 

?>
 
           
        </div>
              
         </div>
 
    </div></div></div>
  
<div role="tabpanel" class="tab-panel active" id="tab-category">

        <div class="p-content">
        <div class="row">
        <div class="col-sm-12"> 
  
        <div class="form-group ">
       <table class="table table-hover table-bordered vmiddle table-striped"> <thead> 
       
       <?php
       echo '<tr> <th>#</th> <th >Tiêu đề</th>
		<th class=" ">Ký hiệu</th>  
		<th class=" ">Loại</th><th class="center">Dịch vụ áp dụng</th>     
		<th class="center"></th></tr> 
		</thead> <tbody> ';
       $itype = 'text' ;
       $lx2 = $model->getListx(array('type_id'=>[]));
     
       if(!empty($lx2['listItem'])){
       foreach ($lx2['listItem'] as $k=>$v){
       	  
       	echo '<tr> <th scope="row">'.($k+1).'</th> 
		<td><input type="'.$itype.'" name="fx['.$k.'][title]" value="'.$v['title'].'" class="form-control input-sm"/></td> 
		<td><input type="'.$itype.'" name="fx['.$k.'][code]" value="'.$v['code'].'" class="form-control input-sm"/></td> 
		<td><select class="form-control input-sm select2-hide-search" name="fx['.$k.'][type_id]">';
       	foreach ($model->get_season_type_category() as $k1=>$v1){
 			echo '<option value="'.$v1['id'].'" '.($v['type_id'] == $v1['id'] ? 'selected' : '').'>'.$v1['title'].'</option>';
       	}
 		 
 		echo '</select></td> 
 		  
 		<td class="center"><a href="'.cu([CONTROLLER_TEXT.DS]).'?tab=setting&category_id='.$v['id'].'#tab-setting" class="center">'.implode(' | ', $model->get_selected_supplier_holiday($v['id'],2)).'</a></td>
 		 
 		<td class="center"><input type="hidden" name="fx['.$k.'][id]" value="'.$v['id'].'"/><input type="hidden" name="existed[]" value="'.$v['id'].'"/><i title="Xóa" class="glyphicon glyphicon-trash pointer" onclick="add_delete_item1('.$v['id'].');removeTrItem(this);"></i></td>         
        
 		</tr> ';
       }}
       echo '<tr><td colspan="5"></td><td class="center"><button data-type="2" data-count="'.($lx2['total_records']).'" onclick="add_more_holidays_categorys(this);" title="Thêm phương tiện" type="button" class="btn btn-default input-sm"><i class="glyphicon glyphicon-plus"></i></button></td></tr>'; 
       echo '</tbody>';
       ?>               
        </table>  
         
 
           
        </div>
              
         </div>
 
    </div></div></div>
    <?php 
    if(getParam('tab') == 'setting' && getParam('category_id')>0){
    	include_once '_forms/'.getParam('tab').'.php';
    }
    ?>
    
    <div role="tabpanel" class="tab-panel " id="tab-weeken">
        <div class="p-content">
        <div class="row">
        <div class="col-sm-12"> 
  
        <div class="form-group ">
       <table class="table table-hover table-bordered vmiddle table-striped"> <thead> 
       
       <?php
       echo '<tr> <th>#</th> <th colspan=2>Từ ngày</th>
		<th colspan=2 class=" ">Đến ngày</th>  
		<th class=" ">Tiêu đề</th>   
		<th class="center ">Kiểu DL</th> 
		<th class="center ">Mặc định</th> 
		<th class="center"></th></tr> 
		</thead> <tbody> ';
       $itype = 'text' ;
       $l1 = $model->get_weekend(array('count'=>true));
       if(!empty($l1['listItem'])){
       foreach ($l1['listItem'] as $k=>$v){
       	  
       	echo '<tr> <th scope="row">'.($k+1).'</th> 
 		<td><select name="f1['.$k.'][from_date]" class="form-control input-sm select2-hide-search">';
		for($i=0;$i<7;$i++){
			echo '<option '.($v['from_date'] == $i ? 'selected' : '').' value="'.$i.'">'.(
					$i == 0 ? 'Chủ nhật' : 'Thứ ' . ($i+1)
					).'</option>';
		}
		echo '</select></td> 
		<td><input type="'.$itype.'" name="f1['.$k.'][from_time]" value="'.date("H:i:s",strtotime($v['from_time'])).'" class="form-control input-sm timepicker"/></td> 
 <td><select name="f1['.$k.'][to_date]" class="form-control input-sm select2-hide-search">';
		for($i=0;$i<7;$i++){
			echo '<option '.($v['to_date'] == $i ? 'selected' : '').' value="'.$i.'">'.(
					$i == 0 ? 'Chủ nhật' : 'Thứ ' . ($i+1)
					).'</option>';
		}
		echo '</select></td> 
		<td><input type="'.$itype.'" name="f1['.$k.'][to_time]" value="'.date("H:i:s",strtotime($v['to_time'])).'" class="form-control input-sm timepicker"/></td> 
 <td><input type="'.$itype.'" name="f1['.$k.'][title]" value="'.$v['title'].'" class="form-control input-sm"/></td> 
 <td><select data-search="hidden" class="chosen from-control input-sm" name="f1[24][type_id]" style="width:100%">
			<option '.($v['type_id'] == SEASON_TYPE_WEEKEND ? 'selected' : '').' value="'.SEASON_TYPE_WEEKEND.'">Cuối tuần</option>
			<option '.($v['type_id'] == SEASON_TYPE_WEEKDAY ? 'selected' : '').' value="'.SEASON_TYPE_WEEKDAY.'">Ngày thường</option></select></td>
		<td class="center"><input type="checkbox" name="f1['.$k.'][is_default]" '.($v['is_default'] == 1 ? 'checked' : '').'/></td> 
 		<td class="center"><input type="hidden" name="f1['.$k.'][id]" value="'.$v['id'].'"/><input type="hidden" name="existed[]" value="'.$v['id'].'"/><i title="Xóa" class="glyphicon glyphicon-trash pointer" data-name="delete_item_3" onclick="add_delete_item('.$v['id'].');removeTrItem(this);"></i></td>         
        </tr> ';
       }}
       echo '<tr><td colspan="7"></td><td class="center"><button data-count="'.($l1['total_records']).'" onclick="add_more_weekend(this);" title="Thêm ngày nghỉ" type="button" class="btn btn-default input-sm"><i class="glyphicon glyphicon-plus"></i></button></td></tr>'; 
       echo '</tbody>';
       ?>               
        </table>  
<?php 

?>
 
           
        </div>
              
         </div>
 
    </div></div></div>
</div>


</form>
</div>



</div>

