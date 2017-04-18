//alert('s')
function delTab(f){
  if(confirm('Xác nhận.')){
    $('a[href="'+f+'"]').parent().remove();
    $(f).remove();
  }
}

function CKupdate(){
    for ( instance in CKEDITOR.instances )
        CKEDITOR.instances[instance].updateElement();
}
function reload_app($t){
	switch($t){
	case 'number_format':case 'number-format':
		jQuery('.ajax-number-format').each(function(i,e){
   		 $d = jQuery(e).attr('data-decimal') ? jQuery(e).attr('data-decimal') : 0; 
   		jQuery(e).number( true,$d); 
		});
	break;
	case 'datepicker':
		jQuery('.ajax-datepicker').datetimepicker({
		      //language:'vi',//dateFormat:'DD/MM/YYYY',
		    	 format:'DD/MM/YYYY',
		         //pickTime:false
		     });
		break;
	case 'chosen':
		var chosen_config = {
			'.chosen-select'           : {search_contains:true,case_sensitive_search:true},
		       '.chosen-select-deselect'  : {allow_single_deselect:true,search_contains:true,case_sensitive_search:true},
		       '.chosen-select-no-single' : {disable_search_threshold:10,search_contains:true,case_sensitive_search:true},
		       '.chosen-select-no-results': {no_results_text:'Oops, nothing found!',search_contains:true,case_sensitive_search:true},
		       '.chosen-select-width'     : {width:"95%",search_contains:true,case_sensitive_search:true}
  }
  for (var selector in chosen_config) {
    if(jQuery(selector).length>0){
      jQuery(selector).chosen(chosen_config[selector]);
    }
  }
		jQuery('select.ajax-chosen-select-ajax').each(function(index,element){
	 	       
			jQuery(element).ajaxChosen({
	         		   dataType: 'json',
	         		   type: 'POST',
	         		   data:{dtype:jQuery(element).attr('data-type'),action:'CHOSEN_AJAX',role:jQuery(element).attr('role') },
	         		   url: $cfg.adminUrl + '/ajax/chosen_ajax' 
	            },{
	         		   loadingImg: $cfg.baseUrl+'/loading.gif'
	            }); 
	            
	 	});
  break;
	
	case 'select2':
		jQuery(".ajax-select2").select2({
	     	language: "vi"
	     });
	     jQuery(".ajax-select2-no-search").select2({
	     	  minimumResultsForSearch: Infinity
	     });
	     jQuery.fn.select2.amd.require([
	                               "select2/core",
	                               "select2/utils",
	                               "select2/compat/matcher"
	                             ], function (Select2, Utils, oldMatcher) {
	                              

	                               //var $ajax = jQuery(".js-select-data-ajax");
	                              
	                                

	                               function formatRepo (repo) {
	                                 if (repo.loading) return repo.text;

	                                 var markup = "<div class='select2-result-repository clearfix'>" +
	                                  // "<div class='select2-result-repository__avatar'><img src='" + repo.owner.avatar_url + "' /></div>" +
	                                   "<div class='select2-result-repository__meta'>" +
	                                     "<div class='select2-result-repository__title'>" + repo.text + "</div>";

	                                 if (repo.description) {
	                                   markup += "<div class='select2-result-repository__description'>" + repo.description + "</div>";
	                                 }

	                                 markup += ''; 
	                                 markup += "</div></div>";

	                                 return markup;
	                               }

	                               function formatRepoSelection (repo) {
	                                 return repo.full_name || repo.text;
	                               }
	                               jQuery('select.ajax-js-select-data-ajax').each(function(index,element){
	                               jQuery(element).select2({
	                             	  language: "vi",
	                                 ajax: {
	                                   url: $cfg.adminUrl + '/ajax/select2_ajax' ,
	                                   dataType: 'json',
	                                   delay: 250,
	                                   type:'POST',
	                                   data: function (params) {
	                                     return {
	                                       q: params.term, // search term
	                                       role:jQuery(element).attr('role'),
	                                       page: params.page,
	                                       groupID:jQuery(element).attr('data-groupID'),
	                                       type:jQuery(element).attr('data-type'),
	                                       filterID:jQuery(element).attr('data-filterID'),
	                                     };
	                                   },
	                                   processResults: function (data) {
	                                       // parse the results into the format expected by Select2.
	                                       // since we are using custom formatting functions we do not need to
	                                       // alter the remote JSON data
	                                       return {
	                                           results: data
	                                       };
	                                   },
	                                   cache: true
	                                 },
	                                // tags: true,
	                                 cache: true,
	                                 escapeMarkup: function (markup) { return markup; },
	                                 minimumInputLength: 1,
	                                 templateResult: formatRepo,
	                                // templateSelection: formatRepoSelection
	                               });
	                               });
	                            
	                                
	                             });
		break;
	}
}
function reloadapp(){
	jQuery('.datetimepicker').each(function(i,e){
	    $maxDate = jQuery(e).attr('data-maxDate') ? jQuery(e).attr('data-maxDate') : false;
	    jQuery(e).datetimepicker({
	    	format:'DD/MM/YYYY HH:mm',
	      
	     maxDate : $maxDate
	 });
	 });
	jQuery('.datepickeronly').each(function(i,e){
	    $maxDate = jQuery(e).attr('data-maxDate') ? jQuery(e).attr('data-maxDate') : false;
	    jQuery(e).datetimepicker({
	     //locale: 'vi',
	    	format:'DD/MM/YYYY',
	     maxDate : $maxDate
	 });
	 });
	
	var chosen_config = {
		      '.chosen-select'           : {},
		      '.chosen-select-deselect'  : {allow_single_deselect:true},
		      '.chosen-select-no-single' : {disable_search_threshold:10},
		      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
		      '.chosen-select-width'     : {width:"95%"}
		    }
		    for (var selector in chosen_config) {
		      if(	jQuery(selector).length>0){
		    		jQuery(selector).chosen(chosen_config[selector]);
		      }
		    }
		    
		    if(	jQuery('select.ajax-chosen-select').length>0){
		    	jQuery('select.ajax-chosen-select').each(function(index,element){
		    		$role = jQuery(element).attr('role') ? jQuery(element).attr('role') : false;
		    		$dtype = jQuery(element).attr('data-type') ? jQuery(element).attr('data-type') : false;
			       
		    		jQuery(element).ajaxChosen({
		        		   dataType: 'json',
		        		   type: 'POST',
		        		   data:{dtype:$dtype,action:'CHOSEN_AJAX',role:$role},
		        		   url: $cfg.adminUrl + '/ajax/chosen_ajax' 
		           },{
		        		   loadingImg: $cfg.baseUrl+'/loading.gif'
		           }); 
		           
			   })
		       
		       
		   }
		   jQuery('.inputAmountVND').number( true, 0);
		   jQuery('.numberFormat').each(function(i,e){
		   	 	$x = jQuery(e).attr('data-decimal') ? jQuery(e).attr('data-decimal') : 0;
		   	 	jQuery(e).number(true,$x);
		    });
		   $.fn.select2.amd.require([
		                              "select2/core",
		                              "select2/utils",
		                              "select2/compat/matcher"
		                            ], function (Select2, Utils, oldMatcher) {
		                             
 
		                              function formatRepo (repo) {
		                                if (repo.loading) return repo.text;

		                                var markup = "<div class='select2-result-repository clearfix'>" +
		                                 // "<div class='select2-result-repository__avatar'><img src='" + repo.owner.avatar_url + "' /></div>" +
		                                  "<div class='select2-result-repository__meta'>" +
		                                    "<div class='select2-result-repository__title'>" + repo.text + "</div>";

		                                if (repo.description) {
		                                  markup += "<div class='select2-result-repository__description'>" + repo.description + "</div>";
		                                }

		                                markup += ''+ //"<div class='select2-result-repository__statistics'>" +
		                                  //"<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> " + repo.forks_count + " Forks</div>" +
		                                  //"<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> " + repo.stargazers_count + " Stars</div>" +
		                                  //"<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> " + repo.watchers_count + " Watchers</div>" +
		                               // "</div>" +
		                                "</div></div>";

		                                return markup;
		                              }

		                              function formatRepoSelection (repo) {
		                                return repo.full_name || repo.text;
		                              }
		                              $('select.js-select-data-ajax').each(function(index,element){
		                              $(element).select2({
		                            	  language: "vi",
		                                ajax: {
		                                  url: $cfg.adminUrl + '/ajax/select2_ajax' ,
		                                  dataType: 'json',
		                                  delay: 250,
		                                  type:'POST',
		                                  data: function (params) {
		                                    return {
		                                      q: params.term, // search term
		                                      role:$(element).attr('role'),
		                                      page: params.page,
		                                      groupID:$(element).attr('data-groupID'),
		                                      type:$(element).attr('data-type'),
		                                    };
		                                  },
		                                  processResults: function (data) {
		                                      // parse the results into the format expected by Select2.
		                                      // since we are using custom formatting functions we do not need to
		                                      // alter the remote JSON data
		                                      return {
		                                          results: data
		                                      };
		                                  },
		                                  cache: true
		                                },
		                               // tags: true,
		                                cache: true,
		                                escapeMarkup: function (markup) { return markup; },
		                                minimumInputLength: 1,
		                                templateResult: formatRepo,
		                               // templateSelection: formatRepoSelection
		                              });
		                              });
		                           
		                               
		                            });
		   jQuery(".autocomplete_cost").each(function(i,e){
		    	$type = jQuery(e).attr('data-type') ? jQuery(e).attr('data-type') : 1;
		    	$ctype = jQuery(e).attr('data-ctype') ? jQuery(e).attr('data-ctype') : 'NOR';
		    	jQuery(e).autocomplete({
			        source: $cfg.cBaseUrl+"/ajax?action=autocomplete&type="+$type+"&ctype="+$ctype,
			        minLength: 2,
			        width			: 481,
					delay			: 150,
					scroll		: false,
					max			: 13,
					selectFirst	: false,
			 
			        select: function(event, ui) {
			        	$this = jQuery(e);
			            var $i = ui.item.item;
			            $tr = $this.parent().parent();
			            $c = parseInt($tr.find('.sl-cost-count').val());
			            $a = parseInt($tr.find('.sl-cost-amount').val());
			            $p = $tr.find('.sl-cost-price');
			            $t = $tr.find('.sl-cost-total');
			            //alert($a)
			            $p.val($c*$a*$i.price);
			            calculationTourCost();
			        },
			        appendTo:'.ac_results',
			        html: true, // optional (jquery.ui.autocomplete.html.js required)
			
				    // optional (if other layers overlap autocomplete list)
			        open: function(event, ui) {
			        	jQuery(".ui-autocomplete").css("z-index", 1000);
			        }
		    	});
		    });		   
	
}
function submitAjax(t){
    
    $this= jQuery(t);
    $href = $cfg.cBaseUrl; 
    $submit = true;
    jQuery('.er').remove();
    $ckc = true;
    $('.error.check_error').each(function(i,e){
    	 $submit = false;
    	 $(e).focus();
    	 $er = $(e).parent().find('.error_field');
    	 if($er.length == 0){
    		 $er = $('<div class="error_field"></div>');
    		 $(e).parent().append($er);
    	 }
    	 $erText = $(e).attr('data-alert') ? $(e).attr('data-alert') : '';
    	 $erText = $erText.replace(/{VAL}/g,$(e).val());
    	 $er.html($erText);
         return false;
     });
     if($submit){
     $this.find('.required').each(function(i,e){
        if(jQuery(e).val().trim() == ""){
        	jQuery(e).focus();
            $ckc = false;
            return false;
        } 
     });
     
    if(!$ckc)  return false;
    CKupdate();
     
    jQuery.ajax({
      type: 'post',
      datatype: 'json',
      url: $href  + '/ajax',						 		 
      data: $this.serialize(),
      beforeSend:function(){
              showFullLoading();
      },
      success: function (data) {
    	  //console.log(data);
          hideFullLoading(); 
          if(data != ""){
        	  //alert(data)
              $d = JSON.parse(data);
 
              if($d.error == true  ){
                  showModal('Thông báo',$d.error_content)
              }else{
                  if($d.modal == true){
                      showModal('Thông báo',$d.modal_content) 
                  }
              }
              if($d.redirect == true  ){
                  window.location = $d.target;
              }
              if($d.event!=undefined){
              	switch($d.event){
              	case '_tour_program_add_service':
              		$pr = jQuery($d.parent);
              		$target = $pr.find($d.target);
              		$target.before($d.html);
              		$c = $target.find('.btn-option .btn-count-array');
              		$cx = parseInt($c.attr('data-count')) > 0 ? parseInt($c.attr('data-count')) : 0;
              		$c.attr('data-count',$cx+1);
              		tour_program_calculation_price();
              		jQuery('.mymodal').modal('hide');
              		reload_app('number-format');
              		reload_app('chosen');
              		
              	break;
              	case '_tour_program_edit_service':
              		$pr = jQuery($d.parent);
              		$target = $pr.find($d.target);
              		$target.replaceWith($d.html);
              		tour_program_calculation_price();
              		//$c = jQuery($d.target).find('.btn-option .btn-count-array');
              		//$cx = parseInt($c.attr('data-count')) > 0 ? parseInt($c.attr('data-count')) : 0;
              		//$c.attr('data-count',$cx+1);
              		console.log($d.html)
              		jQuery('.mymodal').modal('hide');
              		reload_app('number-format');
              		reload_app('chosen');
              		
              	break;
              	case 'quick_edit_field':
              		jQuery($d.target).html($d.title);
              		jQuery('.mymodal').modal('hide');
              	break;
              		case 'redirect_link':
              		$timeout = $d.delay != undefined ? $d.delay : 0;
              		window.setTimeout(
              	              function() 
              	              {
              	                window.location = $d.target;
              	              }, $timeout);
              		break;
              		case 'clearInput':
	              		jQuery($d.target).val(''); 
	              		break;
              		case 'reload':
              			$timeout = $d.delay != undefined ? $d.delay : 0;
                  		window.setTimeout(
                  	              function() 
                  	              {
                  	            	  window.location = window.location;
                  	              }, $timeout);
              			
              			break;
              		case 'add_loai_thu_chi':
              			//alert($d.ptc.target );
              			jQuery('.mymodal1').modal('hide');
              			jQuery($d.target).html($d.select).trigger("chosen:updated").change();
              			break;
              		case 'chon_khach_san':
              			
              			$action = $d.action;
              			switch ($action) {
						case 'add':
							//$tbody = jQuery('.private-row-hotel-'+ $d.option + '-' +$d.pindex);
							$tbody = jQuery('.select_hotel_option_'+ $d.option).find('.private-row-hotel-'+$d.pindex);
							
							//$tbody.addClass('xxxxxxxxxxxxxxxxx'); 
							$v = parseInt(jQuery('#numberOfHotel').val())+1;
							jQuery('#numberOfHotel').val($v);
							$target = jQuery($d.target); 
							$target.attr('data-index',$d.index);
							//alert($d.price); 
							$tbody.before($d.price);
							break;

						default:
							$input_name = jQuery('.input-hotel-name-'+$d.pindex+'-'+$d.index);
							$input_star = jQuery('.input-hotel-star-'+$d.pindex+'-'+$d.index);
              				$input_name.val($d.hotel['name']);
              				$input_star.val($d.hotel['star']);
              				//alert('.input-hotel-name-'+$d.pindex+'-'+$d.index)
              				//hotel-detail-body-index-1-0
              				$tbody = jQuery('.hotel-detail-body-index-'+ $d.option + '-' + $d.pindex + '-' + $d.index);
              				//alert('.hotel-detail-body-index-' + $d.pindex + '-' + $d.index)
              				//alert('.hotel-detail-body-index-'+ $d.option + '-' + $d.pindex + '-' + $d.index); 
              				$tbody.html($d.price);
							break;
						}
              			
              			 
              			jQuery('.mymodal').modal('hide');
              			reload_app('number_format');
              			changeHotelCost(jQuery('.sl-hotel-cost-amount'));
              			reloadCost();
              			break;
              		case 'chon_xe':
              			$action = $d.action;
              			//alert($d.item)
              			switch ($action) {
						case 'add':
							$tbody = jQuery('.public-row-car-0');
							//$tbody.addClass('xxxxxxxxxxxxxxxxx'); 
							$v = parseInt(jQuery('#numberOfCar').val())+1;
							jQuery('#numberOfCar').val($v);
							$tbody.before($d.price);
							jQuery('.btn-add-more-transport').attr('data-index', $d.index);
							break;

						default:
							$input_name = jQuery('.input-car-name-'+$d.index);
							//$input_star = jQuery('.input-hotel-star-'+$d.index);
              				$input_name.val($d.item['name']);
              				//$input_star.val($d.item['star']);
              				$tbody = jQuery('.car-detail-body-index-'+$d.index);
              				$tbody.html($d.price);
							break;
						}
              			
              			 
              			jQuery('.mymodal').modal('hide');
              			reload_app('number-format');
              			reloadCost();
              			break;	
              			
              		case 'checkInError':
              			$e = jQuery('.cError');
              			switch($d.error_code){
              			case 'SUCCESS':
              				$e.html('<p>Điểm danh thành công.</p>');
              				break;
              			case 'CHECKED':
              				$e.html('<p>Bạn đã điểm danh rồi.</p>');
              				break;
              			case 'USER_NOT_EXIST':
              				$e.html('<p>Không tìm thấy tài khoản.</p>');
              				break;
              			case 'NOT_FOUND':
              				$e.html('<p>Không tìm thấy thông tin lớp học.</p>');
              				break;
              			}
              			break;
              		case 'them_danh_muc_chiphi':
              			jQuery('#addCostCateID').append('<option ="'+$d.data['id']+'" selected>'+$d.data['name']+'</option>').trigger("chosen:updated");;
              			jQuery('.mymodal1').modal('hide');
              			break;
              	}
              	
              }
          }
          else
          window.location = window.location;
          
      },
      error : function(err, req) {
          hideFullLoading();
           
				 
				alert('Lỗi kết nối, vui lòng thử lại.');
			}
    });
     }
    return false;  
}
function reloadCost(){
	jQuery('.radio-change-hotel-cost:checked').each(function(i,e){
		$ck = jQuery(e).is(':checked');
		if($ck){
			changeHotelCost(e);
		}
	});
}
///////////////////////////////////////////////
//Biến toàn cục
var http_arr = new Array();

