<?php
Yii::setAlias('@themes',dirname(__DIR__) . '/web/themes');
Yii::setAlias('@views',dirname(__DIR__) . '/views');
Yii::setAlias('@libs', '/libs');
Yii::setAlias('@admin', '/themes/admin');
Yii::setAlias('@modules', dirname(__DIR__) . '/modules');
Yii::setAlias('@applications', dirname(__DIR__) . '/applications');
define('NO_IMAGE', '');
$d = array(
		//'__ROOT_PATH__'=>$root_path,
		'__SS_ID'=>isset($_SESSION['__SS_ID']) && $_SESSION['__SS_ID'] > 0 ? $_SESSION['__SS_ID'] : rand(1111,9999),
		//'DS'=>'/',
		'DRS'=>DIRECTORY_SEPARATOR,
		//"SRC_FOLDER"=>"",
		//"ROOT_FOLDER"=>"",
		//"LIBS_FOLDER"=>"libs",
		//'ASSETS_FOLDER'=>'assets',
		//"DOCUMENT_ROOT"=>$d,
		"MAIN_DOMAIN"=>'vantruong.name.vn',
		"THEMES_FOLDER"=>"themes",
		"BASE_FOLDER"=>"base",
		"SYSTEM_FOLDER"=>"system",
		"APPLICATIONS_FOLDER"=>"applications",
		"CONTROLLERS_FOLDER"=>"controllers",
		"MODELS_FOLDER"=>"models",
		"TEMPLETES_FOLDER"=>"templetes",
		"DEFAULT_SITE"=>"index.php",
		"FILE_EXT"=>".php",
		"FREE_SITE"=>"free01",
		"URL_SUFFIX"=>".html",
		'VIEWS_FOLDER'=>'views',
		
		'JSON_PRETTY_PRINT' => 128,
		'JSON_UNESCAPED_SLASHES' => 64,
		'JSON_UNESCAPED_UNICODE' => 256,
		// Các kiểu khách
		'TYPE_ID_CUS'=>0, // Khách hàng
		'TYPE_ID_PART'=>1, // Đối tác
		'TYPE_ID_TEA'=>2, // Giáo viên
		'TYPE_ID_AST'=>3, // Trợ giảng
		'TYPE_ID_HOTEL'=>4, // Khách sạn
		'TYPE_ID_REST'=>5, // Nhà hàng
		'TYPE_ID_VECL'=>6, // Xe cộ
		'TYPE_ID_TRAIN'=>7, // Tàu hỏa
		'TYPE_ID_SHIP'=>8, // Tàu thuyền
		'TYPE_ID_SCEN'=>9, // Thắng cảnh
		'TYPE_ID_AIR'=>10, // Vé máy bay
		'TYPE_ID_VENDOR'=>11, // Nhà cung cấp , phân phối
		'TYPE_ID_SHIP_HOTEL'=>12, // Tàu ngủ
		'TYPE_ID_GUIDES'=>13, // HDV
		'TYPE_ID_COACHES'=>14, // Huấn luyện viên
		'TYPE_ID_MEMBERS'=>15, // Thành viên
		'TYPE_ID_STUDENTS'=>15, // Học viên
		'TYPE_ID_ORDER'=>80, //Order Status
		'TYPE_ID_PAYMETHOD'=>81, //Pay method
		
		// Các loại hình doanh nghiệp / cá nhân
		'TYPE_CODE_PER'=>27, // Cá nhân
		'TYPE_CODE_SOE'=>21, // Dn nhà nước
		'TYPE_CODE_POE'=>20, // Dn tư nhân
		'TYPE_CODE_COOP'=>26, // Hợp tác xã
		'TYPE_CODE_JSC'=>22, // CT cổ phần
		'TYPE_CODE_LLC'=>23, // CT TNHH
		'TYPE_CODE_PART'=>24, // CT hợp danh 
		'TYPE_CODE_JVT'=>25, // CT liên doanh
		///////////////////////////////////
		'TYPE_CODE_VEHICLE'=>100,
		'TYPE_CODE_HIGHT_WAY'=>200,
		'TYPE_CODE_ROOM_HOTEL'=>150,
		'TYPE_CODE_ROOM_TRAIN'=>152,
		'TYPE_CODE_ROOM_SHIP'=>151,
		'TYPE_CODE_DISTANCE'=>153,
		//
		'FORM_TYPE_COURSES' => 'courses' ,
		'FORM_TYPE_TESTIMONIALS' => 'testimonials',
		'SEASON_TYPE_NORMAL' => 1, // Ngày nghỉ thường
		'SEASON_TYPE_SERVICE' => 2, // Mùa dịch vụ
		'SEASON_TYPE_WEEKEND' => 3, // Cuối tuần
		'SEASON_TYPE_WEEKDAY' => 4, // Ngày thường
		'SEASON_TYPE_TIME' => 5, // Buổi trong ngày (sáng, trưa, chiều, tối)
);
foreach($d as $k=>$v){
	defined($k) or define($k,$v);
} 