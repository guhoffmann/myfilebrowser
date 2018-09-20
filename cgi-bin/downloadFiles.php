<?php

include 'myfilebrowser_functions.php';
//$filename = substr($_GET["filename"],1);
//var_dump($_POST);

foreach($_POST as $key => $value) {
	//var_dump($i);
	//var_dump($stuff);
	$item = $key."=".$value;
	preg_match('name=',$item,$matche)
	echo $item;
	var_dump($matche);
}

?>