function doUploads($t) {
	$this = jQuery($t);
	$o = $this.attr('data-option') ? $this.attr('data-option') : '';
	$index = $this.attr('data-index') ? parseInt($this.attr('data-index')) : 0;
	$temp_file = $this.attr('data-name') ? $this.attr('data-name') : 'list_images';
	
	document.getElementById('progress-group'+$o).innerHTML = ''; //Reset lại Progress-group
	var files = document.getElementById('myfile'+$o).files; 
	for (i=0;i<files.length;i++) {
		uploadFiles(files[i], i,$o,$temp_file+ '['+(i + $index)+'][image]');
	}
	$this.attr('data-index',files.length + $index) ;
	return false;
}
function ajax_upload_files($t) {
	$this = jQuery($t);
	$o = $this.attr('data-option') ? $this.attr('data-option') : '';
	$group = $this.attr('data-group') ? $this.attr('data-group') : '';
	$index = $this.attr('data-index') ? parseInt($this.attr('data-index')) : 0;
	$count = $this.attr('data-count') ? parseInt($this.attr('data-count')) : 0;
	$temp_file = $this.attr('data-name') ? $this.attr('data-name') : 'files_attach';
	$multiple = $this.attr('data-multiple') ? $this.attr('data-multiple') : 'true';
	$filetype = $this.attr('data-filetype') ? parseInt($this.attr('data-filetype')) : 'files';
	$input_name = $this.attr('data-input-name') ? ($this.attr('data-input-name')) : 'file';
	document.getElementById('progress-group'+$group).innerHTML = ''; //Reset lại Progress-group
	var files = document.getElementById('myfile'+$group).files; 
	for (i=0;i<files.length;i++) {
		if($multiple == 'false'){
			_ajax_upload_files(files[i], ($count+i+1),$group,$temp_file+ '['+$input_name+']',$filetype);
		}else{			
			_ajax_upload_files(files[i], ($count+i+1),$group,$temp_file+ '['+(i + $count)+']['+$input_name+']',$filetype);
		}
		
	}
	$this.attr('data-count',files.length + $count) ;
	return false;
}
function doUpload($o,$temp_file) {
	$o = $o == undefined ? '' : $o;
	$temp_file = $temp_file == undefined ? 'list_images[]' : $temp_file;
	//alert($o)
	document.getElementById('progress-group'+$o).innerHTML = ''; //Reset lại Progress-group
	var files = document.getElementById('myfile'+$o).files; 
	for (i=0;i<files.length;i++) {
		uploadFile(files[i], i,$o,$temp_file);
	}
	return false;
}
function _ajax_upload_files(file, $count,$group,$temp_file,$filetype) {
	//alert($o)
	var http = new XMLHttpRequest();
	http_arr.push(http);
	/** Khởi tạo vùng tiến trình **/
	//Div.Progress-group
	var ProgressGroup = document.getElementById('progress-group'+$group);
	var $ffx = document.getElementById('myfile'+$group);
	//alert('progress-group'+$o);
	//Div.Progress
	var Progress = document.createElement('div');
	Progress.className = 'progress';
	//Div.Progress-bar
	var ProgressBar = document.createElement('div');
	ProgressBar.className = 'progress-bar';
	//Div.Progress-text
	var ProgressText = document.createElement('div');
	ProgressText.className = 'progress-text';	
	//Thêm Div.Progress-bar và Div.Progress-text vào Div.Progress
	Progress.appendChild(ProgressBar);
	Progress.appendChild(ProgressText);
	//Thêm Div.Progress và Div.Progress-bar vào Div.Progress-group	
	ProgressGroup.appendChild(Progress);
    //
    var Respon_image_uploaded = document.getElementById('respon_image_uploaded'+$group);

	//Biến hỗ trợ tính toán tốc độ
	var oldLoaded = 0;
	var oldTime = 0;
	//Sự kiện bắt tiến trình
	http.upload.addEventListener('progress', function(event) {	
		if (oldTime == 0) { //Set thời gian trước đó nếu như bằng không.
			oldTime = event.timeStamp;
		}	
		//Khởi tạo các biến cần thiết
		var fileName = file.name; //Tên file
		var fileLoaded = event.loaded; //Đã load được bao nhiêu
		var fileTotal = event.total; //Tổng cộng dung lượng cần load
		var fileProgress = parseInt((fileLoaded/fileTotal)*100) || 0; //Tiến trình xử lý
		var speed = speedRate(oldTime, event.timeStamp, oldLoaded, event.loaded);
		//Sử dụng biến
		ProgressBar.innerHTML = fileName + ' đang được upload...';
		ProgressBar.style.width = fileProgress + '%';
		ProgressText.innerHTML = fileProgress + '% Tốc độ: '+speed+'KB/s';
		//Chờ dữ liệu trả về
		if (fileProgress == 100) {
			ProgressBar.style.background = 'url("'+$cfg.baseUrl+'/themes/admin/assets/images/progressbar.gif")';
		}
		oldTime = event.timeStamp; //Set thời gian sau khi thực hiện xử lý
		oldLoaded = event.loaded; //Set dữ liệu đã nhận được
	}, false);
	

	//Bắt đầu Upload
	var data = new FormData();
	data.append('filename', file.name);
	data.append('myfile', file);
	data.append('filetype', $filetype);
    data.append('action','ajax_uploads');
	http.open('POST', $cfg.adminUrl +'ajax', true);
	http.send(data);
 

	//Nhận dữ liệu trả về
	http.onreadystatechange = function(event) {
		//Kiểm tra điều kiện
		//alert(http.status);
		if (http.readyState == 4 && http.status == 200) {
			ProgressBar.style.background = ''; //Bỏ hình ảnh xử lý
			//alert(http.responseText);
			try { //Bẫy lỗi JSON
				ProgressBar.innerHTML = http.responseText;
				var server = JSON.parse(http.responseText);
                //alert(http.responseText);
				if (server.status) {
					ProgressBar.className += ' progress-bar-success'; //Thêm class Success
					ProgressBar.innerHTML = server.message; //Thông báo	
                    var InputRs = document.createElement('input');
                    InputRs.name = $temp_file;
                    InputRs.type = 'hidden';
                    InputRs.value = server.image;
                    //var InputRsx = document.createElement('input');
                  //  InputRsx.value = server.image;
                  // / InputRsx.className   = 'form-control inputPreview';
                    Respon_image_uploaded.appendChild(InputRs);	
                   // Respon_image_uploaded.appendChild(InputRsx);	
                    var child = document.getElementById('removeAfterUpload'+$group);
                    var respon_btable = document.getElementById('respon-btable'+$group);
                    switch ($group) {
                    case '-files-attach-images':
                    	
                    break;
					case '-files-attach':
						if(respon_btable != null){
	                    	$tr = document.createElement("tr");
	                    	
	                    	$tdc = document.createTextNode($count);
	                    	$td = document.createElement("td");
	                    	$td.className = 'center';
	                    	$td.appendChild($tdc);
	                    	$tr.appendChild($td);
	                    	//////////////////////////
	                    	$td = document.createElement("td");
	                    	$tdc = document.createElement('input');
	                    	$tdc.className = 'form-control  input-sm';
	                    	$tdc.type = 'type';
	                    	$tdc.name = 'biz[files_attach]['+($count-1)+'][title]';
	                    	$td.appendChild($tdc);
	                    	$tr.appendChild($td);
	                    	//////////////////////////////////
	                    	$td = document.createElement("td");
	                    	$tdc = document.createElement('input');
	                    	$tdc.className = 'form-control  input-sm';
	                    	$tdc.type = 'type';
	                    	$tdc.name = 'biz[files_attach]['+($count-1)+'][info]';
	                    	$td.appendChild($tdc);
	                    	$tr.appendChild($td);
	                    	////////////////////////////////// 
	                    	$td = document.createElement("td");
	                    	$tr.appendChild($td);
	                    	//////////// <i onclick="removeTrItem(this);" class="glyphicon glyphicon-trash pointer"></i>     
	                    	$td = document.createElement("td");
	                    	$tdc = document.createElement('i');
	                    	$tdc.className = 'glyphicon glyphicon-trash pointer';
	                    	$tdc.onclick =function(){removeTrItem(this);};
	                    	$td.className = 'center';
	                    	
	                    	$td.appendChild($tdc);
	                    	$tr.appendChild($td);
	                    	respon_btable.appendChild($tr);
	                    }
						break;

					default:
						
						break;
					}
                    
                     
                    if(!child || child == null){
                    	
                    }else{
                    	child.parentNode.removeChild(child);
                    }
                    $ffx.value = '';
                    //var child = document.getElementById("p1");
                   
                    //ProgressGroup.removeChild(inputRM) ;	
				} else {
					ProgressBar.className += ' progress-bar-danger'; //Thêm class Danger
					ProgressBar.innerHTML = server.message; //Thông báo
				}
			} catch (e) {
				ProgressBar.className += ' progress-bar-danger'; //Thêm class Danger
				//alert(e)
				ProgressBar.innerHTML = e ; //'Có lỗi xảy ra :('; //Thông báo
			}
		}
		http.removeEventListener('progress',function(x){}); //Bỏ bắt sự kiện
	}
}
function uploadFiles(file, index,$o,$temp_file) {
	//alert($o)
	var http = new XMLHttpRequest();
	http_arr.push(http);
	/** Khởi tạo vùng tiến trình **/
	//Div.Progress-group
	var ProgressGroup = document.getElementById('progress-group'+$o);
	var $ffx = document.getElementById('myfile'+$o);
	//alert('progress-group'+$o);
	//Div.Progress
	var Progress = document.createElement('div');
	Progress.className = 'progress';
	//Div.Progress-bar
	var ProgressBar = document.createElement('div');
	ProgressBar.className = 'progress-bar';
	//Div.Progress-text
	var ProgressText = document.createElement('div');
	ProgressText.className = 'progress-text';	
	//Thêm Div.Progress-bar và Div.Progress-text vào Div.Progress
	Progress.appendChild(ProgressBar);
	Progress.appendChild(ProgressText);
	//Thêm Div.Progress và Div.Progress-bar vào Div.Progress-group	
	ProgressGroup.appendChild(Progress);
    //
    var Respon_image_uploaded = document.getElementById('respon_image_uploaded'+$o);

	//Biến hỗ trợ tính toán tốc độ
	var oldLoaded = 0;
	var oldTime = 0;
	//Sự kiện bắt tiến trình
	http.upload.addEventListener('progress', function(event) {	
		if (oldTime == 0) { //Set thời gian trước đó nếu như bằng không.
			oldTime = event.timeStamp;
		}	
		//Khởi tạo các biến cần thiết
		var fileName = file.name; //Tên file
		var fileLoaded = event.loaded; //Đã load được bao nhiêu
		var fileTotal = event.total; //Tổng cộng dung lượng cần load
		var fileProgress = parseInt((fileLoaded/fileTotal)*100) || 0; //Tiến trình xử lý
		var speed = speedRate(oldTime, event.timeStamp, oldLoaded, event.loaded);
		//Sử dụng biến
		ProgressBar.innerHTML = fileName + ' đang được upload...';
		ProgressBar.style.width = fileProgress + '%';
		ProgressText.innerHTML = fileProgress + '% Tốc độ: '+speed+'KB/s';
		//Chờ dữ liệu trả về
		if (fileProgress == 100) {
			ProgressBar.style.background = 'url("'+$cfg.baseUrl+'/themes/admin/assets/images/progressbar.gif")';
		}
		oldTime = event.timeStamp; //Set thời gian sau khi thực hiện xử lý
		oldLoaded = event.loaded; //Set dữ liệu đã nhận được
	}, false);
	

	//Bắt đầu Upload
	var data = new FormData();
	data.append('filename', file.name);
	data.append('myfile', file);
    data.append('action','ajax_upload');
	http.open('POST', $cfg.adminUrl +'ajax', true);
	http.send(data);
 

	//Nhận dữ liệu trả về
	http.onreadystatechange = function(event) {
		//Kiểm tra điều kiện
		//alert(http.status);
		if (http.readyState == 4 && http.status == 200) {
			ProgressBar.style.background = ''; //Bỏ hình ảnh xử lý
			try { //Bẫy lỗi JSON
				ProgressBar.innerHTML = http.responseText;
				var server = JSON.parse(http.responseText);
               // alert(http.responseText);
				if (server.status) {
					ProgressBar.className += ' progress-bar-success'; //Thêm class Success
					ProgressBar.innerHTML = server.message; //Thông báo	
                    var InputRs = document.createElement('input');
                    InputRs.name = $temp_file;
                    InputRs.type = 'hidden';
                    InputRs.value = server.image;
                    var InputRsx = document.createElement('input');
                    InputRsx.value = server.image;
                    InputRsx.className   = 'form-control inputPreview';
                    Respon_image_uploaded.appendChild(InputRs);	
                    Respon_image_uploaded.appendChild(InputRsx);	
                    var child = document.getElementById('removeAfterUpload'+$o);
                    if(!child || child == null){
                    	
                    }else{
                    	child.parentNode.removeChild(child);
                    }
                    $ffx.value = '';
                    //var child = document.getElementById("p1");
                   
                    //ProgressGroup.removeChild(inputRM) ;	
				} else {
					ProgressBar.className += ' progress-bar-danger'; //Thêm class Danger
					ProgressBar.innerHTML = server.message; //Thông báo
				}
			} catch (e) {
				ProgressBar.className += ' progress-bar-danger'; //Thêm class Danger
				//alert(e)
				ProgressBar.innerHTML = e ; //'Có lỗi xảy ra :('; //Thông báo
			}
		}
		http.removeEventListener('progress',function(x){}); //Bỏ bắt sự kiện
	}
}
function uploadFile(file, index,$o,$temp_file) {
	//alert($o)
	var http = new XMLHttpRequest();
	http_arr.push(http);
	/** Khởi tạo vùng tiến trình **/
	//Div.Progress-group
	var ProgressGroup = document.getElementById('progress-group'+$o);
	var $ffx = document.getElementById('myfile'+$o);
	//alert('progress-group'+$o);
	//Div.Progress
	var Progress = document.createElement('div');
	Progress.className = 'progress';
	//Div.Progress-bar
	var ProgressBar = document.createElement('div');
	ProgressBar.className = 'progress-bar';
	//Div.Progress-text
	var ProgressText = document.createElement('div');
	ProgressText.className = 'progress-text';	
	//Thêm Div.Progress-bar và Div.Progress-text vào Div.Progress
	Progress.appendChild(ProgressBar);
	Progress.appendChild(ProgressText);
	//Thêm Div.Progress và Div.Progress-bar vào Div.Progress-group	
	ProgressGroup.appendChild(Progress);
    //
    var Respon_image_uploaded = document.getElementById('respon_image_uploaded'+$o);

	//Biến hỗ trợ tính toán tốc độ
	var oldLoaded = 0;
	var oldTime = 0;
	//Sự kiện bắt tiến trình
	http.upload.addEventListener('progress', function(event) {	
		if (oldTime == 0) { //Set thời gian trước đó nếu như bằng không.
			oldTime = event.timeStamp;
		}	
		//Khởi tạo các biến cần thiết
		var fileName = file.name; //Tên file
		var fileLoaded = event.loaded; //Đã load được bao nhiêu
		var fileTotal = event.total; //Tổng cộng dung lượng cần load
		var fileProgress = parseInt((fileLoaded/fileTotal)*100) || 0; //Tiến trình xử lý
		var speed = speedRate(oldTime, event.timeStamp, oldLoaded, event.loaded);
		//Sử dụng biến
		ProgressBar.innerHTML = fileName + ' đang được upload...';
		ProgressBar.style.width = fileProgress + '%';
		ProgressText.innerHTML = fileProgress + '% Tốc độ: '+speed+'KB/s';
		//Chờ dữ liệu trả về
		if (fileProgress == 100) {
			ProgressBar.style.background = 'url("'+$cfg.baseUrl+'/themes/admin/assets/images/progressbar.gif")';
		}
		oldTime = event.timeStamp; //Set thời gian sau khi thực hiện xử lý
		oldLoaded = event.loaded; //Set dữ liệu đã nhận được
	}, false);
	

	//Bắt đầu Upload
	var data = new FormData();
	data.append('filename', file.name);
	data.append('myfile', file);
    data.append('action','ajax_upload');
	http.open('POST', $cfg.adminUrl +'ajax', true);
	http.send(data);
 

	//Nhận dữ liệu trả về
	http.onreadystatechange = function(event) {
		//Kiểm tra điều kiện
		//alert($cfg.adminUrl +'ajax');
		if (http.readyState == 4 && http.status == 200) {
			ProgressBar.style.background = ''; //Bỏ hình ảnh xử lý		
			try { //Bẫy lỗi JSON
				ProgressBar.innerHTML = http.responseText;
				var server = JSON.parse(http.responseText);
                
				if (server.status) {
					ProgressBar.className += ' progress-bar-success'; //Thêm class Success
					ProgressBar.innerHTML = server.message; //Thông báo	
                    var InputRs = document.createElement('input');
                    InputRs.name = $temp_file;
                    InputRs.type = 'hidden';
                    InputRs.value = server.image;
                    var InputRsx = document.createElement('input');
                    InputRsx.value = server.image;
                    InputRsx.className   = 'form-control inputPreview';
                    Respon_image_uploaded.appendChild(InputRs);	
                    Respon_image_uploaded.appendChild(InputRsx);	
                    var child = document.getElementById('removeAfterUpload'+$o);
                    if(!child || child == null){
                    	
                    }else{
                    	child.parentNode.removeChild(child);
                    }
                    $ffx.value = '';
                    //var child = document.getElementById("p1");
                   
                    //ProgressGroup.removeChild(inputRM) ;	
				} else {
					ProgressBar.className += ' progress-bar-danger'; //Thêm class Danger
					ProgressBar.innerHTML = server.message; //Thông báo
				}
			} catch (e) {
				ProgressBar.className += ' progress-bar-danger'; //Thêm class Danger				
				ProgressBar.innerHTML = e ; //'Có lỗi xảy ra :('; //Thông báo
			}
		}
		http.removeEventListener('progress',function(x){}); //Bỏ bắt sự kiện
	}
}
function cancleUpload() {
	for (i=0;i<http_arr.length;i++) {
		http_arr[i].removeEventListener('progress');
		http_arr[i].abort();
	}
	var ProgressBar = document.getElementsByClassName('progress-bar');
	for (i=0;i<ProgressBar.length;i++) {
		ProgressBar[i].className = 'progress progress-bar progress-bar-danger';
	}	
}
function speedRate(oldTime, newTime, oldLoaded, newLoaded) {
		var timeProcess = newTime - oldTime; //Độ trễ giữa 2 lần gọi sự kiện
		if (timeProcess != 0) {
			var currentLoadedPerMilisecond = (newLoaded - oldLoaded)/timeProcess; // Số byte chuyển được 1 Mili giây
			return parseInt((currentLoadedPerMilisecond * 1000)/1024); //Trả về giá trị tốc độ KB/s
		} else {
			return parseInt(newLoaded/1024); //Trả về giá trị tốc độ KB/s
		}
}
function sendAjaxPost($data,t){     
	$this = jQuery(t);
    $href = $cfg.cBaseUrl;   
    jQuery.ajax({
      type: 'post',
      datatype: 'json',
      url: $href  + '/ajax',						 		 
      data: $data,
      beforeSend:function(){
              showFullLoading();
      },
      success: function (data) {
    	  //alert(data);  
          hideFullLoading(); 
          if(data != ""){
              $d = JSON.parse(data);
 
              if($d.error == true  ){
                  showModal('Thông báo',$d.error_content)
              }else{
                  if($d.modal == true){
                      showModal('Thông báo',$d.modal_content) 
                  }
              }
              if($d.redirect == true  ){
                  window.location = $d.target;
              }
              if($d.event!=undefined){
              	switch($d.event){
              		case 'redirect_link':
              		$timeout = $d.delay != undefined ? $d.delay : 0;
              		window.setTimeout(
              	              function() 
              	              {
              	                window.location = $d.target;
              	              }, $timeout);
              		break;
              		case 'clearInput':
	              		jQuery($d.target).val(''); 
	              		break;
              		case 'reload':
              			$timeout = $d.delay != undefined ? $d.delay : 0;
                  		window.setTimeout(
                  	              function() 
                  	              {
                  	            	  window.location = window.location;
                  	              }, $timeout);
              			
              			break;
              	 
              		case 'quickAddCostToTour':
              			if($d.data['type'] == 1){
              				$target_name = '#tab-genaral-costs';
              				
              				$t2 = jQuery('#tab-genaral-costs-grid');
              				$name = 'public_costs';
              			}else{
              				$target_name = '#tab-private-costs';
              				$t2 = jQuery('#tab-private-costs-grid');
              				$name = 'private_costs';
              			}
              			$tx = $name == 'private_costs' ? '' : '';
              			$tp = $name == 'private_costs' ? 'display:none' : '';
              			$target = jQuery($target_name);
              			 //= jQuery($this.attr('data-target'));
              			//$t2 = $this.attr('data-target2');
              			$target2 = jQuery($t2);
              			//$name = $this.attr('data-target-name');
              			$cost = jQuery('.select_costs_from_data');
              			$table_target = $target.find('.tableGrid > .sui-gridcontent > table > tbody > tr:last-child');
              			$count = parseInt($target.find('.data-count-cost-amount').val()) + 1;
              			$target.find('.data-count-cost-amount').val($count);
              			$tb = '<tr class="sui-row">'+$tx+'<td class="sui-cell">'+$count+'</td><td class="sui-cell"><input name="'+$name+'['+$count+'][name]" class="sui-input sui-input-focus w100 sl-costs sl-cost-name" value="'+$d.data['name']+'"></td>';
              			$tb += '<td style="text-align: center;'+$tp+'" class="sui-cell"><input onblur=\"calculationTourCost($t2);\" name="'+$name+'['+$count+'][count]" class="sui-input sui-input-focus w100 numberFormat center sl-cost-count" value="1"></td>';
              			$tb += '<td style="text-align: center;" class="sui-cell"><input onblur=\"calculationTourCost($t2);\" name="'+$name+'['+$count+'][amount]" class="sui-input sui-input-focus w100 numberFormat center sl-cost-amount" value="1"></td><td style="text-align: right;" class="sui-cell"><input onblur=\"calculationTourCost($t2);\" name="'+$name+'['+$count+'][price]" class="sui-input sui-input-focus w100 numberFormat aright sl-cost-price" value="'+$d.data['price']+'"></td><td style="text-align: right;" colspan="1" class="sui-cell"><strong><input name="'+$name+'['+$count+'][total]" class="input_transparent numberFormat aright  sl-cost-total" value="'+$d.data['price']+'"></strong></td>';
              			$tb += '<td style="text-align: center;" class="sui-cell acc-td"><input name="'+$name+'['+$count+'][expired]" class="sui-input sui-input-focus w100 center" value=""></td>';
              			$tb += '<td style="text-align: center;" class="sui-cell acc-td"><input name="'+$name+'['+$count+'][sott]" class="form-control numberFormat center input-sm" value=""></td>';
              			$tb += '<td style="text-align: center;" class="sui-cell acc-td"><input name="'+$name+'['+$count+'][soct]" class="sui-input sui-input-focus w100 center" value=""></td>';
              			$tb += '<td style="text-align: center;" class="sui-cell acc-td"><input name="'+$name+'['+$count+'][cktm]" class="sui-input sui-input-focus w100 center" value=""></td>';
              			$tb += '<td style="text-align: center;" class="sui-cell acc-td"><input name="'+$name+'['+$count+'][vat]" class="sui-input sui-input-focus w100 center" value=""></td>';
              			$tb += '<td style="text-align: center;" class="sui-cell acc-td"><input name="'+$name+'['+$count+'][foc]" class="sui-input sui-input-focus w100 center" value=""></td>';
              			$tb += '<td class="sui-cell"><input name="'+$name+'['+$count+'][note]" class="sui-input sui-input-focus w100" value=""></td><td class="sui-cell"><p class="center"><i title="Xóa" onclick="removeItemCost(this);" class="pointer glyphicon glyphicon-trash"></i></p></td></tr>';
              			
              			$table_target.before($tb); 
              			$cost.val(null).trigger('change');
              			calculationTourCost($target_name);
              			reloadapp();
              			break;
              	}
              	
              }
          }
           
          
      },
      error : function(err, req) {
          hideFullLoading();
          alert('Lỗi kết nối, vui lòng thử lại.');
			}
    });
    return false;  
}
function setCheckboxBool(t){
	$this = jQuery(t) ;
	$ck = $this.is(':checked');
	$this.val($ck ? 1 : 0);
}
function setRadioBool(t){
	$this = jQuery(t) ;
	$role = $this.attr('data-role');
	$sid = $this.attr('data-sid') ? parseInt($this.attr('data-sid')) : 0;
	$ck = $this.is(':checked');
	var checkboxes = $('.'+$role);     
    checkboxes.prop('checked', false).val(0);   
    $ck = $sid > 0 ? $ck : true;
	$this.prop('checked', $ck).val($ck ? 1 : 0);
}
function changeChosseHotel(t){
	//$this = jQuery(t) ;
	//$index = $this.attr('data-index');
	//$ck = $this.is(':checked');
	//if($ck){
		//jQuery('.sl-hotel-costs-'+$index).addClass('active');
	//}else{
		//jQuery('.sl-hotel-costs-'+$index).removeClass('active');
	//}
	//A_calculationTourCost(t);
}
function addNewCost(t){
	
	$this = jQuery(t);
	$target = jQuery($this.attr('data-target'));
	$class = $this.attr('data-autocomplete') ? 'autocomplete_cost' : '';
	$type = $this.attr('data-type') ? $this.attr('data-type')  : 1;
	$ctype = $this.attr('data-ctype') ? $this.attr('data-ctype')  : 'NOR';
	$t2 = $this.attr('data-target');
	$name = ($this.attr('data-name'));
	$tx = $name == 'private_costs' ? '' : '';
	$tp = $name == 'private_costs' ? '' : '';
	$amount = parseInt($target.find('.add_cost_amount_row').val());
	$amount = $amount > 0 ? $amount : 1;
	$table_target = $target.find('.sui-gridcontent > table > tbody > tr:last-child');
	//alert($table_target)
	//$target.remove();
	$count = parseInt($target.find('.data-count-cost-amount').val());
	$target.find('.data-count-cost-amount').val($count);
	$tb = '';
	for($i=0;$i<$amount;$i++){
	$tb += '<tr class="sui-row">'+$tx+'<td class="sui-cell">'+($count+1)+'</td><td class="sui-cell"><input data-type="'+$type+'" data-ctype="'+$ctype+'" name="'+$name+'['+$count+'][name]" class="'+$class+' sui-input sui-input-focus w100 required sl-costs sl-cost-name" value=""></td><td style="text-align: center;'+$tp+'" class="sui-cell"><input onblur=\"calculationTourCost($t2);\" name="'+$name+'['+$count+'][count]" class="sui-input sui-input-focus w100 numberFormat center sl-cost-count" value="1"></td><td style="text-align: center;" class="sui-cell"><input onblur=\"calculationTourCost($t2);\" name="'+$name+'['+$count+'][amount]" class="sui-input sui-input-focus w100 numberFormat center sl-cost-amount" value="1"></td><td style="text-align: right;" class="sui-cell"><input onblur=\"calculationTourCost($t2);\" name="'+$name+'['+$count+'][price]" class="aright sui-input sui-input-focus w100 ajax-number-format center sl-cost-price" value="0.00" data-decimal="2"></td><td style="text-align: right;" colspan="1" class="sui-cell"><strong><input name="'+$name+'['+$count+'][total]" class="input_transparent ajax-number-format aright sl-cost-total" data-decimal="2" readonly value="0.00"></strong></td>';
	$tb += '<td style="text-align: center;" class="sui-cell acc-td"><input name="'+$name+'['+$count+'][expired]" class="sui-input sui-input-focus w100 center" value=""></td>';
	$tb += '<td style="text-align: center;" class="sui-cell acc-td"><input name="'+$name+'['+$count+'][sott]" class="form-control numberFormat center input-sm" value=""></td>';
	$tb += '<td style="text-align: center;" class="sui-cell acc-td"><input name="'+$name+'['+$count+'][soct]" class="sui-input sui-input-focus w100 center" value=""></td>';
	$tb += '<td style="text-align: center;" class="sui-cell acc-td"><input name="'+$name+'['+$count+'][cktm]" class="sui-input sui-input-focus w100 center" value=""></td>';
	$tb += '<td style="text-align: center;" class="sui-cell acc-td"><input name="'+$name+'['+$count+'][vat]" class="sui-input sui-input-focus w100 center" value=""></td>';
	$tb += '<td style="text-align: center;" class="sui-cell acc-td"><input name="'+$name+'['+$count+'][foc]" class="sui-input sui-input-focus w100 center" value=""></td>';
	$tb += '<td class="sui-cell"><input name="'+$name+'['+$count+'][note]" class="sui-input sui-input-focus w100" value=""></td><td class="sui-cell"><p class="center"><input name="pCost['+$name+'][]" value="'+$count+'" type="checkbox" title="Lưu lại sử dụng lần sau." />&nbsp;&nbsp;&nbsp;<i title="Xóa" onclick="removeItemCost(this);" class="pointer glyphicon glyphicon-trash"></i></p></td></tr>';
	$count++;
	}
	$target.find('.data-count-cost-amount').val($count);
	$table_target.before($tb); 
	reloadapp();
}
function addNewCostIncurred(t){
	
	$this = jQuery(t);
	$target = jQuery($this.attr('data-target'));
	$class = '';
	$type = $this.attr('data-type') ? $this.attr('data-type')  : 1;
	$ctype = $this.attr('data-ctype') ? $this.attr('data-ctype')  : 'NOR';
	$required = $this.attr('data-required') ? $this.attr('data-required')  : 'required';
	$t2 = $this.attr('data-target');
	$name = ($this.attr('data-name'));
	//alert($required) 
	$amount = parseInt($target.find('.add_cost_amount_row').val());
	$amount = $amount > 0 ? $amount : 1;
	$table_target = $target.find('.sui-gridcontent > table > tbody > tr:last-child');
	//alert($amount)
	//$target.remove();
	
	$c = $target.find('.data-count-cost-amount') ;
	$count = parseInt($c.val());
	$tb = '';
	for($i=0;$i<$amount;$i++){
		//alert($count);
		
	$tb += '<tr class="sui-row incurred-row"> <td class="sui-cell">'+($count+1)+'</td><td class="sui-cell">';
	$tb += '<input name="'+$name+'['+$count+'][name]" class="'+$class+' sui-input sui-input-focus w100 '+$required+' sl-costs sl-cost-name" value=""></td><td style="text-align: center;" class="sui-cell">';
	$tb += '<input onblur=\"calculationIncurredCost(this);\" name="'+$name+'['+$count+'][INC]" class="sui-input sui-input-focus w100 numberFormat aright sl-cost-inc" value="0"></td><td style="text-align: center;" class="sui-cell">';
	$tb += '<input onblur=\"calculationIncurredCost(this);\" name="'+$name+'['+$count+'][DEC]" class="sui-input sui-input-focus w100 numberFormat aright sl-cost-dec" value="0"></td>';
	//$tb += '<input onblur=\"calculationTourCost($t2);\" name="'+$name+'['+$count+'][price]" class="aright sui-input sui-input-focus w100 numberFormat center sl-cost-price" value="0"></td><td style="text-align: right;" colspan="1" class="sui-cell"><strong>';
	//$tb += '<input name="'+$name+'['+$count+'][total]" class="input_transparent numberFormat aright  sl-cost-total" value="0"></strong></td>';
	//$tb += '<td style="text-align: center;" class="sui-cell"><input name="'+$name+'['+$count+'][expired]" class="sui-input sui-input-focus w100 center" value=""></td>';
	//$tb += '<td style="text-align: center;" class="sui-cell"><input name="'+$name+'['+$count+'][sott]" class="form-control numberFormat center input-sm" value=""></td>';
	//$tb += '<td style="text-align: center;" class="sui-cell"><input name="'+$name+'['+$count+'][soct]" class="sui-input sui-input-focus w100 center" value=""></td>';
	//$tb += '<td style="text-align: center;" class="sui-cell"><input name="'+$name+'['+$count+'][cktm]" class="sui-input sui-input-focus w100 center" value=""></td>';
	//$tb += '<td style="text-align: center;" class="sui-cell"><input name="'+$name+'['+$count+'][vat]" class="sui-input sui-input-focus w100 center" value=""></td>';
	//$tb += '<td style="text-align: center;" class="sui-cell"><input name="'+$name+'['+$count+'][foc]" class="sui-input sui-input-focus w100 center" value=""></td>';
	$tb += '<td class="sui-cell"><input name="'+$name+'['+$count+'][note]" class="sui-input sui-input-focus w100" value=""></td><td class="sui-cell"><p class="center"><i title="Xóa" onclick="removeItemCost(this);calculationIncurredCost(this);" class="pointer glyphicon glyphicon-trash"></i></p></td></tr>';
	$count++;$c.val($count);
	}
	$table_target.before($tb); 
	reloadapp();
}
function calculationIncurredCost(t){
	$totalINC = $totalDEC = 0;
	jQuery('.incurred-row').each(function(i,e){
		$this = jQuery(e);
		$inc = $this.find('.sl-cost-inc');
		$dec = $this.find('.sl-cost-dec');
		$i = parseInt($inc.val()); $d = parseInt($dec.val());
		$i = $i > 0 ? $i : 0;$d = $d > 0 ? $d : 0;
		$totalINC += $i;
		$totalDEC += $d;
		//alert($totalINC);
		
	});//alert($totalINC);
	jQuery('#totalCostINC').val($totalINC); jQuery('#totalCostDEC').val($totalDEC);
}//alert('test');
function addNewHotelCost(t){
	
	$this = jQuery(t); $id = $this.attr('data-id');
	$target = jQuery($this.attr('data-target'));
	$t2 = $this.attr('data-target');
	$name = ($this.attr('data-name'));
	$tx = $name == 'private_costs' ? '' : '';
	$tp = $name == 'private_costs' ? 'display:none' : '';
	$amount = parseInt($target.find('.add_cost_amount_row').val());
	$amount = $amount > 0 ? $amount : 1;
	$table_target = $target.find('.tableGrid > .sui-gridcontent > table > tbody > tr:last-child');
	$count = parseInt($target.find('.data-count-cost-amount').val());
	$target.find('.data-count-cost-amount').val($count);
	$tb = '';
	for($i=0;$i<$amount;$i++){
		 
	$tb += '<tr class="sui-row"><td class="sui-cell">'+($count+1)+'</td><td class="sui-cell"><div data-id="'+$id+'" class="auto_load_room_list auto_load_room_list_'+$i+'" data-name="'+$name+'['+$count+'][item_id]" data-class="sui-input sui-input-focus w100 required sl-costs sl-cost-name"></div></td>';
	$tb += '<td style="text-align: center;" class="sui-cell"><select name="'+$name+'['+$count+'][type]" class="sui-input sui-input-focus w100 center sl-cost-type"><option value="1">Inbound</option><option value="3">Nội địa</option></select></td>';
	$tb += '<td style="text-align: center;" class="sui-cell"><input name="'+$name+'['+$count+'][pmin]" class="sui-input sui-input-focus w100 numberFormat center sl-cost-pmin" value=""></td>';
	$tb += '<td style="text-align: center;" class="sui-cell"><input name="'+$name+'['+$count+'][pmax]" class="sui-input sui-input-focus w100 numberFormat center sl-cost-pmax" value=""></td>';
	$tb += '<td style="text-align: right;" class="sui-cell"><input name="'+$name+'['+$count+'][price1]" class="aright sui-input sui-input-focus w100 numberFormat center sl-cost-price1" value=""></td>';
	$tb += '<td style="text-align: right;" class="sui-cell"><input name="'+$name+'['+$count+'][price2]" class="aright sui-input sui-input-focus w100 numberFormat center sl-cost-price2" value=""></td>';
	$tb += '<td style="text-align: right;" class="sui-cell"><input name="'+$name+'['+$count+'][price3]" class="aright sui-input sui-input-focus w100 numberFormat center sl-cost-price3" value=""></td>';
	$tb += '<td class="sui-cell"><p class="center"><i title="Xóa" onclick="removeItemCost(this);" class="pointer glyphicon glyphicon-trash"></i></p></td></tr>';
	 
	$target.find('.data-count-cost-amount').val(++$count);
	}
	$table_target.before($tb); 
	auto_load_room_list('.auto_load_room_list');
	reloadapp();
}
function auto_load_room_list($t){
	jQuery($t).each(function(i,e){
		$this = jQuery(e);
		// alert(i)
	jQuery.ajax({
        type: 'post',
        datatype: 'json',
		url: $cfg.adminUrl  + '/ajax',						 		 
        data: {action:'auto_load_room_list',id:$this.attr('data-id'),name:$this.attr('data-name'),classx : $this.attr('data-class')},
        beforeSend:function(){
               // showFullLoading();
        },
        success: function (data) {          	 
            //hideFullLoading();
        	jQuery('.auto_load_room_list_'+i).html(data).removeClass('auto_load_room_list') ;    
        	reload_app('select2');                    
        },
        error : function(err, req) {
            hideFullLoading();
            //showModal('Đã xảy ra lỗi','Quá trình thực hiện đã xảy ra lỗi, vui lòng thử lại.');
			}
      });
	});
}
function auto_load_car_list($t){
	jQuery($t).each(function(i,e){
		$this = jQuery(e);
		// alert(i)
	jQuery.ajax({
        type: 'post',
        datatype: 'json',
		url: $cfg.adminUrl  + '/ajax',						 		 
        data: {action:'auto_load_car_list',id:$this.attr('data-id'),name:$this.attr('data-name'),classx : $this.attr('data-class')},
        beforeSend:function(){
               // showFullLoading();
        },
        success: function (data) {          	 
            //hideFullLoading();
        	jQuery('.auto_load_car_list_'+i).html(data).removeClass('auto_load_car_list') ;        
        	reload_app('select2');
        },
        error : function(err, req) {
            hideFullLoading();
            //showModal('Đã xảy ra lỗi','Quá trình thực hiện đã xảy ra lỗi, vui lòng thử lại.');
			}
      });
	});
}
function addNewCarCost(t){
	
	$this = jQuery(t); $id = $this.attr('data-id');
	$target = jQuery($this.attr('data-target'));
	$t2 = $this.attr('data-target');
	$name = ($this.attr('data-name'));
	
	$amount = parseInt($target.find('.add_cost_amount_row').val());
	$amount = $amount > 0 ? $amount : 1;
	$table_target = $target.find('.tableGrid > .sui-gridcontent > table > tbody > tr:last-child');
	$count = $c = parseInt($target.find('.data-count-cost-amount').val());
	$target.find('.data-count-cost-amount').val($count);
	$tb = '';
	for($i=0;$i<$amount;$i++){
	 
	$tb += '<tr class="sui-row"><td class="sui-cell">'+($c+$i+1)+'</td><td class="sui-cell"><div data-id="'+$id+'" class="auto_load_car_list auto_load_car_list_'+$i+'" data-name="'+$name+'['+($count)+'][item_id]" data-class="sui-input sui-input-focus w100 required sl-costs sl-cost-name"></div></td>';
	//$tb += '<td style="text-align: center;" class="sui-cell"><select name="'+$name+'['+$count+'][type]" class="sui-input sui-input-focus w100 numberFormat center sl-cost-type"><option value="1">Inbound</option><option value="3">Nội địa</option></td>';
	$tb += '<td style="text-align: center;" class="sui-cell"><input name="'+$name+'['+$count+'][pmin]" class="sui-input sui-input-focus w100 ajax-number-format center sl-cost-pmin" value=""></td>';
	$tb += '<td style="text-align: center;" class="sui-cell"><input name="'+$name+'['+$count+'][pmax]" class="sui-input sui-input-focus w100 ajax-number-format center sl-cost-pmax" value=""></td>';
	$tb += '<td style="text-align: right;" class="sui-cell"><input name="'+$name+'['+$count+'][price1]" data-decimal="2" class="aright sui-input sui-input-focus w100 ajax-number-format center sl-cost-price1" value=""></td>';
	$tb += '<td style="text-align: right;" class="sui-cell"><input name="'+$name+'['+$count+'][price2]" data-decimal="2" class="aright sui-input sui-input-focus w100 ajax-number-format center sl-cost-price2" value=""></td>';
	$tb += '<td style="text-align: right;" class="sui-cell"><input name="'+$name+'['+$count+'][price3]" data-decimal="2" class="aright sui-input sui-input-focus w100 ajax-number-format center sl-cost-price3" value=""></td>';
	$tb += '<td class="sui-cell"><p class="center"><i title="Xóa" onclick="removeItemCost(this);" class="pointer glyphicon glyphicon-trash"></i></p></td></tr>';
	 
	$target.find('.data-count-cost-amount').val(++$count);
	}
	$table_target.before($tb); 
	auto_load_car_list('.auto_load_car_list');
	//reload_app('select2');
	reload_app('chosen');reload_app('number-format');
	//reloadapp();
}
function quickAddCostToTour(t){
	$this = jQuery(t);
	$target = jQuery($this.attr('data-target'));
	$target2 = jQuery($this.attr('data-target2'));
	$cost = jQuery('.select_costs_from_data');
	$id = parseInt($cost.val());
	if($id > 0){	 
		$data = {action:'quickAddCostToTour',id:$id};
		sendAjaxPost($data,t);
		
	}else{
		$cost.select2('open');
	}
}
function countTotalGuest(t){
	$g1 = parseInt(jQuery('#input-tour-sokhach-nl').val());
	$g2 = parseInt(jQuery('#input-tour-sokhach-te').val());
	$g1 = $g1 > 0 ? $g1 : 0;$g2 = $g2 > 0 ? $g2 : 0;
	$g3 = Math.ceil($g2 / 2);
	//alert($g3)
	jQuery('#input-tour-sokhach').val($g1 + $g3);
	changeTotalGuest(t); 
}
function changeTotalGuest(t){
	jQuery('#inputTotalGuest').val(jQuery(t).val());
	//calculationTourCost('#tab-genaral-costs-grid');
	//calculationTourCost('#tab-private-costs-grid');
	calculationTourCost(t);
}
function A_calculationTourCost(t){
	//jQuery('#inputTotalGuest').val(jQuery(t).val());
	//calculationTourCost('#tab-genaral-costs-grid');
	//calculationTourCost('#tab-private-costs-grid');
	//calculationTourCost(t);
}
function inputChangeCost(t){
	$this = jQuery(t);
	$o = $this.attr('data-old');
	$n = $this.val();
	if($o != $n){
		$this.attr('data-old',$n);
		calculationTourCost(t);
		
	}
}
function calculationTourCost(t){
	$role = {};
	$option = jQuery('.radioSelectOption:checked').attr('data-option');
	$quot = jQuery('#inputQuotation').val() == 'true' ? true : false;
	$type = jQuery('.radio_ctype input:checked').val(); 
	$cp1_e = jQuery('#inputtourCP01'); // Chi phí vận chuyển
	$cp2_e = jQuery('#inputtourCP02'); // Chi phí khác - CP chung
	$cp3_e = jQuery('#inputtourCP03-'+$option); // Chi phi khách sạn
	//alert('#inputtourCP03-'+$option)
	$cp4_e = jQuery('#inputtourCP04'); // Chi phí ăn uống
	$cp5_e = jQuery('#inputtourCP05'); // Chi phí khác - CP riêng
	
	jQuery('.bang-bao-gia-preview').hide();
	$this = jQuery(t);$guest = parseInt(jQuery('#input-tour-sokhach').val());
	$exCost = parseInt(jQuery('#inputExCostOfTour').val());
	$vat = $VAT = parseInt(jQuery('#inputVATtOfTour').val());
	$commission = parseInt(jQuery('#inputCommission').val());
	$commission = $commission > 0 ? $commission : 0;
	$vat = $vat > 0 ? $vat : 0;
	$exCost = $exCost > 0 ? $exCost : 0;
	//alert($this);
	jQuery('.tabGridPrice').each(function(i,e){
		$this = jQuery(e);
		$totalPrice = 0;
		$this.find('.sl-costs').each(function(i,e){ 
			$tr = jQuery(e).parent().parent();
			$count = $tr.find('.sl-cost-count');
			$amount = $tr.find('.sl-cost-amount');
			$price = $tr.find('.sl-cost-price');		
			$total = $tr.find('.sl-cost-total');
			$p1 = parseInt($count.val());
			$p2 = parseInt($amount.val());
			$p3 = parseInt($price.val());		
			$p1 = $p1 > 0 ? $p1 : 0;$p2 = $p2 > 0 ? $p2 : 0;$p3 = $p3 > 0 ? $p3 : 0;		
			$totalVal = ($p1 * $p2 * $p3);
			//$totalPrice += $totalVal;		
			///alert($totalVal);
			$total.val($totalVal);
			$totalPrice += $totalVal;
		});
		//alert($totalPrice);
		$this.find('.sl-cost-total-price').val($totalPrice);
		
	});
	//   alert($totalPrice)
	$cp1 = $totalCarPrice = parseInt(jQuery('#inputtourCP01').val()); // Chi phí xe, vận chuyển
	$cp2 = $totalPublicPrice = parseInt(jQuery('#inputtourCP02').val()); // Chi phí chung khác
	$cp3 = $totalHotelPrice = parseInt($cp3_e.val()); // Chi phí khách sạn
	//alert($cp3)
	$cp4 = $totalEatPrice = parseInt(jQuery('#inputtourCP04').val()); // Chi phí ăn uống
	$cp5 = $totalPrivatePrice = parseInt(jQuery('#inputtourCP05').val()); // Chi phí riêng khác
	$cp6 = $totalCostINC = parseInt(jQuery('#totalCostINC').val()); // Chi phí riêng khác
	$cp7 = $totalCostDEC = parseInt(jQuery('#totalCostDEC').val()); // Chi phí riêng khác
	$cp6 = $totalCostINC = $totalCostINC > 0 ? $totalCostINC : 0;
	$cp7 = $totalCostDEC = $totalCostDEC > 0 ? $totalCostDEC : 0;
	//$cp2 = parseInt(jQuery('#inputtourCP02').val());
	
	// Tính chi phí đầu người
	//$role.public = $cp1 / $guest;
	//$role['cp2'] = $cp2 / $guest;
	$role['commission'] = $commission;
	///
	//alert(JSON.stringify($role));
	$pPrice = jQuery('.persional_price_tour');
	$checkbox_checked = jQuery('.list_selected_hotel_'+$option);
	//alert($checkbox_checked.length);
	$checkbox = $checkbox_checked.length > 0 ? $checkbox_checked : jQuery('.list_selected_hotel_'+$option);
	$x = '';
	$_t = 0;$tPrice = [0,0,0,0,0,0,0];
	$checkbox.each(function(i,e){
		$t = jQuery(e);$index = $t.attr('data-index');
		$tr = $t.parent().parent();
		$h = JSON.parse($t.attr('data-hotel'));
		$child_checked = $tr.find('.radio-checked-manual'); 
		$child_checked_index =  $child_checked.attr('data-index');
		if($checkbox_checked.length > 0){
			for($i=0;$i<$h.detail.length;$i++){
				$v = $h.detail[$i];
				$price = parseInt($tr.find('.input-detail-price-'+$i).val());
				$tPrice[$i] += $price;
			}
		}
	});
	$checkbox.each(function(i,e){
		
		$t = jQuery(e);$index = $t.attr('data-index');$pindex = $t.attr('data-pindex');
		$tr = $t.parent().parent();
		$h = JSON.parse($t.attr('data-hotel'));
		//JSON.parse(data);
		$child_checked = $tr.find('.radio-checked-manual'); 
		$child_checked_index =  $child_checked.attr('data-index');
		//alert($child_checked_index);
		
		$name = (jQuery('.input-hotel-name-'+$pindex+'-'+$index).val());
		$star = (jQuery('.input-hotel-star-'+$pindex+'-'+$index).val());
		
		$x += '<tr class="col-middle"><td>'+$name+'</td>';
		$x += '<td class="center">'+$star+' sao</td>';
		
		$count = parseInt(jQuery('.input-hotel-count-'+$index).val());
		$amount = parseInt(jQuery('.input-hotel-amount-'+$index).val());
		 
		//for($i=0;$i<$h.detail.length;$i++){
			//alert($i)
		$TONG_CONG = $totalCarPrice + $totalPublicPrice + $totalHotelPrice + $totalEatPrice + $totalPrivatePrice + $totalCostINC - $totalCostDEC;
		//alert($totalHotelPrice);
		$exCostTotal = $TONG_CONG * $exCost / 100;
		$commissionPrice = $TONG_CONG * $commission / 100;
		$VAT_TOTAL = ($TONG_CONG + $exCostTotal + $commissionPrice) * $VAT / 100;
		$TBK =  ($TONG_CONG + $exCostTotal + $commissionPrice + $VAT_TOTAL) / $guest;
		$pPublicPrice = ($totalCarPrice + $totalPublicPrice)/$guest;
		$pPrivatePrice = ($totalHotelPrice + $totalEatPrice + $totalPrivatePrice)/$guest;	
		$role.pPublicPrice = ($pPublicPrice);
		$role.pPrivatePrice = ($pPrivatePrice);
		$role.pNetPrice = ($pPublicPrice + $pPrivatePrice);
		$role.pINCPrice = (($totalCostINC - $totalCostDEC)/$guest);
		$role.pCommission =  ($commissionPrice/$guest);
		$role.pExPrice =  ($exCostTotal/$guest);
		$role.pVatPrice =  ($VAT_TOTAL/$guest);
		$role.pTBK = ($TBK);
		$role.pTBKR = (roundMoney($TBK));
		$role.pTotalTour = roundMoney($TBK) * $guest;		 			
			
		$rx = JSON.stringify($role).replace(/"/g,'&quot;')
			 
			
		//}
		//if($quot){
		//	$x += '<td data-role="'+$rx+'" onclick="previewContent(this);" rowspan="'+$checkbox_checked.length+'" class="inputPreview vmiddle " title=""><input readonly class=" bold input_transparent  numberFormat center" value="'+$TBK+'" /></td>';
		//}else{
			$x += i==0 ? '<td data-role="'+$rx+'" onclick="previewContent(this);" rowspan="'+$checkbox_checked.length+'" class="inputPreview vmiddle " title=""><input readonly class=" bold input_transparent  numberFormat center" value="'+$TBK+'" /></td>' : '';			
		//}
		//}
		$x += '</tr>';
		
	});
	//alert($_t);
	if($checkbox_checked.length > 1){
		//$x += '<tr><td class="bold aright">Tổng:</td><td></td>';
		//for($i=0;$i<$h.detail.length;$i++){
			 
		//	$x += '<td class="" title="" colspan=4><input readonly class="bold input_transparent numberFormat center" value="'+$_t+'" /></td></tr>';
		//}
	}
	$pPrice.html($x);
	$totalPrice = 0;
	if($checkbox_checked.length > 0){
		//jQuery('.bang-bao-gia').hide();
		$checkbox.each(function(i,e){
			$index = jQuery(e).attr('data-index');
			$total = parseInt(jQuery('.sl-hotel-cost-total-'+$index).val());
			$totalPrice += $total;
		});
	}else{
		//jQuery('.bang-bao-gia').show();
	}
	jQuery('#input-total-hotel-cost').val($totalPrice);
	
	
	//// Tính chi phi tổng cộng
	
	
	//
	$p1 = $cp1+($cp2) + $totalPrice;
	jQuery('#inputPriceOfTour').val($p1);
	
	$exCost = $exCost > 0 ? $exCost : 0; $vat = $vat > 0 ? $vat : 0; $guest = $guest > 0 ? $guest : 1;
	$p2 = $p1 * $exCost / 100;
	$p3 = ($p1 + $p2) * $vat / 100;
	$p4 = $p1 + $p2 + $p3;
	$p5 = $p4 / $guest;
	jQuery('#inputExCostTotal').val($p2); jQuery('#inputVATTotal').val($p3);jQuery('#inputTotalPrice').val($p4);
	jQuery('#inputTotalPriceBQ').val(($p5));
	jQuery('#inputTotalPriceBQLT').val(roundMoney($p5));
	///
	//$checked = $checkbox.is(':checked');
	//alert($checkbox_checked) ;
	
	//jQuery('.numberFormat').number(true,0);
	reload_app('number-format');
	 
}
function previewContent(t){
	$this = jQuery(t);
	$guest = parseInt(jQuery('#input-tour-sokhach').val());
	$exCost = parseInt(jQuery('#inputExCostOfTour').val());
	$vat = parseInt(jQuery('#inputVATtOfTour').val());
	jQuery('.inputPreview').removeClass('active');
	$this.addClass('active'); 
	//alert($this.attr('data-role'));
	$d = JSON.parse($this.attr('data-role'));
	jQuery('#prevCommission').html(jQuery.number($d.pCommission));
	jQuery('#prevGenaralPrice').html(jQuery.number($d.pPublicPrice)); 
	jQuery('#prevPrivatePrice').html(jQuery.number($d.pPrivatePrice));
	jQuery('#prevNetPrice').html(jQuery.number($d.pNetPrice));
	jQuery('#prevINCPrice').html(jQuery.number($d.pINCPrice));
	jQuery('#prevExPrice').html(jQuery.number($d.pExPrice));
	jQuery('#prevVAT').html(jQuery.number($d.pVatPrice));
	jQuery('#prevTotal').html(jQuery.number($d.pTBK));
	jQuery('#prevRoundMoney').html(jQuery.number($d.pTBKR));
	jQuery('#prevTotalPrice').html(jQuery.number($d.pTotalTour));
	jQuery('#prevVATAmount').html(jQuery.number($vat,1));
	jQuery('#prevExAmount').html(jQuery.number($exCost,1));
	jQuery('#prevCommissionAmount').html(jQuery.number($d.commission,1));
	jQuery('#prevTotalGuest').html(jQuery.number($guest,0)+' khách');
	jQuery('.bang-bao-gia-preview').show();
}
function cprice(t){
	t = t.replace(/,/g,'');
	return parseInt(t);
}
function roundMoney(numB,f){	 
	$t = f == true ? 1000 : (numB > 1000000 ? 10000 : 1000);
	numC=Math.floor(numB/$t);
	numC=numC*$t;
	if ((numB-numC) > ($t == 1000 ? 499 : 4999)){numC=numC+$t}; 
	return numC;
}
function exportContract(){
	
}
function xuatExcel($object,$option){
    $href = $cfg.cBaseUrl; 
     
      jQuery.ajax({
        type: 'post',
        datatype: 'json',
		url: $href  + '/ajax',						 		 
        data: {action:'xuatExcel',option:$option,object:$object},
        beforeSend:function(){
                showFullLoading();
        },
        success: function (data) {  
        	//alert(data);
            var $d = JSON.parse(data);
            hideFullLoading();
             
            jQuery('#gridTableExportExcel').html($d.data) ;
             
            tableToExcel('gridTableExportExcel',$d.file_name);
           
           
            jQuery('#gridTableExportExcel').html('') ;
            
            
        },
        error : function(err, req) {
            hideFullLoading();
            showModal('Đã xảy ra lỗi','Quá trình thực hiện đã xảy ra lỗi, vui lòng thử lại.');
			}
      });
     
    
}
function removeXItem(t){
	$this = jQuery(t);
	$this.parent().parent().remove();
	//c//alculationTourCost(t);
}
function removeItemCost(t){
	$this = jQuery(t);
	$this.parent().parent().parent().remove();
	calculationTourCost(t);
}
function removeItemCostDetail(t){
	$this = jQuery(t);
	$index = $this.attr('data-index');
	$pindex = $this.attr('data-pindex') ? $this.attr('data-pindex') : false;
	//alert($pindex)
	$pr = $this.parent().parent().parent().parent();
	
	$pr.find('.sui-detail-row-'+$index).remove();
	//$v = parseInt(jQuery('#numberOfHotel').val()) - 1;
	//jQuery('#numberOfHotel').val($v);
}
function openPrint($action , $option){
	$href = $cfg.cBaseUrl + '/print/' + $action + $cfg.request; 
	window.open($href);
}
function loadCostCategory($target){
	//$this = jQuery(t);
	//$target = $this.attr('data-target');
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'loadCostCategory'},
	      beforeSend:function(){
 
	      },
	      success: function (data) {
	    	  //alert(data)
	          if(data != ""){
	              $d = JSON.parse(data);
	              jQuery($target).html($d.select).trigger("chosen:updated");
	          }
	          
	      },
	      error : function(err, req) {
	           
				}
	    });
} 
function loadLocaltion($target){
	//$this = jQuery(t);
	//$target = $this.attr('data-target');
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'loadLocaltion'},
	      beforeSend:function(){
 
	      },
	      success: function (data) {
	    	  //alert(data)
	          if(data != ""){
	              $d = JSON.parse(data);
	              jQuery($target).html($d.select).trigger("chosen:updated");
	          }
	          
	      },
	      error : function(err, req) {
	           
				}
	    });
} 

