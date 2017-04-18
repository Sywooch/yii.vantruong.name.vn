<div role="tabpanel" class="tab-panel" id="tab-panel-costs-distance">

<?php
//echo getSupplierVehiclePrices2($v['id']);
//Yii::$app->zii->getTablePrice(6);
//view(getSupplierPricesList(17)); 
//view(\app\modules\admin\models\Customers::getSupplierQuotations(17,[
//			'order_by'=>['a.to_date'=>SORT_DESC,'a.title'=>SORT_ASC],
//			'is_active'=>1
//	]));


echo '<input data-controller="'.Yii::$app->controller->id.'" data-code="'.CONTROLLER_CODE.'" type="hidden" class="auto_play_script_function input-ajax-auto-load-prices-detail2" data-target=".ajax-auto-load-prices-detail2" data-action="loadSupplierVehiclePrices2" data-supplier_id="'. (isset($v['id']) ? $v['id'] : 0).'" value="loadTourProgramDetail(this);"/>';
?><div class="ajax-auto-load-prices-detail2"></div>
</div> 