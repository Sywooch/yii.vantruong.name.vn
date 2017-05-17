

<?php 
echo '<input type="hidden" class="auto_play_script_function" data-target=".ajax-load-time-detail-transport" data-action="loadTourProgramDistances" data-item_id="'.(isset($v['id']) ? $v['id'] : 0).'" data-id="'.(isset($v['id']) ? $v['id'] : 0).'" value="loadTourProgramDetail(this);"/>';
echo '<input type="hidden" class="auto_play_script_function" data-target=".ajax-load-time-detail-guides" data-action="loadTourProgramGuides" data-item_id="'.(isset($v['id']) ? $v['id'] : 0).'" data-id="'.(isset($v['id']) ? $v['id'] : 0).'" value="loadTourProgramDetail(this);"/>'; 
?>
<div class="ajax-load-time-detail-transport"></div>
<div class="ajax-load-time-detail-guides"></div>