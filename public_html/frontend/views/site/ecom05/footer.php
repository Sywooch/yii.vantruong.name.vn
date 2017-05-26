 <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-start-slideshow="true" data-filter=":even">
        <div class="slides"></div>
        <h3 class="title">&nbsp;</h3>
        <a class="prev"></a>
        <a class="next"></a>
        <a class="close"></a>
        <a class="play-pause"></a>
        <ol class="indicator"></ol>
    </div>
            
            <div class="footer-wrapper ">

                
                    
<div id="footer" class="footer-1">
            <div class="container">
            
                            <div class="row">
<?php 
$b = Yii::$app->zii->getBox('footer_col1');
if(!empty($b)){
	echo '<div class="col-sm-12 col-md-3 col-sm-6">
	<aside id="contact-info-widget-2" class="widget contact-info"><h3 class="widget-title upper"> '.uh($b['title']).'</h3>        
	<div class="contact-info">
                      '.uh($b['text'],2).'
                    </div>

        </aside></div>';
}
$b = Yii::$app->zii->getBox('footer_col2');
if(!empty($b)){
	echo '<div class="col-sm-12 col-md-3 col-sm-6">
	<aside id="nav_menu-2" class="widget widget_nav_menu"><h3 class="widget-title upper"> '.uh($b['title']).'</h3><div class="menu-huong-dan-container">';
	echo Yii::$app->zii-> getMenuItem([
			//'listItem'=> $reg->f->__get_bottom_nav(),
			//'hTag'=>array('span'),
			'key'=>'bottom',
			'maxLevel'=>1,
			'attribute'=>array( 'class'=>'menu','id'=>'menu-huong-dan'),
			'li1Class'=>'menu-item menu-item-type-post_type menu-item-object-page '
			]);
	
 
			
    echo '</div></aside></div>';
}
$b = Yii::$app->zii->getBoxCode('footer_col3');
if(!empty($b['listItem'])){
	echo '<div class="col-sm-12 col-md-3 col-sm-6">
	<aside id="nav_menu-2" class="widget widget-recent-posts"><h3 class="widget-title upper"> '.uh($b['box']['title']).'</h3><div class="row"><div class="post-slide">';
	if(!empty($b['listItem'])) 
	{
		foreach ($b['listItem'] as $k=>$v){
			echo '<div class="post-item-small">
    <a target="'.$v['target'].'" href="'.$v['url_link'].'">'.uh($v['title']).'</a>
    <span class="post-date" style="">'.date('d/m/Y',strtotime($v['time'])).'</span>
</div>';
		}
	}
	
	
	echo '</div></div></aside></div>';
}

?>                            

                                                     
                                                     
                                                    <div class="col-sm-12 col-md-3">
                                <aside id="text-2" class="widget widget_text">			<div class="textwidget"> 
 <div class="fb-page" data-href="https://www.facebook.com/apluscosmetics?fref=ts" data-width="270" data-small-header="false" data-adapt-container-width="false" data-hide-cover="false" data-show-facepile="false" data-show-posts="false"></div></div>
		</aside><aside id="follow-us-widget-2" class="widget follow-us">        <div class="share-links">
                        <a href="#" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Youtube" class="share-youtube">Youtube</a><a href="#" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Instagram" class="share-instagram">Instagram</a><a href="#" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Skype" class="share-skype">Skype</a><a href="#" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Google +" class="share-googleplus">Google +</a>                    </div>

        </aside>                            </div>
                                        </div>
            
        </div>
    
        <div class="footer-bottom">
        <div class="container">
            <div class="footer-left">
                                    <span class="logo">
                        <a href="http://myphamaplus.com/" title="Mỹ phẩm A&amp;PLUS - Nhà phân phối độc quyền sản phẩm A&amp;PLUS tại Việt Nam. Dòng sản phẩm dưỡng da an toàn từ thiên nhiên" rel="home">
                            <img class="img-responsive" src="//myphamaplus.com/wp-content/uploads/2015/09/logo-21.png" alt="Mỹ phẩm A&amp;PLUS" />                        </a>
                    </span>
                                <aside id="text-3" class="widget widget_text">			<div class="textwidget">GPĐKKD số 0302351212 do SKH đầu tư TP.HCM - Cấp ngày 04/07/2001</div>
		</aside>            </div>

            
                            <div class="footer-right">
                    © Copyright 2015. Xây dưng bởi A&PLUS                </div>
                    </div>
    </div>
    </div>
                
            </div>
            
            
            
            <div class="panel-overlay"></div>