function loadHotel($target){
	//$this = jQuery(t);
	//$target = $this.attr('data-target');
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'loadHotel'},
	      beforeSend:function(){
 
	      },
	      success: function (data) {
	    	  //alert(data)
	          if(data != ""){
	              $d = JSON.parse(data);
	              jQuery($target).html($d.select).trigger("chosen:updated").change();
	          }
	          
	      },
	      error : function(err, req) {
	           
				}
	    });
} 
function loadCar($target){
	//$this = jQuery(t);
	//$target = $this.attr('data-target');
	$pr = jQuery('.car-detail-row-'+$index);
	$x = '';
	$pr.find('.sl-car-cost-amount').each(function(i,e){
		$id = jQuery(e).attr('data-id');
		$val = jQuery(e).val();
		$x += $id + ':' + $val + ',';
	});
	//alert($x);
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'loadCar'},
	      beforeSend:function(){
 
	      },
	      success: function (data) {
	    	  //alert(data)
	          if(data != ""){
	              $d = JSON.parse(data);
	              jQuery($target).html($d.select).trigger("chosen:updated").change();
	          }
	          
	      },
	      error : function(err, req) {
	           
				}
	    });
} 
function themdanhmucchiphi(t){
	$target = jQuery(t).attr('data-target');
	$type = jQuery(t).attr('data-type');
	$html  = '<form name="ajaxFormx" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return submitAjax(this);">';
	$html += '<input type="hidden" name="action" value="them_danh_muc_chiphi" /><input type="hidden" name="ptc[target]" value="'+$target+'" />';
	$html += '<input type="hidden" name="type" value="'+$type+'" />';
	$html += '<div class="modal-dialog" role="document">';
	$html += '<div class="modal-content">';
	$html += '<div class="modal-header">';
	$html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	$html += '<h4 class="modal-title f12e upper bold" id="myModalLabel" style="font-size:1.5em">Thêm danh mục chi phí</h4>';
	$html += '</div>';
	$html += '<div class="modal-body">';
    $html += '<section class="addCustomer addCashflow showAnimate uln control-poup">';
    $html += '<section class="boxInfo lbl-cl">';
    $html += '<article class="boxForm uln fll w100 mb10">';
 
    
    $html += '<div class="form-group">';
    $html += '<label for="inputMaphieu" class="col-sm-2 control-label">Tiêu đề</label>';
    $html += '<div class="col-sm-10">'; 
    $html += '<input type="text" class="form-control required" value="" name="f[name]" />';
    $html += '</div>';
    $html += '</div>';
    
             
    $html += '</article>';
    $html += '</section>';
 
    $html += '</section>';
	$html += '</div>';
	$html += '<div class="modal-footer">';		
	$html += '<button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-floppy-save"></i> Lưu</button>';
	$html += '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
	$html += '</div>';
	$html += '</div>';
	$html += '</div>';
	$html += '</form>';
	jQuery('.mymodal1').html($html).modal('show');
}

