<?php

include 'myfilebrowser_functions.php';

$postData = $_POST['postData'];
//var_dump($postData);
$filename = $_POST['filename'];
echo("In PHP:".PHP_EOL);
echo($filename.PHP_EOL);
$command = "7z a ".$baseDir.dirname($postData[0])."/".$filename;

foreach($postData as $value) { //loop over values

	//echo $value . PHP_EOL; //print value
	$command .= " ".$baseDir.$value;
}

echo $command;
exec($command);

?>

