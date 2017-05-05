jQuery(document).ready(function(){
	var $body = jQuery('body'); 

	jQuery.get($cfg.baseUrl +'/sajax',{action:'load_taskscheduler'},function($r){
		 //console.log($r)
	},'json');
	jQuery.get($cfg.baseUrl +'/sajax',{action:'execute_cronjobs'},function($r){
		 //console.log($r)
	},'json');
	setAutoHeightElement();
	if($body.has('#gridTable').length==0){jQuery('body').append('<div id="gridTable"></div>')}if($body.has('#gridTableExportExcel').length==0){jQuery('body').append('<table id="gridTableExportExcel"></table>')}if(jQuery('body').has('.mymodal').length==0){jQuery('body').append('<div class="modal mymodal" tabindex="-1" role="dialog" aria-labelledby=""></div>')}if(jQuery('body').has('.mymodal1').length==0){jQuery('body').append('<div class="modal mymodal2" tabindex="-1" role="dialog" aria-labelledby=""></div>')}if(jQuery('body').has('.mymodal2').length==0){jQuery('body').append('<div class="modal mymodal2" tabindex="-1" role="dialog" aria-labelledby=""></div>')}jQuery(window).scroll(function(){if($cfg.is_admin==false&&jQuery(this).scrollTop()>500){if(jQuery('.btn-scroll-to-top').length==0){jQuery('body').append('<a class="btn-scroll-to-top" onclick="return scrollToTop();" href="javascript:void(0);"></a>');}jQuery('.btn-scroll-to-top').show();}else{jQuery('.btn-scroll-to-top').hide();}})
jQuery(window).resize(function(){var $w=jQuery(window).width();
var $h=jQuery(window).height();__system_screen($w,$h);
setItemRatio();
resizeItem4x3();setAutoHeightElement();

});
	//
	jQuery(window).bind('beforeunload', function () {
	    if($body.hasClass('confirm-reload')){
	    	return 'Dữ liệu chưa được lưu. Bạn có chắc chắn tải lại trang ?';
	    }    
	});
	jQuery("#slivechat .chat_fb_header").click(function() {
		jQuery('#slivechat .fchat').toggle('slow');
		return false;
	});
	//
	if(jQuery('.system-style.bottom_nav').length>0){var $h=0;jQuery('.system-style.bottom_nav li.li-level-1').each(function(){if((jQuery(this).height())>$h){$h=jQuery(this).height();}});jQuery('.system-style.bottom_nav li.li-level-1').css({"min-height":$h+'px'});}jQuery('.system.btnPaging').click(function(){var $this=jQuery(this);$role=($this.attr('role'));$loading=$this.parent().find('.img_loading');$loading.removeClass('hide');$.ajax({type:'post',datatype:'json',url:'/ajax',data:{role:$role,action:'loadingItem'},success:function(data){$loading.addClass('hide');var $d=JSON.parse(data);$this.attr('role',$d.role);jQuery('ul.ajax_result').append($d.r);var $d=JSON.parse($d.role);if($d.end==true){$this.parent().addClass('hide');}},error:function(err,req){alert("Error");}});});jQuery('.sys_lang .sys_flag').click(function(){$role=jQuery(this).attr('role');__system_set_language($role);});setItemRatio();jQuery('.sdatetimepicker').each(function(i,e){$maxDate=jQuery(e).attr('data-maxDate')?jQuery(e).attr('data-maxDate'):false;jQuery(e).datetimepicker({format:'DD/MM/YYYY HH:mm',maxDate:$maxDate});});jQuery('.sdatepicker').each(function(i,e){$maxDate=jQuery(e).attr('data-maxDate')?jQuery(e).attr('data-maxDate'):false;$locale=jQuery(e).attr('data-locale')?jQuery(e).attr('data-locale'):false;jQuery(e).datetimepicker({format:'DD/MM/YYYY',maxDate:$maxDate});});
resizeItem4x3();

jQuery('.popup_colorbox').each(function(i,e){jQuery(e).colorbox({rel:jQuery(e).attr('rel')});});jQuery('.s-slick-slider').each(function(i,e){var $this=jQuery(e);var $rows=$this.attr('data-rows')?parseInt($this.attr('data-rows')):1;var $items=$this.attr('data-items')?parseInt($this.attr('data-items')):1;var $category=$this.attr('data-category')?parseInt($this.attr('data-category')):-1;var $arrows=$this.attr('data-arrows')?($this.attr('data-arrows')=='false'?false:true):true;$this.slick({slidesToShow:$items,slidesToScroll:$items,rows:$rows,arrows:$arrows,dots:true,customPaging:function(slider,i){return'<button class="btn-custom-paging index-'+(i+1)+'" data-index="'+(i+1)+'" type="button" data-role="none" role="button" aria-required="false" tabindex="0">'+(i+1)+'</button>';}}).on('beforeChange',function(event,slick,currentSlide,nextSlide){$index=Math.ceil(nextSlide/$items)+1;$paging=$this.parent().parent().find('.paging');$paging.find('.active').removeClass('active');$paging.find('.page.page-'+$index).addClass('active');$it=$this.find('.item.item-page-'+$index);$next=$index+1;$prev=$index-1;$prev=$prev<1?1:$prev;$total=parseInt($paging.attr('data-total'));$next=$next>$total?$total:$next;$paging.find('.first').attr('data-page',$prev);$paging.find('.last').attr('data-page',$next);if($it.attr('data-loaded')=='false'){jQuery.ajax({type:'post',datatype:'jsonp',url:$cfg.baseUrl+'/ajax',data:{action:'get_paging_slick',p:$index,limit:$items,category:$category},beforeSend:function(){},success:function(data){$d=JSON.parse(data);jQuery.each($d.data,function(key,value){$start=(($index-1)*$items)+key;jQuery('.item.item-page-'+$index+'[data-slick-index='+$start+']').html(value).attr('data-loaded','true');});},error:function(err,req){},complete:function(){}});}});});__system_init__();});function setItemRatio(){jQuery('[data-toggle="setRatio"]').each(function(i,e){$ratio=jQuery(e).attr('data-ratio')?parseFloat(jQuery(e).attr('data-ratio')):false;if($ratio!=false){$w=$ratio*jQuery(e).width();}});}function clearInput($t){var $this=jQuery($t);$this.find('input[text]').val('')}
function __system_init__(){
	var $w=jQuery(window).width();
	var $h=jQuery(window).height();
	jQuery.get($cfg.baseUrl+'/sajax/__system_init__',function(r){
		jQuery('body').addClass(r.device+' '+r.short_name+' '+r.platform+' '+r.short_name+r.version+' '+$cfg.controller+'_page');
		if($cfg.is_admin==false&&r.background!==false){
			var link=document.createElement("style");
			link.type="text/css";
			var newContent=document.createTextNode(r.background);
			link.appendChild(newContent);
			document.getElementsByTagName("head")[0].appendChild(link);
			}},'json');
}
function __system_screen($w,$h){}function __system_set_language($lang){}function showFullLoading(t){switch(t){case true:break;default:jQuery('body').append('<div class="fixed-loading-modal"></div>');break;}}
function changeLanguage($lang,$t){var $this=jQuery($t);
var $redirect=$this.attr('data-redirect');
var $cLang=$this.attr('data-lang');
var $r=false;if(!$redirect){}else{$r=true;window.location=$redirect+'/ajax?action=setLanguage&lang='+$lang;}
if(!$r&&$lang!=$cLang){
	jQuery.ajax({type:'post',datatype:'json',url:$cfg.baseUrl+'/sajax',data:{lang:$lang,action:'changeLanguage'},beforeSend:function(){showFullLoading();},success:function(data){window.location=$cfg.cBaseUrl;hideFullLoading();},error:function(err,req){}});}}function checkUserExisted($t){var $this=jQuery($t);$val=$this.val();var re=/^[\w]+$/;jQuery('.btn-submit').attr('disabled','');jQuery('.error-alert').html('<i class="loading"></i>').show();if(!re.test($val)){jQuery('.error-alert').html('<i class="glyphicon glyphicon-remove text-danger"></i> Mật khẩu cũ không đúng.').addClass('bg-danger').removeClass('bg-success');jQuery('.btn-submit').attr('disabled','');$this.focus();return false;}jQuery.ajax({type:'post',datatype:'json',url:$cfg.baseUrl+'/sajax',data:{val:$val,action:'checkUserExisted'},beforeSend:function(){jQuery('.error-alert').addClass('loading_ajax').html('Vui lòng chờ...').show();},success:function(data){var $d=JSON.parse(data);if($d.state){jQuery('.error-alert').html('<i class="glyphicon glyphicon-ok text-success"></i> Bạn có thể sử dụng tên này').addClass('bg-success').removeClass('bg-danger loading_ajax');jQuery('.btn-submit').removeAttr('disabled');}else{jQuery('.btn-submit').attr('disabled','');jQuery('.error-alert').html('<i class="glyphicon glyphicon-remove text-danger"></i> Tên đăng nhập không hợp lệ, hoặc đã được sử dụng.').addClass('bg-danger').removeClass('bg-success loading_ajax');}},error:function(err,req){}});}function checkOldPassword($t){var $this=jQuery($t);$val=$this.val();var re=/^[\w]+$/;jQuery('.btn-submit').attr('disabled','');jQuery('.error-alert').html('<i class="loading"></i>').show();jQuery.ajax({type:'post',datatype:'json',url:$cfg.cBaseUrl+($cfg.is_admin?'/':'')+'ajax',data:{val:$val,action:'checkOldPassword'},beforeSend:function(){jQuery('.error-alert').addClass('loading_ajax').html('Vui lòng chờ...').show();},success:function(data){var $d=JSON.parse(data);if($d.state){jQuery('.error-alert').html('<i class="glyphicon glyphicon-ok text-success"></i> Nhập mật khẩu mới ở ô bên dưới.').addClass('bg-success').removeClass('bg-danger loading_ajax');$this.attr('disabled','');jQuery('.new_pass,.re_new_pass').removeAttr('readonly');jQuery('.new_pass').focus();}else{jQuery('.btn-submit').attr('disabled','');jQuery('.error-alert').html('<i class="glyphicon glyphicon-remove text-danger"></i> Mật khẩu cũ không đúng.').addClass('bg-danger').removeClass('bg-success loading_ajax');}},error:function(err,req){}});}function checkPassword(){$p1=jQuery('.password1').val();$p2=jQuery('.password2').val();$p3=jQuery('.password3').val();if($p2==$p1){jQuery('.error-alert1').html('<i class="glyphicon glyphicon-remove text-danger"></i> Mật khẩu không được trùng với mật khẩu cũ.').addClass('bg-danger').removeClass('bg-success loading_ajax').show();}else{if($p2!=$p3){if($p3.length==0){jQuery('.password3').focus();}else{jQuery('.error-alert1').html('<i class="glyphicon glyphicon-remove text-danger"></i> Mật khẩu mới không khớp.').addClass('bg-danger').removeClass('bg-success loading_ajax').show();}}else{if($p2.length<5){jQuery('.error-alert1').html('<i class="glyphicon glyphicon-remove text-danger"></i> Mật khẩu phải có ít nhất 6 ký tự.').addClass('bg-danger').removeClass('bg-success loading_ajax').show();}else{jQuery('.btn-submit').removeAttr('disabled');jQuery('.error-alert1').remove();}}}}function hideFullLoading(){jQuery('.fixed-loading-modal').remove();}function popup_youtube(e){jQuery(e).colorbox({iframe:true,innerWidth:640,innerHeight:390});}function popup_colorbox(e){jQuery(e).colorbox({rel:jQuery(e).attr('data-rel')});}function setLocation($location){window.location=$location;}function gotoUrl($location){window.location=$location;}function goBack(){window.history.back();}var ChuSo=new Array(" không "," một "," hai "," ba "," bốn "," năm "," sáu "," bảy "," tám "," chín ");var Tien=new Array(""," nghìn"," triệu"," tỷ"," nghìn tỷ"," triệu tỷ");function DocSo3ChuSo(baso){var tram;var chuc;var donvi;var KetQua="";tram=parseInt(baso/100);chuc=parseInt((baso%100)/10);donvi=baso%10;if(tram==0&&chuc==0&&donvi==0)return"";if(tram!=0){KetQua+=ChuSo[tram]+" trăm ";if((chuc==0)&&(donvi!=0))KetQua+=" linh ";}if((chuc!=0)&&(chuc!=1)){KetQua+=ChuSo[chuc]+" mươi";if((chuc==0)&&(donvi!=0))KetQua=KetQua+" linh ";}if(chuc==1)KetQua+=" mười ";switch(donvi){case 1:if((chuc!=0)&&(chuc!=1)){KetQua+=" mốt ";}else{KetQua+=ChuSo[donvi];}break;case 5:if(chuc==0){KetQua+=ChuSo[donvi];}else{KetQua+=" lăm ";}break;default:if(donvi!=0){KetQua+=ChuSo[donvi];}break;}return KetQua;}function docso(SoTien){var lan=0;var i=0;var so=0;var KetQua="";var tmp="";var ViTri=new Array();if(SoTien<0)return"Số tiền âm !";if(SoTien==0)return"Không đồng !";if(SoTien>0){so=SoTien;}else{so=-SoTien;}if(SoTien>8999999999999999){return"Số quá lớn!";}ViTri[5]=Math.floor(so/1000000000000000);if(isNaN(ViTri[5]))ViTri[5]="0";so=so-parseFloat(ViTri[5].toString())*1000000000000000;ViTri[4]=Math.floor(so/1000000000000);if(isNaN(ViTri[4]))ViTri[4]="0";so=so-parseFloat(ViTri[4].toString())*1000000000000;ViTri[3]=Math.floor(so/1000000000);if(isNaN(ViTri[3]))ViTri[3]="0";so=so-parseFloat(ViTri[3].toString())*1000000000;ViTri[2]=parseInt(so/1000000);if(isNaN(ViTri[2]))ViTri[2]="0";ViTri[1]=parseInt((so%1000000)/1000);if(isNaN(ViTri[1]))ViTri[1]="0";ViTri[0]=parseInt(so%1000);if(isNaN(ViTri[0]))ViTri[0]="0";if(ViTri[5]>0){lan=5;}else if(ViTri[4]>0){lan=4;}else if(ViTri[3]>0){lan=3;}else if(ViTri[2]>0){lan=2;}else if(ViTri[1]>0){lan=1;}else{lan=0;}for(i=lan;i>=0;i--){tmp=DocSo3ChuSo(ViTri[i]);KetQua+=tmp;if(ViTri[i]>0)KetQua+=Tien[i];if((i>0)&&(tmp.length>0))KetQua+=',';}if(KetQua.substring(KetQua.length-1)==','){KetQua=KetQua.substring(0,KetQua.length-1);}KetQua=KetQua.substring(1,2).toUpperCase()+KetQua.substring(2);return KetQua;}function changeTourStyle(t){var $this=jQuery(t);$val=parseInt($this.val());$text=jQuery('.tour_type_text_'+$val).val();jQuery('.tour_start_text').val($text);switch($val){case 1:jQuery('.tour_start_multi').hide();jQuery('.table_depart_scheduler').show();jQuery('.tour_start_text').hide();jQuery('.group-item-price').hide();break;case 5:jQuery('.table_depart_scheduler').hide();jQuery('.tour_start_text').hide();jQuery('.tour_start_multi').show();jQuery('.group-item-price').show();break;default:jQuery('.table_depart_scheduler').hide();jQuery('.tour_start_text').show();jQuery('.tour_start_multi').hide();jQuery('.group-item-price').show();break;}}function reEnableInput($t){var $this=jQuery($t);$target=$this.attr('data-target');jQuery($target).find('input,textarea,button').removeAttr('disabled');jQuery('.btn-submit').html('<i class="glyphicon glyphicon-floppy-save"></i> Lưu lại');}function scrollToTop(){jQuery('html,body').animate({scrollTop:0},500);return false;}function scrollToDiv($div,$offset){if(jQuery($div).length>0){$offset=$offset>0?$offset:0;var $o=jQuery($div).offset().top-$offset;jQuery('html,body').animate({scrollTop:$o},500);return false;}}function scrollToDivX($div){if(jQuery($div).length>0){var $o=jQuery($div).offset().top-60;jQuery('html,body').animate({scrollTop:$o},500);return false;}else{}}
	function showModal($title,$content){
		 
		var $html='<div class="modal-dialog modal-sm">';
	$html+='<div class="modal-content">';
	$html+='<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title f12e" id="mySmallModalLabel" style="font-size: 1.5em;">'+$title+'</h4></div>';$html+='<div class="modal-body f12e">'+$content+'</div>'
$html+='<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>';
	$html+='</div></div>';
	if(jQuery('.mymodal').length==0){
		jQuery('body').append('<div class="modal mymodal" tabindex="-1" role="dialog" aria-labelledby=""></div>');
	}
	jQuery('.mymodal').html($html).modal('show');
	}
	
	function showMModal($title,$content){
		var $html='<div class="modal-dialog ">';
		$html+='<div class="modal-content">';
		$html+='<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title f12e" id="mySmallModalLabel" style="font-size: 1.5em;">'+$title+'</h4></div>';
		$html+='<div class="modal-body f12e">'+$content+'</div>'
		$html+='<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>';
		$html+='</div></div>';
		jQuery('.mymodal').html($html).modal('show');}
	function showXModal($content){
		var $html='<div class="modal-dialog ">';
		$html+='<div class="modal-content">';
		//$html+='<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title f12e" id="mySmallModalLabel" style="font-size: 1.5em;">'+$title+'</h4></div>';
		$html+='<div class="modal-body f12e">'+$content+'</div>'
		//$html+='<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>';
		$html+='</div></div>';
		jQuery('.mymodal').html($html).modal({'show':true,backdrop: 'static', keyboard: true});
		 
		}
	function showZModal($title,$content){ 
		var $html='<div class="modal-dialog ">';
		$html+='<div class="modal-content">';
		$html+='<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title f12e bold" id="mySmallModalLabel" style="font-size: 1.5em;">'+$title+'</h4></div>';
		$html+='<div class="modal-body f12e">'+$content+'</div>'
		//$html+='<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>';
		$html+='</div></div>';
		jQuery('.mymodal').html($html).modal({'show':true,backdrop: 'static', keyboard: true});
		 
		}
	function showFModal($content,t){$html='';$this=jQuery(t);$today=jQuery.format.date(new Date(),"dd/MM/yyyy H:m");switch($content){case'changeAvatar':$html='<form name="ajaxFormx" class="ajaxForm form-horizontal f12e" method="post" onsubmit="return submitAjaxs(this);">';$html+='<input type="hidden" name="action" value="changeAvatar" />';$html+='<div class="modal-dialog" role="document">';$html+='<div class="modal-content">';$html+='<div class="modal-header">';$html+='<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';$html+='<h4 class="modal-title f12e upper bold" id="myModalLabel" style="font-size:1.5em">Thay đổi hình đại diện</h4>';$html+='</div>';$html+='<div class="modal-body">';$html+='<section class="addCustomer addCashflow showAnimate uln control-poup">';$html+='<section class="boxInfo lbl-cl">';$html+='<article class="boxForm uln fll w100 mb10">';$html+='<div class="form-group">';$html+='<div class="col-sm-10">';$html+='<div class="browser_images"><div class="form-group col-sm-9">';$html+='<input type="file" class="form-control input-sm " name="myfile" id="myfile" />';$html+='</div>';$html+='<button data-name="biz" type="button" data-index="0" class="btn btn-default btn-sm btn-sd" onclick="return ajaxUploadFiles(this);" style="vertical-align: middle; margin-left: 5px;"><i class="glyphicon glyphicon-upload"></i> Tải lên</button>';$html+='<div class="col-sm-12"><div id="progress-group" class="" ></div><div class="" id="respon_image_uploaded"></div></div></div>';$html+='</div>';$html+='</div>';$html+='</article>';$html+='</section>';$html+='</section>';$html+='</div>';$html+='<div class="modal-footer">';$html+='<button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-floppy-save"></i> Cập nhật</button>';$html+='<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Hủy</button>';$html+='</div>';$html+='</div>';$html+='</div>';$html+='</form>';break;default:break;}jQuery('.mymodal').html($html).modal('show');}function randomStr($l){var text="";var possible="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";for(var i=0;i<$l;i++)text+=possible.charAt(Math.floor(Math.random()*possible.length));return text;}function getLocalByParent(t){var $this=jQuery(t);$new=$this.val();jQuery.ajax({type:'post',datatype:'json',url:$cfg.baseUrl+'/sajax',data:{action:'getLocalByParent',val:$new},beforeSend:function(){},success:function(data){$target=jQuery($this.attr('data-target'));$target.html(data);if($target.hasClass('select2')){$target.trigger('change');}},complete:function(){},error:function(err,req){}});}function removeErrorField($t){jQuery('form .error_alert').remove();}function showErrorField($t){var $this=jQuery($t);jQuery('form .error_alert').remove();$text=$this.attr('data-alert')?$this.attr('data-alert'):$cfg.text[210];$p='<p class="error_alert bg-danger pd15 mgt10"><i class="glyphicon glyphicon-remove"></i> '+$text+'</p>';$this.parent().append($p);}function showSuccessField($t){var $this=jQuery($t);jQuery('form .error_alert').remove();$text=$this.attr('data-success')?$this.attr('data-success'):$cfg.text[211];$p='<p class="error_alert bg-success pd15 mgt10"><i class="glyphicon glyphicon-ok"></i> '+$text+'</p>';$this.parent().append($p);}function refreshCaptcha($t){document.getElementById('captcha_image').src=$cfg.libsDir+'/captcha/?'+Math.random();document.getElementById('input-captcha-code').value='';document.getElementById('input-captcha-code').focus();return false;}function checkMemberExisteds($t){$j=jQuery;$this=$j($t);$val=$this.val();$field=$this.attr('data-field');$id=$this.attr('data-id');jQuery.ajax({type:'post',datatype:'json',url:$cfg.baseUrl+'/sajax',data:{action:'checkMemberExisteds',val:$val,field:$field,id:$id},beforeSend:function(){},success:function(data){$d=JSON.parse(data);if($d.state>0){$j('.submitFormBtn').attr('disabled','');showErrorField($t);}else{$j('.submitFormBtn').removeAttr('disabled');showSuccessField($t);}},complete:function(){},error:function(err,req){}});}function checkCaptcha($t){$j=jQuery;$this=$j($t);$val=$this.val();$field=$this.attr('data-field');$id=$this.attr('data-id');jQuery.ajax({type:'post',datatype:'json',url:$cfg.baseUrl+'/sajax',data:{action:'checkCaptcha',val:$val},beforeSend:function(){},success:function(data){$d=JSON.parse(data);if($d.state>0){$j('.submitFormBtn').attr('disabled','');$j($t).addClass('error');return false;}else{$j('.submitFormBtn').removeAttr('disabled');jQuery('form .error_alert').remove();$j($t).removeClass('error').addClass('success');}},complete:function(){},error:function(err,req){}});}function checkCurrentPassword($t){$j=jQuery;$this=$j($t);$type=$this.attr('data-type');jQuery.ajax({type:'post',datatype:'json',url:$cfg.baseUrl+'/sajax',data:{action:'checkCurrentPassword',val:$this.val()},beforeSend:function(){},success:function(data){$d=JSON.parse(data);if($d.state>0){removeErrorField($t);$j('.inputFormDisabled').removeAttr('disabled');}else{showErrorField($t);}},complete:function(){$j('.inputFormFocus').focus();},error:function(err,req){}});}function parseValue($t){var $this=jQuery($t);$target=jQuery($this.attr('data-target'));jQuery('form .error_alert').remove();if($this.val()!=$target.val()){showErrorField($t);return false;}return true;}
