<?php 
 
?>
<div class="col-sm-12 bang-thong-tin-chung" style=""><div class="row">
<?php 
if(isset($v['id'])){
	echo '<div class="alert alert-warning f12e" role="alert" style="margin-top: 10px">
<label>Ngày khởi tạo: <span class="underline green">'.date("d/m/Y H:i:s",strtotime($v['time'])).'</span></label>
			&nbsp;&nbsp;&nbsp;-|-&nbsp;&nbsp;&nbsp;
<label>Lần chỉnh sửa gần nhất: <span class="underline red">'.date("d/m/Y H:i:s",strtotime($v['last_modify'])).'</span></label>
		<input type="hidden" name="f[last_modify]" value="'.date("Y-m-d H:i:s").'"/>
</div>';
}
?>


<div class="" style="margin-top: 10px">
<p class="upper bold grid-sui-pheader aleft ">Thông tin chung</p>
 
<table class="table table-bordered table-sm vmiddle table-striped"> 
<?php $ctype = isset($v['ctype']) ? $v['ctype'] : 1;?>
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
<thead> </thead> <tbody class=""> 
 <tr class="col-middle">
 <td >Loại tour</td>
 <td colspan="3">
 <label class="radio_ctype bold"><input class="radio_quotation_type" onchange="changeTourType(this);" type="radio" name="f[ctype]" value="1" <?php echo $ctype == 1 ? 'checked' : '';?>/> Inbound</label>
 <label class="radio_ctype bold"><input class="radio_quotation_type" onchange="changeTourType(this);" type="radio" name="f[ctype]" value="2" <?php echo $ctype == 2 ? 'checked' : '';?>/> Outbound</label>
 <label class="radio_ctype bold"><input class="radio_quotation_type" onchange="changeTourType(this);" type="radio" name="f[ctype]" value="3" <?php echo $ctype == 3 ? 'checked' : '';?>/> Nội địa</label>
 </td>
 <td class="center">SL khách</td>
 <td colspan="7">
           
          <div class="col-sm-12"><div class="form-group" style="margin-bottom: 0"> 
           <div class="col-sm-3"><div class="input-group input-sm row" style="margin-right: 0">
          <?php 
           
          $_so_luong_khach = isset($v['guest1']) ? $v['guest1'] : 1;
          ?>
          <span class="input-group-addon input-sm ">Người lớn</span>  <input id="input-tour-sokhach-nl" onblur="countTotalGuest(this);" type="text" data-num="true" name="f[guest1]" class="form-control input-sm required  numberFormat bold red" id="inputPositionx1" placeholder="Người lớn" value="<?php echo $_so_luong_khach;?>" />
          
          </div>
          
          </div>
           <div class="col-sm-3"><div class="input-group row input-sm" style="margin-right: 0">
          <?php 
           
          $_so_luong_khach = isset($v['guest2']) ? $v['guest2'] : 0;
          ?>
          <span class="input-group-addon input-sm">Trẻ em 50%</span>  <input id="input-tour-sokhach-te" onblur="countTotalGuest(this);" type="text" data-num="true" name="f[guest2]" class="form-control input-sm numberFormat bold red" id="inputPositionx2" placeholder="Trẻ em 50%" value="<?php echo $_so_luong_khach;?>" />
          
          </div>
          
          </div>
           <div class="col-sm-3"><div class="input-group row input-sm" style="margin-right: 0">
          <?php 
           
          $_so_luong_khach = isset($v['guest3']) ? $v['guest3'] : 0;
          ?>
          <span class="input-group-addon input-sm">Trẻ em MP</span>  <input id="input-tour-sokhach-tefree" type="text" data-num="true" name="f[guest3]" class="form-control numberFormat input-sm bold red input-sm" id="inputPositionx3" placeholder="Trẻ em MP" value="<?php echo $_so_luong_khach;?>" />
          
          </div>
          
          </div>
          
          
          <div class="col-sm-3"><div class="input-group row input-sm" title="Tổng số khách tính phí">
          <?php 
           
          $_so_luong_khach = isset($v['guest']) ? $v['guest'] : 1;
          ?>
          <span class="input-group-addon input-sm">Tổng</span>  <input readonly="readonly" id="input-tour-sokhach" onblur="changeTotalGuest(this);" type="text" data-num="true" name="f[guest]" class="form-control required  numberFormat bold input-sm red" id="inputPositionx" placeholder="Số khách" value="<?php echo $_so_luong_khach;?>" />
          
          </div>
          
          </div></div> 
          
          
           
        </div></td>
 </tr>
  
  <tr class="col-middle">
 <td >Thời gian</td>
 <td colspan="2" >
 <div class="form-group" style="margin-bottom: 0">
           
          <div class="col-sm-12"><div class="row">
           <div class="col-sm-6"><div class="input-group ">
          <?php 
           
          $night = isset($v['day']) ? $v['day'] : 0;
          ?>
          <input id="input-day-amount" onblur="changeNightPreview(this);" type="text" data-num="true" name="f[day]" class="form-control input-sm required center numberFormat bold red"  placeholder="Số ngày" value="<?php echo $night;?>" data-old="<?php echo $night;?>" /><span class="input-group-addon input-sm">Ngày</span>  
          
          </div>
          
          </div>
        <div class="col-sm-6"><div class="input-group ">
          <?php 
           
          $night = isset($v['night']) ? $v['night'] : 0;
          ?>
          <input id="input-night-amount" onblur="changeNightPreview(this);" type="text" data-num="true" name="f[night]" class="form-control input-sm center numberFormat bold red"  placeholder="Số đêm" value="<?php echo $night;?>"  data-old="<?php echo $night;?>"/><span class="input-group-addon input-sm">đêm</span>  
          
          </div>
          
          </div>
            
          </div></div>
          
          
           
        </div>
 </td>
  <td class="center" ></td>
 <td colspan="1" class="center"> Quốc tịch
 <input id="inputTourID" type="hidden"  value="<?php echo isset($v['id']) ? $v['id'] : 0;?>"/>
 <input type="hidden" id="inputStarttime" onchange="genTourCode(this);changeEatPreview(this);changeHotelPreview(this);changeNightPreview(this);" name="f[from_date]" class="datepickeronly form-control" value="<?php echo isset($v['from_date']) ? date("d/m/Y",strtotime($v['from_date'])) : '';?>">
            </td>
      
