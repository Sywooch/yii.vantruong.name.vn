<?php
use app\modules\admin\models\Content;
?>
 
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
        'name'=>getTextTranslate(54),
        'class'=>' ','order'=>true,'field'=>'a.title'	
    ),
    array(
        'name'=>getTextTranslate(65),
        'class'=>' ','order'=>true,'field'=>'category_name'	
    ),
  		 
    array(
        'name'=>getTextTranslate(29),
        'class'=>'center  ','order'=>true,'field'=>'a.price2'	
    ),
    array(
        'name'=>getTextTranslate(66),
        'class'=>'center ctime',
    	'order'=>true,'field'=>'a.time'	
    ),
    array(
        'name'=>getTextTranslate(117),
        'class'=>'center w50p','order'=>true,'field'=>'a.position'	
    ),
    
  	array(
  		'name'=>'Home','title'=>getTextTranslate(67),
  		'class'=>'center cactive',
  	),
  	 
  	array(
  		'name'=>getTextTranslate(57),
  		'class'=>'center cactive','order'=>true,'field'=>'a.is_active'	
  	),

  ),array('STT'=>getTextTranslate(53),'ACTION'=>getTextTranslate(58)));
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
		$list_category = Content::getItemCategorys($v['id'],0);
		
    echo '<tr class="tr_item_'.$v['id'].'">
        <td class="center">
        <input type="checkbox" class="checked_item" name="check_item[]" value="'.$v['id'].'" />
        </td>
        <td class="center">'.($k+1 + ($l['p'] - 1)  * $l['limit']).'</td>
        <td><a href="#" rel="popover" tabindex="0" class="center block" role="button"  data-img="'.(isset($v['icon']) ? $v['icon'] : '').'" data-trigger="focus" title="Xem ảnh lớn"
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
  				 
  				</td>
        <td class="">';
        if(isset($list_category)) echo implode(' | ', $list_category);
        echo '</td>        
		 
        <td class="center"><p class="bold red numberFormat">'.($v['price2']).'</p></td>
        <td class="center"><p class="stime" title="'.date("d/m/Y H:i",strtotime($v['time'])).'">'.date("d/m/Y",strtotime($v['time'])).'</p><p class="post_by"><i class="f11p">- '. ($v['post_by_name']).' -</i></p></td>		                		
        <td class="center"><input '.showJqueryAttr($role).' data-field="position" data-role="" onblur="Ad_quick_change_item(this)" class="w100 center sui-input sui-input-focus" data-old="'.$v['position'].'" value="'.$v['position'].'"/></td>
         
        <td class="center">'.getCheckBox(array(
            'name'=>'state',
            'value'=>isset($v['is_home']) ? $v['is_home'] : 0,
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