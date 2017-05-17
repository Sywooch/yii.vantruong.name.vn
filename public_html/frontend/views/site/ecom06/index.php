<?php 
$this->registerCssFile(__RSDIR__.'/css/slider.css');
$this->registerCssFile(__RSDIR__.'/css/global2.css');
$this->registerCssFile(__RSDIR__.'/css/global3.css');
?>
<div class="wapper_home">
    <div class="top_top"></div>
    <div class="row">
        <div class="left_home col-sm-3">
<div class="DefaultModule">
    <div id="wrap_drop">
<div class="defaultTitle TextHTML-Title"><span>Tất Cả Danh Mục</span></div>
<?php 
$l = \app\models\SiteMenu::getList([
		'key'=>'main'
]);
if(!empty($l)){
	echo '<ul class="menu-verti ls menu-verti-active">';
	foreach ($l as $k=>$v){
		$l1 = \app\models\SiteMenu::getList([
				'parent_id'=>$v['id']
		]);
		echo '<li class="menu-verti-i menu-verti-i-0"><span class="icon">&nbsp;</span> 
		<a class="a-menu-verti-0" href="'.$v['url_link'].'">'.uh($v['title']).' <span>'.(isset($v['short_info']) ? uh($v['short_info']) : '').'</span></a>';
		if(!empty($l1)){
			echo '<ul class="ls menu-hori-1"><li class="menu-hori-1-icon">&nbsp;</li><li class="menu-verti-i menu-verti-i-1"><ul class="ls menu-hori-2">';
			foreach ($l1 as $k1=>$v1){
				echo '<li>
			<h3 class="a-menu-verti-1" title="'.uh($v1['title']).'"><a class="a-menu-verti-0" href="'.$v1['url_link'].'">'.uh($v1['title']).'</a></h3>
			</li>';
			}
			 
		echo '</ul></li></ul>';
		}
		echo '</li>';
	}
	echo '</ul>';	
}
?>
</div>
<div style="clear:both;height:0px;">&nbsp;</div>       
</div>
</div>
<?php 
include_once '_slider.php';
$b = Yii::$app->zii->getBox('marquee');
if(!empty($b)){
	echo '<div id="TextHTML-Module" class="DefaultModule">
    <div class="col-sm-12" style="height:20px; float:right; color:#3690F1; font-size:18px; font-weight:bold">
    <marquee>'.uh($b['text'],2).'</marquee>
    
    </div>
        
</div>';
}
?>
<div style="clear:both;height:0px;"></div>
</div>
<?php 
$lb = Yii::$app->zii->getBoxIndex([
		'listSubMenu'=>true ,
		'limitSub'=>5
]); 
if(!empty($lb)){	
	foreach ($lb as $kb=>$vb){
		$b = $vb['box'];
		$link_box = isset($b['url_link']) ? $b['url_link'] : '#';
		echo '<div class="fl100">';
		echo '<div class="product_title">
<div class="DefaultModule">
    <div class="title">
<h2 class="title-name"><a class="title-main" href="'.$link_box.'" title="'.uh($b['title']).'">'.uh($b['title']).' <span class="category-arrow">&nbsp;</span> </a></h2>';
		if(isset($vb['listSubMenu']) && !empty($vb['listSubMenu'])){
			echo '<ul class="title-link">';
			foreach ($vb['listSubMenu'] as $s){
				echo '<li class="item"><a class="link_text" href="'.$s['url_link'].'">'.uh($s['title']).'</a></li>';
			}
			echo '<li class="see-all"><a class="link_text" href="'.$link_box.'">Tất cả</a></li>';
			echo '</ul>';
		}
 
echo '</div>
        
</div>
</div>';
		
echo '<div class="content row"><div class="product_left col-sm-9">';
$advs = Yii::$app->zii->getAdvert(['code'=>'ADV_INDEX_LEFT','box_id'=>$b['id']]);
if(!empty($advs)){
	echo '<div class="DefaultModule">';
	foreach ($advs as $adv){
		echo '<p><a target="'.$adv['target'].'" href="'.$adv['link'].'"><img alt="wabafun" src="'.$adv['image'].'" title="'.$adv['title'].'"></a></p>';	
	}    
  
	echo '</div>';
}
echo '<div class="Block FeaturedProducts DefaultModule"><div class="defaultContent BlockContent">';
echo '<ul class="ProductList row">';
if(!empty($vb['listItem'])){
	foreach ($vb['listItem'] as $k=>$v){
		echo '<li class="col-sm-3"><div class="item-container pr">
                            <div class="ProductImage ProductImageTooltip ">
                                <a href="'.$v['url_link'].'" >
                                   '.  getImage([
				'src'=>$v['icon'], 
				'w'=>400,
				'attrs'=>[
						'alt'=>uh($v['title']),
						 
				]
		]).'
                                </a>
                            </div>
                            
		<div class="saleFlag iconSprite ">'.discountPrice($v['price2'],$v['price1']).' 
                                 </div>
                            <div class="ProductDetails">
                                <strong><a href="'.$v['url_link'].'">
                                '.uh($v['title']).'</a></strong>
                            </div>
                            <div class="ProductPrice">
                                <div class="retail-price disable">
                                    <span class="price-label"></span><span class="price"><strike class="RetailPriceValue">
                                        </strike>
                                    </span>
                                </div>
                                <div class="special-price">
                                    <span class="price-label"></span>
		'.($v['price2']>0 ? '<span class="price"><em> '.Yii::$app->zii->showPrice($v['price2'],$v['currency']).'</em></span>' : '').'
		'.($v['price1']>$v['price2'] ? '<span class="price line-through italic mgl15"> '.Yii::$app->zii->showPrice($v['price1'],$v['currency']).'</span>' : '').'
		
		
                                </div>
                            </div>
                            <div class="ProductRating Rating-1 hide" style="display: ;">
                                <div class="RatingImage">
                                </div>
                            </div>
                            <div class="ProductActionAdd"><a href="javascript:;" data-id="'.$v['id'].'" data-amount="1" onclick="return add_to_cart(this);"><i class="fa fa-cart-plus"></i><span> Mua hàng</span></a></div>
                        </div></li>';
	}
}
            echo '</ul>';
