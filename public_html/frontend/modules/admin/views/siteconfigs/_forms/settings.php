<?php 
//view($v);
 
?> 
<div class="table-responsive ">
<div class="fl100 edit-form">
<form id="editFormContent" method="post" class="form-horizontal edit-form-left " role="form" enctype="multipart/form-data">
<input type="hidden" class="currentTab" name="currentTab" value="" />
<input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
<input type="hidden" name="formSubmit" value="true"  />
<input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"/>
    <!-- Nav tabs -->
<ul class="nav form-edit-tab nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#tab-general" role="tab" data-toggle="tab">Thiết lập thuộc tính, các trường hiển thị</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content edit-form-content-tab">

  <div role="tabpanel" class="tab-panel active" id="tab-general">

        <div class="p-content">
        <div class="row">
        <div class="col-sm-12">
        <?php 
        $field = 'f[customers]';
foreach (getCustomerTypeID() as $c){
	$c['code'] = 'customers'; 
	echo '<div class="block-example">
         <span class="f12e block-title">'.uh($c['title']).'</span>
                        
<div class="form-group ">  <div class="col-sm-12">    
 
 
<table class="table table-bordered vmiddle tabel-hover table-css1">
<thead>
<tr> <th class="center w200p">Thuộc tính</th><th class="center w100p">Trạng thái</th><th class="center">Cài đặt khác</th><th class="center w300p">Ghi chú</th>
</tr>
</thead>
<tbody>
<tr>
<td rowspan="6" class="center ">Mã số</td>
<td rowspan="6" class="center"> 
<input class="switch-btn" type="checkbox" name="'.$field.'['.$c['id'].'][fields][code]" '.(isset($v[$c['code']][$c['id']]['fields']['code']) && $v[$c['code']][$c['id']]['fields']['code'] == 'on' ? 'checked' : '').'/> 
        		</td>
<td><label data-toggle="tooltip" title="Sử dụng tùy chọn này, khi thêm mới mã số sẽ được đánh tự động">
<input type="checkbox" name="f[customers]['.$c['id'].'][code][auto_code]" '.(isset($v['customers'][$c['id']]['code']['auto_code']) && $v['customers'][$c['id']]['code']['auto_code'] == 'on' ? 'checked' : '').'/> sinh mã tự động</label></td>

<td></td>
</tr>
 <tr>
 

<td><label data-toggle="tooltip" title="Chỉ áp dụng cho quy tắc sinh mã là dạng số và có cùng tiền tố hậu tố - mặc định sẽ sinh mã ngẫu nhiên, không phân biệt thứ tự">
<input type="checkbox" name="f[customers]['.$c['id'].'][code][sort_asc]" '.(isset($v['customers'][$c['id']]['code']['sort_asc']) && $v['customers'][$c['id']]['code']['sort_asc'] == 'on' ? 'checked' : '').'/> sinh mã theo thứ tự 0-9</label></td>

<td></td>
</tr>
<tr>
 

<td> 
<input type="hidden" name="f[customers]['.$c['id'].'][controller_code]" value="'.$c['id'].'" />
<input name="f[customers]['.$c['id'].'][code][code_length]" value="'.(isset($v['customers'][$c['id']]['code']['code_length']) ? $v['customers'][$c['id']]['code']['code_length'] : 6).'" maxlength="2" data-toggle="tooltip" title="Độ dài mã, bao gồm cả tiền tố hậu tố, tối đa 32 ký tự" type="text" placeholder="Độ dài mã (ký tự)" class="form-control number-format"/> </td>

<td>Độ dài mã số bao gồm cả tiền tố và hậu tố, tối đa 32 ký tự</td>
</tr> 
<tr>
 

<td> <input name="f[customers]['.$c['id'].'][code][code_before]" value="'.(isset($v['customers'][$c['id']]['code']['code_before']) ? $v['customers'][$c['id']]['code']['code_before'] : '').'" data-toggle="tooltip" title="Dãy ký tự bắt đầu mã số" type="text" placeholder="Tiền tố" class="form-control"/> </td>

<td>Ký tự cố định gán trước mã các mã số</td>
</tr> 
<tr>

<td> <input name="f[customers]['.$c['id'].'][code][code_after]" value="'.(isset($v['customers'][$c['id']]['code']['code_after']) ? $v['customers'][$c['id']]['code']['code_after'] : '').'" data-toggle="tooltip" title="Dãy ký tự kết thúc mã số" type="text" placeholder="Hậu tố" class="form-control"/> </td>
<td>Ký tự cố định gán sau mỗi mã</td>
</tr> 
<tr>

<td> <input name="f[customers]['.$c['id'].'][code][code_regex]" value="'.(isset($v['customers'][$c['id']]['code']['code_regex']) ? $v['customers'][$c['id']]['code']['code_regex'] : '1234567890').'" data-toggle="tooltip" title="Dãy ký tự dùng để sinh mã, dùng A-Z hoặc 0-9 hoặc kết hợp cả chữ và số" type="text" placeholder="Ký tự sinh mã, vd:1234567890" class="form-control"/> </td>
<td>Chuỗi ký tự dùng để sinh mã</td>
</tr> 
 
</tbody>
</table> 
     
</div>        </div>
 
 
    
        
 

        </div>';
}
?> 
<?php 
 
