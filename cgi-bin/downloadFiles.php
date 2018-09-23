<?php

include 'myfilebrowser_functions.php';

$receivedData = trim(array_keys($_POST)[0],"\"");
$array = explode("\",\"", $receivedData); //split string into array seperated by ","

foreach($array as $value) //loop over values
{
    echo $value . PHP_EOL; //print value
}

?>

