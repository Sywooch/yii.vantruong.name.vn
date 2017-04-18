<div class="col-sm-12 bang-thong-tin-chung" style=""><div class="row">
<div class="" style="margin-top: 10px">
<p class="upper bold grid-sui-pheader aleft ">Vận chuyển</p>
<?php 
if(Yii::$app->user->can(ROOT_USER)){
	echo loadTourProgramDistances($v['id']);
}
?> 
 
<?php /* /echo <input data-guest="<?php echo isset($v['guest']) ? $v['guest'] : 0;?>" data-nationality="<?php echo isset($v['nationality']) ? $v['nationality'] : 0;?>" data-day="<?php echo isset($v['day']) ? (max($v['day'],$v['night'])) : 0;?>" type="hidden" class="auto_play_script_function ajax-auto-load-distance-detail" data-target=".ajax-load-distance-detail" data-action="loadTourProgramDistances" data-id="<?php echo isset($v['id']) ? $v['id'] : 0?>" value="loadTourProgramDetail(this);"/> */?>   
</div></div></div>  