foreach (\app\modules\admin\models\AdForms::getUserForms(['is_content'=>1]) as $c){
	echo '<div class="block-example">
         <span class="f12e block-title">Cài đặt Thuộc tính & các trường thông tin form <b class="underline red">'.$c['title'].'</b></span>
        
        
        
<div class="form-group ">  <div class="col-sm-12">    
 
 
<table class="table table-bordered vmiddle taber-hover table-css1">
<thead>
<tr> <th class="center w200p">Thuộc tính</th><th class="center w100p">Trạng thái</th><th class="center">Cài đặt khác</th><th class="center w300p">Ghi chú</th>
</tr>
</thead>
<tbody>';
	$field = 'f['.$c['code'].']';
	//$field
	switch ($c['code']){
		case 'products':
			echo '<tr><td rowspan="6" class="center ">Mã sản phẩm</td>';
			echo '<td rowspan="6" class="center"> 
<input class="switch-btn" type="checkbox" name="'.$field.'[fields][code]" '.(isset($v[$c['code']]['fields']['code']) && $v[$c['code']]['fields']['code'] == 'on' ? 'checked' : '').'/> 
        		</td>';
			echo '<td><label data-toggle="tooltip" title="Sử dụng tùy chọn này, khi thêm mới mã số sẽ được đánh tự động">
<input type="checkbox" name="'.$field.'[code][auto_code]" '.(isset($v[$c['code']]['code']['auto_code']) && $v[$c['code']]['code']['auto_code'] == 'on' ? 'checked' : '').'/> sinh mã tự động</label></td>

<td></td>
</tr>';
			echo '<tr><td><label data-toggle="tooltip" title="Chỉ áp dụng cho quy tắc sinh mã là dạng số và có cùng tiền tố hậu tố - mặc định sẽ sinh mã ngẫu nhiên, không phân biệt thứ tự">
<input type="checkbox" name="'.$field.'[code]][sort_asc]" '.(isset($v[$c['code']]['code']['sort_asc']) && $v[$c['code']]['code']['sort_asc'] == 'on' ? 'checked' : '').'/> sinh mã theo thứ tự 0-9</label></td>

<td></td>
</tr>';
			echo '<tr><td> 
<input type="hidden" name="f[code][controller_code]" value="code" />
<input name="'.$field.'[code][code_length]" value="'.(isset($v[$c['code']]['code']['code_length']) ? $v[$c['code']]['code']['code_length'] : 6).'" maxlength="2" data-toggle="tooltip" title="Độ dài mã, bao gồm cả tiền tố hậu tố, tối đa 32 ký tự" type="text" placeholder="Độ dài mã (ký tự)" class="form-control number-format"/> </td>

<td>Độ dài mã số bao gồm cả tiền tố và hậu tố, tối đa 32 ký tự</td>
</tr>';
			echo '<tr> <td> <input name="'.$field.'[code][code_before]" value="'.(isset($v[$c['code']]['code']['code_before']) ? $v[$c['code']]['code']['code_before'] : '').'" data-toggle="tooltip" title="Dãy ký tự bắt đầu mã số" type="text" placeholder="Tiền tố" class="form-control"/> </td>

<td>Ký tự cố định gán trước mã các mã số</td>
</tr> ';
			echo '<tr>
<td> <input name="'.$field.'[code][code_after]" value="'.(isset($v[$c['code']]['code']['code_after']) ? $v[$c['code']]['code']['code_after'] : '').'" data-toggle="tooltip" title="Dãy ký tự kết thúc mã số" type="text" placeholder="Hậu tố" class="form-control"/> </td>
<td>Ký tự cố định gán sau mỗi mã</td>
</tr> ';
			echo '<tr>
<td> <input name="'.$field.'[code][code_regex]" value="'.(isset($v[$c['code']]['code']['code_regex']) ? $v[$c['code']]['code']['code_regex'] : '1234567890').'" data-toggle="tooltip" title="Dãy ký tự dùng để sinh mã, dùng A-Z hoặc 0-9 hoặc kết hợp cả chữ và số" type="text" placeholder="Ký tự sinh mã, vd:1234567890" class="form-control"/> </td>
<td>Chuỗi ký tự dùng để sinh mã</td>
</tr>';
			echo '<tr>
<td  class="center ">Barcode</td>

<td class="center"> 
<input class="switch-btn" type="checkbox" name="'.$field.'[fields][barcode]" '.(isset($v[$c['code']]['fields']['barcode']) && $v[$c['code']]['fields']['barcode'] == 'on' ? 'checked' : '').'/> </td>

<td></td><td></td>
</tr>';
			echo '<tr>
<td  class="center ">Đơn giá</td>

<td class="center"> 
<input class="switch-btn" type="checkbox" name="'.$field.'[fields][price2]" '.(isset($v[$c['code']]['fields']['price2']) && $v[$c['code']]['fields']['price2'] == 'on' ? 'checked' : '').'/> </td>

<td></td><td></td>
</tr>';
			echo '<tr>
<td  class="center ">Giá so sánh (giá cũ)</td>

<td class="center"> 
<input class="switch-btn" type="checkbox" name="'.$field.'[fields][price1]" '.(isset($v[$c['code']]['fields']['price1']) && $v[$c['code']]['fields']['price1'] == 'on' ? 'checked' : '').'/> </td>

<td></td><td></td>
</tr>		
<tr>
<td  class="center ">Bảng giá</td>

<td class="center"> 
<input type="checkbox" name="'.$field.'[fields][prices_list]" '.(isset($v[$c['code']]['fields']['prices_list']) && $v[$c['code']]['fields']['prices_list'] == 'on' ? 'checked' : '').'/> </td>

<td></td><td>Sử dụng bảng giá nếu sản phẩm có cài đặt nhiều mức giá.</td>
</tr>';
			echo '<tr>
<td  class="center ">Thương hiệu</td>

<td class="center"> 
<input class="switch-btn" type="checkbox" name="'.$field.'[fields][producers]" '.(isset($v[$c['code']]['fields']['producers']) && $v[$c['code']]['fields']['producers'] == 'on' ? 'checked' : '').'/> </td>

<td></td><td></td>
</tr>	
<tr>
<td  class="center ">Nhà sản xuất</td>

<td class="center"> 
<input class="switch-btn" type="checkbox" name="'.$field.'[fields][vendors]" '.(isset($v[$c['code']]['fields']['vendors']) && $v[$c['code']]['fields']['vendors'] == 'on' ? 'checked' : '').'/> </td>

<td></td><td></td>
</tr>	
		<tr>
<td  class="center ">Xuất xứ</td>

<td class="center"> 
<input class="switch-btn" type="checkbox" name="'.$field.'[fields][orignal]" '.(isset($v[$c['code']]['fields']['orignal']) && $v[$c['code']]['fields']['orignal'] == 'on' ? 'checked' : '').'/> </td>

<td></td><td></td>
</tr>	

		
		
				
				<tr>
<td  class="center ">File đính kèm</td>

<td class="center"> 
<input class="switch-btn" type="checkbox" name="'.$field.'[fields][attach]" '.(isset($v[$c['code']]['fields']['attach']) && $v[$c['code']]['fields']['attach'] == 'on' ? 'checked' : '').'/> </td>

<td></td><td></td>
</tr>	
		
		<tr>
<td  class="center ">Kho hàng</td>

<td class="center"> 
<input class="switch-btn" type="checkbox" name="'.$field.'[fields][stores]" '.(isset($v[$c['code']]['fields']['stores']) && $v[$c['code']]['fields']['stores'] == 'on' ? 'checked' : '').'/> </td>

<td></td><td></td>
</tr>';
			echo '<tr>
<td  class="center ">Text hiển thị khi giá = 0</td>
<td></td>
<td class="center">';
			$langs = Yii::$app->zii->getUserLanguages();
if(!empty($langs)){
foreach ($langs as $k3=>$v3){				
	echo count($langs) > 1 ? '<p class="bold help-block aleft">'.$v3['title'].'</p>' : '';
	echo '<input type="text" placeholder="'.getTextTranslate(2,$v3['code']).'"  class="form-control" name="'.$field.'[prices][zero]['.$v3['code'].']" value="'.(isset($v[$c['code']]['prices']['zero'][$v3['code']]) ? $v[$c['code']]['prices']['zero'][$v3['code']] : '').'"/>';
	echo $k3 < count($langs)-1 ? '<hr/>' : ''; 
}}
echo '</td>

<td></td>
</tr>';
			break;
	}
	
echo '		
		

		

 



 
 
<tr>
<td  class="center ">Tiêu đề ngắn</td>

<td class="center"> 
<input class="switch-btn" type="checkbox" name="'.$field.'[fields][short_title]" '.(isset($v[$c['code']]['fields']['short_title']) && $v[$c['code']]['fields']['short_title'] == 'on' ? 'checked' : '').'/> </td>

<td></td><td></td>
</tr>
		
<tr>
<td class="center ">Thông số đặc tả</td>

<td class="center"> 
<input class="switch-btn" type="checkbox" name="'.$field.'[fields][description]" '.(isset($v[$c['code']]['fields']['description']) && $v[$c['code']]['fields']['description'] == 'on' ? 'checked' : '').'/> </td>

<td></td><td>Đoạn thông tin gắn liền với tiêu đề bài viết</td>
</tr>
		
		
	<tr>
<td  class="center ">Bộ lọc</td>

<td class="center"> 
<input class="switch-btn" type="checkbox" name="'.$field.'[fields][filters]" '.(isset($v[$c['code']]['fields']['filters']) && $v[$c['code']]['fields']['filters'] == 'on' ? 'checked' : '').'/> </td>

<td></td><td>Sử dụng bộ lọc</td>
</tr>		
		
 
			
		
			
</tbody>
</table> 
     
</div>        </div>

 
    
        
 

        </div>';
}
?> 
         
        
    
            
         
        
                
        
        
        
        
         </div>
          



    </div></div></div>
 

</div>


</form>
</div>



</div>

