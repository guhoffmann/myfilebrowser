<?php

include 'myfilebrowser_functions.php';

$targetDir = $_POST['uploadDir'];
$db = new PDO("sqlite:../myfilebrowser.db");
$result = $db->query("SELECT entry FROM clipboard GROUP BY entry");
/*
if(!$db) {
      echo $db->lastErrorMsg();
} else {
      echo "Opened database successfully\n";
}
$db->exec("INSERT INTO clipboard VALUES ('t.txt');");
*/

foreach ($result as $row) {
    $filename = $row["entry"];
	$response = shell_exec("cp -r \"".$baseDir.$filename."\" \"".$baseDir.$targetDir."/".basename($filename)."\"");
	print_r("cp \"".$baseDir.$filename."\" \"".$baseDir.$targetDir."/".basename($filename)."\"".PHP_EOL);

}

$db = null;

?>

