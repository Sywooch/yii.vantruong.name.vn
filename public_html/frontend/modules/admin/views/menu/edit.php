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
  <li role="presentation" class=""><a href="#tab-advanced" role="tab" data-toggle="tab">'.getTextTranslate(106,ADMIN_LANG).'</a></li>
  <li role="presentation" class=""><a href="#tab-seo" role="tab" data-toggle="tab">'.getTextTranslate(71,ADMIN_LANG).'</a></li>';
if(in_array(getParam('type'), array('tours'))){
	echo '<li role="presentation" class=""><a href="#tab-destination" role="tab" data-toggle="tab">Điểm đến</a></li>';
}

echo '<li role="presentation" class=""><a href="#tab-file-attach" role="tab" data-toggle="tab">'.getTextTranslate(127,ADMIN_LANG).'</a></li>';
if(Yii::$app->request->get() == 'edit'){
	echo '<li><a rel="link_target" target="_blank" href="'.$this->createUrl($v['url']).'" >'.getTextTranslate(74,ADMIN_LANG).'</a></li>';
}
?>
  

</ul>

<!-- Tab panes -->
<div class="tab-content edit-form-content-tab">
  <div role="tabpanel" class="tab-panel active" id="tab-general">

        <div class="p-content"> <div class="row">
         <div class="col-sm-12">

         <div class="form-group">
          <label for="inputTitle" class="col-sm-12 control-label"><?php echo getTextTranslate(54,ADMIN_LANG);?></label>
          <div class="col-sm-12">

            <?php
            if(Yii::$app->controller->action->id == 'add'){
              echo '<input type="text" name="f[title]" class="form-control input-sm required tagsinput" id="inputTitle" placeholder="Nhấn Enter để thêm nhiều" data-height="30" />';
            }else{
              echo '<input type="text" name="f[title]" class="form-control input-sm required" id="inputTitle" placeholder="Title" value="'.$v['title'].'" />';
            }
            ?>

          </div>
         </div>
         
         

            <?php
            //if(Yii::$app->request->get() == 'edit'){
            	echo '<div class="form-group"><label for="inputTitle" class="col-sm-12 control-label">Tiêu đề ngắn</label><div class="col-sm-12">';
             	echo '<input type="text" name="f[short_title]" class="form-control input-sm" placeholder="Tiêu đề ngắn" value="'.(isset($v['short_title']) ? uh($v['short_title']) : '').'" />';
             	echo '</div></div>';
           // }
            //if(Yii::$app->request->get() == 'edit'){
            	echo '<div class="form-group"><label for="inputTitle" class="col-sm-12 control-label">Mô tả ngắn</label><div class="col-sm-12">';
            	echo '<input type="text" name="biz[short_info]" class="form-control input-sm" placeholder="Mô tả ngắn" value="'.(isset($v['short_info']) ? uh($v['short_info']) : '').'" />';
            	echo '</div></div>';
           // }
          
		?>
        <div class="form-group">
          <label for="inputLink" class="col-sm-12 control-label"><?php echo getTextTranslate(65,ADMIN_LANG);?></label>
          <div class="col-sm-12 group-sm34">       
            <select data-language="vi" name="f[parent_id]" class="form-control select2">
            <option value="0">[-- <?php echo getTextTranslate(105,ADMIN_LANG);?> --]</option>
            <?php

            if(!empty($all)){
              foreach($all as $k1=>$v1){
                echo '<option '.(in_array($v1['id'],$igr) ? 'disabled="disabled"' : '').' '.(isset( $v['parent_id']) && $v1['id'] == $v['parent_id'] ? 'selected="selected"' : '').' value="'.$v1['id'].'">'.spc($v1['level']).$v1['title'].'</option> ';
              }
            }
            
            ?>
            </select>
          </div>
        </div>
