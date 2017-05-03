<?php 
 
/*
$prices = Yii::$app->zii->getServiceDetailPrices([
			'supplier_id'=>126,
			'package_id'=>0,
			'type_id'=>4,
			'item_id'=>3,
			'sub_item_id'=>0,
			'day_id'=>4,
			'time_id'=>3,
			'season_time_id'=>3,	
			'service_id'=>126,
			//'from_date'=>'2017-12-20',
			'nationality_id'=>212,
			'total_pax'=>28,	
			'loadDefault'=>true	,
			'updateDatabase'=>false,	
		]);
view($prices);

$seasons = \app\modules\admin\models\Suppliers::getSeasons([
				'date'=>'2017-12-24',
				'time_id'=>3,
				'supplier_id'=>5
		]);
$r = Yii::$app->zii->getDefaultServicePrices([
		'from_date'=>'2017-12-24',
		//'quotation_id'=>$quotation['id'],
		//'nationality_id'=>$nationality_group['id'],
		'item_id'=>17,
		'time_id'=>3,
		'day_id'=>4,
		'service_id'=>5,'supplier_id'=>5,
		'total_pax'=>28,
		'weekend_id'=>isset($seasons['week_day_prices']['id']) ? $seasons['week_day_prices']['id'] : 0,
		'time_id'=>isset($seasons['time_day_prices']['id']) ? $seasons['time_day_prices']['id'] : -1,
		'package_id'=>0,
		//'season_time_id'=>$season_time_id,
		'seasons'=>\app\modules\admin\models\Suppliers::getSeasons([
				'date'=>'2017-12-24',
				'time_id'=>3,
				'supplier_id'=>5
		]),
		'loadDefault'=>true,
		'updateDatabase'=>false,
		'controller_code'=>TYPE_ID_REST,
		'quotation_id'=>2,'nationality_id'=>16
]);
 
 
 $a = loadTourProgramDetail([
		'id'=>$v['id'],
		'loadDefault'=>true,
		'updateDatabase'=>true,
]);

 
/*
view(Yii::$app->zii->getServiceDetailPrices([
			'supplier_id'=>60,
			'package_id'=>0,
			'type_id'=>4,
			'item_id'=>4,
			'sub_item_id'=>0,
			'day_id'=>0,
			'time_id'=>-1,
			'service_id'=>60,
			'from_date'=>$v['from_date'],
			'nationality_id'=>$v['nationality']	,
			'total_pax'=>$v['guest'],	
			'loadDefault'=>true	,
			'updateDatabase'=>false,	
		]));
	*/				
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
 <th colspan="4" class="center col-ws-3">Tên dịch vụ / Đơn vị cung cấp</th>
 <th colspan="2" class="center col-ws-2">Chi tiết dịch vụ</th>
 <th colspan="1" class="center col-ws-1">Loại dịch vụ</th>
 <th colspan="1" class="center col-ws-1">ĐVT</th>
 <th colspan="1" class="center col-ws-1">Số lượng</th>
 <th colspan="1" class="center col-ws-1">Đơn giá</th>
 <th colspan="1" class="center col-ws-1">Thành tiền (VND)</th> 
 
 </tr>
</thead> <tbody class="ajax-load-time-detail" data-count="0">  
<?php 
 
//view(loadTourProgramDetail(['id'=>$v['id']])); 
 
?>
</tbody> </table> 
<?php 
echo '<input data-day="'.(isset($v['day']) ? (max($v['day'],$v['night'])) : 0).'" type="hidden" class="auto_play_script_function ajax-auto-load-time-detail" data-target=".ajax-load-time-detail" data-action="loadTourProgramDetail" data-id="'.(isset($v['id']) ? $v['id'] : 0).'" value="loadTourProgramDetail(this);"/>';
?>
</div></div></div>  