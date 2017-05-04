<?php 
// echo '<input type="hidden" class="auto_play_script_function" data-target=".ajax-load-time-detail-guides" data-action="loadTourProgramGuides" data-id="'.(isset($v['id']) ? $v['id'] : 0).'" value="loadTourProgramDetail(this);"/>';
?>
<div class="ajax-load-time-detail-guides"></div>
<?php 
echo '<input type="hidden" class="auto_play_script_function ajax-auto-load-time-detail" data-target=".ajax-load-time-detail-transport" data-action="loadTourProgramDistances" data-id="'.(isset($v['id']) ? $v['id'] : 0).'" value="loadTourProgramDetail(this);"/>';
?>
<div class="ajax-load-time-detail-transport"></div>