function changePriceCar(t){
	$this = jQuery(t);
	$target = $this.attr('data-target');
	$old = parseInt($this.attr('data-old'));
	$sn = parseInt(jQuery($this.attr('data-amount')).val());
	$sn = $sn >0 ? $sn : 1;
	$val = parseInt($this.val());
	//alert($old + '/' + $val);
	if($old != $val){
		$this.attr('data-old',$val);
		$tbk = $val / $sn;
		jQuery($target).val($tbk);
		$index = $this.attr('data-index');
		$radio = jQuery('.radio-change-car-cost-'+$index);
		//$ckc = $radio.is(':checked');
		//if($ckc){
			changeCarCost(t);
		//}
		
	}
	//A_calculationTourCost(t);
}
function changePriceHotel(t){
	/*$this = jQuery(t);
	$target = $this.attr('data-target');
	$old = parseInt($this.attr('data-old'));
	$sn = parseInt(jQuery($this.attr('data-amount')).val());
	$sn = $sn >0 ? $sn : 1;
	$val = parseInt($this.val());
	//alert($old + '/' + $val);
	if($old != $val){
		$this.attr('data-old',$val);
		$tbk = $val / $sn;
		jQuery($target).val($tbk);
		$index = $this.attr('data-index');
		$radio = jQuery('.radio-change-hotel-cost-'+$index);
		$ckc = $radio.is(':checked');
		if($ckc){*/
			changeHotelCost(t);
		//}
		
	//}
	//A_calculationTourCost(t);
}
function changeHotelCost(t){
	$this = jQuery(t); $dParent = $this.parent().parent();
	///
	$option = jQuery('.radioSelectOption:checked').attr('data-option');
	$autocheck = $this.attr('data-autocheck') ? true : false;
	$ck = $dParent.find('.radio-checked-hotel');
	if($autocheck){
		$val = parseInt($this.val());
		if($val > 0){
			$ck.prop('checked',true).val(1); 
		}else{
			$ck.prop('checked',false).val(0);
		}
	}
	//
	
	$index = $this.attr('data-index');
	$pindex = $this.attr('data-pindex');
	$parent = $this.attr('data-parent');
	$exchange = parseInt(jQuery('#inputXChangeRate').val());
	$tr = jQuery('.hotel-detail-body-index-'+$option+'-'+$pindex+'-'+$parent);
	
	$seat = parseInt($dParent.find('.sl-hotel-cost-seat').val());
	//alert($seat)
	////$sk = parseInt(jQuery('#input-tour-sokhach').val());
	//$sp = Math.ceil($sk/$amount);
	$hr = jQuery('.hotel-row-'+$option+'-'+$pindex+'-'+$parent);
	$pIndexCount = parseInt(jQuery('.input-hotel-count-'+$parent).val());
	$pIndexTotal = $hr.find('.sl-hotel-cost-total-'+$pindex+'-'+$parent);
	$pIndexAmount = $hr.find('.input-hotel-amount-'+$pindex+'-'+$parent);
	$pIndexPrice = $hr.find('.input-hotel-cost-'+$pindex+'-'+$parent);
	//alert($tbk); 
	$total = 0; $tAmount = 0;
	$radio = $tr.find('.radio-checked-manual');
	$radio.each(function(i,e){
		$pr = jQuery(e).parent().parent();
		$amount = parseInt($pr.find('.sl-hotel-cost-amount').val());
		$price = $p1 =  parseInt($pr.find('.sl-hotel-cost-price').val());
		if($price < 1000){
			$p1 = $price * $exchange;
			//alert($p1)
			//$price = $price * $exchange;
		}
		$pr.find('.sl-hotel-cost-total').val($amount * $price);
		$total += $amount * $p1;
		$tAmount += $amount;
		
	});
	///alert($tAmount)
	$tb = $total / $tAmount;
	$pIndexTotal.val($total);
	$pIndexAmount.val($tAmount);
	$pIndexPrice.val($tb);
	calculationTourCost(t);
	//$ckc = $radio.is(':checked');
	//if($ckc){
		//jQuery('.input-car-amount-'+$parent).val($sp)
		//jQuery('.input-car-cost-'+$parent).val($tbk).blur();
	//}
}

