<?php 
if(isset(Yii::$_category['icon']) && Yii::$_category['icon'] != ""){
	echo '<div class="fusion-page-title-bar center fusion-page-title-bar-none fusion-page-title-bar-left">
	<div class="fusion-page-title-row container">
	<div class="fusion-page-title-wrapper">
	<div class="fusion-page-title-captions">'.getImage([
			'src'=>Yii::$_category['icon'],'w'=>1300
	]).'	
	</div>
	
	</div>
	</div>
	</div>';
}
echo '<div class="fusion-page-title-row container"><h1 class="entry-title" data-fontsize="30" data-lineheight="70">
'.showFirstTitle(__CATEGORY_NAME__,2).'</h1>
</div>';

echo '<div class="container">';
echo '<div class="row">';
echo '<div class="col-sm-12 col-xs-12">';
$lb = Yii::$app->zii->getArticles(['category'=>0, 'type'=>FORM_TYPE_COURSES,'key'=>'limit-courses-testimonials']);
 
if(!empty($lb['listItem'])){
	foreach ($lb['listItem'] as $kb=>$vb){
		$l = Yii::$app->zii->getArticles(['category'=>__CATEGORY_ID__,'key'=>'limit-testimonials','course_id'=>$vb['id'] ]); 
		if(!empty($l['listItem'])){
		
		echo '<p class="rate-title2 rate-title upper mgb30" data-fontsize="24" data-lineheight="35">'.uh($vb['title']).'</p>
<div class="fusion-blog-shortcode fusion-blog-shortcode-1 fusion-blog-archive fusion-blog-layout-grid-wrapper fusion-blog-pagination">
				
<div class="s-slick-slider testimonial100" data-items="3" data-dots="true">';
		
			foreach ($l['listItem'] as $k=>$v){
				$link = cu([DS.$v['url']]);
				
				
				echo '<div class="item" ><div class="fusion-post-wrapperx col-sm-12">';
				
				echo '<div class="fusion-layout-column fusion-spacing-yes"><div class="fusion-column-wrapper">
		<div class="fusion-testimonials clean fusion-testimonials-2" data-random="0">
		<div class="reviews"><div class="review avatar-image" style="display: block;">
		<div class="testimonial-thumbnail testimonial-thumbnail100"><a href="'.$link.'">'.getImage([
				'src'=>$v['icon'],
				'w'=>100,'h'=>100,
				'img_attr'=>[
						'class'=>'testimonial-image',
				]
		]).'</a>
		</div>
		<blockquote style="background-color:;"><q style="background-color:;color:;">
						<span class="testi-word-enable">“'.uh($v['info']).'</span>
		<a href="'.$link.'" class="testi-more"> '.getTextTranslate(34).'</a><span class="testi-word-disable">'.uh($v['summary']).'”</span></q></blockquote>
				
						<div class="author" style="color:;"><a href="'.$link.'" class="company-name"><strong>'.uh($v['title']).'</strong>, <span>'.uh($v['short_title']).'</span></a></div></div></div></div><div class="fusion-clearfix"></div></div></div>';
		echo '</div></div><!--item-->';
		
			}
		
		
		
		echo '</div><div class="clear"></div></div>';
		}
	}
}

echo '</div></div></div>';