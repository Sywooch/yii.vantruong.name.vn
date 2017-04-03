 
<div class="table-responsive ">
 
  <table class="table table-bordered vmiddle table-hover">
  <?php
  echo afShowThread(array(
    array(
        'name'=>'Icon',
        'class'=>'cicon center',
    ),
    array(
        'name'=>'Code',
        'class'=>'center  ','order'=>true,'field'=>'a.code'	
    ),
    array(
        'name'=>getTextTranslate(54,ADMIN_LANG),
        'class'=>' ','order'=>true,'field'=>'a.title'	
    ),
  		//array(
  		//		'name'=>'TiÃªu Ä‘á»� ngáº¯n',
  		//		'class'=>' ctime','order'=>true,'field'=>'a.short_title'
  		//),
    array(
        'name'=>getTextTranslate(65,ADMIN_LANG),
        'class'=>' ','order'=>true,'field'=>'category_name'	
    ),
  		 
    array(
        'name'=>getTextTranslate(29,ADMIN_LANG),
        'class'=>'center  ','order'=>true,'field'=>'a.price2'	
    ),
    array(
        'name'=>getTextTranslate(66,ADMIN_LANG),
        'class'=>'center ctime',
    	'order'=>true,'field'=>'a.time'	
    ),
    array(
        'name'=>getTextTranslate(117,ADMIN_LANG),
        'class'=>'center w50p','order'=>true,'field'=>'a.position'	
    ),
    array(
        'name'=>'Hot',
        'class'=>'center cactive',
    ),
  	array(
  		'name'=>'Home','title'=> getTextTranslate(67,ADMIN_LANG),
  		'class'=>'center cactive',
  	),
  	 
  	array(
  		'name'=>getTextTranslate(57,ADMIN_LANG),
  		'class'=>'center cactive','order'=>true,'field'=>'a.is_active'	
  	),

  ),array('STT'=>false));
  ?>

    <tbody>
<?php
$role = [
		'type'=>'single',
		'table'=>$model->tableName(),
		'controller'=>Yii::$app->controller->id,
			
];
if(!empty($l['listItem'])){
	foreach($l['listItem'] as $k=>$v){
		$role['id']=$v['id'];
		$role['action'] = 'Ad_quick_change_item';
		$link = get_link_edit($v['id']);
		$list_category = $model->getItemCategorys($v['id'],0);
		
		echo '<tr class="tr_item_'.$v['id'].'">
        <td class="center">
        <input type="checkbox" class="checked_item" name="check_item[]" value="'.$v['id'].'" />
        </td>
        <td class="center hide">'.($k+1 + ($l['p'] - 1)  * $l['limit']).'</td>
        <td><a href="#" rel="popover" tabindex="0" class="center block" role="button"  data-img="'.(isset($v['icon']) ? $v['icon'] : '').'" data-trigger="focus" title="Xem áº£nh lá»›n"
        data-content="">'
        .getImage(array(
                'src'=>isset($v['icon']) ? $v['icon'] : '',
                'h'=>30,'w'=>30,
                'attr'=>array(
                    'class'=>'img-rounded img-small-icon',
                    'alt'=>'' ,
                    'data-holder-rendered'=>"true"  ,
                 ),
              )).
        '</a></td>
        <td class="nowrap"><a href="'.$link.'">'.uh($v['code']).'</a></td>
        <td class=""><a href="'.$link.'">'.uh($v['title']).'</a>
  				<i class="ssummany">('.$v['day'].'N - '.$v['night'].'Đ - '.$v['tour_hotel'].'S)</i>
  				</td>';
   		
   		//echo '<td class="center"><input data-field="short_title" data-role="" onblur="quickChange(this)" class="w100 center sui-input sui-input-focus" data-old="'.$v['short_title'].'" value="'.$v['short_title'].'"/></td>';
        echo '<td class="">';
        if(isset($list_category)) echo implode(' | ', $list_category);
        echo '</td>        
		<td class="center"><input '.showJqueryAttr($role).' data-field="price2" data-role="" onblur="Ad_quick_change_item(this)" class="w100 center sui-input sui-input-focus" data-old="'.$v['price2'].'" value="'.$v['price2'].'"/></td>        
        <td class="center"><p class="stime" title="'.date("d/m/Y H:i",strtotime($v['time'])).'">'.date("d/m/Y",strtotime($v['time'])).'</p><p class="post_by"><i class="f11p">- '. ($v['post_by_name']).' -</i></p></td>		                		
        <td class="center"><input '.showJqueryAttr($role).' data-field="position" data-role="" onblur="Ad_quick_change_item(this)" class="w100 center sui-input sui-input-focus" data-old="'.$v['position'].'" value="'.$v['position'].'"/></td>
        <td class="center">'.getCheckBox(array(
            'name'=>'state',
            'value'=>isset($v['is_hot']['state']) ? $v['is_hot']['state'] : 0,
            'type'=>'singer',
            'class'=>'switchBtn ',
            'attr'=>array(
                'data-table'=>'{{%articles_to_attrs}}', 
                'data-boolean'=>1,
                'data-field'=>'state',
                'onchange'=>'Ad_quick_change_item(this)',
                'data-action'=>'Ad_quick_change_attr',
                'data-item_id'=>$v['id'],'data-attr_id'=>'is_hot',
                
            ),
        )).'</td>
        <td class="center">'.getCheckBox(array(
            'name'=>'state',
            'value'=>isset($v['is_home']['state']) ? $v['is_home']['state'] : 0,
            'type'=>'singer',
            'class'=>'switchBtn ',
            'attr'=>array(
                'data-table'=>'{{%articles_to_attrs}}', 
                'data-boolean'=>1,
                'data-field'=>'state',
                'onchange'=>'Ad_quick_change_item(this)',
                'data-action'=>'Ad_quick_change_attr',
                'data-item_id'=>$v['id'],'data-attr_id'=>'is_home',
                
            ),
        )).'</td>	
        		
         <td class="center">'.getCheckBox(array(
            'name'=>'is_active',
            'value'=>$v['is_active'],
            'type'=>'singer',
            'class'=>'switchBtn ',
            'attr'=>showJqueryAttr($role,true)+array(
                'data-old'=>$v['is_active'],
                'data-boolean'=>1,
                'data-field'=>'is_active',
                'onchange'=>'Ad_quick_change_item(this);'
                //'data-table'=>$this->controller()->model->tableName(),
                
            ),
        )).'</td>';
        
		$role['action'] = 'Ad_quick_delete_item';
        echo '<td class="center">'.afShowListButton($v['id'],
        array(
        'btn'=>[
        	'del'=>['attr'=>$role]
        ]
            		)).'</td>
      </tr>';

  }
}


?>
    </tbody>
  </table>
</div>
<?php
echo afShowPagination(array(
		'p'=>$l['p'],
		'total_records'=>$l['total_records'],
		'total_pages'=>$l['total_pages'],
		'limit'=>$l['limit'],
		'btn'=>['del'=>['attr'=>$role]]
));