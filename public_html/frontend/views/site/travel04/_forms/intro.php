<div id="bg" class="bgwidth"></div>

<div id="intro"> 
<div id="content">
<div id="logo" class="" style="opacity: 0">
<a href="/">
<img src="<?php echo __RSDIR__;?>/images/logo_en.png" /></a></div>
<div class="clear">
</div>
<div id="inner">
<div id="intro-accor">
<div id="text">
<?php 
$b = Yii::$app->zii->getBox('intro_right');
if(!empty($b)){
	echo uh($b['text'],2);
}
?>
</div>
<ul class="accordion" id="accordion">
 
<?php 
foreach (Yii::$app->zii->getAdvert([
		'code'=>'ADV_INTRO',
		'lang'=>false,
]) as $k=>$v){
	echo '<li class="sicon-language" style="background-image:url('.$v['image'].')">
                            <div class="heading '.(isset($v['icon_class']) ? $v['icon_class'] : '').'">'.uh($v['title']).'</div>
                            <div class="bgDescription">
                            </div>
                            <div class="description">
                                <div class="percent-60">
                                    <h2>
                                        <a target="'.$v['target'].'" href="'.$v['link'].'">'.uh($v['title']).'</a></h2>
                                    '.(uh($v['info'])).'
                                    <a target="'.$v['target'].'" href="'.$v['link'].'" class="more"><i class="fa fa-hand-o-right"></i> &rarr;</a>
                                </div>
                                <div class="percent-30">';
if($v['category_id']>0){
	$l1 = \app\models\SiteMenu::getList([
			'parent_id'=>$v['category_id']
	]);
	$i = \app\models\SiteMenu::getItem($v['category_id']);
	echo '<h3>'.uh($i['title']).'</h3>';
	if(!empty($l1)){		
		foreach ($l1 as $v1){
			echo '<a href="'.$v1['url_link'].'" target="'.$v1['target'].'">'.uh($v1['title']).'</a>';
		}
	}
}
echo '                                    
                                </div>
                            </div>
                        </li>';
}
 
?>                        
                    </ul>
                </div>
            </div>
        </div>
    </div>
<div class="address-foot">
<?php 
$b = Yii::$app->zii->getBox('intro_bottom');
if(!empty($b)){
	echo uh($b['text'],2);
}
?>
</div>
<?php 
$this->registerCssFile(__RSDIR__ .'/css/intro.css',['depends' => [\yii\bootstrap\BootstrapAsset::className()]], 'css-intro-theme');
$this->registerJs(' 
 
		
$(\'#accordion > li\').hover(
   function() {
       var $this = $(this);
       $this.stop().animate({ \'width\': \'480px\' }, 800);
       $(\'.heading\', $this).stop(true, true).fadeOut();
       $(\'.bgDescription\', $this).stop(true, true).fadeIn(300);
       $(\'.description\', $this).stop(true, true).fadeIn();
   },
   function() {
       var $this = $(this);
       $this.stop().animate({ \'width\': \'100px\' }, 800);
       $(\'.heading\', $this).stop(true, true).fadeIn();
       $(\'.description\', $this).stop(true, true).fadeOut(500);
       $(\'.bgDescription\', $this).stop(true, true).fadeOut(700);
   }
  );
		
		');
?>