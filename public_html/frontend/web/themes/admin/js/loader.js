var $tree = false;
jQuery.get($cfg.cBaseUrl +'/ajax',{action:'load_css',controller:$cfg.controller,view:$cfg.action},function($r){
	load_css($r.script,1); 
},'json');
//
jQuery.get($cfg.cBaseUrl +'/ajax',{action:'load_js',controller:$cfg.controller,view:$cfg.action},function($r){LazyLoad.js($r.script, function(){
	// Common js	
	var $body = $('body'); $window = $(window);
	/*
	var date = new Date();
	var minutes = 0.6;
	date.setTime(date.getTime() + (minutes * 60 * 1000));
	Cookies.set('expried_alert_time_left', 'value', { expires: date });
	console.log(Cookies.get('expried_alert_time_left'));
	*/
	
	var $_progress = jQuery('.progcess-bar-life-time');
	//
	var SHOP_TIME_LEFT = parseInt($_progress.attr('data-time'));
	var $s = SHOP_TIME_LEFT * 100 / 365;
	var $w = (15 * 100 / 365) + SHOP_TIME_LEFT;	 
	var $d = 100 - $s - $w;
	//
	var $progress = '<div data-toggle="tooltip" title="Bạn còn '+SHOP_TIME_LEFT+' ngày sử dụng" data-placement="bottom" class="progress life-time"><div class="progress-bar progress-bar-success" style="width: '+$s+'%"><span class="sr-only">'+$s+'% Complete (success)</span></div>';
	$progress += '<div class="progress-bar progress-bar-warning progress-bar-striped" style="width: '+$w+'%"><span class="sr-only">'+$w+'% Complete (warning)</span></div>';
	$progress += '<div class="progress-bar progress-bar-danger" style="width: '+$d+'%"><span class="sr-only">'+$d+'% Complete (danger)</span></div></div>';
	jQuery('.progcess-bar-life-time').html($progress);
	if(!Cookies.get('expried_alert_time_left')){
		var $tlf = jQuery('.sys-alert-time-left');
		
		//console.log(SHOP_TIME_LEFT < 31);
		//var $px = SHOP_TIME_LEFT * 100 / 365; 
		$alert = '';
		if(SHOP_TIME_LEFT < 31){
			
			$alert += '<div class="alert life-time alert-danger alert-dismissible fade in" role="alert">'; 
			$alert += SHOP_TIME_LEFT > 0 ? '<button title="Đóng" type="button" onclick="setCookieExpried(this)" data-time="60" data-name="expried_alert_time_left" data-value="1" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' : ''; 
			$alert += '<strong>Gấp gấp gấp!</strong> ';
			$alert += 'Tài khoản của bạn sắp hết hạn sử dụng. Hệ thống sẽ tự động tạm ngưng dịch vụ sau '; 
			$alert += '<b>'+SHOP_TIME_LEFT+'</b> ngày nữa ('+$tlf.attr('data-alert')+')'; 
			$alert += '. Vui lòng liên hệ hotline: <b class="underline"><a class="red " href="tel:0985527788">098 552 77 88</a> - Mr. Trường</b> để được hỗ trợ gia hạn dịch vụ.';
			$alert += '<br/><i>Sau <b>15</b> ngày kể từ ngày tạm ngưng dịch vụ, tài khoản của bạn sẽ bị khóa toàn bộ các dịch vụ.</i>';
			$alert += '</div>';
			if(SHOP_TIME_LEFT > 0 && SHOP_TIME_LEFT < 7){
				showZModal('Cảnh báo !!!',$alert);
			}else{
				if(SHOP_TIME_LEFT<1) {
					$alert = '<div class="alert life-time alert-danger alert-dismissible fade in" role="alert">'; 
					$alert += SHOP_TIME_LEFT > 0 ? '<button title="Đóng" type="button" onclick="setCookieExpried(this)" data-time="60" data-name="expried_alert_time_left" data-value="1" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' : ''; 
					$alert += '<strong>Gấp gấp gấp!</strong> ';
					$alert += 'Tài khoản của bạn đã hết hạn <b>'+(-1*SHOP_TIME_LEFT)+'</b> ngày.<br/>'; 
					//$alert += '<b>'+SHOP_TIME_LEFT+'</b> ngày nữa '+$tlf.attr('data-alert'); 
					$alert += 'Vui lòng liên hệ hotline: <b class="underline"><a class="red " href="tel:0985527788">098 552 77 88</a> - Mr. Trường</b> để được hỗ trợ gia hạn dịch vụ.';
					$alert += '<br/><i>Sau <b>15</b> ngày kể từ ngày tạm ngưng dịch vụ, tài khoản của bạn sẽ bị khóa toàn bộ các dịch vụ.</i>';
					$alert += '</div>';
					showXModal($alert);
				}
			}
		}else{
			if(SHOP_TIME_LEFT < 60){
				$alert += '<div class="alert life-time alert-warning alert-dismissible fade in" role="alert">'; 
				$alert += '<button title="Đóng" type="button" onclick="setCookieExpried(this)" data-time="480" data-name="expried_alert_time_left" data-value="1" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>'; 
				$alert += '<strong>Cảnh báo!</strong> ';
				$alert += 'Tài khoản của bạn sắp hết hạn sử dụng. Hệ thống sẽ tự động tạm ngưng dịch vụ sau '; 
				$alert += '<b>'+SHOP_TIME_LEFT+'</b> ngày nữa ('+$tlf.attr('data-alert')+')'; 
				$alert += '. Vui lòng liên hệ hotline: <b class="underline"><a class="red " href="tel:0985527788">098 552 77 88</a> - Mr. Trường</b> để được hỗ trợ gia hạn dịch vụ.';
				$alert += '<br/><i>Sau <b>15</b> ngày kể từ ngày tạm ngưng dịch vụ, tài khoản của bạn sẽ bị khóa toàn bộ các dịch vụ.</i>';
				$alert += '</div>';
			}
		}
	 
		$tlf.html($alert);
	}
	$('.superfish').superfish({});load_select2();load_chosen_select();load_number_format();load_datetimepicker();
    $href = window.location.href.split('#');if($href.length > 1){$href = '#' + $href[1];if($($href).length > 0){if($($href).length>0) $('.nav-tabs a[href="'+$href+'"]').tab('show')  ;}}
	$('.form-edit-tab>li>a').click(function(){
		$('input.currentTab').val($(this).attr('href'));
	});
    $("input[type=checkbox].switchBtn").each(function(i,e){
		$(e).switchButton({
	        labels_placement: "left"
	    });
	});
 
    if($('[data-toggle="popover"]').length > 0){
        $('[data-toggle="popover"]').popover()  ;
    }
    reloadTooltip();
    $('.auto_height_price_list').each(function(i,e){
    	$h = $(e).find('.table-prices').height()+65;
    	//console.log($(e).find('.table-prices').height())
    	//$(e).height($h)
    })
    if($('a[rel=popover]').length > 0){
        $('a[rel=popover]').popover({
        html: true,
           trigger: 'hover',
           content: function () {
           return '<img src="'+$(this).data('img') + '" alt="" style="max-width: 600px;max-height:600px" />';
          }
        });
    }
    $('.Ccolorpicker').each(function(i,e){
   	 $format = $(e).attr('data-format') ? $(e).attr('data-format') : false;      
        $(e).colorpicker({
       	 format:$format
        });
    });
    $("input[type=checkbox].switch-btn").each(function(i,e){
    	$this = $(e);
    	$parent = $this.parent();
    	node = document.createElement("div");
    	node.className = 'onoff-button-div';
    	$parent.append(node);
    	$this.appendTo(node).removeClass('switch-btn').addClass('switchBtn')    	
		.switchButton({
	        labels_placement: "left"
	    });
		
	});
    
    $('#resultsForm_checkall').change(function(event) {  //on click
        if(this.checked) { // check select status
            $('.checked_item').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"
            });
        }else{
            $('.checked_item').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"
            });
        }
    });
    //alert('sss')
    // popout
    $popout =  $('[data-toggle="confirmation-popout"]');
    $('[data-toggle="confirmation"]').confirmation();
    $('[data-toggle="confirmation-singleton"]').confirmation({singleton:true});
    $popout.confirmation({
	      //btnOkLabel:'Có',
	      popout: true,
	      onConfirm:function(element){
	    	  $this = jQuery(this);	    	
	    	  if($this.attr('data-type') == 'multiple'){
	    		  $v = jQuery("input.checked_item:checked").map(function () {return this.value;}).get().join(",");
	    		  $this.attr('data-id',$v);
	    	  }
	    	  $data = getAttributes($this);	    	   
	    	  ///console.log($data)
	      jQuery.ajax({	  			
	    	  type: 'post',	  		 	
	    	  datatype: 'json',	  			
	    	  url: $cfg.cBaseUrl +'/ajax/delete',	  			
	    	  data:$data,	          
	    	  beforeSend: function() {
	    		  show_left_small_loading('show');             
	    	  },	  			
	    	  success: function(data) {	                  
	    		  $popout.confirmation('hide');	              
	    		 // console.log(data);
	    		//  alert(data)
	              var $d = JSON.parse(data);
	              if($d.state == true){ 
	              //$d.hide_class += '';
	              //$a = $d.hide_class.split(',');
	              jQuery.each($d.hide_class,function(index,value){   
	            	  jQuery('.tr_item_'+value).remove();
	              });
	              show_left_small_loading('hide');
	              }
	    	  },	  				  			
	    	  error : function(err, req) {	  		
	    		  //console.log(err);
	    		  show_left_small_loading('show');
	    	  },	  				            
	    	  complete: function() {
	    		  
	    	  }
	      });	        
	      return false	      
	      },

	});
    ///////
	$('.Breadcrumb li:last-child').prev().addClass('SecondLast');
	if($('.fixed-bottom-left').length==0){
		$('body').append('<div class="fixed-bottom-left alert alert-success alert-dismissible fade hide" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button> <strong class="alert-content"></strong></div>');
	}
	// Customize js
	 
	if($r.load_js) $.getScript($cfg.assets + "/js/actions/"+$cfg.controller+".js");
	// Scroll window 
	var $list_btn = $('.list-btn');	
	var $Offset = $list_btn.length>0 ? $list_btn.offset().top : 0;
	$window.scroll(function(){        
       $this = $(this);       
       if($list_btn.length>0){    	   
    	   if ($window.scrollTop() > $Offset) {
    		   $list_btn.addClass('header-sticky'); 
    	   }else{
    		   $list_btn.removeClass('header-sticky');
    	   }
       }
	})
	/// Load editor
	$('.ckeditor_basic1').each(function(i,e){
        $id = $(e).attr('id');
        $width = parseInt($(e).attr('data-width'));
        $height = parseInt($(e).attr('data-height'));
        CKEDITOR.replace( $id, {
            width:$width, height:$height,
             toolbar: [
                 { name: 'basicstyles',items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat'  ] },														// Line break - next group will be placed in new line.
                 { name: 'styles', items : [ 'Font','FontSize' ] },	 
                 { name: 'colors', items : [ 'TextColor','BGColor' ] }, 
                 { name: 'links', items : [ 'Link','Unlink' ] },
             ] 
             });
     });
     $('.ckeditor_basic2').each(function(i,e){
        $id = $(e).attr('id');
        $width = parseInt($(e).attr('data-width'));
        $height = parseInt($(e).attr('data-height'));
        CKEDITOR.replace( $id, {
            width:$width, height:$height,
             toolbar: [
                 { name: 'basicstyles',items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat'  ] },														// Line break - next group will be placed in new line.
                 { name: 'styles', items : [ 'Font','FontSize' ] },	 
                 { name: 'colors', items : [ 'TextColor','BGColor' ] }, 
                 { name: 'links', items : [ 'Link','Unlink' ] },
             ],
             toolbarStartupExpanded : false
             });
     });
     $('.ckeditor_basic3').each(function(i,e){
        $id = $(e).attr('id');
        $width = parseInt($(e).attr('data-width'));
        $height = parseInt($(e).attr('data-height'));
        CKEDITOR.replace( $id, {
            width:$width, height:$height,
             toolbar: [
                 { name: 'basicstyles',items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat'  ] },														// Line break - next group will be placed in new line.
                 { name: 'styles', items : [ 'Font','FontSize' ] },	 
                 { name: 'colors', items : [ 'TextColor','BGColor' ] }, 
                 { name: 'links', items : [ 'Link','Unlink' ] },
                 { name: 'insert', items: [ 'Image',  'SpecialChar' ] },
             ],
             toolbarStartupExpanded : false
             });
     });
     $('.ckeditor_full').each(function(i,e){
        $id = $(e).attr('id');
        $width = parseInt($(e).attr('data-width'));
        $height = parseInt($(e).attr('data-height'));
        $expand = $(e).attr('data-expand') ? $(e).attr('data-expand') : true;
        $expand = $expand == 'false' ? false : true;
        editor  = CKEDITOR.replace( $id, {
             width:$width, height:$height,
             toolbar:  'Full',
             toolbarStartupExpanded : $expand,
             filebrowserBrowseUrl : $cfg.libsDir + "/ckeditor/ckfinder/ckfinder.html",
             filebrowserImageBrowseUrl : $cfg.libsDir + "/ckeditor/ckfinder/ckfinder.html?type=Images",
             filebrowserFlashBrowseUrl : $cfg.libsDir + "/ckeditor/ckfinder/ckfinder.html?type=Flash",
             filebrowserUploadUrl : $cfg.libsDir + "/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files",
             filebrowserImageUploadUrl : $cfg.libsDir + "/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images",
             filebrowserFlashUploadUrl : $cfg.libsDir + "/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash"
            });
     });
     $('input.used_editor_for_info').each(function(i,e){
         $role = $(e).attr('role');
         $checked = $(e).is(':checked') ;
         $width = parseInt($(e).attr('data-width'));
         $height = parseInt($(e).attr('data-height'));
         if($checked){
            //alert($role);
            editor = CKEDITOR.replace( $role, {
             width:$width, height:$height,
             toolbar: [
                 { name: 'basicstyles',items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat'  ] },														// Line break - next group will be placed in new line.
                 { name: 'styles', items : [ 'Font','FontSize' ] },	 
                 { name: 'colors', items : [ 'TextColor','BGColor' ] }, 
                 { name: 'links', items : [ 'Link','Unlink' ] },
                 { name: 'insert', items: [ 'Image',  'SpecialChar' ] },
             ],
              
             filebrowserBrowseUrl : $cfg.libsDir + "/ckeditor/ckfinder/ckfinder.html",
             filebrowserImageBrowseUrl : $cfg.libsDir + "/ckeditor/ckfinder/ckfinder.html?type=Images",
		filebrowserFlashBrowseUrl : $cfg.libsDir + "/ckeditor/ckfinder/ckfinder.html?type=Flash",
		filebrowserUploadUrl : $cfg.libsDir + "/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files",
		filebrowserImageUploadUrl : $cfg.libsDir + "/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images",
		filebrowserFlashUploadUrl : $cfg.libsDir + "/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash"
            });
         } 
         
     });
     $('input.used_editor_for_info').change(function(){
        $this = $(this); $role = $this.attr('role');
        $checked = $this.is(':checked') ;
        $width = parseInt($this.attr('data-width'));
        $height = parseInt($this.attr('data-height'));
        if($checked){
            //alert($role);
            editor = CKEDITOR.replace( $role, {
             width:$width, height:$height,
             toolbar: [
                 { name: 'basicstyles',items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat'  ] },														// Line break - next group will be placed in new line.
                 { name: 'styles', items : [ 'Font','FontSize' ] },	 
                 { name: 'colors', items : [ 'TextColor','BGColor' ] }, 
                 { name: 'links', items : [ 'Link','Unlink' ] },
                 { name: 'insert', items: [ 'Image',  'SpecialChar' ] },
             ],
              
             filebrowserBrowseUrl : $cfg.libsDir + "/ckeditor/ckfinder/ckfinder.html",
             filebrowserImageBrowseUrl : $cfg.libsDir + "/ckeditor/ckfinder/ckfinder.html?type=Images",
		filebrowserFlashBrowseUrl : $cfg.libsDir + "/ckeditor/ckfinder/ckfinder.html?type=Flash",
		filebrowserUploadUrl : $cfg.libsDir + "/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files",
		filebrowserImageUploadUrl : $cfg.libsDir + "/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images",
		filebrowserFlashUploadUrl : $cfg.libsDir + "/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash"
            });
        }else{
            editor.destroy();

        }
     });
     
     $('.auto_play_script_function').each(function(i,e){
  	   eval($(e).val());
     })
     $('.input_codemirror').each(function(i,e){
    	var $this = jQuery(e);
    	 var editor_mirror = CodeMirror.fromTextArea(document.getElementById($this.attr('id')), {
    		  mode: "text/html",
    		  lineNumbers: true,
    		   
    	});
    	 
    	$this.data('CodeMirrorInstance', editor_mirror);
    	if($this.attr('onkeyup')){
    		editor_mirror.on('change', function () {
    			var html = editor_mirror.getValue();
    			$this.val(html)
    			validate_seo_preview($this);
    	    });
    	}
     });
     notification.init();
     // autocomplete
     loadTagsInput(); 
     loadTagsInput1(); 
     loadAutocomplete();
     /////////////////////////////
     loadSelectTagsinput();
     //reloadTooltip(); 
     /////////////////////////////
      
})},'json');

var notification = {
		 init:function(){
			 
			 //
			 (function getNotisTimeoutFunction(){
				 notification.getNotis(getNotisTimeoutFunction);
				})();
		 },
		 getNotis : function(callback){
			  
				jQuery.get($cfg.cBaseUrl +'/ajax?action=countNotifis',{},function(r){
					min = 3000; max = 10000;
//					console.log(r)
					var nextRequest = Math.floor(Math.random()*(max-min+1)+min);
					$n = jQuery('.item-notifications');
					$badge = $n.find('.alert-count');
					if(r.unview > 0){
						$badge.html(r.unview).show();
						$n.find('.badge-0').removeClass('badge-0').addClass('badge-1');
					} else{
						$badge.html('').hide();
						$n.find('.badge-1').removeClass('badge-1').addClass('badge-0');
					}
					// 2 seconds
					//if(chat.data.noActivity > 3){
					//	nextRequest = 2000;
					//}
					
					//if(chat.data.noActivity > 10){
					//	nextRequest = 5000;
					//}
					
					// 15 seconds
					//if(chat.data.noActivity > 20){
					//	nextRequest = 15000;
					//}
				
					setTimeout(callback,nextRequest);
				},'json');
			},
		 
}
function load_css(urls,target) {
	var node;	 
	head = target == 1 ? document.getElementsByTagName("link")[0] : document.getElementsByTagName("head")[0];	
	urls = typeof urls === 'string' ? [urls] : urls.concat();
	for (i = 0, len = urls.length; i < len; ++i) {
	  node = document.createElement("link");
	  node.type = "text/css";
	  node.rel = "stylesheet";
	  node.className = 'lazyload';
	  node.href = urls[i];
	  if(target == 1){
		  head.parentNode.insertBefore(node, head);	  
	  }else{
		  head.appendChild(node); 
	  }
	}
}