<td colspan="7" > <div class="col-sm-12">
<div class="form-group" style="margin-bottom: 0"><div class="group-sm borderc1">
		      <select id="inputNationality" onchange="genTourCode(this);" name="f[nationality]" data-type="0" data-num="true" data-select="select2" class="select2 form-control" role="load_costs" data-placeholder="Chọn điểm đón khách" style="width: 100%">
		      <?php 
		       
		     $locals = load_model('local');
		     $l = $locals->getAll(['level'=>1]); 
			if(!empty($l)){
		     	foreach ($l as $k1=>$v1){
		     		echo '<option value="'.$v1['id'].'" '.(isset($v['nationality']) && $v['nationality'] == $v1['id'] ? 'selected="selected"' : '').'>'.spc($v1['level']).uh($v1['name']).'</option>';
		     	}
		     }
		     ?>
		      </select>
		      
		    </div></div></div>
  
             </td>
 
   
    
 
 
 </tr>
 
 
 <tr class="col-middle">
 <td >Tiền tệ</td>
 
 <td colspan="1" class="center">
 <?php 
 $currency = isset($v['currency']) ? $v['currency'] : 1;
 echo Yii::$app->zii->showCurrency($currency);?> 
  
 </td> 
 <td class="center">Tỷ giá</td>
 
 <td colspan="1" class="center"><?php 
 if(isset($v['id'])){
 	echo '<a data-time="'.$v['time'].'" data-action="change-exchange-rate" data-item_id="'.$v['id'].'" data-currency="'. $currency.'" data-title="Tỷ giá tiền tệ tính theo <b class=red>'.(Yii::$app->zii->showCurrency($currency)).'</b>" onclick="open_ajax_modal(this);return false;" href="#">Click để xem</a>';
 }
 ?>
 
 </td> 
  <td class="center" >Đón</td>
  
