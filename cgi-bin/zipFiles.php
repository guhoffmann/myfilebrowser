<?php

set_time_limit(0);

include 'myFunctions.php';

$dateStr = "_".date('y-m-d_H-i-s');
$postData = $_POST['postData'];
$zipFileName= $_SERVER["DOCUMENT_ROOT"]."/zipfiles/".$_POST['filename'].$dateStr.".zip";
$command = "7z a \"".$zipFileName."\"";

//echo "\n*** zipFiles.php, before Loop!".PHP_EOL;

foreach($postData as $value) { //loop over values

	$fileToAdd = $baseDir.$value;
	$command .= " \"".$fileToAdd."\"";
	//echo $fileToAdd.PHP_EOL;
	
}

$response = shell_exec($command);

echo basename($zipFileName);

?>

