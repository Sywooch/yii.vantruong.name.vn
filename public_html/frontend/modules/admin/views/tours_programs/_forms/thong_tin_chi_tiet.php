<?php 
$a = [
		'service_id',
		'id',
		'type_id',
		'package_id',
		'day_id',
		'item_id',
		'supplier_id','service_id'

];
foreach ($a as $b){
	$$b = post($b,12); 
}
$time_id = post('time_id',-1);

	

	
	
$prices = Yii::$app->zii->getServiceDetailDayPrices([
		'supplier_id'=>$supplier_id,
		'package_id'=>$package_id,
		'type_id'=>4,
		'item_id'=>$id,
		'sub_item_id'=>$item_id,
		'day_id'=>$day_id,
		'time_id'=>$time_id,'service_id'=>$service_id
]);
?>
<div class="col-sm-12 bang-thong-tin-chung" style=""><div class="row">
<div class="" style="margin-top: 10px">
<p class="upper bold grid-sui-pheader aleft ">Thông tin chi tiết</p>
 
<table class="table table-bordered table-sm vmiddle table-striped"> 
 
<colgroup>
<col style="width:8.333333%">
<col style="width:8.333333%">
<col style="width:8.333333%">
<col style="width:8.333333%">
<col style="width:8.333333%">
<col style="width:8.333333%">
<col style="width:8.333333%">
<col style="width:8.333333%">
<col style="width:8.333333%">
<col style="width:8.333333%">
<col style="width:8.333333%">
<col style="width:8.333333%">
</colgroup>
<thead>

<tr class="col-middle bold">
 <th class="bold center">Ngày</th>
 <th colspan="1" class="center col-ws-1">Thời gian</th>
 <th colspan="4" class="center col-ws-4">Tên dịch vụ</th>
 <th colspan="2" class="center col-ws-2">Loại dịch vụ</th>
 <th colspan="1" class="center col-ws-1">ĐVT</th>
 <th colspan="1" class="center col-ws-1">Số lượng</th>
 <th colspan="1" class="center col-ws-1">Đơn giá</th>
 <th colspan="1" class="center col-ws-1">Thành tiền (VND)</th> 
 
 </tr>
</thead> <tbody class="ajax-load-time-detail" data-count="0">  
<?php 
echo loadTourProgramDetail([
				'day'=>isset($v['day']) ? (max($v['day'],$v['night'])) : 0, 'id'=>$v['id'],
		'loadDefault'=>true
		]);
?>
</tbody> </table> 
<?php 
//echo '<input data-day="'.(isset($v['day']) ? (max($v['day'],$v['night'])) : 0).'" type="hidden" class="auto_play_script_function ajax-auto-load-time-detail" data-target=".ajax-load-time-detail" data-action="loadTourProgramDetail" data-id="'.(isset($v['id']) ? $v['id'] : 0).'" value="loadTourProgramDetail(this);"/>';
?>
</div></div></div>  