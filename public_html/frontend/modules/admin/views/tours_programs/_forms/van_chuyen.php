<?php 
if(Yii::$app->user->can(ROOT_USER)){
	echo getTourProgramSegments($v['id']);
}

?>
<div class="col-sm-12 bang-thong-tin-chung" style=""><div class="row">
<div class="" style="margin-top: 10px">
<p class="upper bold grid-sui-pheader aleft ">Vận chuyển</p>
<div class="ajax-load-time-detail-transport"></div>
<?php 
if(Yii::$app->user->can(ROOT_USER)){
	//echo loadTourProgramDistances($v['id']);
}
?> 
<?php 
if(!Yii::$app->user->can(ROOT_USER)){
echo '<input type="hidden" class="auto_play_script_function ajax-auto-load-time-detail" data-target=".ajax-load-time-detail-transport" data-action="loadTourProgramDistances" data-id="'.(isset($v['id']) ? $v['id'] : 0).'" value="loadTourProgramDetail(this);"/>';
}
?>
    
</div></div></div>  