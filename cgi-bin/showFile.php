<?php

include 'myfilebrowser_functions.php';
$filename = $_GET["filename"];
$mimeType = mime_content_type($baseDir.$filename);

if ($mimeType == "image/jpeg" || $mimeType == "image/png" || $mimeType == "image/gif" )  {
	
    header('Content-Type: text/html');
    echo("<html><head><link rel=\"stylesheet\" href=\"/css/custom.css\">".
	"</head><body><img src=\"/docs/".$filename."\"></body></html>\r\n");
	
} elseif ($mimeType == "image/png") {
	
    echo("<html><head><link rel=\"stylesheet\" href=\"/css/custom.css\">".
	"</head><body><img src=\"/docs/".$filename."\"></body></html>\r\n");

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
	
} else {
	
    echo("Datei ".$baseDir.$filename.": Weiss net...\n".$mimeType."???");
    
}

?>
