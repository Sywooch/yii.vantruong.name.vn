<div class="clear"></div>



<div class="footer_bottom">
<div class="DefaultModule">
    <div class="newsletter">
<div class="newsletter-content container">
<div class="newsletter-info pr">
<div   class="bottom_email">
 
<div class=" DefaultModule EmailSubscription" style="max-width: 450px">
 <?php 
 echo '<form class="" action="" id="frmNewsLetter" data-action="sajax" method="post" onsubmit="return ajaxSubmitForm(this);" name="frmNewsLetter">  
  
<div class="fl100" >
    <div class="input-group">
 
      <input class="newsletter-email required form-control " type="email" required="" name="f[email]" value="" placeholder="Nhập email của bạn">
      <span class="input-group-btn">
        <button class="btn btn-danger newsletter-submit" type="submit"><i class="fa fa-hand-pointer-o"></i>&nbsp; Đăng ký ngay</button>
      </span>
    </div><!-- /input-group -->
  </div><!-- /.col-lg-6 -->
 
 <input type="hidden" name="_csrf-frontend" value="'.Yii::$app->request->csrfToken.'"  />
<input type="hidden" name="action" value="set_subcribes"/>  
 </form>';
 ?>            
     
</div>

</div>
<?php 
$b = Yii::$app->zii->getBox('email_subcrib');
if(!empty($b)){
	echo '<div class="newsletter-email">
<p class="newsletter-title pr"><i class="tk-i-mail"> </i> '.uh($b['title']).'</p>
</div>';
}
?>

</div>
</div>
</div>

<div style="clear:both; height:0px;"> </div>
        
</div>
</div>

<div class="Clear"></div>

<div class="bottom">
<div class="DefaultModule">
    <div class="b-footer-3__content">
<div class=" container">
<div class="b-footer-3__row-1 row">
<div class="b-footer-3__link col-md-9">
<div class="row">
<?php 
$l = \app\models\SiteMenu::getList([
	'key'=>'bottom',
]);
if(!empty($l)){
	foreach ($l as $v){
		echo '<div class="col-md-4"><p class="b-footer-3__title">'.uh($v['title']).'</p>
		<ul class="b-footer-3__link-item">';
		$l1 = \app\models\SiteMenu::getList([
				'parent_id'=>$v['id']
		]);
		if(!empty($l1)){
			foreach ($l1 as $v1){
				echo '<li><a href="'.$v1['url_link'].'" target="_blank" title="'.uh($v1['title']).'">'.uh($v1['title']).'</a></li>';
			}
		}
		echo '</ul></div>';
	}
}
?>

 
 


</div>
</div>

<div class="b-footer-3__social col-md-3 pull-right">
<div class="b-footer-3__fb"> 
<div class="fb-page" data-height="230" data-hide-cover="false" data-href="https://www.facebook.com/babymart.net.vn" data-show-facepile="true" data-show-posts="false" data-width="260"> </div>
</div>

 
</div>
 
</div>
</div>
</div>
        
</div>
</div>
    <div class="Clear"></div>
<div id="Footer"><div class="container mgt10"><div class="footer"><div class="footer_info"><?php 
$b = Yii::$app->zii->getBox('copyright');
if(!empty($b)){
	echo '<div class="company-address">' . uh($b['text'],2) . '</div>';
}
?></div></div></div><div class="Clear"></div></div>
 