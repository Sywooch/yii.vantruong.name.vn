<div class="table-responsive ">

<div class="fl100 edit-form">
 <form id="editFormContent" method="post" class="form-horizontal edit-form-left" role="form" enctype="multipart/form-data">
 <input type="hidden" class="currentTab" name="currentTab" value="" />
 <input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
 <input type="hidden" name="formSubmit" value="true"  />
 <input type="hidden" name="f[type]" value="<?php echo getParam('type');?>"  />
<input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"/>
    <!-- Nav tabs -->
<ul class="nav form-edit-tab nav-tabs" role="tablist">
<?php 
echo '<li role="presentation" class="active"><a href="#tab-general" role="tab" data-toggle="tab">'.getTextTranslate(68,ADMIN_LANG).'</a></li>
  <li role="presentation" class=""><a href="#tab-scheduler" role="tab" data-toggle="tab">'.getTextTranslate(221,ADMIN_LANG).'</a></li>
  <li role="presentation" class=""><a href="#tab-detail" role="tab" data-toggle="tab">'.getTextTranslate(69,ADMIN_LANG).'</a></li>
  
  <li role="presentation" class=""><a href="#tab-images" role="tab" data-toggle="tab">'.getTextTranslate(70,ADMIN_LANG).'</a></li>
  <li role="presentation" class=""><a href="#tab-option" role="tab" data-toggle="tab">'.getTextTranslate(72,ADMIN_LANG).'</a></li>
  <li role="presentation" class=""><a href="#tab-filters" role="tab" data-toggle="tab">'.getTextTranslate(179,ADMIN_LANG).'</a></li>
  <li role="presentation" class=""><a href="#tab-seo" role="tab" data-toggle="tab">'.getTextTranslate(71,ADMIN_LANG).'</a></li>
  <li role="presentation" class=""><a href="#tab-file-attach" role="tab" data-toggle="tab">'.getTextTranslate(127,ADMIN_LANG).'</a></li>
 <li role="presentation" class=""><a href="#tab-help" role="tab" data-toggle="tab">'.getTextTranslate(73,ADMIN_LANG).'</a></li>';
 
  if($this->getAction() == 'edit'){
  	echo '<li><a rel="link_target" target="_blank" href="'.$this->createUrl(DS.$v['url']).'" >'.getTextTranslate(74,ADMIN_LANG).'</a></li>';
  }
  ?>
	
</ul>

<!-- Tab panes -->
<div class="tab-content edit-form-content-tab">
  <div role="tabpanel" class="tab-panel active" id="tab-general">

        <div class="p-content">
        <div class="row">
         <div class="col-sm-12 col8respon">

<?php 
echo Ad_edit_show_text_field($v,[
		'field'=>'title',
		'label'=>getTextTranslate(54,ADMIN_LANG),
		'class'=>'required'
]);

echo Ad_edit_show_text_field($v,[
		'field'=>'short_title',
		'label'=>getTextTranslate(222,ADMIN_LANG),
		'class'=>''
]);

echo Ad_edit_show_text_field($v,[
		'field'=>'code',
		'label'=>getTextTranslate(27,ADMIN_LANG),
		'class'=>''
]);

echo Ad_edit_show_select_field($v,[
		//'field'=>'title',
		'label'=>getTextTranslate(65,ADMIN_LANG),
		'class'=>'required select2',
		'field_name'=>'category_id[]',
		'multiple'=>true,
		'data'=>$all_menu,
		'data-disabled'=>[],
		'data-selected'=>$categorys,
		'option-value-field'=>'id',
		'option-title-field'=>'title',
]);

