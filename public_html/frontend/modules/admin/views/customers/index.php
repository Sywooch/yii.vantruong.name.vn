<?php 
if(isset($l)){
?><div class="table-responsive ">
 
<table class="table table-bordered vmiddle table-hover">
  <?php
  echo afShowThread(array(
  		array(
  				'name'=>'Mã số',
  				'class'=>'center cactive',
  		),
  		array(
  				'name'=>'Tên viết tắt',
  				'class'=>'center',
  		),
	    array(
	        'name'=>'Tên đầy đủ',
	        'class'=>'',
	    ),
  		array(
  				'name'=>'Tỉnh thành',
  				'class'=>'center',
  		),
  		array(
  				'name'=>'Địa chỉ',
  				'class'=>'',
  		),
  		array(
  				'name'=>'Điện thoại',
  				'class'=>'center',
  		),
  		array(
  				'name'=>'Email',
  				'class'=>'center',
  		),
  		[
  			'name'=>getTextTranslate(57,ADMIN_LANG),
  			'class'=>'center cactive','order'=>true,'field'=>'a.is_active'
  		]
  	 

  ));
  ?>

    <tbody>
<?php
$role = [
		'type'=>'single',
		'table'=>$model->tableName(),
		'controller'=>Yii::$app->controller->id,
			
];
if(!empty($l['listItem'])){
	foreach($l['listItem'] as $k=>$v){
		$role['id']=$v['id'];
		$role['action'] = 'Ad_quick_change_item';
echo '<tr class="tr_item_'.$v['id'].'">
        <td class="center">
        <input type="checkbox" class="checked_item" name="check_item[]" value="'.$v['id'].'" />
        </td>
        <td class="center">'.($k+1).'</td>
		<td class="center"><a href="'.get_link_edit($v['id']).'">'.($v['code']).'</a></td>
  		<td class="center nowrap"><a href="'.get_link_edit($v['id']).'">'.($v['short_name']).'</a></td> 
        <td class="nowrap"><a href="'.get_link_edit($v['id']).'">'.uh($v['name']).'</a></td>
  		<td class="center nowrap">'.showLocalName($v['localName'],$v['localType']).'</td>
  		<td class="">'.$v['address'].'</td> 
        <td class="nowrap center">'.$v['phone'].'</td>
		<td class="nowrap center">'.$v['email'].'</td>';
echo Ad_list_show_checkbox_field($v,[
		'field'=>'is_active',
		//'class'=>'number-format ',
		//'decimal'=>0,
		'role'=>$role
]);
echo Ad_list_show_option_field($v,[
		'role'=>$role,
		'action'=>'Ad_quick_delete_item',
		'btn'=>['']
]);
echo '</tr>';

  }
}


?>
    </tbody>
  </table>
</div>
<?php
echo afShowPagination([
		'p'=>$l['p'],
		'total_records'=>$l['total_records'],
		'total_pages'=>$l['total_pages'],
		'limit'=>$l['limit'],
		'btn'=>['del'=>['attr'=>$role]]
]);
}