function removeHotelCost(t){
	$this = jQuery(t);	
	$count = jQuery('.hotel-row').length;
	//alert($count);
	//if($count>0){
		
		removeItemCostDetail(t,true);
		removeItemCost(t);
		//A_calculationTourCost(t);
	//}else{
		//showModal('Thông báo','Không thể xóa hết các khách sạn.');
	//}
}
function changeCarCost(t){
	$this = jQuery(t); 
	$dParent = $this.parent().parent();
	$index = $this.attr('data-index');
	$parent = $this.attr('data-parent');
	$tr = jQuery('.car-detail-body-index-'+$parent);
	
	//$sk = parseInt(jQuery('#input-tour-sokhach').val());
	//$sp = Math.ceil($sk/$amount);
	
	$pIndexCount = parseInt(jQuery('.input-car-count-'+$parent).val());
	$pIndexTotal = jQuery('.sl-car-cost-total-'+$parent);
	$pIndexAmount = jQuery('.input-car-amount-'+$parent);
	$pIndexPrice = jQuery('.input-car-cost-'+$parent);
	//alert($tbk);
	$total = 0; $tAmount = 0;
	$radio = $tr.find('.sl-car-cost-price');
	$radio.each(function(i,e){
		$amount = parseInt(jQuery(e).parent().parent().find('.sl-car-cost-amount').val());
		$price = parseInt(jQuery(e).parent().parent().find('.sl-car-cost-price').val());
		$total += $pIndexCount * $amount * $price;
		$tAmount += $amount;
		//alert($price);
	});
	$tb = $total / $tAmount /$pIndexCount;
	$pIndexTotal.val($total);
	$pIndexAmount.val($tAmount);
	$pIndexPrice.val($tb);
	calculationTourCost(t);
	//$ckc = $radio.is(':checked');
	//if($ckc){
		//jQuery('.input-car-amount-'+$parent).val($sp)
		//jQuery('.input-car-cost-'+$parent).val($tbk).blur();
	//}
}
function removeCarCost(t){
	$this = jQuery(t);	
	$count = jQuery('.car-row').length;
	//alert($count);
	if($count>1){
		
		removeItemCostDetail(t);
		removeItemCost(t);
		//A_calculationTourCost(t);
	}else{
		showModal('Thông báo','Không thể xóa hết trường xe.');
	}
}
function genTourCode(t){
	$this = jQuery(t);
	//$target = $this.attr('data-target');
	$cusID = jQuery('#inputCustomerID').val();
	$start = jQuery('#inputStarttime').val();
	$end = jQuery('#inputEndtime').val();
	$in = jQuery('#inputDateIN').val();
	$out = jQuery('#inputDateOUT').val();
	$id = jQuery('#inputTourID').val();
	$code = jQuery('#inputTourCode');
	//alert($start);
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'genTourCode',cusID:$cusID,pIN:$in,pOUT:$out,start:$start,end:$end,id:$id},
	      beforeSend:function(){
 
	      },
	      success: function (data) {
	    	  $code.val(data)
	         // if(data != ""){
	          //    $d = JSON.parse(data);
	          //    jQuery($target).html($d.select).trigger("chosen:updated");
	          //}
	          
	      },
	      error : function(err, req) {
	           
				}
	    });
} 
function changeNightPreview(t){
	$this = jQuery(t);
	$day = parseInt(jQuery('#input-day-amount').val());
	$n = jQuery('#input-night-amount');
	if(parseInt($n.val()) < $day-1 ){
		$night = $day - 1;
		$n.val($night); 
	}else{
		if(parseInt($n.val()) > $day+1){
			$night = $day + 1;
			$n.val($night); 
		}else $night = parseInt($n.val());
	}

	$max = Math.max($day,$night) - 1;
	$cDate =  jQuery('#inputStarttime').val().replace(/\//g,'-');
	$target = jQuery("#inputEndtime");
	$d = $cDate.split('-');
	$d1 = (Date.parse($d[2]+'-'+$d[1]+'-'+$d[0]));
	$d2 = new Date($d1);
	$d2.setDate($d2.getDate()+$max);
	//alert($d2);
	//alert($d1 + '/' +$d2)
	//var date = new Date(
     //       Date.parse(
    //        		$d[2]+'-'+$d[1]+'-'+$d[0] 
     //       )
     //   );
	 
	//var d = new Date();
	//jQuery("#inputStarttime").on("dp.change", function (e) {
    //    jQuery('#inputEndtime').data("DateTimePicker").minDate($cDate);
   // });
	$newDate = $d2;//(jQuery.format.date($d2, "dd/MM/yyyy"));
	
	//$n1 = $d2.getDate() > 9 ? $d2.getDate() : '0' + $d2.getDate();
	//$n2 = $d2.getMonth() > 8 ? $d2.getMonth()+1 : '0' + ($d2.getMonth()+1);
	if(jQuery('#inputStarttime').val() != "")
	$target.val($newDate) ;
}
function quickTranslate(t){
	$this = jQuery(t);
	$old = $this.attr('data-old');
	$new = $this.val();
	$role = $this.attr('data-role');
	$field = $this.attr('data-field');
	$r = JSON.parse($role);
	//alert($role)
	if($new != $old){
		jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'quickTranslate',val:$new,role:$role,id:$r.id,field:$field},
	      beforeSend:function(){
	    	  showLoading();
	    	  //showFullLoading();
	      },
	      success: function (data) {
	    	  $this.attr('data-old',$new);
	    	   
	    	  
	    	  //hideFullLoading();
	    	  //hideLoading();
	      },
	      complete:function(){
	    	  hideLoading();  
	      },
	      error : function(err, req) {
	           
				}
		});
	}
}
function quickTranslateText(t){
	$this = jQuery(t);
	$old = $this.attr('data-old');
	$new = $this.val();
	$role = $this.attr('data-role');
	$field = $this.attr('data-field');
	$r = JSON.parse($role);
	//alert($role)
	if($new != $old){
		jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'quickTranslateText',val:$new,role:$role,id:$r.id,field:$field},
	      beforeSend:function(){
	    	  showLoading();
	    	  //showFullLoading();
	      },
	      success: function (data) {
	    	  $this.attr('data-old',$new);
	    	   
	    	  //alert(data)
	    	  // hideFullLoading();
	    	  //hideLoading();
	      },
	      complete:function(){
	    	  hideLoading();  
	      },
	      error : function(err, req) {
	           
				}
		});
	}
}

function quickChange(t){
	$this = jQuery(t);
	$old = $this.attr('data-old');
	$new = $this.val();
	$role = $this.attr('data-role');
	$field = $this.attr('data-field');
	$r = JSON.parse($role);
	//alert($r.table)
	if($new != $old){
		jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'quickChange',val:$new,role:$role,id:$r.id,field:$field},
	      beforeSend:function(){
	    	  showLoading();
	    	  //showFullLoading();
	      },
	      success: function (data) {
	    	  $this.attr('data-old',$new);
	    	  //alert(data)
	    	  //$code.val(data)
	         // if(data != ""){
	          //    $d = JSON.parse(data);
	          //    jQuery($target).html($d.select).trigger("chosen:updated");
	          //}
	    	  
	    	  //hideFullLoading();
	    	  //hideLoading();
	      },
	      complete:function(){
	    	  hideLoading();  
	      },
	      error : function(err, req) {
	           
				}
		});
	}
}

function changeEatPreview(t){
	$this = jQuery(t);
    $quot = jQuery('#inputQuotation').val() == 'true' ? true : false;
	$old = ($this.attr('data-old'));
	$new = ($this.val());
	$n = jQuery('#input-day-amount');
	$day = parseInt($n.val());
	$dOld = parseInt($n.attr('data-old'));
	 
	$target2 = jQuery('.table-eat-cost-prev');
	$index  = parseInt(jQuery('#inputCountNumberOfEat').val());
	$time = jQuery('#inputStarttime').val();
	$time = $quot ? 'false' : $time;
     
	if($new != $old && $time != ""){
		if($day < $dOld){
			//alert($i)
			jQuery('.tr_eat_cost_index').each(function(i,e){
				$i = parseInt(jQuery(e).attr('data-index'));
				
				if($i > $day-1){
					jQuery(e).remove();	
					jQuery('#inputCountNumberOfEat').val($day);
					calculationTourCost(t);
				}
			});
		}else{
		jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'changeEatPreview',val:$day,index:$index,time:$time,quot:$quot},
	      beforeSend:function(){
	    	  //showLoading();
	    	  showFullLoading();
	      },
	      success: function (data) {
	    	  $this.attr('data-old',$new);
	    	  
	    	  // alert(data)
	    	  // $code.val(data)
	           if(data != ""){
	              $d = JSON.parse(data);
	              if($cfg.action == 'add'){
	            	//  alert($target.length)
	            	 $target2.html($d.html);
	              }else{
	            	 jQuery('#inputCountNumberOfEat').val($day);
	            	 $target2.append($d.html); 
	              }
	              
	          }	    	  
	    	  hideFullLoading();
	    	  //hideLoading();
	      },
	      complete:function(){
	    	 // hideLoading();  
	      },
	      error : function(err, req) {
	    	  hideFullLoading();
				}
		});
		}
	}
}

function changeHotelPreview(t){
    $quot = jQuery('#inputQuotation').val() == 'true' ? true : false;
	$this = jQuery(t);
	$old = ($this.attr('data-old'));
	$new = ($this.val());
	$n = jQuery('#input-night-amount');
	$day = parseInt($n.val());
	$dOld = parseInt($n.attr('data-old'));
	$target1 = jQuery('.table-hotel-cost-prev');
	$index  = parseInt(jQuery('#inputCountNumberOfHotel').val());
	$time = jQuery('#inputStarttime').val();
	//alert($old); alert($new)
    $time = $quot ? 'false' : $time;
	if($new != $old && $time != ""){
		if($day < $dOld){
			//alert($i)
			jQuery('.tr_hotel_cost_index').each(function(i,e){
				$i = parseInt(jQuery(e).attr('data-index'));
				
				if($i > $day-1){
					jQuery(e).remove();	
					jQuery('#inputCountNumberOfHotel').val($day);
					calculationTourCost(t);
				}
			});
		}else{
			//alert($day )
		jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'changeHotelPreview',val:$day,index:$index,time:$time,quot:$quot},
	      beforeSend:function(){
	    	  //showLoading();
	    	  showFullLoading();
	      },
	      success: function (data) {
	    	  $this.attr('data-old',$new);
	    	  
	    	  alert(data)
	    	  //$code.val(data)
	           if(data != ""){
	              $d = JSON.parse(data);
	              //$d.html = 'sdasdaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
	              if($cfg.action == 'add'){
	            	 // alert($target);
	            	 $target1.html($d.html);
	              }else{
	            	 jQuery('#inputCountNumberOfHotel').val($day);
	            	 $target1.append($d.html); 
	              }
	              jQuery('.select_hotel_option').each(function(i,e){
	            	 $Oindex = jQuery(e).attr('data-option');
	            	 jQuery(e).find('.add_hotel_button').attr('data-option',$Oindex);
	              });
	              
	          }	    	  
	    	  hideFullLoading();
	    	  //hideLoading();
	      },
	      complete:function(){
	    	 // hideLoading();  
	      },
	      error : function(err, req) {
	    	  hideFullLoading();
				}
		});
		}
	}
}

function showLoading(){
		 jQuery(".alert-success").removeClass('hide').addClass('in loading');
	}
function hideLoading(){
	jQuery(".alert-success").removeClass('loading') ;
    window.setTimeout(function() {
   	jQuery(".alert-success").addClass('hide'); }, 500);
}
function checkCustomerCode(t){
	$this = jQuery(t);
	$field = $this.attr('data-field') ? $this.attr('data-field') : 'code';
	$er = $this.parent().find('.error_field');
	if($er.length == 0){
	 $er = jQuery('<div class="error_field"></div>');
		 
	}
	$id = $this.attr('data-id') ? $this.attr('data-id') : 0;
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'checkCustomercode',val:$this.val(),id:$id,'field':$field},
	      beforeSend:function(){
	    	  //showLoading();
	    	  showFullLoading();
	      },
	      success: function (data) {
	    	  //alert(data)
	    	  $d = JSON.parse(data);
	    	  
	    	  //$this.attr('data-old',$new);
	    	  // alert(data)
	    	  //$code.val(data)
	           if($d.state == false){
	        	   $this.removeClass('error');jQuery('.submitFormBtn').removeAttr('disabled');
	        	   $er.addClass('success').html('Bạn có thể sử dụng giá trị này.');
	          //    $d = JSON.parse(data);
	          //    jQuery($target).html($d.select).trigger("chosen:updated");
	          }else{
	        	  jQuery('.submitFormBtn').attr('disabled','disabled');
	        	  $this.addClass('error');
	        	  $add = '<p class="red"><b>'+$this.val()+'</b> không hợp lệ hoặc đã được sử dụng.</p>';
	        	  $add += '<p>'+$d.data['name']+'</p>';
	        	  $add += '<p>Địa chỉ: '+$d.data['address']+'</p>';
	        	  $add += '<p>Điện thoại: '+$d.data['phone']+'</p>';
	        	  $er.removeClass('success').html($add);
	          }
	    	  
	    	  hideFullLoading();
	    	  //hideLoading();
	      },
	      complete:function(){
	    	  //hideLoading();  
	    	  $this.parent().append($er);
	      },
	      error : function(err, req) {
	           
				}
	});
}
function checkDomain(t){
	$this = jQuery(t);
	//$er = $this.parent().find('.error_field');
	$er = $this.parent().find('.error_field');
	if($er.length == 0){
	 $er = jQuery('<div class="error_field"></div>');
		 
	}
	$id = $this.attr('data-id') ? $this.attr('data-id') : 0;
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'checkDomain',val:$this.val(),id:$id },
	      beforeSend:function(){
	    	  //showLoading();
	    	  showFullLoading();
	      },
	      success: function (data) {
	    	  //alert(data)
	    	  $d = JSON.parse(data);
	    	  
	    	  //$this.attr('data-old',$new);
	    	  // alert(data)
	    	  //$code.val(data)
	           if($d.state == true){
	        	   $this.removeClass('error');jQuery('.submitFormBtn').removeAttr('disabled');
	        	   $er.addClass('success').html($d.msg);
	          //    $d = JSON.parse(data);
	          //    jQuery($target).html($d.select).trigger("chosen:updated");
	          }else{
	        	  jQuery('.submitFormBtn').attr('disabled','disabled');
	        	  $this.addClass('error');
	        	  $add = '<p class="red">'+$d.msg+'</p>';
	        	   
	        	  $er.removeClass('success').html($add);
	          }
	    	  
	    	  hideFullLoading();
	    	  //hideLoading();
	      },
	      complete:function(){
	    	  //hideLoading();  
	    	  $this.parent().append($er);
	      },
	      error : function(err, req) {
	           
				}
	});
}

