<div class="container">
                <div class="row">
            
            <!-- main content -->
            <div class="main-content col-md-12">

                                        
                            
    <div id="content" role="main">
                
            <article class="post-4028 page type-page status-publish hentry">
                
                <span class="entry-title" style="display: none;">Home</span><span class="vcard" style="display: none;"><span class="fn"><a href="http://myphamaplus.com/author/phuongthao" title="Đăng bởi phuongthao" rel="author">phuongthao</a></span></span><span class="updated" style="display:none">2016-10-17T11:16:22+00:00</span>
                <div class="page-content">
<?php 
 
$lb = $this->app()->getBoxIndex();
if(!empty($lb)){
	foreach ($lb as $kb=>$vb){
		$b = $vb['box'];
		$link_box = isset($b['url_link']) ? $b['url_link'] : '#';
		echo '<div class="vc_row wpb_row vc_row-fluid vc_custom_1445022099656"><div class="vc_column_container vc_col-sm-12"><div class="wpb_wrapper">
<div id="ultimate-heading-vb-'.$b['id'].'" class="uvc-heading ultimate-heading-vb-'.$b['id'].' uvc-451 " data-hspacer="image_only"  data-halign="center" style="text-align:center">
<div class="uvc-main-heading ult-responsive"  data-ultimate-target=\'.uvc-heading.ultimate-heading58f25c0f6804d h2\'  data-responsive-json-new=\'{"font-size":"","line-height":""}\' >
<h2 class="upper" style="font-weight:normal;color:#bd0b00;margin-top:35px;margin-bottom:20px;">'.uh($b['title']).'</h2></div>
<div class="uvc-sub-heading ult-responsive"  data-ultimate-target=\'.uvc-heading.ultimate-heading58f25c0f6804d .uvc-sub-heading \'  data-responsive-json-new=\'{"font-size":"","line-height":""}\'  style="font-weight:normal;color:#666666;margin-bottom:20px;">'.uh($b['info']).'</div>
<div class="uvc-heading-spacer image_only" style="margin-bottom:20px;">
 
<span class="ultimate-headings-icon-image leaf-img"></span>
</div></div>	
<div class="vc_empty_space"  style="height: 25px" ><span class="vc_empty_space_inner"></span></div>
<div class="porto-products wpb_content_element appear-animation" data-appear-animation="flash"><div class="slider-wrapper"><div class="woocommerce columns-4">
			
			<ul class="products  products-slider  pcols-lg-4 pcols-md-4 pcols-xs-2 pcols-ls-1  pwidth-lg-4 pwidth-md-4 pwidth-xs-2 pwidth-ls-1" data-cols-lg="4" data-cols-md="4" data-cols-xs="2" data-cols-ls="1" data-navigation="1">
';
		if(!empty($vb['listItem'])){
			foreach ($vb['listItem'] as $k=>$v){
				$link = isset($v['url_link']) ? $v['url_link'] : cu([DS.$v['url']]);
				$target = isset($v['target']) ? $v['target'] : '_self';
					 
				
				echo '<li class="show-links-onimage post-4302 product type-product status-publish has-post-thumbnail instock featured shipping-taxable purchasable product-type-simple">

	<a href="'.$link.'" class="woocommerce-LoopProduct-link">
    <div class="product-image">
        <a href="'.$link.'">
            <div class="labels">
 '.discountPrice($v['price2'],$v['price1']).' 
<div data-link="/cart" class="viewcart" title="Xem giỏ hàng"></div></div><div class="inner img-effect">
		 
		'.getImage(['src'=>$v['icon'], 'w'=>300,'h'=>300,'attrs'=>[
				'class'=>" wp-post-image",
				'alt'=>$v['title']
		]]).' 
		</div></a>

        <div class="links-on-image">
            <div class="add-links-wrap">
    <div class="add-links  clearfix">
        <a href="#" rel="nofollow" data-id="'.$v['id'].'" data-amount="1" onclick="return add_to_cart(this);" class="button add_to_cart_button product_type_simple">Thêm vào giỏ</a>
						<div class="quickview " data-id="'.$v['id'].'" title="Xem Nhanh"><a class="white" href="'.$link.'">Xem Nhanh</a></div>    </div>
</div>        </div>
    </div>

            <h3>'.uh($v['title']).'</h3>    
        <div class="description">'.uh($v['info']).'</div>
    
	 
<span class="price">'.($v['price1'] > 0 && $v['price1'] > $v['price2'] ? '<del><span class="woocommerce-Price-amount amount">'.Yii::$app->zii->showPrice($v['price1'],$v['currency']).'</span></del>' : '').'
'.($v['price2'] > 0 ? '<ins><span class="woocommerce-Price-amount amount">'.Yii::$app->zii->showPrice($v['price2'],$v['currency']).'</span></ins>' : '').'						
						
						
						</span>
    </a><div class="add-links-wrap">
    <div class="add-links  clearfix">
       <a href="#" rel="nofollow" data-id="'.$v['id'].'" data-amount="1" onclick="return add_to_cart(this);" class="button add_to_cart_button product_type_simple">Thêm vào giỏ</a><div class="quickview " data-id="'.$v['id'].'" title="Xem Nhanh"><a class="white" href="'.$link.'">Xem Nhanh</a></div>   </div>
</div>
</li>';
			}
		}
				
					 
				
		echo '</ul></div></div></div></div></div></div>';
		echo '<div class="vc_row wpb_row vc_row-fluid vc_custom_1439057328884">';
		$adverts = Yii::$app->zii->getAdvert([
				'code'=>'ADV_INDEX_BOTTOM_LEFT',
				'box_id'=>$b['id']
		]);
		if(!empty($adverts)){
			echo '<div class="vc_column_container vc_col-sm-8"><div class="wpb_wrapper">
		<div class=" ult-ib-effect-style2 ult-ib-resp "   data-opacity="1" data-hover-opacity="1" >';
			foreach ($adverts as $adv){
		echo '<a href="'.$adv['link'].'" target="'.$adv['target'].'">'.getImage(['src'=>$adv['image'],'w'=>768,'attrs'=>['alt'=>$adv['title'],'class'=>'alignnone size-full']]).'</a>';
			}
		echo '<div class="ult-new-ib-desc" style=""><h2 class="ult-new-ib-title" style="font-weight:normal;color:#dd3333;"></h2>
		<div class="ult-new-ib-content" style="font-weight:normal;"><p></p></div></div>
		<a class="ult-new-ib-link"  ></a></div></div></div>';
		}
		
		
		
		echo '<div class="vc_column_container vc_col-sm-4"><div class="wpb_wrapper">';
		$adverts = Yii::$app->zii->getAdvert([
				'code'=>'ADV_INDEX_BOTTOM_RIGHT',
				'box_id'=>$b['id']
		]);
		if(!empty($adverts)){
			foreach ($adverts as $adv){
			echo '<div class="wpb_text_column wpb_content_element ">
		<div class="wpb_wrapper">
			<a href="'.$adv['link'].'" target="'.$adv['target'].'">'.getImage(['src'=>$adv['image'],'w'=>450,'attrs'=>['alt'=>$adv['title'],'class'=>'alignnone size-full']]).'</a>

		</div>
	</div>';
		}}
	
	

	echo ' 
