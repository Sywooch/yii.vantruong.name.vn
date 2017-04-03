<?php 
 
echo '<div class="fusion-page-title-row container"><h1 class="entry-title" data-fontsize="30" data-lineheight="70"><a href="'.cu([DS. __CATEGORY_URL__]).'">
'.showFirstTitle(__CATEGORY_NAME__,2).'</a></h1>
</div>';

echo '<div class="container"><div class="row">';  
echo '<div class="col-sm-9 col-xs-12">';
$v = Yii::$app->zii->getArticles(array('detail'=>true));
if(!empty($v)){
	$v['seo']['title'] = isset($v['seo']['title']) && $v['seo']['title'] != ""? $v['seo']['title'] : $v['title'];
	echo '<div class="main box-tin-chi-tiet"><div class="mainbg"><div class="body_resize"><div class="box-center"><div class="box-tin-chi-tiet">';
	echo '<div class="news_title" itemtype="http://data-vocabulary.org/Recipe" itemscope="" >
                                    <div class="news_img hide">
                                        <img  itemprop="description" alt="'.$v['seo']['title'].'" class="" title="'.$v['seo']['title'].'" src="'.$v['icon'].'"/>
                                    </div>
                                    <div class="title_right">
                                        <h1 class="tieu-de-chi-tiet" itemprop="name">'.$v['title'].'</h1>
                                        <div class="itemscop" itemtype="http://data-vocabulary.org/Review-aggregate" itemscope="" itemprop="review">
                                                <i class="fontitem1"><span>Ngày đăng: </span></i>
                                                <i class="fontitem1">  <span>'.date("d/m/Y",strtotime($v['time'])).'</span></i>
                                        </div>
                                        <br/>
                                        <h4 class="h4introitem1">
                                            <span itemprop="description">'.uh($v['info']).'</span>
                                        </h4>
                                    </div>
                                </div>';
	echo '<div class="fll news_body" >';
	if(!empty($v['ctab'])){
		foreach($v['ctab'] as $d=>$t){
			echo '<div class="box-details">'.Yii::$app->zii->showTextDetail(uh($t['text'],2)).'</div>';
		}
	}
	echo '</div>';
	echo '<div class="box-tin-chi-tiet-share">
                        <div class="item_social fb-like" data-href="" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
            
                        <!-- Đặt thẻ này vào phần đầu hoặc ngay trước thẻ đóng phần nội dung của bạn. -->
                        <script src="https://apis.google.com/js/platform.js" async defer>
                            {lang: \'vi\'}
                        </script>
                        <div class="item_social g-plusone" data-size="medium" data-href="" ></div>

                        <a href="https://twitter.com/share" class="item_social twitter-share-button">Tweet</a>
                        <script>!function (d, s, id) { var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? \'http\' : \'https\'; if (!d.getElementById(id)) { js = d.createElement(s); js.id = id; js.src = p + \'://platform.twitter.com/widgets.js\'; fjs.parentNode.insertBefore(js, fjs); } }(document, \'script\', \'twitter-wjs\');</script>
                        <span style="margin-left: 10px;">
                        <a class="item_social" data-pin-color="white" data-pin-do="buttonPin" href="https://www.pinterest.com/pin/create/button/?url='.cu($v['url']).'&media='.$v['icon'].'&description='.uh($v['title']).'" data-pin-config="beside"></a>
                        <script async defer src="//assets.pinterest.com/js/pinit.js"></script>
                        </span></div> ';
	echo '<div class="fl100 comment_facebook"><div class="fb-comments" data-href="'. cu(__DETAIL_URL__) .'" data-numposts="5" data-width="100%"></div></div>';
	echo '<div class="box_other fl100"><div class=" viewmore   "><p class="title bold">Bài viết cùng chuyên mục</p><ul class="news_lq">';
	$l = Yii::$app->zii->getArticles(array('category'=>__CATEGORY_ID__,'limit'=>5,'other'=>$v['id']));

	if(!empty($l['listItem'])){
		foreach ($l['listItem'] as $k=>$v){
			$link = $this->createUrl($v['url']);
			echo '<li><a href="'.$link.'" title="'.uh($v['title']).'">'.uh($v['title']).'</a> -<span class="DDMMYY italic">'.date("d/m/Y",strtotime($v['time'])).'</span></li>';
		}
	}
	echo '</ul></div></div>';
	echo '</div></div></div></div></div>';
}

echo '</div>';
include_once '_rsidebar.php';
echo '</div></div>';