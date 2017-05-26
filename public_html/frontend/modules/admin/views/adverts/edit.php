<?php
if(!__IS_ROOT__ && Yii::$app->request->get('type_id',0) == 0 && $this->getAction() == 'add'){
	header("Location:".ADMIN_ADDRESS);
}
$fp = dirname(__FILE__) . '/_forms/' . getParam('child') .'.php';
if(file_exists($fp)){
    include_once($fp);
}else{

?>
<?php
$type = 'advert_category';
 
?>
<div class="table-responsive ">

<div class="fl100 edit-form">
<form id="editFormContent" method="post" class="form-horizontal edit-form-left" role="form" enctype="multipart/form-data">
<input type="hidden" class="currentTab" name="currentTab" value="" />
<input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
<input type="hidden" name="formSubmit" value="true"/>
<input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"/> 
<input type="hidden" name="type" value="<?php echo $type;?>"  />
 

    <!-- Nav tabs -->
<ul class="nav form-edit-tab nav-tabs" role="tablist">
<li role="presentation" class="active"><a href="#tab-general" role="tab" data-toggle="tab"><?php echo getTextTranslate(68);?></a></li>
  <li role="presentation" class=""><a href="#tab-help" role="tab" data-toggle="tab"><?php echo getTextTranslate(73);?></a></li>

</ul>

<!-- Tab panes -->
<div class="tab-content edit-form-content-tab">

  <div role="tabpanel" class="tab-panel active" id="tab-general">

        <div class="p-content">
        <div class="row">
        <div class="col-sm-12">
        
         
<?php 

echo Ad_edit_show_text_field($v,[
		'field'=>'code',
		'label'=>'Code',
		'class'=>'required'
]);echo Ad_edit_show_text_field($v,[
		'field'=>'title',
		'label'=>getTextTranslate(54,ADMIN_LANG),
		'class'=>'required'
]);
echo Ad_edit_show_text_field($v,[
		'field'=>'info',	 
		'label'=>getTextTranslate(90,ADMIN_LANG),
		'class'=>'',
		'input_type'=>'textarea'
]);

echo Ad_edit_show_check_field([
		'field'=>'is_active',
		'value'=>isset($v['is_active']) ? $v['is_active'] : 1,
		'type'=>'time',
		'label'=>getTextTranslate(57,ADMIN_LANG),
		'active_from_date'=>isset($v['active_from_date']) ? $v['active_from_date'] : '',
		'active_to_date'=>isset($v['active_to_date']) ? $v['active_to_date'] : '',
]);
echo Ad_edit_show_check_field([
		'field'=>[
				'f[set_language]'=>['value'=>isset($v['set_language']) ? $v['set_language'] : 0,'label'=>'Cài đặt theo ngôn ngữ','boolean'=>true],
					
		]
]);

if(Yii::$app->user->can(ROOT_USER)){
echo Ad_edit_show_check_field([
		'field'=>[
				'f[is_all]'=>['value'=>isset($v['is_all']) ? $v['is_all'] : 0,'label'=>'All','boolean'=>true],
					
		]
]);
}

?>

         
         


         </div>
 



    </div></div></div>

  <div role="tabpanel" class="tab-panel" id="tab-help">
    <div class="fl100">
        <div class="p-content f12e">
           <?php echo getTextTranslate(132);?>
        </div>
    </div>
  </div>

</div>


</form>
</div>



</div>

<?php }?>