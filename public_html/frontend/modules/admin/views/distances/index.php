<?php
echo '<div class="table-responsive ">';
 
echo '<table class="table table-bordered vmiddle table-hover">';
echo afShowThread(array(
    array(
        'name'=>'Icon',
        'class'=>'cicon center',
    ),
    array(
        'name'=>getTextTranslate(54),
        'class'=>'',
    ), 
		array(
				'name'=>'Cao tốc',
				'class'=>'center',
		),
		array(
				'name'=>'Địa danh',
				'class'=>'center ',
		),
		array(
				'name'=>'Khoảng cách (km)',
				'class'=>'center w100p',
		),
		array(
				'name'=>'Lưu đêm',
				'class'=>'center w100p',
		),
  	array(
  		'name'=>getTextTranslate(117),
  		'class'=>'center w50p','order'=>true,'field'=>'a.position'
  	),
    array(
        'name'=>getTextTranslate(57),
        'class'=>'center cactive',
    ),
));
echo '<tbody>';
$role = [
		'type'=>'single',
		'table'=>$model->tableName(),
		'controller'=>Yii::$app->controller->id,
			
];
if(!empty($l['listItem'])){
	foreach($l['listItem'] as $k=>$v){
		$role['id']=$v['id'];
		$role['action'] = 'Ad_quick_change_item';
		$link = get_link_edit($v['id']);
		$pl = $model->get_existed_place($v['id']);
 
  $places = array();
  if(!empty($pl)){
  	foreach ($pl as $place){
  		$places[] = $place['name'];
  	}
  }
    echo '<tr class="tr_item_'.$v['id'].'">
        <td class="center">
        <input type="checkbox" class="checked_item" name="check_item[]" value="'.$v['id'].'" />
        </td>
        <td class="center">'.($k+1).'</td>
        <td><a href="#" rel="popover" tabindex="0" class="center block" role="button"  data-img="'.(isset($v['icon']) ? $v['icon'] : '').'" data-trigger="focus" title="'.getTextTranslate(52).'"
        data-content="">'
        .getImage(array(
                'src'=>isset($v['icon']) ? $v['icon'] : '',
                'h'=>30,'w'=>30,
                'attr'=>array(
                    'class'=>'img-rounded img-small-icon',
                    'alt'=>'' ,
                    'data-holder-rendered'=>"true"  ,
                 ),
              )).
        '</a></td>
        <td class=""><a href="'.$link.'">'.(isset($v['title']) ? uh($v['title']) : '').'</a></td>
        <td>'.
        $model->show_list_hight_way($v['id'])
        . '</td>		
		<td class="center">'.(!empty($places) ? implode(' | ', $places) : '').'</td>          		
         '.Ad_list_show_qtext_field($v,[
        		'field'=>'distance',
        		'class'=>'number-format ',
        		'decimal'=>0,
        		'role'=>$role
        ])
        		.Ad_list_show_qtext_field($v,[
        		'field'=>'overnight',
        		'class'=>'number-format ',
        		'decimal'=>0,
        		'role'=>$role
        ]).
        		Ad_list_show_qtext_field($v,[
        		'field'=>'position',
        		'class'=>'number-format ',
        		'decimal'=>0,
        		'role'=>$role
        ]).
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