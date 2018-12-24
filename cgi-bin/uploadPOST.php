<!DOCTYPE html>
<html lang="en">

<head>
  <title>File Browser</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/custom.css">
</head>

<h1>Datei(en) hochladen:</h1>

<?php 

include 'myfilebrowser_functions.php';

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
echo("<h1><a href=\"/?".$_POST["uploadDir"]."#list\");'><button>Zur&uuml;ck zur Dateiliste!</button></a></h1>");

?>

</body>
</html>