function removeTrItem($t,$x){var $this=jQuery($t);
var $c = $this.attr('data-count') ? parseInt($this.attr('data-count')) : $x;switch($c){case 4:$this.parent().parent().parent().parent().remove();break;case 3:$this.parent().parent().parent().remove();break;case 1:$this.parent().remove();break;default:$this.parent().parent().remove();break;}}function change_departure_place_book($t){var $this=jQuery($t);$price=($this.attr('data-price'));$id=parseInt($this.attr('data-id'));$target=jQuery($this.attr('data-target'));$target2=jQuery($this.attr('data-xtarget'));$date=$this.attr('data-date');jQuery.ajax({type:'post',datatype:'json',url:$cfg.baseUrl+'/sajax',data:{action:'change_departure_place_book',depart:$this.val(),id:$id,date:$date,price:$price},beforeSend:function(){},success:function(data){$d=JSON.parse(data);if($d.state){$target.html($d.price);$target2.attr('data-depart',$this.val())}},complete:function(){},error:function(err,req){}});return true;}function btn_book_tour($t){$rating_service=parseInt(jQuery('#rating_service').val());$token_string=jQuery('#token_string').val();$hash_string=jQuery('#hash_string').val();$this=jQuery($t);$id=parseInt($this.attr('data-id'));$depart=($this.attr('data-depart'));$date=$this.attr('data-date');$href=$cfg.baseUrl+'/book?token='+$token_string;$href+='&hash='+$hash_string;$href+='&id='+$id;$href+='&date_departure='+$date;$href+='&rating_service='+($rating_service>0?$rating_service:3);$href+='&departure='+$depart;window.location=$href;}function get_item_price_from_depart($t,$departure,$date){var $this=jQuery($t);$date=jQuery('#date_departure').length>0?jQuery('#date_departure').val():$date;$departure=jQuery('#departure_local').length>0?jQuery('#departure_local').val():$departure;$rating_service=jQuery('#rating_service').length>0?jQuery('#rating_service').val():-1;$filter_tour_type=jQuery('#filters_tour_type').length>0?jQuery('#filters_tour_type').val():-1;$filter_tour_group=jQuery('#filter_tour_group').length>0?jQuery('#filter_tour_group').val():-1;$id=parseInt($this.attr('data-id'));$target=jQuery($this.attr('data-target'));jQuery.ajax({type:'post',datatype:'json',url:$cfg.baseUrl+'/sajax',data:{action:'get_item_price_from_depart',departure:$departure,id:$id,date:$date,rating_service:$rating_service,filter_tour_type:$filter_tour_type,filter_tour_group:$filter_tour_group},beforeSend:function(){},success:function(data){$d=JSON.parse(data);if($d.state){if(parseInt($d.xprice)<=0){$target.html('<button type="submit" class="btn btn-link f19px"><i class="glyphicon glyphicon-hand-right "></i> '+$cfg.text[2]+'</button>');jQuery('.t_price_change_currency,.t_price_change_prec').hide();jQuery('.submit-booktour').html($cfg.text[198]);}else{$target.html($d.price);jQuery('.t_price_change_currency,.t_price_change_prec').show();jQuery('.submit-booktour').html($cfg.text[35]);}if(parseInt($filter_tour_type)==3){jQuery('#departure_scheduler_from_item_price').slideUp();jQuery('.date_available_select').hide();jQuery('.group_filter_tour_group_depart_select').show();jQuery('.group_filter_tour_group_depart_select_none').hide();}else{jQuery('.date_available_select').show();jQuery('.group_filter_tour_group_depart_select').hide();$idd=jQuery('.group_filter_tour_group_depart_select_none').attr('data-id');jQuery('#departure_local').val($idd).change();jQuery('.group_filter_tour_group_depart_select_none').show();jQuery('#departure_scheduler_from_item_price').slideDown();}}else{$target.html('');}},complete:function(){},error:function(err,req){}});return true;}
function validate_seo_preview($t){
	var $this=jQuery($t);
	var $min=parseInt($this.attr('data-min'));
	var $max=parseInt($this.attr('data-max'));
	var $role=($this.attr('data-role'));
	var $target=jQuery($this.attr('data-target')).find('.progress-bar');
	var $prev=jQuery('.seo_preview').find('.preview-'+$role);
	var $val=$this.val();
	var $len=$val.length;
	var $du=0; var $cl;
	//console.log($len)
	if($len<$min){$cl='progress-bar-warning';$c1='';}else{if($len<$max+1){$cl='progress-bar-success';$c1='';}else{$cl='progress-bar-danger';$c1='danger';$du=$len-$max;}}$w=$len/$max*80;$w=$w>100?100:$w;if($role=='url'){jQuery.ajax({type:'post',datatype:'json',url:$cfg.cBaseUrl+'/ajax',data:{action:'get_item_link',url:$val,},beforeSend:function(){},success:function(data){$prev.html(data);},complete:function(){},error:function(err,req){}});}else{$prev.html($val);$target.html($len+' ký tự '+($du>0?'<i>('+($du*-1)+')</i>':'')).css({"width":$w+"%"}).removeClass('progress-bar-warning progress-bar-success progress-bar-danger').addClass($cl);}}function show_datepicker_by_item($t){var $this=jQuery($t);$id=$this.attr('data-id');$target=jQuery('#respon_date_ch_'+$id);$mindate=$this.attr('data-date');jQuery.ajax({type:'post',datatype:'json',url:$cfg.baseUrl+'/sajax',data:{action:'show_datepicker_by_item',id:$id,},beforeSend:function(){},success:function(data){$d=JSON.parse(data);$target.datepicker({dateFormat:'dd/mm/yy',beforeShowDay:function(date){dmy=date.getDate()+"/"+(date.getMonth()+1)+'/'+date.getFullYear();return[($.inArray(dmy,$d.availableDates)!=-1),""];},minDate:$mindate,maxDate:'+1y',regional:"vi",onSelect:function(){get_price_from_task_scheduler(this);}}).focus();},complete:function(){},error:function(err,req){}});}function get_price_from_task_scheduler($t){var $this=jQuery($t);$id=$this.attr('data-id');$date=$this.val();$target_price=$this.parent().parent().find('.tour_pricex_rp');$target_book=$this.parent().parent().find('.btn-book-tour-rp');jQuery.ajax({type:'post',datatype:'json',url:$cfg.baseUrl+'/sajax',data:{action:'get_price_from_task_scheduler',id:$id,date:$date},beforeSend:function(){},success:function(data){$d=JSON.parse(data);$target_price.html($d.price)
$target_book.attr('data-date',$d.date)},complete:function(){},error:function(err,req){}});}function getText($id){alert($cfg.text[210])
$text='a';jQuery.ajax({type:'post',datatype:'json',url:$cfg.baseUrl+'/sajax',data:{action:'get_text',id:$id},beforeSend:function(){},success:function(data){},complete:function(){},error:function(err,req){}});return $text;}function set_checked_bool($t){if(jQuery($t).is(':checked')){jQuery($t).val(1);}else{jQuery($t).val(0);}}function view_obj(obj){var propValue;for(var propName in obj){propValue=obj[propName]
console.log(propName,propValue);}}
function add_to_cart($t){
	var $this=jQuery($t);
	var $id=$this.attr('data-id')?$this.attr('data-id'):false;
	var $role=$this.attr('data-role')?$this.attr('data-role'):'push';
	var $amount=$this.attr('data-amount')?$this.attr('data-amount'):(jQuery('.cart-item-quantity-'+$id).length>0?jQuery('.cart-item-quantity-'+$id):$this.parent().find('.cart-item-quantity').val());$amount=parseInt($amount)>0?parseInt($amount):1;if($id!==false){update_cart('add',$id,$amount,$role);}show_popup_to_cart();
	return false;
	}
