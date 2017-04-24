<div role="tabpanel" class="tab-panel" id="tab-list-cars" >
   
<div class="fl100">
<div class="p-contentx f12e">
<div class="fl100 cxd_tabs_auto_height grid-small" style=""> 
  
<div class="col-sm-12 "><div class="row"> 
<div class="ajax-auto-load-list-detail"></div>
<?php

echo '<input data-controller="'.Yii::$app->controller->id.'" data-code="'.CONTROLLER_CODE.'" type="hidden" class="auto_play_script_function input-ajax-auto-load-list-detail" data-target=".ajax-auto-load-list-detail" data-action="loadSupplierListVehicle" data-id="'. (isset($v['id']) ? $v['id'] : 0).'" value="loadTourProgramDetail(this);"/>';
?>
  
</div></div>     
    
</div></div></div> 
 
     
  </div>