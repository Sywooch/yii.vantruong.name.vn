<?php 
 
$this->registerCssFile(__LIBS_DIR__ . '/themes/css/animate.css');
$this->registerCssFile(__LIBS_DIR__ . '/font-awesome/css/font-awesome.min.css');
//$this->registerCssFile(__LIBS_DIR__ . '/themes/fontello/css/animation.css');
$this->registerCssFile(__LIBS_DIR__ . '/popup/colorbox/colorbox.css');
$this->registerCssFile(__LIBS_DIR__ . '/menu/superfish-1.7.4/src/css/superfish.css');
$this->registerCssFile(__LIBS_DIR__ . '/slider/slick/slick.css');
$this->registerCssFile(__LIBS_DIR__ . '/slider/slick/slick-theme.css');
$this->registerCssFile(__RSDIR__ . '/css/validationEngine.jquery.css?ver=2.7.7');
$this->registerCssFile(__RSDIR__ . '/css/Defaults.css?ver=4.7.2');
$this->registerCssFile(__RSDIR__ . '/css/styles.css?ver=4.6.1');
$this->registerCssFile(__RSDIR__ . '/css/settings.css?ver=4.6.93');
$this->registerCssFile(__RSDIR__ . '/css/yith_wcas_ajax_search.css?ver=4.7.2');
$this->registerCssFile(__RSDIR__ . '/css/js_composer.css?ver=4.7');
$this->registerCssFile(__RSDIR__ . '/css/ultimate.min.css?ver=3.13.4');


$this->registerCssFile(__RSDIR__ . '/css/wpmu-ui.3.min.css?ver=4.7.2');
$this->registerCssFile(__RSDIR__ . '/css/animate.3.min.css?ver=4.7.2');
$this->registerCssFile(__RSDIR__ . '/css/plugins_1.css?ver=4.7.2');
$this->registerCssFile(__RSDIR__ . '/css/theme_1.css?ver=4.7.2');
$this->registerCssFile(__RSDIR__ . '/css/theme_shop_1.css?ver=4.7.2');
$this->registerCssFile(__RSDIR__ . '/css/masterslider.main.css?ver=4.7.2');
$this->registerCssFile(__RSDIR__ . '/css/skin_1.css?ver=4.7.2');
 
?>

  
 

<div class="header-wrapper fixed-header clearfix"><!-- header wrapper -->
                                
                    <header id="header" class="header-corporate header-10 ">
    
    <div class="header-main">
        <div class="container">
            <div class="header-left">
                                <div class="logo">                    <a href="http://myphamaplus.com/" title="Mỹ phẩm A&amp;PLUS - Nhà phân phối độc quyền sản phẩm A&amp;PLUS tại Việt Nam. Dòng sản phẩm dưỡng da an toàn từ thiên nhiên" rel="home">
                        <img class="img-responsive" src="//myphamaplus.com/wp-content/uploads/2015/09/logo-21.png" alt="Mỹ phẩm A&amp;PLUS" />                    </a>
                </div>            </div>

            <div class="header-right">
                <div class="header-right-top">
                    <div class="header-contact"><i class="fa fa-phone"></i> <strong> 0902 667 038</strong><span class="gap">|   <a href="http://myphamaplus.com/blog/" class="">Khuyến mãi</a></span></div>    <div class="searchform-popup">
        <a class="search-toggle"><i class="fa fa-search"></i></a>
        
<form role="search" method="get" id="yith-ajaxsearchform" action="http://myphamaplus.com/" class="yith-ajaxsearchform-container searchform ">
    <fieldset>
        <span class="text"><input name="s" id="yith-s" type="text" value="" placeholder="Search&hellip;" /></span>
                <span class="button-wrap"><button class="btn" id="yith-searchsubmit" title="Search" type="submit"><i class="fa fa-search"></i></button></span>
        <input type="hidden" name="post_type" value="product" />
    </fieldset>
</form>
  </div>
                        <a class="mobile-toggle"><i class="fa fa-reorder"></i></a>
                </div>
                <div class="header-right-bottom">
                    <div id="main-menu">