<div class="form-group">
          <label for="inputicon" class="col-sm-12 control-label"><?php echo getTextTranslate(70,ADMIN_LANG);?></label>
          <div class="col-sm-12">
          <?php
            if(isset($v['icon']) && $v['icon'] != ""){
              echo '<p>';
              echo getImage(array(
                'src'=>$v['icon'], 
                'h'=>100,
                'attr'=>array(
                    'class'=>'img-thumbnail img-icon',
                    'alt'=>'' ,
                    'data-holder-rendered'=>"true"
                 ),
              ));

             echo '</p>';
            }
          ?>
            
            <input type="file" id="inputslide_image" name="image" class="f12e form-control input-sm" accept="image/*" />
            
            <p class="help-block"></p>
          </div>
          
        </div>
        
        <?php
         echo '<div class="form-group">
          <label  class="col-sm-12 control-label">'. getTextTranslate(104,ADMIN_LANG).'</label>
          <div class="col-sm-12">
            <input type="text" maxlength="255" name="old_icon" class="form-control input-sm" value="'.(isset($v['icon']) ? $v['icon'] : '').'" placeholder="Sử dụng khi lấy ảnh từ 1 site khác" />
          </div>
         </div>';


        ?>
        <div class="form-group">
          <label for="inputPosition" class="col-sm-12 control-label"><?php echo getTextTranslate(56,ADMIN_LANG);?></label>
          <div class="col-sm-6">
            <input type="text" name="f[position]" class="form-control input-sm numberFormat" id="inputPosition" placeholder="Position" value="<?php echo isset($v['position']) ? $v['position'] : 999;?>" />
          </div>
           
        </div>
        <?php echo getCheckBox(array(
            'name'=>'f[is_active]',
            'value'=>(isset($v['is_active']) ? $v['is_active'] : 1),
            'label'=>'Kích hoạt',
        	'type'=>'n02'
        ));?>


         </div>
          

        </div>
    </div>
  </div>
  <div role="tabpanel" class="tab-panel" id="tab-advanced">

        <div class="p-content">
        <div class="row">
        <div class="col-sm-12">

        <div class="form-group">
          <label for="inputLink" class="col-sm-12 control-label"><?php echo getTextTranslate(102,ADMIN_LANG);?></label>
          <div class="col-sm-12">
            <select data-target=".input-link-target" onchange="return changeFormType(this);" data-language="vi" data-minimum-results-for-search="Infinity" name="f[type]" style="width: 100%" class="form-control input-sm select2">
            <option value="-1">[-- <?php echo getTextTranslate(103,ADMIN_LANG);?> --]</option>

            <?php
            
            if(!empty($forms)){
            	$type = isset($v['type']) ? $v['type'] : (getParam('type'));
              foreach($forms as $k1=>$v1){

                echo '<option '.($v1['code'] == $type? 'selected="selected"' : '').' value="'.$v1['code'].'">' .(__LANG__ == DEFAULT_LANG ? $v1['title'] : $v1['title']).'</option> ';
              }
            }
            echo '<option '.(isset($v['type']) && 'manual' == $v['type'] ? 'selected="selected"' : '').' value="manual">'.getTextTranslate(177,ADMIN_LANG).'</option> ';
            ?>
            </select>
          </div>
        </div>

        <div class="form-group input-link-target" style="<?php echo isset($v['type']) && in_array($v['type'],array('link','manual')) ? '' : 'display:none'; ?>">
          <label for="inputlink_target" class="col-sm-12 control-label">Link target</label>
          <div class="col-sm-12">
            <input type="text" name="biz[link_target]" class="form-control  input-sm" id="inputlink_target" placeholder="" value="<?php echo isset($v['link_target']) ? $v['link_target'] : '';?>" />
          </div>
        </div>
<?php 

