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
$l = Yii::$app->zii->getArticles(['category_id'=>CONTROLLER_ID,'detail'=>__IS_DETAIL__]);
$v = __IS_DETAIL__ ? $l : (isset($l['listItem'][0]) ? $l['listItem'][0] : []);
//var_dump($l);
if(!empty($v)){
	echo '<div class="container post-content">
<div class="fusion-one-full fusion-layout-column fusion-column-last fusion-spacing-yes" >
<div class="fusion-column-wrapper">'.(uh($v['info']) != "" ? '<blockquote class="mgt30">
<p style="text-align: center;">'.uh($v['info']).'</p>
</blockquote>' : '').'
<h3><span ><strong>'.uh($v['title']).'</strong></span></h3>';
if(!empty($v['ctab'])){
	foreach($v['ctab'] as $d=>$t){
		echo '<div class="box-details">'.Yii::$app->zii->showTextDetail(uh($t['text'],2)).'</div>';
	}
}
echo '<div class="fusion-clearfix"></div></div></div></div>';
}
?>		