<div class="filter-overlay"></div>

<div id="nav-panel" class="">
    <div class="menu-wrap"><ul id="menu-home-1" class="mobile-menu accordion-menu"><li id="accordion-menu-item-4032" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-4028 current_page_item active"><a href="http://myphamaplus.com/" class=" current ">Trang chủ</a></li>
<li id="accordion-menu-item-3914" class="custom-menu menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children  has-sub"><a href="http://myphamaplus.com/shop" class="">Sản phẩm</a>
<span class="arrow"></span><ul class="sub-menu">
	<li id="accordion-menu-item-4177" class="menu-item menu-item-type-taxonomy menu-item-object-product_cat "><a href="http://myphamaplus.com/dm/cham-soc-lam-sach-da" class=""><i class="fa fa-dot-circle-o"></i>Chăm sóc làm sạch da</a></li>
	<li id="accordion-menu-item-4186" class="menu-item menu-item-type-taxonomy menu-item-object-product_cat "><a href="http://myphamaplus.com/dm/duong-da-ngay-va-dem" class=""><i class="fa fa-dot-circle-o"></i>Dưỡng da ngày và đêm</a></li>
	<li id="accordion-menu-item-4183" class="menu-item menu-item-type-taxonomy menu-item-object-product_cat "><a href="http://myphamaplus.com/dm/cham-soc-da-mun" class=""><i class="fa fa-dot-circle-o"></i>Chăm sóc da mụn</a></li>
	<li id="accordion-menu-item-4184" class="menu-item menu-item-type-taxonomy menu-item-object-product_cat "><a href="http://myphamaplus.com/dm/cham-soc-da-sam-nam" class=""><i class="fa fa-dot-circle-o"></i>Chăm sóc da sạm nám</a></li>
	<li id="accordion-menu-item-4194" class="menu-item menu-item-type-taxonomy menu-item-object-product_cat "><a href="http://myphamaplus.com/dm/cham-soc-da-lao-hoa" class=""><i class="fa fa-dot-circle-o"></i>Chăm sóc da lão hoá</a></li>
	<li id="accordion-menu-item-4198" class="menu-item menu-item-type-taxonomy menu-item-object-product_cat "><a href="http://myphamaplus.com/dm/cung-cap-duong-chat" class=""><i class="fa fa-dot-circle-o"></i>Cung cấp dưỡng chất</a></li>
	<li id="accordion-menu-item-4200" class="menu-item menu-item-type-taxonomy menu-item-object-product_cat "><a href="http://myphamaplus.com/dm/trang-diem-chong-nang" class=""><i class="fa fa-dot-circle-o"></i>Trang điểm &#8211; chống nắng</a></li>
	<li id="accordion-menu-item-4193" class="menu-item menu-item-type-taxonomy menu-item-object-product_cat "><a href="http://myphamaplus.com/dm/bo-chuyen-nghiep" class=""><i class="fa fa-dot-circle-o"></i>Bộ chuyên nghiệp</a></li>