<td colspan="3" > 
<div class="form-group" style="margin-bottom: 0"><div class="col-sm-12"><div class="group-sm borderc1">
		      <select id="inputDateIN" onchange="genTourCode(this);" name="biz[in]" data-type="0" data-num="true" data-select="select2" class="select2 form-control" role="load_costs" data-placeholder="Chọn điểm đón khách" style="width: 100%">
		      <?php 
		      
		     $l = $locals->getAll(['level'=>2]); 
			if(!empty($l)){
		     	foreach ($l as $k1=>$v1){
		     		echo '<option value="'.$v1['id'].'" '.(isset($v['in']) && $v['in'] == $v1['id'] ? 'selected="selected"' : '').'>'.spc($v1['level']).uh($v1['name']).'</option>';
		     	}
		     }
		     ?>
		      </select>
		      
		    </div></div></div>
 <input type="hidden" id="inputEndtime" onchange="genTourCode(this);" readonly="readonly" name="f[to_date]" class="form-control" value="<?php echo isset($v['to_date']) ? date("d/m/Y",strtotime($v['to_date'])) : '';?>">
             </td>
 
  <td colspan="1" class="center">Tiễn</td>
  <td colspan="3">
  <div class="col-sm-12"><div class="form-group" style="margin-bottom: 0"><div class="group-sm borderc1">
<select id="inputDateOUT" onchange="genTourCode(this);" name="biz[out]" class="select2 form-control" data-placeholder="Chọn điểm tiễn khách" style="width: 100%">
		      
		     <?php 
		      
		     //$l = $locals->getAll(2,true); 
		     
		     if(!empty($l)){
		     	foreach ($l as $k1=>$v1){
		     		echo '<option value="'.$v1['id'].'" '.(isset($v['out']) && $v['out'] == $v1['id'] ? 'selected="selected"' : '').'>'.spc($v1['level']).uh($v1['name']).'</option>';
		     	}
		     }
		     ?>
		      </select></div></div></div>
   </td>  
 
 
 </tr>
  
 <tr class="col-middle">
 <td >Mã tour</td>
 <td colspan="3">
 <?php echo '<input data-action="checkExistedTourProgramCode" type="text" onkeyup="checkExistedTourProgramCode(this);" onblur="checkExistedTourProgramCode(this);" data-id="'.(isset($v['id']) ? $v['id'] : 0).'" name="f[code]" class="form-control required" placeholder="Mã tour" value="'.(isset($v['code']) ? $v['code']  : '').'" />';?>
  
 </td>
 <td class="center">Tiêu đề</td>
 <td colspan="7">
 <?php echo '<input type="text" name="f[title]" class="form-control" placeholder="Tiêu đề " value="'.(isset($v['title']) ? $v['title']  : '').'" />';?>
  
 </td> 
 </tr>
  
   <tr class="col-middle">
 <td >Ngày bắt đầu</td>
 <td colspan="3">
 <?php echo '<input type="text" data-id="'.(isset($v['id']) ? $v['id'] : 0).'" name="f[from_date]" class="form-control datepicker" data-format="DD/MM/YYYY" placeholder="" value="'.(isset($v['from_date']) ? date("d/m/Y",strtotime($v['from_date']))  : '').'" />';?>
  
 </td>
 <td class="center">Kết thúc</td>
 <td colspan="7">
 <?php echo '<input type="text" name="f[to_date]" class="form-control datepicker" data-format="DD/MM/YYYY" placeholder=" " value="'.(isset($v['to_date']) ? date("d/m/Y",strtotime($v['to_date']))  : '').'" />';?>
  
 </td> 
 </tr>
  
   </tbody> </table>
</div></div></div>  