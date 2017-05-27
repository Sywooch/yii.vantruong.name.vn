<?php
$this->registerJsFile(__RSDIR__ . '/js/jquery.customInput.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/jquery.slider.bundle.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/jquery.slider.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile(__RSDIR__ . '/css/jslider.css', ['depends' => [\yii\bootstrap\BootstrapAsset::className()]]);
/**
 * <script src="http://dalaco.travel/themes/home/travel04/assets/js/jquery.slider.bundle.js"></script>
<script src="http://dalaco.travel/themes/home/travel04/assets/js/jquery.slider.js"></script>
<link rel="stylesheet" href="http://dalaco.travel/themes/home/travel04/assets/css/jslider.css">
 */

//
if(__CATEGORY_ID__>0){
	
	
	
	?>
	<div id="header" class="header">
	<div class="container_12 pr">    	
        <div class="logo"> 
   	    	<a href="./" class="">
   	    	<span class="logo_chu hide"><span class="upper">Dalaco</span>.travel</span>
   	    	<img src="<?php echo Yii::$site['logo']['logo']['image']; ?>" class="w100" alt="<?php echo DOMAIN;?>"></a>
            <h1><?php echo get_site_value('seo/seo_title'); ?></h1>
        </div>
        
      	<div class="header_right pr">
	        
	        <div class="topsearch">
            	<form id="searchForm" action="./search" method="get">
                	<button type="submit" id="searchSubmit" class="btn-search center"><i class="glyphicon glyphicon-search"></i></button>
                	
                    <input type="text" name="q" id="stext" value="<?php echo getParam('q');?>" class="stext">                    
				</form>
            </div>  
            
            <div class="toplogin">
	        	<p><a href="#">SIGN IN</a> <span class="separator">|</span> <a href="#">REGISTER</a></p>
	        </div>    
            
            <div class="header_phone">
            <?php 
            $b = $this->app()->getBox('top_hotline');
            if(!empty($b)){
            	echo uh($b['text'],2);
            }
            ?>
	        	
	        </div>	        
	        <?php 
	        $langs = Yii::$app->zii->getUserLanguages();
	        if(!empty($langs)){
	        	echo '<div class="slanguage">';
	        	foreach ($langs as $k=>$v){
	        		if(isset($v['is_active']) && $v['is_active'] == 1){
	        			echo '<a onclick="changeLanguage(\''.$v['code'].'\',this)" data-lang="'.__LANG__.'" rel="nofollow" class="flag '.$v['code'].'">'.$v['title'].'</a>';
	        		}
	        	}
	        	echo '</div>';
	        }
	        ?>
	        
            <div class="clear"></div>
        </div>
        
        <div class="topmenu">
        <nav class="navbar navbar-default navbar-custom1">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
             
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav dropdown navbar-nav navbar-right mainmenu">
         
            	<li class="menu-level-0 menu-item-home current-menu-item"><a href="./"><i class="glyphicon glyphicon-home fl"></i></a></li>
            	<?php 
            	$sl = 'a.id,a.parent_id,a.type,a.type,a.title,a.short_title,a.url,a.check_sum,a.bizrule';
            	 
            	$l = \app\models\SiteMenu::getList([
            			'key'=>'main',
            			//'parent_id'=>$v['id']
            	]);
            	if(!empty($l)){
            		foreach ($l as $k=>$v){
            			$v['title']= isset($v['short_title']) && $v['short_title'] != "" ? $v['short_title'] : $v['title'];
            			$link = $v['type'] == 'link' ? $v['link_target'] : $this->createUrl($v['url']);
            			if(isset($v['style']) && $v['style'] == 1){
            				$style = 'mega-nav';
            			}else{
            				$style = '';
            			}
            			 
            			$l1 = \app\models\SiteMenu::getList([
            					//'key'=>'main',
            					'parent_id'=>$v['id']
            			]);
            			$tag = isset($v['heading']) && $v['heading'] > 0 ? 'h'.$v['heading'] : 'span';
            			echo '<li class="menu-level-0 '.$style.'"><a '.(isset($v['action']) && $v['action'] != "" ? $v['action'] .'="'.$v['action_detail'].'" ' : '').' href="'.$link.'">';
						echo '<'.$tag.' class="menu-item">'.uh($v['title']).'</'.$tag.'></a>';
            			if(!empty($l1)){
            				echo '<ul class="submenu-1">';
            				foreach ($l1 as $k1=>$v1){
            					$v1['title']= isset($v1['short_title']) && $v1['short_title'] != "" ? $v1['short_title'] : $v1['title'];
            					$link = $v1['type'] == 'link' ? $v1['link_target'] : $this->createUrl($v1['url']);
            					echo '<li class="menu-level-1"><a href="'.$link.'">';
            					if(isset($v1['icon']) && $v1['icon'] != ""){
            						echo getImage(array('src'=>$v1['icon'],'w'=>80,'h'=>68));
            					}
            					$tag = isset($v1['heading']) && $v1['heading'] > 0 ? 'h'.$v1['heading'] : 'span';
            					echo '<'.$tag.' class="menu-item">'.uh($v1['title']).'</'.$tag.'></a>';
            					$l2 = \app\models\SiteMenu::getList([
            							//'key'=>'main',
            							'parent_id'=>$v1['id']
            					]);
            					if(!empty($l2)){
            						echo '<ul class="submenu-2">';
            						foreach ($l2 as $k2=>$v2){
            							$v2['title']= isset($v2['short_title']) && $v2['short_title'] != "" ? $v2['short_title'] : $v2['title'];
            							$link = $v2['type'] == 'link' ? $v2['link_target'] : $this->createUrl($v2['url']);
            							$tag = isset($v2['heading']) && $v2['heading'] > 0 ? 'h'.$v2['heading'] : 'span';
            							echo '<li class="menu-level-2"><a href="'.$link.'"><'.$tag.'>'.uh($v2['title']).'</'.$tag.'></a></li>';
            						}
            						echo '</ul>';
            					}
            					echo '</li>';
            				}
                    		echo '</ul>';
            			}
            			echo '</li>';
            		}
            	}
            	?>
            </ul>
            </div>
            </nav>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
</div>
	<?php 
}else 
$this->registerCssFile(__RSDIR__ .'/css/intro.css');
 
