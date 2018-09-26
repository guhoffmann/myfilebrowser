<?php

include 'myfilebrowser_functions.php';
$filename = substr($_GET["filename"],1);

if (file_exists($baseDir.$filename)) {
	
	header('Content-Description: File Transfer');
	header('Content-Type: application/force-download');
	header('Content-Disposition: attachment; filename="'.basename($filename).'"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');
	ob_clean();
	flush();
	readfile($baseDir.$filename);
	fclose($baseDir.$filename);
}

?>

