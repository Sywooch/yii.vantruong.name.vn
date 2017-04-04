<?php include_once '_breadcrumb.php';?>    	
<div class="container" >
   <div class="row"> <div class="fl100" id="content"">     
<?php include_once '_lsidebar.php';?>    	

    	<div class="col-md-9 col-sm-12">
            <div class="box_pro box_pro_list row">

            <div class="box_page box_pro_cat col-sm-12">
<div class="head_"><h1><?php echo __IS_SEARCH__ ? 'Kết quả tìm kiếm' : __CATEGORY_NAME__;?></h1></div>
<div class="content" ><?php echo uh(Yii::$_category['text'],2);?></div>
                
                

             </div>
<?php 
$l = $this->app()->getArticles(array('p'=>getParam('p',1),'type'=>'products', 'category'=>__CATEGORY_ID__,'search'=>__IS_SEARCH__,'key'=>'limit-products','count'=>true));
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
?>
    
                
            
            </div>

           
            
            <div class="clearfix"></div>
<?php 
if($l['total_pages'] > 1){
	$p = getParam('p',1);
	echo '<div class="wp-pagenavi">
<span class="pages">'.getTextTranslate(63).' '.$p.' - '.$l['total_pages'].'</span>';
		if($p>1) echo '<a class="previouspostslink" href="'.cu([DS. __CATEGORY_URL__]).'">«</a>';
		for($i=1; $i<$l['total_pages'] + 1; $i++){
			if($i==$p){
				echo '<span class="current">'.$i.'</span>';
			}else{
				echo '<a class="page smaller" href="'.cu([DS. __CATEGORY_URL__,'p'=>$i]).'">'.$i.'</a>';
			}
		}
		echo ($p<$l['total_pages']) ? '<a class="nextpostslink" href="'.cu([DS. __CATEGORY_URL__,'p'=>$l['total_pages']]).'">»</a>' : '';
echo '</div>';
}
?>


        </div>
        <div class="clearfix"></div>        
    </div></div></div>