?>
 
         
        <div class="form-group">
           <div class="col-sm-6 col-xs-12"><div class="row">
           <label for="inputLink" class="col-sm-12 control-label">Danh mục tour</label>
             <div class="col-sm-12 group-sm34">
            <select onchange="selectDeparturePlace(this);" name="tours[tour_type]" class="form-control input-sm select2" data-search="hidden" style="width: 100%" >

            <?php
			$l = $model->getTourType();
 
            if(!empty($l)){
              foreach($l as $k1=>$v1){
              	
                echo '<option '.(isset($v['tour_type']) && $v1['id'] == $v['tour_type'] ? 'selected="selected"' : '').' value="'.$v1['id'].'">+&nbsp;'. $v1['name'].'</option> ';
               
              }
            }
            ?>
            </select>
          </div>
           </div></div>
           <div class="col-sm-6 col-xs-12"><div class="row">
           <label for="inputLink" class="col-sm-12 control-label">Loại tour</label>
           <div class="col-sm-12 group-sm34" >
            <select data-placeholder="Loại tour" name="filters[]" multiple="multiple" class="form-control input-sm select2" style="width: 100%">

            <?php
            
            $l = $filtersModel->getFilters(array(
            		'code'=>'tour_type','parent_id'=>0
            ));
            
            if(!empty($l)){
              foreach($l as $k1=>$v1){
              	echo '<optgroup label="'.$v1['title'].'">';
              	$l2 = $filtersModel->getFilters(array(
              			'parent_id'=>$v1['id']
              	));
              	if(!empty($l2)){
              		$des = isset($v['filters']) && !empty($v['filters']) ? $v['filters'] : array(0);
              		foreach($l2 as $k2=>$v2){
                echo '<option '.(in_array($v2['id'],$des) ? 'selected="selected"' : '').' value="'.$v2['id'].'">'. $v2['title'].'</option> ';
              		}
              	}
                echo '</optgroup>';
              }
            }
            ?>
            </select>
          </div>
           </div></div>
         
          
          
          </div> 
        
         <div class="form-group">
         <div class="col-sm-6 col-xs-12"><div class="row">
         <label for="inputLink" class="col-sm-12 control-label"><?php echo getTextTranslate(15,ADMIN_LANG);?></label>
          <div class="col-sm-12" >
             <div class="row">
             <div class="col-sm-4"><input onchange="autoSetNight(this);" data-target=".input-tour-night" name="tours[day]" class="form-control input-tour-day input-sm bold aright numberFormat" placeholder="<?php echo getTextTranslate(19,ADMIN_LANG);?>" value="<?php echo (isset($v['day']) ? $v['day'] : '');?>" /></div>
             <label  class="col-sm-2 control-label aleft"><?php echo getTextTranslate(19,ADMIN_LANG);?></label>
             
             <div class="col-sm-4"><input name="tours[night]" class="form-control input-tour-night input-sm bold aright numberFormat" placeholder="<?php echo getTextTranslate(20,ADMIN_LANG);?>" value="<?php echo (isset($v['night']) ? $v['night'] : '');?>" /></div>
             <label  class="col-sm-2 control-label aleft"><?php echo getTextTranslate(20,ADMIN_LANG);?></label>
             
             </div>
          </div>
         </div></div>
         
         <div class="col-sm-6 col-xs-12"><div class="row">
         <label  class="col-sm-12 control-label">Điểm đến</label>
          <div class="col-sm-12 group-sm30" data-toggle="tooltip" data-placement="right" title="<?php echo getTextTranslate(14,ADMIN_LANG);?>">
            <select data-placeholder="<?php echo getTextTranslate(14,ADMIN_LANG);?>" multiple="multiple" id="dropPositionEnd" name="tours[destination][]" class="form-control input-sm select2 " style="width: 100%" > 
            <?php
            $tour_type = isset($v['tour_type']) && $v['tour_type'] == 2 ? 2 : 1;
            $label = $tour_type == 1 ? getTextTranslate(21,ADMIN_LANG) : getTextTranslate(22,ADMIN_LANG);
			$destinations = $model->getDeparturePlace(array(
            		'is_destination'=>1,'type'=>$tour_type
            ));
			 
 
            if(!empty($destinations)){
            	echo '<optgroup label="'.$label.'">';
            	$des = isset($v['destinations']) && !empty($v['destinations']) ? $v['destinations'] :
            	array(); 
              foreach($destinations as $k1=>$v1){
                echo '<option '.(in_array($v1['id'] ,$des) ? 'selected="selected"' : '').' value="'.$v1['id'].'">'. $v1['name'].'</option> ';
              }
              echo '</optgroup>'; 
            }
             
            ?>
            </select>
            
          </div>
         </div></div>
         
          
           
          
         
              
          </div> 
      
         
        
   