if(!empty($l1)){
 
			echo '<div class="form-group"><label class="col-sm-12 control-label">Danh mục (form)</label><div class="col-sm-12">';
			echo '<select data-minimum-results-for-search="Infinity" class="form-control input-sm select2" name="f[category_type]">';
			echo '<option value="0">--</option>';
			foreach ($l1 as $k1=>$v2){
				echo '<option '.(isset($v['category_type']) && $v['category_type'] == $v2['value'] ? 'selected' : '').' value="'.$v2['value'].'">'.uh($v2['title']).'</option>';
			}
			echo '</select>';
			echo '</div></div>';
	 
}
?>        
        <div class="form-group">
          <label class="col-sm-12 control-label">Chuyển hướng</label>
          <div class="col-sm-12">
            <input type="text" name="biz[link_redirect]" class="form-control input-sm" placeholder="Link đích: http://link-moi hoặc /link-moi" value="<?php echo isset($v['link_redirect']) ? $v['link_redirect'] : '';?>" />
          </div>
        </div>
        
        <div class="form-group">
          <label class="col-sm-12 control-label">Action</label>
          <div class="col-sm-2">
            <select data-minimum-results-for-search="Infinity" class="form-control input-sm select2" name="biz[action]">
            <option value="">[-- Không sử dụng --]</option>
            <option <?php echo isset($v['action']) && $v['action'] == 'onclick' ? 'selected' : '';?> value="onclick" >+&nbsp;&nbsp;&nbsp;onClick</option>
            <option <?php echo isset($v['action']) && $v['action'] == 'onchange' ? 'selected' : '';?> value="onchange">+&nbsp;&nbsp;&nbsp;onChange</option>
            <option <?php echo isset($v['action']) && $v['action'] == 'onblur' ? 'selected' : '';?> value="onblur">+&nbsp;&nbsp;&nbsp;onBlur</option>
            </select>

          </div>
          <div class="col-sm-6"> 
            <input type="text" name="biz[action_detail]" class="form-control input-sm" placeholder="Return action function" value="<?php echo isset($v['action_detail']) ? $v['action_detail'] : '';?>" />
          </div>
          
          <div class="col-sm-4"> 
            <select data-placeholder="Style" data-minimum-results-for-search="Infinity" class="form-control input-sm select2" name="biz[style]">
             <optgroup label="Style"><option value="0">--</option>
            <?php 
            for($i = 1; $i < 11; $i++){
            	echo '<option '.(isset($v['style']) && $v['style'] == $i ? 'selected' : '' ).' value="'.$i.'">Style '.$i.'</option>';	
            }
            ?></optgroup>
            </select>
          </div>          
           
          
        </div> 
        <div class="form-group">
          <label for="inputLink" class="col-sm-12 control-label">Menu liên kết</label>
          <div class="col-sm-12">       
            <select multiple="multiple" data-language="vi" name="biz[menu_ex][]" class="form-control select2">
            <option value="0"></option>
            <?php
             
            if(!empty($all)){
              foreach($all as $k1=>$v1){
                echo '<option '.(in_array($v1['id'],$igr) ? 'disabled="disabled"' : '').' '.(isset($v['menu_ex']) && in_array($v1['id'], $v['menu_ex']) ? 'selected="selected"' : '').' value="'.$v1['id'].'">'.spc($v1['level']).$v1['title'].'</option> ';
              }
            }
            ?>
            </select>
          </div>
        </div>    
        <div class="form-group">
          <label class="col-sm-12 control-label">Icon class (Font Awesome hoặc Glyphicon)</label>
          <div class="col-sm-12">
            <input type="text" name="biz[icon_class]" class="form-control input-sm" placeholder="fa fa-icon" value="<?php echo isset($v['icon_class']) ? $v['icon_class'] : '';?>" />
          </div>
        </div>           
        <div class="form-group">
          <label  class="col-sm-12 control-label"><?php echo getTextTranslate(55,ADMIN_LANG);?></label>
          <div class="col-sm-12">
          <?php
          echo $positions;
          ?>
          </div>
        </div>
        
        

<div class="form-group">
<label for="ckeditor_detail" class="control-label col-sm-2">Text</label>
          <div class="col-sm-12">
          
          
          <?php
          //view($v['c_detail']);
          echo ckeditor('text_detail',array(
                'attr'=> array('class'=>'','name'=>'biz[text]')    ,
                'upload'=>true,
                'toolbar' =>  'Basic',
                'value' =>isset($v['text']) ?  uh($v['text'],2) : '',
                'h'=>250,
          ));

          ?>
          </div>
        </div>
         </div>
      
        </div>
    </div></div>

  
<?php

include_once '_forms/tab_seo.php';
if(in_array(getParam('type'), array('tours'))){
	echo '<div role="tabpanel" class="tab-panel" id="tab-destination" style=""><div class="fl100"><div class="p-content f12e">';
 
$f = $destination['listItem']; 
	if(!empty($f)){ 
		echo '<div class="clear	">';
		foreach ($f as $kf=>$vf){
			$vf['level'] = isset($vf['level']) ? $vf['level'] : 0;
			echo '<div class="inline-block vtop fl" style="width:33%"><div class="checkbox m-level-'.($vf['level']+1).'"><label><input data-role="prs_ckc_forms" data-parent="0" data-id="'.$vf['id'].'" '.(in_array($vf['id'], $selected_destination) ? 'checked' : '').' name="fDestination[]" value="'.$vf['id'].'" type="checkbox"><b class="'.($vf['type'] == 1 ? 'green' : 'red').'">'.uh($vf['name']).'</b></label></div>';			 
			echo '</div>';
		}
		echo '</div>';
	}
echo '</div></div></div>';
}
include_once '_forms/files_attach.php';
 ?>
 
</div>


</form>
</div>



</div>

 