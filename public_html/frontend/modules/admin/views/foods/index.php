<?php
use yii\helpers\Html;
use app\modules\admin\models\Foods;
echo '<div class="admin-menu-index col-sm-12 col-xs-12"><div class="row"><div class="table-responsive ">';
  
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
				'name'=>getTextTranslate(65,ADMIN_LANG),
				'class'=>'',
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
	  $link = get_link_edit($v['id']);
	  $list_category = $model->getItemCategorys($v['id'],0);
    echo '<tr class="tr_item_'.$v['id'].'">
        '.Ad_list_show_check($v).'
        <td class="center">'.($k+1).'</td>
        '.Ad_list_show_icon($v).'
        '.Ad_list_show_link_field($v,['field'=>'title','link'=>$link]).     
        Ad_list_show_plain_text_field($list_category).
		Ad_list_show_option_field($v,[
        		'role'=>$role,
        		'action'=>'Ad_quick_delete_item',
        		'btn'=>['']
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