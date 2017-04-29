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

  
 

<div class="header-wrapper clearfix <?php echo Yii::$app->controller->action->id == 'index' ? ' fixed-header' : '';?>"><!-- header wrapper -->
                                
                    <header id="header" class="header-corporate header-10 ">
    
    <div class="header-main">
        <div class="container">
            <div class="header-left">
                                <div class="logo">                   
<a href="<?php echo Yii::$app->homeUrl;?>" title="<?php echo get_site_value('seo/title');?>" rel="home">
<?php 
    	echo getImage([
    			'src'=>Yii::$site['logo']['logo']['image'],
    			'w'=>125,'rename'=>false,
    			'img_attr'=>[
				'class'=>'logo-main','alt'=>'Mỹ phẩm A&amp;PLUS'
   	]
]);
?>                   </a>
                </div>            </div>

            <div class="header-right">
                <div class="header-right-top">
                    <div class="header-contact">
<?php 
$b = Yii::$app->zii->getBox('top_contact');
if(!empty($b)){
	echo uh($b['text'],2);
}
?>                    
</div>    
                    
                    <div class="searchform-popup">
        <a class="search-toggle"><i class="fa fa-search"></i></a>
        
<form role="search" method="get" id="yith-ajaxsearchform" action="/search" class="yith-ajaxsearchform-container searchform ">
    <fieldset>
        <span class="text"><input name="q" id="yith-s" type="text" value="<?php echo getParam('q');?>" placeholder="Tìm kiếm&hellip;" /></span>
                <span class="button-wrap"><button class="btn" id="yith-searchsubmit" title="Search" type="submit"><i class="fa fa-search"></i></button></span>        
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
		'li1Class'=>'menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item',
		'li2Class'=>'menu-item menu-item-type-taxonomy menu-item-object-product_cat w50',
		'li1WithChildClass'=>'custom-menu menu-item-has-children has-sub wide pos-center col-2',
		'ul2Attr'=>['class'=>'sub-menu'],
		'preUl2'=>'<div class="popup"><div class="inner">',
		'a2Pre'=>'<i class="fa fa-dot-circle-o"></i>',
		'afterUl2'=>'</div></div>',
		//'afterUl1'=>' <li class="menu-item"><a href="/my-account"><i class="fa fa-user"></i>Đăng nhập</a></li><li class="menu-item"><a href="/my-account"><i class="fa fa-user-plus"></i>Đăng ký</a></li>',
		]);?>                    
</div>
<?php 
$cart = Yii::$app->zii->getCart();
 
?>                            <div id="mini-cart" class="dropdown mini-cart minicart-inline effect-fadein">
            <div class="dropdown-toggle cart-head " data-toggle="dropdown" data-delay="50" data-close-others="false">
                <i class="minicart-icon "></i>
                                    <span class="cart-items"><span class="mobile-hide"><i class="cart-total-item"><?php echo $cart['totalItem'];?></i> sản phẩm</span><span class="mobile-show"><i class="cart-total-item"><?php echo $cart['totalItem'];?></i></span></span>
                            </div>
            <div class="dropdown-menu cart-popup widget_shopping_cart">
                <div class="widget_shopping_cart_content">