</div></div></div>';
	}
}
?>                
                
                
                
                
                    









    <div class="vc_row-full-width"></div><div class="vc_row wpb_row vc_row-fluid"><div class="vc_column_container vc_col-sm-12"><div class="wpb_wrapper"><style type="text/css" data-type="vc_shortcodes-custom-css">.vc_custom_1425514263484{margin-top: 0px !important;margin-bottom: 0px !important;}</style><div class="porto-block  appear-animation" data-appear-animation="fadeInUp"><div class="vc_row wpb_row vc_row-fluid ads-container-light br-none vc_custom_1425514263484"><div class="vc_column_container vc_col-sm-12"><div class="wpb_wrapper"><div class="porto-container container "><div class="vc_row wpb_row vc_inner vc_row-fluid"><div class="vc_column_container vc_col-sm-4"><div class="wpb_wrapper"><div class="aio-icon-component   style_1"><div class="aio-icon-box left-icon" style=""  ><div class="aio-icon-left"><div class="ult-just-icon-wrapper "><div class="align-icon" style="text-align:center;">
<div class="aio-icon circle "  style="color:#ffffff;background:#6fc1f1;font-size:32px;display:inline-block;">
	<i class="Defaults-truck"></i>
</div></div></div></div><div class="aio-ibd-block"><div class="aio-icon-header"><h3 class="aio-icon-title" style="font-family:'Open Sans';font-weight:600;font-size:16px;line-height:32px;color:#313131;">GIAO HÀNG TẬN NƠI</h3></div> <!-- header --><div class="aio-icon-description" style="font-size:12px;">Miễn phí cho đơn hàng từ 500k</div> <!-- description --></div> <!-- aio-ibd-block --></div> <!-- aio-icon-box --></div> <!-- aio-icon-component --></div></div><div class="vc_column_container vc_col-sm-4"><div class="wpb_wrapper"><div class="aio-icon-component   style_1"><div class="aio-icon-box left-icon" style=""  ><div class="aio-icon-left"><div class="ult-just-icon-wrapper "><div class="align-icon" style="text-align:center;">
<div class="aio-icon circle "  style="color:#ffffff;background:#f16fb5;font-size:32px;display:inline-block;">
	<i class="Defaults-gift"></i>
</div></div></div></div><div class="aio-ibd-block"><div class="aio-icon-header"><h3 class="aio-icon-title" style="font-family:'Open Sans';font-weight:600;font-size:16px;line-height:32px;color:#313131;">KHUYẾN MÃI</h3></div> <!-- header --><div class="aio-icon-description" style="font-size:12px;">Nhiều chương trình ưu đãi hấp dẫn</div> <!-- description --></div> <!-- aio-ibd-block --></div> <!-- aio-icon-box --></div> <!-- aio-icon-component --></div></div><div class="vc_column_container vc_col-sm-4"><div class="wpb_wrapper"><div class="aio-icon-component   style_1"><div class="aio-icon-box left-icon" style=""  ><div class="aio-icon-left"><div class="ult-just-icon-wrapper "><div class="align-icon" style="text-align:center;">
<div class="aio-icon circle "  style="color:#ffffff;background:#f16f6f;font-size:32px;display:inline-block;">
	<i class="Defaults-comments"></i>
</div></div></div></div><div class="aio-ibd-block"><div class="aio-icon-header"><h3 class="aio-icon-title" style="font-family:'Open Sans';font-weight:600;font-size:16px;line-height:32px;color:#313131;">HỖ TRỢ ONLINE 24/7</h3></div> <!-- header --><div class="aio-icon-description" style="font-size:12px;">Đội ngũ nhân viên luôn bên cạnh bạn</div> <!-- description --></div> <!-- aio-ibd-block --></div> <!-- aio-icon-box --></div> <!-- aio-icon-component --></div></div></div></div>
</div></div></div></div>
	<div class="vc_empty_space"  style="height: 15px" ><span
			class="vc_empty_space_inner"></span></div></div></div></div>
                </div>
            </article>

            
            
        
    </div>

        

</div><!-- end main content -->


    </div>
</div>