function show_popup_to_cart(){jQuery('.modal').remove();
var $html='<div class="modal cart_alert fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"><div class="modal-dialog modal-sm" role="document"><div class="modal-content">';
$html+='<div class="alert f12e alert-default alert-dismissible fade in success" role="alert"><i class="glyphicon glyphicon-ok success"></i> Thêm vào giỏ hàng thành công.</div>';
$html+='</div></div></div>';
jQuery('body').append($html);
var $modal=jQuery('.modal').modal('show');
window.setTimeout(function(){jQuery('.modal').modal('hide');},1500);}
function cart_update_item_quantity($t){
	var $this=jQuery($t);
	var $id=$this.attr('data-id')?$this.attr('data-id'):false;
	var $role=$this.attr('data-role')?$this.attr('data-role'):'add';
	var $item=jQuery('.item-quantity-'+$id);
	var $val=parseInt($item.val());
	if($role=='sub'){$val--;}else{$val++;}$val=$val>1?$val:1;$item.val($val);update_cart('update',$id,$val,'update');}
function cart_remove_item($t){
	var $this=jQuery($t);
	var $id=$this.attr('data-id')?$this.attr('data-id'):false;
	update_cart('delete',$id,0,'delete');}
function cart_reload($d){
	
	jQuery('.cart-total-price').html($d.cart['totalPrice']);
	jQuery('.cart-total-item').html($d.cart['totalItem']);
	//jQuery('.cart-total-item-'+$id).html($d.cart['changeSubTotal']);
	jQuery('.cart-total-price-text').html($d.cart['totalPriceText']);}
