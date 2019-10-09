<?php
session_start();

/*                          - actions.php -
 
          'Event handler' for MyFileBrowser http(s) file explorer.
   
                          (C) guhoffmann 2018
                          
 Note: for some actions the user rights are defined by a byte value in the
 myfilebrowser.db database! It is defined as a byte value with the bits
 
	1	delete files or folders
	2	upload files
	4	insert from clipboard
	8	edit notices in folder
	16	reserved
	32	reserved
	64	reserved
	128	reserved

 At the moment, a byte value of 1+2+4+8=15 defines full rights (=admin)!

*/

include 'myFunctions.php';

/** Get the action parameter from the calling GET or POST request *************
 */

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
	$action =  $_POST["action"];
} else {
	$action =  urldecode($_GET["action"]);
}

//=============================================================================
//=========================== action handlers =================================
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
 ** Read an edit notice in current directory 
 */
 
} else if ( $action == "readNotice" ) {
	
	$pathname = urldecode($_GET["objectname"]);
	header("Content-type: text/html");
	// open notizen.txt if found
	if ( file_exists($baseDir.$pathname."/notizen.txt") ) {
		$file = fopen($baseDir.$pathname."/notizen.txt", "r") or die("Unable to open ".$baseDir.$pathname."/notizen.txt!");
		echo fread($file,filesize($baseDir.$pathname."/notizen.txt"));
		fclose($file);
	}

/******************************************************************************
 ** Write content of edited notice to current directory 
 */
 
} else if ( $action == "saveNotice" ) {
	
	$pathname = urldecode($_GET["pathname"]);
	$content = urldecode($_GET["content"]);
	$file = fopen($baseDir.$pathname."/notizen.txt", "w") or die("Unable to open ".$baseDir.$pathname."/notizen.txt!");
	fwrite($file,$content);
	fclose($file);
		
/******************************************************************************
 ** Zip files in folder zipfiles for later download
 */

} else if ( $action == "zipFiles" ) {

	set_time_limit(0);

	$dateStr = "_".date('y-m-d_H-i-s');
	$postData = $_POST['postData'];
	$zipFileName= $_SERVER["DOCUMENT_ROOT"]."/zipfiles/".$_POST['filename'].$dateStr.".zip";
	$command = "7z a \"".$zipFileName."\"";

	foreach($postData as $value) { //loop over values
		$fileToAdd = $baseDir.$value;
		$command .= " \"".$fileToAdd."\"";
		//echo $fileToAdd.PHP_EOL;
	}

	$response = shell_exec($command); // do zipping
	echo basename($zipFileName); // return zip filename

/******************************************************************************
 ** Zip clipboard files in folder zipfiles for later download
 */

} else if ( $action == "zipClipboardFiles" ) {

	set_time_limit(0);

	$dateStr = "_".date('y-m-d_H-i-s');
	$zipFileName= $_SERVER["DOCUMENT_ROOT"]."/zipfiles/clipBoard_".$dateStr.".zip";
	$command = "7z a \"".$zipFileName."\"";
	
	foreach($_SESSION["memory"] as $key=>$data){
		$fileToAdd = $baseDir.$key;
		$command .= " \"".$fileToAdd."\"";
	}
	$response = shell_exec($command); // do zipping
	echo basename($zipFileName); // return zip filename
	
/******************************************************************************
 ** Download zipped file containig zipped files and folders from the
 ** zipfiles folder (called from downloadFiles() in myGuiFunctions.js
 ** after the zip files were created)!
 */

} elseif ( $action == "downloadZipAndDelete" ) {

	// download zipped files from zipfiles dir and delete them afterwards

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
 ** Make listing of the actual directory
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
			  "<a href='?".dirname($relDir)."#list'><div><i class='material-icons'>arrow_upward</i> .. ".$_SESSION["directory_up"]." </div></a></td></tr>\r\n");
	}

	echo "<section class='section1'>\r\n"; // for the self styled checkbox!
	$dirs = "";
	$files = "";
	$relDir .= "/";
	$dirList = scandir( $baseDir.$relDir );

	if ($dirList != FALSE) {
		for ( $i = 0; $i < count($dirList); $i++ ) {

			# show all except notizen.txt
			if ( $dirList[$i] != "notizen.txt" ) {
				
				$absDirAktFile = $baseDir.$relDir.$dirList[$i]; 
				$relDirAktFile = $relDir.$dirList[$i]; 

				if ( is_dir( $absDirAktFile ) AND $dirList[$i] != "." AND $dirList[$i] != ".." ) {
					
					// Now when it's a folder, do this...
					
					$dirs .= "<tr><td class='folder' style='width:2em; text-align: center;' valign='top'>\r\n".
							// For the new self styled checkbox
							"<span class='checkcontainer'>
							<input type='checkbox' name='fileaction' value='".$relDirAktFile."' id='checkbox-".$i."' class='hidden_checkbox'>
							<label for='checkbox-".$i."'><span class='checkbox'></span></label>
							</span>".
							// The link with the folder name
							"</td><td class='folder' colspan='3' style=\"width:2em\"><a href='?".
								$relDirAktFile."#list'><div><i class='material-icons'>folder</i>\r\n".
							 $dirList[$i]."</div></a></td>\n";
							 
				} elseif ( is_file( $absDirAktFile ) ) {
					
					// Aaah, we found it's a file, so...

					$fileSize = formatSize(filesize($absDirAktFile));
								 $fileDate = date("d.m.Y  H:i:s", filemtime($absDirAktFile));
					
					$files .= "<tr><td class='direntry' style='width:2em; text-align: center;' valign='top'> \r\n".

							// For the new self styled checkbox
							"<span class='checkcontainer'>
							<input type='checkbox' name='fileaction' value='".$relDirAktFile."' id='checkbox-".$i."' class='hidden_checkbox'>
							<label for='checkbox-".$i."'><span class='checkbox'></span></label>
							</span>".
							// Now for the rest
							"</td><td class='direntry'>
							  <a href='/cgi-bin/actions.php?action=showFile&filename=".$relDirAktFile."' target='_blank'><div><span class='white'>
							".$dirList[$i]."</span></br><span class='blue5'>".$fileDate."&nbsp; ".$fileSize."</span></div></a><td class='direntry' style='width:3em; text-align: center;' >
							  <a href='/cgi-bin/actions.php?objectname=".$relDirAktFile."&action=downloadFile'><i class='material-icons blue5'>cloud_download</i></a></td>\n";
				}
			} // of if ( $dirList[i] != "notizen.txt" )...
		} // of for ( $i = 0; $i < count($dirList); $i++ ) {...
		echo($dirs);
		echo($files);

	} else {
		echo "<h3>Error!</h3><p>No directory content found, please check if your docs directory exists!</p>";
	} //of if ($dirlist)...
	echo "</section>";// for the self styled checkbox!