function set_main_domain(t){
	$this = jQuery(t);
	//$er = $this.parent().find('.error_field');
	$er = $this.parent().find('.error_field');
	if($er.length == 0){
	 $er = jQuery('<div class="error_field"></div>');
		 
	}
	$id = $this.attr('data-id') ? $this.attr('data-id') : 0;
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'set_main_domain',val:$this.val(),id:$id },
	      beforeSend:function(){
	    	  showLoading();
	    	 // showFullLoading();
	      },
	      success: function (data) {
	    	 // 	alert(data)
	    	  //./$d = JSON.parse(data);
	    	  
	    	  
	    	  
	    	  //hideFullLoading();
	    	  //hideLoading();
	      },
	      complete:function(){
	    	  hideLoading();  
	    	  //$this.parent().append($er);
	      },
	      error : function(err, req) {
	           
				}
	});
}
function changeTourType(t){
	$this = jQuery(t);
	$v = $this.val();
	switch ($v) {
	case 1: case '1':
		jQuery('#table_CTYPE').addClass('inbound');
		jQuery('.hotel_room_3').find('.radio-checked-hotel').prop('checked',false).change();
		break;
	default:
		jQuery('#table_CTYPE').removeClass('inbound');
		break;
	}
}
function genGridUI($target,$data,$o){
	jQuery($target).shieldGrid({
        dataSource: {
            data: $data
        },
        
        columns: [
            { field: "id", width: "40px", title: "STT" },
            { field: "name", title: "Nội dung" },
            { field: "quantity",headerAttributes: { style: "text-align: center;" },attributes: { style: "text-align: center;" }, title: "Số lượng" , width: "150px" }, 
            { field: "seat",headerAttributes: { style: "text-align: center;" },attributes: { style: "text-align: center;" }, title: "Số người" , width: "150px" },
            { field: "price",headerAttributes: { style: "text-align: center;" },attributes: { style: "text-align: right;" }, title: "Đơn giá" , width: "250px" },
            { field: "action", title: "Thao tác", width: "80px",headerAttributes: { style: "text-align: center;" } }
        ],
        altRows: false,
        rowHover: false,
         
    });
	reload_app('number-format');
 
}
var tableToExcel = (function() {
	  var uri = 'data:application/vnd.ms-excel;base64,'
	    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
	    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
	    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
	  return function(table, name) {
	     
	    if (!table.nodeType) table = document.getElementById(table)
	    //alert(table);
	    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
	    var blob = new Blob([format(template, ctx)], { type: "application/xls" });
						saveAs(blob,  name+".xls");
	    //window.location.href = uri + base64(format(template, ctx))
	  }
	})();


function addDepartureScheduler(t){
	$this = jQuery(t);
	$index = parseInt($this.attr('data-index')) + 1;
	$this.attr('data-index',$index);
	$html = '<tr class="vmiddle"> <th class="center">'+($index+1)+'</th> ';
	$html += '<td><div class="input-group date datepickeronly"><input type="text" name="d['+$index+'][dtime]" class=" form-control input-sm" value="" /><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></td>';
	$html += '<td><input type="text" name="d['+$index+'][dprice]" class="bold red aright numberFormat form-control input-sm" value="" /></td>'; 
    $html += '<td><input type="text" name="d['+$index+'][status]" class=" form-control input-sm" value="" /></td> ';
	$html += '<td><input type="text" name="d['+$index+'][dnote]" class=" form-control input-sm" value="" /></td> ';
    $html += '<td class="center"> <i onclick="removeXItem(this);" class="glyphicon glyphicon-trash pointer"></i></td></tr>';
    jQuery('.tbody_departure_scheduler').prepend($html);
    reload_app('number-format');
    jQuery('.datepickeronly').datetimepicker({
        //language:'vi',
    	format:'DD/MM/YYYY',
       });
}
function removeItemTR($t){
	$this = jQuery($t); $this.parent().parent().remove();
}
function addConversation($t){
	$this = jQuery($t);
	$index = parseInt($this.attr('data-index'))+1;
    $role = parseInt($this.attr('data-role'));
	$this.attr('data-index',$index);
	$html = '<tr class="vmiddle"><th scope="row" class="center">'+($index+1)+'</th>';
	$html += '<td><input class="form-control input-sm" name="tab_biz['+$role+'][text_trans]['+$index+'][text]" value=""/></td>';
	$html += '<td><input class="form-control input-sm" name="tab_biz['+$role+'][text_trans]['+$index+'][mean]" value=""/></td>';	 
	$html += '<td><input data-decimal="2" class="form-control numberFormat input-sm center" name="tab_biz['+$role+'][text_trans]['+$index+'][time]" value=""/></td>';
	$html += '<td><input data-decimal="2" class="form-control numberFormat input-sm center" name="tab_biz['+$role+'][text_trans]['+$index+'][end]" value=""/></td>';
	$html += '<td class="center"><i onclick="removeItemTR(this);" class=" pointer glyphicon glyphicon-trash"></i></td></tr>';	 
	 
	jQuery('.tbl_conversation_'+$role).append($html);
    reload_app('number-format');
	 
}

function openUTab(t){
	$this = jQuery(t);
	$p = $this.parent().parent();
	$p.find('.utab-header a, .utab-panel .tpanel').removeClass('active');
	$this.addClass('active');
	//
	$href = $this.attr('href') ? $this.attr('href') : $this.attr('data-href');
	$p.find('.utab-panel '+$href).addClass('active');
	return false;
}
function changeTabPosition($t){
	$this = jQuery($t);
	if($this.is(':checked')){
		jQuery('#append-tabs .tab-pane').addClass('border-xx show').css({'display':'block'});
		jQuery('#append-tabs .tab-pane .p-content').hide();
		jQuery('.sort_tab').show();
		$('#append-tabs').sortable(); 
	}else{
		jQuery('#append-tabs .tab-pane').removeClass('show border-xx ');
		$('#append-tabs').disableSelection(); 
		jQuery('#append-tabs .tab-pane .p-content').show();
		jQuery('.sort_tab').hide();
	}
}

function changeFilterCode(t){
	$this = jQuery(t);
	//$er = $this.parent().find('.error_field');
	$er = $this.parent().find('.error_field');
	if($er.length == 0){
	 $er = jQuery('<div class="error_field"></div>');
		 
	}
	///$id = $this.attr('data-id') ? $this.attr('data-id') : 0;
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'changeFilterCode',val:$this.val()  },
	      beforeSend:function(){
	    	 // showLoading();
	    	 // showFullLoading();
	      },
	      success: function (data) {
	    	  // 	alert(data)
	    	  $d = JSON.parse(data);
	    	  $rs = jQuery('.rs_code_filter');
	    	  if($d.state){
	    		  $rs.val($d.code);
	    	  }else{
	    		  if($rs.val() == ""){
	    			  $rs.val($d.code);
	    		  }
	    	  }
	    	  
	    	  //hideFullLoading();
	    	  //hideLoading();
	      },
	      complete:function(){
	    	  //hideLoading();  
	    	  //$this.parent().append($er);
	      },
	      error : function(err, req) {
	           
				}
	});
}
function getFormStyle($t ){
	
	$this = jQuery($t);
	$role = parseInt($this.attr('data-role')) ;
	$id = parseInt($this.attr('data-id'));
	if(jQuery('#default_formstyle_'+$role).length > 0){
		
	}else{
	
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'getFormStyle',role:$role ,id:$id },
	      beforeSend:function(){
	    	 // showLoading();
	    	 // showFullLoading();
	      },
	      success: function (data) {
	    	   	//alert(data)
	    	   jQuery('body').append('<div class="hide" id="default_formstyle_'+$role+'">'+data+'</div>');
	    	  
	    	  //hideFullLoading();
	    	  //hideLoading();
	      },
	      complete:function(){
	    	  //hideLoading();  
	    	  //$this.parent().append($er);
	      },
	      error : function(err, req) {
	           
				}
	});
	}
}

function setdefaultTemplete($t ){
	
	$this = jQuery($t);
	$lang = $this.attr('data-lang');
	$id = parseInt($this.val());
 
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'setdefaultTemplete',lang:$lang ,id:$id },
	      beforeSend:function(){
	    	  showLoading();
	    	 // showFullLoading();
	      },
	      success: function (data) {
	    	   	//alert(data)
	    	  // jQuery('body').append('<div class="hide" id="default_formstyle_'+$role+'">'+data+'</div>');
	    	  
	    	  //hideFullLoading();
	    	  hideLoading();
	      },
	      complete:function(){
	    	  //hideLoading();  
	    	  //$this.parent().append($er);
	      },
	      error : function(err, req) {
	           
				}
	});
	 
}
function addNewTab($t){
    $this = jQuery($t);
    //getFormStyle($t); 
    
       $c_type = $this.attr('data-c_type') ? true : false;
       $role = parseInt($this.attr('data-role'));
       $id = parseInt($this.attr('data-id'));
       $tab = 'edetail-tab-'+$role;
       ////alert('#default_formstyle_'+$role);
      // alert(jQuery('#default_formstyle_'+$role).length);
      // alert($tab);
       $this.parent().before('<li class="pr" role="presentation"><a href="#" class="delTab" onclick="delTab(\'#'+$tab+'\');">x</a><a href="#'+$tab+'"  role="tab" data-toggle="tab">Tab '+($role + 1)+'</a></li>');
       jQuery.ajax({
 	      type: 'post',
 	      datatype: 'json',
 		  url: $cfg.adminUrl  + '/ajax',						 		 
 	      data: {action:'addNewTab',role:$role ,id:$id ,c_type:$c_type,tab:$tab},
 	      beforeSend:function(){
 	    	 // showLoading();
 	    	 // showFullLoading();
 	      },
 	      success: function (data) {
 	    	   	//alert(data)
 	    	  $html = data;  
 	    	// $html = '<div role="tabpanel" class="tab-pane" id="'+$tab+'"><div class="p-content"><div class="row"><div class="col-sm-6"><div class="form-group"><label   class="col-sm-2 control-label">Tiêu đề</label><div class="col-sm-10"><input data-id="0" type="text" name="tab['+$role+'][title]" class="form-control" id="inputTitleTab'+$role+'" placeholder="Title" value="Tab '+$role+'" /><input type="hidden" name="tab['+$role+'][id]" class="form-control" value="0" />   </div> </div>';
 	        
 	        //$html += $c_type ? '<div class="form-group"><label class="col-sm-2 control-label">Kiểu form</label><div class="col-sm-10">'+(jQuery('#default_formstyle_'+$role).html())+'</div></div>' : '';
 	        
 	        //$html += '</div><div class="col-sm-6"></div><div class="col-sm-10"><div class="form-group"><div class="col-sm-10 ajax_result_form_change'+$role+'"><textarea data-id="0" name="tab_biz['+$role+'][text]" class="form-control" id="xckc_'+$tab+'"  ></textarea>  </div> </div></div></div></div></div>';
 	        jQuery('#append-etabs').append($html);
 	        jQuery('a[href="#'+$tab+'"]').tab('show'); 
 	    	 $this.attr('data-role',$role+1);
 	        editor = CKEDITOR.replace('xckc_'+$tab,{
 	          height:400,
 	          filebrowserBrowseUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/ckfinder.html',
 	          filebrowserImageBrowseUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/ckfinder.html?type=Images',
 	          filebrowserFlashBrowseUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/ckfinder.html?type=Flash',
 	          filebrowserUploadUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
 	          filebrowserImageUploadUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
 	          filebrowserFlashUploadUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'

 	        });
 	      },
 	      complete:function(){
 	    	  //hideLoading();  
 	    	  //$this.parent().append($er);
 	      },
 	      error : function(err, req) {
 	           
 				}
 	});
       
       
       
       
       
       //jQuery('#inputTitleTab'+$role).select();

       return false;
}
function changeFormLessionType($t){
	$this = jQuery($t);
    $type = $this.val();
    $role = $this.attr('data-role');
	$class = $this.val() + '_only';
    $html = '';
    switch($type){
        case 'video':
        $html += '<div class="f_form_video f_form_change '+$type+'_only"><div class="browser_images">';		   
		$html += '<div class="form-group col-sm-3"><input type="file" class="form-control input-sm" name="myfile_'+$role+'_video" id="myfile_'+$role+'_video" accept="video/*"/></div>';			
		$html += '<button type="button" class="btn btn-default btn-sm btn-sd" onclick="return doUpload(\'_'+$role+'_video\',\'tab_biz['+$role+'][video]\');" style="vertical-align: middle; margin-left: 5px;"><i class="glyphicon glyphicon-upload"></i> Tải lên</button>';  	  
	    $html += '<div class="col-sm-12"><div class="row"><div class="col-sm-6">';
        $html += '<div id="progress-group_'+$role+'_video" class="row" ><p style="font: italic 1.2em arial; margin-bottom: 20px;">*** Chấp nhận định dạng mp4, avi, mpeg</p>';
	    $html += '<input type="text" id="removeAfterUpload_'+$role+'_video" class="form-control inputPreview removeAfterUpload" name="tab_biz['+$role+'][video]" value=""/>';
        $html += '</div></div>';
        $html += '<div class="" id="respon_image_uploaded_'+$role+'_video"></div></div></div></div>';
     
         $html += '<div class="f_form_srt f_form_change '+$type+'_only"><div class="browser_images">';		   
		$html += '<div class="form-group col-sm-3"><input type="file" class="form-control input-sm" name="myfile_'+$role+'_srt" id="myfile_'+$role+'_srt" accept="*"/></div>';			
		$html += '<button type="button" class="btn btn-default btn-sm btn-sd" onclick="return doUpload(\'_'+$role+'_srt\',\'tab_biz['+$role+'][srt]\');" style="vertical-align: middle; margin-left: 5px;"><i class="glyphicon glyphicon-upload"></i> Tải lên</button>';  	  
	    $html += '<div class="col-sm-12"><div class="row"><div class="col-sm-6">';
        $html += '<div id="progress-group_'+$role+'_srt" class="row" ><p style="font: italic 1.2em arial; margin-bottom: 20px;">*** Chấp nhận định dạng srt</p>';
	    $html += '<input type="text" id="removeAfterUpload_'+$role+'_srt" class="form-control inputPreview removeAfterUpload" name="tab_biz['+$role+'][srt]" value=""/>';
        $html += '</div></div>';
        $html += '<div class="" id="respon_image_uploaded_'+$role+'_srt"></div></div></div></div>';
        
        $html += '<div class="f_form_change '+$type+'_only"><div class="timeloc"><table class="table table-bordered table-hover"> <thead> <tr> <th width="50" class="center">STT</th><th>Hội thoại</th><th>Nghĩa</th><th>Thời gian hiển thị (giây)</th><th></th> </tr> </thead> <tbody class="tbl_conversation_'+$role+'">'; 
        $kx = -1;
     
		$html += '</tbody></table><table><tr class="vmiddle"><th scope="row" class="center"></th><td></td><td></td>';
		$html += '<td class="aright"><button data-role="'+$role+'" data-index="'+($kx)+'" onclick="addConversation(this);" type="button" class="btn btn-success input-sm"><i class="glyphicon glyphicon-plus"></i> Thêm hội thoại</button></td></tr></table></div></div>';
        
        jQuery('.ajax_result_form_change'+$role).html($html);
        break;
        
        default :
        $tab = "editor-detail-tab"+$role;
        //alert($tab)
        $html += '<div class="f_form_video f_form_change '+$type+'_only"><div class="browser_images">';		 
        $html += '<textarea data-id="0" name="tab_biz['+$role+'][text]" class="form-control" id="xckc_'+$tab+'"  ></textarea> ';
        
        $html += '</div></div>';
        jQuery('.ajax_result_form_change'+$role).html($html);	
        
        editor = CKEDITOR.replace('xckc_'+$tab,{
         height:400,
         filebrowserBrowseUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/ckfinder.html',
         filebrowserImageBrowseUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/ckfinder.html?type=Images',
         filebrowserFlashBrowseUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/ckfinder.html?type=Flash',
         filebrowserUploadUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
         filebrowserImageUploadUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
         filebrowserFlashUploadUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'

       });	 
        break;
    }
	//jQuery('.f_form_change').hide();
    
	////jQuery('.'+$class).show();
}
function enabledInput($t){
	$this = jQuery($t);
	$target = jQuery($this.attr('data-target'));
	$val = $this.is(':checked') ? 1 : 0;
	if($val == 1){
		$target.removeAttr('disabled').focus();
	}else{
		$target.attr('disabled','');
	}
}

function changeFormType($t){
	$this = jQuery($t);
	$target = jQuery($this.attr('data-target'));
	$val = $this.val();
	switch ($val) {
	case 'link': case 'manual':
		$target.show();
		//$target.find('input').val('sss').focus(); 
		return false;
		break;

	default: $target.hide();
		break;
	}
}
function autoSetNight($t){
	$this = jQuery($t);
	$target = jQuery($this.attr('data-target'));
	$v1 = parseInt($this.val());
	$v2 = ($target.val());
	//alert($v1 + ' - ' + $v2)
	if($v1 > 0 && ($v2 < 1 || $v2 == "" || $v2 == 'NaN')){
		$target.val($v1-1);
	}
}
function addCTab(){
	
}