function update_cart($behavior,$id,$amount,$role){
	jQuery.ajax({type:'post',datatype:'json',url:$cfg.absoluteUrl+'/sajax',data:{role:$role,id:$id,amount:$amount,behavior:$behavior,action:'update_cart'},beforeSend:function(){console.log($cfg.absoluteUrl+'/sajax');showFullLoading();},success:function(data){console.log(data);var $d=JSON.parse(data);cart_reload($d);},error:function(err,req){},complete:function(){switch($behavior){case'add':break;case'delete':jQuery('.cart-item-id-'+$id).remove();break;default:break;}switch($role){case'buynow':case'buy':window.location='/cart';break;}hideFullLoading();}});}function cart_show_company($t){var $this=jQuery($t);$ck=$this.is(':checked');$c=jQuery('.cart-company-information');if($ck){$c.show();$c.find('.srequired').addClass('required');$c.find('.focus').focus();}else{$c.hide();$c.find('.required').removeClass('required');}}function cart_show_receiver_information($t){var $this=jQuery($t);$ck=$this.is(':checked');$c=jQuery('.cart-receiver-information');if(!$ck){$c.show();$c.find('.srequired').addClass('required');$c.find('.focus').focus();}else{$c.hide();$c.find('.required').removeClass('required');}}function show_paging($t){var $this=jQuery($t);if(!$this.hasClass('active')){$loading=jQuery('<div class="ajax-loading-paging-data"></div>');$option=$this.attr('data-option')?$this.attr('data-option'):'';$p=$this.attr('data-page')?$this.attr('data-page'):($this.attr('data-p')?$this.attr('data-p'):1);$limit=$this.attr('data-limit')?$this.attr('data-limit'):10;$role=$this.attr('data-role')?$this.attr('data-role'):'';$category=$this.attr('data-category')?$this.attr('data-category'):-1;$box_id=$this.attr('data-box_id')?$this.attr('data-box_id'):0;$p=parseInt($p)>1?parseInt($p):1;$this.parent().find('.active').removeClass('active');$this.parent().find('.page-'+$p).addClass('active');$target=$this.parent().parent().find('.ajax-data-result');switch($role){case'slick-goto':$parent=jQuery($this.attr('data-parent'));$dots=$parent.find('.btn-custom-paging.index-'+$p);if($dots.length>0){$dots.click();}else{}break;case'normal':break;default:if($target.find('.item-page-'+$p).length>0){$target.find('.item').slideUp();$target.find('.item-page-'+$p).slideDown()}else{jQuery.ajax({type:'post',datatype:'jsonp',url:$cfg.baseUrl+'/ajax',data:{action:'get_paging',option:$option,p:$p,limit:$limit,box_id:$box_id},beforeSend:function(){if($target.find('.ajax-loading-paging-data').length>0){}else{$loading.appendTo($target)}},success:function(data){$d=JSON.parse(data);$target.find('.item').hide();$target.append($d.html)},error:function(err,req){$loading.remove();},complete:function(){$loading.remove();}});}break;}}}function goto_google_search($t){var $this=jQuery($t);var $key=$this.find('.search-keyword');if($key.val()==""){$key.focus();return false;}key=$key.val().split(" ");var q=key[0];for(i=1;i<key.length;i++){q=q+"+"+key[i];}var href='https://www.google.com.vn/#hl=vi&sclient=psy-ab&q=site:'+$cfg.domain+' '+q+'&oq='+q;window.open(href,'_blank');return false;}function change_device($t){var $this=jQuery($t);jQuery.post($cfg.baseUrl+'/sajax/change_device',function(r){window.location=window.location.href},'json');}