/******************************************************************************
 ** Download file with the green cloud link on the right
 */

} elseif ( $action == "downloadFile" ) {

	// download file

	$filename = substr($_GET["objectname"],1);
	if (file_exists($baseDir.$filename)) {

		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.basename($filename).'"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');

		/* old simple way with readfile
		ob_clean();
		flush();
		readfile($baseDir.$filename); */

		// chunked download
		$chunkSize = 1024 * 1024;
		$handle = fopen($baseDir.$filename, 'rb');
		while (!feof($handle)) {
			$buffer = fread($handle, $chunkSize);
			echo $buffer;
			ob_flush();
			flush();
		}
		fclose($handle);
	}
	



	exit; // MUST add this to prevent an x0a being attached at the end of file!!!

/******************************************************************************
 ** Delete one file, calls delete_files() from myfilebrowser_functions.php!
 */
 
} elseif ( $action == "deleteFile" ) {

	// delete if rights to do it...
	if ( ($_SESSION["userrights"] & 1) != 0 ) {
		// delete file or dir
		$filename = urldecode($_GET["objectname"]);
		delete_files($baseDir."/".$filename);
	} else {
		echo "error";
	}

/******************************************************************************
 ** Get the notizen.txt of current path for display in edit view
 */

} elseif ( $action == "showInfo" ) {

	// return some program infos

	$db = connect_db();
	$result = $db->query('SELECT value FROM strings
								 WHERE language = '.$_SESSION["language"].
								' AND name = "prog_description"');
	$row = $result->fetch();
	header("Content-type: text/html");
	$clientIp = $_SERVER['REMOTE_ADDR'];
	$retStr= "<p class='info'>".$row[0]."</p>
				<p class='info'>".shell_exec("uname -a").
				"</br>HTTP-Server: ".$_SERVER['SERVER_SOFTWARE']."</br>
				Server-Name/-IP: ".$_SERVER['SERVER_NAME']." ".$_SERVER['REMOTE_ADDR']."</br>
				Port: ".$_SERVER['SERVER_PORT']."</br>
				Client-IP: ".getClientIp()."</p>
				<p class='info'><b>".$_SESSION["data_details"].":</b></br>"
				.$_SESSION["size"].": ".shell_exec("du -Lchs ../docs|grep docs|awk '{print $1}'")."</br>"
				.$_SESSION["files"].": ".shell_exec("find -L ../docs -type f|wc -l")."</br>"
				.$_SESSION["dirs"].": ".shell_exec("find -L ../docs -type d|wc -l")."</p>
				<p class='info'>(C) Gert-Uwe Hoffmann 2018</p>";
	echo $retStr;

/******************************************************************************
 ** Show short page with some infos 'bout the server
 */

} elseif ( $action == "showInfo" ) {

	// return some program infos

	$db = connect_db();
	$result = $db->query('SELECT value FROM strings
								 WHERE language = '.$_SESSION["language"].
								' AND name = "prog_description"');
	$row = $result->fetch();
	header("Content-type: text/html");
	$clientIp = $_SERVER['REMOTE_ADDR'];
	$retStr= "<p class='info'>".$row[0]."</p>
				<p class='info'>".shell_exec("uname -a").
				"</br>HTTP-Server: ".$_SERVER['SERVER_SOFTWARE']."</br>
				Server-Name/-IP: ".$_SERVER['SERVER_NAME']." ".$_SERVER['REMOTE_ADDR']."</br>
				Port: ".$_SERVER['SERVER_PORT']."</br>
				Client-IP: ".getClientIp()."</p>
				<p class='info'><b>".$_SESSION["data_details"].":</b></br>"
				.$_SESSION["size"].": ".shell_exec("du -Lchs ../docs|grep docs|awk '{print $1}'")."</br>"
				.$_SESSION["files"].": ".shell_exec("find -L ../docs -type f|wc -l")."</br>"
				.$_SESSION["dirs"].": ".shell_exec("find -L ../docs -type d|wc -l")."</p>
				<p class='info'>(C) Gert-Uwe Hoffmann 2018</p>";
	echo $retStr;

/******************************************************************************
 ** Show short help page
 */

} elseif ( $action == "showHelp" ) {

	// return some program infos

	$db = connect_db();
	$result = $db->query('SELECT value FROM strings
								 WHERE language = '.$_SESSION["language"].
								' AND name = "prog_help"');
	$row = $result->fetch();
	header("Content-type: text/html");
	echo $row[0];


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

		$postData = $_POST["objectname"];
		$numResults=0;
		foreach($postData as $value) { //loop over values
			array_push($_SESSION["memory"][$value],1);
			$numResults++;
		}
		echo "<div class='info'>".$numResults." Dateien/Ordner eingefügt!</div>";

/******************************************************************************
 ** Clear the clipboard
 */

} elseif ( $action == "clearClipboard" ) {

	echo "<div class='info'>Zwischenablage geleert!</div>";
	$_SESSION["memory"] = array();

/******************************************************************************
 ** Show the contents of the clipboard
 */

} elseif ( $action == "showClipboard" ) {

	echo "<div class='info'>";
	foreach($_SESSION["memory"] as $key=>$data){
		echo $key."</br>";
   }
	echo "</div>";

/******************************************************************************
 ** Paste files from clipboard to current location
 */

} elseif ( $action == "pasteFiles" ) {

	// paste files if rights to do it...
	if ( ($_SESSION["userrights"] & 4) != 0 ) {
		$uploaddir = urldecode($_GET["uploadDir"]);

		echo "Einfügen nach: ".$uploaddir."\n";

		foreach($_SESSION["memory"] as $key=>$data){
			echo "kopiere ".$key." -> ".$uploaddir."/".basename($key)."\n";
			shell_exec("cp -r '".$baseDir.$key."' '".$baseDir.$uploaddir."/".basename($key)."'" );
		}
	} else {
		echo "error";
	}

/******************************************************************************
 ** Upload files to current location and give message!
 */

} elseif ( $action == "uploadPost" ) {

	// upload if rights to do it...
	if ( ($_SESSION["userrights"] & 8) != 0 ) {
		echo '<!DOCTYPE html>
				<html lang="en">
				<head>
					<title>File Browser</title>
					<meta charset="utf-8">
					<meta name="viewport" content="width=device-width, initial-scale=1">
					<link rel="stylesheet" href="../css/bootstrap.min.css">
					<link rel="stylesheet" href="../css/custom.css">
				</head>
				<h1>Datei(en) hochladen:</h1>';

		$uploaddir = urldecode($baseDir.$_POST["uploadDir"]."/");

		// loop over selected files:
		if ( count($_FILES['file']['name']) > 0 ) {

			// loop over all selected files
			for ( $i=0; $i<count($_FILES['file']['name']); $i++ ) {
			
				// get temp file
				$tmpFile = $_FILES['file']['tmp_name'][$i];

				// if we could make tempfile...
				if($tmpFile != ""){

					$uploadfile = $uploaddir.basename($_FILES['file']['name'][$i]);
					// upload files to temp dir, move to target and check if successful!
					if (move_uploaded_file($tmpFile, $uploadfile)) {
						// first change attributes to allow deletion etc. later on
						chmod($uploadfile, 0757);
						echo("<h3>+ Datei ".$_FILES['file']['name'][$i]." hochgeladen.</h3>");
					} else {
						echo("<h3>--- Upload von ".$_FILES['file']['name'][$i]." fehlerhaft!</h3>");
					}

				}
			}
		}

		// all done, now go back to file listing!
		echo "<h1><a href=\"/?".$_POST["uploadDir"]."#list\"><button>Zur&uuml;ck zur Dateiliste!</button></a></h1>";
		echo "</body></html>";
		
		sleep(1);
	} else {
		echo "error";
	}

/******************************************************************************
 ** Show a file corresponding to its mime type
 */

} elseif ( $action == "showFile" ) {

	$filename = $_GET["filename"];
	$mimeType = mime_content_type($baseDir.$filename);

	if ($mimeType == "image/jpeg" || $mimeType == "image/png" || $mimeType == "image/gif" )  {
		
		 header('Content-Type: text/html');
		 echo("<html><head><link rel=\"stylesheet\" href=\"/css/custom.css\">".
		"</head><body><img src=\"/docs".$_SESSION["userdir"].$filename."\"></body></html>\r\n");

	} elseif ($mimeType == "application/pdf") {
		
		 if (file_exists($baseDir.$filename)) {
			header('Content-Type: application/pdf');
			header('Content-Disposition: inline; filename="'.basename($filename).'"');
			header('Content-Transfer-Encoding: binary');
			header('Accept-Ranges: bytes');
			ob_clean();
			flush();
			readfile($baseDir.$filename);
			fclose($baseDir.$filename);
		 }
		 
	} elseif ($mimeType == "text/html") {
		
		 if (file_exists($baseDir.$filename)) {
			header('Content-Type: text/html');
			ob_clean();
			flush();
			readfile($baseDir.$filename);
			fclose($baseDir.$filename);
		 }

	} elseif ($mimeType == "video/mp4") {
		
		 echo("<html><head><link rel=\"stylesheet\" href=\"/css/custom.css\">".
		"</head><body><video controls><source src=\"/docs/".$filename."\" type=\"video/mp4\">".
		"</video></body></html>\r\n");

	} elseif ($mimeType == "text/plain" || $mimeType == "text/x-shellscript") {
		
		 if (file_exists($baseDir.$filename)) {
			header('Content-Type: text/plain');
			ob_clean();
			flush();
			readfile($baseDir.$filename);
			fclose($baseDir.$filename);
		 }

	} else {
		
		 echo("Datei ".$baseDir.$filename.": Weiss net...\n".$mimeType."???");
		 
	}

/******************************************************************************
 ** Change language
 */

} elseif ( $action == "changeLanguage" ) {

	$_SESSION["language"] = $_GET["language"];

/******************************************************************************
 ** Change language
 */

} elseif ( $action == "getStrings" ) {

	// return some program infos

	$db = connect_db();
	// first read strings from table
	$result = $db->query('SELECT name,value FROM strings
								 WHERE language = '.$_SESSION["language"]);
	$retArray = array();
	while ($row = $result->fetch()) {
		$retArray[$row[0]] = $row[1];
	}
	$retArray["userrights"]=$_SESSION["userrights"];
	header("Content-type: application/json");
	echo json_encode( $retArray );

/******************************************************************************
 ** This is it!
 */

} else {
	echo "No suited action found!";
}

?>
