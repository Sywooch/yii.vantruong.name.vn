<div class="clear"></div>

	
	<?php 
if(__IS_DETAIL__ && Yii::$app->controller->action->id == 'tours' && is_array($v) && !empty($v)){	
	echo '<div class="after_content fl100"><div class="after_inner fl100 "><div class="container_12 pr " style="padding-top:0"><div class="fl100">';
$l = $this->app()->getArticles(array('category'=>__CATEGORY_ID__,'key'=>'limit-tour-lq','other'=>$v['id']));
if(!empty($l['listItem'])){
	echo '<h3 class="cafeta ">'.$l['box']['title'].'</h3>';
	echo '<div class="row"><div class="tour_lien_quan slick-slider">';
	foreach ($l['listItem'] as $k=>$v){
		$link = $this->createUrl($v['url']);
		echo '<div class="item" title="'.uh($v['title']).'">
			<a href="'.$link.'">
			<div class="item-img">'.getImage(array(
				'src'=>$v['icon'],'w'=>400, 'img_attr'=>array('alt'=>$v['title'],'itemprop'=>'image')
		)).'</div>
				<h2 class="item-name truncate">'.uh(isset($v['short_title']) && $v['short_title'] != "" ? $v['short_title'] : $v['title']).'</h2>
			</a>
		<p class="item-time">'.$this->app()->getText(15).' : '.($v['day'] || $v['night'] > 0 ? '('.$this->app()->showTourTime($v['day'],$v['night']).')' : '').'</p>
		<p class="item-price">'. ($v['price2'] == 0 ? $this->app()->getText(29) : $this->app()->getText(29)).' : '.($v['price2'] > 0 ? showPrice($v['price2']) : '<span class="hide" itemprop="price">0</span><b>' . $this->app()->getText(2)).'</b></p>
        		</div>';
	}
	echo '</div></div>';       
}
echo '</div></div></div></div>';
}
?>  
	
<?php 
if(Yii::$app->controller->action->id == 'tours'){
	echo '<div class="clear"></div>';
	 
	

if(__LANG__ == 'vi_VN')
	require __VIEWS_PATH__ . '/_top_search_tour.php';
	else{
		require  __VIEWS_PATH__ . '/_top_search_en.php';
	}
}
?>	
<div class="after_content hide">
<div class="after_inner  ">
	<div class="container_12 pr "><div class="row">
   	    
        <!--# widgets area, col 1 -->    
        <div class="widgetarea widget_col_1 col-sm-4 col-xs-12">
        
        	<!-- widget_products -->
        	<div class="widget-container widget_products">
            	<div class="inner">
               	  	<h3 class="cafeta ">PROMO OF THE DAY:</h3>                    
                    <div class="prod_item">
	                    <div class="prod_image"><a href="offer-details.html"><img src="<?php echo __RSDIR__;?>/images/temp/offer_small_01.jpg" width="140" height="98" alt=""></a></div>
	                    <span class="price_box"><ins>$</ins><strong>1579</strong></span>
	                    <div class="prod_title">
                        	<a href="offer-details.html"><strong>Hilton Opera House 5* 7 nights, flight included</strong></a><br>
	                    	<span><a href="offer-details.html">Sydney, Australia</a></span>
	                    </div> 
                    </div>                                  
                    <div class="clear"></div>                                                                  
                </div>
            </div>
            <!--/ widget_products -->
            
        </div>
        <!--/ widgets area, col 1 -->    
        
        <!--# widgets area, col 2 -->
        <div class="widgetarea widget_col_2 col-sm-4 col-xs-12">
        	
            <!-- widget_newsletter -->
            <div class="widget-container newsletterBox">
				<div class="inner">
					<h3 class="cafeta ">NEWSLETTER SIGNUP:</h3>
					<form method="get" action="#">
						<input type="text" value="Enter your email address" onfocus="if (this.value == 'Enter your email address') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Enter your email address';}" name="email" class="inputField">				                    
						<div class="rowCheckbox input_styled checklist">
						<input type="checkbox" name="subscribe" id="subscribe" value="1"> <label for="subscribe">Subscribe to RSS</label>
						</div>
						<input type="submit" value="SUBMIT" class="btn-submit">
					</form>     
				</div>
			</div>
			<!--/ widget_newsletter -->
            
        </div>
        <!--/ widgets area, col 2 -->
        
        <!--# widgets area, col 3 -->
        <div class="widgetarea widget_col_3 col-sm-4 col-xs-12">
        	
            <!-- widget_twitter -->            
            <div class="widget-container widget_twitter">
				<h3 class="cafeta ">TWITTER FEED:</h3>   
               
  		    	<div class="tweet_item">
                	<div class="tweet_image"><img src="<?php echo __RSDIR__;?>/images/temp/twitter_avatar.png" width="30" height="30" alt=""></div>
                    <div class="tweet_text">
                    	<div class="inner">
		    	  		How the Explosion in Online’s Education can be the Revolution for your Business: <a href="#">ow.ly/aFK40</a>
                        <br><a href="#" class="tweet_author">#blogging</a>
                        </div>
                    </div>
                    <div class="clear"></div>
	  		  	</div>   
                                
                <a href="#" class="fallow">Follow us</a>
			</div>
            <!--/ widget_twitter -->
        </div>
        <!--/ widgets area, col 3 -->
        
        <div class="clear"></div>
    </div>
