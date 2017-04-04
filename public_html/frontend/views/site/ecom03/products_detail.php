<?php include_once '_breadcrumb.php';?>    	
<div class="container" >
   <div class="row"> <div class="fl100" id="content"">     
<?php include_once '_lsidebar.php';?>    	

    	<div class="col-md-9 col-sm-12">
    	
 <div class="box_pro box_pro_list row">

            <div class="box_page box_pro_cat col-sm-12">
<div class="head_"><h1><?php echo  __CATEGORY_NAME__;?></h1></div>
 
                
                

             </div>
 
    
                
            
            </div>
    
<?php
$v = $this->app()->getArticles(array('detail'=>true));
//view($v['listImages']);
if(!empty($v)){
    echo '<div class="product-info"> ';
    echo '<div class="info-wrapper">';
    echo '<div id="ca-container" class="ca-container">        
         <div class="image-additional ca-wrapper slick-slider-vertical" data-show="5" style="overflow: hidden; height: 368px; width: 70px;">';
         if(!empty($v['listImages'])){
            foreach($v['listImages'] as $i=>$im){
                $sm = getImage(array('src'=>$im['image'],'w'=>400,'h'=>300,'output'=>'src'));
                $sm_thumb = getImage(array('src'=>$im['image'],'w'=>100,'output'=>'src'));
                echo '<div class="item-wrap ca-item " >
                	<div class="adtwrap item-wrapwp">
						<a onclick="cimagezoom(\''.$im['image'].'\');return false;" style="height:60px;display:table-cell;vertical-align:middle" href="'.$im['image'].'" title="" class="cloud-zoom-gallery " rel="colorbox">
						<img src="'.$sm_thumb.'" title="" alt="" width="60"  >
						</a>
                    </div>
            	   	</div>';
            }
         }                       
                                
           echo '</div>
		</div>';
        if(!empty($v['listImages'])){
        	$_i = '';
        	if(isset($v['listImages']) && !empty($v['listImages'])){
        		$_i = $v['listImages'][0];
        		foreach ($v['listImages'] as $im){
        			if(isset($im['main']) && $im['main'] == 1){
        				$_i = $im; break;
        			}
        		}
        	}
            $im = $_i;
           // view($v['listImages'],true);
        echo '<div class="image">
					<div class="zoomimg">
						'.getImage(array(
                            'src'=>$im['image'],
                            'w'=>450,
								'img_attr'=>[
										//'data-cloudzoom'=>'zoomImage: \''.$im['image'].'\'',
										'class'=>'zoom_detail'
								]
                        )).'
                       
					</div>
                <span id="zoomlinks" class="zoomlinks"> ';
                if(!empty($v['listImages'])){
            foreach($v['listImages'] as $i=>$im){
                echo '<a href="'.$im['image'].'" title="" class="colorbox enlarge" rel="colorbox" style="'.($i == 0 ? 'display:inline' : 'display:none').'">Phóng to</a>';
                break;
            }
                }
                                             
                     
                echo '</span>
                </div>';
                				 
        }      
        
        echo '<div class="right pull-left product-info">            
 
        	<div class="product-header">
  				<div class="name">'.uh($v['title']).'</div>
            </div>
  
			<div class="description">
            
            	<div class="desc">
					<span class="bold">Mã sản phẩm:</span> '.$v['code'].'<br />
					 
                </div> 
                		
                <div class="price pr">'.getTextTranslate(29).': <span class="price1 span1">'.Yii::$app->zii->showPrice(['price'=>$v['price2'],'text_contact'=>218]).'</span>
		
                		<span class="price-old2 ">'.($v['price1'] > 0 && $v['price1']>$v['price2'] ? Yii::$app->zii->showPrice($v['price1']) : '').'</span>
	                </div>		
                    			</div>
            '.(isset($v['info']) && $v['info'] != "" ? '<div class="description"><div class="desc">'.uh($v['info']).'</div></div>' : "").'
            
			<div class="description hide">
            	<div class="desc">
					<span>Hotline:</span><a href="tel:0946581636" style="color:#e01356; padding-left: 5px; font-weight: bold;font-size:16px">0946 58 16 36</a> <br/>
					
                </div>
			</div>';
        $b = Yii::$app->zii->getBox('detail-hotline');
        if(!empty($b)){
        	echo '<div class="text-detail-box fl100">' . uh($b['text'],2) .'</div>';
        }
		echo '	    	</div>';
                    echo '<div class="price-area hide">
 
                              
 
				 
                
                <div class="quantity">Số lượng: <input type="text" name="quantity cart-item-quantity-'.$v['id'].'" class="center" size="2" value="1" />
                     
                </div>
            
                <div class="addcart">
                    <div class="button">
                        <input type="button" value="Mua hàng" class="button-cart" data-id="'.$v['id'].'" onclick="add_to_cart(this);" data-role="buynow"/>
                    </div>
                </div>';
           
 			 
			echo '</div>'; 
          
    echo '</div><div class="clear"></div>'; // info-wrapper
  
    
    if(!empty($v['ctab']) && count($v['ctab']) > 1){
    	echo '<ul class="nav nav-tabs tabs-detail" role="tablist">';
        foreach($v['ctab'] as $d=>$t){
            echo '<li role="presentation" class="'.($d == 0 ? 'active' : '').'"><a aria-controls="tab-description-'.$d.'" role="tab" data-toggle="tab" href="#tab-description-'.$d.'">'.uh($t['title'],2).'</a></li>';
        }
        echo '</ul>';
    }else{
    	echo '<div class="dline-break"></div>';
    }
    
    if(!empty($v['ctab'])  ){
    	echo '<div class="tab-content tabs-detail">';
        foreach($v['ctab'] as $d=>$t){
            echo '<div role="tabpanel" class="tab-pane active f12p" id="tab-description-'.$d.'" >'.uh($t['text'],2).'</div>';
        }
        echo '</div>';
    }
    
    echo '</div><div class="clear"></div>'; // product-info
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
    $b = Yii::$app->zii->getBox('fb-comment');
    if(!empty($b)){
    	echo '<div class="comment-fb fl100"><div class="fb-comments w100" data-href="'.cu([DS.$v['url']],true).'" data-width="100%" data-numposts="5"></div></div>';
    }
    echo '
    <div class="fl100">
    <div class="box_pro box_pro_list row">
    
    <div class="box_page box_pro_cat col-sm-12">
    <div class="head_"><h1>Sản phẩm liên quan</h1></div>
     
                    
                    
    
                 </div>';
     
    $l = $this->app()->getArticles(array('p'=>getParam('p',1),'type'=>'products','other'=>$v['id'] ,'category'=>__CATEGORY_ID__ ,'key'=>'limit-products-rate','count'=>false));
    if(!empty($l['listItem'])){
    	foreach($l['listItem'] as $k=>$v){
    		$link = cu(DS.$v['url']);
    		
    		echo '<div class="col-md-4 col-sm-6 col-xs-12">
    	            <div class="pro_item pro_item--home">
    	                <a href="'.$link.'"><div class="img pr auto_rz_4x3"><div class="img_hover"></div>
                		'.getImage(['src'=>$v['icon'],'w'=>400,'img_attr'=>['alt'=>uh($v['title'])]]).' </div></a>
    			
    	            	<div class="title"><a href="'.$link.'">'.uh($v['title']).'</a></div>
    	                <div class="price pr">'.getTextTranslate(29).': <span class="price1 span1">'.Yii::$app->zii->showPrice(['price'=>$v['price2'],'text_contact'=>218]).'</span>
    		
                    		<span class="price-old">'.($v['price1'] > 0 && $v['price1']>$v['price2'] ? Yii::$app->zii->showPrice($v['price1']) : '').'</span>
    	                </div>
    	            </div>
    	        </div>';
    	}
    }
    echo '</div>
    
               
                
                <div class="clearfix"></div>';
    
    
     
    
    
     echo '</div>';
}
?>
 


 
 
</div></div></div></div>