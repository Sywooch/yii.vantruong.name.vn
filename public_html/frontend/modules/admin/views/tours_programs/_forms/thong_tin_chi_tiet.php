<div class="col-sm-12 bang-thong-tin-chung" style=""><div class="row">
<div class="" style="margin-top: 10px">
<p class="upper bold grid-sui-pheader aleft ">Chi tiết dịch vụ ngày</p>
 
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