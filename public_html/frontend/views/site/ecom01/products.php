<div class="container">
<?php
include_once '_lsidebar.php';
?>

<div id="content" class="col-middle col-sm-9 pull-right">
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
    if(CONTROLLER == 'search'){
    	echo ' » <a >Kết quả tìm kiếm</a>';
    }
    ?>
      </div>
 
<div class="product-grid row" id="infinite">
<?php
//view(Yii::$app->controller->action->id,true);
$l = $this->app()->getArticles(array('p'=>getParam('p',1), 'category'=>__CATEGORY_ID__,'search'=>__IS_SEARCH__,'key'=>'limit-products','count'=>true));
if(!empty($l['listItem'])){
    foreach($l['listItem'] as $k=>$v){
        $link = cu(DS.$v['url']);    
        $pc = $v['price1'] > 0 ? number_format(100 - $v['price2']/$v['price1']*100 ,0) * -1 : 0;
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
        echo '<div class="col-sm-4">
        <div class="roxw">
        <div class="prod-wrap ">
    <div class="image-con fixed_img_size">
         '.($pc < 0 ? '<div class="saving"><div class="saving_text">'.$pc.'<sup class="percent">%</sup></div></div>' : '').'
        		<a href="'.$link.'"> <img data-src="'.$img_src.'" alt="'.uh($v['title']).'" class="lazyload-img-product" />               
                 
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
        <div class="cart">
	    	<div class="button">
            <input type="button" value="Mua hàng" data-id="'.$v['id'].'" data-amount="1" onclick="add_to_cart(this);" class="addbutton" />
            </div>
        </div>
    </div>
        
    <div class="name-desc-con">	<strong class="name"><a href="'.$link.'" class="" title="'.uh($v['title']).'">'.uh($v['title']).'</a></strong>	
     
    </div></div>
</div></div>';
    }
}
?>
 				

</div>
<input type="hidden" value="<?php echo getParam('p',1);?>" name="currentPage" class="currentPage"/>
<input type="hidden" value="<?php echo $l['totalPage'];?>" name="totalPage" class="totalPage"/>
<div id="marker-end"></div>
<div class="clearfix"></div>
<!-- PRODUCT LIST FINISH -->

  <div class="pagination hide"><div class="links"> <b>1</b>  <a href="http://dongunu.com/thoi-trang-nu?limit=25&amp;page=2">2</a>  <a href="http://dongunu.com/thoi-trang-nu?limit=25&amp;page=3">3</a>  <a href="http://dongunu.com/thoi-trang-nu?limit=25&amp;page=2">&gt;</a> <a href="http://dongunu.com/thoi-trang-nu?limit=25&amp;page=3">&gt;|</a> </div><div class="results">Hiển thị 1 đến 25 trong 71 (3 Trang)</div></div>
      <!-- CIRCULAR STAGE THEME OPTIONS -->
 
</div></div></div>