<?php
echo '<div class="table-responsive"><table class="table table-bordered vmiddle table-hover">';
echo afShowThread([
    [
        'name'=>'Icon',
        'class'=>'cicon center',
    ],
    [ 
        'name'=>'Code',
        'class'=>'center  ',
    ],
  	[
  		'name'=>getTextTranslate(54,ADMIN_LANG),
  		'class'=>' ','order'=>true,'field'=>'a.title'
  	],
  	[
  		'name'=>getTextTranslate(65,ADMIN_LANG),
  		'class'=>' ','order'=>true,'field'=>'category_name'
  	],
  	
  	[
  		'name'=>getTextTranslate(29,ADMIN_LANG),
  		'class'=>'center  ','order'=>true,'field'=>'a.price2'
  	],
  	[
  		'name'=>getTextTranslate(66,ADMIN_LANG),
  		'class'=>'center ctime',
  		'order'=>true,'field'=>'a.time'
  	],
  	[
  		'name'=>getTextTranslate(117,ADMIN_LANG),
  		'class'=>'center w50p','order'=>true,'field'=>'a.position'
  	],
  	[
  		'name'=>getTextTranslate(57,ADMIN_LANG),
  		'class'=>'center cactive','order'=>true,'field'=>'a.is_active'
  	]

  ],['STT'=>false]);
  
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
		$is_edited = checkAuthByUser(['form-'.getParam('type').'-edit','form-'.getParam('type').'-edit-all'],['post_by'=>$v['owner']]) ;
		$is_deleted = checkAuthByUser(['form-'.getParam('type').'-delete','form-'.getParam('type').'-edit-delete'],['post_by'=>$v['owner']]) ;
		$link = $is_edited ? get_link_edit($v['id']) : '#';
		$list_category = $model->getItemCategorys($v['id'],0);
		//var_dump(checkAuthByUser(['form-'.getParam('type').'-edit','form-'.getParam('type').'-edit-all'],['post_by'=>$v['owner']]) );
		echo '<tr class="tr_item_'.$v['id'].'">
        '.Ad_list_show_check($v,['disabled'=>!$is_deleted]).'        
        '.Ad_list_show_icon($v).'
  		'.Ad_list_show_link_field($v,['field'=>'code','link'=>$link]).'				
        '.Ad_list_show_link_field($v,['field'=>'title','link'=>$link]).'
        '.Ad_list_show_plain_text_field($list_category).'
        '.Ad_list_show_qtext_field($v,[
        		'field'=>'price2',
        		'class'=>'number-format bold',
        		'decimal'=>2,
        		'role'=>$role,
        		'disabled'=>!$is_edited,
        ]).'
		'.Ad_list_show_plain_text_field('<p class="stime" title="'.date("d/m/Y H:i",strtotime($v['time'])).'">'.date("d/m/Y",strtotime($v['time'])).'</p>'.($v['post_by_name'] != "" ? '<p class="post_by"><i class="f11p">- '. ($v['post_by_name']).' -</i></p>' : '').'').'		
		
        '.Ad_list_show_qtext_field($v,[
        		'field'=>'position',
        		'class'=>'number-format ',
        		'decimal'=>0,
        		'role'=>$role,
        		'disabled'=>!$is_edited
        		
        ]).
        
        Ad_list_show_checkbox_field($v,[
        		'field'=>'is_active',
        		//'class'=>'number-format ',
        		//'decimal'=>0,
        		'role'=>$role,
        		'disabled'=>!$is_edited
		]);
        //var_dump($is_edited);
        echo Ad_list_show_option_field($v,[
        		'role'=>$role,
        		'post_by'=>$v['owner'],
        		'action'=>'Ad_quick_delete_item',
        		'btn'=>[''],
        		'edit'=>$is_edited,
        		'del'=>$is_deleted
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
		'btn'=>['del'=>['attr'=>$role]],
		
]);