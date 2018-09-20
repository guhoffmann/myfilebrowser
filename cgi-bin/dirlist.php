<?php

include 'myfilebrowser_functions.php';

$relDir = urldecode($_GET["pathname"]);

header("Content-type: text/html");

if ( $relDir=="" OR $relDir=="/" ) {
    echo("<h4 class='padding-start'>Hauptordner</h4>\r\n<table>\r\n");
} else {
    echo("<h4 class='padding-start'>".substr($relDir,2)."</h4>\r\n");
    // create dirup entry if not in document root!
    echo("<table>\r\n<tr><td class='folder' colspan='4' style=\"width:2em\">".
        "<a href='?".dirname($relDir)."#list'><i class='material-icons'>arrow_upward</i> .. ( Verzeichnis zur&uuml;ck )</a></td></tr>\r\n");
}

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
				"<input type='checkbox' name='fileaction' value='".$relDirAktFile."' />".
				"</td><td class='folder' colspan='3' style=\"width:2em\"><a href='?".
		         $relDirAktFile."#list'><i class='material-icons'>folder</i>\r\n".
				 $dirList[$i]."</a></td>\n";
				 
	} elseif ( is_file( $absDirAktFile ) ) {
		
		// Aaah, we found it's a file, so...

		$fileSize = formatSize(filesize($absDirAktFile));
                $fileDate = date("d.m.Y  H:i:s", filemtime($absDirAktFile));
		
		$files .= "<tr><td class='direntry' style='width:2em; text-align: center;' valign='top'> \r\n".
				"<input type='checkbox' name='fileaction' value='".$relDirAktFile."' />".
				"</td><td class='direntry'>".
				  "<a href='/cgi-bin/showFile.php?filename=".$relDirAktFile."'><span class='white'>".
			  	$dirList[$i]."</span></br><span class='blue5'>".$fileDate."&nbsp; ".$fileSize."</span></a><td class='direntry' style='width:3em; text-align: center;' >".
				  "<a href='/cgi-bin/downloadFile.php?filename=".$relDirAktFile.
				  "'><i class='material-icons green'>cloud_download</i></a></td>\n";
			      
	}
}

echo($dirs);
echo($files);

?>
