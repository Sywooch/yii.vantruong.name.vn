<?php 
 
$this->registerCssFile(__LIBS_DIR__ . '/themes/css/animate.css');
$this->registerCssFile(__LIBS_DIR__ . '/font-awesome/css/font-awesome.min.css');
//$this->registerCssFile(__LIBS_DIR__ . '/themes/fontello/css/animation.css');
$this->registerCssFile(__LIBS_DIR__ . '/popup/colorbox/colorbox.css');
$this->registerCssFile(__LIBS_DIR__ . '/menu/superfish-1.7.4/src/css/superfish.css');
$this->registerCssFile(__RSDIR__ . '/css/owl.carousel.css');
$this->registerCssFile(__RSDIR__ . '/css/owl.theme.default.css');
$this->registerCssFile(__LIBS_DIR__ . '/slider/slick/slick.css');
$this->registerCssFile(__LIBS_DIR__ . '/slider/slick/slick-theme.css');
$this->registerCssFile('https://fonts.googleapis.com/css?family=Abril+Fatface');
$this->registerCssFile('https://fonts.googleapis.com/css?family=Shrikhand');
$this->registerCssFile('https://fonts.googleapis.com/css?family=Lobster');
$this->registerCssFile('https://fonts.googleapis.com/css?family=Baloo+Bhaina');
 
?>
<header id="header">          
<div class="full" id="top">
        <div class="container pr">
<div class="top-fnav pr">        
        <?php
echo Yii::$app->zii-> getMenuItem([
		//'listItem'=> $reg->f->__get_bottom_nav(),
		//'hTag'=>array('span'),
				'key'=>'top',
				'maxLevel'=>1,
		'attribute'=>array( 'class'=>'top_menu', ),
				//'firstItemClass'=>'first-item',
		//'lastItemClass'=>'last-item',
		//'activeClass'=>array('a'=>'active'),
		//'ul2Attr'=>['class'=>'sub-menu'],
		//'li2Class'=>'menu-item menu-item-type-taxonomy menu-item-object-category'
		]);
 
 
$langs = Yii::$app->zii-> getUserLanguages();
if(!empty($langs)){
	echo '<div class="slanguage">';
	foreach ($langs as $k=>$v){
		if(isset($v['is_active']) && $v['is_active'] == 1){
			echo '<a onclick="changeLanguage(\''.$v['code'].'\',this)" data-lang="'.__LANG__.'" rel="nofollow" class="flag '.$v['code'].'">'.$v['title'].'</a>';
		}
	}
	echo '</div>';
}
?>
  </div>
           
        </div>
      </div>
<div class="box-top2">
<div class="container">
	<div class="banner">
    	<div class="sleft"><h1 class="h1_logo"><a href="<?php echo Yii::$app->homeUrl;?>" title="Logo"><?php 
    	echo getImage([
    			'src'=>Yii::$site['logo']['logo']['image'],
    			'w'=>125,'rename'=>false,
    			'img_attr'=>[
    					'class'=>'logo-main','alt'=>'logo'
    	]
    	]);
    	?> </a></h1></div>
		<div class="slogan center ps">
		<?php 
		if(0>1 && Yii::$app->user->can(ROOT_USER)){
			echo '<p><img src="https://media.lenguyet.vn/ecom03/images/banner3.gif"></p>';
		}else{
		$b=Yii::$app->zii->getBox('slogan');
		if(!empty($b)){
			echo uh($b['text'],2);
		}
		}
		?>
		</div>
		<div class="slogan-right ps">
		<?php 
		if(Yii::$app->user->can(ROOT_USER)){
			echo '<div class="giao-hang"><p>Nhận đặt hàng</p>
		<p>Giao hàng toàn quốc</p>
		</div>';	
		}else{
		$b=Yii::$app->zii->getBox('slogan-right');
		if(!empty($b)){
			echo uh($b['text'],2);
		}
		
		}
		?>
		</div>
		<div class="clr"></div>
<form id="custom-search-input" method="get" action="<?php echo cu(['/search']);?>">
                <div class="input-group col-md-12">
                    <input type="text" name="q" class="form-control" placeholder="Nhập từ khóa tìm kiếm" />
                    <span class="input-group-btn">
                        <button class="btn btn-info" type="button">
                            <i class="glyphicon glyphicon-search"></i>
                        </button>
                    </span>
                </div>
            </form>   
  </div>
