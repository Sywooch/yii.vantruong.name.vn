<div class="col-sm-12 bang-thong-tin-chung" style=""><div class="row">
<div class="" style="margin-top: 10px">
<p class="upper bold grid-sui-pheader aleft ">Vận chuyển</p>
 
<table class="table table-bordered table-striped table-sm vmiddle"> 
 
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

<tr class="col-middle">
 <th class="bold center">Nhà xe</th>
 <th class="bold center" colspan="2">Loại xe</th>
 <th class="bold center">Số lượng</th>
 <th colspan="5" > 
 <p class="bold center">Chặng di chuyển</p>
</th>
<th class="bold center">Km</th>
<th class="bold center">Đơn giá (<?php echo Yii::$app->zii->showCurrency(isset($v['currency']) ? $v['currency'] : 1);?>)</th>
<th class="bold center">Thành tiền </th> 
 
</tr>
</thead> <tbody class="ajax-load-distance-detail" data-count="0">  </tbody> </table> 
<input data-guest="<?php echo isset($v['guest']) ? $v['guest'] : 0;?>" data-nationality="<?php echo isset($v['nationality']) ? $v['nationality'] : 0;?>" data-day="<?php echo isset($v['day']) ? (max($v['day'],$v['night'])) : 0;?>" type="hidden" class="auto_play_script_function ajax-auto-load-distance-detail" data-target=".ajax-load-distance-detail" data-action="loadTourProgramDistances" data-id="<?php echo isset($v['id']) ? $v['id'] : 0?>" value="loadTourProgramDetail(this);"/>   
</div></div></div>  