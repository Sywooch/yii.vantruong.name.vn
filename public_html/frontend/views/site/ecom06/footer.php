
<div id="Footer"><div id="FooterContainer"><div class="footer"><div class="footer_info"><?php 
$b = Yii::$app->zii->getBox('footer-info');
if(!empty($b)){
	echo '<div class="company-address">' . uh($b['text'],2) . '</div>';
}
?></div></div></div><div class="Clear"></div></div>
<?php
$this->registerJsFile(__LIBS_DIR__ . '/menu/superfish-1.7.4/src/js/hoverIntent.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/menu/superfish-1.7.4/src/js/superfish.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/themes/js/jquery-ui.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/bootstrap/assets/js/bootstrap-tooltip.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/bootstrap/assets/js/moment.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/bootstrap/assets/js/bootstrap-datetimepicker.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/bootstrap/colorpicker/dist/js/bootstrap-colorpicker.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/bootstrap/3.2.0/js/ie10-viewport-bug-workaround.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/lazyload.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/modernizr.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/slider/slick-master/slick/slick.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/popup/colorbox/jquery.colorbox.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/lazyloadxt/lazy.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/main.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>