</div>
</div></div>

<div class="clear"></div>

<div class="footer" id="footer">
<div class="footer_inner "><div class="container">
	<div class="container_12"><div class="row">
<?php 
$b = $this->app()->getBox('footer_col1');
if(!empty($b)){
	echo '<div class="widgetarea f_col_1 col-sm-5 col-xs-12">
	        <div class="widget-container widget_categories">
				<h3 class="widget-title">'.uh($b['title']).'</h3>
					<ul>';
	$l = $this->app()->getCategorys(array('parent_id'=>$b['menu_id']));
	if(!empty($l)){
		foreach ($l as $k=>$v){
			echo '<li><a href="'.cu($v['url']).'"><span>'.uh($v['title']).'</span></a></li>';
		}
	}
 
	echo '</ul></div></div>';
}
$b = $this->app()->getBox('footer_col2');
if(!empty($b)){
	echo '<div class="widgetarea f_col_2 col-sm-3 col-xs-12"> 
	        <div class="widget-container widget_pages">
				<h3 class="widget-title">'.uh($b['title']).'</h3>
					<ul>';
	$l = $this->app()->getCategorys(array('parent_id'=>$b['menu_id']));
	if(!empty($l)){
		foreach ($l as $k=>$v){
			echo '<li><a href="'.cu($v['url']).'"><span>'.uh($v['title']).'</span></a></li>';
		}
	}

	echo '</ul></div></div>';
}
?>
        <div class="widgetarea f_col_3 col-sm-4 col-xs-12">
        	            
            <!-- widget_contact -->
	        <?php 
	        $b = $this->app()->getBox('footer_info1');
	        if(!empty($b)){
	        	echo uh($b['text'],2);
	        }
	        ?>
	        <!--/ widget_contact -->
           
        </div>
        <!--/ footer col 3 -->
    	
        
        <div class="copyright col-sm-12 pr">
        <?php 
        $b = $this->app()->getBox('copyright');
        if(!empty($b)){
        	echo uh($b['text'],2);
        }
        ?>
     <a target="_blank" href="http://online.gov.vn/HomePage/CustomWebsiteDisplay.aspx?DocId=23529" rel="nofollow" class="link_bct"></a>
        </div>
        
    </div></div> 
</div>
</div></div>
 <?php 
 //$this->registerJsFile(__LIBS_DIR__ .'/editor/ckeditor_4.6.2/ckeditor.js', ['depends' => [\yii\web\JqueryAsset::className()]]); 

 