</ul>
</li>
<li id="accordion-menu-item-4561" class="menu-item menu-item-type-post_type menu-item-object-page "><a href="http://myphamaplus.com/giay-phep" class="">Giấy phép</a></li>
<li id="accordion-menu-item-4106" class="menu-item menu-item-type-custom menu-item-object-custom "><a href="/hoi-dap/" class="">Hỏi đáp</a></li>
<li id="accordion-menu-item-4117" class="custom-menu menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children  has-sub"><a href="http://myphamaplus.com/blog" class="">Tin tức</a>
<span class="arrow"></span><ul class="sub-menu">
	<li id="accordion-menu-item-4187" class="menu-item menu-item-type-taxonomy menu-item-object-category "><a href="http://myphamaplus.com/category/tin-tuc/ho-tro-mo-tiem" class=""><i class="fa fa-newspaper-o"></i>HỖ TRỢ MỞ TIỆM</a></li>
	<li id="accordion-menu-item-4188" class="menu-item menu-item-type-taxonomy menu-item-object-category "><a href="http://myphamaplus.com/category/tin-tuc/dao-tao-day-nghe" class=""><i class="fa fa-newspaper-o"></i>ĐÀO TẠO-DẠY NGHỀ</a></li>
	<li id="accordion-menu-item-4189" class="menu-item menu-item-type-taxonomy menu-item-object-category "><a href="http://myphamaplus.com/category/tin-tuc/chuyen-muc-lam-dep" class=""><i class="fa fa-newspaper-o"></i>CHUYÊN MỤC LÀM ĐẸP</a></li>
	<li id="accordion-menu-item-4190" class="menu-item menu-item-type-taxonomy menu-item-object-category "><a href="http://myphamaplus.com/category/tin-tuc/goc-a-plus" class=""><i class="fa fa-newspaper-o"></i>Góc A&#038;PLUS</a></li>
</ul>
</li>
<li class="menu-item"><a href="http://myphamaplus.com/my-account"><i class="fa fa-user"></i>Đăng nhập</a></li><li class="menu-item"><a href="http://myphamaplus.com/my-account"><i class="fa fa-user-plus"></i>Đăng ký</a></li></ul></div><div class="menu-wrap"><ul id="menu-top-navigation" class="top-links accordion-menu show-arrow"><li id="accordion-menu-item-3767" class="menu-item menu-item-type-post_type menu-item-object-page "><a href="http://myphamaplus.com/my-account" class="">My Account</a></li>
<li id="accordion-menu-item-3603" class="menu-item menu-item-type-post_type menu-item-object-page "><a href="http://myphamaplus.com/my-account" class="">My Account</a></li>
<li id="accordion-menu-item-3367" class="menu-item menu-item-type-post_type menu-item-object-page "><a href="http://myphamaplus.com/my-account" class="">My Account</a></li>
<li id="accordion-menu-item-3726" class="menu-item menu-item-type-custom menu-item-object-custom "><a href="#" class="">My Wishlist</a></li>
<li id="accordion-menu-item-3562" class="menu-item menu-item-type-custom menu-item-object-custom "><a href="#" class="">My Wishlist</a></li>
<li id="accordion-menu-item-3323" class="menu-item menu-item-type-custom menu-item-object-custom "><a href="#" class="">My Wishlist</a></li>
<li id="accordion-menu-item-3771" class="menu-item menu-item-type-post_type menu-item-object-page "><a href="http://myphamaplus.com/khuyen-mai" class="">Khuyến mãi</a></li>
<li id="accordion-menu-item-3371" class="menu-item menu-item-type-post_type menu-item-object-page "><a href="http://myphamaplus.com/khuyen-mai" class="">Khuyến mãi</a></li>
<li id="accordion-menu-item-3607" class="menu-item menu-item-type-post_type menu-item-object-page "><a href="http://myphamaplus.com/khuyen-mai" class="">Khuyến mãi</a></li>
</ul></div><div class="switcher-wrap"><ul id="menu-currency-switcher" class="currency-switcher accordion-menu show-arrow"><li id="accordion-menu-item-3711" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children  has-sub"><a href="#" class="">GBP</a>
<span class="arrow"></span><ul class="sub-menu">
	<li id="accordion-menu-item-3709" class="menu-item menu-item-type-custom menu-item-object-custom "><a href="#" class="">USD</a></li>
	<li id="accordion-menu-item-3710" class="menu-item menu-item-type-custom menu-item-object-custom "><a href="#" class="">EUR</a></li>
</ul>
</li>
<li id="accordion-menu-item-3547" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children  has-sub"><a href="#" class="">GBP</a>
<span class="arrow"></span><ul class="sub-menu">
	<li id="accordion-menu-item-3545" class="menu-item menu-item-type-custom menu-item-object-custom "><a href="#" class="">USD</a></li>
	<li id="accordion-menu-item-3546" class="menu-item menu-item-type-custom menu-item-object-custom "><a href="#" class="">EUR</a></li>
