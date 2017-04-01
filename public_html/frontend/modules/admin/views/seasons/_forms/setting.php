<div role="tabpanel" class="tab-panel " id="tab-setting">
        <div class="p-content">
        <div class="row">
        <div class="col-sm-12"> 
  
        <div class="form-group ">
        <?php 
        $v1 = $model->get_category_item(getParam('category_id'));
        if(!empty($v1)){
        	echo '<div class="f12e ">';
        	echo '<p class="help-block">Bạn đang thao tác trong vùng thiết lập cho: 	';
        	echo '<b>' . $v1['title'] . ':  </b><b class="red f12e">' . ($v1['incurred'] < 0 ? ' -' : ' +') . $v1['incurred'] . '%' .'</b></p>';
        	
        	echo '</div>';
        }
        ?>
        <p class="help-block">Các dịch vụ được áp dụng:</p>
        
       <table class="table table-hover table-bordered vmiddle table-striped"> <thead> 
       
       <?php
       echo '<tr> <th class="w50p">#</th> <th>Tên dịch vụ</th>
		<th class="center w200p">Áp dụng</th>  
		 </tr> 
		</thead> <tbody> ';
       foreach ($l2 = $model->get_incurred_services() as $k2=>$v2){
       	//view($v2);
       	echo '<tr>
        		<th class="center">'.($k2+1).'</th>
        		<td>'.$v2['title'].'</td>
        		<td class="center">
        			'.getCheckBox(array( 
            'name'=>'s['.$k2.'][supplier_id]',
            'checked'=> $model->check_holiday_supplier(getParam('category_id'),$v2['id'],2) ? 1 : 0 ,
			'value'=>$v2['id'],        				
            'type'=>'singer',
            'class'=>'switchBtn  ',
            'cvalue'=>true,
        )).'
        		 
        		</td>
        		</tr>';
       }
        echo '</tbody>';
       ?>               
        </table>  
<?php 
echo '<input type="hidden" name="sx[season_id]" value="'.getParam('category_id').'" />';
echo '<input type="hidden" name="sx[type_id]" value="2" />';
?>
 
           
        </div>
              
         </div>
 
    </div></div></div>