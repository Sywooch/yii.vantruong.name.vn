<?php
$this->registerCssFile('/themes/forms/css/products-col-sm-9.01.css',['depends' => [\yii\bootstrap\BootstrapPluginAsset::className()]]);
?>
<div class="col-middle col-sm-9 product-sm-9-01">	
<div class="row">
<div class="breadcrumb">
         
         <?php
    $l = $this->app()->get_tree_menu();
    echo '<a href="./">Trang chủ</a>';
    if(!empty($l)){
        foreach($l as $k=>$v){
            echo ' » <a href="'.$v['link'].'">'.uh($v['title']).'</a>';
        }
    }
    ?>
      </div>
    
<?php
$v = $this->app()->getArticles(array('detail'=>true));
//view($v['listImages']);
if(!empty($v)){
    echo '<div class="product-info"> ';
    echo '<div class="info-wrapper">';
    echo '<div id="ca-container" class="ca-container">        
         <div class="image-additional ca-wrapper slick-slider-vertical" data-show="4" style="overflow: hidden; height: 368px; width: 70px;">';
         if(!empty($v['listImages'])){
            foreach($v['listImages'] as $i=>$im){
                $sm = getImage(array('src'=>$im['image'],'w'=>297,'h'=>370,'output'=>'src'));
                $sm_thumb = getImage(array('src'=>$im['image'],'w'=>100,'output'=>'src'));
                echo '<div class="item-wrap ca-item " >
                	<div class="adtwrap item-wrapwp">
						<a onclick="cimagezoom(\''.$im['image'].'\');return false;" style="height:78px;display:table-cell;vertical-align:middle" href="'.$im['image'].'" title="" class="cloud-zoom-gallery " rel="colorbox">
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
                            //'w'=>297,'h'=>370,
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
        
        echo '<div class="right pull-right">            
 
        	<div class="product-header">
  				<div class="name">'.uh($v['title']).'</div>
            </div>
 
            
            
			<div class="description">
            
            	<div class="desc">
					<span>Mã sản phẩm:</span> '.$v['code'].'<br />
					<span>Tình trạng:</span><span style="color:#e01356; padding-left: 5px;">Còn hàng</span>
                </div> 
                    			</div>
            '.(isset($v['info']) && $v['info'] != "" ? '<div class="description"><div class="desc">'.uh($v['info']).'</div></div>' : "").'
            
			<div class="description">
            	<div class="desc">
					<span>Hotline:</span><a href="tel:0946581636" style="color:#e01356; padding-left: 5px; font-weight: bold;font-size:16px">0946 58 16 36</a> <br/>
					
                </div>
			</div>
			    	</div>';
                    echo '<div class="price-area">
 
                             <div class="price-table">
                    <div class="price-container" style="display:table-cell;vertical-align: middle;">
                         <div class="price">'.($v['price2'] > 0 ? ($v['price1'] > $v['price2'] ? '<span class="price-old">'.number_format($v['price1']).' VNĐ</span><br />' : '').'<span class="price-new">'.number_format($v['price2']).' VNĐ</span>  ' : 'Liên hệ').'</div>
</div>
                </div>
 
				 
                
                <div class="quantity">Số lượng: <input type="text" name="quantity cart-item-quantity-'.$v['id'].'" class="center" size="2" value="1" />
                     
                </div>
            
                <div class="addcart">
                    <div class="button">
                        <input type="button" value="Mua hàng" class="button-cart" data-id="'.$v['id'].'" onclick="add_to_cart(this);" data-role="buynow"/>
                    </div>
                </div>
                
 
            
			</div>';
          
    echo '</div>'; // info-wrapper
  
    echo '<div id="tabs" class="htabs">';
    if(!empty($v['ctab']) && count($v['ctab']) > 1){
        foreach($v['ctab'] as $d=>$t){
            echo '<a href="#tab-description-'.$d.'">'.uh($t['title'],2).'</a>';
        }
    }
    echo '</div>';
    if(!empty($v['ctab'])  ){
        foreach($v['ctab'] as $d=>$t){
            echo '<div id="tab-description-'.$d.'" class="tab-content f12e">'.uh($t['text'],2).'</div>';
        }
    }
    
    echo '</div>'; // product-info
}
?>
 


 
 
</div></div>