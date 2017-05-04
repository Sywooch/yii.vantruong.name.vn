<?php
use yii\helpers\Html;
use app\modules\admin\models\EmailsSubscribes;
echo '<div class="admin-menu-index col-sm-12 col-xs-12"><div class="row"><div class="table-responsive ">';
$l = EmailsSubscribes::getList();
echo '<table class="table table-bordered vmiddle table-hover">';
echo afShowThread(array(
	array(
			'name'=>'Icon',
			'class'=>'cicon center',
	),
	array(
			'name'=>'Họ tên',
			'class'=>'',
	),
	array(
				'name'=>'Email',
				'class'=>'w300p center',
	),
	 

)); 
echo '<tbody>';
$role = [
		'type'=>'single',
		'table'=>$model->tableName(),
		'controller'=>Yii::$app->controller->id,		
		'action'=>'Ad_quick_change_item',
		'identity_field'=>'email',
		 
];
if(!empty($l['listItem'])){
	foreach($l['listItem'] as $k=>$v){ 
	  $v['identity_value'] = $role['identity_value'] = $v['email'];
	  $v['identity_field'] = 'email';
    echo '<tr class="tr_item_'.$k.'">
        '.Ad_list_show_check($v,['identity_field'=>$v['identity_field']]).'
        <td class="center">'.($k+1).'</td>
        '.Ad_list_show_icon($v).'
        '.Ad_list_show_qtext_field($v,[
        		'field'=>'title',
        		'class'=>'aleft',
        		'role'=>$role,
        		'identity_field'=>$v['identity_field'],
        		'identity_value'=>$v['email'],
        ]).'  
        '.Ad_list_show_plain_text_field($v,['field'=>'email']).
    	 
              
		Ad_list_show_option_field($v,[
        		'role'=>$role + ['delete_option'=>'remove','update_state'=>0,'field_check_index'=>$k],
				'identity_field'=>$v['identity_field'],
				'edit'=>false,
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