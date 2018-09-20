<?php

include 'myfilebrowser_functions.php';
$filename = urldecode($_GET["filename"]);
delete_files($baseDir."/".$filename);

?>
