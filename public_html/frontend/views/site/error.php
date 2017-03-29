<?php
$this->registerCssFile(__LIBS_DIR__ . '/themes/css/error.css');
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div id="cerror-pages" class="cerror-pages">
	
		
		<!-- Header top -->
		<div id="error-header_top"></div>
		<!-- End header top -->
		
		
		<!-- Header -->
		<div id="error-header">
			<h2>Oops, page not found</h2>
			<h5>Somebody really liked this page, or maybe your mis-typed the URL.</h5>
		</div>
		<!-- End Header -->
		
		
		<!-- The content div -->
		<div id="error-content">
		
			<!-- text -->
			<div id="text">
				<!-- The info text -->
				<p>Unfortunately it looks like someone really liked this page, and torned it out.</p>
				<p>But don‘t worry, there a lots of pages you can still see. Here is a little map to help you out:</p>
				<!-- End info text -->
				
				<!-- Page links -->
				<ul>
					<li><a href="/">» Home</a></li>
					<li><a onclick="goBack();" href="#">» Go previous page</a></li>
					 
				</ul>
				<!-- End page links -->	
			</div>
			<!-- End info text -->
		
		
			<!-- Book icon -->
			<img id="book" src="<?php echo __LIBS_DIR__?>/themes/img/error/book.png" alt="Book iCon">
			<!-- End Book icon -->
			
			<div style="clear:both;"></div>
		</div>
		<!-- End Content -->
		
		
		<!-- Footer -->
		<div id="error-footer">
			
			<!-- Twitter -->
			<img src="<?php echo __LIBS_DIR__?>/themes/img/error/twitter.png" alt="twitter" id="twitter">
			
			<p id="twitter_text">
			<a href="#"></a> 
			
			<!-- Get tweet -->
						<!-- End get tweet -->
			</p>
			<!-- End Twitter -->
			
			
			<!-- Search Form -->
			<form action="" method="post">
			<p id="searchform">
				<input type="submit" name="submit" id="submit" value="Search">
				<input type="text" name="search" id="search">
			</p>
			</form>
			<!-- End Search form -->
			
			<div style="clear:both;"></div>
		</div>
		<!-- End Footer -->
		
		
		<!-- Footer bottom -->
		<div id="error-footer_bottom"></div>
		<!-- End Footer bottom -->
	
	 
		
		
		<div style="clear:both;"></div>


	</div>
