<div class="post-content">
<?php 
 
$lb = $this->app()->getBoxIndex();
if(!empty($lb)){
	foreach ($lb as $kb=>$vb){ //$vb['style'] = isset($vb['style']) ? $vb['style'] : 0;
		$link_box = $vb['box']['menu_id'] > 0 ? cu(false,false,array('category_id'=>$vb['box']['menu_id'])) : '#';
		$b = $vb['box'];
		echo '<div class="box-'.$vb['box']['code'].'-background box-index f12e boxstyle-'.$vb['box']['style'].' index-box-'.$vb['box']['id'].'">';
		switch ($vb['box']['style']){
			case 1:
				echo '<div class="fusion-fullwidth fullwidth-box fusion-fullwidth-1  fusion-parallax-none nonhundred-percent-fullwidth" >
<div class="fusion-row"><div class="fusion-one-full fusion-layout-column fusion-column-last fusion-spacing-yes"  id="introduce-home">
<div class="fusion-column-wrapper center">

<div class="fusion-title title fusion-sep-none fusion-title-center fusion-title-size-two fusion-border-below-title center">
<h2 class="title-heading-center center" data-fontsize="24" data-lineheight="35">'.uh($vb['box']['title']).'</h2>
</div>
'.uh($vb['box']['text'],2).'

<div class="fusion-clearfix"></div></div></div><div class="fusion-clearfix"></div></div>

</div>';
				//
				break;
			case 14:
				echo '<div class="fusion-fullwidth fullwidth-box fusion-fullwidth-2  fusion-parallax-none nonhundred-percent-fullwidth" >
<div class="fusion-row">
<div class="fusion-one-full fusion-layout-column fusion-column-last fusion-spacing-yes" style="margin-top:0px;margin-bottom:20px;">

<div class="fusion-column-wrapper">
<div class="fusion-title title fusion-sep-none fusion-title-center fusion-title-size-two fusion-border-below-title" style="margin-bottom:0px;">
<h2 class="title-heading-center" data-fontsize="24" data-lineheight="35">'.uh($vb['box']['title']).'</h2></div>
<div class="fusion-sep-clear"></div>
<div class="fusion-separator fusion-full-width-sep sep-none" style="border-color:#e0dede;margin-left: auto;margin-right: auto;margin-top:30px;"></div>
<div class="fusion-content-boxes content-boxes columns fusion-columns-3 fusion-columns-total-3 fusion-content-boxes-1 content-boxes-icon-with-title row content-left" data-animationoffset="100%" style="margin-top:0px;margin-bottom:60px;">
';
				if(!empty($vb['listItem'])){
					foreach ($vb['listItem'] as $k=>$v){
						$link = cu([DS.$v['url']]);
						echo '<div class="center fusion-column content-box-column content-box-column-1 col-lg-4 col-md-4 col-sm-4 fusion-content-box-hover content-box-column-first-in-row">
<div class="col content-wrapper link-area-link-icon link-type-text icon-hover-animation-fade" style="background-color:transparent;">
<div class="heading heading-with-icon icon-left"><a class="heading-link" href="'.$link.'" >
<div class="image">'.getImage([
		'src'=>$v['icon'],
		'w'=>250,'h'=>250
]).'</div>
<h2 class="content-box-heading" style="font-size: 18px;line-height:23px;" data-inline-fontsize="true" data-inline-lineheight="true" data-fontsize="18" data-lineheight="23">'.uh($v['title']).'</h2></a></div>
<div class="fusion-clearfix"></div>
<div class="content-container center">
'.uh($v['info'],2).'
</div></div></div>';
					}
				}
echo '<div class="fusion-clearfix"></div><div class="fusion-clearfix"></div></div>
<div class="fusion-clearfix"></div>
</div>

</div><div class="fusion-clearfix"></div></div></div>';
				break;
				
			case 4: // Doi ngu giao vien
				echo '<div class="fusion-fullwidth fullwidth-box fusion-fullwidth-3 nonhundred-percent-fullwidth" style=""><div class="fusion-row"><div class="fusion-one-full fusion-layout-column fusion-column-last fusion-spacing-yes" style="margin-top:0px;margin-bottom:20px;">
<div class="fusion-column-wrapper">';
				echo '<div class="fusion-title title fusion-sep-none fusion-title-center fusion-title-size-two" style="margin-bottom:0px;"><h2 class="title-heading-center upper">
<a class="white" '.(isset($vb['box']['make_url']) && $vb['box']['make_url'] == 'on' ? 'href="'.$vb['box']['url_link'].'"' : '').'>'.uh($vb['box']['title']).'</a></h2></div><div class="fusion-sep-clear"></div>';
				echo '</div></div><div class="fusion-clearfix"></div></div></div>';
				
				echo '<div class="fusion-row"><div class="box-product cf-wrapper slick-slidersss fusion-posts-teacher" data-show="4" data-scroll="4" data-dots="true" data-rows="1" data-prevArrow="" data-nextArrow="">';
				
				//$adverts = $this->app()->getAdvert(array('code'=>'coach' ));
				$adverts = Yii::$app->zii->getMembers([
						'type_id'=>TYPE_ID_TEA
				]);
//				view()
				if(!empty($adverts['listItem'])){
					foreach ($adverts['listItem'] as $adv){
						$link = isset($adv['url_link']) ? $adv['url_link'] : '#';
						echo '<div class="item"><div class="col-sm-12 post fusion-carousel-item fusion-post-grid fusion-carousel-item post-308 fusion-carousel-item post-k-teacher fusion-carousel-item type-post-k-teacher fusion-carousel-item status-publish fusion-carousel-item has-post-thumbnail fusion-carousel-item hentry" >
						<div class="post-inner">
						<div class="entry-thumb">
						<a href="'.$link.'" title="'.uh($adv['name']).'">
						<img src="'.getImage(array('src'=>$adv['icon'],'w'=>500,'output'=>'src')).'" class="attachment-rt_thumb300x400 size-rt_thumb300x400 wp-post-image" alt="" /></a></div>
						<h3 class="entry-title"><a href="'.$link.'" title="'.uh($adv['name']).'">'.uh($adv['name']).'</a></h3>
						<div class="entry-manifesto"><p>'.uh($adv['info']).'</p>
</div></div></div></div>';
					}
				}

			
 
				echo '</div></div>'; 
				break;
			case 15: // Hoạt động
				$link_box = $vb['box']['menu_id'] > 0 ? cu(false,false,array('category_id'=>$vb['box']['menu_id'])) : '#';
				echo '<div class="fusion-fullwidth fullwidth-box fusion-fullwidth-4 fusion-parallax-none nonhundred-percent-fullwidth" >

<div class="fusion-row">
<div class="fusion-title title fusion-sep-none fusion-title-center fusion-title-size-two fusion-border-below-title"><a href="'.$link_box.'" class="atitle">
<h2 class="title-heading-center upper mgt30 mgb15" data-fontsize="24" data-lineheight="35">'.uh($vb['box']['title']).'</h2></a></div>
<div class="fusion-sep-clear"></div>
<div class="fusion-separator fusion-full-width-sep sep-none"></div>
<div class="fusion-blog-shortcode fusion-blog-shortcode-1 fusion-blog-archive fusion-blog-layout-grid-wrapper fusion-blog-pagination">

<div class="s-slick-slider slide-hoatdong" data-items="3" data-dots="true">';

  
if(!empty($vb['listItem'])){
	foreach ($vb['listItem'] as $k=>$v){
		$link = cu([DS.$v['url']]);
	
  
echo '<div class="item" ><div class="fusion-post-wrapperx col-sm-12">			
 
<div class="slidesx">
																									
		<div class="flex-active-slides"  >
						
		<div class="fusion-image-wrapper" aria-haspopup="true">
 	
		'.getImage([
				'src'=>$v['icon'],
				'alt'=>uh($v['title']),'w'=>500,
				//'img_attr'=>['class'=>'auto_rz_4x3 img ']
		]).'
<div class="fusion-rollover">
<div class="fusion-rollover-content">
<a class="fusion-rollover-link" href="'.$link.'">Permalink</a>
					
 
<a class="fusion-rollover-gallery popup_colorbox" href="'.getImage([
				'src'=>$v['icon'],'output'=>'src',
				'alt'=>uh($v['title']),'w'=>0
		]).'" data-rel="group1" data-title="'.uh($v['title']).'" data-caption="">Gallery</a>															
