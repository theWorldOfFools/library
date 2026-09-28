<?php
$dir    = 'H:\tolong\IGD\Agung Supriyanto';
$files1 = scandir($dir);
$files2 = scandir($dir, SCANDIR_SORT_DESCENDING);
echo count($files1);
print_r($files1);
//echo count($files1);
for($i=2;$i<=count($files1)-1;$i++)
            {

 echo "<br>";

$parts=pathinfo($files1[$i]);
$fileNameParts = explode('.', $files1[$i]);
$ext = end($fileNameParts);
//echo $files1[$i];

if($ext == 'jpg' or $ext == 'png')
{
	$img_type = $files1[$i];
}
echo "<br>";

}
echo $img_type;
//print_r($files2);
?>