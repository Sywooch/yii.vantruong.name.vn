<div role="tabpanel" class="tab-panel" id="tab-panel-prices">

<?php
//getSupplierPricesList($v['id']);
echo '<input data-controller="'.Yii::$app->controller->id.'" data-code="'.CONTROLLER_CODE.'" type="hidden" class="auto_play_script_function input-ajax-auto-load-prices-detail" data-target=".ajax-auto-load-prices-detail1" data-action="loadSupplierHotelPrice1" data-supplier_id="'. (isset($v['id']) ? $v['id'] : 0).'" value="loadTourProgramDetail(this);"/>';
?><div class="ajax-auto-load-prices-detail1"></div>
</div> 
 