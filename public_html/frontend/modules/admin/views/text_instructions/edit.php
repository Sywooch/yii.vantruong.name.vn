<div class="table-responsive ">
<div class="fl100 edit-form">
<form id="editFormContent" method="post" class="form-horizontal edit-form-left" role="form" enctype="multipart/form-data">
<input type="hidden" class="currentTab" name="currentTab" value="" />
<input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
<input type="hidden" name="formSubmit" value="true"  />
<input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"  />
    <!-- Nav tabs -->
<ul class="nav form-edit-tab nav-tabs" role="tablist">
<?php 
echo '<li role="presentation" class="active"><a href="#tab-general" role="tab" data-toggle="tab">'.getTextTranslate(68,ADMIN_LANG).'</a></li>
   
 <li role="presentation" class=""><a href="#tab-help" role="tab" data-toggle="tab">'.getTextTranslate(73,ADMIN_LANG).'</a></li>';
 
  if(isset($v['url'])){
  	echo '<li><a rel="link_target" target="_blank" href="'.cu(DS.$v['url']).'" >'.getTextTranslate(74,ADMIN_LANG).'</a></li>';
  }
  ?>
</ul>

<!-- Tab panes -->
<div class="tab-content edit-form-content-tab">
<div role="tabpanel" class="tab-panel active" id="tab-general">
<div class="p-content">
<?php 
echo Ad_edit_show_text_field($v,[
		'field'=>'title',
		'label'=>getTextTranslate(54,ADMIN_LANG),
		'class'=>'required '. (Yii::$app->controller->action->id == 'add' ? 'tagsinput' : '')
]);
 
 
 
?>   
</div></div> 

<div role="tabpanel" class="tab-panel" id="tab-help">
   <div class="fl100">
        <div class="p-content f12e help-panel-<?php echo Yii::$app->controller->id;?>">
            
        </div>
    </div>
</div>
</div>

</form>
</div>
</div>