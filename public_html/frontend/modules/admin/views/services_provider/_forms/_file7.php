<div role="tabpanel" class="tab-panel " id="tab-panel-rooms"><div class="col-sm-12">
 <div class="ajax-result-load-rooms"></div>
<input type="hidden" class="auto_play_script_function ajax-load-rooms" data-target=".ajax-result-load-rooms" data-action="loadSupplierRooms" data-id="<?php echo isset($v['id']) ? $v['id'] : 0?>" value="loadHtmlData(this);"/>
</div></div> 


<div role="tabpanel" class="tab-panel " id="tab-panel-routes"><div class="col-sm-12">
<div class="ajax-result-load-routes"></div>
<input type="hidden" class="auto_play_script_function ajax-load-routes" data-target=".ajax-result-load-routes" data-action="loadSupplierRoutes" data-id="<?php echo isset($v['id']) ? $v['id'] : 0?>" value="loadHtmlData(this);"/>
</div></div>


<div role="tabpanel" class="tab-panel " id="tab-panel-prices"><div class="col-sm-12">
<?php 
//view($model->getTrainDistanceBySupplier(['parent_id'=> 50,'package_id'=>289]));
//view($model->getTrainPrice(1,108,50,289));
?>
<div class="ajax-result-load-prices"></div>
<input type="hidden" class="auto_play_script_function ajax-load-prices" data-target=".ajax-result-load-prices" data-action="loadSupplierPrices" data-id="<?php echo isset($v['id']) ? $v['id'] : 0?>" value="loadHtmlData(this);"/>
</div></div>