<?php
use yii\helpers\Html;
use app\modules\admin\models\ToursPrograms;
echo '<div class="admin-menu-index col-sm-12 col-xs-12"><div class="row"><div class="table-responsive ">';
$l = ToursPrograms::getList();
echo '<table class="table table-bordered vmiddle table-hover">';
echo afShowThread(array(
	array(
			'name'=>'Icon',
			'class'=>'cicon center',
	),
	array(
			'name'=>getTextTranslate(54,ADMIN_LANG),
			'class'=>'',
	),
	array(
			'name'=>getTextTranslate(117,ADMIN_LANG),
			'class'=>'center w50p','order'=>true,'field'=>'a.position'
	),
	array(
			'name'=>getTextTranslate(57,ADMIN_LANG),
			'class'=>'center cactive',
	),

)); 
echo '<tbody>';
$role = [
		'type'=>'single',
		'table'=>$model->tableName(),
		'controller'=>Yii::$app->controller->id,		
		'action'=>'Ad_quick_change_item'
];
if(!empty($l['listItem'])){
	foreach($l['listItem'] as $k=>$v){ 
	  $role['id']=$v['id'];	  
	  $link = get_link_edit($v['id']).'#tab-general';
    echo '<tr class="tr_item_'.$v['id'].'">
        '.Ad_list_show_check($v).'
        <td class="center">'.($k+1).'</td>
        '.Ad_list_show_icon($v).'
        '.Ad_list_show_link_field($v,['field'=>'title','link'=>$link]).'     
    	'.Ad_list_show_qtext_field($v,[
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
		]).      
		Ad_list_show_option_field($v,[
        		'role'=>$role,
        		'action'=>'Ad_quick_delete_item',
        		'btn'=>[''],
				'tab_click'=>'#tab-general'
        ])
      .'</tr>';

  }
}
echo '</tbody></table></div>';
echo afShowPagination(array(
    'btn'=>['del'=>['attr'=>$role]],	
	'p'=>$l['p'],
	'total_records'=>$l['total_records'],
	'total_pages'=>$l['total_pages'],
	'limit'=>$l['limit'],
));
echo '</div></div>';