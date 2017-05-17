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
echo '<div class="col-sm-9 col-xs-12">';
$l = Yii::$app->zii->getArticles(['category_id'=>CONTROLLER_ID,'detail'=>__IS_DETAIL__]);
$v = __IS_DETAIL__ ? $l : (isset($l['listItem'][0]) ? $l['listItem'][0] : []);
 
if(!empty($v)){
	echo '<div class="post-content">
<div class="fusion-one-full fusion-layout-column fusion-column-last fusion-spacing-yes" >
<div class="fusion-column-wrapper">'.(uh($v['title']) != "" ? '<blockquote class=" ">
<h3><span ><strong>'.uh($v['title']).'</strong></span>'.($v['short_title'] != "" ? '<span class="flxc">, '.  uh($v['short_title']) .'</span>' : '').'</h3> 

</blockquote>' : '').'
 ';
if(!empty($v['ctab'])){
	foreach($v['ctab'] as $d=>$t){
		echo '<div class="box-details">'.Yii::$app->zii->showTextDetail(uh($t['text'],2)).'</div>';
	}
}
echo '<div class="fusion-clearfix"></div></div></div></div>';


$l = Yii::$app->zii->getArticles(['category'=>__CATEGORY_ID__,'key'=>'limit-testimonials-rate','other'=>$v['id']]);
 
echo ' 
<p class="rate-title upper mgb15 mgt30" data-fontsize="24" data-lineheight="35">'.uh($l['box']['title']).'</p> 
<div class="fusion-blog-shortcode fusion-blog-shortcode-1 fusion-blog-archive fusion-blog-layout-grid-wrapper fusion-blog-pagination">
		
<div class="s-slick-slider testimonial100 slide-hoatdong" data-items="3" data-dots="true">';
if(!empty($l['listItem'])){
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
}


echo '</div><div class="clear"></div></div>';
}
echo '</div>';
include_once '_rsidebar.php';

echo '</div>';
echo '</div>';
?>		

