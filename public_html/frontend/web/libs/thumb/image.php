<?php
@ini_set('display_startup_errors',0);@ini_set('display_errors',0);$img_path = dirname(__FILE__);
echo getImage($_GET); 
 
function remove_accent($str) { 
  $a = array('<br>','<br/>', "à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă","ằ","ắ","ặ","ẳ","ẵ","è","é","ẹ","ẻ","ẽ","ê","ề","ế","ệ","ể","ễ","ì","í","ị","ỉ","ĩ","ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ","ờ","ớ","ợ","ở","ỡ","ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ","ỳ","ý","ỵ","ỷ","ỹ","đ","À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă","Ằ","Ắ","Ặ","Ẳ","Ẵ","È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ","Ì","Í","Ị","Ỉ","Ĩ","Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ","Ờ","Ớ","Ợ","Ở","Ỡ","Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ","Ỳ","Ý","Ỵ","Ỷ","Ỹ","Đ",'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ','&quot;');
  $b = array('-','-',"a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a",	"e","e","e","e","e","e","e","e","e","e","e",	"i","i","i","i","i",	"o","o","o","o","o","o","o","o","o","o","o","o"	,"o","o","o","o","o","u","u","u","u","u","u","u","u","u","u","u","y","y","y","y","y","d","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","E","E","E","E","E","E","E","E","E","E","E","I","I","I","I","I","O","O","O","O","O","O","O","O","O","O","O","O"		,"O","O","O","O","O",		"U","U","U","U","U","U","U","U","U","U","U","Y","Y","Y","Y","Y","D",'A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o','');
  return str_replace($a, $b, $str); 
}
function unMark($str,$r='-'){ 
  return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), 
  array('', $r, ''), remove_accent($str)));
}

function getImage($o = [],$absolute = false){
	define('__ROOT_PATH__',dirname(__FILE__));
	$w = isset($o['w']) && $o['w'] > 0 ? $o['w'] : (isset($o['width']) && $o['width'] > 0 ? $o['width'] : 0);
	$h = isset($o['h']) && $o['h'] > 0 ? $o['h'] : (isset($o['height']) && $o['height'] > 0 ? $o['height'] : 0);
	$maxWidth = isset($o['max-width']) && $o['max-width'] > 0 ? $o['max-width'] : 0;
	$maxHeight = isset($o['max-height']) && $o['max-height'] > 0 ? $o['max-height'] : 0;
	$rename = isset($o['rename']) && $o['rename'] == false ? false : true;
	$w = $w > $maxWidth && $maxWidth > 0 ? $maxWidth : $w;
	$h = $h > $maxHeight && $maxHeight > 0 ? $maxHeight : $h;
	$src = isset($o['src'])  ?   $o['src'] : false;
	$src = str_replace(" ",'%20',$src);
	if($src === false || $src == "") return false;
	//
	$origin_src = $src;
	//
	$op = isset($o['img_attr']) ?  $o['img_attr'] : (isset($o['attr']) ?  $o['attr'] : false);
	$output = isset($o['output']) ? $o['output'] : 'img';
	$save = isset($o['save']) && $o['save'] ? true : false;
	$alt = isset($o['alt']) ? $o['alt'] : '';
	$s = explode(';',$src);
	$href = $src;
	$hxx= ($src);
	if(count($s)>0){
		$src = $s[0];
	}
	 
	if($w==0 && $h==0 ){
		$href = $src;
	}else{
		$file = parse_file_name($src);
		$fs = parse_url($origin_src);
		$hash_file = $rename ? md5($src) : unMark(str_replace('%20', '-', $file['name']));
		$ext = $file['type'];
		if($ext == 'jpeg') $ext = 'jpg';
		//if(0>1 && isset(Yii::$site['other_setting']['thumb_url']) && strlen(Yii::$site['other_setting']['thumb_url']) > 4){
		//	$host_url = Yii::$site['other_setting']['thumb_url'];
		//}else{
			$host_url = (isset($fs['scheme']) ? $fs['scheme'] .'://' : '') . $fs['host'];
		//}
		$fp =   '/medias/thumbs/'.$w.'x'.$h.DIRECTORY_SEPARATOR.$hash_file.'.'.$ext;
		//view(__ROOT_PATH__.$fp);
		if(@file_exists(__ROOT_PATH__.$fp)){
			//}
			//if(check_file_existed($fp)){
			$href = removeLastSlashes($host_url).$fp;
		}else{
			//if($save){
				if($w + $h > 0) {
					$pos = strrpos($src,'/',1);
					$file_src = strtolower(substr($src,$pos+1));
					$pos = strrpos($file_src,'.',1);
					$file_type = strtolower(substr($file_src,$pos+1));
					if($file_type == 'jpeg') $file_type = 'jpg';
					$file_name = substr($file_src, 0,$pos);
					$hash_file = $rename ? md5($src) : unMark(str_replace("%20",'-',$file_name));   
					$file_save_path = __ROOT_PATH__.'/medias/thumbs/'.$w.'x'.$h.'/'.$hash_file.'.'.$file_type;
					 
				    if(in_array($file_type,array('jpg','png','jpeg')) && !file_exists($file_save_path)){               
					   if(!file_exists(__ROOT_PATH__.'/medias/thumbs')) @mkdir(__ROOT_PATH__.'/medias/thumbs',0755,true);
					   $thumb = PhpThumbFactory::create($src);
					   //$thumb->show();
					   @$thumb->save($file_save_path, $file_type);	   
					   if(file_exists($file_save_path)){
					   	$href = '/medias/thumbs/'.$w.'x'.$h.'/'.$hash_file.'.'.$file_type;
					   }
				    }
			//	}
			}else	$href =$src;
		}
	}
	$img = '<img src="'.$href.'" ';
	if(!empty($op)){
		foreach($op as $k=>$v){
			if($k == 'alt'){
				$alt = $v;
			}else $img .= $k .'="'.$v.'" ';
		}
	}
	$img .= ' alt="'.$alt.'" />';
	return $img;
	 
	return $href;
}
function parse_file_name($filename = ''){
	$pos = strrpos($filename,'/',1);
	$filename = substr($filename,$pos+1);
	$pos = strrpos($filename,'.',1);
	$file_type = substr($filename,$pos+1);
	$file_name = substr($filename, 0,$pos);
	$u = parse_url($filename); 
	return array(
			'type'=>$file_type,
			'name'=>$file_name,
			'full_name'=>$filename,
			//'host_url' => (isset($u['scheme']) ? $u['scheme'].'://' : '') . $u['host'],

	);
}
function removeLastSlashes($string){
	while(strlen($string)>0 && substr($string, -1) == '/'){
		$string = substr($string, 0,-1);
	}
	return $string;
}