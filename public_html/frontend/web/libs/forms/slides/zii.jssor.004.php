<?php
$w = isset($_slide_width) ? $_slide_width : '999px';     
$h = isset($_slide_height) ? $_slide_height : '300px';         
$l = Yii::$app->zii->getAdvert(['code'=>'ADV_SLIDER','category_id'=>__CATEGORY_ID__]);
 
?>
<div id="slider1_container" style="position: relative; top: 0px; left: 0px; width: 1140px ; height: 360px; background: transparent; overflow: hidden; ">

        <!-- Loading Screen -->
        <div u="loading" style="position: absolute; top: 0px; left: 0px;">
            <div class="jssor_mask_loading" style="filter: alpha(opacity=70); opacity:0.7; position: absolute; display: block;
                top: 0px; left: 0px;width: 100%;height:100%;">
            </div>
            <div class="jssor_loading" style="position: absolute; display: block; top: 0px; left: 0px;width: 100%;height:100%;">
            </div>
        </div>

        <!-- Slides Container -->
        <div u="slides" style="cursor: move; position: absolute; left: 0px; top: 0px; width: 1140px; height: 360px; overflow: hidden;">
            <?php
            if(!empty($l)){
                foreach($l as $k=>$v){
                    echo '<div><a u=image href="'.$v['link'].'">
                    <img src="'.getImage(array(
                        'src'=>$v['image'],
                        'w'=>(int)$w,
                        'output'=>'src',
                        
                    )).'" data-effect="none" class="lazyload-img-product" alt="'.$v['title'].'" /></a></div>';
                }
            }
            ?>
            </div>        
        <style>
                /* jssor slider bullet navigator skin 05 css */
                /*
                .jssorb05 div           (normal)
                .jssorb05 div:hover     (normal mouseover)
                .jssorb05 .av           (active)
                .jssorb05 .av:hover     (active mouseover)
                .jssorb05 .dn           (mousedown)
                */
                .jssorb05 {
                    position: absolute;
                }
                .jssorb05 div, .jssorb05 div:hover, .jssorb05 .av {
                    position: absolute;
                    /* size of bullet elment */
                    width: 16px;
                    height: 16px;
                    background: url(<?php echo __LIBS_DIR__;?>/slider/jssor/img/b20.png) no-repeat;
                    overflow: hidden;
                    cursor: pointer;
                }
                .jssorb05 div { background-position: -7px -7px; }
                .jssorb05 div:hover, .jssorb05 .av:hover { background-position: -37px -7px; }
                .jssorb05 .av { background-position: -67px -7px; }
                .jssorb05 .dn, .jssorb05 .dn:hover { background-position: -97px -7px; }
            </style>
            <div u="navigator" class="jssorb05" style="bottom: 10px; right: 6px;">
                <div u="prototype"></div>
            </div>
    </div>