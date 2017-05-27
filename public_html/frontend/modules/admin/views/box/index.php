<div class="table-responsive ">

<div class="fl100  ">
 <form id="editFormContent" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
 <input type="hidden" class="currentTab" name="currentTab" value="" />
 <input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
 <input type="hidden" name="formSubmit" value="true"  />
    <!-- Nav tabs -->
<ul class="nav form-edit-tab nav-tabs hide" role="tablist">
  <li role="presentation" class="active"><a href="#tab-general" role="tab" data-toggle="tab"></a></li>
  <li role="presentation" class="hide"><a href="#tab-advanced" role="tab" data-toggle="tab">Cài đặt nâng cao</a></li>
  <li role="presentation" class="hide"><a href="#tab-seo" role="tab" data-toggle="tab">Cấu hình SEO</a></li>

</ul>

<!-- Tab panes -->
<div class=" ">
  <div > 

         <div class="fl100">
 
<?php
 //view($this->admin()->model);
?>
<div class="table-responsive ">
<?php
 
?>
  <table class="table table-bordered vmidle table-hover">
  <?php
  echo afShowThread(array(
     
    array(
        'name'=>'Code',
        'class'=>'center  ',
    ),
    array(
        'name'=>getTextTranslate(54,ADMIN_LANG),
        'class'=>'',
    ),
    array(
        'name'=>getTextTranslate(65,ADMIN_LANG),
        'class'=>'',
    ),
     
    array(
        'name'=>'Module',
        'class'=>'center ctime',
    ),
  		array(
  				'name'=>'Style',
  				'class'=>'center ctime',
  		),
  		array(
  				'name'=>getTextTranslate(125,ADMIN_LANG),
  				'class'=>'center ctime',
  		),
    array(
        'name'=>getTextTranslate(56,ADMIN_LANG),
        'class'=>'center cposition',
    ),
    array(
        'name'=>getTextTranslate(57,ADMIN_LANG),
        'class'=>'center cactive',
    ),

  ));
  ?>

    <tbody>
<?php
$a = array(
		'normal'=>'',
		'index'=>getTextTranslate(37,ADMIN_LANG),
		'left'=>'Sidebar ('.getTextTranslate(130,ADMIN_LANG).')',
		'right'=>'Sidebar ('.getTextTranslate(131,ADMIN_LANG).')'
);
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
    echo '<tr class="tr_item_'.$v['id'].'">
        <td class="center">
        <input type="checkbox" class="checked_item" name="check_item[]" value="'.$v['id'].'" />
        </td>
        <td class="center">'.($k+1).'</td>
     
        <td class="nowrap">'.uh($v['code']).'</td>
        <td class="nowrap"><a href="'.$link.'">'.uh($v['title']).'</a></td>
        <td class="">'.$v['categoryName'].'</td>
        
 
<td class="center"><p class="stime">'.($a[$v['module']]).'</p></td>
		<td class="center"><p class="stime">'.(isset($v['style']) && $v['style'] > 0 ? 'Style '.$v['style'] : '').'</p></td>
        
        '.Ad_list_show_qtext_field($v,[
        		'field'=>'limit',
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
        ])
       .'</tr>';

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
?>
 
    </div>
  </div>
  <div role="tabpanel" class="tab-pane" id="tab-advanced">

    <div class="p-content">
        <div class="row">
       
        </div>
    </div></div>

  <div role="tabpanel" class="tab-pane" id="tab-seo">

        <div class="p-content">
        <div class="row">
        <div class="col-sm-6">
 
         </div>
         <div class="col-sm-6"></div>
 
    </div></div></div>

  <div role="tabpanel" class="tab-pane" id="messages">
    <div class="fl100">
        <div class="p-content">

        </div>
    </div>
  </div>
  <div role="tabpanel" class="tab-pane" id="settings">
        <div class="p-content">

        </div>
  </div>
</div>


</form>
</div>



</div>

