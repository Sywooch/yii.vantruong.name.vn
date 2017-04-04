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
        $l = $this->app()->getArticles(array('category'=>__CATEGORY_ID__,'key'=>'limit-news'));
        if(!empty($l['listItem'])){
        	echo '<div class="box-list-tin">';
        	foreach ($l['listItem'] as $k=>$v){
        		$link = cu($v['url']);
        	 
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
        ?>
        
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
</div></div></div>