<h4 class="fusion-rollover-title" data-fontsize="18" data-lineheight="22"><a class="upper white hide " href="'.$link.'">'.uh($v['title']).'</a></h4>
 				
				
				 
		</div>
		</div>					</div>
														</div>
			 </div>
			<div class="fusion-post-content-wrapper"><div class="fusion-post-content post-content">
		<h2 class="entry-title2 center" data-fontsize="15" data-lineheight="22"><a class="center upper" href="'.$link.'">'.uh($v['title']).'</a></h2> 
				
				</div></div></div><!--col-->
</div><!--item-->';

}
}
 

echo '</div><div class="clear"></div>
</div></div></div>';
				
				break;
			case 8: // news
				
				echo '<div class="clear"></div><div class="ibox ibox9  "><div class="container"><div class="row"><p class="box-title upper bold center">'.uh($b['title']).'</p>';
				 
				if(!empty($vb['listItem'])){
					echo '<div class="box-content col-xs-12 col-sm-6">';
					$v = $vb['listItem'][0];$url = cu([DS . $v['url']]);
					//
					echo '<div class="list-news-01"><div class="fl100 pr big-item">
							
								<a href="'.$url.'">
								<span class="big-item-img ">'.getImage(['src'=>$v['icon'],'w'=>600,'alt'=>uh($v['title']),'attrs'=>['class'=>'w100']]).'</span>
								<h2 class="big-item-name big-item-title">'.uh($v['title']).'</h2>	</a>
								<p class="big-item-post-by">'.($v['post_by_name'] != "" ? 'by '.$v['post_by_name'] : '').'  '.'<span class="item-time"> - '.date("d/m/Y",strtotime($v['time'])).'</span></p>
								<p class="big-item-description">'.uh($v['info']).'</p>
<p class="big-item-description big-item-summary">'.uh($v['summary']).'</p>
								<a href="" class="big-item-read-more">'.getTextTranslate(34).'</a>
						</div>';
					//
					echo '</div></div>';
					
					echo '<div class="box-content col-xs-12 col-sm-6 "><div class="list-news-01">';
					foreach ($vb['listItem'] as $k=>$v){
						if($k>0){
						$url = cu([DS . $v['url']]);
						echo '<div class="fl100 pr item">
								
								<a href="'.$url.'">
								<span class="item-img ">'.getImage(['src'=>$v['icon'],'w'=>200,'alt'=>uh($v['title']),'attrs'=>['class'=>'w100']]).'</span>
								<h2 class="item-name item-title">'.uh($v['title']).'</h2>	</a>
								<p class="item-post-by">'.($v['post_by_name'] != "" ? 'by '.$v['post_by_name'] : '').'  '.'<span class="item-time"> - '.date("d/m/Y",strtotime($v['time'])).'</span></p>
								<p class="item-description">'.uh($v['info']).'</p>
								<a href="'.$url.'" class="item-read-more">'.getTextTranslate(34).'</a>
						</div>';
						}
					}
					echo '</div></div>';
					 
					
				}
				echo '</div></div></div></div><div class="clear"></div>';
				
				break;
			case 16: // Testimonial
				
				echo '<div class="fusion-fullwidth fullwidth-box fusion-fullwidth-5 fusion-parallax-none nonhundred-percent-fullwidth" >

