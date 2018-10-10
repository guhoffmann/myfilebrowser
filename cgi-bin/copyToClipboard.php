<?php

set_time_limit(0);

include 'myfilebrowser_functions.php';

$postData = $_POST['postData'];
$db = new PDO("sqlite:../myfilebrowser.db");
/*
if(!$db) {
      echo $db->lastErrorMsg();
} else {
      echo "Opened database successfully\n";
}
$db->exec("INSERT INTO clipboard VALUES ('t.txt');");
*/

foreach($postData as $value) { //loop over values

	$db->exec("INSERT INTO clipboard VALUES ('".$value."');");

}

$db = null;

?>

