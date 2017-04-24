 
<div class="table-responsive ">
 
  <table class="table table-bordered  table-hover">
  <?php
  echo afShowThread(array(
     
    array(
        'name'=>'Tiêu đề',
        'class'=>'',
    ),
  		array(
  				'name'=>'Tiêu chuẩn',
  				'class'=>'center cactive',
  		),
  		array(
  				'name'=>'Địa danh',
  				'class'=>'center',
  		),
  		array(
  				'name'=>'Điện thoại',
  				'class'=>'center ',
  		),
  	array(
        'name'=>'Email',
        'class'=>'center ',
    ),
  		 
    array(
        'name'=>'Kích hoạt',
        'class'=>'center cactive',
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
if(!empty($l['listItem'])){
	foreach($l['listItem'] as $k=>$v){
		$pl = $model->get_existed_place($v['id']);
		 
		$places = array();
		if(!empty($pl)){
			foreach ($pl as $place){
				$places[] = $place['name'];
			}
		}
		$role['id']=$v['id'];
		$role['action'] = 'Ad_quick_change_item';
		$link = get_link_edit($v['id']);
		echo '<tr class="tr_item_'.$v['id'].'">
        <td class="center">
        <input type="checkbox" class="checked_item" name="check_item[]" value="'.$v['id'].'" />
        </td>
        <td class="center">'.($k+1).'</td>
     
        <td class="nowrap"><a href="'.$link.'">'.uh($v['name']).'</a></td>
  		<td class="center">'.($v['rating'] > 0 ? $v['rating'] .' sao': 'Khác').' </td>
		<td class="center nowrap">'.(!empty($places) ? implode(' | ', $places) : '').($v['localType'] > 0 ? '<p>['.showLocalName($v['localName'],$v['localType']).']</p>' : '').'</td>
  		 
  		<td class="center"><p class=" ">'.$v['phone'].'</p></td> 
        <td class="center"><p class=" stime">'.$v['email'].'</p></td> '.
 
        Ad_list_show_checkbox_field($v,[
        		'field'=>'is_active',
        		//'class'=>'number-format ',
        		//'decimal'=>0,
        		'role'=>$role
		]);
        echo Ad_list_show_option_field($v,[
        		'role'=>$role,
        		'action'=>'Ad_quick_delete_item',
        		'btn'=>['']
        ]) .'
      </tr>';
 
  }
}
echo '</tbody></table></div>';
echo afShowPagination([
		'p'=>$l['p'],
		'total_records'=>$l['total_records'],
		'total_pages'=>$l['total_pages'],
		'limit'=>$l['limit'],
		'btn'=>['del'=>['attr'=>$role]]
]);  