<?php 
echo Yii::$app->zii-> getMenuItem([
		//'listItem'=> $reg->f->__get_bottom_nav(),
		//'hTag'=>array('span'),
		'key'=>'main',
				//'maxLevel'=>1,
		'attribute'=>array( 'class'=>'main-menu mega-menu show-arrow','id'=>'menu-home'),
				//'firstItemClass'=>'first-item',
		//'lastItemClass'=>'last-item',
		//'firstItem'=>'<li class="li-child li-child-0 li-level-1 "><a href="./" class=""><i class="fa fa-home"></i>&nbsp;</a></li>',
		//'ul2Attr'=>['class'=>'sub-menu'],
		//'li2Class'=>'menu-item menu-item-type-taxonomy menu-item-object-category',
		'li1Class'=>'menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item narrow',
		'li1WithChildClass'=>'custom-menu menu-item-has-children  has-sub wide pos-center col-2',
		'ul2Attr'=>['class'=>'sub-menu']
		]);?>                    
                        <ul id="menu-home" class="main-menu mega-menu show-arrow">
                        <li id="nav-menu-item-4032" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-4028 current_page_item active narrow "><a href="http://myphamaplus.com/" class=" current ">Trang chủ</a></li>
<li id="nav-menu-item-3914" class="custom-menu menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children  has-sub wide pos-center col-2"><a href="http://myphamaplus.com/shop" class="">Sản phẩm</a>
<div class="popup"><div class="inner" style="background-image:url(//myphamaplus.com/wp-content/uploads/2016/08/Pink-Citrus-Powerpoint-Background-1000x750.jpg);;background-position:center bottom;;background-repeat:no-repeat;"><ul class="sub-menu">
	<li id="nav-menu-item-4177" class="menu-item menu-item-type-taxonomy menu-item-object-product_cat " data-cols="1"><a href="http://myphamaplus.com/dm/cham-soc-lam-sach-da" class=""><i class="fa fa-dot-circle-o"></i>Chăm sóc làm sạch da</a></li>
	<li id="nav-menu-item-4186" class="menu-item menu-item-type-taxonomy menu-item-object-product_cat " data-cols="1"><a href="http://myphamaplus.com/dm/duong-da-ngay-va-dem" class=""><i class="fa fa-dot-circle-o"></i>Dưỡng da ngày và đêm</a></li>
	<li id="nav-menu-item-4183" class="menu-item menu-item-type-taxonomy menu-item-object-product_cat " data-cols="1"><a href="http://myphamaplus.com/dm/cham-soc-da-mun" class=""><i class="fa fa-dot-circle-o"></i>Chăm sóc da mụn</a></li>
	<li id="nav-menu-item-4184" class="menu-item menu-item-type-taxonomy menu-item-object-product_cat " data-cols="1"><a href="http://myphamaplus.com/dm/cham-soc-da-sam-nam" class=""><i class="fa fa-dot-circle-o"></i>Chăm sóc da sạm nám</a></li>
	<li id="nav-menu-item-4194" class="menu-item menu-item-type-taxonomy menu-item-object-product_cat " data-cols="1"><a href="http://myphamaplus.com/dm/cham-soc-da-lao-hoa" class=""><i class="fa fa-dot-circle-o"></i>Chăm sóc da lão hoá</a></li>
	<li id="nav-menu-item-4198" class="menu-item menu-item-type-taxonomy menu-item-object-product_cat " data-cols="1"><a href="http://myphamaplus.com/dm/cung-cap-duong-chat" class=""><i class="fa fa-dot-circle-o"></i>Cung cấp dưỡng chất</a></li>
	<li id="nav-menu-item-4200" class="menu-item menu-item-type-taxonomy menu-item-object-product_cat " data-cols="1"><a href="http://myphamaplus.com/dm/trang-diem-chong-nang" class=""><i class="fa fa-dot-circle-o"></i>Trang điểm &#8211; chống nắng</a></li>
	<li id="nav-menu-item-4193" class="menu-item menu-item-type-taxonomy menu-item-object-product_cat " data-cols="1"><a href="http://myphamaplus.com/dm/bo-chuyen-nghiep" class=""><i class="fa fa-dot-circle-o"></i>Bộ chuyên nghiệp</a></li>
