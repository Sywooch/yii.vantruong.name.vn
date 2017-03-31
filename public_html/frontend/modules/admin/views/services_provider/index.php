<div class="table-responsive ">

<div class="fl100  ">
 <form id="editFormContent" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
 <input type="hidden" class="currentTab" name="currentTab" value="" />
 <input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
 <input type="hidden" name="formSubmit" value="true"  />
    <!-- Nav tabs -->
<ul class="nav form-edit-tab nav-tabs hide" role="tablist">
  <li role="presentation" class="active"><a href="#tab-general" role="tab" data-toggle="tab">Box trang chủ</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content edit-form-content-tab">
  <div role="tabpanel" class="tab-pane active" id="tab-general">

        <div class=" ">
 
         <div class="col-sm-12s ">

<div class="table-responsive ">
 
  <table class="table table-bordered vmiddle table-hover">
  <?php
  echo afShowThread(array(
  		array(
  				'name'=>'Mã số',
  				'class'=>'center cactive',
  		),
  		array(
  				'name'=>'Tên viết tắt',
  				'class'=>'',
  		),
	    array(
	        'name'=>'Tên đầy đủ',
	        'class'=>'',
	    ),
  		array(
  				'name'=>'Địa danh',
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
			
		'action'=>'Ad_quick_change_item'
];

if(!empty($l['listItem'])){$i=0;
foreach ($l['listItem'] as $k=>$v){
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
		<td class="center"><a href="'.$link.'">'.($v['code']).'</a></td>
  		<td class="center nowrap"><a href="'.$link.'">'.($v['short_name']).'</a></td>
 
        <td class="nowrap"><a href="'.$link.'">'.uh($v['name']).'</a></td>
  		<td class="center nowrap">'.(!empty($places) ? implode(' | ', $places) : '').'</td>
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
        ]) .'
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
 
         </div>
     


 
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

