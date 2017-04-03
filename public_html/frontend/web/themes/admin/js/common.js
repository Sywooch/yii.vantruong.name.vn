function checkAllItemTree($t){
	$j = jQuery;
	$this = $j($t);
	$id = $this.attr('data-id');
	$role = $this.attr('data-role');
	$parent_id = $this.attr('data-parent');
	$ck = $this.is(':checked');
	$j('.'+$role+$id).each(function(i,e){
		$j(e).prop('checked', $ck).change();
	});
}
function parseSeoUrl($t){
	$j = jQuery;
	$this = $j($t); $val = $this.val();
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.baseUrl  + '/systemAjax',						 		 
	      data: {action:'parseSeoUrl',val:$val},
	      beforeSend:function(){
	    	 // showLoading();
	    	  //showFullLoading();
	      },
	      success: function (data) {
	    	  $d = JSON.parse(data);
	    	  if($d.state == false){
	    		  jQuery('.btn-submit').attr('disabled','');
	    		  $j('#error-respon').html('<i class="glyphicon glyphicon-remove"></i> Đường dẫn không hợp lệ.').removeClass('bg-success').addClass('bg-danger').show();
	    	  }else{	    		  
	    		  $j('#error-respon').html('<i class="glyphicon glyphicon-ok"></i> Bạn có thể sử dụng url này.').removeClass('bg-danger').addClass('bg-success').show();
	    		  jQuery('#inputSeoTitle').val($d.item['seo_title']);
	    		  jQuery('#inputSeoKeyword').val($d.item['seo_keyword']);
	    		  jQuery('#inputDescription').val($d.item['seo_description']);
	    		  jQuery('.btn-submit').removeAttr('disabled');
	    		  jQuery('#seo_table').val($d.item['table']);
	    		  jQuery('#seo_id').val($d.item['id']);
	    	  }
	    	  
	    	   
	    	  
	      },
	      complete:function(){
	    	 // hideLoading();  
	      },
	      error : function(err, req) {
	           
				}
	});
}
function checkUserExisteds($t){
	$j = jQuery;
	$this = $j($t); $val = $this.val(); $field = $this.attr('data-field'); $id = $this.attr('data-id');
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.baseUrl  + '/systemAjax',						 		 
	      data: {action:'checkUserExisteds',val:$val,field:$field,id:$id},
	      beforeSend:function(){
	    	 // showLoading();
	    	  //showFullLoading();
	      },
	      success: function (data) {	    
	    	  //alert(data)
	    	  $d = JSON.parse(data);
	    	  if($d.state > 0){
	    		  $j('.submitFormBtn').attr('disabled','');
	    		  $j('#error-respon').html('<i class="glyphicon glyphicon-remove"></i> Tài khoản không hợp lệ hoặc đã có người sử dụng.').removeClass('bg-success').addClass('bg-danger').show();
	    	  } else{
	    		  $j('.submitFormBtn').removeAttr('disabled');
	    		  $j('#error-respon').html('<i class="glyphicon glyphicon-ok"></i> Bạn có thể sử dụng tài khoản này.').removeClass('bg-danger').addClass('bg-success').show();
	    	  } 	    	   	    	   	    	 
	      },
	      complete:function(){
	    	 // hideLoading();  
	      },
	      error : function(err, req) {
	           
				}
	});
}
function submitEditForm($t){

    $this = jQuery($t);
    $role = $this.attr('role');
    jQuery('input.btnSubmit').val($role);
    if($role == 5){
       window.location = $_config.adminUrl + '/' + $_config.controller + $_config.returnUrl;
    }else{
      $submit = true;
      jQuery('.error.check_error').each(function(i,e){
         	 $submit = false;
         	 jQuery(e).focus();
         	 $er = jQuery(e).parent().find('.error_field');
         	 if($er.length == 0){
         		$er = jQuery('<div class="error_field"></div>');
         		jQuery(e).parent().append($er);
         	 }
         	 $erText = jQuery(e).attr('data-alert') ? jQuery(e).attr('data-alert') : '';
         	 $erText = $erText.replace(/{VAL}/g,jQuery(e).val());
         	 $er.html($erText);
              return false;
          });
          if($submit){
        	  jQuery('.error').removeClass('error');
        	  jQuery('.required').each(function(i,e){
         	   $sl = jQuery(e).attr('data-select') ? jQuery(e).attr('data-select') : false;
         	   $num = jQuery(e).attr('data-num') ? jQuery(e).attr('data-num') : false;
         	   //alert($(e).val());
         	   switch($sl){
         	   case 'select2':
         	   case 'chosen':   
         		   $val = jQuery(e).val();
         		   if($val == "" || $val == null){
         			  jQuery(e).addClass('error').focus();
         			  jQuery(e).next().addClass('error');
                        $submit = false;
                        return false;
                    }
         		   if($num != false && $val == 0){
         			  jQuery(e).addClass('error').focus();
         			  jQuery(e).next().addClass('error');
                        $submit = false;
                        return false;
         		   }
         		   break;
         	  default:
         			$val = jQuery(e).val() ;
         		   	if($val == ""){
         		   	jQuery(e).addClass('error').focus();
                        $submit = false;
                        return false;
                       }
         		   	if($num != false && $val == 0){
         		   	jQuery(e).addClass('error').focus();
         		   		//jQuery(e).addClass('error');
                         $submit = false;
                         return false;
         		   	}
         		   	$minlength = parseInt(jQuery(e).attr('data-min'));
         		   	if($minlength > 0 && $val.length < $minlength){
         		   		//
         		   		$div = '<div class="error_field error">Độ dài ít nhất '+$minlength+' ký tự.</div>';
         		   		jQuery(e).parent().find('.error_field').remove().append($div);
         		   	    jQuery(e).parent().append($div);
         		   		//
         		   		jQuery(e).addClass('error').focus();     		   		 
         		   		$submit = false;
         		   		return false;
         		   	}
         		    $compare = jQuery(e).attr('data-compare') ? jQuery(jQuery(e).attr('data-compare')) : false;
         		    if($compare != false){
         		    	if($val != $compare.val()){
             		   		//
             		   		$div = '<div class="error_field error">Giá trị không hợp lệ.</div>';
             		   		jQuery(e).parent().find('.error_field').remove();
             		   	    jQuery(e).parent().append($div);
             		   		//
             		   		jQuery(e).addClass('error').focus();     		   		 
             		   		$submit = false;
             		   		return false;
             		   	}
         		    }
         			break;
         	   }
            
            
           });
        	  if(jQuery('.input_password').length>0 && jQuery('.input_repassword').length>0){
        		  $j1 = jQuery('.input_password');$j2 = jQuery('.input_repassword');
        		  
        		  $val1 = $j1.val();$val2 = $j2.val();       
        		  if($val1.length > 0){
        			$minlength = parseInt($j1.attr('data-min'));
        			if($minlength > 0 && $val1.length < $minlength){
         		   		//
         		   		$div = '<div class="error_field error">Độ dài ít nhất '+$minlength+' ký tự.</div>';
         		   		$j1.parent().find('.error_field').remove();
         		   		$j1.parent().append($div);
         		   		//
         		   		$j1.addClass('error').focus();     		   		 
         		   		$submit = false;
         		   		return false;
         		   	}else{
         		   		$j1.parent().find('.error_field').remove();
         		   	}
        			if($val2 != $val1){
        				$div = '<div class="error_field error">Mật khẩu không khớp.</div>';
         		   		$j2.parent().find('.error_field').remove().append($div);
         		   		$j2.parent().append($div);
         		   		//
         		   		$j2.addClass('error').focus();     		   		 
         		   		$submit = false;
         		   		return false;
        			}else{
        				$j2.parent().find('.error_field').remove();
        			}
        	  }         }   
          }
          
       if($submit == true)  jQuery('#editFormContent').submit();
       else return false;
    }


 } 