</ul></div></div>
</li>
<li id="nav-menu-item-4561" class="menu-item menu-item-type-post_type menu-item-object-page  narrow "><a href="http://myphamaplus.com/giay-phep" class="">Giấy phép</a></li>
<li id="nav-menu-item-4106" class="menu-item menu-item-type-custom menu-item-object-custom  narrow "><a href="/hoi-dap/" class="">Hỏi đáp</a></li>
<li id="nav-menu-item-4117" class="custom-menu menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children  has-sub wide pos-center col-2"><a href="http://myphamaplus.com/blog" class="">Tin tức</a>
<div class="popup"><div class="inner" style="background-image:url(//myphamaplus.com/wp-content/uploads/2016/08/pink-elegant-flowers-backgrounds-for-powerpoint.jpg);;background-position:center center;"><ul class="sub-menu">
	<li id="nav-menu-item-4187" class="menu-item menu-item-type-taxonomy menu-item-object-category " data-cols="1"><a href="http://myphamaplus.com/category/tin-tuc/ho-tro-mo-tiem" class=""><i class="fa fa-newspaper-o"></i>HỖ TRỢ MỞ TIỆM</a></li>
	<li id="nav-menu-item-4188" class="menu-item menu-item-type-taxonomy menu-item-object-category " data-cols="1"><a href="http://myphamaplus.com/category/tin-tuc/dao-tao-day-nghe" class=""><i class="fa fa-newspaper-o"></i>ĐÀO TẠO-DẠY NGHỀ</a></li>
	<li id="nav-menu-item-4189" class="menu-item menu-item-type-taxonomy menu-item-object-category " data-cols="1"><a href="http://myphamaplus.com/category/tin-tuc/chuyen-muc-lam-dep" class=""><i class="fa fa-newspaper-o"></i>CHUYÊN MỤC LÀM ĐẸP</a></li>
	<li id="nav-menu-item-4190" class="menu-item menu-item-type-taxonomy menu-item-object-category " data-cols="1"><a href="http://myphamaplus.com/category/tin-tuc/goc-a-plus" class=""><i class="fa fa-newspaper-o"></i>Góc A&#038;PLUS</a></li>
</ul></div></div>
</li>
<li class="menu-item"><a href="http://myphamaplus.com/my-account"><i class="fa fa-user"></i>Đăng nhập</a></li><li class="menu-item"><a href="http://myphamaplus.com/my-account"><i class="fa fa-user-plus"></i>Đăng ký</a></li></ul>                    </div>
                            <div id="mini-cart" class="dropdown mini-cart minicart-inline effect-fadein">
            <div class="dropdown-toggle cart-head " data-toggle="dropdown" data-delay="50" data-close-others="false">
                <i class="minicart-icon "></i>
                                    <span class="cart-items"><span class="mobile-hide"><i class="fa fa-spinner fa-pulse"></i></span><span class="mobile-show"><i class="fa fa-spinner fa-pulse"></i></span></span>
                            </div>
            <div class="dropdown-menu cart-popup widget_shopping_cart">
                <div class="widget_shopping_cart_content">
                    <div class="cart-loading"></div>
                </div>
            </div>
         
        </div>
                    </div>
            </div>
        </div>
    </div>
</header>
                            </div><!-- end header wrapper -->
                            
                            
                            
                            
                            
                            
                            
                            
                            
                             
<div class="banner-container">
            <div id="banner-wrapper" class="">
                
<div id="rev_slider_2_1_wrapper" class="rev_slider_wrapper fullwidthbanner-container" style="margin:0px auto;background-color:#E9E9E9;padding:0px;margin-top:0px;margin-bottom:0px;max-height:500px;">
<!-- START REVOLUTION SLIDER 4.6.93 fullwidth mode -->
	<div id="rev_slider_2_1" class="rev_slider fullwidthabanner" style="display:none;max-height:500px;height:500px;">
<ul>	<!-- SLIDE  -->
	<li data-transition="random" data-slotamount="7" data-masterspeed="300"  data-saveperformance="off" >
		<!-- MAIN IMAGE -->
		<img src="http://myphamaplus.com/wp-content/uploads/2016/06/001.jpg"  alt="001"  data-bgposition="center top" data-bgfit="cover" data-bgrepeat="no-repeat">
		<!-- LAYERS -->
	</li>
	<!-- SLIDE  -->
	<li data-transition="random" data-slotamount="7" data-masterspeed="300"  data-saveperformance="off" >
		<!-- MAIN IMAGE -->
		<img src="http://myphamaplus.com/wp-content/uploads/2016/06/002.jpg"  alt="002"  data-bgposition="center top" data-bgfit="cover" data-bgrepeat="no-repeat">
		<!-- LAYERS -->
	</li>
</ul>
<div class="tp-bannertimer"></div>	</div>
			

			 


			</div><!-- END REVOLUTION SLIDER -->            </div>
        </div>                            