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

echo '<div class="container"><div class="row">';  
$l = Yii::$app->zii->getArticles(['category'=>__CATEGORY_ID__,'key'=>'limit-news','p'=>getParam('p',1)]);
echo '<div class="col-sm-9 col-xs-12">';
if(!empty($l['listItem'])){
	echo '<div class="box-list-tin">';
	foreach ($l['listItem'] as $k=>$v){
		$link = cu([DS.$v['url']]);

		echo '<div class="item-tin-box-center">
                            <a href="'.$link.'" class="fll aimgitem1" title="'.(isset($v['seo']['title']) ? uh($v['seo']['title']) : '').'">
                                '.getImage(array('src'=>isset($v['icon']) ? $v['icon'] : '','w'=>150,'attr'=>array('alt'=>(isset($v['seo']['title']) ? uh($v['seo']['title']) : uh($v['title']))))).'
                            </a>
                        <div class="item-tin-right">
                            <div class="title"><a href="'.$link.'" class="" title="'.(isset($v['seo']['title']) ? uh($v['seo']['title']) : uh($v['title'])).'">'.uh($v['title']).'</a></div>
                            <div class="news_time">
                                    <i class="fontitem1 "><span>Ngày đăng: </span></i>
                                    <a href="" class="hide" title="'.uh($v['title']).'">'.uh($v['title']).'</a>
									<i class="fontitem1 DDMMYY"> <span>'.date("d/m/Y",strtotime($v['time'])).'</span></i>
                            </div>
                            <h4 class="intro_item ajust">'.(isset($v['info']) ? uh($v['info']) : '').'</h4>
                        </div>
                     </div>';
		 
	}
	echo '<div class="fll listpage">
                    <div id="Page_List" class="fontpage">



                    </div>
                </div>
        </div>';
}
echo '</div>';
include_once '_rsidebar.php';


echo '</div></div>';