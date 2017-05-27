<?php 
include_once '_breadcrumb.php';
$v = Yii::$app->zii->getArticles(['detail'=>true]);
if(!empty($v)){
	echo '<div class="container"><div class="row"><div class="main-content col-md-12">                                                            
	<div id="container"><div id="content" role="main">
<div itemscope itemtype="http://schema.org/Product" id="product-'.$v['id'].'" class="post-'.$v['id'].' product type-product status-publish has-post-thumbnail product_cat-trang-diem-chong-nang first instock featured shipping-taxable purchasable product-type-simple">

    <div class="product-summary-wrap">
        <div class="row">
            <div class="col-sm-5 summary-before">
                <div class="labels"></div>
<div class="product-images">

    <!-- MasterSlider Main -->
    <div id="product-image-slider-i28e6r8cotwis4ttv8n4p5kdfpj2jt6" class="product-image-slider master-slider">

        <div class="ms-slide">
            <img src="http://myphamaplus.com/wp-content/uploads/2016/05/A021-468x468.jpg" data-href="http://myphamaplus.com/wp-content/uploads/2016/05/A021.jpg" class="woocommerce-main-image zoom" alt="A021" itemprop="image" content="http://myphamaplus.com/wp-content/uploads/2016/05/A021.jpg" /><img class="ms-thumb" alt="A021" src="http://myphamaplus.com/wp-content/uploads/2016/05/A021-90x90.jpg" />        </div>

                <div class="ms-slide">
            <img src="http://myphamaplus.com/wp-content/uploads/2016/05/A021-1-468x468.jpg" data-href="http://myphamaplus.com/wp-content/uploads/2016/05/A021-1.jpg" class="zoom first" alt="A021-1" itemprop="image" content="http://myphamaplus.com/wp-content/uploads/2016/05/A021-1.jpg" /><img class="ms-thumb" alt="A021-1" src="http://myphamaplus.com/wp-content/uploads/2016/05/A021-1-90x90.jpg" />        </div>
                <div class="ms-slide">
            <img src="http://myphamaplus.com/wp-content/uploads/2016/05/A021-2-468x468.jpg" data-href="http://myphamaplus.com/wp-content/uploads/2016/05/A021-2.jpg" class="zoom" alt="A021-2" itemprop="image" content="http://myphamaplus.com/wp-content/uploads/2016/05/A021-2.jpg" /><img class="ms-thumb" alt="A021-2" src="http://myphamaplus.com/wp-content/uploads/2016/05/A021-2-90x90.jpg" />        </div>
                <div class="ms-slide">
            <img src="http://myphamaplus.com/wp-content/uploads/2016/05/A021-468x468.jpg" data-href="http://myphamaplus.com/wp-content/uploads/2016/05/A021.jpg" class="zoom last" alt="A021" itemprop="image" content="http://myphamaplus.com/wp-content/uploads/2016/05/A021.jpg" /><img class="ms-thumb" alt="A021" src="http://myphamaplus.com/wp-content/uploads/2016/05/A021-90x90.jpg" />        </div>
        
    </div>
    <!-- END MasterSlider Main -->


</div>
<!-- END MasterSlider -->


            </div>

            <div class="col-sm-7 summary entry-summary">
                <h1 itemprop="name" class="product_title entry-title">'.uh($v['title']).'</h1>
'.($v['code'] != "" ? '<span class="sku_wrapper">Mã: <span class="sku" itemprop="sku">'.$v['code'].'</span></span>' : '').'

<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">

	<p class="price">
'.($v['price2'] > 0 ? '<ins><span class="woocommerce-Price-amount amount">'.Yii::$app->zii->showPrice($v['price2'],$v['currency']).'</span></ins>' : '').'
'.($v['price1'] > 0 && $v['price1'] > $v['price2'] ? '<del><span class="woocommerce-Price-amount amount">'.Yii::$app->zii->showPrice($v['price1'],$v['currency']).'</span></del>' : '') .'

</p>
';
	if($v['price2']>0){
		Yii::$app->view->registerMetaTag([
				'itemprop'=>"price" ,
				'content' =>$v['price2']
		]);
		Yii::$app->view->registerMetaTag([
				'itemprop'=>"priceCurrency" ,
				'content' => Yii::$app->zii->showCurrency($v['currency'],1)
		]);
		Yii::$app->view-> registerLinkTag([
				'itemprop'=>"availability" ,
				'href'=>"http://schema.org/InStock"
		]);
	}
	echo '
	 
	<link itemprop="availability" href="http://schema.org/InStock" />

</div>
<div class="description" itemprop="description">
	<div class="item-info">'.uh($v['info']).'</div>
<div class="item-summary">'.uh($v['summary']).'</div>
</div>

<form class="cart" method="post" enctype=\'multipart/form-data\'>

	 	<input type="hidden" id="wc_quick_buy_hook_'.$v['id'].'" value="'.$v['id'].'"  />


	 	<div class="quantity">
<input type="number" step="1" min="1"  name="quantity" value="1" title="SL" class="input-text cart-item-quantity-'.$v['id'].' qty text" /></div>
 <button data-id="'.$v['id'].'" onclick="return add_to_cart(this);" type="button" class="single_add_to_cart_button button alt">Thêm vào giỏ</button>

 </form>

	<div style="width: 100%;float: left;margin-bottom: 25px;">

		<form data-action="sajax" class="form-inline add_phone_to_backend" method="post" action="" onsubmit="return ajaxSubmitForm(this);">
<input type="hidden" name="_csrf-frontend" value="'.Yii::$app->request->csrfToken.'"  />
<input type="hidden" name="action" value="call_me_now"  />
			<div class="form-group" style="float: left;margin-right: 20px;">

			   

			    <input type="tel" minlength="9" required="required" class="form-control add-phone" name="f[phone]" id="exampleInputName2" placeholder="Số điện thoại">
			     
			    <input type="hidden" id="name_product" name="name_product" value="">

			    <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-earphone"></i> Gọi lại cho tôi</button>

	  			<img class="image_load" width="25" style="margin-left: 5px; display:none;"  src="'.__RSDIR__.'/images/loading.gif">

	  			<span class="add_complete" style="color: green;font-size: 23px; display:none;"><i class="fa fa-check"></i></span>

	  		</div>

  		</form><div class="clear"></div>';
echo '<div class="quick-form-support">';
	foreach(Yii::$app->zii->getSupports() as $k1=>$v1){
		echo '<div class="qsupport clear">
<div class="skypeadd"><a title="Skype " href="skype:'.$v1['skype'].'"><img style="height:32px" alt="Skype" src="'.__RSDIR__.'/images/skype.png"></a></div>
<div class="sp-name">'.uh($v1['title']).' - <a class="bold" href="tel:'.unMark($v1['phone'],'').'">'.$v1['phone'].'</a>'.'</div>
 
</div>';
	}
	echo '</div>	';
echo '

		


	</div>		<div class="clear"></div>



	
 
<div class="share-links">
<a href="http://www.facebook.com/sharer.php?u='.FULL_URL.'&amp;text='.uh($v['title']).'&amp;images='.$v['icon'].'" target="_blank" rel="nofollow" data-toggle="tooltip" data-placement="bottom" title="Facebook" class="share-facebook">Facebook</a>
<a href="https://twitter.com/intent/tweet?text='.uh($v['title']).'&amp;url='.FULL_URL.'" target="_blank" rel="nofollow" data-toggle="tooltip" data-placement="bottom" title="Twitter" class="share-twitter">Twitter</a>
<a href="https://www.linkedin.com/shareArticle?mini=true&amp;url='.FULL_URL.'&amp;title='.uh($v['title']).'" target="_blank" rel="nofollow" data-toggle="tooltip" data-placement="bottom" title="LinkedIn" class="share-linkedin">LinkedIn</a>
<a href="https://plus.google.com/share?url='.FULL_URL.'" target="_blank" rel="nofollow" data-toggle="tooltip" data-placement="bottom" title="Google +" class="share-googleplus">Google +</a>
 
</div></div>
        </div>
    </div><!-- .summary -->

	
	<div class="row">
		<div class="main-content col-md-9">
			<div class="woocommerce-tabs woocommerce-tabs-sm4xjmwl0g79y1simqwh5rr9x6iqg2m" id="product-tab">
				<ul class="resp-tabs-list">';
	if(!empty($v['ctab'])){
		 
		foreach($v['ctab'] as $d=>$t){
			echo '<li  aria-controls="tab-" role="presentation" class="'.($d == 0 ? 'active' : '').'"><a aria-controls="tab-description-'.$d.'" role="tab" data-toggle="tab" href="#tab-description-'.$d.'">'.uh($t['title'],2).'</a></li>';
		}
	}
		
					
echo '</ul>
				<div class="resp-tabs-container">';
if(!empty($v['ctab'])  ){
	//echo '<div class="tab-content tabs-detail">';
	foreach($v['ctab'] as $d=>$t){
		echo '<div role="tabpanel" class="tab-pane tab-content active f14p" id="tab-description-'.$d.'" >'.uh($t['text'],2).'</div>';
	}
	//echo '</div>';
}
echo '					
 			
									</div>
			</div>
            <div class="woocommerce-tabs woocommerce-tabs-sm4xjmwl0g79y1simqwh5rr9x6iqg2m" id="product-tab">
                <ul class="resp-tabs-list">
                    <li aria-controls="tab-1">
                                Đánh giá (0)                            </li>                    <li aria-controls="tab-2">
                        Đánh giá Facebook
                    </li>
                </ul>
                <div class="resp-tabs-container">
                			                        <div class="tab-content" id="tab-1">
	                            <div id="reviews">
	<div id="comments">
		<p class="f14e fw400 lh15 mgb15">Đánh giá</p>

		
			<p class="woocommerce-noreviews">Chưa có đánh giá nào.</p>

			</div>

    <hr class="tall">

	
		<div id="review_form_wrapper">
			<div id="review_form">
					<div id="respond" class="comment-respond">
		<p id="reply-title" class="comment-reply-title">Đánh giá về sản phẩm &ldquo;'.uh($v['title']).'&rdquo; <small><a rel="nofollow" id="cancel-comment-reply-link" href="/sp/spphan-nen-khong-troi-sieu-min-va-nuoi-duong-da#respond" style="display:none;">Hủy</a></small></p>			
<form action="" method="post" id="commentform" class="comment-form">
				<p class="comment-form-comment"><label for="comment">Đánh giá của bạn</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p><p class="comment-form-author"><label for="author">Tên <span class="required">*</span></label> <input id="author" name="author" type="text" value="" size="30" aria-required="true" /></p>
<p class="comment-form-email"><label for="email">Email <span class="required">*</span></label> <input id="email" name="email" type="text" value="" size="30" aria-required="true" /></p>
<p class="form-submit"><input name="submit" type="submit" id="submit" class="submit" value="Gửi đi" /> <input type=\'hidden\' name=\'comment_post_ID\' value=\''.$v['id'].'\' id=\'comment_post_ID\' />
<input type=\'hidden\' name=\'comment_parent\' id=\'comment_parent\' value=\'0\' />
</p>			</form>
			</div><!-- #respond -->
				</div>
		</div>

	
	<div class="clear"></div>
</div>	                        </div>
	                                            <div class="tab-content" id="tab-2">
                            <span>Đánh giá trên Facebook</span>
                            <div class="fb-comments" data-href="'.FULL_URL.'" data-colorscheme="light" data-numposts="5" data-width="100%"></div>
                        </div>
                </div>
                
                <div class="mua-ngay">Hãy cùng Click Mua ngay để nhận giá hấp dẫn bạn nhé !
                    <h4>'.uh($v['title']).'</h4>
                    <div class="quick_buy_container quick_buy_'.$v['id'].'_container" id="quick_buy_'.$v['id'].'_container" >
<button data-id="'.$v['id'].'" data-amount="1" data-role="buynow" onclick="add_to_cart(this)" type="button" type="button" id="quick_buy_'.$v['id'].'_button" name=""  data-product-type="simple" data-product-id="'.$v['id'].'"  class="btn btn-info wcqb_button wc_quick_buy_button quick_buy_button quick_buy_simple quick_buy_'.$v['id'].'_button quick_buy_'.$v['id'].'quick_buy_button" ><i class="glyphicon glyphicon-hand-right"></i> Mua ngay</button></div>                </div>
            </div>
		</div>';
include_once '_rsidebar.php';		
	echo '</div>
    

 



	<div class="related products">

		<p class="slider-title"><span class="inline-title">Sản phẩm liên quan</span><span class="line"></span></p>

        <div class="slider-wrapper">

            <ul class="products  products-slider  pcols-lg-5 pcols-md-4 pcols-xs-2 pcols-ls-1  pwidth-lg-5 pwidth-md-4 pwidth-xs-2 pwidth-ls-1" data-cols-lg="5" data-cols-md="4" data-cols-xs="2" data-cols-ls="1">';
$l = Yii::$app->zii->getArticles(['p'=>getParam('p',1),'type'=>'products','other'=>$v['id'] ,'category'=>__CATEGORY_ID__ ,'key'=>'limit-products-rate','count'=>false]);
if(!empty($l['listItem'])){
	foreach($l['listItem'] as $k=>$v){
    $link = isset($v['url_link']) ? $v['url_link'] : cu([DS . $v['url']]);            
echo '<li class="show-links-onimage first pcols-lg-first pcols-md-first pcols-xs-first product type-product status-publish has-post-thumbnail instock shipping-taxable purchasable product-type-simple">

	<a href="'.$link.'" class="woocommerce-LoopProduct-link">
    <div class="product-image">
        <a href="'.$link.'">
            <div class="labels">
<div data-link="/cart" class="viewcart  viewcart-4296" title="Xem giỏ hàng"></div></div>
<div class="inner img-effect">
'.getImage(['src'=>$v['icon'], 'w'=>300,'h'=>300,'attrs'=>[
				'class'=>" wp-post-image",
				'alt'=>$v['title']
		]]).'