</div>
<div class="main_menu">
<div class="container container-menu-top"><div class="menu-top"> 
<div class="menu-menu-top-container"> 
<?php
if(0>1 && Yii::$app->user->can(ROOT_USER)){
	echo '<nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Project name</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#">About</a></li>
              <li><a href="#">Contact</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li role="separator" class="divider"></li>
                  <li class="dropdown-header">Nav header</li>
                  <li><a href="#">Separated link</a></li>
                  <li><a href="#">One more separated link</a></li>
                </ul>
              </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li class="active"><a href="./">Default <span class="sr-only">(current)</span></a></li>
              <li><a href="../navbar-static-top/">Static top</a></li>
              <li><a href="../navbar-fixed-top/">Fixed top</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>';
}
echo Yii::$app->zii-> getMenuItem([
		//'listItem'=> $reg->f->__get_bottom_nav(),
		//'hTag'=>array('span'),
		'key'=>'main',
				//'maxLevel'=>1,
		'attribute'=>array( 'class'=>'menu_top_in superfish','id'=>'menu-menu-top'),
				//'firstItemClass'=>'first-item',
		//'lastItemClass'=>'last-item',
		'firstItem'=>'<li class="li-child li-child-0 li-level-1 "><a href="./" class=""><i class="fa fa-home"></i>&nbsp;</a></li>',
		'ul2Attr'=>['class'=>'sub-menu'],
		'li2Class'=>'menu-item menu-item-type-taxonomy menu-item-object-category'
		]);
 

?>
</div></div></div></div>
</div>
<div class="container-fluid" style="margin-bottom:1px;">
<div class="row">
<?php 
$sliders = Yii::$app->zii->getAdvert(['code'=>'ADV_SLIDER','category_id'=>__CATEGORY_ID__]);
if(!empty($sliders)){
	echo '<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
<ol class="carousel-indicators">';
	foreach ($sliders as $k=>$v){
		echo '<li data-target="#carousel-example-generic" data-slide-to="'.$k.'" class="'.($k==0 ? 'active' : '').'"></li>';
	}
echo '</ol>
<div class="carousel-inner" role="listbox">';
	foreach ($sliders as $k=>$v){
		echo '<div class="item '.($k==0 ? 'active' : '').'" >
      <a href=""><img src="'.getImage(array('src'=>$v['image'],'w'=>1600,'output'=>'src')).'" alt="'.$v['title'].'" style="margin:auto;text-align:center;width:100%;"></a>
    </div>';
	}
echo '
 
    
    </div> 
</div>';
}
?>

</div>
</div>
</header>


<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="./">Trang chủ</a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
 <?php
echo Yii::$app->zii-> getMenuItem([
		//'listItem'=> $reg->f->__get_bottom_nav(),
		//'hTag'=>array('span'),
				'key'=>'main',
				//'maxLevel'=>1,
				'attribute'=>array( 'class'=>'nav navbar-nav'),
				//'firstItemClass'=>'first-item',
		//'lastItemClass'=>'last-item',
		//'activeClass'=>array('a'=>'active'),
		'ul2Attr'=>['class'=>'sub-menu'],
		'li1WithChildClass'=>'dropdown',
		'li1NotChildClass'=>'menu-item menu-item-type-post_type menu-item-object-page',
		'li2Class'=>'menu-item menu-item-type-taxonomy menu-item-object-category',
		]);
 

?>
           
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>




<div class="box_giaohang hide">
  <div class="container">
	<div class="float_center">
		<div class="child">
			  <div class="cl cl1">
				<div class="r1">Miễn phí chuyển hàng Hà Nội</div>
				<div class="r2">Nhận đặt hàng Toàn Quốc</div>
			  </div>
			  <div class="cl cl2">
				<div class="r1">Hàng trăm mẫu Sofa giá rẻ có sẵn</div>
				<div class="r2">Kho hàng luôn đầy sản phẩm</div>
			  </div>
			  <div class="cl cl3">
				<div class="r1">Đa dạng kiểu dáng - Đa dạng mức giá</div>
				<div class="r2">Bảo hành 1.5 năm - 2 năm sản phẩm</div>
			  </div>
			  <div class="clearfix"></div>
		</div>
	  </div>		  
      <div class="hotline hotline--mobile">
      <div class="hotline--banle">Bán lẻ:<span>04 6687 3660</span></div>
      <div class="hotline--banbuon">Bán buôn:<span>0987 10 20 50</span></div>
    </div>
  </div>
</div>



