<div class="footer col-sm-12" id="footer">
    <div class="container pr">
        <div id="fborder"></div>
        <div class="col-sm-12"><div class="row">
        <div class="custom-footer">
         <div  class=" pull-left" style="">
        <div class="heading_1">TWITTER</div>
        
        
        </div>
        <div  class="facebook_box pull-right" style="width: 520px;">
        
        <div class="heading_1">FACEBOOK</div>
        <div class="fb-page" data-href="https://www.facebook.com/thaochipshop" data-width="460" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/thaochipshop"><a href="https://www.facebook.com/thaochipshop">THẢO CHÍP SHOP</a></blockquote></div></div>
        
        </div>
        </div>
        </div>
        </div>
        
        <div  class="footer_nav col-sm-12">
        <div class="row">
            
            <?php
            echo $this -> app()-> getMenuItem(
array(//'listItem'=> $reg->f->__get_bottom_nav(),
//'hTag'=>array('span'),
'key'=>'bottom',
'maxLevel'=>2,
'attribute'=>array( 'class'=>'bottommenu style-none '),
//'firstItemClass'=>'first-item',
//'lastItemClass'=>'last-item',
'activeClass'=>array('a'=>'active'),
)); 
            ?>  
          
           
          <div class="custom-column"><div class="column-content">
          <div class="share">
          	<div class="addthis_default_style">
          	<a target="_blank" class="fpage" href="https://www.facebook.com/thaochipshop"><span class="face-icon"></span></a>
            <a target="_blank" class="tpage" href="https://twitter.com/dongunu"><span class="twitter-icon"></span></a>
            <a target="_blank" class="gpage" href="https://plus.google.com/u/0/110331228633803092473"><span class="googlep-icon"></span></a>
            <a class="addthis_button_email at300b" target="_blank" title="Email" href="#"><span class="email-icon"></span></a>
            <a target="_blank" class="addthis_button_compact at300m" href="#"><span class="more-icon"></span></a>
            </div>
            <script type="text/javascript" src="//s7.addthis.com/js/250/addthis_widget.js"></script>
          </div>
        			<table class="footer-c-column">
                  		<tbody>
                   			<tr id="contact-phone">
                    			<td class="">
                                	Phone:            			</td>
                    			<td class="value">0946581636</td>
                    		</tr>
                   			<tr id="contact-mobile">
                    			<td class="">
                                	Mobile:            			</td>
                    			<td class="value">0946581636</td>
                    		</tr>
                   			<tr id="contact-fax">
                    			<td class="">
                                	Fax:            			</td>
                    			<td class="value">0946581636</td>
                    		</tr>
                   			<tr id="contact-email">
                    			<td class="">
                                	E-Mail:            			</td>
                    			<td class="value"><a href="mailto:thaodtp123@gmail.com">thaodtp123@gmail.com</a></td>
                    		</tr>
                    	</tbody>
                    </table>
          </div></div>
        </div></div>
        
        
       
        
        
    </div>
</div>
 <div id="powered">
<div class="p-container">
<div class="powered"></div>
<div class="cards"> </div>
</div>
</div>
<div id="fb-root"></div>

<?php 

