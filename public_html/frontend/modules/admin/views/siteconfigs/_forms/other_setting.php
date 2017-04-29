 
<div class="table-responsive ">
<div class="fl100 edit-form">
<form id="editFormContent" method="post" class="form-horizontal edit-form-left " role="form" enctype="multipart/form-data">
<input type="hidden" class="currentTab" name="currentTab" value="" />
<input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
<input type="hidden" name="formSubmit" value="true"  />
<input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"/>
    <!-- Nav tabs -->
<ul class="nav form-edit-tab nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#tab-general" role="tab" data-toggle="tab">Thiết lập các thuộc tính khác</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content edit-form-content-tab">

  <div role="tabpanel" class="tab-panel active" id="tab-general">

        <div class="p-content">
        <div class="row">
        <div class="col-sm-12">
 
         <div class="block-example">
         <span class="f12e block-title">Tiền tệ</span>
        
        
        
<div class="form-group ">  <div class="col-sm-12">    
<p class="help-block underline">Các loại tiền tệ đang sử dụng:</p>
<?php 
$existed_id = $lx = array();
 
?>
<table class="table table-bordered vmiddle taber-hover">
<thead>
<tr><th class="center w50p">#</th><th>Loại tiền</th><th class="center">Mã quốc tế</th><th class="center">Ký hiệu</th><th class="center">Quy cách hiển thị</th><th class="center">Hiển thị ký hiệu</th><th class="center">Mặc định</th><th></th>
</tr>
</thead>
<tbody>
 
<?php 
$selected_list=[];
if(isset($v['currency']['list']) && !empty($v['currency']['list'])){
	$lx = $v['currency']['list'];
	$i = $k1 = 0;
	foreach($v['currency']['list'] as $kx=>$v1){
		if($i == 0){
			$selected_list =  $v1;
		}
		$existed_id[] = $v1['id'];
		if(isset($v['currency']['default']) && $v['currency']['default'] == $v1['id']){
			$selected_list = $v1;
		}
		echo '<tr><td class="center">'.($k1+1).'
 		<input type="hidden" name="f[currency][list]['.$k1.'][id]" value="'.$v1['id'].'" />
 		<input type="hidden" name="f[currency][list]['.$k1.'][title]" value="'.$v1['title'].'" />
 		<input type="hidden" name="f[currency][list]['.$k1.'][code]" value="'.$v1['code'].'" />
 		<input type="hidden" name="f[currency][list]['.$k1.'][symbol]" value="'.$v1['symbol'].'" />
 		<input type="hidden" name="f[currency][list]['.$k1.'][decimal_number]" value="'.(isset($v1['decimal_number']) ? $v1['decimal_number'] : 2).'" /> 
 		</td><td class="">'.$v1['title'].'</td><td class="center">'.$v1['code'].'</td><td class="center">'.$v1['symbol'].'</td>';
		echo '<td><select class="form-control input-sm select2 " data-search="hidden" name="f[currency][list]['.$k1.'][display]">
          <option '.(isset($v1['display']) && $v1['display'] == 1 ? 'selected' : '').' value="1">Hiển thị sau số tiền (10,000đ)</option>
          <option '.(isset($v1['display']) && $v1['display'] == -1 ? 'selected' : '').' value="-1">Hiển thị trước số tiền ($10,000)</option>
          </select></td>';
		echo '<td><select class="form-control input-sm select2 " data-search="hidden" name="f[currency][list]['.$k1.'][display_type]">  
          <option '.(isset($v1['display_type']) && $v1['display_type'] == 1 ? 'selected' : '').' value="1">Hiển thị mã quốc tế ('.$v1['code'].')</option>
          <option '.(isset($v1['display_type']) && $v1['display_type'] == 2 ? 'selected' : '').' value="2">Hiển thị symbol ('.$v1['symbol'].')</option>
          </select></td>'; 
		echo '<td class="center"><input onchange="setDefaultCurrency(this)" '.(isset($v['currency']['default']) && $v['currency']['default'] == $v1['id'] ? 'checked' : '').' type="radio" name="f[currency][default]" value="'.$v1['id'].'"/></td>';
 		echo '<td class="center"><i class="glyphicon glyphicon-trash pointer" onclick="removeTrItem(this)"></i></td></tr>';
	$k1++;
	}
	
}
if(!isset($selected_list['display'])) $selected_list['id'] = 1;
if(!isset($selected_list['display'])) $selected_list['display'] = 1;
if(!isset($selected_list['display_type'])) $selected_list['display_type'] = 1;
if(!isset($selected_list['decimal_number'])) $selected_list['decimal_number'] = 0;