function add_start_point($t) {
	$this = jQuery($t);
	$index = parseInt($this.attr('data-index'));
	$index = $index > 0 ? $index : 0;
	$this.attr('data-index',$index+1);
	$name = $this.attr('data-name');
	$target = jQuery($this.attr('data-target'));
	$c = jQuery($this.attr('data-source')).html();
	//alert($this.attr('data-source'));
	$html = '<div class="input-group group-mt5 group-sm30 group-vtop">';
	$html += '<select name="'+$name+'['+$index+'][dID]" class="form-control input-sm select2 aselect2">'+$c+'</select>';
	$html += '<span class="input-group-btn"></span>';
	$html += '<input name="'+$name+'['+$index+'][price]" class="form-control bold red input-sm anumberFormat" value="" placeholder="Giá phát sinh">';
	$html += '<span class="input-group-btn"><button onclick="removeItemTR(this);" type="button" class="btn btn-default "><i class="glyphicon glyphicon-trash"></i></button></span></div>';
	$target.prepend($html);
	reloadSelect2Ajax();reloadNumberFormat();
}
function reloadSelect2Ajax(){
	jQuery(".aselect2").select2({
     	language: "vi"
    });
}
function reloadNumberFormat(){
	jQuery('.anumberFormat').each(function(i,e){
		$x = $(e).attr('data-decimal') ? $(e).attr('data-decimal') : 0;
		jQuery(e).number( true,$x);
    });
}
function reloadTagInput(){
	jQuery('.ajax_taginput').each(function(i,e){
		//$x = $(e).attr('data-decimal') ? $(e).attr('data-decimal') : 0;
		jQuery(e).tagsinput();
    });
}
function change_filters_selected($t){
	$this  = jQuery($t);
	$val = $this.val();
	//$this.on('select2:selecting', function (evt) {
	//	  // Do something
	///	alert(evt.item)
	//	});
}
function set_departure_to_category($t){
	$this = jQuery($t);
	$category_id = $this.val();
	$departure_id = $this.attr('data-departure');
	$type = $this.attr('data-type');
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'set_departure_to_category',category_id:$category_id ,departure_id:$departure_id ,type:$type},
	      beforeSend:function(){
	    	  showLoading();
	    	 // showFullLoading();
	      },
	      success: function (data) {
	    	  // alert(data)
	         
	      },
	      complete:function(){
	    	  hideLoading();  
	    	  //$this.parent().append($er);
	      },
	      error : function(err, req) {
	           
				}
	});
}
function set_html_val($e,$val){
	jQuery($e).val($val); 
}