<div class="scroll-wrapper cart_list product_list_widget scrollbar-inner" style="position: relative;"><ul class="cart_list product_list_widget scrollbar-inner  scroll-content" style="margin-bottom: 0px; margin-right: 0px;">

    
							<li class="mini_cart_item">
                        <div class="product-image"><div class="inner">
                                                    <a href="http://myphamaplus.com/sp/spsptinh-chat-hyaluronate-chong-oxy-hoa-da">
                                <img width="90" height="90" src="//myphamaplus.com/wp-content/uploads/2016/05/A017-90x90.jpg" class="attachment-shop_thumbnail size-shop_thumbnail wp-post-image" alt="" srcset="//myphamaplus.com/wp-content/uploads/2016/05/A017-90x90.jpg 90w, //myphamaplus.com/wp-content/uploads/2016/05/A017-50x50.jpg 50w, //myphamaplus.com/wp-content/uploads/2016/05/A017-500x500.jpg 500w, //myphamaplus.com/wp-content/uploads/2016/05/A017-600x600.jpg 600w, //myphamaplus.com/wp-content/uploads/2016/05/A017-300x300.jpg 300w, //myphamaplus.com/wp-content/uploads/2016/05/A017-468x468.jpg 468w, //myphamaplus.com/wp-content/uploads/2016/05/A017-560x560.jpg 560w, //myphamaplus.com/wp-content/uploads/2016/05/A017-367x367.jpg 367w, //myphamaplus.com/wp-content/uploads/2016/05/A017-450x450.jpg 450w, //myphamaplus.com/wp-content/uploads/2016/05/A017-85x85.jpg 85w, //myphamaplus.com/wp-content/uploads/2016/05/A017.jpg 1000w" sizes="(max-width: 90px) 100vw, 90px">                            </a>
                                                </div></div>
                        <div class="product-details">
                                                            <a href="http://myphamaplus.com/sp/spsptinh-chat-hyaluronate-chong-oxy-hoa-da">
                                    Tinh chất Hyaluronate chống oxy hóa da&nbsp;                                </a>
                                                        
                            <span class="quantity">1 × <span class="woocommerce-Price-amount amount">1.200.000&nbsp;<span class="woocommerce-Price-currencySymbol">₫</span></span></span>                            <a href="http://myphamaplus.com/cart?remove_item=a732804c8566fc8f498947ea59a841f8&amp;_wpnonce=8f60f666c0" class="remove remove-product" title="Xóa sản phẩm này" data-cart_id="a732804c8566fc8f498947ea59a841f8" data-product_id="4281" data-product_sku="A017"></a>                        </div>
                        <div class="ajax-loading"></div>
					</li>
										<li class="mini_cart_item">
                        <div class="product-image"><div class="inner">
                                                    <a href="http://myphamaplus.com/sp/sptinh-chat-collagen-tang-dan-hoi-da">
                                <img width="90" height="90" src="//myphamaplus.com/wp-content/uploads/2016/05/A018-90x90.jpg" class="attachment-shop_thumbnail size-shop_thumbnail wp-post-image" alt="" srcset="//myphamaplus.com/wp-content/uploads/2016/05/A018-90x90.jpg 90w, //myphamaplus.com/wp-content/uploads/2016/05/A018-50x50.jpg 50w, //myphamaplus.com/wp-content/uploads/2016/05/A018-500x500.jpg 500w, //myphamaplus.com/wp-content/uploads/2016/05/A018-600x600.jpg 600w, //myphamaplus.com/wp-content/uploads/2016/05/A018-300x300.jpg 300w, //myphamaplus.com/wp-content/uploads/2016/05/A018-468x468.jpg 468w, //myphamaplus.com/wp-content/uploads/2016/05/A018-560x560.jpg 560w, //myphamaplus.com/wp-content/uploads/2016/05/A018-367x367.jpg 367w, //myphamaplus.com/wp-content/uploads/2016/05/A018-450x450.jpg 450w, //myphamaplus.com/wp-content/uploads/2016/05/A018-85x85.jpg 85w, //myphamaplus.com/wp-content/uploads/2016/05/A018.jpg 1000w" sizes="(max-width: 90px) 100vw, 90px">                            </a>
                                                </div></div>
                        <div class="product-details">
                                                            <a href="http://myphamaplus.com/sp/sptinh-chat-collagen-tang-dan-hoi-da">
                                    Tinh chất Collagen tăng đàn hồi da A&amp;PLUS&nbsp;                                </a>
                                                        
                            <span class="quantity">1 × <span class="woocommerce-Price-amount amount">1.200.000&nbsp;<span class="woocommerce-Price-currencySymbol">₫</span></span></span>                            <a href="http://myphamaplus.com/cart?remove_item=18a010d2a9813e91907ce88cd9143fdf&amp;_wpnonce=8f60f666c0" class="remove remove-product" title="Xóa sản phẩm này" data-cart_id="18a010d2a9813e91907ce88cd9143fdf" data-product_id="4277" data-product_sku="A018"></a>                        </div>
                        <div class="ajax-loading"></div>
					</li>
					
	
</ul><div class="scroll-element scroll-x"><div class="scroll-element_outer">    <div class="scroll-element_size"></div>    <div class="scroll-element_track"></div>    <div class="scroll-bar" style="width: 100px;"></div></div></div><div class="scroll-element scroll-y"><div class="scroll-element_outer">    <div class="scroll-element_size"></div>    <div class="scroll-element_track"></div>    <div class="scroll-bar" style="height: 100px;"></div></div></div></div><!-- end product list -->


	<p class="total"><strong>Tổng phụ:</strong> <span class="woocommerce-Price-amount amount">2.400.000&nbsp;<span class="woocommerce-Price-currencySymbol">₫</span></span></p>

	
	<p class="buttons">
		<a href="http://myphamaplus.com/cart" class="button wc-forward">Xem giỏ hàng</a>
		<a href="http://myphamaplus.com/checkout" class="button checkout wc-forward">Thanh toán</a>
	</p>


</div>
            </div>
         
        </div>
                    </div>
            </div>
        </div>
    </div>
</header>
</div><!-- end header wrapper -->                            
<?php
$l = Yii::$app->zii->getAdvert(['code'=>'ADV_SLIDER','category_id'=>__CATEGORY_ID__]);
 
if(!empty($l)){ 
	echo '<div class="banner-container"><div id="banner-wrapper" class=""><div id="rev_slider_2_1_wrapper" class="rev_slider_wrapper fullwidthbanner-container" style="margin:0px auto;background-color:#E9E9E9;padding:0px;margin-top:0px;margin-bottom:0px;max-height:500px;">';
	echo '<div id="rev_slider_2_1" class="rev_slider fullwidthabanner" style="display:none;max-height:500px;height:500px;">';
	echo '<ul>';
	foreach ($l as $k=>$v){
		echo '<li data-transition="random" data-slotamount="7" data-masterspeed="300"  data-saveperformance="off" >';
		echo getImage([
				'src'=>$v['image'],
				'w'=>1600,
				'attrs'=>[
						'alt'=>uh($v['title']),
						'data-bgposition'=>"center top",
						'data-bgfit'=>"cover",
						'data-bgrepeat'=>"no-repeat"
		]
		]);
		echo '</li>';
	}
	echo '</ul><div class="tp-bannertimer"></div></div></div></div></div>  ';
}
?>                 