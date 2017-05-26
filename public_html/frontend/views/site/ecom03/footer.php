<div class="clear"></div>
 <footer id="footer" class="fl100" style="padding-bottom: 15px">
<div class="container">
<div class="row">
<?php 
$b = Yii::$app->zii->getBox('aboutus');
if(!empty($b)){
	echo '<div class="col-md-4 col-sm-6 col-xs-12">
    <div class="col_first">
        <div class="head_"><h3>'.uh($b['title']).'</h3></div>
        <div class="address">'.uh($b['text'],2).'</div>
    </div>
</div>';
}
?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <div class="col_second">
    <div class="head_ head___22"></div>    
    <ul class="link">
<?php 
$l = Yii::$app->zii->getCategorys(['key'=>'bottom']);
if(!empty($l)){
	foreach($l as $k=>$v){
		$link = cu([ DS.$v['url'] ]); 
		echo '<li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home"><a title="'.uh($v['title']).'" href="'.$link.'">'.uh($v['title']).'</a></li>';
	}
}?> </ul>
    </div>
     
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <div class="head_ head___33"></div>
    <!--like facebook-->
   <?php 
$b = Yii::$app->zii->getBox('fb_fanpage');
if(!empty($b)){
	echo uh($b['text'],2);
}
?>
</div>
<div class="clearfix"></div>
</div>
</div>
</footer>
<div class="box_footer2 copyright fl100">
  <div class="container">
     <?php 
$b = Yii::$app->zii->getBox('copyright');
if(!empty($b)){
	echo '<div class="address">'.uh($b['text'],2).'</div>';
}
?>
  </div>
</div>

<?php 

/*/$this->registerJsFile(__RSDIR__ .'/js/cloud-zoom.1.0.3.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile(__LIBS_DIR__ .'/cloudzoom/cloudzoom.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile(__LIBS_DIR__ . '/slider/jssor/js/jssor.slider.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
 
$this->registerJsFile(__LIBS_DIR__ . '/menu/superfish-1.7.4/src/js/hoverIntent.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/menu/superfish-1.7.4/src/js/superfish.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/themes/js/jquery-ui.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

//$this->registerJsFile(__LIBS_DIR__ . '/bootstrap/3.2.0/js/bootstrap.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile(__LIBS_DIR__ . '/bootstrap/assets/js/bootstrap-tooltip.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/bootstrap/assets/js/moment.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/bootstrap/assets/js/bootstrap-datetimepicker.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/bootstrap/colorpicker/dist/js/bootstrap-colorpicker.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/bootstrap/3.2.0/js/ie10-viewport-bug-workaround.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile(__RSDIR__ . '/js/jquery.number.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/lazyload.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/modernizr.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/slider/slick-master/slick/slick.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/popup/colorbox/jquery.colorbox.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/lazyloadxt/lazy.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile('https://cdn.rawgit.com/jackmoore/zoom/master/jquery.zoom.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__RSDIR__ . '/js/main.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
*/
?>   
<div id="fb-root"></div>  