echo '</div></div></div><!--col-9-->';	

echo '<div class="product_right col-sm-3">';
$l = Yii::$app->zii->getBoxCode('bestseller');
if(!empty($l['listItem'])){
	echo '<div class="TopSellers Moveable Panel DefaultModule">
    <div class="defaultTitle SideTopSeller-Title">
        <span> '.uh($l['box']['title']).'</span></div>
    <div class="defaultContent SideTopSeller-content">
        <div class="BlockContent"><ul class="ProductList">';
	foreach ($l['listItem'] as $k=>$v){
		echo '<li class="'.($k%2==0 ? 'Odd' : 'Event').' pr pdt15">
                            <div class="TopSellerNumber1">'.($k+1).'</div>
                            <div class="ProductImage" style="display: block">
                                <a href="'.$v['url_link'].'" >
                                   '.  getImage([
				'src'=>$v['icon'], 
				'w'=>400,
				'attrs'=>[
						'alt'=>uh($v['title']),
						 
				]
		]).'
                                </a>
                            </div>
                            <div class="ProductDetails">
                                <strong><a href="'.$v['url_link'].'">'.uh($v['title']).'</a></strong>
                            </div>
                            <div class="ProductPrice">
                                <div class="retail-price disable">
                                    <span class="price-label"></span><span class="price"><strike class="RetailPriceValue">
                                        </strike>
                                    </span>
                                </div>
                                <div class="special-price">
                                    <span class="price-label"><span>
                                    '.($v['price2']>0 ? '<span class="price"><em> '.Yii::$app->zii->showPrice($v['price2'],$v['currency']).'</em></span>' : '').'
                                    '.($v['price1']>$v['price2'] ? '<span class="price line-through italic mgl15"> '.Yii::$app->zii->showPrice($v['price1'],$v['currency']).'</span>' : '').'
                                </span></span></div>
                            </div>
                            <hr class="Clear">
                        </li>';
	}
	echo '</ul></div><div class="Clear"></div></div></div>';
}
$advs = Yii::$app->zii->getAdvert(['code'=>'ADV_INDEX_RIGHT','box_id'=>$b['id']]);
if(!empty($advs)){
	echo '<div class="DefaultModule">';
	foreach ($advs as $adv){
		echo '<div class="sidebar-img"><a target="'.$adv['target'].'" href="'.$adv['link'].'"><img alt="wabafun" src="'.$adv['image'].'" title="'.$adv['title'].'"></a></div>';
	}

	echo '</div>';
}
echo '</div><!--col-3-->';
 

echo '</div></div>';		
		 
	}
}
?>    
    
     
    
  
    <div class="Clear"></div>
</div>
 