//$this->registerJsFile(__RSDIR__ .'/js/cloud-zoom.1.0.3.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ .'/cloudzoom/cloudzoom.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(__LIBS_DIR__ . '/slider/jssor/js/jssor.slider.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJs(
		'var _SlideshowTransitions = [
            //Swing Outside in Stairs
            {$Duration: 1200, $Delay: 20, $Cols: 8, $Rows: 4, $Clip: 15, $During: { $Left: [0.3, 0.7], $Top: [0.3, 0.7] }, $FlyDirection: 9, $Formation: $JssorSlideshowFormations$.$FormationStraightStairs, $Assembly: 260, $Easing: { $Left: $JssorEasing$.$EaseInWave, $Top: $JssorEasing$.$EaseInWave, $Clip: $JssorEasing$.$EaseOutQuad }, $ScaleHorizontal: 0.2, $ScaleVertical: 0.1, $Outside: true, $Round: { $Left: 1.3, $Top: 2.5} }

            //Dodge Dance Outside out Stairs
            , { $Duration: 1500, $Delay: 20, $Cols: 8, $Rows: 4, $Clip: 15, $During: { $Left: [0.1, 0.9], $Top: [0.1, 0.9] }, $SlideOut: true, $FlyDirection: 9, $Formation: $JssorSlideshowFormations$.$FormationStraightStairs, $Assembly: 260, $Easing: { $Left: $JssorEasing$.$EaseInJump, $Top: $JssorEasing$.$EaseInJump, $Clip: $JssorEasing$.$EaseOutQuad }, $ScaleHorizontal: 0.3, $ScaleVertical: 0.3, $Outside: true, $Round: { $Left: 0.8, $Top: 2.5} }

            //Dodge Pet Outside in Stairs
            , { $Duration: 1500, $Delay: 20, $Cols: 8, $Rows: 4, $Clip: 15, $During: { $Left: [0.3, 0.7], $Top: [0.3, 0.7] }, $FlyDirection: 9, $Formation: $JssorSlideshowFormations$.$FormationStraightStairs, $Assembly: 260, $Easing: { $Left: $JssorEasing$.$EaseInWave, $Top: $JssorEasing$.$EaseInWave, $Clip: $JssorEasing$.$EaseOutQuad }, $ScaleHorizontal: 0.2, $ScaleVertical: 0.1, $Outside: true, $Round: { $Left: 0.8, $Top: 2.5} }

            //Dodge Dance Outside in Random
            , { $Duration: 1500, $Delay: 20, $Cols: 8, $Rows: 4, $Clip: 15, $During: { $Left: [0.3, 0.7], $Top: [0.3, 0.7] }, $FlyDirection: 9, $Easing: { $Left: $JssorEasing$.$EaseInJump, $Top: $JssorEasing$.$EaseInJump, $Clip: $JssorEasing$.$EaseOutQuad }, $ScaleHorizontal: 0.3, $ScaleVertical: 0.3, $Outside: true, $Round: { $Left: 0.8, $Top: 2.5} }

            //Flutter out Wind
            , { $Duration: 1800, $Delay: 30, $Cols: 10, $Rows: 5, $Clip: 15, $During: { $Left: [0.3, 0.7], $Top: [0.3, 0.7] }, $SlideOut: true, $FlyDirection: 5, $Reverse: true, $Formation: $JssorSlideshowFormations$.$FormationStraightStairs, $Assembly: 2050, $Easing: { $Left: $JssorEasing$.$EaseInOutSine, $Top: $JssorEasing$.$EaseOutWave, $Clip: $JssorEasing$.$EaseInOutQuad }, $ScaleHorizontal: 1, $ScaleVertical: 0.2, $Outside: true, $Round: { $Top: 1.3} }

            //Collapse Stairs
            , { $Duration: 1200, $Delay: 30, $Cols: 8, $Rows: 4, $Clip: 15, $SlideOut: true, $Formation: $JssorSlideshowFormations$.$FormationStraightStairs, $Assembly: 2049, $Easing: $JssorEasing$.$EaseOutQuad }

            //Collapse Random
            , { $Duration: 1000, $Delay: 80, $Cols: 8, $Rows: 4, $Clip: 15, $SlideOut: true, $Easing: $JssorEasing$.$EaseOutQuad }

            //Vertical Chess Stripe
            , { $Duration: 1000, $Cols: 12, $FlyDirection: 8, $Formation: $JssorSlideshowFormations$.$FormationStraight, $ChessMode: { $Column: 12} }

            //Extrude out Stripe
            , { $Duration: 1000, $Delay: 40, $Cols: 12, $SlideOut: true, $FlyDirection: 2, $Formation: $JssorSlideshowFormations$.$FormationStraight, $Assembly: 260, $Easing: { $Left: $JssorEasing$.$EaseInOutExpo, $Opacity: $JssorEasing$.$EaseInOutQuad }, $ScaleHorizontal: 0.2, $Opacity: 2, $Outside: true, $Round: { $Top: 0.5} }

            //Dominoes Stripe
            , { $Duration: 2000, $Delay: 60, $Cols: 15, $SlideOut: true, $FlyDirection: 8, $Formation: $JssorSlideshowFormations$.$FormationStraight, $Easing: $JssorEasing$.$EaseOutJump, $Round: { $Top: 1.5} }
            ];    
            var options = {
                $AutoPlay: true,                                    //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
                $AutoPlaySteps: 1,                                  //[Optional] Steps to go for each navigation request (this options applys only when slideshow disabled), the default value is 1
                $AutoPlayInterval: 4000,                            //[Optional] Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000
                $PauseOnHover: 0,                               //[Optional] Whether to pause when mouse over if a slider is auto playing, 0 no pause, 1 pause for desktop, 2 pause for touch device, 3 pause for desktop and touch device, 4 freeze for desktop, 8 freeze for touch device, 12 freeze for desktop and touch device, default value is 1

                $ArrowKeyNavigation: true,   			            //[Optional] Allows keyboard (arrow key) navigation or not, default value is false
                $SlideDuration: 500,                                //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
                $MinDragOffsetToSlide: 20,                          //[Optional] Minimum drag offset to trigger slide , default value is 20
                //$SlideWidth: 600,                                 //[Optional] Width of every slide in pixels, default value is width of \'slides\' container
                //$SlideHeight: 300,                                //[Optional] Height of every slide in pixels, default value is height of \'slides\' container
                $SlideSpacing: 5, 					                //[Optional] Space between each slide in pixels, default value is 0
                $DisplayPieces: 1,                                  //[Optional] Number of pieces to display (the slideshow would be disabled if the value is set to greater than 1), the default value is 1
                $ParkingPosition: 0,                                //[Optional] The offset position to park slide (this options applys only when slideshow disabled), default value is 0.
                $UISearchMode: 1,                                   //[Optional] The way (0 parellel, 1 recursive, default value is 1) to search UI components (slides container, loading screen, navigator container, arrow navigator container, thumbnail navigator container etc).
                $PlayOrientation: 1,                                //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, 5 horizental reverse, 6 vertical reverse, default value is 1
                $DragOrientation: 3,                                //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)
                $SlideshowOptions: {                                //[Optional] Options to specify and enable slideshow or not
                   $Class: $JssorSlideshowRunner$,                 //[Required] Class to create instance of slideshow
                    $Transitions: _SlideshowTransitions,            //[Required] An array of slideshow transitions to play slideshow
                    $TransitionsOrder: 1,                           //[Optional] The way to choose transition to play slide, 1 Sequence, 0 Random
                    $ShowLink: true                                    //[Optional] Whether to bring slide link on top of the slider when slideshow is running, default value is false
                },

                   			            //[Optional] Allows keyboard (arrow key) navigation or not, default value is false
                //$SlideEasing: $JssorEasing$.$EaseOutQuint,          //[Optional] Specifies easing for right to left animation, default value is $JssorEasing$.$EaseOutQuad
                                                //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)

                 

                $BulletNavigatorOptions: {                                //[Optional] Options to specify and enable navigator or not
                    $Class: $JssorBulletNavigator$,                       //[Required] Class to create navigator instance
                    $ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
                    $AutoCenter: 0,                                 //[Optional] Auto center navigator in parent container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
                    $Steps: 1,                                      //[Optional] Steps to go for each navigation request, default value is 1
                    $Lanes: 1,                                      //[Optional] Specify lanes to arrange items, default value is 1
                    $SpacingX: 12,                                   //[Optional] Horizontal space between each item in pixel, default value is 0
                    $SpacingY: 4,                                   //[Optional] Vertical space between each item in pixel, default value is 0
                    $Orientation: 1,                                //[Optional] The orientation of the navigator, 1 horizontal, 2 vertical, default value is 1
                    $Scale: false                                   //Scales bullets navigator or not while slider scale
                }

            };
           
            if($(\'#slider1_container\').length > 0) {
            var jssor_slider1 = new $JssorSlider$("slider1_container", options);
            function ScaleSlider() {
            
                var parentWidth = jssor_slider1.$Elmt.parentNode.clientWidth;
                 
                if (parentWidth)
                    jssor_slider1.$SetScaleWidth(parentWidth );
                else
                    window.setTimeout(ScaleSlider, 30);
            }
              
            ScaleSlider();

            $(window).bind("load", ScaleSlider);
            $(window).bind("resize", ScaleSlider);
            $(window).bind("orientationchange", ScaleSlider);
            }',
		yii\web\View::POS_READY,
		'my-button-handler'
		);

