<div class="table-responsive ">

<div class="fl100 edit-form">
 <form id="editFormContent" method="post" class="form-horizontal edit-form-left" role="form" enctype="multipart/form-data">
 <input type="hidden" class="currentTab" name="currentTab" value="" />
 <input type="hidden" class="btnSubmit" name="btnSubmit" value="1" />
 <input type="hidden" name="formSubmit" value="true"  />
 <input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"/>

    <!-- Nav tabs -->
<ul class="nav form-edit-tab nav-tabs" role="tablist">

  <li role="presentation" class="active"><a href="#tab-seo" role="tab" data-toggle="tab">Cấu hình chung</a></li>
  <li role="presentation" class=""><a href="#tab-help" role="tab" data-toggle="tab">Hướng dẫn</a></li>

</ul>

<!-- Tab panes -->
<div class="tab-content edit-form-content-tab">

  <div role="tabpanel" class="tab-panel active" id="tab-seo">

        <div class="p-content">
        <div class="row">
        <div class="col-sm-12"> 
<div class="block-example">
         <span class="f12e block-title">Thông tin chung</span>
         
         <div class="form-group">
          <label class="col-sm-12 control-label">Tên website</label>
          <div class="col-sm-12">

<?php

echo '<input type="text" name="f[site_name]" class="form-control" placeholder="Tên website" value="'.(isset($v['site_name']) ? $v['site_name'] : '').'"/>';
?>

          </div>
         </div>
         
         
<div class="form-group">
          <label  class="col-sm-12 control-label">Domain chính</label>
          <div class="col-sm-12">
 
            <?php
              echo '<input data-delimiter="," data-action="load_domains" name="f[domain]" class="form-control autocomplete tagsinput"  placeholder="Domain" value="'.(isset($v['domain']) ? $v['domain'] : '').'" />';
            ?>

          </div>
         </div>   
<div class="form-group">
          <label for="inputSeoTitle" class="col-sm-12 control-label">Tiêu đề trang</label>
          <div class="col-sm-12">

<?php
$len = mb_strlen((isset($v['title']) ? $v['title'] : ''));
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
echo '<input data-min="35" data-max="65" data-role="title" onkeyup="validate_seo_preview(this);" data-target=".progress-customize-title" name="f[title]" class="form-control '.$c1.'" id="inputSeoTitle" placeholder="Tiêu đề trang" value="'.(isset($v['title']) ? $v['title'] : '').'" />';

echo '<div class="progress progress-sm progress-customize progress-customize-title">
  <div class="progress-bar '.$cl.'" role="progressbar" aria-valuenow="'.$len.'" aria-valuemin="0" aria-valuemax="65" style="width: '.$w.'%;">
    '.$len.' ký tự '.($du > 0 ? '<i>('.($du * -1).')</i>' : '').'
  </div>
</div>';
?>

          </div>
         </div>
<div class="form-group">
          <label for="inputSeoKeyword" class="col-sm-12 control-label">Từ khóa</label>
          <div class="col-sm-12">

<?php

echo '<input type="text" name="f[keyword]" class="form-control tagsinput '.$c1.'" id="inputSeoKeyword" placeholder="Từ khóa cách nhau bởi dấu phẩy (,)" value="'.(isset($v['keyword']) ? $v['keyword'] : '').'"/>';
?>

          </div>
         </div>         
<div class="form-group">
          <label for="inputDescription" class="col-sm-12 control-label">Mô tả</label>
          <div class="col-sm-12">

<?php

$len = mb_strlen((isset($v['description']) ? $v['description'] : ''));
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
echo '<textarea data-min="'.$min.'" data-max="'.$max.'" data-role="description" onkeyup="validate_seo_preview(this);" data-target=".progress-customize-description" resize="none" name="f[description]" class="form-control input_codemirror height-auto" id="inputDescription" placeholder="Mô tả" >'.(isset($v['description']) ? $v['description'] : '').'</textarea>';
echo '<div class="progress progress-sm progress-customize progress-customize-description">
  <div class="progress-bar '.$cl.'" role="progressbar" aria-valuenow="'.$len.'" aria-valuemin="0" aria-valuemax="65" style="width: '.$w.'%;">
    '.$len.' ký tự '.($du > 0 ? '<i>('.($du * -1).')</i>' : '').'
  </div>
</div>';

?>

          </div>
         </div>

         <div class="form-group">
          <label for="inputmeta" class="col-sm-12 control-label">Thẻ meta</label>
          <div class="col-sm-12">

            <?php

              echo '<textarea name="f[meta]" class="form-control input_codemirror height-auto" id="inputmeta" placeholder="meta" >'.(isset($v['meta']) ? uh($v['meta'],2) : '').'</textarea>';
            ?>

          </div>
         </div>         
               
<div class="form-group">
          <label for="inputmeta" class="col-sm-12 control-label">Url</label>
          <div class="col-sm-12">
