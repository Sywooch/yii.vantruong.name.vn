<?php 
/*
$string = '[1111] Tiền mặt tại quỹ - VNĐ;[1112] Tiền mặt tại quỹ - Ngoại tệ;[1113] Vàng, bạc, đá quý;[1121] Tiền gửi ngân hàng - VNĐ;[1122] Tiền gửi ngân hàng - Ngoại tệ;[1123] Vàng, bạc, đá quý;[1131] Tiền đồng VN;[1132] Ngoại tệ;[1211] Cổ phiếu;[1212] Trái phiếu ngân quỹ;[1281] Tiền gửi có kỳ hạn;[1282] Đầu tư ngắn hạn khác;[129] Dự phòng giảm giá đầu tư ngắn hạn;[131] Các khoản phải thu khách hàng;[13101] Công nợ phải thu;[1331] Thuế GTGT được khấu trừ của hàng hóa dịch vụ;[1332] Thuế GTGT được khấu trừ của tài sản cố định;[1361] Vốn kinh doanh ở các đơn vị trực thuộc;[1368] Phải thu nội bộ khác;[1381] Tài sản thiếu chờ xử lý;[1385] Phải thu về cổ phần hóa;[1388] Các khoản phải thu khác;[139] Dự phòng phải thu ngắn hạn khó đòi;[141] Tạm ứng;[142] Chi phí trả trước ngắn hạn;[144] Ký cược, ký quỹ ngắn hạn;[151] Hàng mua đang đi đường;[152] Nguyên vật liệu;[153] Công cụ, dụng cụ trong kho;[154] Chi phí SXKD dở dang;[155] Thành phẩm hàng tồn kho;[1561] Giá mua hàng hóa;[1562] Chi phí thu mua hàng hóa;[1567] Hàng hóa bất động sản;[157] Hoàng hóa gửi đi bán;[158] Hàng hóa kho bảo thuế;[159] Dự phòng giảm giá hàng tồn kho;[2111] Nhà cửa, vật kiến trúc;[2112] Máy móc thiết bị;[2113] Phương tiện vận tải, truyền dẫn;[2114] Thiết bị dụng cụ quản lý;[2115] Cây lâu năm, súc vật làm việc và cho sản phẩm;[2118] Tài sản cố định khác;[212] Tài sản cố định thuê tài chính;[2131] Đất và quyền sử dụng đất;[2132] Quyền phát hành;[2133] Bản quyền, bằng sáng chế;[2134] Nhãn hiệu hàng hóa;[2135] Phần mềm máy vi tính;[2136] Giấy phép và giấy phép nhượng quyền;[2138] Tài sản cố định hữu hình khác;[21411] Hao mòn Nhà cửa, vật kiến trúc;[21412] Hao mòn Máy móc thiết bị;[21413] Hao mòn Phương tiện vận tải, truyền dẫn;[21414] Hao mòn Thiết bị dụng cụ quản lý;[21415] Hao mòn Cây lâu năm, súc vật làm việc và cho sản phẩm;[21418] Hao mòn Tài sản cố định khác;[2142] Hap mòn TSCĐ cho thuê tài chính;[2143] Hao mòn TSCĐ vô hình;[2147] Hao mòn bất động sản đầu tư;[217] Bất động sản đầu tư;[2211] Cổ phiếu;[2212] Trái phiếu;[222] Vốn góp liên doanh;[223] Đầu tư vào công ty liên kết;[2281] Cổ phiếu;[2282] Trái phiếu;[2283] Đầu tư dài hạn khác;[229] Dự phòng giảm giá đầu tư dài hạn;[2411] Mua sắm TSCĐ;[2412] Xây dựng cơ bản dở dang;[2413] Sửa chữa lớn TSCĐ;[242] Chi phí trả trước dài hạn;[243] Tài sản thuế thu nhập hoãn lại;[244] Ký cược ký quỹ dài hạn;[311] Vay ngắn hạn;[315] Nợ dài hạn đến hạn trả;[331] Phải trả người bán;[33311] Thuế GTGT đầu ra;[33312] Thuế GTGT hàng nhập khẩu;[3332] Thuế tiêu thụ đặc biệt;[3333] Thuế xuất nhập khẩu;[3334] Thuế thu nhập doanh nghiệp;[3335] Thuế thu nhập cá nhân;[3336] Thuế tài nguyên;[3337] Thuế nhà đất, tiền thuê đất;[3338] Thuế khác;[3339] Phí, lệ phí ＆ các khoản phải nộp khác;[3341] Phải trả công nhân viên;[3348] Phải trả người lao động khác;[335] Chi phí phải trả;[336] Phải trả nội bộ;[337] Thanh toán theo tiến độ kế hoạch hợp đồng xây dựng;[3381] Tài sản thừa chở xử lý;[3382] Kinh phí công đoàn;[3383] Bảo hiểm xã hội;[3384] Bảo hiểm y tế;[3385] Phải trả về cổ phần hóa;[3386] Nhận ký quỹ, ký cược ngắn hạn;[3387] Doanh thu chưa thực hiện;[3388] Phải trả phải nộp khác;[3389] Bảo hiểm thất nghiệp;[341] Vay dài hạn;[342] Nợ dài hạn;[3431] Mệnh giá trái phiếu;[3432] Chiết khấu trái phiếu;[3433] Phụ trách trái phiếu;[344] Nhận ký cược, ký quỹ dài hạn;[347] Thuế thu nhập doanh nghiệp hoãn lại;[351] Quỹ dự phòng trợ cấp mất việc làm;[352] Dự phòng phải trả;[4111] Vốn đầu tư của chủ sở hữu;[4112] Thaặng dư vốn cổ phần;[4118] Vốn khác;[412] Chênh lệch đánh giá lại tài sản;[4131] Chênh lệch tỷ giá hối đoái đánh giá lại cuối năm tài chính;[4132] Chênh lệch tỉ giá hối đoái trong giai đoạn đầu tư xây dựng cơ bản;[414] Quỹ đầu tư phát triển;[415] Quỹ dự phòng tài chính;[418] Các quỹ khác thuộc vốn chủ sở hữu;[419] Cổ phiếu quỹ;[4211] Lợi nhuận chưa phân phối năm trước;[4212] Lợi nhuận chưa phân phối năm nay;[4311] Quỹ khen thưởng;[4312] Quỹ phúc lợi;[4313] Quyỹ phúc lợi đã hình thành tài sản cố định;[441] Nguồn kinh phí sự nghiệp;[461] Nguồn vốn đầu tư xây dựng cơ bản;[466] Nguồn kinh phí đã hình thành tài sản cố định;[5111] Doanh thu bán hàng hóa;[5112] Doanh thu bán các thành phẩm;[5113] Doanh thu cung cấp dịch vụ;[5114] Doanh thu trợ cấp, trợ giá;[5117] Doanh thu kinh doanh bất động sản đầu tư;[5121] Doanh thu bán hàng hóa nội bộ;[5122] Doanh thu bán các thành phẩm nội bộ;[5123] Doanh thu cung cấp dịch vụ nội bộ;[515] Doanh thu hoạt động tài chính;[5211] Chiết khấu bán hàng hóa;[5212] Chiết khấu bán sản phẩm;[5213] Chiết khấu cung cấp dịch vụ;[531] Hàng bán bị trả lại;[532] Giảm giá hàng bán;[6111] Mua nguyên vật liệu;[6112] Mua hàng hóa;[6231] Chi phí nhân công;[6232] Chi phí vật liệu;[6233] Chi phí dụng cụ sản xuất;[6234] Chi phí khấu hao máy thi công;[6237] Chi phí dịch vụ mua ngoài;[6238] Chi phí bằng tiền khác;[6271] Chi phí nhân viên phân xưởng;[6272] Chi phí vật liệu;[6273] Chi phí dụng cụ sản xuất;[6274] Chi phí khấu hoa TSCĐ;[6277] Chi phí dịch vụ mua ngoài;[6278] Chi phí bằng tiền khác;[631] Giá thành sản xuất;[632] Giá vốn hàng bán;[635] Chi phí tài chính;[6411] Chi phí nhân viên bán hàng;[6412] Chi phí vật liệu, bao bì;[6413] Chi phí dụng cụ, đồ dùng;[6414] Chi phí khấu hao TSCĐ;[6415] Chi phí bảo hành;[6417] Chi phí mua ngoài;[6418] Chi phí bằng tiền khác;[6421] Chi phí nhân viên quản lý;[6422] Chi phí phục vụ quản lý;[6423] Chi phí đồ dùng văn phòng;[6424] Chi phí khấu hao;[6425] Thuế, phí và lệ phí;[6426] Chi phí dự phòng;[6427] Chi phí dịch vụ mua ngoài;[6428] Chi phí bằng tiền khác;[711] Thu nhập khác;[811] Chi phí khác;[8211] Chi phí thuế thu nhập hiện hành;[8212] Chi phí thuế thu nhập hoàn lại';
$ex = explode(';', $string);
foreach ($ex as $e){
	$p = explode(']', $e);
	$code = trim(str_replace('[', '', $p[0]));
	$title = trim($p[1]);
	Yii::$app->db->createCommand()-> insert('acc_acount',['id'=>$code,'title'=>$title])->execute();
}
*/
?>