<div class="form-group">
<div class="col-sm-6 col-xs-12"><div class="row">
<label for="inputLink" class="col-sm-12 control-label"><?php echo getTextTranslate(76,ADMIN_LANG);?></label>
          <div class="col-sm-12">
          <div class="input-groupx group-sm34">
          <select id="select_tour_start_point" name="tours[start]" class="form-control input-sm select2  " style="width: 100%">

            <?php
            
            $starts = $model->getDeparturePlace(array(
            		'is_start'=>1
            ));
 			$hselect = '';
            if(!empty($starts)){
              foreach($starts as $k1=>$v1){
                $hselect .= '<option '.(isset($v['start']) && $v1['id'] == $v['start'] ? 'selected="selected"' : '').' value="'.$v1['id'].'">+&nbsp;'. $v1['name'].'</option> ';
              }
            }
            echo $hselect;
            ?>
            </select>
           <div class="input-group-btn"><?php 
           $l1 = $model->getDeparturePlace(array(
            	'xdepart'=>true,'item_id'=>isset($v['id']) ? $v['id'] : 0,
           		'dtype'=>2
            ));
            
           ?>  
           
          </div> </div>
             
          </div>
</div></div>

<div class="col-sm-6 col-xs-12"><div class="row">
<label for="inputLink" class="col-sm-12 control-label"><?php echo getTextTranslate(28,ADMIN_LANG);?></label>
<div class="col-sm-12  " data-toggle="tooltip" data-placement="top" title="">
          <div class="input-groupx group-sm34">
            <select id="select_tour_hotel_point" name="tours[tour_hotel]" class="form-control input-sm select2" data-search="hidden" style="width: 100%">
			<option value="0"> - <?php echo getTextTranslate(28,ADMIN_LANG);?> -</option>
            <?php           
            $l2 = $model->getTourHotel(array(
              			 
            ));
            if(!empty($l2)){
            	foreach($l2 as $k2=>$v2){
               		echo '<option '.(isset($v['tour_hotel']) && $v2['id'] == $v['tour_hotel'] ? 'selected="selected"' : '').' value="'.$v2['id'].'">+&nbsp;Khách sạn '. $v2['name'].'</option> ';
            	}
           	}
                 
            ?>
            </select>
            <div class="input-group-btn"><?php 
           $l1 = $model->getDeparturePlace(array(
            	'xdepart'=>true,'item_id'=>isset($v['id']) ? $v['id'] : 0,
           		'dtype'=>3
            ));
           //view($l1);
           //echo '<button data-name="xhotel" data-source="#select_tour_hotel_point" title="ThÃªm khÃ¡ch sáº¡n" data-index="'.(!empty($l1) ? count($l1) : 0).'" type="button" onclick="return add_start_point(this);" data-target=".them_khachsan" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i></button>';
           ?>  
           
          </div>
            </div>
             
          </div>
</div></div>


          
          
        </div>
		<div class="form-group">
		<div class="col-sm-6 col-xs-12"><div class="row">
		<label for="inputLink" class="col-sm-2 control-label"><?php echo getTextTranslate(78,ADMIN_LANG);?></label>
          <div class="col-sm-12" data-toggle="tooltip" data-placement="right" title="<?php echo getTextTranslate(78,ADMIN_LANG);?>">
            <select name="filters[]" class="form-control input-sm select2" data-search="hidden" style="width: 100%" multiple="multiple" >

            <?php
            
            $l = $filtersModel->getFilters(array(
            		'code'=>'tour_group','parent_id'=>0
            ));
 
            if(!empty($l)){
              foreach($l as $k1=>$v1){
              	echo '<optgroup label="'.$v1['title'].'">';
              	$l2 = $filtersModel->getFilters(array(
              			//'code'=>'tour_group',
              			'parent_id'=>$v1['id']
              	));
              	if(!empty($l2)){
              		$des = isset($v['filters']) && !empty($v['filters']) ? $v['filters'] : array(0);
              		foreach($l2 as $k2=>$v2){
                echo '<option '.(in_array($v2['id'],$des) ? 'selected="selected"' : '').' value="'.$v2['id'].'">&nbsp;'. $v2['title'].'</option> ';
              		}
              	}
                echo '</optgroup>';
              }
            }
            ?>
            </select>
          </div>
		</div></div>