<?php 
$type = isset($v['url_config']['type']) ? $v['url_config']['type'] : 2; 
?>          
<p><label data-toggle="tooltip" data-placement="top" title="Url với 1 link bài viết hoặc danh mục)"><input <?php echo $type == 2 ? 'checked' : '';?> name="f[url_config][type]" type="radio" value="2"> Url ngắn (1 cấp) - <span class="italic f11px"><?php echo cu(['/'],true) .'<b class="red">link-bai-viet</b>';?></span></label></p>
<p><label data-toggle="tooltip" data-placement="top" title="Bạn có thể đặt url tối đa 2 cấp (1 cấp cha (danh mục) + 1 link bài viết)"><input <?php echo $type == 3 ? 'checked' : '';?> name="f[url_config][type]" type="radio" value="3"> Url với 1 cấp cha - <span class="italic f11px"><?php echo cu(['/'],true) .'<b class="red">danh-muc/link-bai-viet</b>';?></span></label></p>
<p><label data-toggle="tooltip" data-placement="top" title="Bạn có thể đặt url tối đa 4 cấp (3 cấp cha (danh mục) + 1 link bài viết)"><input <?php echo $type == 1 ? 'checked' : '';?> name="f[url_config][type]" type="radio" value="1"> Url đầy đủ - <span class="italic f11px"><?php echo cu(['/'],true) .'<b class="red">danh-muc-cap-1/danh-muc-cap-2/link-bai-viet</b>';?></span></label></p>             
<p><label><input name="f[url_config][suffix]" type="text" value="<?php echo isset($v['url_config']['suffix']) ? $v['url_config']['suffix'] : '';?>">  - Đuôi mở rộng <span class="italic f11px">(.html, .ln, ... ** Lưu ý: Bắt buộc phải bắt đầu bằng dấu chấm) </span></label></p>             

          </div>
         </div>   
         
         <div class="form-group">
          <label for="inputmeta" class="col-sm-12 control-label">Heading 1</label>
          <div class="col-sm-12">
<?php 
$heading1 = isset($v['heading1']) ? $v['heading1'] : 0; 
echo '<input type="hidden" name="f[real_title]" value="'.(isset($v['title']) ? $v['title'] : '').'"/>';
?>          
<p><label data-toggle="tooltip" data-placement="top" title="Thẻ H1 sẽ được chèn thủ công vào từng vị trí theo ý đồ của người làm SEO"><input <?php echo $heading1== 0 ? 'checked' : '';?> name="f[heading1]" type="radio" value="0"> Mặc định - <span class="italic f11px">Tùy chỉnh theo ý đồ của người làm SEO</span></label></p>
<p><label data-toggle="tooltip" data-placement="top" title="Thẻ H1 sẽ được lấy tự động theo tiêu đề trang"><input <?php echo $heading1== 1 ? 'checked' : '';?> name="f[heading1]" type="radio" value="1"> Tự động - <span class="italic f11px">Lấy tự động theo tiêu đề trang</span></label></p>
<p><label data-toggle="tooltip" data-placement="top" title="Thẻ H1 đặt cố định theo tiêu đề trang đã điền ở ô bên trên"><input <?php echo $heading1== 2 ? 'checked' : '';?> name="f[heading1]" type="radio" value="2"> Cố định - <span class="italic f11px red font-normal"><q><?php echo '“' . (isset($v['title']) ? $v['title'] : '') . '”';?></q></span></label></p>
          </div>
         </div>           
</div>         
<div class="block-example">
<span class="f12e block-title">Google Analystics</span>
<div class="w100">

            <?php

              echo '<textarea name="f[googleanalystics]" class="form-control input-seo input_codemirror" id="inputgoogleanalystics" placeholder="Google Analystics" >'.(isset($v['googleanalystics']) ? uh($v['googleanalystics'],2) : '').'</textarea>';
            ?>

          </div>
</div>                  
<div class="block-example">
<span class="f12e block-title">Robots</span>
<?php echo '<textarea name="f[robots]" class="form-control input_codemirror height-auto" rows="5" id="inpuRobots" placeholder="Robost.txt" >'.(isset($v['robots']) && $v['robots'] != "" ? uh($v['robots'],2) : 'User-agent: *
Disallow: /cgi-bin/
Disallow: /tmp/
Disallow: /system/
Disallow: /admin/
Disallow: /themes/admin/').'</textarea>';?>
</div>
<div class="block-example">
<span class="f12e block-title">Sitemap</span>
 
<div class="form-inline fl100" style="margin-bottom: 15px">
  <div class="form-group"><div class="col-sm-12 " style="margin-right: 30px">
    <label>Change frequency</label>
    <select id="sitemap_freq" name="freq" class="form-control">

<option value="" selected="">None</option>

<option value="always">Always</option>

<option value="hourly">Hourly</option>

<option value="daily">Daily</option>

<option value="weekly">Weekly</option>

<option value="monthly">Monthly</option>

<option value="yearly">Yearly</option>

<option value="never">Never</option>

</select>
  </div></div>
  <div class="form-group"><div class="col-sm-12" style="margin-right: 30px">
    <label>Last modification</label>
    <input id="sitemap_last_mod" type="text" class="form-control" name="last_mod" value="" placeholder="<?php echo date("Y-m-d H:i:s");?>">
  </div></div>
  
  <div class="form-group"><div class="col-sm-12">
    <label>Priority:&nbsp; </label>
    <label class="font-normal "  style="margin-right: 30px">None 
    <input value="none" class="sitemap_priority" checked type="radio" name="priority"/></label>
    <label class="font-normal "  style="margin-right: 30px"> Automatically Calculated Priority  
    <input value="auto" class="sitemap_priority" type="radio" name="priority"/></label>
    
  </div></div>
  <button type="button" onclick="generateSitemap();" class="btn btn-success"><i class="glyphicon glyphicon-hand-right"></i> Tạo sitemap</button>
</div>
 
<?php echo '<textarea name="f[sitemap]" class="form-control input-seo input_codemirror" id="inpuSitemap" placeholder="sitemap.xml" >'.(isset($v['sitemap']) ? uh($v['sitemap'],2) : '').'</textarea>';?>
</div>
         
 

         </div>
 



    </div></div></div>

  <div role="tabpanel" class="tab-panel" id="tab-help">
    <div class="fl100">
        <div class="p-content">
           Đang cập nhật !
        </div>
    </div>
  </div>

</div>


</form>
</div>



</div>

