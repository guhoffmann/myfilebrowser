<?php

include 'myFunctions.php';

/** Get the action parameter from the calling GET or POST request *************
 */

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
	$action =  $_POST["action"];
} else {
	$action =  urldecode($_GET["action"]);
}

//=============================================================================
//=========== Now filter the actions and react as an event handler ============
//=============================================================================

/******************************************************************************
 ** Create a new folder in current directory 
 */

if ( $action == "createFolder" ) {

	// create new folder in current dir
	$pathname = urldecode($_GET["objectname"]);
	//header("Content-type: text/plain");
	mkdir($baseDir."/".$pathname);

/******************************************************************************
 * Zip files in folder zipfiles for later download
 */

} else if ( $action == "zipFiles" ) {

	set_time_limit(0);

	$dateStr = "_".date('y-m-d_H-i-s');
	$postData = $_POST['postData'];
	$zipFileName= $_SERVER["DOCUMENT_ROOT"]."/zipfiles/".$_POST['filename'].$dateStr.".zip";
	$command = "7z a \"".$zipFileName."\"";

	//echo "\n*** zipFiles.php, before Loop!".PHP_EOL;

	foreach($postData as $value) { //loop over values

		$fileToAdd = $baseDir.$value;
		$command .= " \"".$fileToAdd."\"";
		//echo $fileToAdd.PHP_EOL;
	
	}

	$response = shell_exec($command);
	echo basename($zipFileName);

/******************************************************************************
 ** Download zipped file containig zipped files and folders from the
 ** zipfiles folder (called from downloadFiles() in myGuiFunctions.js
 ** after the zip files were created)!
 */

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

	// delete the file after transmission
	delete_files($downloadFile);

/******************************************************************************
 * Make listing of the actual directory
 */

} elseif ( $action == "dirlist" ) {

	$relDir = urldecode($_GET["pathname"]);

	header("Content-type: text/html");

	// Break if URL isn't allowed!

	if ( checkUrl($relDir) == 0 ) {
		exit("Unzulässige URL!");
	}

	if ( $relDir=="" OR $relDir=="/" ) {
		 echo("<h4 class='padding-start'><i class='material-icons'>home</i></h4>\r\n<table>\r\n");
	} else {
		 echo("<h4 class='padding-start'><i class='material-icons'>home</i>&nbsp;/".substr($relDir,2)."</h4>\r\n");
		 // create dirup entry if not in document root!
		 echo("<table>\r\n<tr><td class='folder' colspan='4' style=\"width:2em\">".
			  "<a href='?".dirname($relDir)."#list'><i class='material-icons'>arrow_upward</i> .. ( Verzeichnis zur&uuml;ck )</a></td></tr>\r\n");
	}

	echo "<section class='section1'>\r\n";
	$dirs = "";
	$files = "";
	$dirList = scandir( $baseDir.$relDir );

	$relDir .= "/";

	for ( $i = 0; $i < count($dirList); $i++ ) {

		$absDirAktFile = $baseDir.$relDir.$dirList[$i]; 
		$relDirAktFile = $relDir.$dirList[$i]; 

		if ( is_dir( $absDirAktFile ) AND $dirList[$i] != "." AND $dirList[$i] != ".." ) {
			
			// Now when it's a folder, do this...
			
			$dirs .= "<tr><td class='folder' style='width:2em; text-align: center;' valign='top'>\r\n".
					// For the new self styled checkbox
					"<span class='checkcontainer'>".
			      "<input type='checkbox' name='fileaction' value='".$relDirAktFile."' id='checkbox-".$i."' class='hidden_checkbox'>".
					"<label for='checkbox-".$i."'><span class='checkbox'></span></label>".
					"</span>".

					"</td><td class='folder' colspan='3' style=\"width:2em\"><a href='?".
						$relDirAktFile."#list'><i class='material-icons'>folder</i>\r\n".
					 $dirList[$i]."</a></td>\n";
					 
		} elseif ( is_file( $absDirAktFile ) ) {
			
			// Aaah, we found it's a file, so...

			$fileSize = formatSize(filesize($absDirAktFile));
						 $fileDate = date("d.m.Y  H:i:s", filemtime($absDirAktFile));
			
			$files .= "<tr><td class='direntry' style='width:2em; text-align: center;' valign='top'> \r\n".

					// For the new self styled checkbox
					"<span class='checkcontainer'>".
			      "<input type='checkbox' name='fileaction' value='".$relDirAktFile."' id='checkbox-".$i."' class='hidden_checkbox'>".
					"<label for='checkbox-".$i."'><span class='checkbox'></span></label>".
					"</span>".

//					"<input type='checkbox' name='fileaction' value='".$relDirAktFile."' />".
					"</td><td class='direntry'>".
					  "<a href='/cgi-bin/showFile.php?filename=".$relDirAktFile."'><span class='white'>".
					$dirList[$i]."</span></br><span class='blue5'>".$fileDate."&nbsp; ".$fileSize."</span></a><td class='direntry' style='width:3em; text-align: center;' >".
					  "<a href='/cgi-bin/actions.php?objectname=".$relDirAktFile."&action=downloadFile'><i class='material-icons green'>cloud_download</i></a></td>\n";
		}
	}

	echo($dirs);
	echo($files);
	echo "</section>";

/******************************************************************************
 * Download file with the green cloud link on the right
 */

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

/******************************************************************************
 ** Delete one file, calls delete_files() from myfilebrowser_functions.php!
 */
 
} elseif ( $action == "deleteFile" ) {

	// delete file or dir
	$filename = urldecode($_GET["objectname"]);
	delete_files($baseDir."/".$filename);

/******************************************************************************
 ** Show short page with some infos 'bout the server
 */

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

/******************************************************************************
 ** Show php info pages
 */

} elseif ( $action == "phpinfo" ) {

	// show the well known phpinfo page

	header("Content-type: text/html");
	phpinfo();

/******************************************************************************
 ** Copy marked files/folders to the clipboard (sqlite db)
 */

} elseif ( $action == "copyToClipboard" ) {

	$db = new PDO("sqlite:".$_SERVER["DOCUMENT_ROOT"]."/myfilebrowser.db");
	
	if (!$db) {
		echo $db->lastErrorMsg();
	} else {
		$postData = $_POST["objectname"];
		$numResults=0;
		foreach($postData as $value) { //loop over values
			$db->exec("INSERT INTO clipboard VALUES ('".$value."');");
			$numResults++;
		}
		$db = null;
		echo "<div class='info'>".$numResults." Dateien/Ordner eingefügt!</div>";
	}

/******************************************************************************
 ** Clear the clipboard (sqlite db)
 */

} elseif ( $action == "clearClipboard" ) {

	$db = new PDO("sqlite:".$_SERVER["DOCUMENT_ROOT"]."/myfilebrowser.db");
	
	if (!$db) {
		echo $db->lastErrorMsg();
	} else {
		echo "<div class='info'>Zwischenablage geleert!</div>";
		$db->exec("DELETE FROM clipboard;");
		$db = null;
	}

/******************************************************************************
 ** Show the contents of the clipboard (sqlite db)
 */

} elseif ( $action == "showClipboard" ) {

	$db = new PDO("sqlite:".$_SERVER["DOCUMENT_ROOT"]."/myfilebrowser.db");
	
	if (!$db) {
		echo $db->lastErrorMsg();
	} else {
		$result = $db->query("SELECT * FROM clipboard GROUP BY entry;");
		echo "<div class='info'>";
		$numResults=0;
		while ($row = $result->fetch()) {
			echo $row[0]."</br>";
			$numResults++;
		}
		if ( $numResults == 0 ) {
			echo "Die Zwischenablage ist leer.";
		}
		$db = null;
		echo "</div>";
	}

/******************************************************************************
 ** Paste files from clipboard to current location (sqlite db)
 */

} elseif ( $action == "pasteFiles" ) {

	$uploaddir = urldecode($_GET["uploadDir"]);

	echo "Einfügen nach: ".$uploaddir."\n";


	$db = new PDO("sqlite:".$_SERVER["DOCUMENT_ROOT"]."/myfilebrowser.db");
	
	if (!$db) {
		echo $db->lastErrorMsg();
	} else {
		$result = $db->query("SELECT * FROM clipboard GROUP BY entry;");
		while ($row = $result->fetch()) {
			echo "kopiere ".$row[0]." -> ".$uploaddir."/".basename($row[0])."\n";
			shell_exec("cp -r '".$baseDir.$row[0]."' '".$baseDir.$uploaddir."/".basename($row[0])."'" );
		}
		$db = null;
	}

/******************************************************************************
 ** This is it!
 */

} else {
	echo "No suited action found!";
}

?>

