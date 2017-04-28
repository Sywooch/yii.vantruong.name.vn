<?php

$this->registerCssFile(__LIBS_DIR__ . '/themes/css/animate.css');
$this->registerCssFile(__LIBS_DIR__ . '/font-awesome/css/font-awesome.min.css');
$this->registerCssFile(__LIBS_DIR__ . '/themes/fontello/css/animation.css');
$this->registerCssFile(__LIBS_DIR__ . '/popup/colorbox/colorbox.css');
$this->registerCssFile(__LIBS_DIR__ . '/menu/superfish-1.7.4/src/css/superfish.css');
$this->registerCssFile(__LIBS_DIR__ . '/slider/slick/slick.css');
$this->registerCssFile(__LIBS_DIR__ . '/slider/slick/slick-theme.css');
//
//$this->registerCssFile(__RSDIR__ . '/css/flip-countdown.css');
//$this->registerCssFile(__RSDIR__ . '/css/event.css');
//$this->registerCssFile(__RSDIR__ . '/css/teacher.css');
$this->registerCssFile('https://fonts.googleapis.com/css?family=Roboto%3A&#038;ver=4.4.8');
//$this->registerCssFile(__RSDIR__ . '/css/ilightbox.css');
//$this->registerCssFile(__RSDIR__ . '/css/animations.css');
//$this->registerCssFile(__RSDIR__ . '/css/responsive.css'); 
 
?>
<header id="header" class="header_out_side">
<div class="fusion-header-wrapper">
<div class="fusion-header-v1 fusion-logo-left fusion-sticky-menu- fusion-sticky-logo- fusion-mobile-logo- fusion-mobile-menu-design-modern ">
<div class="fusion-header-sticky-height"></div>
<div class="fusion-header ">
<div class="fusion-row container">

<div class="fusion-logo" data-margin-top="14px" data-margin-bottom="14px" data-margin-left="0px" data-margin-right="0px">
<a class="fusion-logo-link" href="<?php echo Yii::$app->homeUrl;?>">
	

<img src="<?php echo Yii::$site['logo']['logo']['image']; ?>" width="" height="" alt="Logo" class="fusion-logo-1x fusion-standard-logo" />
 
 
</a>
</div> 
<div class="fusion-main-menu">
<?php
echo Yii::$app->zii-> getMenuItem([
		//'listItem'=> $reg->f->__get_bottom_nav(),
		//'hTag'=>array('span'),
		'key'=>'main',
				//'maxLevel'=>1,
		'attribute'=>array( 'class'=>'fusion-menu sf-menu superfish','id'=>'menu-main-menu'),
				//'firstItemClass'=>'first-item',
		//'lastItemClass'=>'last-item',
		//'firstItem'=>'<li class="li-child li-child-0 li-level-1 "><a href="./" class=""><i class="fa fa-home"></i>&nbsp;</a></li>',
		//'ul2Attr'=>['class'=>'sub-menu'],
		'li2Class'=>'menu-item menu-item-type-post_type menu-item-object-page'
		]);
?>
</div><div class="fusion-mobile-menu-icons"><a href="#" class="fusion-icon fusion-icon-bars"></a></div><div class="fusion-mobile-nav-holder"></div>			</div>
</div>
</div>
<div class="fusion-clearfix"></div>
</div>
 </header>
<?php 
$sliders = Yii::$app->zii->getAdvert([
		'code'=>'ADV_SLIDER',
		'category_id'=> __CATEGORY_ID__]);
if(!empty($sliders) && (Yii::$app->controller->action->id == 'index' || __CATEGORY_ID__>0)){
	echo '<div class="row mslider fullwidth"><div class="columns small-12 slider w100">';
	foreach ($sliders as $k=>$v){
		echo '<div class="text-center slide">'.getImage(array('src'=>$v['image'],'w'=>1600,'alt'=>$v['title'],'img_attr'=>['class'=>'w100'])).'</div>';
	}
 
echo '</div></div>';
}
?>
 
