<div role="tabpanel" class="tab-panel" id="tab-seo">
<?php 
$title = (isset($v['seo']['title']) && $v['seo']['title'] != "" ? $v['seo']['title'] : (isset($v['title']) ? $v['title'] : ''));
?>
        <div class="p-content">
        <div class="row">
        <div class="col-sm-12 col8respon"> 
         <div class="form-group">
          <label for="inputSeoTitle" class="col-sm-12 control-label">
          Tìm kiếm google
          </label>
          <div class="col-sm-12"><div class="seo_preview"> 
<?php
echo '<input type="hidden" name="old[url]" value="'.(isset($v['url']) ? $v['url'] : '').'">';
echo '<a href="'.(isset($v['url']) ? cu($v['url'],true) : '').'" target="_blank" class="preview-title">'.$title.'</a>';
echo '<p class="preview-url">'.(isset($v['url']) ? cu($v['url'],true) : '').'</p>';
echo '<p class="preview-description">'.(isset($v['seo']['description']) ? $v['seo']['description'] : '').'</p>';
?>				
        </div></div>
         </div>
         
         <div class="form-group">
          <label class="col-sm-12 control-label">Rel</label>
          <div class="col-sm-12">

            <select data-placeholder="Rel" data-minimum-results-for-search="Infinity" class="form-control input-sm select2" name="biz[rel]">
            <?php 
            $rels = array(
            		array('id'=>'','name'=>getTextTranslate(118,ADMIN_LANG)),
            		array('id'=>'alternate','name'=>'Alternate'),
            		array('id'=>'author','name'=>'Author'),
            		array('id'=>'nofollow','name'=>'Nofollow'),
            		array('id'=>'noreferrer','name'=>'Noreferrer'),
            		array('id'=>'bookmark','name'=>'Bookmark'),
            		array('id'=>'help','name'=>'Help'),
            		array('id'=>'license','name'=>'License'),
            		array('id'=>'tag','name'=>'Tag'),
            		array('id'=>'search','name'=>'Search'),
            		array('id'=>'next','name'=>'Next'),
            		array('id'=>'noreferrer','name'=>'Noreferrer'),
            		
            );
            foreach ($rels as $k=>$rel){
            	echo '<option '.(isset($v['rel']) && $rel['id'] == $v['rel'] ? 'selected' : '').' value="'.$rel['id'].'">&nbsp;&nbsp;&nbsp;+&nbsp;'.$rel['name'].'</option>';
            }
            ?>
            </select>

          </div>
         </div>  
         <div class="form-group">
          <label for="inputSeoTitle" class="col-sm-12 control-label"><?php echo getTextTranslate(100,ADMIN_LANG);?></label>
          <div class="col-sm-12">

            <?php
$len = mb_strlen($title); 
$du = 0; $min = 35; $max = 65;
if($len < $min){
	$cl = 'progress-bar-warning';
	$c1 = '';
}elseif($len < $max+1){
	$cl = 'progress-bar-success';
	$c1 = '';
}else{
	$cl = 'progress-bar-danger';
	$c1 = 'danger';
	$du = $len - $max;
}            
$w = $len / $max * 80; $w = $w > 100 ? 100 : $w; 
echo '<input data-min="35" data-max="65" data-role="title" onkeyup="validate_seo_preview(this);" data-target=".progress-customize-title" name="biz[seo][title]" class="form-control input-sm '.$c1.'" id="inputSeoTitle" placeholder="'.getTextTranslate(100,ADMIN_LANG).'" value="'.uh(isset($v['seo']['title']) ? $v['seo']['title'] : '').'" />';

echo '<div class="progress progress-sm progress-customize progress-customize-title">
  <div class="progress-bar '.$cl.'" role="progressbar" aria-valuenow="'.$len.'" aria-valuemin="0" aria-valuemax="65" style="width: '.$w.'%;">
    '.$len.' ký tự '.($du > 0 ? '<i>('.($du * -1).')</i>' : '').'
  </div>
</div>';            
              ?>

          </div>
         </div>
 
     
           