//$this->registerJsFile(__LIBS_DIR__ . '/menu/superfish-1.7.4/src/js/hoverIntent.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile(__LIBS_DIR__ . '/menu/superfish-1.7.4/src/js/superfish.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile(__LIBS_DIR__ . '/themes/js/jquery-ui.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

//$this->registerJsFile(__LIBS_DIR__ . '/bootstrap/3.2.0/js/bootstrap.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

//$this->registerJsFile(__LIBS_DIR__ . '/bootstrap/assets/js/bootstrap-tooltip.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile(__LIBS_DIR__ . '/bootstrap/assets/js/moment.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile(__LIBS_DIR__ . '/bootstrap/assets/js/bootstrap-datetimepicker.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile(__LIBS_DIR__ . '/bootstrap/colorpicker/dist/js/bootstrap-colorpicker.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile(__LIBS_DIR__ . '/bootstrap/3.2.0/js/ie10-viewport-bug-workaround.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile(__RSDIR__ . '/js/jquery.number.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile(__LIBS_DIR__ . '/lazyload.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile(__LIBS_DIR__ . '/modernizr.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile(__LIBS_DIR__ . '/slider/slick-master/slick/slick.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile(__LIBS_DIR__ . '/popup/colorbox/jquery.colorbox.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile(__LIBS_DIR__ . '/lazyloadxt/lazy.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('https://cdn.rawgit.com/jackmoore/zoom/master/jquery.zoom.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile(__RSDIR__ . '/js/main.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

?> 
  
 
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 976924995;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/976924995/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.3";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
  