<div class="fusion-row">
<div class="fusion-title title fusion-sep-none fusion-title-center fusion-title-size-two fusion-border-below-title">
<h2 class="title-heading-center upper mgb15" data-fontsize="24" data-lineheight="35">'.uh($vb['box']['title']).'</h2></div>
<div class="fusion-sep-clear"></div>
<div class="fusion-separator fusion-full-width-sep sep-none"></div>
<div class="fusion-blog-shortcode fusion-blog-shortcode-1 fusion-blog-archive fusion-blog-layout-grid-wrapper fusion-blog-pagination">

<div class="s-slick-slider slide-hoatdong" data-items="3" data-dots="true">';

  
if(!empty($vb['listItem'])){
	foreach ($vb['listItem'] as $k=>$v){
		$link = cu([DS.$v['url']]);
	
  
echo '<div class="item" ><div class="fusion-post-wrapperx col-sm-12">';		
 
echo '<div class="fusion-layout-column fusion-spacing-yes"><div class="fusion-column-wrapper">
		<div class="fusion-testimonials clean fusion-testimonials-2" data-random="0">
		<div class="reviews"><div class="review avatar-image" style="display: block;">
		<div class="testimonial-thumbnail">'.getImage([
				'src'=>$v['icon'],
				'w'=>120,'h'=>120,
				'img_attr'=>[
						'class'=>'testimonial-image',
				]
		]).'
		</div>
		<blockquote style="background-color:;"><q style="background-color:;color:;">
						<span class="testi-word-enable">“'.uh($v['info']).'</span>
						<span onclick="showMoreTestimonial(this)" class="testi-more"> Xem thêm</span><span class="testi-word-disable">'.uh($v['summary']).'”</span></q></blockquote>
						
						<div class="author" style="color:;"><span class="company-name"><strong>'.uh($v['title']).'</strong>, <span>'.uh($v['short_title']).'</span></span></div></div></div></div><div class="fusion-clearfix"></div></div></div>'; 
echo '</div></div><!--item-->';

}
}
 

echo '</div><div class="clear"></div>
</div></div></div></div>';
				
				break;	
				
				
		}
		echo '</div>';
	}
}
?>
 
 






</div>