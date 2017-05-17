<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "templete_category".
 *
 * @property integer $id
 * @property string $title
 * @property integer $state
 * @property integer $is_active
 */
class FormActive extends \yii\db\ActiveRecord
{
	private static $booleanFields = [],$getDateTimeFields = [];
	private static $numericFields = [
				'position',
				'price',
				'price1',
				'price2',
				'price3',
				'rgt',
				'lft',
				//'is_active',
	
		];
	 
	public static $jsonFields = [];
	
	public static function setBooleanFields($fields){
		self::$booleanFields = $fields;
	} 
	public static function setNumericFields($fields){
		self::$numericFields = $fields;
	}
	public static function setDateTimeFields($fields){
		self::$getDateTimeFields = $fields;
	}
	
	public static function getFormSubmit(){
		if(Yii::$app->request->post('formSubmit') == 'true'){
			$f = Yii::$app->request->post('f',[]);
			$biz = Yii::$app->request->post('biz',[]);
			
			if(isset($biz['attr']) && !empty($biz['attr'])){
				foreach($biz['attr'] as $at=>$a){
					if($a == "") unset($biz['attr'][$at]);
				}
			}
			
			$id = Yii::$app->request->get('id',0);
			// 
			$image = isset($_FILES['image']) && $_FILES['image']['size'] > 0 ? $_FILES['image'] : (isset($_FILES['icon']) ? $_FILES['icon'] : '');
			//view($image,true);
			$check = @getimagesize($image["tmp_name"]);
			if($check !== false) {
				$image = Yii::file()->upload_files($image,['rename'=>false]);
				//
				if(isset($f['favicon']['image'])){
					$f['favicon']['image'] = $image;
				}
				//
				if(!$image){
					$image = post('old_icon');
				}
			}else{
				$image = post('old_icon');
			}
			// 
			$thumbnail= isset($_FILES['thumbnail']) && $_FILES['thumbnail']['size'] > 0 ? $_FILES['thumbnail'] : '';
			//view($image,true);
			$check = @getimagesize($thumbnail["tmp_name"]);
			if($check !== false) {
				$thumbnail= Yii::file()->upload_files($thumbnail,['rename'=>false]);
				 
				if(!$thumbnail){
					$thumbnail= post('old_thumbnail');
				}
			}else{
				$thumbnail= post('old_thumbnail');
			}
			
			
			if(isset($f['icon'])) $f['icon'] = $image;
			if(isset($f['image'])) $f['image'] = $image;
			if(isset($thumbnail) && isset($f['thumbnail'])) $f['thumbnail'] = $thumbnail;
			if(isset($thumbnail)) $biz['thumbnail'] = $thumbnail;
			
			//
			switch (Yii::$app->controller->id){
				case 'box':
					$body_image = isset($_FILES['css_image']) && $_FILES['css_image']['size'] > 0 ? $_FILES['css_image'] : '';
					$check = @getimagesize($body_image["tmp_name"]);
					if($check !== false) {
						$body_image = Yii::file()->upload_files($body_image,['rename'=>false]);
						$biz['css']['image'] = $body_image;
							
					}
					break;
			}
			switch (CONTROLLER_CODE){
				case 'background':
					 
					$body_image = isset($_FILES['body_image']) && $_FILES['body_image']['size'] > 0 ? $_FILES['body_image'] : '';
					$check = @getimagesize($body_image["tmp_name"]);
					if($check !== false) {
						$body_image = Yii::file()->upload_files($body_image,['rename'=>false]);						 
						$f['body']['image'] = $body_image;
						 
					}
					///////////
					$body_image = isset($_FILES['header_out_image']) && $_FILES['header_out_image']['size'] > 0 ? $_FILES['header_out_image'] : '';
					$check = @getimagesize($body_image["tmp_name"]);
					if($check !== false) {
						$body_image = Yii::file()->upload_files($body_image,['rename'=>false]);						 
						$f['header_out']['image'] = $body_image;
						 
					}
					$body_image = isset($_FILES['header_in_image']) && $_FILES['header_in_image']['size'] > 0 ? $_FILES['header_in_image'] : '';
					$check = @getimagesize($body_image["tmp_name"]);
					if($check !== false) {
						$body_image = Yii::file()->upload_files($body_image,['rename'=>false]);						 
						$f['header_in']['image'] = $body_image;
						 
					}
					////
					$body_image = isset($_FILES['main_out_image']) && $_FILES['main_out_image']['size'] > 0 ? $_FILES['main_out_image'] : '';
					$check = @getimagesize($body_image["tmp_name"]);
					if($check !== false) {
						$body_image = Yii::file()->upload_files($body_image,['rename'=>false]);
						$f['main_out']['image'] = $body_image;
							
					}
					$body_image = isset($_FILES['main_in_image']) && $_FILES['main_in_image']['size'] > 0 ? $_FILES['main_in_image'] : '';
					$check = @getimagesize($body_image["tmp_name"]);
					if($check !== false) {
						$body_image = Yii::file()->upload_files($body_image,['rename'=>false]);
						$f['main_in']['image'] = $body_image;
							
					}
					//
					$body_image = isset($_FILES['footer_in_image']) && $_FILES['footer_in_image']['size'] > 0 ? $_FILES['footer_in_image'] : '';
					$check = @getimagesize($body_image["tmp_name"]);
					if($check !== false) {
						$body_image = Yii::file()->upload_files($body_image,['rename'=>false]);
						$f['footer_in']['image'] = $body_image;
							
					}
					$body_image = isset($_FILES['footer_out_image']) && $_FILES['footer_out_image']['size'] > 0 ? $_FILES['footer_out_image'] : '';
					$check = @getimagesize($body_image["tmp_name"]);
					if($check !== false) {
						$body_image = Yii::file()->upload_files($body_image,['rename'=>false]);
						$f['footer_out']['image'] = $body_image;
							
					}
					
					
					break;
			}
			if(isset($_POST['old_file'])){
				$file = isset($_FILES['file']) ? $_FILES['file'] : array();
				if(isset($file['error']) && $file['error'] == 0){
					$file = Yii::file()->upload_files($file,['type'=>'files','rename'=>false]);
				}else{
					$file = $_POST['old_file'];
				}
				$biz['file'] = $file;
				 
			}
			//
			if(isset($f['fullName'])){
				$name = explode(' ', trim($f['fullName']));
				$f['fname'] = $name[count($name)-1];
				unset($name[count($name)-1]);
				$f['lname'] = implode(' ', $name);
				unset($f['fullName']);
			}
			
			if(!empty($biz)){
				// $im0 = '';
				if($image == "" && isset($biz['listImages']) && !empty($biz['listImages'])){
					$main = false;
					foreach ($biz['listImages'] as $km => $im){
						if(isset($im['main']) && cbool($im['main']) == 1){
							$main = true;
							$image = $im['image']; break;
						}
						//if($km == 0){
						//	$im0 = $im['image'];
						//}
					}
					if(!$main){
						$biz['listImages'] = array_values($biz['listImages']);
						$image = $biz['listImages'][0]['image'];// = $im0;
						$biz['listImages'][0]['main'] = 1;
					}
				}
				//view($image,true);
				$biz['icon'] = $image;
				$f['bizrule'] = json_encode($biz);
			}
			 
			if(!empty(self::$jsonFields)){
				foreach (self::$jsonFields as $j){
					$f[$j] = json_encode(post($j,[]));
				}
			}
			if(!empty(self::$booleanFields)){
				foreach (self::$booleanFields as $field){
					$f[$field] = isset($f[$field]) ? cbool($f[$field]) : 0;
				}
			}
			if(!empty(self::$numericFields)){
				foreach (self::$numericFields as $field){
					if(isset($f[$field])){
						if(is_numeric(cprice($f[$field]))){
							$f[$field] = cprice($f[$field]);
						}else unset($f[$field]);
					}
				}
			}
			if(!empty(self::$getDateTimeFields)){
				foreach (self::$getDateTimeFields as $field){
					if(isset($f[$field])) $f[$field] = ctime(['string'=>$f[$field]]);
				}
			}
			
			 
			return $f;
		}
	}
    
}
