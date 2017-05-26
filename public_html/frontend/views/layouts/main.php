<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use app\modules\admin\models\Domains;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]>  <html class="no-js"><![endif]-->
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?= Yii::$app->language ?>" class="no-js"><head>
<meta charset="<?= Yii::$app->charset ?>">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="msnbot" content="NOODP" />
<meta http-equiv="Cache-control" content="public" />
<meta http-equiv="expires" content="<?php echo date("D, j M Y G:i:s ",time()+3600);?> GMT"/>
<meta http-equiv="pragma" content="public"/>
<meta name="author" content="<?php echo MAIN_DOMAIN;?>"/>
<meta name="description" content="<?php echo get_site_value('seo/description'); ?>" />
<meta name="keywords" content="<?php echo get_site_value('seo/keyword'); ?>" />
<meta name="Title" content="<?= Html::encode(get_site_value('seo/title')) ?>" />
<link rel="shortcut icon" href="<?php echo isset(Yii::$site['logo']['favicon']['image']) ? Yii::$site['logo']['favicon']['image'] : ''; ?>" type="image/x-icon" />
<?php
$og_image = isset(Yii::$site['seo']['og_image']) ? Yii::$site['seo']['og_image'] : '';
$html = $og_image != "" ? '<meta property="og:image" content="'.getImage(['src'=>$og_image,'w'=>550,'save'=>true,'output'=>'src','absolute'=>true],true).'"/>' : '';
$html .= '<meta property="og:url" content="'.URL_WITH_PATH.'" />
<meta property="og:type" content="website" />
<meta property="og:locale" content="vi_VN" />
<meta property="og:site_name" content="'.get_site_value('seo/site_name').'" />		
<meta property="og:title" content="'.get_site_value('seo/title').'" />
<meta property="og:description" content="'.get_site_value('seo/description').'" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:description" content="'.get_site_value('seo/description').'" />
<meta name="twitter:title" content="'.get_site_value('seo/title').'" />
<meta name="copyright" content="'.(get_site_value('seo/copyright') != "" ? get_site_value('seo/copyright') : '').'" />
<meta name="author" content="'.(get_site_value('seo/author') != "" ? get_site_value('seo/author') : '').'" />
<meta name="revisit-after" content="1 days" />';
echo $html;
//$this->registerCssFile(__LIBS_DIR__ . '/themes/css/base.css');
$this->registerCustomizeCss();
echo Html::csrfMetaTags();
echo get_site_value('seo/meta');
$domains = explode(',', get_site_value('seo/domain'));
if(
		(isset(Yii::$_category['rel']) && Yii::$_category['rel'] == 'nofollow') 
		|| !in_array(DOMAIN, $domains)
		|| in_array(Yii::$app->controller->id, [
				'ajax','sajax','contact','cart','search','login','logout','error'
])
		){
	echo '<meta name="robots" content="noindex,nofollow">';
}else{
	echo '<meta content="index,follow" name="robots"/>';
}
?>
<title><?= Html::encode(get_site_value('seo/title')); ?></title>
<?php $this->head();?>
</head><body class="<?php echo Yii::$app->controller->action->id;?>-page">
<?php $this->beginBody();
if(file_exists(__VIEWS__ . '/header.php')){
	include_once __VIEWS__ . '/header.php';
}
echo '<div id="main" class="main_out_side">'. $content .'</div>';
if(file_exists(__VIEWS__ . '/footer.php')){
	include_once __VIEWS__ . '/footer.php';
} 
$this->registerCustomizeJs();
//$this->registerJsFile(__LIBS_DIR__ . '/jquerycookie/jquery.cookie.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('https://cdn.ampproject.org/v0/amp-carousel-0.1.js', ['depends' => [\yii\web\JqueryAsset::className()],'async'=>'async','custom-element'=>"amp-carousel"]);
$this->registerJsFile('https://cdn.ampproject.org/v0/amp-analytics-0.1.js', ['depends' => [\yii\web\JqueryAsset::className()],'async'=>'async','custom-element'=>"amp-analytics"]);
$this->registerJsFile('https://cdn.ampproject.org/v0/amp-iframe-0.1.js', ['depends' => [\yii\web\JqueryAsset::className()],'async'=>'async','custom-element'=>"amp-iframe"]);
$this->registerJsFile('https://cdn.ampproject.org/v0/amp-youtube-0.1.js', ['depends' => [\yii\web\JqueryAsset::className()],'async'=>'async','custom-element'=>"amp-youtube"]);
//$this->registerJsFile(__LIBS_DIR__ . '/themes/js/fapi.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile(__LIBS_DIR__ . '/themes/js/gapi.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('https://apis.google.com/js/client:platform.js', ['depends' => [\yii\web\JqueryAsset::className()],'async'=>'async', 'defer'=>'defer']);
$this->endBody();?>
<div id="fb-root"></div></body></html>
<?php $this->endPage();?>