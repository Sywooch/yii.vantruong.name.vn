<?php

$this->registerJsFile(__RSDIR__ . '/js/jwplayer/jwplayer.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/jwplayer/jquery.jplayer.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/jwplayer/AudioStyle.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJs('jwplayer.key = "7jY1U8qi8ugYQ88uyRjUtGpUBf8yu5GQCOP+XA=="');
$this->registerJsFile(__RSDIR__ . '/js/jscrollpane/jquery.jscrollpane.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/jscrollpane/jquery.mousewheel.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/jscrollpane/mwheelIntent.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/ubaplayer/jquery.ubaplayer.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerCssFile(__RSDIR__ . '/js/jscrollpane/jquery.jscrollpane.css');
 
echo '<div class="fusion-page-title-row container"><h1 class="entry-title" data-fontsize="30" data-lineheight="70">
'.showFirstTitle(__CATEGORY_NAME__,2).'</h1>
</div>';
?>
<div class="container"><div class="pr fl100">








 
<div class="content_left">
    <div class="main_content">
 
<?php 
$v = $this->app()->getArticles(array('detail'=>true,'tabs'=>true));
 
?>        
        
        <div class="com_lesson_detail">
        <?php 
        echo '<h1 class="lesson_title">'.uh($v['title']).'</h1>
            <div class="lesson_menu">';
        if(!empty($v['tabs']) && count($v['tabs']) > 1){
        	foreach ($v['tabs'] as $kc=>$vc){
        		echo '<a data-href="#tab-content-'.$kc.'">'.uh($vc['title']).'</a>';
        	}
        }
        echo '</div>';
        ?>
            
            <div class="lesson_content">
                <div class="question_detail">
                <?php 
                //view($v['tabs']); 
                if(!empty($v['tabs'])){
                	foreach ($v['tabs'] as $kc=>$vc){
                		echo '<div class="panel '.($kc == 0 ? 'selected' : '').'" id="tab-content-'.$kc.'">';
                		switch ($vc['type']){
                			case 'video': case 'videos':
                				include_once 'lession_forms/video.php';
                				break;
                			default:
                				echo (isset($vc['text']) ? uh($vc['text'],2) : '');
                				break;
                		}
                		//echo '';
                        echo '</div>';
                	}
                	
                }
                ?>
                
                
                
                

  
</div></div>

            </div>
        </div>
        <div class="share_lesson_group hide">
    
    <div class="share_item save"> 
        <i class="share_item_icon save_lession"></i><span class="title">Lưu bài học</span></div>
    <div class="share_item share">
        <i class="share_item_icon share_lession"></i><span class="title">Chia sẻ bài học</span>
        <ul class="social_share_lst">
            
            <li>
                <a target="_blank" rel="nofollow" onclick="window.open('http://www.facebook.com/sharer/sharer.php?u=<?php echo FULL_URL;?>','Share on Facebook','width=540,height=560');"  class="face">
                    <img alt="Facebook" title="Share on Facebook" src="<?php echo __RSDIR__;?>/images/facebook.png" />
                    Facebook
                </a>
            </li>
            <li>
                <a target="_blank" rel="nofollow" onclick="window.open('http://twitter.com/share?url=<?php echo FULL_URL;?>','name','width=550,height=450');" class="twi">
                    <img class="sociable-hovers" alt="Twitter" title="Share on Twitter" src="<?php echo __RSDIR__;?>/images/twitter.png" />
                    Twitter
                </a>
            </li>
            <li>
                <a target="_blank" rel="nofollow" onclick="window.open('https://plus.google.com/share?url=<?php echo FULL_URL;?>','Share on Google+','width=620,height=620');" class="google">
                    <img class="sociable-hovers" alt="Google+" title="Share on Google+" src="<?php echo __RSDIR__;?>/images/googleplus.png" />
                    Google+
                </a>
            </li>
            <li>
                <a target="_blank" rel="nofollow" onclick="window.open('http://pinterest.com/pin/create/button/?url=<?php echo FULL_URL;?>','Share on Pinterest','width=1024,height=650');" class="pint">
                    <img class="sociable-hovers" alt="Google+" title="Share on Pinterest" src="<?php echo __RSDIR__;?>/images/pinterestIcon.png" />
                    Pinterest
                </a>
            </li>
            <li>
                <a target="_blank" rel="nofollow" onclick="window.open('http://linkhay.com/submit?url=<?php echo FULL_URL;?>','Link hay','width=500,height=500');" class="linkhay">
                    <img class="sociable-hovers" alt="Link hay" title="Share on Link hay" src="<?php echo __RSDIR__;?>/images/icon_linkhay.png" />
                    Link hay
                </a>
            </li>
            <li>
                <a target="_blank" rel="nofollow" onclick="window.open('http://link.apps.zing.vn/share?u=<?php echo FULL_URL;?>','Share on Zing','width=500,height=500');" class="zing">
                    <img src="<?php echo __RSDIR__;?>/images/small_zing_icon.png" alt="Share on Zing" />
                    Zing
                </a>
            </li>
            <li>
                <a rel="nofollow" name="articlePrint" onclick="javascript: window.print();" href="javascript:;">
                    <img border="0" alt="Print" align="bottom" src="<?php echo __RSDIR__;?>/images/print.png" />
                    Print
                </a>
            </li>
        </ul>
    </div>
    <div class="share_item like">
        <i class="share_item_icon like_lession"></i><span class="title" onclick="SaveNewsLike(15,0)">Thích bài học</span>
        <ul class="social_share_lst like_list">
            <li>
                <div id="loadLike">
                    
                </div>
            </li>
        </ul>
    </div>
                <div class="share_item note">
                    <i class="share_item_icon note_lession"></i>  <span class="title">Ghi chú</span>
                </div>
    
</div>
    
  
<div class="clear"></div>
        <div class="box box_border" style="margin-top: 30px">
    <div class="box_title hide">Bài học cùng chuyên mục</div>
    <div class="box_content box_relate_lesson box_relate_lesson_gt"><div class="row">
        <div class="slide_relate_lesson_gt s-slick-slider" data-items="5">
        <?php 
        $l = $this->app()->getArticles(array('category'=>__CATEGORY_ID__,'key'=>'limit-lession-lq','other'=>$v['id']));
        if(!empty($l['listItem'])){
        	foreach ($l['listItem'] as $k=>$v1){
        		$link = cu([DS.$v1['url']]);
        		echo '<div class="relate_item center col-sm-12 col-xs-12">
                    <a class="image img " href="'.$link.'">'.getImage(array('src'=>$v1['icon'],'w'=>200,'alt'=>uh($v1['title']))).'</a>
                    <a class="center ltitle" href="'.$link.'" title="'.uh($v1['title']).'">'.uh($v1['title']).'</a>
                </div>';
        	}
        }
        ?>  
        </div></div>
        
    </div>
</div>
 
</div>

 
</div></div>                
                