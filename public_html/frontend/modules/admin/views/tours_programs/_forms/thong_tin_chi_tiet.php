<?php 
$prices = Yii::$app->zii->getServiceDetailPrices([
		'item_id'=>$v['id'],
		'day'=>0,
		'time'=>0,
		'service_id'=>68,
		'type_id'=>4,
		'nationality'=>$v['nationality'],
		'total_pax'=>$v['guest'],
		'from_date'=>$v['from_date']
]);
 

$prices = \app\modules\admin\models\Suppliers::getSeasons([
		'date'=>'10/03/2017',
		'supplier_id'=>5,
		'time_id'=>1
		
]
		);

view($prices);
/*foreach ((new \yii\db\Query)->from('seasons')->all() as $k1=>$v1){
	view($v1['to_date']);
	Yii::$app->db->createCommand()->update('seasons',['to_date'=>date("Y-m-d 23:59:59:59",strtotime($v1['to_date']))],[
			'id'=>$v1['id']
	])->execute();
}
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
 <th colspan="4" class="center col-ws-4">Tên dịch vụ</th>
 <th colspan="2" class="center col-ws-2">Loại dịch vụ</th>
 <th colspan="1" class="center col-ws-1">ĐVT</th>
 <th colspan="1" class="center col-ws-1">Số lượng</th>
 <th colspan="1" class="center col-ws-1">Đơn giá</th>
 <th colspan="1" class="center col-ws-1">Thành tiền (VND)</th> 
 
 </tr>
</thead> <tbody class="ajax-load-time-detail" data-count="0">  </tbody> </table> 
<input data-day="<?php echo isset($v['day']) ? (max($v['day'],$v['night'])) : 0;?>" type="hidden" class="auto_play_script_function ajax-auto-load-time-detail" data-target=".ajax-load-time-detail" data-action="loadTourProgramDetail" data-id="<?php echo isset($v['id']) ? $v['id'] : 0?>" value="loadTourProgramDetail(this);"/>   
</div></div></div>  