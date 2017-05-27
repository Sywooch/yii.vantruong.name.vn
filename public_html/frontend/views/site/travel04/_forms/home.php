<div id="middle" class="full_width">
	<div class="container_12 container">
    
		<!-- breadcrumbs -->
        <div class="breadcrumbs"></div>
        <!--/ breadcrumbs --> 
        
        <!-- content -->
        <div class="content">
        	
            <div class="post-detail">
            	<div class="entry">
                	<?php 
                	$lb = Yii::$app->zii->getBoxIndex(array('attr'=>array('is_home')));
                	 
                	if(!empty($lb)){
                		foreach ($lb as $kb=>$vb){
                			echo '<div class="index_box style_'.$vb['box']['style'].'">';
                			switch ($vb['box']['style']){
                				case 1:	
                					echo '<div class="title"><h2 class="cafeta upper">'.uh($vb['box']['title']).'</h2><span class="title_right hide"><a href="#">See all Latest offers</a></span></div>';
                					$l = Yii::$app->zii->getFilters(array('id'=>isset($vb['box']['filters']) ? $vb['box']['filters'] : 0));
                					if(!empty($l)){
                						echo '<div class="boxed_list">';
                						foreach ($l as $k=>$v){
                							echo '<div class="boxed_item col-xs-6 col-sm-3 col-md-3">
                        	<div class="boxed_icon">'.getImage(array('src'=>$v['icon'],'w'=>48,'h'=>48)).'</div>
                        	<div class="boxed_title"><strong>'.uh($v['title']).'</strong></div>
                            <span><a  >0 offers available</a></span></div>';
                         
                						}
                        			echo '<div class="clear"></div></div>';
                        			$b = Yii::$app->zii->getBox('last_minute');
                        			if(!empty($b)){
                        				echo '<div class="boxed_list boxed_list2">';
                        				$l = Yii::$app->zii->getFilters(array('id'=>isset($b['filters']) ? $b['filters'] : 0));
                						echo '<div class="boxed_item col-xs-6 col-sm-3 col-md-3"><div class="boxed_title_arrow"><strong>'.uh($b['title']).'</strong></div></div>';
                						if(!empty($l)){
                							foreach ($l as $k=>$v){
                								echo '<div class="boxed_item col-xs-6 col-sm-3 col-md-3">
                        	<div class="boxed_icon">'.getImage(array('src'=>$v['icon'],'w'=>48,'h'=>48)).'</div>
                        	<div class="boxed_title"><a ><strong>'.uh($v['title']).'</strong></a></div>
                            <span><a>'.uh($v['info']).'</a></span></div>';
                							}
                						}
                    	 
                        echo '<div class="clear"></div></div>';
                        			}
                        			//echo '';
                        			echo '<div class="divider_space_big"></div>';
                					}
                					break;
                				case 2:
                					if(!empty($vb['listItem'])){
                					echo '<div class="title"><h2 class="cafeta upper">'.uh($vb['box']['title']).'</h2><span class="title_right hide"><a href="#">See all Latest offers</a></span></div>';
                					 
                					
                						echo '<div class="grid_list row">';
                						foreach ($vb['listItem'] as $k=>$v){
                							$link = cu($v['url']); $title = $v['title'];
                							$v['title'] = isset($v['short_title']) && $v['short_title'] != "" ? $v['short_title'] : $v['title'];
                							echo '<div data-toggle="setRatio" data-ratio="0.7" class="list_item mcol-md-20 mcol-sm-20 col-xs-12">
	                        	<div class="item_img">'.getImage(array(
                						                        			'src'=>$v['icon'],'w'=>400,
                						                        			'attr'=>array(
                						                        					'class'=>'w100','alt'=>uh($v['title'])
                						                        					 
                						                        			))).'<a href="'.$link.'" class="link-img">more...</a>
	                            </div>
	                            <div class="item-caption w100 pr">
	                            <p class="caption center"><a class="center" href="'.$link.'">'.uh($v['title']).'</a>  </p>
	                            </div>
	                        </div>';
                					
                						}
                					
                						echo '<div class="clear"></div></div>';
                					
                					echo '<div class="divider_space"></div>';
                					}
                					
                					break;
                				default:
                					if(!empty($vb['listItem'])){
                					
                			echo '<div class="title"><h2 class="cafeta upper">'.uh($vb['box']['title']).'</h2>'.($vb['box']['menu_id'] > 0 ? '<span class="title_right "><a href="'.cu('',array('category_id'=>$vb['box']['menu_id'])).'">'.getTextTranslate(181).'</a></span>' : '').'</div>';
                			echo '<div class="block_hr filter_mid">
						<form action="./search" method="get" class="">

                        	<div class="fl input_styled checklist">
			                  	<label class="label_title">'.getTextTranslate(183).':</label>';
$l = Yii::$app->zii->getFilters(array('parent_id'=>-1, 'id'=>isset($vb['box']['filters']) ? $vb['box']['filters'] : 0));      
if(!empty($l)){
	foreach ($l as $k=>$v){
		echo '<div class="rowCheckbox">
<input class="item_filter_offers_hidden_'.$vb['box']['id'].'" data-group="item_filter_offers_hidden_'.$vb['box']['id'].'" data-target=".filter_offers_hidden_'.$vb['box']['id'].'" onchange="getFilterCheckedValue(this);" type="checkbox" id="filter_offer_'.$vb['box']['id'].'_'.$v['id'].'" value="'.$v['id'].'">
<label for="filter_offer_'.$vb['box']['id'].'_'.$v['id'].'">'.uh($v['title']).'</label></div>';
	}
}
                			
echo '<input type="hidden" class="filter_offers_hidden_'.$vb['box']['id'].'" name="filters" value=""/></div>
										
					        <div class="fl rangeField">
                            	<label class="label_title">'.getTextTranslate(29).':</label>
				              	<div class="range-slider" >
				                <input class="price_range" id="price_range_'.$vb['box']['id'].'" type="text" name="price_range" value="'.(price_range()['default']).'">
				                </div>                   
				                <div class="clear"></div>
				            </div>
                                
                            <div class="fr rowSubmit">
				             	<input type="submit" value="'.getTextTranslate(182).'" class="btn-submit">
				            </div>
								
                            <div class="clear"></div>
		<input type="hidden" name="box_id" value="'.$vb['box']['id'].'" /> 
			            </form>
                          	                
			        </div>'; 
$j = '$("#price_range_'.$vb['box']['id'].'").slider({ 
						  			from: '.price_range()['from'].',
									to: '.price_range()['to'].',
									limits: false, 
									scale: [\''.price_range()['scale_from'].'\', \''.price_range()['scale_to'].'\'],
									heterogeneity: [\'50/3000\'],
									step: '.price_range()['step'].',
									smooth: true,
									dimension: \''.price_range()['dimension'].'\',
									skin: "round_green"
								});';
$this->registerJs($j
		//, ['depends' => [\yii\web\JqueryAsset::className()]]
		);
                			
                			echo '<div class="grid_list row">';
                			foreach ($vb['listItem'] as $k=>$v){
                				$link = cu($v['url']); $title = $v['title'];
                				$v['title'] = isset($v['short_title']) && $v['short_title'] != "" ? $v['short_title'] : $v['title'];
	                			echo '<div title="'.$title.'" data-toggle="setRatio" data-ratio="0.7" class="list_item col-md-4 col-sm-6 col-xs-12"> 
	                        	<div class="item_img">'.getImage(array(
                    			'src'=>$v['icon'],'w'=>400,
                    			'attr'=>array(
                    					'class'=>'w100','alt'=>uh($v['title'])
                    					
		))).'<a href="'.$link.'" class="link-img">more...</a>
	                            </div>
	                            <div class="item-caption w100 pr">
	                            <p class="caption"><a href="'.$link.'">'.uh($v['title']).'</a> '.(showPrice($v['price2']) ).'</p>
	                            </div>
	                        </div>'; 
                        
                			}
                        
                        echo '<div class="clear"></div></div>';
                			
                	echo '<div class="divider_space"></div>';
                					}
                			break; 
                			}
                			echo '</div>';
                		}
                	}
                	?>
    
                </div>
            </div>
        	
        </div>
        <!--/ content -->
        
        <div class="clear"></div>        
    </div>
</div>
 