<div class="col-sm-6 col-xs-12"><div class="row">
<label for="inputLink" class="col-sm-2 control-label"><?php echo getTextTranslate(79,ADMIN_LANG);?></label>
<div class="col-sm-12" data-toggle="tooltip" data-placement="right" title="<?php echo getTextTranslate(79,ADMIN_LANG);?>">
            <select data-placeholder="<?php echo getTextTranslate(79,ADMIN_LANG);?>" name="filters[]" multiple="multiple" class="form-control input-sm select2" data-search="hidden" style="width: 100%">

            <?php
            
            $l = $filtersModel->getFilters(array(
            		'code'=>'tour_region','parent_id'=>0
            ));
 
            if(!empty($l)){
              foreach($l as $k1=>$v1){
              	echo '<optgroup label="'.$v1['title'].'">';
              	$l2 = $filtersModel->getFilters(array(
              			//'code'=>'tour_region',
              			'parent_id'=>$v1['id']
              	));
              	if(!empty($l2)){
              		//$des = isset($v['regions']) && !empty($v['regions']) ? $v['regions'] : array($v['tour_region']);
              		$des = isset($v['filters']) && !empty($v['filters']) ? $v['filters'] : array(0);
              		foreach($l2 as $k2=>$v2){
                echo '<option '.(in_array($v2['id'],$des) ? 'selected="selected"' : '').' value="'.$v2['id'].'">'. $v2['title'].'</option> ';
              		}
              	}
                echo '</optgroup>';
              }
            }
            ?>
            </select>
          </div>		
</div></div>          
          
        </div>
        <div class="form-group">
          <label for="inputLink" class="col-sm-2 control-label"><?php echo getTextTranslate(31,ADMIN_LANG);?></label>
          <div class="col-sm-12" data-placement="right" title="">
            <select data-placeholder="<?php echo getTextTranslate(31,ADMIN_LANG);?>" name="filters[]" multiple="multiple" class="form-control input-sm select2" dât-search="hidden" style="width: 100%">

            <?php
            
            $l = $filtersModel->getFilters(array(
            		'code'=>'tour_vehicle','parent_id'=>0
            )); 
            if(!empty($l)){
              foreach($l as $k1=>$v1){
              	echo '<optgroup label="'.$v1['title'].'">';
              	$l2 = $filtersModel->getFilters(array(
              			//'code'=>'tour_region',
              			'parent_id'=>$v1['id']
              	));
              	if(!empty($l2)){
              		//$des = isset($v['regions']) && !empty($v['regions']) ? $v['regions'] : array($v['tour_region']);
              		$des = isset($v['filters']) && !empty($v['filters']) ? $v['filters'] : array(0);
              		foreach($l2 as $k2=>$v2){
                echo '<option '.(in_array($v2['id'],$des) ? 'selected="selected"' : '').' value="'.$v2['id'].'">'. $v2['title'].'</option> ';
              		}
              	}
                echo '</optgroup>';
              }
            }
            ?>
            </select>
          </div>
        </div>
         
        
        
 
<?php         
echo Ad_edit_show_text_field($v,[
		'field'=>'position',
		'default_value'=>999,
		'label'=>getTextTranslate(56,ADMIN_LANG),
		'class'=>'number-format',
		'placeholder'=>'Thứ tự xắp xếp'
]);
echo Ad_edit_show_check_field([
		'field'=>'is_active',
		'value'=>isset($v['is_active']) ? $v['is_active'] : 1,
		'type'=>'time',
		'label'=>getTextTranslate(57,ADMIN_LANG),
		'active_from_date'=>isset($v['active_from_date']) ? $v['active_from_date'] : '',
		'active_to_date'=>isset($v['active_to_date']) ? $v['active_to_date'] : '',
]);

?>
         </div>
      



        </div>
    </div>
  </div>
 
  
<?php include_once(dirname(__FILE__).DRS.'../../_forms/_forms/_tab_detail_with_tab.php');?>  
<?php include_once(dirname(__FILE__).DRS.'_forms/_tab_scheduler.php');?>
<?php include_once(dirname(__FILE__).DRS.'../../_forms/_forms/_tab_seo.php');?>   
<?php include_once(dirname(__FILE__).DRS.'../../_forms/_forms/_tab_help.php');?>
<?php include_once(dirname(__FILE__).DRS.'../../_forms/_forms/_tab_filters.php');?> 
<?php include_once(dirname(__FILE__).DRS.'../../_forms/_forms/_tab_images.php');?>
<?php include_once(dirname(__FILE__).DRS.'../../_forms/_forms/_tab_options.php');?>
<?php include_once(dirname(__FILE__).DRS.'../../_forms/_forms/_tab_files_attach.php');?>
  
</div>


</form>
</div>



</div>