function set_default_ftp_server($t){
	$this = jQuery($t);
	$id = $this.attr('data-id') ? $this.attr('data-id') : 0;
	$sid = $this.attr('data-sid') ? $this.attr('data-sid') : 0;
	$ck = $this.is(':checked');
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/systemAjax',						 		 
	      data: {action:'set_default_ftp_server',sid:$sid ,id:$id,val:$ck ? 1 : 0},
	      beforeSend:function(){
	    	  showLoading();
	    	 // showFullLoading();
	      },
	      success: function (data) {
	    	  // alert(data)
	         
	      },
	      complete:function(){
	    	  hideLoading();  
	    	  //$this.parent().append($er);
	      },
	      error : function(err, req) {
	           
				}
	});
}
function add_more_language($t){
	$this = jQuery($t); $tr = $this.parent().parent();
	$k = parseInt($this.attr('data-count'));$this.attr('data-count',$k+1);
	$html  = '<tr><th scope="row"></th>'; 
	$html += '<td><input type="text" name="f['+$k+'][name]" value="" class="form-control input-sm"/></td>'; 
	$html += '<td><input type="text" name="f['+$k+'][cname]" value="" class="form-control input-sm"/></td>'; 
	$html += '<td><input type="text" name="f['+$k+'][code]" value="" class="form-control input-sm"/></td>'; 
	$html += '<td><input type="text" name="f['+$k+'][domain]" value="" class="form-control input-sm"/></td>';
	$html += '<td class="center"><input onchange="setCheckboxBool(this);" type="checkbox" name="f['+$k+'][root_active]" value="0" /></td>';
	$html += '<td class="center"><input onchange="setCheckboxBool(this);" type="checkbox" name="f['+$k+'][is_active]" value="0" /></td>'; 
	$html += '<td class="center"><input onchange="setRadioBool(this);" data-role="radio_bool1" type="radio" name="f['+$k+'][default]" value="0" class="radio_bool1"/></td>'; 
	$html += '<td class="center"><i title="Xóa" class="glyphicon glyphicon-trash pointer" onclick="removeTrItem(this);"></i></td></tr>';
	$tr.before($html);
}
function add_images_advert($t){
	$this = jQuery($t);
	$c = parseInt($this.attr('data-count'));
	$target = jQuery($this.attr('data-target'));
	//$html = '<div class="form-group"><label class="col-sm-3 control-label">Hình ảnh ' +(++$c)+'</label><div class="col-sm-9">';
	$html = '<p class="ptop10"><input type="file" id="inputslide_images_'+$c+'" name="list_images[]" class="f12e form-control" accept="image/*" /></p>';//</div></div>';
	$this.attr('data-count',$c);
	$target.append($html);
}
function change_adv_html_type($t){
	$this = jQuery($t);
	$val = $this.val();
	$target = jQuery('.adv_type_'+$val);
	$x = jQuery('.adv_type');
	$x.hide();
	$target.show();
}
function add_more_vehicles_categorys($t){
	$this = jQuery($t); $c = parseInt($this.attr('data-count')); 
	$q = parseInt($this.attr('data-quantity')); 
	$name = $this.attr('data-name') ? $this.attr('data-name') : 'f';
	$target = $this.parent().parent();
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'add_more_vehicles_categorys',name:$name,c:$c},
	      beforeSend:function(){
	    	  //showLoading();
	    	 // showFullLoading();
	      },
	      success: function (data) {
	    	    
	    	    $html = '<tr><th scope="row">'+($c+1)+'</th>';
	    		$html += '<td><input type="text" name="'+$name+'['+$c+'][name]" value="" class="form-control input-sm"/></td>'; 
	    		$html += '<td>'+data+'</td>';
	    		$html += '<td><input type="text" name="'+$name+'['+$c+'][title]" value="" class="form-control input-sm"/></td>';  
	    		$html += '<td class="center"><input type="text" name="'+$name+'['+$c+'][seats]" value="" class="form-control input-sm center numberFormat mw100p inline-block"/></td>';
	    		$html += $q == 1 ? '<td class="center"><input type="text" name="'+$name+'['+$c+'][quantity]" value="" class="form-control input-sm center numberFormat mw100p inline-block"/></td>' : '';  
	    		$html += '<td class="center"><input type="text" name="x['+$c+'][pmin]" value="" class="form-control input-sm center numberFormat mw100p inline-block"/></td>'; 
	    		$html += '<td class="center"><input type="text" name="x['+$c+'][pmax]" value="" class="form-control input-sm center numberFormat mw100p inline-block"/></td>'; 		
	    		$html += '<td class="center"><input type="text" name="'+$name+'['+$c+'][pmin]" value="" class="form-control input-sm center numberFormat mw100p inline-block"/></td>'; 
	    		$html += '<td class="center"><input type="text" name="'+$name+'['+$c+'][pmax]" value="" class="form-control input-sm center numberFormat mw100p inline-block"/></td>'; 		
	    	    
	    		$html += '<td class="center"><input onchange="setCheckboxBool(this);" type="checkbox" name="'+$name+'['+$c+'][is_active]" checked value="1" /></td>'; 
	    		
	    		$html += '<td class="center"><i title="Xóa" class="glyphicon glyphicon-trash pointer" onclick="removeTrItem(this);"></i></td>';
	    		$html += '</tr>';
	    		$this.attr('data-count',++$c);
	    		$target.before($html);
	    		reload_app('select2');
	      },
	      complete:function(){
	    	  hideLoading();  
	    	  //$this.parent().append($er);
	      },
	      error : function(err, req) {
	           
				}
	});
	
}
function add_more_rooms_categorys($t){
	$this = jQuery($t); $c = parseInt($this.attr('data-count')); 
	$target = $this.parent().parent();
	$html = '<tr><th scope="row">'+($c+1)+'</th>';
	$html += '<td><input type="text" name="f['+$c+'][name]" value="" class="form-control input-sm"/></td>'; 
	$html += '<td><input type="text" name="f['+$c+'][title]" value="" class="form-control input-sm"/></td>';  
	$html += '<td class="center"><input type="text" name="f['+$c+'][seats]" value="" class="form-control input-sm center numberFormat mw100p inline-block"/></td>';  
	$html += '<td class="center"><input type="text" name="f['+$c+'][position]" value="9" class="form-control input-sm center numberFormat mw100p inline-block"/></td>'; 
	//$html += '<td class="center"><input type="text" name="x['+$c+'][pmax]" value="" class="form-control input-sm center numberFormat mw100p inline-block"/></td>'; 		
	//$html += '<td class="center"><input type="text" name="f['+$c+'][pmin]" value="" class="form-control input-sm center numberFormat mw100p inline-block"/></td>'; 
	//$html += '<td class="center"><input type="text" name="f['+$c+'][pmax]" value="" class="form-control input-sm center numberFormat mw100p inline-block"/></td>'; 		
    
	$html += '<td class="center"><input onchange="setCheckboxBool(this);" type="checkbox" name="f['+$c+'][is_active]" checked value="1" /></td>'; 
	
	$html += '<td class="center"><i title="Xóa" class="glyphicon glyphicon-trash pointer" onclick="removeTrItem(this);"></i></td>';
	$html += '</tr>';$this.attr('data-count',++$c);
	$target.before($html);
}
function add_more_rooms_categorys1($t){
	$this = jQuery($t); $c = parseInt($this.attr('data-count')); 
	$target = $this.parent().parent();
	$html = '<tr><th scope="row">'+($c+1)+'</th>';
	$html += '<td><input type="text" name="h['+$c+'][name]" value="" class="form-control input-sm"/></td>'; 
	$html += '<td><input type="text" name="h['+$c+'][title]" value="" class="form-control input-sm"/></td>';  
	$html += '<td class="center"><input type="text" name="h['+$c+'][seats]" value="" class="form-control input-sm center ajax-number-format mw100p inline-block"/></td>';  
	$html += '<td class="center"><input type="text" name="h['+$c+'][quantity]" value="" class="form-control input-sm center ajax-number-format mw100p inline-block"/>'; 
	//$html += '<td class="center"><input type="text" name="x['+$c+'][pmax]" value="" class="form-control input-sm center numberFormat mw100p inline-block"/></td>'; 		
	//$html += '<td class="center"><input type="text" name="f['+$c+'][pmin]" value="" class="form-control input-sm center numberFormat mw100p inline-block"/></td>'; 
	//$html += '<td class="center"><input type="text" name="f['+$c+'][pmax]" value="" class="form-control input-sm center numberFormat mw100p inline-block"/></td>'; 		
    
	$html += '<input onchange="setCheckboxBool(this);" type="hidden" name="h['+$c+'][is_active]" checked value="1" /></td>'; 
	
	$html += '<td class="center"><i title="Xóa" class="glyphicon glyphicon-trash pointer" onclick="removeTrItem(this);"></i></td>';
	$html += '</tr>';$this.attr('data-count',++$c);
	$target.before($html);
}
function add_more_holidays_categorys($t){
	$this = jQuery($t); $c = parseInt($this.attr('data-count')); 
	$target = $this.parent().parent();
	$html = '<tr><th scope="row">'+($c+1)+'</th>';
	$html += '<td><input type="text" name="fx['+$c+'][title]" value="" class="form-control input-sm"/></td>'; 
	$html += '<td><input type="text" name="fx['+$c+'][code]" value="" class="form-control input-sm"/></td>';  
	//$html += '<td class="center"><input type="text" name="f['+$c+'][seats]" value="" class="form-control input-sm center numberFormat mw100p inline-block"/></td>';  
	//$html += '<td class="center"><input type="text" name="f['+$c+'][position]" value="9" class="form-control input-sm center numberFormat mw100p inline-block"/></td>'; 
	//$html += '<td class="center"><input type="text" name="x['+$c+'][pmax]" value="" class="form-control input-sm center numberFormat mw100p inline-block"/></td>'; 		
	//$html += '<td class="center"><input type="text" name="f['+$c+'][pmin]" value="" class="form-control input-sm center numberFormat mw100p inline-block"/></td>'; 
	//$html += '<td class="center"><input type="text" name="f['+$c+'][pmax]" value="" class="form-control input-sm center numberFormat mw100p inline-block"/></td>'; 		
    
	//$html += '<td class="center"><input onchange="setCheckboxBool(this);" type="checkbox" name="f['+$c+'][is_active]" checked value="1" /></td>'; 
	
	$html += '<td class="center"><i title="Xóa" class="glyphicon glyphicon-trash pointer" onclick="removeTrItem(this);"></i></td>';
	$html += '</tr>';$this.attr('data-count',++$c);
	$target.before($html);
}
function add_more_holidays($t){
	$this = jQuery($t); $c = parseInt($this.attr('data-count')); 
	$target = $this.parent().parent();
	$html = '<tr><th scope="row">'+($c+1)+'</th>';
	$html += '<td><input type="text" name="f['+$c+'][from_date]" value="" class="form-control input-sm ajax_datepicker"/></td>'; 
	$html += '<td><input type="text" name="f['+$c+'][to_date]" value="" class="form-control input-sm ajax_datepicker"/></td>'; 
	$html += '<td><input type="text" name="f['+$c+'][title]" value="" class="form-control input-sm"/></td>'; 
	$html += '<td>'+(jQuery('#ajax_select_htype').html())+'</td>';  
	 
	$html += '<td class="center"><i title="Xóa" class="glyphicon glyphicon-trash pointer" onclick="removeTrItem(this);"></i></td>';
	$html += '</tr>';$this.attr('data-count',++$c);
	$target.before($html);
	jQuery('.ajax_datepicker').datetimepicker({
	      //language:'vi',
	    	 format:'DD/MM/YYYY',
	      //pickTime:false,
	      //dateFormat:'DD/MM/YYYY'
	     });
}
function add_delete_item($id,$t){
	$this = jQuery($t);
	$name = $this.attr('data-name') ? $this.attr('data-name') : 'delete_item';
	if(jQuery('.delete_item').length == 0 ){
		jQuery('#editFormContent').prepend('<div class="delete_item"></div>');
	}
	jQuery('.delete_item').append('<input type="hidden" name="'+$name+'[]" value="'+$id+'"/>');
}
function add_delete_item1($id){
	jQuery('.delete_item').append('<input type="hidden" name="delete_item1[]" value="'+$id+'"/>');
}
function get_list_chon_xe($t){
	$this = jQuery($t);
	$id = $this.val();
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'get_list_chon_xe',id:$id},
	      beforeSend:function(){
	    	  showLoading();
	    	 // showFullLoading();
	      },
	      success: function (data) {
	    	  alert(data)
	    	 jQuery('#bang_list_chon_xe').html(data);
	         reload_app('number_format');
	      },
	      complete:function(){
	    	  hideLoading();  
	    	  //$this.parent().append($er);
	      },
	      error : function(err, req) {
	           
				}
	});
}
function get_list_chon_phong($t){
	$this = jQuery($t);
	$id = $this.val();
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'get_list_chon_phong',id:$id},
	      beforeSend:function(){
	    	  showLoading();
	    	 // showFullLoading();
	      },
	      success: function (data) {
	    	 jQuery('#bang_list_chon_xe').html(data);
	         reload_app('number_format');
	      },
	      complete:function(){
	    	  hideLoading();  
	    	  //$this.parent().append($er);
	      },
	      error : function(err, req) {
	           
				}
	});
}
function check_form_chon_xe(){
	$t = true; $c = 0;  
	jQuery('.input-select-vehicle-quantity').each(function(i,e){
		$x = parseInt(jQuery(e).val());
		$c += $x > 0 ? $x : 0; 
	});
	
	if($c > 0){
		jQuery('.show_error_out').html('');
		return true;
	}else{		
		jQuery('.show_error_out').html('<p class="help-block italic f12p red">Phải chọn ít nhất 1 giá trị</p>');
		return false;
	}
}
function get_quotation_type(){
	return jQuery('.radio_quotation_type:checked').val();
}
function open_manual_option($t){
	$this = jQuery($t);
	$tr = $this.parent().parent();
	if($this.is(':checked')){
		$tr.find('.manual_input').removeAttr('readonly');
	}else{
		$tr.find('.manual_input').attr('readonly',''); 
	}
}
function get_list_ftp_file($folder){
	jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax/ftp',						 		 
	      data: {action:'get_list_ftp_file',folder:$folder},
	      beforeSend:function(){
	    	  //showLoading();
	    	 // showFullLoading();
	      },
	      success: function (data) {
	    	 jQuery('#list_files_folders').html(data);
	         
	      },
	      complete:function(){
	    	  //hideLoading();  
	    	  //$this.parent().append($er);
	      },
	      error : function(err, req) {
	           
				}
	});
}
function set_selected($t){
	$this = jQuery($t);
	$this.parent().find('.selected').removeClass('selected');
	//$this.parent().find('.cboxElement').removeClass('cboxElement');
	$this.addClass('selected');
}
function zoom($t){
	jQuery($t).colorbox({rel:'group1'});
}
function copyToClipboard(elem) {
	  // create hidden text element, if it doesn't already exist
  var targetId = "_hiddenCopyText_";
  var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
  var origSelectionStart, origSelectionEnd;
  if (isInput) {
      // can just use the original source element for the selection and copy
      target = elem;
      origSelectionStart = elem.selectionStart;
      origSelectionEnd = elem.selectionEnd;
  } else {
      // must use a temporary form element for the selection and copy
      target = document.getElementById(targetId);
      if (!target) {
          var target = document.createElement("textarea");
          target.style.position = "absolute";
          target.style.left = "-9999px";
          target.style.top = "0";
          target.id = targetId;
          document.body.appendChild(target);
      }
      target.textContent = elem.textContent;
  }
  // select the content
  var currentFocus = document.activeElement;
  target.focus();
  target.setSelectionRange(0, target.value.length);
  
  // copy the selection
  var succeed;
  try {
  	  succeed = document.execCommand("copy");
  } catch(e) {
      succeed = false;
  }
  // restore original focus
  if (currentFocus && typeof currentFocus.focus === "function") {
      currentFocus.focus();
  }
  
  if (isInput) {
      // restore prior selection
      elem.setSelectionRange(origSelectionStart, origSelectionEnd);
  } else {
      // clear temporary content
      target.textContent = "";
  }
  return succeed;
}
function add_more_contacts_list($t){
	$this = jQuery($t);
	$count = parseInt($this.attr('data-count'));
	$html = '<div class="block-example2"><i title="Xóa" onclick="removeTrItem(this,1);" class="remove_item glyphicon glyphicon-trash pointer"></i>';
	$html += '<label  class="control-label bold">Họ tên</label>';
	$html += '<input type="text" name="biz[contacts]['+$count+'][name]" class="form-control" placeholder="" value="" />';
	$html += '<label  class="control-label bold">Số điện thoại</label>';
	$html += '<input type="text" name="biz[contacts]['+$count+'][phone]" class="form-control" placeholder="" value="" />';
	$html += '<label  class="control-label bold">Email</label>';
	$html += '<input type="email" name="biz[contacts]['+$count+'][email]" class="form-control" placeholder="" value="" />';
	$html += '</div>';
	$this.attr('data-count',$count+1);
	jQuery('.contacts_list').prepend($html);
}
function add_more_bank_list($t){
	$this = jQuery($t);
	$count = parseInt($this.attr('data-count'));
	$html = '<div class="block-example2"><i title="Xóa" onclick="removeTrItem(this,1);" class="remove_item glyphicon glyphicon-trash pointer"></i>';
	$html += '<label  class="control-label bold">Số tài khoản</label>';
	$html += '<input type="text" name="biz[bank]['+$count+'][account]" class="form-control" placeholder="" value="" />';
	$html += '<label  class="control-label bold">Tên ngân hàng</label>';
	$html += '<input type="text" name="biz[bank]['+$count+'][name]" class="form-control" placeholder="" value="" />';
	$html += '<label  class="control-label bold">Chi nhánh</label>';
	$html += '<input type="email" name="biz[bank]['+$count+'][branch]" class="form-control" placeholder="" value="" />';
	$html += '</div>';
	$this.attr('data-count',$count+1);
	jQuery($this.attr('data-target')).prepend($html);
}
function add_more_fileds_list($t){
	$this = jQuery($t);
	$count = parseInt($this.attr('data-count'));
	$html = '<div class="block-example2"><i title="Xóa" onclick="removeTrItem(this,1);" class="remove_item glyphicon glyphicon-trash pointer"></i>';
	$html += '<label  class="control-label bold">Tiêu đề</label>';
	$html += '<input type="text" name="biz[fileds]['+$count+'][name]" class="form-control" placeholder="" value="" />';
	$html += '<label  class="control-label bold">Nội dung</label>';
	$html += '<input type="text" name="biz[fileds]['+$count+'][text]" class="form-control" placeholder="" value="" />';
	//$html += '<label  class="control-label bold">Email</label>';
	//$html += '<input type="email" name="biz[contacts]['+$count+'][email]" class="form-control" placeholder="" value="" />';
	$html += '</div>';
	$this.attr('data-count',$count+1);
	jQuery('.customers_fileds_list').prepend($html);
}
function check_all_item($t){
	$this = jQuery($t);
	$role = $this.attr('data-role');
	$checkboxes = jQuery('input[data-role='+$role+']');
	$checkboxes.prop('checked', $this.is(':checked'));
}
function turnoff_editor($t){
	$this = jQuery($t);
	  //view_obj(CKEDITOR.instances.ckeditor_text_detail );
	$target = $this.attr('data-target');
	if (!$this.is(':checked') ){		 
		var config = {};
		$id =  '#'+jQuery('.ajax_ckeditor').attr('id');
		jQuery($id).addClass('ckeditor_full');
		editor = create_ckeditor($id);
	}else {
		
	
	

	// Retrieve the editor contents. In an Ajax application, this data would be
	// sent to the server or used in any other way.
	//document.getElementById($target).innerHTML =  editor.getData();
	//document.getElementById( 'contents' ).style.display = '';

	// Destroy the editor.
	CKEDITOR.instances.ckeditor_text_detail.destroy();
	//CKEDITOR.instances.ckeditor_text_detail = null;
	}
	 
	 
}
function create_ckeditor($t){
	$this = jQuery($t);
    $id = $this.attr('id');
   // alert($id)
    $width = parseInt($this.attr('data-width'));
    $height = parseInt($this.attr('data-height'));
    $expand = $this.attr('data-expand') ? $this.attr('data-expand') : true;
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
    return editor;
 };
 
 function templete_load_child($t){
	 $this = jQuery($t);
	 $target = jQuery($this.attr('data-target'));
	 jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:'templete_load_child',id:$this.val()},
	      beforeSend:function(){
	    	  //showLoading();
	    	 // showFullLoading();
	      },
	      success: function (data) {
	    	 // alert(data)
	    	 $target.html(data).select2();
	         
	      },
	      complete:function(){
	    	  //hideLoading();  
	    	  //$this.parent().append($er);
	      },
	      error : function(err, req) {
	           
				}
	});
 }
 function add_ctab_tab($t){ 
	 $this = jQuery($t);
	 $c_type = $this.attr('data-c_type') ? true : false;
     $role = parseInt($this.attr('data-count'));
     $id = parseInt($this.attr('data-id'));
     $tab = 'edetail-tab-'+$role;
      
     $this.parent().before('<li class="pr" role="presentation"><a href="#" class="delTab" onclick="delTab(\'#'+$tab+'\');">x</a><a href="#'+$tab+'"  role="tab" data-toggle="tab">Tab '+($role + 1)+'</a></li>');
     $html = '<div role="tabpanel" class="tab-pane" id="edetail-tab-'+$role+'"><div class="p-content"><div class="row">';
     $html += '<div class="col-sm-6 "><div class="form-group"><label class="col-sm-2 control-label">Tiêu đề</label><div class="col-sm-10">';
     $html += '<input type="text" name="ctab['+$role+'][title]" class="form-control"  placeholder="Title" value="" />';
     $html += '</div></div></div><div class="col-sm-6 "><div class="form-group"><label class="col-sm-2 control-label">Style</label><div class="col-sm-10 group-sm34"><select class="form-control ajax-select2-hide-seearch ">';    
     $html += '<option value="0">--</option>'; 

for($i = 1;$i<11;$i++){
	$html += '<option value="'+$i+'">Style '+$i+'</option>';
}
 
 	$html += '</select></div></div></div><div class="col-sm-12"><div class="form-group"><div class="col-sm-12 col8respon">';
 	$html += '<textarea class="ckeditor_full form-control" id="xckc_'+$tab+'" data-height="350" name="ctab['+$role+'][text]" ></textarea>';
       
    $html += '</div></div></div></div></div></div>';
     jQuery('#append-tabs').append($html);
	        jQuery('a[href="#'+$tab+'"]').tab('show'); 
	    	 $this.attr('data-count',$role+1);
	        editor = CKEDITOR.replace('xckc_'+$tab,{
	          height:400,
	          filebrowserBrowseUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/ckfinder.html',
	          filebrowserImageBrowseUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/ckfinder.html?type=Images',
	          filebrowserFlashBrowseUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/ckfinder.html?type=Flash',
	          filebrowserUploadUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
	          filebrowserImageUploadUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
	          filebrowserFlashUploadUrl : $cfg.baseUrl+'/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'

	        });
	       
     return false;
 }
 function get_notifications($t){
	 $this = jQuery($t);
	 $target = $this.find('ul.msg_list');
	 if(!$target.is(':visible')){
		 jQuery.get($cfg.cBaseUrl +'ajax/notifis?action=getNotifis',{},function(r){
				
				// Setting a timeout for the next request,
				// depending on the chat activity:
				//alert(r)
				var nextRequest = 3000;
				$n = jQuery('.item-notifications');
				$badge = $n.find('.alert-count');
				$badge.hide();
				$target.html(r.html)
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
			
				 
			},'json');
	 }
 }
 function show_help($t){
	 $this = jQuery($t);
	 $id = $this.attr('data-id');$rid = $this.attr('data-rid');
	 jQuery.post($cfg.cBaseUrl +'ajax/helps',{action:'get_item',id:$id,rid:$rid},function(r){
			
			// Setting a timeout for the next request,
			// depending on the chat activity:
			//alert(r)
			 
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
		
			 
		},'json').done(function (d) {
			$this.parent().parent().html(d.text); 
			window.history.pushState({"html":d.text,"pageTitle":d.title},"", d.link);
			// jstree_right_panel js-helps-panel
			jQuery('.jstree_right_panel').removeClass('js-helps-panel');
		});
	 return false;
 }
 function tour_program_add_service($t){
	 $this = jQuery($t);
	 if($this.attr('data-edit') == '1'){
		 $action = '_tour_program_edit_service';
		 $edit = true;
	 }else{
		 $action = '_tour_program_add_service';
		 $edit = false;
	 } 
	 if($this.attr('data-day') == '1'){		 
		 $day = true;
	 }else{
		 $day = false;
	 } 
	 //$action = $this.attr('data-edit') == '1' ? '_tour_program_edit_service' : '_tour_program_add_service';
	 $index = parseInt($this.attr('data-count')) > 0 ? parseInt($this.attr('data-count')) : 0;
	 //
	 $pr = $this.attr('data-parent');
	 $target = $this.attr('data-target');
	 $tg = jQuery($target);
	 $type_id = $edit ? $tg.find('.input-service-group-type-id').val() : 0;
	 $local_id = $edit ? $tg.find('.input-service-group-local-id').val() : 234;
	 //
	 $html ='';
	 $html += '<form name="" action="./ajax" class=" form-horizontal f12e" method="post" onsubmit="return submitAjax(this);">';
		$html += '<div class="modal-dialog" role="document">';
		$html += '<div class="modal-content">';
		$html += '<div class="modal-header">';
		$html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$html += '<h4 class="modal-title f12e upper bold" id="myModalLabel" style="font-size:1.5em">Chọn thêm dịch vụ</h4>';
		$html += '</div>';
		$html += '<div class="modal-body">';
     $html += '<section class="addCustomer addCashflow showAnimate uln control-poup">';
     $html += '<section class="boxInfo lbl-cl">';
     $html += '<article class="boxForm uln fll w100 mb10">';
     
     if($day){
    	 if($edit){
    		 $date = $tg.find('.input-service-group-date').val();
    	 }else{
    		 var d = new Date();
    		 var day = d.getDate();
    		 var month = d.getMonth();
    		 var year = d.getFullYear();
    		 $date = day + '/' + month + '/' + year;
    	 }
    	 $html += '<div class="form-group">';
         $html += '<label class="control-label col-sm-2" >Ngày tháng</label>';
         $html += '<div class="col-sm-10" ><div class="input-group date ajax-datepicker">';
         $html += '<input type="text" name="f[date]" class="form-control input-sm" value="'+$date+'" />';
         $html += '<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div>';   
         $html += '</div>';            
         $html += '</div>'; 
         
         $html += '<div class="form-group">';
         $html += '<label class="control-label col-sm-2" >Quốc gia</label>';
         $html += '<div class="col-sm-10" ><select data-edit="'+$edit+'" data-ptarget="'+$target+'" data-type_id="'+$local_id+'" name="f[local_id]" data-target="" data-num="true" data-select="chosen" class="chosen-select form-control _chon_lanh_tho_" ></select>';   
         $html += '</div>';            
         $html += '</div>'; 
     }
     
     
     $html += '<div class="form-group">';
     $html += '<label class="control-label col-sm-2" >Danh mục</label>';
     $html += '<div class="col-sm-10" ><select data-edit="'+$edit+'" data-ptarget="'+$pr + ' ' + $target+'" data-type_id="'+$type_id+'" name="f[type_id]" data-target="._tour_program_change_category_type" onchange="return _tour_program_change_category_type(this);" data-num="true" data-select="chosen" class="chosen-select form-control _danh_muc_" ></select>';   
     $html += '</div>';            
     $html += '</div>'; 
    
   
     
     $html += '<div class=" _tour_program_change_category_type">';           
     $html += '</div>';
             
     $html += '</article>';
     $html += '</section>';
  
     $html += '</section>';
		$html += '</div>';
		$html += '<div class="modal-footer">';		
		$html += '<button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-floppy-save"></i> Lưu lại</button>';
		$html += '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';
		$html += '</div>';
		$html += '</div>';
		$html += '</div>';
		//$html += '<input type="hidden" name="field[cusID]" value="'+$cusID+'"><input type="hidden" name="field[classID]" value="'+$classID+'">';
		$html += '<input type="hidden" name="action" value="'+$action+'">';
		$html += '<input type="hidden" name="f[target]" value="'+($target)+'">';
		$html += '<input type="hidden" name="f[index]" value="'+$index+'">';
		$html += '<input type="hidden" name="f[parent]" value="'+($this.attr('data-parent'))+'">';
		$html += '<input type="hidden" name="f[exchange_rate]" value="'+(jQuery('#input_exchange_rate').val())+'">';
		$html += '<input type="hidden" name="f[day]" value="'+$day+'">';
		$html += '</form>';
		
		jQuery('.mymodal').html($html).modal('show');
		_load_danh_muc_('._danh_muc_',$type_id);
		_get_text_data('_chon_lanh_tho_','._chon_lanh_tho_');
		reload_app('chosen');
 }
 function _tour_program_change_category_type($t){
	 $this = jQuery($t);
	 $target = jQuery($this.attr('data-target'));
	 $tg = jQuery($this.attr('data-ptarget'));
	 $val = parseInt($this.val());
	 $edit = $this.attr('data-edit');
	 $group_name = $edit == 'true' ? $tg.find('.input-service-group-name').val() : '';
	 $title = $edit == 'true' ? $tg.find('.input-service-group-title').val() : '';
	 $summary = $edit == 'true' ? $tg.find('.input-service-group-summary').val() : '';
	 $amount = $edit == 'true' ? $tg.find('.input-service-group-amount').val() : 1;
	 $price = $edit == 'true' ? $tg.find('.input-service-group-price').val() : '';
	 $guide_type = $edit == 'true' ? $tg.find('.input-service-group-guide-type:checked').val() : 2;
	 $enabled_edit = $edit == 'true' ? $tg.find('.input-service-group-enabled-edit').val() : 'on';
	 $currency = $edit == 'true' ? $tg.find('.input-service-group-currency').val() : 1;
	 $html = '<div class="form-group"><label class="control-label col-sm-2">Nhóm dịch vụ</label><div class="col-sm-10"><input type="text" class="form-control required" placeholder="Service group" name="f[group_name]" value="'+$group_name+'"/></div></div>'; 
	 $html += '<div class="form-group"><label class="control-label col-sm-2">Tên dịch vụ</label><div class="col-sm-10"><input type="text" class="form-control required" placeholder="Title of service" name="f[title]" value="'+$title+'"/></div></div>';
	 $html += '<div class="form-group"><label class="control-label col-sm-2">Tóm lược</label><div class="col-sm-10"><input type="text" class="form-control " placeholder="Summary" name="f[summary]" value="'+$summary+'"/></div></div>';
	 $html += '<div class="form-group"><label class="control-label col-sm-2">Số lượng</label><div class="col-sm-10"><input type="text" class="form-control ajax-number-format" placeholder="Amount" name="f[amount]" value="'+$amount+'"/></div></div>';
	 $html += '<div class="form-group"><label class="control-label col-sm-2">Đơn giá</label><div class="col-sm-7"><input type="text" class="form-control ajax-number-format bold red" data-decimal="2" placeholder="Price" name="f[price]" value="'+$price+'"/></div><div class="col-sm-3"><select data-id="1" name="f[currency]" data-select="chosen" class="chosen-select-no-single _get_select_currency_"></select></div></div>';
	 switch($val){
	 case 53:
		 $html += '<div class="form-group"><label class="control-label col-sm-2">Loại HD</label><div class="col-sm-10"><label class="checkbox w50"><input type="radio" class="" name="f[guide_type]" value="1" '+($guide_type == 1 ? 'checked' : '')+'/> Từng tuyến</label><label class="checkbox w50"><input type="radio" class="" '+($guide_type == 2 ? 'checked' : '')+' name="f[guide_type]" value="2"> Toàn tuyến</label></div></div>';	
	 break;
	 	default:
	 		
	 		break;
	 }
	 $html += '<div class="form-group"><label class="control-label col-sm-2">&nbsp;</label><div class="col-sm-10"><label class="checkbox mgl20"><input type="checkbox" class="" name="f[enabled_edit]" '+($enabled_edit == 'on' ? 'checked' : '')+'> Cho phép chỉnh sửa</label></div></div>';
	 $target.html($html);
	 _get_text_data('_get_select_currency_','._get_select_currency_');

	 reload_app('chosen');
	 reload_app('number-format');reload_app('datepicker');
 }
 function _get_text_data($action, $target){
	 jQuery.ajax({
	      type: 'post',
	      datatype: 'json',
		  url: $cfg.adminUrl  + '/ajax',						 		 
	      data: {action:$action,id:jQuery($target).attr('data-id')},
	      beforeSend:function(){

	      },
	      success: function (data) {
	    	//  console.log(data)
	          if(data != ""){
	              $d = JSON.parse(data);
	              $this = jQuery($target) 
	              $this.html($d.html);
	              if($this.attr('data-select') == 'chosen')
	              $this.trigger("chosen:updated").change();
	          }
	          
	      },
	      error : function(err, req) {
	           
	      }
	 });
 }
 function _load_danh_muc_($target,$type_id){		 
		jQuery.ajax({
		      type: 'post',
		      datatype: 'json',
			  url: $cfg.adminUrl  + '/ajax',						 		 
		      data: {action:'_load_danh_muc_',type_id:$type_id},
		      beforeSend:function(){
	 
		      },
		      success: function (data) {
		    	  //console.log(data)
		          if(data != ""){
		              $d = JSON.parse(data);
		              jQuery($target).html($d.select).trigger("chosen:updated").change();
		          }
		          
		      },
		      error : function(err, req) {
		           
					}
		    });
 }
 function tour_program_change_rete_service($t){
	 $this = jQuery($t);
	 $this.parent().find('.cell-category-selected').removeClass('cell-category-selected');
	 $this.addClass('cell-category-selected');
	 $this.find('input[type=radio]').prop('checked', true);
 }
 function tour_program_calculation_price($t){
	 $ckc = true;
	 if($t !== undefined){
		 $this = jQuery($t);		 
		 $old = $this.attr('data-old');
		 if($old == $this.val()){
			 $ckc = false;
		 }else{
			 $this.attr('data-old',$this.val());
		 }
		  
	 }
	 //console.log($ckc)
	 if($ckc){
		 // Service group
		 $g = jQuery('#tour-program-services-group');
		 
		 $subs_total = $g.find('.service-group-row');
		 $total_group = $total_day = 0;
		 $subs_total.each(function(i,e){
			 $selected = jQuery(e).find('.input-service-group-state').is(':checked');
			 if($selected){
			 //$subs_total = $g.find('.input-service-group-price');
			 $val = parseFloat(jQuery(e).find('.input-service-group-price').val());
			 $val = $val > 0 ? $val : 0;
			 $total_group += $val;
			 }
		 });
		 jQuery('#tour_program_total_group').html(jQuery.number($total_group,0));
		 
		 // Service day
		 $g = jQuery('#tour-program-services-day');
		 
		 $subs_total = $g.find('.service-group-row');
		 $total_group = $total_day = 0;
		 $subs_total.each(function(i,e){
			 $selected = jQuery(e).find('.input-service-group-state').is(':checked');
			 if($selected){
			 //$subs_total = $g.find('.input-service-group-price');
			 $val = parseFloat(jQuery(e).find('.input-service-group-price').val());
			 $val = $val > 0 ? $val : 0;
			 $total_group += $val;
			 }
		 });
		 jQuery('#tour_program_total_day').html(jQuery.number($total_group,0));
	 }
 }
 function tour_program_change_price_status($t){
	 $this = jQuery($t);
	 $pr = $this.parent().parent();
	 $e = $pr.find('.input-service-group-price');
	 if($this.is(':checked')){
		 $e.removeClass('disabled-text-status');
		 $e.removeAttr('readonly');
	 }else{
		 $e.addClass('disabled-text-status');
		 $e.attr('readonly','')
	 }
 }