<?php 
echo '<label class="fl100 control-label">Url</label>';
echo '<div class="input-group mgb15">';
echo '<input data-min="0" data-max="255" data-role="url" onkeyup="validate_seo_preview(this);" data-target=".progress-customize-url" '.(isset($v['manual']) && $v['manual'] == 'on' ? '' : 'disabled').' type="text" name="f[url]" class="form-control finput-url " placeholder="Url" value="'.(isset($v['url']) ? $v['url'] : '').'" />';
echo ' <span class="input-group-addon">
<label><input onchange="enabledInput(this);" data-target=".finput-url" class="" '.(isset($v['manual']) && $v['manual'] == 'on' ? 'checked' : '').' name="biz[manual]" type="checkbox"> '.getTextTranslate(177,ADMIN_LANG).'</label>
       </span></div>   ';
?>    
<?php 
echo '<label class="fl100 control-label">Link cố định</label>';
echo '<div class="input-group mgb15">';
echo '<input data-min="0" '.(isset($v['manual_link']) && $v['manual_link'] == 'on' ? '' : 'disabled').' type="text" name="biz[url_link]" class="form-control finput-url-link " placeholder="Url link" value="'.(isset($v['url_link']) ? $v['url_link'] : '').'" />';
echo ' <span class="input-group-addon">
<label><input onchange="enabledInput(this);" data-target=".finput-url-link" class="" '.(isset($v['manual_link']) && $v['manual_link'] == 'on' ? 'checked' : '').' name="biz[manual_link]" type="checkbox"> '.getTextTranslate(177,ADMIN_LANG).'</label>
       </span></div>   ';
?>      
<div class="form-group">
          <label for="inputDescription" class="col-sm-12 control-label"><?php echo getTextTranslate(90,ADMIN_LANG);?></label>
          <div class="col-sm-12">

            <?php

              echo '<textarea data-min="120" data-max="160" data-role="description" onkeyup="validate_seo_preview(this);" data-target=".progress-customize-description" name="biz[seo][description]" class="form-control input-sm" id="inputDescription" placeholder="'.getTextTranslate(90,ADMIN_LANG).'" >'.(isset($v['seo']['description']) ? uh($v['seo']['description']) : '').'</textarea>';
              $len = mb_strlen((isset($v['seo']['description']) ? $v['seo']['description'] : ''));
              $du = 0; $min = 120; $max = 160;
              if($len < $min){
              	$cl = 'progress-bar-warning';
              	$c1 = '';
              }elseif($len < $max+1){
              	$cl = 'progress-bar-success';
              	$c1 = '';
              }else{
              	$cl = 'progress-bar-danger';
              	$c1 = 'danger';
              	$du = $len - $max;
              }           
              $w = $len / $max * 80; $w = $w > 100 ? 100 : $w;
              echo '<div class="progress progress-sm progress-customize progress-customize-description">
  <div class="progress-bar '.$cl.'" role="progressbar" aria-valuenow="'.$len.'" aria-valuemin="0" aria-valuemax="65" style="width: '.$w.'%;">
    '.$len.' ký tự '.($du > 0 ? '<i>('.($du * -1).')</i>' : '').'
  </div>
</div>';
              ?>

          </div>
         </div>
          <div class="form-group">
          <label for="inputSeoFocusKeyword" class="col-sm-12 control-label">Focus keyword</label>
          <div class="col-sm-12">
            <?php
              echo '<input data-role="tagsinput" name="biz[focus_keyword]" class="form-control input-sm tagsinput" placeholder="" id="inputSeoFocusKeyword" value="'.(isset($v['focus_keyword']) ? uh($v['focus_keyword']) : '').'" />';
            ?>
          </div>
         </div>
         <div class="form-group">
          <label for="inputSeoKeyword" class="col-sm-12 control-label"><?php echo getTextTranslate(98,ADMIN_LANG);?></label>
          <div class="col-sm-12">
            <?php
              echo '<input data-role="tagsinput" name="biz[seo][keyword]" class="form-control input-sm tagsinput" placeholder="" id="inputSeoKeyword" value="'.(isset($v['seo']['keyword']) ? uh($v['seo']['keyword']) : '').'" />';
            ?>
          </div>
         </div>

         

<div class="form-group">
          <label class="col-sm-12 control-label">Tags</label>
          <div class="col-sm-12">

            <?php

              echo '<input name="biz[tags]" class="form-control input-sm tagsinput" placeholder="" value="'.uh(isset($v['tags']) ? $v['tags'] : '').'"/>';
            ?>

          </div>
         </div>
         </div>
          

    </div></div></div>