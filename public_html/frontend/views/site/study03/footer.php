<?php 
//view(Yii::$app->controller->id);
if(Yii::$app->controller->action->id != 'testimonials'){
	$vb = Yii::$app->zii->getBoxCode('testimonials');
	//view($vb,true);
	echo '<div class="fusion-fullwidth fullwidth-box fusion-fullwidth-5 fusion-parallax-none nonhundred-percent-fullwidth" >
	
<div class="fusion-row container">
<div class="fusion-title title fusion-sep-none fusion-title-center fusion-title-size-two fusion-border-below-title">
<h2 class="title-heading-center upper mgb15" data-fontsize="24" data-lineheight="35">'.uh($vb['box']['title']).'</h2></div>
<div class="fusion-sep-clear"></div>
<div class="fusion-separator fusion-full-width-sep sep-none"></div>
<div class="fusion-blog-shortcode fusion-blog-shortcode-1 fusion-blog-archive fusion-blog-layout-grid-wrapper fusion-blog-pagination">
	
<div class="s-slick-slider slide-hoatdong" data-items="3" data-dots="true">';		
	if(!empty($vb['listItem'])){
		foreach ($vb['listItem'] as $k=>$v){
			$link = cu([DS.$v['url']]);
	
	
			echo '<div class="item" ><div class="fusion-post-wrapperx col-sm-12">';
	
			echo '<div class="fusion-layout-column fusion-spacing-yes"><div class="fusion-column-wrapper">
		<div class="fusion-testimonials clean fusion-testimonials-2" data-random="0">
		<div class="reviews"><div class="review avatar-image" style="display: block;">
		<div class="testimonial-thumbnail"><a href="'.$link.'">'.getImage([
					'src'=>$v['icon'],
					'w'=>120,'h'=>120,
					'img_attr'=>[
							'class'=>'testimonial-image',
					]
			]).'</a>
		</div>
		<blockquote style="background-color:;"><q style="background-color:;color:;">
						<span class="testi-word-enable">“'.uh($v['info']).'</span>
		<a href="'.$link.'" class="testi-more"> '.getTextTranslate(34).'</a><span class="testi-word-disable">'.uh($v['summary']).'”</span></q></blockquote>
	
						<div class="author" style="color:;"><a href="'.$link.'" class="company-name"><strong>'.uh($v['title']).'</strong>, <span>'.uh($v['short_title']).'</span></a></div></div></div></div><div class="fusion-clearfix"></div></div></div>';
			echo '</div></div><!--item-->';
	
		}
	}
	
	
	echo '</div><div class="clear"></div>
</div></div></div></div>';
}

?>


<div class="fl100 subcribes">
<div class="container">


<div class="fusion-one-full fusion-layout-column fusion-column-last fusion-spacing-yes" style="margin-top:0px;margin-bottom:5px;">
<div class="fusion-column-wrapper">
<?php 
$b = $this->app()->getBox('subcribe');
if(!empty($b)){
	echo '<div class="fusion-title title fusion-sep-none fusion-title-center fusion-title-size-two fusion-border-below-title">

<h2 class="title-heading-center upper" data-fontsize="24" data-lineheight="35"><strong>'.uh($b['title']).'</strong></h2></div>
<div class="fusion-title title fusion-sep-none fusion-title-center fusion-title-size-four fusion-border-below-title" style="margin-top:10px;margin-bottom:8px;">
<h4 class="title-heading-center" data-fontsize="14" >'.uh($b['text']).'</h4>

</div>';
}
?>





<div class="fusion-widget-area fusion-widget-area-1 fusion-content-widget-area">
<div id="newsletterwidget-2" class="fusion-footer-widget-column widget widget_newsletterwidget">
 

<div class="newsletter newsletter-widget">
 

<form class="row" action="" id="frmNewsLetter" data-action="sajax" method="post" onsubmit="return ajaxSubmitForm(this);" name="frmNewsLetter">  
<div class="container" style="max-width: 686px"><div class="row">
<div class="col-lg-12" >
    <div class="input-group">
 
      <input class="newsletter-email required form-control input-lg" type="email" required="" name="f[email]" value="" placeholder="Nhập email của bạn">
      <span class="input-group-btn">
        <button class="btn btn-danger newsletter-submit input-lg" type="submit">Đăng ký ngay</button>
      </span>
    </div><!-- /input-group -->
  </div><!-- /.col-lg-6 -->
 </div></div>
 <input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"  />