</ul>
</li>
<li id="accordion-menu-item-3308" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children  has-sub"><a href="#" class="">GBP</a>
<span class="arrow"></span><ul class="sub-menu">
	<li id="accordion-menu-item-3306" class="menu-item menu-item-type-custom menu-item-object-custom "><a href="#" class="">USD</a></li>
	<li id="accordion-menu-item-3307" class="menu-item menu-item-type-custom menu-item-object-custom "><a href="#" class="">EUR</a></li>
</ul>
</li>
</ul></div></div>
            
            
<script type='text/javascript'>
/* <![CDATA[ */
var _wpcf7 = {"recaptcha":{"messages":{"empty":"Please verify that you are not a robot."}},"cached":"1"};
/* ]]> */
</script>
<script type='text/javascript'>
/* <![CDATA[ */
var woocommerce_params = {"ajax_url":"\/ajax","wc_ajax_url":"\/?wc-ajax=%%endpoint%%"};
/* ]]> */
</script>
<script type='text/javascript'>
/* <![CDATA[ */
var wc_cart_fragments_params = {"ajax_url":"\/ajax","wc_ajax_url":"\/?wc-ajax=%%endpoint%%","fragment_name":"wc_fragments"};
/* ]]> */
</script>
<script type='text/javascript'>
/* <![CDATA[ */
var js_porto_vars = {"rtl":"","ajax_url":"\/ajax","post_zoom":"0","portfolio_zoom":"1","member_zoom":"1","page_zoom":"1","container_width":"1280","grid_gutter_width":"20","show_sticky_header":"1","show_sticky_header_tablet":"1","show_sticky_header_mobile":"1","request_error":"The requested content cannot be loaded.<br\/>Please try again later.","ajax_loader_url":"\/themes\/ecom05\/images\/ajax-loader@2x.gif"};
/* ]]> */
</script> 
<?php 
/*  
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

*/
$this->registerJsFile(__RSDIR__ . '/js/jquery-migrate.min.js?ver=1.4.1', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/jquery.themepunch.tools.min.js?ver=4.6.93', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/jquery.themepunch.revolution.min.js?ver=4.6.93', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile(__RSDIR__ . '/js/frontend.js?ver=1.2', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/ultimate-params.min.js?ver=3.13.4', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/jquery-appear.min.js?ver=3.13.4', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/custom.min.js?ver=3.13.4', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile(__RSDIR__ . '/js/plugins.min.js?ver=2.5.1', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile(__RSDIR__ . '/js/jquery.blueimp-gallery.min.js?ver=2.5.1', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/masterslider.min.js?ver=4.7.2', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/jquery.form.min.js?ver=3.51.0-2014.06.20', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/scripts.js?ver=4.6.1', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/jquery.blockUI.min.js?ver=2.70', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/wpmu-ui.3.min.js?ver=4.7.2', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/jquery.cookie.min.js?ver=1.4.1', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/public.min.js?ver=4.7.2', ['depends' => [\yii\web\JqueryAsset::className()]]);

  
$this->registerJsFile(__RSDIR__ . '/js/theme.min.js?ver=2.5.1', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/js_composer_front.js?ver=4.7', ['depends' => [\yii\web\JqueryAsset::className()]]);


$this->registerJs ('var setREVStartSize = function() {
					var	tpopt = new Object();
						tpopt.startwidth = 1400;
						tpopt.startheight = 500;
						tpopt.container = jQuery(\'#rev_slider_2_1\');
						tpopt.fullScreen = "off";
						tpopt.forceFullWidth="off";

					tpopt.container.closest(".rev_slider_wrapper").css({height:tpopt.container.height()});tpopt.width=parseInt(tpopt.container.width(),0);tpopt.height=parseInt(tpopt.container.height(),0);tpopt.bw=tpopt.width/tpopt.startwidth;tpopt.bh=tpopt.height/tpopt.startheight;if(tpopt.bh>tpopt.bw)tpopt.bh=tpopt.bw;if(tpopt.bh<tpopt.bw)tpopt.bw=tpopt.bh;if(tpopt.bw<tpopt.bh)tpopt.bh=tpopt.bw;if(tpopt.bh>1){tpopt.bw=1;tpopt.bh=1}if(tpopt.bw>1){tpopt.bw=1;tpopt.bh=1}tpopt.height=Math.round(tpopt.startheight*(tpopt.width/tpopt.startwidth));if(tpopt.height>tpopt.startheight&&tpopt.autoHeight!="on")tpopt.height=tpopt.startheight;if(tpopt.fullScreen=="on"){tpopt.height=tpopt.bw*tpopt.startheight;var cow=tpopt.container.parent().width();var coh=jQuery(window).height();if(tpopt.fullScreenOffsetContainer!=undefined){try{var offcontainers=tpopt.fullScreenOffsetContainer.split(",");jQuery.each(offcontainers,function(e,t){coh=coh-jQuery(t).outerHeight(true);if(coh<tpopt.minFullScreenHeight)coh=tpopt.minFullScreenHeight})}catch(e){}}tpopt.container.parent().height(coh);tpopt.container.height(coh);tpopt.container.closest(".rev_slider_wrapper").height(coh);tpopt.container.closest(".forcefullwidth_wrapper_tp_banner").find(".tp-fullwidth-forcer").height(coh);tpopt.container.css({height:"100%"});tpopt.height=coh;}else{tpopt.container.height(tpopt.height);tpopt.container.closest(".rev_slider_wrapper").height(tpopt.height);tpopt.container.closest(".forcefullwidth_wrapper_tp_banner").find(".tp-fullwidth-forcer").height(tpopt.height);}
				};

				/* CALL PLACEHOLDER */
				setREVStartSize();


				var tpj=jQuery;
				tpj.noConflict();
				var revapi2;

				tpj(document).ready(function() {

				if(tpj(\'#rev_slider_2_1\').revolution == undefined){
					revslider_showDoubleJqueryError(\'#rev_slider_2_1\');
				}else{
				   revapi2 = tpj(\'#rev_slider_2_1\').show().revolution(
					{	
												dottedOverlay:"none",
						delay:9000,
						startwidth:1400,
						startheight:500,
						hideThumbs:200,

						thumbWidth:100,
						thumbHeight:50,
						thumbAmount:2,
						
												
						simplifyAll:"off",

						navigationType:"bullet",
						navigationArrows:"solo",
						navigationStyle:"round",

						touchenabled:"on",
						onHoverStop:"on",
						nextSlideOnWindowFocus:"off",

						swipe_threshold: 75,
						swipe_min_touches: 1,
						drag_block_vertical: false,
						
												
												
						keyboardNavigation:"off",

						navigationHAlign:"center",
						navigationVAlign:"bottom",
						navigationHOffset:0,
						navigationVOffset:20,

						soloArrowLeftHalign:"left",
						soloArrowLeftValign:"center",
						soloArrowLeftHOffset:20,
						soloArrowLeftVOffset:0,

						soloArrowRightHalign:"right",
						soloArrowRightValign:"center",
						soloArrowRightHOffset:20,
						soloArrowRightVOffset:0,

						shadow:0,
						fullWidth:"on",
						fullScreen:"off",

												spinner:"spinner0",
												
						stopLoop:"off",
						stopAfterLoops:-1,
						stopAtSlide:-1,

						shuffle:"off",

						autoHeight:"off",
						forceFullWidth:"off",
						
						
						
						hideThumbsOnMobile:"off",
						hideNavDelayOnMobile:1500,
						hideBulletsOnMobile:"off",
						hideArrowsOnMobile:"off",
						hideThumbsUnderResolution:0,

												hideSliderAtLimit:0,
						hideCaptionAtLimit:0,
						hideAllCaptionAtLilmit:0,
						startWithSlide:0					});



									}
				});');

//
//$this->registerJsFile(__RSDIR__ . '/js/main.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

?>