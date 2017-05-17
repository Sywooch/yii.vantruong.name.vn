<?php 
 
$this->registerCssFile(__LIBS_DIR__ . '/themes/css/animate.css');
//$this->registerCssFile(__LIBS_DIR__ . '/themes/fontello/css/fontello.css');
$this->registerCssFile(__LIBS_DIR__ . '/font-awesome/css/font-awesome.min.css');
//$this->registerCssFile(__LIBS_DIR__ . '/themes/fontello/css/animation.css');
$this->registerCssFile(__LIBS_DIR__ . '/popup/colorbox/colorbox.css');
$this->registerCssFile(__LIBS_DIR__ . '/menu/superfish-1.7.4/src/css/superfish.css');
$this->registerCssFile(__LIBS_DIR__ . '/slider/slick/slick.css');
$this->registerCssFile(__LIBS_DIR__ . '/slider/slick/slick-theme.css');


?>
<div id="bg_Header">
<div id="header" class="container"><div class="row">
<div id="Logo"><div id="LogoContainer"><a href="<?php echo Yii::$app->homeUrl;?>">
<?php 
    	echo getImage([
    			'src'=>Yii::$site['logo']['logo']['image'],
    			'w'=>240,'rename'=>false,
    			'img_attr'=>[
    					'class'=>'logo-main','alt'=>get_site_value('seo/site_name')
   	]
]);
?>    </a></div></div>
<form method="get" action="/search" id="SearchForm">
                 
<div id="SearchFormContainer">
 	<div class="input-group">
      <input name="q" required="required" type="text" class="form-control" autocomplete="off" placeholder="Nhập từ khóa tìm kiếm">
      <span class="input-group-btn">
        <button class="btn btn-success" type="submit"><i class="fa fa-search"></i> Tìm</button>
      </span>
    </div><!-- /input-group -->
  
</div>
                    </form>
<?php 
$b = Yii::$app->zii->getBox('map_link');
 
if(!empty($b)){	
	echo '<a href="'.(isset($b['url_link']) ? $b['url_link'] : '#').'" class="btn btn-default order-tracking-shortcut"><i class="fa fa-map-marker "></i> '.uh($b['title']).'</a>';
}
?>                    

 
 
 
 <div id="TopMenu" class=" ">
                        
<ul>
    
    <li class="First topMenu_Account hide">
        <a href="/account/personal.html">
            Tài khoản của tôi
        </a>
    </li>
    <li class="topMenu_Order hide">
        <a href="/account/order-status.html">
            Quản lý đơn hàng
        </a>
    </li>
    <li class="topMenu_Wishlist hide">
        <a href="/account/wishlist.html">
            Danh sách ưa thích
        </a>
    </li>
    <li class="CartLink topMenu_Cart">
        <a href="/cart">
            Giỏ hàng
<?php 
$cart = Yii::$app->zii->getCart();
echo '<span id="pInTop">'.($cart['totalItem']>0 ? '('.$cart['totalItem'].')' : '').'</span>';
?>            
            <span id="pInTop"></span>
        </a>
    </li>
    
    <li class="topMenu_Login hide">
        <a href="/account/login.html">
            Đăng nhập
        </a>
    </li>
    <li class="topMenu_Register hide">
        <a href="/account/register.html">
            Đăng ký
        </a>
    </li>
    
</ul>
 
                    </div>
                    <br class="Clear">
                     
 <div class="b-header-3__link">
<?php 
echo Yii::$app->zii-> getMenuItem([
		//'listItem'=> $reg->f->__get_bottom_nav(),
		//'hTag'=>array('span'),
		'key'=>'top',
		'maxLevel'=>1,
		'attribute'=>array( 'class'=>'top-menu inline f14p', ),
				 
		]);?>  
 </div>                   
                    
                    <div class="phone">
                        <i class="fa fa-phone"> </i>
                        
                        <span class="hotline">
                            Hotline: <span class="phone-number">0949 357 123</span> <span class="hotline_time">( 8h - 21h kể cả T7,CN )</span>
                        </span>
                    </div>
                </div></div>
 </div>
            
      
            
            
            
            
            
            