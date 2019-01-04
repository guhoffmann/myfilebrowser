<?php
session_start();

/*                     - index.php -

      Start file for MyFileBrowser http file explorer.
   
                   (C) guhoffmann 2018 -
*/

// Initialize all session variables here

// Start with German language!
if ( !isset($_SESSION["language"]) ) {
	$_SESSION["language"] = "1";
}

// Necessary to keep clipboard alive:
// only clear if not existing!!!
// 'clipboard' must be named 'memory' to not be confounded
// with the language string 'clipboard'!!!
if ( !isset($_SESSION["memory"]) ) { 
	$_SESSION["memory"] = array();
}

include('cgi-bin/myFunctions.php');

// Now fetch all language dependent Strings for menu and main page!

$db = connect_db();
$result = $db->query('SELECT name,value FROM strings WHERE language = '.$_SESSION["language"]);

while ($row = $result->fetch()) {
	
	$_SESSION[$row[0]] = $row[1];

}

echo '
<!DOCTYPE html>
<html lang="en">

<head>
	<title>'.$_SESSION["progname"].'</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/custom.css">
	<!-- Insert the icons -->
	<link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">

</head>

<body>

	<!-- ******************************************************* -->
	<!-- Bootstrap Modal Window used for all messages in the app -->

	<div class="modal fade" id="ModalMessage" tabindex="10" role="dialog" aria-labelledby="ModalTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">

				<div class="modal-header">
					<h5 class="modal-title" id="ModalTitle">Nachricht</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div> <!-- modal-header -->

				<!-- section for value input -->
				<input class="hidden" type="text" id="inputval" name="foldername" value="">

				<!-- section for the upload -->
				<form id="upload" action="cgi-bin/actions.php" method="post" enctype="multipart/form-data"> 
					<input type="file" name="file[]" id="fileinput" class="inputfile" data-multiple-caption="{count} Dateien hochladen" multiple="multiple"/>
               <!-- label MUST follow file input immediately to work! -->
               <label id="filebuttlabel" class="filebuttlabel" for="fileinput">Datei(en) ausw&auml;hlen...</label>
               <!-- hidden value for upload directory -->
               <input id="uploadDir" type="hidden" value="path to upload" name="uploadDir" />
					<input id="action" type="hidden" value="uploadPost" name="action" />

				</form>

				<!-- section for modal window content -->
				<div class="modal-body" id="ModalContent">
				</div>

				<!-- section for the modal window footer -->
				<div class="modal-footer">
					<button type="button" id="ModalOk" class="material-icons btn btn-primary" data-dismiss="modal">done</button>
					<button type="button" id="ModalClose" class="material-icons btn btn-primary" data-dismiss="modal">clear</button>
				</div>
				
			</div>
		</div>
	</div>

	<!-- ******************************************************* -->
	<!--          The navbar for the whole HTML app              -->

	<nav class="navbar navbar-dark fixed-top my-nav-bg">

		<!-- the dropdown main menu -->
		<div class="dropdown show">
			<a class="btn btn-primary dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<span class="material-icons">build</span>&nbsp;'.$_SESSION["actions"].'
			</a>
			<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
				<div class="dropdown-item" onclick="createFolder()">
					<span class="material-icons">create_new_folder</span>
					&nbsp;'.$_SESSION["create_folder"].'
				</div>
				<div class="dropdown-item" onclick="uploadDialog(\'Hochladen\')">
					<span class="material-icons">cloud_upload</span>
					&nbsp;'.$_SESSION["upload_files"].'
				</div>
				<div class="dropdown-item" onclick="downloadFiles()">
					<span class="material-icons">cloud_download</span>
					&nbsp;'.$_SESSION["download_as_zip"].'
				</div>
				<div class="dropdown-item" onclick="deleteFiles()">
					<span class="material-icons">delete_forever</span>
					&nbsp;'.$_SESSION["delete_selected"].'
				</div>
				<div class="dropdown-divider"></div>
				<div class="dropdown-item" onclick="copyFiles()">
					<span class="material-icons">library_add</span>
					&nbsp;'.$_SESSION["add_to_clipboard"].'
				</div>
				<div class="dropdown-item" onclick="pasteFiles()">
					<span class="material-icons">assignment_returned</span>
					&nbsp;'.$_SESSION["paste_clipboard"].'
				</div>
				<div class="dropdown-item" onclick="clearClipboard()">
					<span class="material-icons">delete_forever</span>
					&nbsp;'.$_SESSION["clear_clipboard"].'
				</div>
				<div class="dropdown-item" onclick="showClipboard()">
					<span class="material-icons">assignment</span>
					&nbsp;'.$_SESSION["show_clipboard"].'
				</div>
				<div class="dropdown-divider"></div>
			<!--
				<div class="dropdown-item" onclick="testAjaxPhp("Testausgabe!")">
					<span class="material-icons">info</span>
					&nbsp;testAjaxPhp!
				</div> -->
				<div class="dropdown-item" onclick="infoDialog()">
					<span class="material-icons">info</span>
					&nbsp; '.$_SESSION["show_infos"].'
				</div>
			<!--	
				<div class="dropdown-item" onclick="phpInfo()">
					<span class="material-icons">info</span>
					&nbsp; PHP-Info
				</div> -->
			</div>
		</div> <!-- dropdown show -->

		<!-- Link to documents main folder -->
		<a class="navbar-brand" href="/"><i class="material-icons">home</i>&nbsp;Home</a>
		<!-- the lanuage menu -->
		<div class="dropdown show">
			<a class="btn btn-primary dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<span class="material-icons">language</span>&nbsp;'.$_SESSION["select_language"].'
			</a>
			<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';

$result = $db->query('SELECT * FROM languages');

while ($row = $result->fetch()) {
	echo '<div class="dropdown-item" onClick="changeLanguage('.$row[2].')">&nbsp;'.$row[0].'</div>';
}

echo '			</div>
		</div> <!-- dropdown show/language menu -->
	</nav>

  	<!-- ******************************************************* -->
	<!--       The app contents are rendered to div id="app"     -->

	<div class="container">
	  <div class="row">
		<div class="col-sm-12">
			<div id="app">
			</div>
		</div>
	  </div>
	</div>

	<!-- the footer -->
	<!--
	<footer class="footer">
		<div id="footer" class="container" style="margin-bottom:0">
    		- (C) Gert-Uwe Hoffmann 2018 -
    	</div>
	</footer>
-->

	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/router.js"></script>
	<script src="js/myFunctions.js"></script>

</body>
</html>'

?>