echo '<tr class="ajax-html-result-before-list-vehicles"><td colspan="7" ></td>';         		       	
echo '<td colspan="1" class="center"><button data-existed="'.implode(',', $existed_id).'" data-quantity="1" data-count="'.count($lx).'" onclick="add_more_currency(this);" data-name="c" title="Thêm " type="button" class="btn btn-default input-sm btn-add-more"><i class="glyphicon glyphicon-plus"></i></button></td></tr>';

?>

</tbody>
</table> 
<?php 
if(!empty($selected_list)){
echo '<input type="hidden" class="input-currency-id" value="'.$selected_list['id'].'" name="f[currency][id]"/>';
echo '<input type="hidden" class="input-currency-display" value="'.$selected_list['display'].'" name="f[currency][display]"/>';
echo '<input type="hidden" class="input-currency-display-type" value="'.$selected_list['display_type'].'" name="f[currency][display_type]"/>';
echo '<input type="hidden" class="input-currency-decimal-number" name="f[currency][decimal_number]" value="'.$selected_list['decimal_number'].'" />';
}
?>       
</div>        </div>
<hr>
 
    
        
 

        </div>
        
   <div class="block-example">
         <span class="f12e block-title">Ứng dụng facebook</span>
        <?php 
        $key = 'facebook_app';
        echo '<div class="form-group ">
          <label for="inputicon" class="col-sm-2 control-label">ID Ứng dụng</label>
          <div class="col-sm-8">
          <input class="form-control input-sm " name="f['.$key.'][appId]" value="'.(isset($v[$key]['appId']) ? $v[$key]['appId'] : '').'" placeholder="ID ứng dụng facebook của bạn"/>  
          </div>
           
        </div>';
        echo '<div class="form-group ">
          <label for="inputicon" class="col-sm-2 control-label">Phiên bản</label>
          <div class="col-sm-8">
          <input class="form-control input-sm " name="f['.$key.'][version]" value="'.(isset($v[$key]['version']) ? $v[$key]['version'] : '').'" placeholder="Phiên bản ứng dụng (vd: v2.7)"/>
          </div>
      
        </div>';
        ?>
 
        </div>
 <div class="block-example">
         <span class="f12e block-title">Link mạng xã hội</span>
        <?php 
        $key = 'social';
        $l = get_social();
        foreach ($l as $k1=>$v1){
        echo '<div class="form-group ">
          <label for="inputicon" class="col-sm-2 control-label">'.$v1['name'].'</label>
          <div class="col-sm-8">
          <input class="form-control input-sm " name="f['.$key.']['.$k1.']" value="'.(isset($v[$key][$k1]) ? $v[$key][$k1] : '').'" placeholder="'.$v1['hint_link'].'"/>  
          </div>
           
        </div>';
        }
        ?>
 
        </div>            
        <div class="block-example">
         <span class="f12e block-title">Khác</span>
        
        <div class="form-group ">
          <label for="inputicon" class="col-sm-2 control-label ptop0">Chứng chỉ số (SSL)</label> 
          <div class="col-sm-8">
          <input type="checkbox"  name="f[ssl]" <?php echo isset($v['ssl']) && $v['ssl'] == 'on' ? 'checked' : '';?> />  
          </div>
           
        </div>
        <?php          
        if(Yii::$app->user->can([ROOT_USER])){ 
        ?>
        <div class="form-group ">
          <label for="inputicon" class="col-sm-2 control-label ptop0">Chứng chỉ số (Domain SSL)</label> 
          <div class="col-sm-8">
          <input type="checkbox" name="f[<?php echo DOMAIN;?>_ssl]" <?php echo (isset($v['ssl']) && $v['ssl'] == 'on') || (isset($v[DOMAIN.'_ssl']) && $v[DOMAIN.'_ssl'] == 'on') ? 'checked' : '';?> />  
          </div>
           
        </div>
   <?php }?>
   
        </div>
        
                
        
        
        
        
         </div>
          



    </div></div></div>
 

</div>


</form>
</div>



</div>