<input type="hidden" name="action" value="set_subcribes"/>  
 </form>

</div><div style="clear:both;"></div></div><div class="fusion-additional-widget-content"></div></div><div class="fusion-clearfix"></div></div></div>


</div>
</div>

<footer id="footer" class="footer_out_side fl100 f12e">
<div class="fusion-fullwidth fullwidth-box fusion-fullwidth-6  fusion-parallax-none nonhundred-percent-fullwidth" >
<div class="fusion-row container"><div class="fusion-one-full fusion-layout-column fusion-column-last fusion-spacing-yes" >
<div class="fusion-column-wrapper">

<?php 
$b = $this->app()->getBox('footer_info');
if(!empty($b)){
	echo '<div class="fusion-title title fusion-sep-none fusion-title-center fusion-title-size-two fusion-border-below-title">
<h2 class="title-heading-center upper" data-fontsize="24" data-lineheight="35"><strong><span  style="color: #ffffff;">'.uh($b['title']).'</span></strong></h2>
</div>
<div class="fusion-title title fusion-sep-none fusion-title-center fusion-title-size-four fusion-border-below-title center white " >
'.uh($b['text'],2).'

</div>';
  	//echo uh($b['text'],2);
}
?>



<div class="fusion-clearfix"></div></div>
</div>
<div class="fusion-clearfix"></div>
 
<div class="white branches">
<?php 
$b = $this->app()->getBox('footer_info1');
if(!empty($b)){
  	echo uh($b['text'],2);
}
?>
 </div>


<div class="fusion-clearfix"></div></div></div>



</footer>



<div class="fusion-fullwidth fullwidth-box fusion-fullwidth-7  fusion-parallax-none nonhundred-percent-fullwidth fl100 copyright" >                            
<div class="fusion-row container">
 
 
<div class="fusion-one-half italic fusion-column-last fusion-spacing-yes" style="margin-top:12px;margin-bottom:0px;" id="copyright">
<div class="fusion-column-wrapper pr f12e">
<?php 
$b = $this->app()->getBox('copyright');
if(!empty($b)){
  	echo uh($b['text'],2);
}
?>

<div class="fusion-social-links ps"><div class="fusion-social-networks">
<div class="fusion-social-networks-wrapper"><a class="fusion-social-network-icon fusion-tooltip fusion-facebook fusion-icon-facebook" target="_blank" href="https://www.facebook.com/tienganhlenguyet/" style="color:#FFF;" data-placement="top" data-title="Facebook" data-toggle="tooltip" title="" data-original-title="Facebook"></a>
<a class="fusion-social-network-icon fusion-tooltip fusion-youtube fusion-icon-youtube" target="_blank" href="#" style="color:#FFF;" data-placement="top" data-title="Youtube" data-toggle="tooltip" title="" data-original-title="Youtube"></a>
<a class="fusion-social-network-icon fusion-tooltip fusion-googleplus fusion-icon-googleplus" target="_blank" href="#" style="color:#FFF;" data-placement="top" data-title="Google+" data-toggle="tooltip" title="" data-original-title="Google+"></a></div></div></div>
<div class="fusion-clearfix"></div></div></div>
<div class="fusion-clearfix"></div></div></div> 
  

<?php 
 
$this->registerJsFile(__LIBS_DIR__ . '/menu/superfish-1.7.4/src/js/hoverIntent.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/menu/superfish-1.7.4/src/js/superfish.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/themes/js/jquery-ui.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
 
$this->registerJsFile(__LIBS_DIR__ . '/bootstrap/assets/js/bootstrap-tooltip.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/bootstrap/assets/js/moment.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/bootstrap/assets/js/bootstrap-datetimepicker.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/bootstrap/colorpicker/dist/js/bootstrap-colorpicker.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/bootstrap/3.2.0/js/ie10-viewport-bug-workaround.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/lazyload.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/modernizr.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/slider/slick-master/slick/slick.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/popup/colorbox/jquery.colorbox.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/lazyloadxt/lazy.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/jquery.themepunch.tools.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/jquery.themepunch.revolution.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('http://www.skypeassets.com/i/scom/js/skype-uri.js?ver=4.4.8', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/main1.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/main.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

?> 
<div id="fb-root"></div>