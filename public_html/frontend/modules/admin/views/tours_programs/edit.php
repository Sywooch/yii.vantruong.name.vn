<?php 
/*/view(\app\modules\admin\models\ProgramSegments::getAllChild(1,0));
(\app\modules\admin\models\ToursPrograms::setSegmentsAutoGuides([
		'item_id'=>1,
			
]));
*/
//\app\modules\admin\models\ToursPrograms::setSegmentsAutoGuides(['item_id'=>1]);/
 

  
//view($a);


  /*
view(\app\modules\admin\models\ToursPrograms::getProgramGuides([
				'item_id'=>1,
				'segment_id'=>7,
				'guide_type'=>2
		]));*/
/*
view(Yii::$app->zii->getProgramGuidesPrices([
		'item_id'=>1,
		'controller_code'=>TYPE_ID_GUIDES,
		'service_id'=>30,
		'loadDefault'=>true,
		'segment_id'=>15,
		'updateDatabase'=>false
]));
*/
?>

<div class="table-responsive ">
<div class="fl100 edit-form">
<form id="editFormContent" method="post" class="form-horizontal edit-form-left" role="form" enctype="multipart/form-data">
<input type="hidden" class="currentTab" name="currentTab" value="" />
<input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
<input type="hidden" name="formSubmit" value="true"  />
<input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"/>
    <!-- Nav tabs -->
<ul class="nav form-edit-tab nav-tabs" role="tablist">
<?php 
echo '<li role="presentation" class=""><a href="#tab-general1" role="tab" data-toggle="tab">Yêu cầu / CT vắn tắt</a></li>
	<li role="presentation" class="active"><a href="#tab-general" role="tab" data-toggle="tab">'.getTextTranslate(68,ADMIN_LANG).'</a></li>
   
 <li role="presentation" class=""><a href="#tab-help" role="tab" data-toggle="tab">'.getTextTranslate(73,ADMIN_LANG).'</a></li>';
 
  if(isset($v['url'])){
  	echo '<li><a rel="link_target" target="_blank" href="'.cu(DS.$v['url']).'" >'.getTextTranslate(74,ADMIN_LANG).'</a></li>';
  }
  ?>
</ul> 

<!-- Tab panes -->
<div class="tab-content edit-form-content-tab">
<div role="tabpanel" class="tab-panel active" id="tab-general">
<div class="p-contentxx">
<?php 
include_once '_forms/thong_tin_chung.php';
if(Yii::$app->controller->action->id == 'edit'){
	include_once '_forms/thong_tin_chi_tiet.php';
	include_once '_forms/van_chuyen.php';
}
/*
echo Ad_edit_show_text_field($v,[
		'field'=>'title',
		'label'=>getTextTranslate(54,ADMIN_LANG),
		'class'=>'required'
]);
echo Ad_edit_show_image_field($v,[
		'field'=>'icon',
		'label'=>'Icon/Thumbnail',
		'class'=>'',
		'placeholder'=>'Url hình ảnh',
		'field_name'=>'icon',
			
]);
echo Ad_edit_show_text_field($v,[
		'field'=>'position',
		'label'=>getTextTranslate(56,ADMIN_LANG),
		'class'=>'number-format',
		'placeholder'=>'Thứ tự xắp xếp'
]);
echo Ad_edit_show_check_field([
		'field'=>[
				'f[is_active]'=>['value'=>isset($v['is_active']) ? $v['is_active'] : 1,'label'=>getTextTranslate(57,ADMIN_LANG),'boolean'=>true],
					
		]
]);
*/
loadTourProgramGuides(1);
?>   
</div></div> 
<div role="tabpanel" class="tab-panel" id="tab-general1">
   <div class="col-sm-12 mgt30">
        
        <?php

          echo ckeditor('detail-tab-'.randString(5),array(
                'attr'=> array('class'=>'','name'=>'biz[summary]')    ,
                'upload'=>true,
                'toolbar' =>  'Full',
                'value' =>isset($v['summary']) ? uh($v['summary'],2) : '',
                'h'=>550,
          ));

          ?>
        
    </div>
</div>
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