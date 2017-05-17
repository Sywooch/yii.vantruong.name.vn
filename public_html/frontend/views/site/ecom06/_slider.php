<div id="cphMain_ctl00_TopPane" class="content_right col-sm-9">
<div id="TextHTML-Module" class="DefaultModule">
     
<div class="b-main b-main_home">
<div class="b-main-container ">
<div class="middle-container">
<div class="middle col-1-layout">
<div class="b-layout b-layout_full">
<div class="col-main b-content" id="main" role="main">
<div class="b-main_home-banner clearfix">
<div class="b-main_home-banner-box" style="z-index: 1; overflow: hidden; position: relative;">
<ul class="b-main_home-banner__list js-slide-nav1" style="z-index: 101;">
</ul>

<div class="b-main_home-banner__image js-slide-list1" style="width: 100%; top: -390px;">
<div id="acsux-hero">
<?php 
$l = Yii::$app->zii->getAdvert(['code'=>'ADV_SLIDER','category_id'=>__CATEGORY_ID__]);

if(!empty($l)){
	foreach ($l as $k=>$v){
		$img0 = getImage([
				'src'=>$v['image'],'output'=>'src',
				'w'=>1600,
				'attrs'=>[
						'alt'=>uh($v['title']),
						 
				]
		]);
		$img50 = getImage([
				'src'=>isset($v['thumbnail']) && $v['thumbnail'] != "" ? $v['thumbnail'] : $v['image'],
				'output'=>'src',
				'w'=>50,
				'attrs'=>[
						'alt'=>uh($v['title']),
						
				]
		]);
		echo '<a class="b-main_home-banner__img-link" data-content="'.uh($v['info']).'" 
data-event="_gaq.push([\'_trackPageview\',\''.$v['link'].'\']);" 
data-link="'.$v['link'].'" data-thumb="'.$img50.'" 
data-title="'.uh($v['title']).'" href="'.$v['link'].'" 
onclick="_gaq.push([\'_trackPageview\', \''.$v['link'].'\']);" 
style="background: transparent url('.$img0.') no-repeat center top; " 
target="'.($v['target']).'" title="'.uh($v['info']).'"><span class="hidden">'.uh($v['info']).'</span> </a>';
	}
}
?>
 
 

</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div></div>
</div>

  
        
</div>


</div>
 
<?php 
$this->registerJsFile(__RSDIR__ .'/js/jquery.cycle2.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ .'/js/home.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>