<div class="table-responsive ">

<div class="fl100 edit-form">
 <form id="editFormContent" method="post" class="form-horizontal edit-form-left " role="form" enctype="multipart/form-data">
 <input type="hidden" class="currentTab" name="currentTab" value="" />
 <input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
 <input type="hidden" name="formSubmit" value="true"  />
 <input type="hidden" name="f[type]" value="<?php echo getParam('type');?>"  />
<input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"/>
    <!-- Nav tabs -->
<ul class="nav form-edit-tab nav-tabs" role="tablist">
<?php 
  echo '<li role="presentation" class="active"><a href="#tab-general" role="tab" data-toggle="tab">'.getTextTranslate(68).'</a></li>';
  if(Yii::$app->user->can([ROOT_USER])){
  	echo '<li role="presentation" class=""><a href="#tab-prices" role="tab" data-toggle="tab">Bảng giá</a></li>';
  	echo '<li role="presentation" class=""><a href="#tab-warehouse" role="tab" data-toggle="tab">Kho hàng</a></li>';
  }
		
  echo '<li role="presentation" class=""><a href="#tab-detail" role="tab" data-toggle="tab">'.getTextTranslate(69).'</a></li>
  <li role="presentation" class=""><a href="#tab-images" role="tab" data-toggle="tab">'.getTextTranslate(70).'</a></li>
  <li role="presentation" class=""><a href="#tab-option" role="tab" data-toggle="tab">'.getTextTranslate(72).'</a></li>
	<li role="presentation" class=""><a href="#tab-filters" role="tab" data-toggle="tab">'.getTextTranslate(179,ADMIN_LANG).'</a></li>
  <li role="presentation" class="hide"><a href="#tab-promotion" role="tab" data-toggle="tab">'.getTextTranslate(219).'</a></li>
  <li role="presentation" class=""><a href="#tab-seo" role="tab" data-toggle="tab">'.getTextTranslate(71).'</a></li>';
  echo '<li role="presentation" class=""><a href="#tab-file-attach" role="tab" data-toggle="tab">'.getTextTranslate(127,ADMIN_LANG).'</a></li>
 		<li role="presentation" class=""><a href="#tab-help" role="tab" data-toggle="tab">'.getTextTranslate(73).'</a></li>';
  if($this->getAction() == 'edit'){
  	echo '<li><a rel="link_target" target="_blank" href="'.$this->createUrl(DS.$v['url']).'" >'.getTextTranslate(74,ADMIN_LANG).'</a></li>';
  }
