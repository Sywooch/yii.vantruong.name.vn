<?php 


?><?
$str_s = '';
if (isset($_POST['str']))
{
        $str_s=$_POST['str'];
}
?>

<form action=? method=POST>
<textarea name=str><? echo $str_s; ?>
</textarea>
    Textarea:<input type=checkbox name=textarea value=yes>
<input type=submit>
<input type="hidden" name="_csrf-frontend" value="<?php echo Yii::$app->request->csrfToken;?>"/>
</form><br><br>


<?

//$str2 = html_entity_decode($str_s);
$str2 = unhtmlentities($str_s);

echo $str2;
echo "<br><br>\n\n";

parse_str($str2, $output);

$ta = false;
if (isset($_POST['textarea']) && $_POST['textarea'] == "yes")
{
             $ta = true;
}

foreach ($output as $key => $value)
{
            echo "<b>";
            echo urldecode($key);
            if ($ta) { echo "</b> = <textarea cols=120 rows=35 wrap=off>"; }
            else { echo "</b> = "; }

            echo htmlentities(urldecode($value));

            if ($ta) { echo "</textarea><br><br><br>\n\n\n"; }
            else { echo "<br><br><br>\n\n\n"; }
}

function unhtmlentities($string)
{
       return preg_replace('~&#([0-9][0-9])~e', 'chr(\\1)', $string);
}



$str="list[]=value1&list[]=value2&list[]=value3&list[]=value4";
$a = explode('&', $str);
$values = Array();

$i=0;
foreach ($a as $v)
{
	$values[$i++] = substr(strstr($v, '='), 1);
}

print_r($values);
?>