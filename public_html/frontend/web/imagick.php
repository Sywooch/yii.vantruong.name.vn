<?php
@ini_set('display_startup_errors',1);@ini_set('display_errors',1);
header('Content-type: image/jpeg');

//$image = new Imagick($_GET['src']);
$im = new Imagick($_GET['src']);

/* optimize the image layers */
$im->optimizeImageLayers();

/* write the image back */
$im->writeImages("test_optimized.jpg", true);

?>