?>
  

</ul>

<!-- Tab panes -->
<div class="tab-content edit-form-content-tab">
  <div role="tabpanel" class="tab-panel active" id="tab-general">

        <div class="p-content">
        <div class="row">
         <div class="col-sm-12">
<?php 
$setting = isset(Yii::$site['settings'][getParam('type')]) ? Yii::$site['settings'][getParam('type')]: [];
$auto_code =  isset($v['auto_code']) ? $v['auto_code'] : (!isset($v['code']) && isset($setting['code']['auto_code']) ? $setting['code']['auto_code'] : '');
 
echo Ad_edit_show_text_field($v,[
		'field'=>'code',
		'label'=>'Mã sản phẩm',
		'class'=>$auto_code == 'on' ? "" : 'required' ,
		'crkey'=>getParam('type'),
		'group'=>true,
		'disabled'=>$auto_code,
		'group_attrs'=>[
				'label'=>'Sinh mã tự động',
				'name'=>'biz[auto_code]',
				'checked'=>$auto_code,
				'disabled'=>$auto_code
]
]);
echo Ad_edit_show_text_field($v,[
		'field'=>'title',
		'label'=>getTextTranslate(54,ADMIN_LANG),
		'class'=>'required'
]);
echo Ad_edit_show_text_field($v,[
		'field'=>'short_title',
		//'field_name'=>'f[short_title]',
		'label'=>'Tiêu đề ngắn',
		'class'=>'',
		'crkey'=>getParam('type')
]);
echo Ad_edit_show_text_field($v,[
		'field'=>'description',
		'field_name'=>'biz[description]',
		'label'=>'Thông số đặc tả',
		'class'=>'',
		'crkey'=>getParam('type')
]);
 