</div>        </a>

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
'.($v['price2'] > 0 ? '<ins><span class="woocommerce-Price-amount amount">'.Yii::$app->zii->showPrice($v['price2'],$v['currency']).'</span></ins>' : '').'</span>
    </a><div class="add-links-wrap">
    <div class="add-links  clearfix">
        <a href="#" rel="nofollow" data-id="'.$v['id'].'" data-amount="1" onclick="return add_to_cart(this);" class="button add_to_cart_button product_type_simple">Thêm vào giỏ</a>
						<div class="quickview " data-id="'.$v['id'].'" title="Xem Nhanh"><a class="white" href="'.$link.'">Xem Nhanh</a></div>   
</div>
</div>
</li>';
	}
}
            
                   
            
echo '</ul></div></div>
 

</div><!-- #product-'.$v['id'].' -->


		
	</div></div>
	

</div><!-- end main content -->


    </div>
</div>';
}
?>


<?php 
$this->registerJs('
        
            var product_image_slider = new MasterSlider();

            var $image_slider = jQuery(\'#product-image-slider-i28e6r8cotwis4ttv8n4p5kdfpj2jt6\');
            $image_slider.data(\'imageSlider\', product_image_slider);
            var $product_thumbs = $image_slider.find(\'.ms-thumb\');
            var ms_view = \'#product-image-slider-i28e6r8cotwis4ttv8n4p5kdfpj2jt6 .ms-view\';

                        var zoomConfig = {
                zoomContainer: ms_view,
                responsive: true,
                zoomWindowFadeIn: 300,
                zoomType: \'inner\',
                                borderSize: 0,
                cursor: \'grab\'
            };
            
            // slider setup
            product_image_slider.setup("product-image-slider-i28e6r8cotwis4ttv8n4p5kdfpj2jt6", {
                layout          : "fillwidth",
                loop            : true,
                autoHeight      : true,
                overPause       : true,
                centerControls  : false,
                speed           : 35,
                preload         : 0,
                parallaxMode    : \'swipe\',
                layersMode      : \'full\'
            });

            // slider controls
            product_image_slider.control(\'arrows\' ,{ autohide:true });
                        product_image_slider.control(\'thumblist\', { autohide:false, margin:0, width:100, height:100, space:8, speed:40 });
                                    product_image_slider.control(\'lightbox\');
            
            var sliderLoaded = false;
            var zoomLoading = false;

                        function initProductImageZoom() {
                if (zoomLoading || !sliderLoaded || typeof product_image_slider.api.view == \'undefined\' || product_image_slider.api.view.currentSlide == null) return;

                zoomLoading = true;
                var curSlide = product_image_slider.api.view.currentSlide;
                var imgCont = curSlide.$imgcont;
                jQuery(ms_view).find(\'.zoomContainer\').remove();
                var zoomImg = imgCont.find(\'.zoom\');
                var image = new Image();
                var zoomImgSrc = zoomImg.attr( \'src\' );
                image.onload = function() {
                    zoomConfig.onZoomedImageLoaded = function() {
                        var elevateZoom = zoomImg.data(\'elevateZoom\');
                        elevateZoom.swaptheimage(zoomImgSrc, zoomImgSrc);
                        zoomLoading = false;
                    };
                    zoomImg.elevateZoom(zoomConfig);
                };
                image.src = zoomImgSrc;
            }
            
            function initProductImageLightBox() {
                $image_slider.find(\'.ms-lightbox-btn\').click(function() {
                    var links = [];
                    var i = 0;
                    var $image_links = $image_slider.find(\'.zoom\');
                    $image_links.each(function() {
                        var slide = {};
                        var $image = jQuery(this);
                        slide.title = $image.attr(\'alt\');
                        if ($image.attr(\'href\'))
                            slide.href = $image.attr(\'href\');
                        else
                        slide.href = $image.attr(\'data-href\');
                        slide.thumbnail = $image.attr(\'src\');
                        links[i] = slide;
                        i++;
                    });
                    var curSlide = product_image_slider.api.view.currentSlide;
                    var imgCont = curSlide.$imgcont;
                    var curImage = imgCont.find(\'.zoom\');
                    var options = {index: $image_links.index(curImage)};
                    blueimp.Gallery(links, options);
                });
            }

            product_image_slider.api.addEventListener(MSSliderEvent.INIT, function() {
                sliderLoaded = true;
                setTimeout(function() {
                    product_image_slider.slideController.locate();
                }, 600);
                                initProductImageZoom();
                                initProductImageLightBox();
                $image_slider.find(\'.ms-thumblist-fwd\').unbind(\'click\').click(function() {
                    product_image_slider.api.next();
                });
                $image_slider.find(\'.ms-thumblist-bwd\').unbind(\'click\').click(function() {
                    product_image_slider.api.previous();
                });

                                product_image_slider.api.view.addEventListener(MSViewEvents.SWIPE_START, function() {
                    jQuery(ms_view).find(\'.zoomContainer\').remove();
                    zoomLoading = false;
                });

                product_image_slider.api.view.addEventListener(MSViewEvents.SWIPE_CANCEL, function() {
                    initProductImageZoom();
                });
                            });

                        product_image_slider.api.addEventListener(MSSliderEvent.CHANGE_END, function() {
                initProductImageZoom();
            });
            
            product_image_slider.api.addEventListener(MSSliderEvent.RESIZE, function() {
                                initProductImageZoom();
                                var $product_thumb = jQuery($product_thumbs.get(0));
                var thumb_padding = parseInt($product_thumb.closest(\'.ms-thumb-frame\').css(\'border-left-width\')) + parseInt($product_thumb.closest(\'.ms-thumb-frame\').css(\'padding-left\'));
                var thumb_width = parseInt(($image_slider.width() - 24 - thumb_padding * 6) / 4) - 0.01;
                $product_thumbs.css(\'width\', thumb_width);
            });
      

    ');

$this->registerJs('jQuery(\'.woocommerce-tabs-sm4xjmwl0g79y1simqwh5rr9x6iqg2m\').easyResponsiveTabs({
                type: \'default\', //Types: default, vertical, accordion
                width: \'auto\', //auto or any width like 600px
                fit: true,   // 100% fit in a container
                closed: \'accordion\', // Start closed if in accordion view
                activate: function(event) { // Callback function if tab is switched

                }
            });

                        // go to reviews, write a review
            jQuery(\'.woocommerce-review-link, .woocommerce-write-review-link\').click(function(e) {
                var recalc_pos = false;
                if (jQuery(\'#content #tab-reviews\').css(\'display\') != \'block\') {
                    recalc_pos = true;
                }
                if (jQuery("h2[aria-controls=tab_item-0]").length && jQuery("h2[aria-controls=tab_item-0]").next().css(\'display\') == \'none\')
                    jQuery("h2[aria-controls=tab_item-0]").click();
                else if (jQuery("li[aria-controls=tab_item-0]").length && jQuery("li[aria-controls=tab_item-0]").next().css(\'display\') == \'none\')
                    jQuery("li[aria-controls=tab_item-0]").click();

                var target = jQuery(this.hash);
                if (target.length) {
                    e.preventDefault();

                    var delay = 1;
                    if (jQuery(window).scrollTop() < theme.StickyHeader.sticky_pos) {
                        delay += 250;
                        jQuery(\'html, body\').animate({
                            scrollTop: theme.StickyHeader.sticky_pos + 1
                        }, 200);
                    }
                    setTimeout(function() {
                        jQuery(\'html, body\').stop().animate({
                            scrollTop: target.offset().top - theme.StickyHeader.sticky_height - theme.StickyHeader.adminbar_height - 14
                        }, 600, \'easeOutQuad\');
                    }, delay);

                    return false;
                }
            });
            // Open review form lightbox if accessed via anchor
            if ( window.location.hash == \'#review_form\' || window.location.hash == \'#reviews\' || window.location.hash.indexOf(\'#comment-\') != -1 ) {
                setTimeout(function() {
                    if (jQuery("h2[aria-controls=tab_item-0]").next().css(\'display\') == \'none\') {
                        jQuery("h2[aria-controls=tab_item-0]").click();
                        var target = jQuery(window.location.hash);
                        if (target.length) {
                            var delay = 1;
                            if (jQuery(window).scrollTop() < theme.StickyHeader.sticky_pos) {
                                delay += 250;
                                jQuery(\'html, body\').animate({
                                    scrollTop: theme.StickyHeader.sticky_pos + 1
                                }, 200);
                            }
                            setTimeout(function() {
                                jQuery(\'html, body\').stop().animate({
                                    scrollTop: target.offset().top - theme.StickyHeader.sticky_height - theme.StickyHeader.adminbar_height - 14
                                }, 600, \'easeOutQuad\');
                            }, delay);
                        }
                    }
                }, 200);
            }');
?>