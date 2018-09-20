<?php

include 'myfilebrowser_functions.php';
$pathname = urldecode($_GET["pathname"]);
header("Content-type: text/plain");
mkdir($baseDir."/".$pathname);

?>