echo Ad_edit_show_text_field($v,[
		'field'=>'barcode',
		'label'=>'Mã vạch',
		'class'=>'',
		'crkey'=>getParam('type')
]);
 
echo Ad_edit_show_select_field($v,[
		//'field'=>'title',
		'label'=>getTextTranslate(65,ADMIN_LANG),
		'class'=>'required select2',
		'field_name'=>'category_id[]',
		'multiple'=>true,
		'data'=>$all_menu,
		'data-disabled'=>[],
		'data-selected'=>$categorys,
		'option-value-field'=>'id',
		'option-title-field'=>'title',
]);
echo Ad_edit_show_text_field($v,[
		'field'=>'price1',
		'label'=>getTextTranslate(80,ADMIN_LANG),
		'class'=>'number-format',
		'crkey'=>getParam('type')
]);
echo Ad_edit_show_text_field($v,[
		'field'=>'price2',
		'label'=>getTextTranslate(29,ADMIN_LANG),
		'class'=>'number-format bold red',
		'crkey'=>getParam('type')
		
]);
echo Ad_edit_show_text_field($v,[
		'field'=>'position',
		'label'=>getTextTranslate(56,ADMIN_LANG),
		'class'=>'number-format',
		'placeholder'=>'Thứ tự xắp xếp',
		'default_value'=>999
]);

echo Ad_edit_show_check_field([
		'field'=>'is_active',
		'value'=>isset($v['is_active']) ? $v['is_active'] : 1,
		'type'=>'time',
		'label'=>getTextTranslate(57,ADMIN_LANG),
		'active_from_date'=>isset($v['active_from_date']) ? $v['active_from_date'] : '',
		'active_to_date'=>isset($v['active_to_date']) ? $v['active_to_date'] : '',
]);
?>
          
 
 
         
 

         
        

         </div>
          
        </div>
    </div>
  </div>
<?php include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'../../_forms/_forms/_tab_detail.php');?>
<?php include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'../../_forms/_forms/_tab_filters.php');?>
<?php include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'../../_forms/_forms/_tab_seo.php');?>
<?php include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'../../_forms/_forms/_tab_files_attach.php');?>
<?php include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'../../_forms/_forms/_tab_help.php');?>
<?php //include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'_forms/_tab_promotion.php');?>
<?php 
if(Yii::$app->user->can([ROOT_USER])){
	include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'_forms/_tab_prices.php');
	include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'_forms/_tab_warehouse.php');
}
?>
<?php include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'../../_forms/_forms/_tab_images.php');?>
<?php include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'../../_forms/_forms/_tab_options.php');?>
</div>


</form>
</div>



</div>