<?php

include 'myfilebrowser_functions.php';

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
	$action =  $_POST["action"];
} else {
	$action =  urldecode($_GET["action"]);
}

if ( $action == "createFolder" ) {

	// create new folder in current dir

	$pathname = urldecode($_GET["objectname"]);
	//header("Content-type: text/plain");
	mkdir($baseDir."/".$pathname);

} elseif ( $action == "downloadFile" ) {

	// download file

	$filename = substr($_GET["objectname"],1);
	if (file_exists($baseDir.$filename)) {

			$size=filesize($baseDir.$filename);
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="'.basename($filename).'"');
			header('Content-Transfer-Encoding: binary');
			header('Accept-Ranges: bytes');
 
			ob_clean();
			flush();
			readfile($baseDir.$filename);
			fclose($baseDir.$filename);
	}

	exit; // MUST add this to prevent an x0a attached at the end of file!!!

} elseif ( $action == "deleteFile" ) {

	// delete file or dir

	$filename = urldecode($_GET["objectname"]);
	delete_files($baseDir."/".$filename);

} elseif ( $action == "info" ) {

	// return some program infos

		header("Content-type: text/html");

		$clientIp = $_SERVER['REMOTE_ADDR'];
		echo("<table><tr><td colspan='2' class='info'>Einfache Dateiverwaltung f&uuml;r entfernte Daten.</br></br></td></tr>");
		echo("<tr><td class='right info'>Server-Software:</td><td class='left info'>".$_SERVER['SERVER_SOFTWARE']."</td></tr>");
		echo("<tr><td class='right info'>Server-Name/-IP:</td><td class='left info'>".$_SERVER['SERVER_NAME']."</br>".$_SERVER['SERVER_ADDR']."</td></tr>");
		echo("<tr><td class='right info'>Port:</td><td class='left info'>".$_SERVER['SERVER_PORT']."</td></tr>" );
		echo("<tr><td class='right info'>Client-IP:</td><td class='left info'>".$_SERVER['REMOTE_ADDR']."</td></tr>");
		echo("<tr><td colspan='2' class='info'></br>(C) Gert-Uwe Hoffmann 2018</td></tr></table>");

} elseif ( $action == "phpinfo" ) {

	// show the well known phpinfo page

	header("Content-type: text/html");
	phpinfo();

} elseif ( $action == "downloadZipAndDelete" ) {

	// download zipped files from zipfiles die and delete them afterwards

	$filename = substr($_GET["objectname"],1);

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

} elseif ( $action == "copyToClipboard" ) {

		$postData = $_POST["objectname"];
		$db = new PDO("sqlite:../myfilebrowser.db");
		
		if (!$db) {
			  echo $db->lastErrorMsg();
		} else {
			  echo "Opened database successfully\n";
		}

		foreach($postData as $value) { //loop over values

			$db->exec("INSERT INTO clipboard VALUES ('".$value."');");

		}

		$db = null;
		echo "I've done it!";
} else {
	echo "No suited action found!";
}

?>

