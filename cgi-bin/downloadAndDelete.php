<?php

include 'myfilebrowser_functions.php';
$filename = substr($_GET["filename"],1);

if( file_exists($baseDir.$filename) ) {
	$downloadFile = $baseDir.$filename;
}  elseif( file_exists($_SERVER["DOCUMENT_ROOT"]."/".$filename) ) {
	$downloadFile = $_SERVER["DOCUMENT_ROOT"]."/".$filename;
}  else {
	die("FEHLER: Kann Datei ".$filename." nicht runterladen!");
}
	
header('Content-Description: File Transfer');
header('Content-Type: application/force-download');
header('Content-Disposition: attachment; filename="'.basename($filename).'"');
header('Content-Transfer-Encoding: binary');
header('Accept-Ranges: bytes');
ob_clean();
flush();
readfile($downloadFile);
fclose($downloadFile);

delete_files($downloadFile);

?>