function ajaxSubmitForm(t){
	var $this=jQuery(t); var $href; 
	if($this.attr('data-action')=='current'){
		var $href=$this.attr('action')!=""?$this.attr('action'):window.location.href;}else{
		var	$href=$cfg.cBaseUrl+'/ajax';}
	switch($this.attr('data-action')){ case'current':$href=$this.attr('action')!=""?$this.attr('action'):window.location.href;break;case'sajax':$href=$cfg.baseUrl+'/sajax';break;default:$href=$cfg.cBaseUrl+'/ajax';break;}
	$submit=true;
	//alert($href)
	jQuery('.er').remove();
	$ckc=true;jQuery('.error.check_error').each(function(i,e){$submit=false;jQuery(e).focus();
	$er=jQuery(e).parent().find('.error_field');if($er.length==0){$er=jQuery('<div class="error_field"></div>');
	jQuery(e).parent().append($er);}$erText=jQuery(e).attr('data-alert')?jQuery(e).attr('data-alert'):'';
	$erText=$erText.replace(/{VAL}/g,jQuery(e).val());$er.html($erText);return false;});
	if($submit){$this.find('input.required,textarea.required,email.required').each(function(i,e){$e=jQuery(e);
	if($e.val().trim()==""){$e.focus();
	if($e.attr('data-select')=='select2'){$e.parent().find('.select2-selection').addClass('error');}$ckc=false;return false;}});
	//alert($ckc);
	if(!$ckc)return false;if($this.find('.cke_editor_ckeditor_content').length>0)CKupdate();
	
	jQuery.ajax({type:'post',datatype:'json',url:$href,data:$this.serialize(),beforeSend:function(){showFullLoading();},success:function(data){ 
		hideFullLoading();
		if(data!=""){ var $d=JSON.parse(data);if($d.error==true){showModal('Thông báo',$d.error_content)}else{if($d.modal==true){showModal('Thông báo',$d.modal_content)
$timeout=$d.delay!=undefined?$d.delay:0;if($timeout>0){window.setTimeout(function(){$modal=jQuery('.mymodal');$modal.modal('hide');},$timeout);}}}
		if($d.redirect==true){window.location=$d.target;}if($d.callback){eval($d.callback_function);}if($d.event!=undefined){switch($d.event){case'preview_order':jQuery('.preview_order').html($d.text);break;case'quick-add-more-nationality-group-to-tickets':$target=jQuery('.ajax-load-group-nationality');$modal=jQuery('.mymodal1');$modal.modal('hide');$target.append($d.html);jQuery('.btn-list-add-more-1').find('.btn-add-more').attr('data-existed',$d.existed);reload_app('number-format');break;case'quick_update_seo':jQuery('.btn-submit').attr('disabled','disabled').html('<i class="glyphicon glyphicon-ok"></i> Thành công');$this.find('input,textarea').attr('disabled','disabled');break;case'edit_user_success':jQuery('.btn-submit').attr('disabled','disabled').html('<i class="glyphicon glyphicon-ok"></i> Thành công');$this.find('input').attr('disabled','disabled');break;case'submit-controller-form':show_left_small_loading('show');show_left_small_loading('hide');break;case'hide-modal':$modal=jQuery('.mymodal');$modal.modal('hide');break;case'relogin':jQuery('.btn-submit').attr('disabled','disabled').html('<i class="glyphicon glyphicon-ok"></i> Thành công');$this.find('input').attr('disabled','disabled');window.location=$d.target;break;case'forgot':if(!$d.state){$r='<p class="text-danger bg-danger pd15"><i class="glyphicon glyphicon-remove"></i> Rất tiếc! Hệ thống không tìm thấy thông tin tài khoản của bạn, vui lòng kiểm tra lại.</p>';jQuery('.error_respon').html($r);}else{$r='<p class="text-success bg-success pd15"><i class="glyphicon glyphicon-ok"></i> Thông tin khôi phục đã được gửi tới email <b>'+$d.email+'</b>.<br/>Vui lòng kiểm tra email và làm theo hướng dẫn.</p>';jQuery('.error_respon').html($r);jQuery('.remove-after-submit').remove();}break;case'quick-add-more-season-to-supplier':$target=jQuery($d.target);$target.append($d.html);$modal=jQuery('.mymodal');$modal.modal('hide');jQuery('.btn-list-add-more-1').find('.btn-add-more').attr('data-existed',$d.existed)
reload_app('switch-btn');break;case'quick-add-more-room-to-hotel':$target=jQuery('.ajax-result-quick-add-more-before');$target.before($d.html);$modal=jQuery('.mymodal');$modal.modal('hide');jQuery('.btn-list-add-more-1').find('.btn-add-more').attr('data-existed',$d.existed)
reload_app('switch-btn');break;case'quick-add-more-hight-way':$target=jQuery('.ajax-result-more-hight-way');$target.append($d.html);$modal=jQuery('.mymodal');$modal.modal('hide');jQuery('.btn-list-add-more-1').find('.btn-add-more').attr('data-existed',$d.existed)
reload_app('switch-btn');break;case'add_new_cost_distance':jQuery.each($d.r,function($i,$e){jQuery('.'+$d.target_class+$i).append($e);});jQuery('.btn-list-add-more-1').find('.btn-add-more').attr('data-existed',$d.existed).attr('data-count',$d.index);jQuery('.mymodal').modal('hide');reload_app('chosen');reload_app('select2');reload_app('number-format');reload_app('switch-btn');break;case'set_quantity_vehicles_categorys':case'set_quantity_currency':jQuery('.'+$d.target_class).before($d.html);jQuery('.'+$d.target_class).find('.btn-add-more').attr('data-existed',$d.existed_id).attr('data-count',$d.index);jQuery('.mymodal').modal('hide');break;case'quick_add_more_vehicle_category':jQuery('.mymodal1').modal('hide');jQuery('.mymodal').modal('show');get_list_vehicles_makers('#select-chon-xe');break;case'_tour_program_add_service':$pr=jQuery($d.parent);$target=$pr.find($d.target);$target.before($d.html);$c=$target.find('.btn-option .btn-count-array');$cx=parseInt($c.attr('data-count'))>0?parseInt($c.attr('data-count')):0;$c.attr('data-count',$cx+1);tour_program_calculation_price();jQuery('.mymodal').modal('hide');reload_app('number-format');reload_app('chosen');break;case'_tour_program_edit_service':$pr=jQuery($d.parent);$target=$pr.find($d.target);$target.replaceWith($d.html);tour_program_calculation_price();jQuery('.mymodal').modal('hide');reload_app('number-format');reload_app('chosen');break;case'quick_edit_field':jQuery($d.target).html($d.title);jQuery('.mymodal').modal('hide');break;case'redirect_link':$timeout=$d.delay!=undefined?$d.delay:0;window.setTimeout(function(){window.location=$d.target;},$timeout);break;case'clearInput':jQuery($d.target).val('');break;case'reload':$timeout=$d.delay!=undefined?$d.delay:0;window.setTimeout(function(){window.location=window.location;},$timeout);break;case'add_loai_thu_chi':jQuery('.mymodal1').modal('hide');jQuery($d.target).html($d.select).trigger("chosen:updated").change();break;case'chon_khach_san':$action=$d.action;switch($action){case'add':$tbody=jQuery('.select_hotel_option_'+$d.option).find('.private-row-hotel-'+$d.pindex);$v=parseInt(jQuery('#numberOfHotel').val())+1;jQuery('#numberOfHotel').val($v);$target=jQuery($d.target);$target.attr('data-index',$d.index);$tbody.before($d.price);break;default:$input_name=jQuery('.input-hotel-name-'+$d.pindex+'-'+$d.index);$input_star=jQuery('.input-hotel-star-'+$d.pindex+'-'+$d.index);$input_name.val($d.hotel['name']);$input_star.val($d.hotel['star']);$tbody=jQuery('.hotel-detail-body-index-'+$d.option+'-'+$d.pindex+'-'+$d.index);$tbody.html($d.price);break;}jQuery('.mymodal').modal('hide');reload_app('number_format');changeHotelCost(jQuery('.sl-hotel-cost-amount'));reloadCost();break;case'chon_xe':$action=$d.action;switch($action){case'add':$tbody=jQuery('.public-row-car-0');$v=parseInt(jQuery('#numberOfCar').val())+1;jQuery('#numberOfCar').val($v);$tbody.before($d.price);jQuery('.btn-add-more-transport').attr('data-index',$d.index);break;default:$input_name=jQuery('.input-car-name-'+$d.index);$input_name.val($d.item['name']);$tbody=jQuery('.car-detail-body-index-'+$d.index);$tbody.html($d.price);break;}jQuery('.mymodal').modal('hide');reload_app('number-format');reloadCost();break;case'checkInError':$e=jQuery('.cError');switch($d.error_code){case'SUCCESS':$e.html('<p>Điểm danh thành công.</p>');break;case'CHECKED':$e.html('<p>Bạn đã điểm danh rồi.</p>');break;case'USER_NOT_EXIST':$e.html('<p>Không tìm thấy tài khoản.</p>');break;case'NOT_FOUND':$e.html('<p>Không tìm thấy lớp học.</p>');break;}break;case'them_danh_muc_chiphi':jQuery('#addCostCateID').append('<option ="'+$d.data['id']+'" selected>'+$d.data['name']+'</option>').trigger("chosen:updated");;jQuery('.mymodal1').modal('hide');break;}}}},error:function(err,req){hideFullLoading();}});}return false;}
function openPrint(){window.print();window.onfocus=function(){ window.close();}}
function changeLayoutProductView($t){
	var $this = jQuery($t);
	$layout = $this.attr('data-layout');
	$target = $this.attr('data-target');
	$tx = jQuery('.'+$target);
	switch($layout){
	case 'list': 
		$tx.css({"height":''});
		$tx.find('.item-name-fix-height').css({"height":''});
		break;
	case 'grid': 
		//$tx.addClass($target) ;
		//setAutoHeightElement();
		break;
	}
}
function setAutoHeightElement(){
	$c = {};
	jQuery('.auto-height-element').each(function(i,e){
		var $e = jQuery(e);
		var $g = $e.attr('data-group');
		$h = $e.height();
		if($c[$g] == undefined){
			$c[$g] = $h;
		}else{
			if($h > $c[$g]){
				$c[$g] = $h;
			}
		}
		
	});
	jQuery.each($c,function(i,e){
		//console.log(i + '/' + e)
		jQuery('.auto-height-element-'+i).height(e)
	}); 
}
function parseJsonData(msg){
	//var IS_JSON = true;
    try
    {
    	//console.log(jQuery.parseJSON(msg).name);
        return jQuery.parseJSON(msg);
             
    }
    catch(err)
    {
        //  IS_JSON = false;
        return false;
    }         
}
function getAttributes ( $node ) {
    var attrs = {};
    if(!$node.jquery){
    	$node = jQuery($node);
    }
    //if($node[0].attributes){
    jQuery.each( $node[0].attributes, function ( index, attribute ) {
    	var res =  attribute.name.substr(0, 5);
    	if(res== 'data-'){
    		var $name = attribute.name.replace(/data-/g,'');
        	attrs[$name] = attribute.value;
    	}
    } );
    //}
    return attrs;
}
function loadChildsProvinces($t){
	var $this = jQuery($t);
	var $val = $this.val();
	var $data = getAttributes($this);
	var $level = parseInt($this.attr('data-level'));
	$data['action'] = 'loadChildsProvinces';
	$data['parent_id'] = $val;
	jQuery.ajax({
		type:'post',
		datatype:'json',
		url:$cfg.baseUrl+'/sajax',
		data:$data,
		beforeSend:function(){
			hideFullLoading();
		},
		success:function(data){
			var $d = parseJsonData(data);
			var $target_input = jQuery($d.target);
			var $selected_value = parseInt($target_input.attr('data-selected'));
			//console.log($selected_value);
			$target_input
			.html($d.html)
			.attr('data-parent_id',$val);
			if($selected_value>0){
				$target_input.val($selected_value);
			}
			 
			jQuery($d.target_input).val($d.local_id);
			if($level != 2){
				///alert($d.selected)
				$target_input.trigger("chosen:updated").change();
			}else{
				$target_input.trigger("chosen:updated");
			}
			load_chosen_select();
		},
		error:function(err,req){}
	});
	
}
function changeDayOfYear($t){
	var $this = jQuery($t);
	var $pr = $this.parent().parent().parent().parent();
	var $role = parseInt($this.attr('data-role'));
	//console.log($role);
	switch ($role) {
	case 0: // nam
		jQuery($this.attr('data-target')).attr('data-year',$this.val()).trigger("chosen:updated").change();
		break;
	case 2:
		jQuery($this.attr('data-target')).val($this.attr('data-year')+'-'+$this.attr('data-month')+'-'+$this.val());
		break;
	default: // thang
		var $year = parseInt($this.attr('data-year'));
		var $month = parseInt($this.val());
		var $day = 31;
		switch ($month){
		case 1:case 3:case 5:case 7:case 8:case 10:case 12:
			$day = 31;
			break;
		case 4:case 6:case 9:case 11:
			$day = 30;
			break;
		case 2:
			if($year % 400 == 0 ){
				$day = 29;
			}else{
				if($year % 4 == 0){
					$day = 29;
				}else{
					$day = 28;
				}
			}
			break;
		}
		var $target = jQuery($this.attr('data-target'));
		$target.find('option').removeAttr('disabled');
		var $vt = parseInt($target.val());
		if($vt>$day){
			$target.val($day)
		}
		for($i = $day+1; $i<32;$i++){
			$target.find('option[value="'+$i+'"]').attr('disabled','');
		}
		$target.attr({'data-year':$this.attr('data-year'),'data-month':$this.val()}).trigger("chosen:updated").change();
		break;
	}
}
function resizeItem4x3(){
	jQuery('.auto_rz_4x3').each(function(i,e){
		var $this=jQuery(e);
		var $w=$this.width();
		var $h=$w*3/4;
		$this.addClass('autow'+$w).height($h);
		$this.find('img').addClass('mw100p');
	});
}
function setCookieExpried($t){
	var $this=jQuery($t);
	var $time = parseInt($this.attr('data-time'));
	var $value = ($this.attr('data-value'));
	var $name = ($this.attr('data-name'));
	var date = new Date();
	//var minutes = 0.1;
	date.setTime(date.getTime() + ($time * 60 * 1000));
	Cookies.set($name, $value, { expires: date });
	//console.log(Cookies.get('name') + date);
	
}