<div class="table-responsive ">
<?php
 

?>
  <table class="table table-bordered vtop table-hover">
  <?php
  echo afShowThread(array(
  		array(
  				'name'=>'Tên',
  				'class'=>'center',
  		),
  		[
  				'name'=>'Tạo bởi',
  				'class'=>'center'
  		],
    array(
        'name'=>'Domain',
        'class'=>'center',
    ),
    array(
        'name'=>'Email admin',
        'class'=>'center',
    ),
  		array(
  				'name'=>'Danh mục',
  				'class'=>'center',
  		),
  		array(
  				'name'=>'Giao diện',
  				'class'=>'center',
  		),
  		array(
  				'name'=>'Ngày hết hạn',
  				'class'=>'center',
  		),
  		array(
  				'name'=>'Suspended',
  				'class'=>'center',
  		),

  ));
  ?>

    <tbody>
<?php
$role = [
		'type'=>'single',
		'table'=>$model->tableName(),
		'controller'=>Yii::$app->controller->id,
			
		 
];
if(!empty($l['listItem'])){ $i=0;
foreach($l['listItem'] as $k=>$v){	
  	$role['id']=$v['id'];
	$role['action'] = 'Ad_quick_change_item';
    $count_down = countDownDayExpired($v['to_date']);
    echo '<tr class="tr_item_'.$v['id'].'">
        <td class="center">
        <input type="checkbox" class="checked_item" name="check_item[]" value="'.$v['id'].'" />
        </td>
        <td class="center">'.(++$i).'</td>
        <td class=""><a '.(in_array($v['reseller_level'], [1,2]) ? 'title="Đại lý cấp '.$v['reseller_level'].'"' : '').' class="'.(in_array($v['reseller_level'], [1,2]) ? 'bold red' : '').'" href="'.get_link_edit($v['id']).'">'.$v['code'].'</a></td>
		<td class=""><a href="'.get_link_edit($v['id']).'">'.$v['creatorName'].'</a></td> 
        <td class=""><a class="bold underline" href="'.get_domain_with_scheme($v['domain']).'" target="_blank">'.$v['domain'].'</a>';
        $packs = $model->getPackDomains($v['id']);
        if(!empty($packs)){
        	foreach ($packs as $p){
        		echo '<p class="pdl15 f11p"><a href="'.get_domain_with_scheme($p['domain']).'" target="_blank">+&nbsp;'.$p['domain'].'</a></p>';
        	}
        }
        echo '</td>
        <td class=""><a>'.$v['email'].'</a></td> 
		<td class=""><a>'.$v['categoryName'].'</a></td> 
		<td class=""><a>'.$v['tempName'].'</a></td>
        <td class="center">'.(check_date_string($v['to_date']) ? '<p data-action="renew_shop_time_life" data-id="'.$v['id'].'" data-title="Gia hạn dịch vụ" onclick="open_ajax_modal(this);" class="bold btn '.($count_down > 30 ? ($count_down < 60 ? 'btn-warning' : 'btn-success') : 'btn-danger').' btn-success center">'.date('d/m/Y', strtotime($v['to_date'])).' - Còn '.$count_down.' ngày</p>' : '<p class="center disabled btn btn-default line-through">Chưa xác định</p>').'</td>
        <td class="center">'.(\common\models\Suspended::checkSuspended($v['id']) ? '
        		<p data-toggle="tooltip" title="Click để thay đổi trạng thái." data-confirm-action="unSuspendUser" data-type_id="1" data-action="open-confirm-dialog" data-confirm-text="Bạn có chắc chắn khôi phục dịch vụ ?" data-class="modal-sm" data-id="'.$v['id'].'" data-title="Khôi phục dịch vụ" onclick="open_ajax_modal(this);" class="bold btn btn-danger center">Bật</p>' : 
        		'<p data-toggle="tooltip" title="Click để thay đổi trạng thái." data-confirm-action="addSuspendUser" data-type_id="1" data-action="open-confirm-dialog" data-confirm-text="Bạn có chắc chắn tạm dừng dịch vụ ?" data-class="modal-sm" data-id="'.$v['id'].'" data-title="Tạm dừng dịch vụ" onclick="open_ajax_modal(this);" class="bold btn btn-default center">Tắt</p>').'</td>';        
		$role['action'] = 'Ad_quick_delete_item';
        echo '<td class="center">'.afShowListButton($v['id'],
        [
	        'btn'=>[
	        	'del'=>['attr'=>$role]
	        ]
        ]).'</td> 
        </tr>';

  }
}
?>
    </tbody>
  </table>
</div>
<?php
echo afShowPagination(array(
    'p'=>$l['p'],
    'total_records'=>$l['total_records'],
    'total_pages'=>$l['total_pages'],
    'limit'=>$l['limit'],
	'btn'=>['del'=>['attr'=>$role]]
));
?>
