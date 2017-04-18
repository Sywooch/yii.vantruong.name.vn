<?php
use yii\helpers\Html;
use app\modules\admin\models\AdminMenu;
use app\modules\admin\models\AdModule;
//use yii\grid\GridView;
//use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AdminMenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Admin Menus');
//view(AdminMenu::find()->max('id'));
?>
<div class="admin-menu-index col-sm-12 col-xs-12"><div class="row">
<div class="table-responsive ">
<?php
//AdminMenu::update_all_level_menu();
$l = AdModule::getList1(Yii::$app->request->get());
?>
  <table class="table table-bordered vmiddle table-hover">
  <?php
  echo afShowThread(array(
    array(
        'name'=>'Tiêu đề',
        'class'=>'',
    ),
    array(
        'name'=>'Route',
        'class'=>'',
    ),
  	array(
        'name'=>'Child code',
        'class'=>'',
    ),
  		array(
  				'name'=>'lft',
  				'class'=>'center cposition',
  		),
  		array(
  				'name'=>'rgt',
  				'class'=>'center cposition',
  		),
    array(
        'name'=>'Thứ tự',
        'class'=>'center cposition',
    ),
  		array(
  				'name'=>'Root',
  				'class'=>'center cactive',
  		),
  		array(
  				'name'=>'Auth',
  				'class'=>'center cactive',
  		),
  		array(
  				'name'=>'Ẩn',
  				'class'=>'center cactive',
  		),
    array(
        'name'=>'Kích hoạt',
        'class'=>'center cactive',
    ),

  )); 
  ?>

    <tbody>
<?php
$role = [
		'type'=>'single',
		'table'=>$model->tableName(),
		'controller'=>Yii::$app->controller->id,
			
		'action'=>'Ad_quick_change_item'
];
 
if(!empty($l['listItem'])){ 
	foreach ($l['listItem'] as $k=>$v){
 
  $role['id']=$v['id'];
  $role['action'] = 'Ad_quick_change_item';
  $_c = isset($v['count_child']) ? $v['count_child'] : 0;//$this->model()->count_all_child($v['id']);
    echo '<tr class="tr_item_'.$v['id'].'">
        <td class="center">
        <input type="checkbox" class="checked_item" name="check_item[]" value="'.$v['id'].'" />
        </td>
        <td class="center">'.($k+1).'</td>
        <td class="'.($v['level']==0 ? 'bold' : '').'"><a href="'.get_link_edit($v['id']).'">'.spc($v['level']).$v['title'].($_c > 0 ? ' <i class="red">('.$_c.')</i>' : '').'</a></td>
        <td>'.$v['route'].'</td>
		<td>'.$v['child_code'].'</td>
    	<td class="center"> <input '.showJqueryAttr($role).' readonly data-field="lft" data-role="" onblur="Ad_quick_change_item(this)" class="w100 input-auto-save-change center numberFormat sui-input sui-input-focus" data-old="'.$v['lft'].'" value="'.$v['lft'].'"/></td>
        <td class="center"> <input '.showJqueryAttr($role).' readonly data-field="rgt" data-role="" onblur="Ad_quick_change_item(this)" class="w100 input-auto-save-change center numberFormat sui-input sui-input-focus" data-old="'.$v['rgt'].'" value="'.$v['rgt'].'"/></td>
        <td class="center"><input '.showJqueryAttr($role).' data-field="position" data-role="" onblur="Ad_quick_change_item(this)" class="w100 center sui-input sui-input-focus" data-old="'.$v['position'].'" value="'.$v['position'].'"/></td>
        
  		<td class="center">'.getCheckBox(array(
            'name'=>'is_fix',
            'value'=>$v['is_fix'],
            'type'=>'singer',
            'class'=>'switchBtn ',
            'attr'=>showJqueryAttr($role,true)+array(
                'data-old'=>$v['is_fix'],
                'data-boolean'=>1,
                'data-field'=>'is_fix',
                'onchange'=>'Ad_quick_change_item(this);'                
                
            ),
        )).'</td>
        <td class="center">'.getCheckBox(array(
            'name'=>'is_active',
            'value'=>$v['is_permission'],
            'type'=>'singer',
            'class'=>'switchBtn ',
            'attr'=>showJqueryAttr($role,true)+array(
                'data-old'=>$v['is_permission'],
                'data-boolean'=>1,
                'data-field'=>'is_permission',
                'onchange'=>'Ad_quick_change_item(this);'                
                
            ),
        )).'</td>
        <td class="center">'.getCheckBox(array(
            'name'=>'is_invisibled',
            'value'=>$v['is_invisibled'],
            'type'=>'singer',
            'class'=>'switchBtn ckcAjaxChangeStatus',
            'attr'=>showJqueryAttr($role,true)+array(
                'data-old'=>$v['is_invisibled'],
                'data-boolean'=>1,
                'data-field'=>'is_invisibled',
                'onchange'=>'Ad_quick_change_item(this);'
                //'data-table'=>$this->controller()->model->tableName(),
                
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
?>

</div></div>
