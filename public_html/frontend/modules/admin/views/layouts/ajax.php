<?php 
//use yii\helpers\Html;
//use common\widgets\Alert;
//use app\models\AdminMenu;
use yii\helpers\Url;
use frontend\assets\AdminAsset;
AdminAsset::register($this);
 
if(strtoupper($_SERVER['REQUEST_METHOD']) == 'GET'){
switch (getParam('action')){
	 
	case 'load_css':
		$r = [Yii::getAlias('@libs') . '/themes/css/base.css',
		Yii::getAlias('@libs') . '/font-awesome/css/font-awesome.min.css',
		Yii::getAlias('@libs') . '/bootstrap/datetime/bootstrap-datetimepicker.css',
		Yii::getAlias('@libs') . '/bootstrap/colorpicker/dist/css/bootstrap-colorpicker.min.css',
		Yii::getAlias('@libs') . '/bootstrap/assets/css/docs.css',
		Yii::getAlias('@libs') . '/bootstrap/tagsinput/dist/bootstrap-tagsinput.css',
		Yii::getAlias('@libs') . '/tagsinput/dist/jquery.tagsinput.min.css',
		Yii::getAlias('@libs') . '/vendors/nprogress/nprogress.css',
		Yii::getAlias('@libs') . '/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css',
		Yii::getAlias('@libs') . '/chosen/chosen.css',
		Yii::getAlias('@libs') . '/shieldui/css/light/all.min.css',
		Yii::getAlias('@libs') . '/popup/colorbox/colorbox.css',
		Yii::getAlias('@libs') . '/contextMenu/dist/jquery.contextMenu.css',
		Yii::getAlias('@libs') . '/menu/superfish-1.7.4/src/css/superfish.css',
		Yii::getAlias('@libs') . '/onoff/jquery.switchButton.css',
		Yii::getAlias('@libs') . '/select2/dist/css/select2.min.css',
		__RSDIR__ .'/build/css/custom.css',
		Yii::getAlias('@libs') . '/shieldui/css/light/all.min.css',
		Yii::getAlias('@libs') . '/jquery-ui-1.12.1/jquery-ui.min.css',
		Yii::getAlias('@libs') . '/jquery-ui-1.12.1/jquery-ui.theme.min.css',
		
		];
		$load_script = false;
		switch (getParam('controller')){
			case 'helps':
				$r[] = Yii::getAlias('@libs') . '/jstree/dist/themes/default/style.min.css';
				break;
			case 'siteconfigs':
				$r[] = Yii::getAlias('@libs') . '/code/codemirror-5.23.0/lib/codemirror.css';
				$r[] = Yii::getAlias('@libs') . '/code/codemirror-5.23.0/addon/dialog/dialog.css';
				$r[] = Yii::getAlias('@libs') . '/code/codemirror-5.23.0/addon/search/matchesonscrollbar.css';
				 
				break;
		}		 
		echo json_encode(array('script'=>$r)); exit;
		break;
	case 'load_js':
$r = [
Yii::getAlias('@admin') . '/js/functions.js',
Yii::getAlias('@libs') . '/jquery-ui-1.12.1/jquery-ui.min.js',
Yii::getAlias('@libs') . '/ckeditor/ckeditor.js',
Yii::getAlias('@libs') . '/onoff/jquery.switchButton.js',
Yii::getAlias('@libs') . '/bootstrap/assets/js/moment.min.js',
Yii::getAlias('@libs') . '/bootstrap/datetime/bootstrap-datetimepicker.js',
Yii::getAlias('@libs') . '/bootstrap/colorpicker/dist/js/bootstrap-colorpicker.min.js',
Yii::getAlias('@libs') . '/scrolls/slimscroll/jquery.slimscroll.min.js',
//'https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js',
'//twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js',
Yii::getAlias('@libs') . '/bootstrap/tagsinput/dist/bootstrap-tagsinput.min.js',

Yii::getAlias('@libs') . '/tagsinput/dist/jquery.tagsinput.min.js',
Yii::getAlias('@libs') . "/menu/superfish-1.7.4/src/js/hoverIntent.js",
Yii::getAlias('@libs') . "/menu/superfish-1.7.4/src/js/superfish.js",
Yii::getAlias('@admin') . '/js/jquery.number.min.js',
'//code.jquery.com/jquery-migrate-1.4.1.min.js',
Yii::getAlias('@libs') . '/chosen/chosen.jquery.js',
Yii::getAlias('@libs') . '/chosen/chosen.ajaxaddition.jquery.js',
Yii::getAlias('@libs') . '/shieldui/js/shieldui-all.min.js',
Yii::getAlias('@libs') . "/themes/js/base.js",
Yii::getAlias('@libs') . "/bootstrap/3.2.0/js/ie10-viewport-bug-workaround.js",
Yii::getAlias('@libs') . "/bootstrap/assets/js/bootstrap-tooltip.js",
Yii::getAlias('@libs') . '/select2/dist/js/select2.full.js',
Yii::getAlias('@libs') . "/bootstrap/assets/js/bootstrap-tooltip.js",
Yii::getAlias('@libs') . '/bootstrap/assets/js/bootstrap-confirmation.js',
Yii::getAlias('@libs') . '/jquerycookie/jquery.cookie.js',
];
 
$load_script = false;
switch (getParam('controller')){
	case 'index': case 'default':
		$r[] = Yii::getAlias('@libs') . "/vendors/gauge.js/dist/gauge.min.js";
		$r[] = Yii::getAlias('@libs') . "/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js";
		$r[] = Yii::getAlias('@libs') . "/vendors/skycons/skycons.js";
		$r[] = Yii::getAlias('@libs') . "/vendors/Flot/jquery.flot.js";
		$r[] = Yii::getAlias('@libs') . "/vendors/Flot/jquery.flot.pie.js";
		$r[] = Yii::getAlias('@libs') . "/vendors/Flot/jquery.flot.time.js";
		$r[] = Yii::getAlias('@libs') . "/vendors/Flot/jquery.flot.stack.js";
		$r[] = Yii::getAlias('@libs') . "/vendors/Flot/jquery.flot.resize.js";
		$r[] = Yii::getAlias('@admin') . '/js/flot/jquery.flot.orderBars.js';
		$r[] = Yii::getAlias('@admin') . '/js/flot/date.js';
		$r[] = Yii::getAlias('@admin') . '/js/flot/jquery.flot.spline.js';
		$r[] = Yii::getAlias('@admin') . '/js/flot/curvedLines.js';
		$r[] = Yii::getAlias('@admin') . '/js/datepicker/daterangepicker.js';
		$r[] = Yii::getAlias('@admin') . '/js/smartresize.js';
		$r[] = Yii::getAlias('@libs') . "/vendors/fastclick/lib/fastclick.js";
		$r[] = Yii::getAlias('@libs') . "/vendors/nprogress/nprogress.js";
		$r[] = Yii::getAlias('@libs') . "/vendors/Chart.js/dist/Chart.min.js";
		$r[] = Yii::getAlias('@libs') . "/vendors/jquery-sparkline/dist/jquery.sparkline.min.js";
		$r[] = Yii::getAlias('@libs') . "/vendors/raphael/raphael.min.js";		
		$r[] = Yii::getAlias('@libs') . "/vendors/morris.js/morris.min.js";		
		$r[] = Yii::getAlias('@libs') . "/clipboard.js/dist/clipboard.min.js";
		$r[] = Yii::getAlias('@libs') . "/contextMenu/dist/jquery.contextMenu.min.js";
		$r[] = Yii::getAlias('@libs') . "/vendors/gauge.js/dist/gauge.min.js";
		$r[] = Yii::getAlias('@libs') . "/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js";
		break;
	case 'helps':
		$r[] = Yii::getAlias('@libs') . '/jstree/dist/jstree.min.js'; 
		$r[] = Yii::getAlias('@admin') . '/js/jstree.js';
		break;
	case 'siteconfigs':
		$r[] = Yii::getAlias('@libs') . '/code/codemirror-5.23.0/lib/codemirror.js';
		$r[] = Yii::getAlias('@libs') . '/code/codemirror-5.23.0/mode/xml/xml.js';
		$r[] = Yii::getAlias('@libs') . '/code/codemirror-5.23.0/addon/dialog/dialog.js';
		$r[] = Yii::getAlias('@libs') . '/code/codemirror-5.23.0/addon/search/searchcursor.js';
		$r[] = Yii::getAlias('@libs') . '/code/codemirror-5.23.0/addon/search/search.js';
		$r[] = Yii::getAlias('@libs') . '/code/codemirror-5.23.0/addon/scroll/annotatescrollbar.js';
		$r[] = Yii::getAlias('@libs') . '/code/codemirror-5.23.0/addon/search/matchesonscrollbar.js';
		$r[] = Yii::getAlias('@libs') . '/code/codemirror-5.23.0/addon/search/jump-to-line.js';
		 
		break;
}
$fp = __RSPATH__ . '/js/actions/'.getParam('controller') .'.js';
 
		echo json_encode(array('script'=>$r,'load_js'=>file_exists($fp)));
		exit;
		break;
	default:
		
		break;
}}