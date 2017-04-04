<div id="home-content" class="fl100">
<div class="container">
<?php
 
$lb = $this->app()->getBoxIndex();
if(!empty($lb)){
    foreach($lb as $kb=>$vb){
        echo '<div  class="box cf-container index-box-slide">
 
  <div class="box-heading"><span '.($vb['box']['menu_id']>0 ? '' : '').'>'.uh($vb['box']['title']).'</span></div>
  <div class="box-content">
    <div class="box-product cf-wrapper slick-slider" data-show="4" data-scroll="4" data-dots="false" data-rows="1">';
    if(!empty($vb['listItem'])){
        foreach($vb['listItem'] as $k=>$v){
            $link = cu(DS. $v['url']);
            $_i = '';
            if(isset($v['listImages']) && !empty($v['listImages'])){
            	foreach ($v['listImages'] as $im){
            		if(isset($im['main']) && $im['main'] == 1){
            			$_i = $im['image']; break;
            		}
            	}
            }
            $img_src = getImage(array(
                    'src'=>$_i,
                    'w'=>300,
                    'output'=>'src'
                    )
                );
          // view($v);
          $pc = $v['price1'] > 0 ? number_format(100 - $v['price2']/$v['price1']*100 ,0) * -1 : 0;
            echo '<div class="item-wrap cf-item item-'.$v['id'].' col-sm-3">
<div class="rowc">            
      <div class="back item-wrapwf">
        <div class="image fixed_img_size">'.($pc < 0 ? '<div class="saving"><div class="saving_text">'.$pc.'<sup class="percent">%</sup></div></div>' : '').'
        
                <a href="'.$link.'">    
                <img src="'.$img_src.'" class="lazyload-img-product" alt="'.uh($v['title']).'"/>            
                
                </a>
		<ul>
			<li><a href="'.$link.'">Chi tiết</a></li>
			<li><a onclick="addToWishList(\''.$v['id'].'\');">Thêm Yêu thích</a></li>
			<li class="last"><a onclick="addToCompare(\''.$v['id'].'\');">Thêm so sánh</a></li>
		</ul>
        </div>
    	<div class="price-cart-con">
        	
            			<div class="prc">
            <div class="price">'.($v['price2'] > 0 ? ($v['price1'] > $v['price2'] ? '<span class="price-old">'.number_format($v['price1']).' VNĐ</span><br />' : '').'<span class="price-new">'.number_format($v['price2']).' VNĐ</span>  ' : 'Liên hệ').'</div>
		    </div>
                        <div class="rating"></div>
            <div class="cart">
	    	<div class="button">
            <input type="button" value="Mua hàng" data-id="'.$v['id'].'" data-amount="1" onclick="add_to_cart(this);" class="addbutton" />
            </div>
            </div>
      </div>
      
      <div class="name index_box_name"><a href="'.$link.'" class=" " title="'.uh($v['title']).'">'.uh($v['title']).'</a></div>
      </div></div>
      </div>';
        }
    }
            
        
     
    echo '</div>
  </div>
</div>';
$l = $this->app()->getAdvert(array('code'=>'ADV_INDEX_01','box_id'=>$vb['box']['id']));
if(!empty($l)){ 
echo '<div class="col-sm-12 index_box_adv ovh">';
 foreach($l as $k=>$v){
    echo '<div class="adv_block">
    '.(
    $v['html'] != "" ? uh($v['html']) : '<a target="'.$v['target'].'" href="'.$v['link'].'"><img data-src="'.getImage(array(
        'src'=>$v['image'],
        'w'=>400,
        'output'=>'src'
        )).'" alt="'.$v['title'].'"/></a>'
    ).'
    </div>';
 }
echo '</div>';
}
    }
}
?>

</div></div>