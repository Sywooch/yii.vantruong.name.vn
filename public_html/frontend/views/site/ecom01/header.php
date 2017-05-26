<?php 
 
//$this->registerCssFile(__LIBS_DIR__ . '/themes/css/animate.css');
$this->registerCssFile(__LIBS_DIR__ . '/themes/fontello/css/fontello.css');
$this->registerCssFile(__LIBS_DIR__ . '/themes/fontello/css/animation.css');
//$this->registerCssFile(__LIBS_DIR__ . '/popup/colorbox/colorbox.css');
//$this->registerCssFile(__LIBS_DIR__ . '/menu/superfish-1.7.4/src/css/superfish.css');
$this->registerCssFile(__RSDIR__ . '/css/cloud-zoom.css');
//$this->registerCssFile(__LIBS_DIR__ . '/slider/slick/slick.css');
//$this->registerCssFile(__LIBS_DIR__ . '/slider/slick/slick-theme.css');


?>
<div class="header pr col-sm-12" id="header">
    <div class="line_top row">
        <div class="container pr">
 
            <?php
            echo $this -> app()-> getMenuItem(
array(//'listItem'=> $reg->f->__get_bottom_nav(),
//'hTag'=>array('span'),
'key'=>'top',
'maxLevel'=>1,
'attribute'=>array( 'class'=>'style-none inline ul-top-left pull-left'),
//'firstItemClass'=>'first-item',
//'lastItemClass'=>'last-item',
//'activeClass'=>array('a'=>'active'),
)); 
            ?>
            <form class="search_form pull-right" action="./search" method="get" name="search">
                <input name="q" type="text" value="<?php echo getParam('q');?>" class="search_input" />
                <button type="submit" class="button-search">
                <i class="ft ft-search"></i>
                </button>
            </form>
        </div>
    </div>
    <div  class="row main_header">
        <div class="container pr">
        
    <div id="logo" class="pull-left">
        <a href="./">
            <img src="<?php echo Yii::$site['logo']['logo']['image']; ?>" title="<?php echo Yii::$site['seo']['title']; ?>" alt="<?php echo Yii::$site['seo']['title']; ?>" />
        </a>
    </div>
 
  <div class="header-level">
	<span class="icon-lv1"></span>
	<div class="level-content lv-width1">Giao hàng <br>toàn quốc</div>
	<span class="icon-lv2"></span>
	<div class="level-content lv-width2">Thanh toán <br> tại nhà</div>
	<span class="icon-lv3"></span>
	<div class="level-content lv-width3">Đổi trả hàng <br>trong vòng 7 ngày</div>
	 
  </div>  
  <div id="cart">
  <div class="heading">
<?php
$cart = $this->app()->getCart();
echo '<div class="cart pointer" onclick="window.location=\''. cu(['/cart']).'\';"> 
<div class="cart-icon"></div>     
<a href="./cart">
<span id="cart-total"><span class="cart-total-item number-items-from-cart">'.$cart['totalItem'].'</span> sản phẩm - <span class="cart-total-price">'.number_format($cart['totalPrice']).'</span> VNĐ</span>
</a></div>       
 
            
        </div>';
?>
        	
 
    
    
  <div class="content">
        <div class="empty">Giỏ hàng đang trống!</div>
      </div>
</div> 


<div class="clear"></div>

<div class="navigation-bar pull-left w100">
<a href="./" class="home-btn home-bt"></a>
		<div class="transparent"></div>			
		 <?php
		 //view(Category::getListCategory(array(              'key'=>'main',                'select'=>'*',            )));
            echo $this ->app()-> getMenuItem(
array(//'listItem'=> $reg->f->__get_bottom_nav(),
//'hTag'=>array('span'),
'key'=>'main',
'maxLevel'=>6,
'attribute'=>array( 'class'=>'mainmenu style-none inline superfish sf-menu'),
//'firstItemClass'=>'first-item',
//'lastItemClass'=>'last-item',
'activeClass'=>array('a'=>'active'),
)); 
            ?>
 
   	 
</div>

 
<div class="slider fl100  ">
<?php
$_slide_width = '1300px';
$_slide_height = '360px';
include_once __LIBS_PATH__. '/forms/slides/zii.jssor.004.php';            
?>
</div>
            
        </div>
    </div>
    
</div> 