<div class="center col-sm-9 col-xs-12">
<div class="Block FeaturedProducts DefaultModule ">
    <div class="defaultTitle TitleContent">
        <span><?php echo __CATEGORY_NAME__?></span> 
    </div>
    <div class="defaultContent BlockContent row">
        
                <ul class="ProductList First mg0 list-l1">
<?php 
$l = Yii::$app->zii->getArticles([
		'p'=>getParam('p',1),
		'type'=>'products',
		'category'=>__CATEGORY_ID__,
		'search'=>__IS_SEARCH__,
		'key'=>'limit-products', 
		'count'=>true,
		
]+$_GET);
if(!empty($l['listItem'])){
	foreach ($l['listItem'] as $k=>$v){
		
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
	echo '<div class="clear"></div>';
	echo setPagi([
			'total_records'=>$l['total_records'],
			'class'=>'page-numbers','rewrite'=>false,
			'p'=>$l['p'],'limit'=>$l['limit'],
			'prev_class'=>'prev page-numbers',
			'page_class'=>'page-numbers',
			'next_class'=>'next page-numbers',
			'first_class'=>'hide',
			'last_class'=>'hide',
	]);
}
?>                     
                             
                               
                        
                    <br class="Clear">
                </ul>
            
                 
            
        
         
        <div class="Clear"></div>
              
    </div>
    <div class="defaultFooter FooterContent">
        <div></div>
    </div>
    <div class="Clear"></div>
</div>

 
 
</div>