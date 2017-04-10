<div role="tabpanel" class="tab-panel " id="tab-tickets"><div class="col-sm-12">
<?php 
if(!empty($v)){
$ticketModel = load_model('tickets');
$lx = $ticketModel->getList(['supplier_id'=>isset($v['id']) ? $v['id'] : 0]);
if(!empty($lx['listItem'])){
	foreach ($lx['listItem'] as $k1=>$v1){
		echo '<div class="mgt15 pr tr_item_'.$v1['id'].'" style="border:1px solid #ddd; padding:15px">
		<div><i data-toggle="confirmation-popout" data-type="single" data-table="{{%tickets}}" data-controller="tickets" data-id="'.$v1['id'].'" data-action="Ad_quick_delete_item" data-title="Xóa bản ghi này ?" data-placement="left" data-btnokclass="btn-primary" data-original-title="" class="btn-remove glyphicon glyphicon-trash pointer"></i></div>
		<a '.showJqueryAttr([
        						'title'=>'Cập nhật vé tham quan',
        						'action'=>'ajax-form-add-new',
        						'class'=>'w90',
        						//'controller-id'=>CONTROLLER_ID,
        						'controller'=>'tickets',
        						//'controller-text'=>CONTROLLER_TEXT,
        						'id'=>$v1['id']]).' onclick="open_ajax_modal(this);return false;" class="bold pointer">'.$v1['title'].'</a><table class="table vmiddle table-hover table-bordered">';
		echo '<thead><tr><th>Nhóm khách</th>';
			
		$m = load_model('guest_groups');
		$guests = $m->getAll();
		if(!empty($guests)){
			foreach ($guests as $g){
				echo '<th class="center">'.$g['title'].'</th>';
			}
		}
			
		echo '<th class="center">Tiền tệ</th>';
		echo '</tr></thead>';
		
		echo '<tbody class="ajax-load-group-nationality">';
		
		$existed = [];
		$l = $ticketModel->getNationalityGroup($v1['id']);
		if(!empty($l)){
			foreach ($l as $k2=>$v3){
				$p = $ticketModel->get_prices($v1['id'],$v3['id']);
				//view($p);
				$existed[] = $v3['id'];
				echo '<tr><td>'.$v3['title'].'</td>';
				if(!empty($guests)){
					foreach ($guests as $g){
						echo '<td><p class="aright bold">'.number_format(isset($p[$g['id']]['price1']) ? $p[$g['id']]['price1'] : 0).'</p></td>';
					}
				}
				echo '<td class="group-sm30 center">';
				echo Yii::$app->zii->showCurrency($p[$g['id']]['currency']);
				echo '</td></tr>';
			}
		}
		echo '</tbody></table>';
		
		
			
		echo '</div>';
	}
}
?>
<div style="margin-top: 15px;" class="aright">
<a data-title="Thêm mới vé tham quan" data-place="<?php echo $place_id;?>" data-supplier="<?php echo $v['id']?>" data-action="ajax-form-add-new" data-class="w90" data-controller-id="454" data-controller-text="ve-tham-quan" data-controller="tickets" onclick="open_ajax_modal(this);return false;" data-href="/ve-tham-quan/add" class="btn btn-add btn-sm btn-warning"><i class="glyphicon glyphicon-plus"></i> Thêm mới vé</a>
</div>
<?php }?>
</div>        </div>