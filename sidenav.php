<?php
session_start();
/*	This one includes the sidebar for navigation
	on the left side of the document!
*/
?>

<!-- Der Navbar links! -->

<div id="mySidenav" class="sidenav">
	<div class="sidenavbartop">
		<span class="sidenavtitle"><i class="material-icons">build</i>&nbsp;<?php echo $_SESSION["actions"]; ?></span>
		<button type="button" class="btn btn-primary closebtn" onclick="closeNav()"><i class="material-icons">close</i></button>
	</div>
	<div class="scrolldiv">

		<div class="sidenaventry" onclick="closeNav();createFolder()">
			<?php	echo '<span class="material-icons">create_new_folder</span>&nbsp;'.$_SESSION["create_folder"]; ?>
		</div>
		<div class="sidenaventry" onclick="closeNav();uploadDialog('Upload')">
			<?php	echo '<span class="material-icons">cloud_upload</span>&nbsp;'.$_SESSION["upload_files"]; ?>
		</div>
		<div class="sidenaventry" onclick="closeNav();downloadFiles()">
			<?php	echo '<span class="material-icons">cloud_download</span>&nbsp;'.$_SESSION["download_as_zip"]; ?>
		</div>
		<div class="sidenaventry" onclick="closeNav();deleteFiles()">
			<?php	echo '<span class="material-icons">delete_forever</span>&nbsp;'.$_SESSION["delete_selected"]; ?>
		</div>
		<hr>
		<div class="sidenaventry" onclick="closeNav();copyFiles()">
			<?php	echo '<span class="material-icons">library_add</span>&nbsp;'.$_SESSION["add_to_clipboard"]; ?>
		</div>
		<div class="sidenaventry" onclick="closeNav();pasteFiles()">
			<?php	echo '<span class="material-icons">assignment_returned</span>&nbsp;'.$_SESSION["paste_clipboard"]; ?>
		</div>
		<div class="sidenaventry" onclick="closeNav();clearClipboard()">
			<?php	echo '<span class="material-icons">delete_forever</span>&nbsp;'.$_SESSION["clear_clipboard"]; ?>
		</div>
		<div class="sidenaventry" onclick="closeNav();showClipboard()">
			<?php	echo '<span class="material-icons">assignment</span>&nbsp;'.$_SESSION["show_clipboard"]; ?>
		</div>
		<hr>
		<div class="sidenaventry" onclick="closeNav();infoDialog()">
			<?php	echo '<span class="material-icons">info</span>&nbsp;'.$_SESSION["show_infos"]; ?>
		</div>
		<div class="sidenaventry" onclick="closeNav();location.href = '#help' ">
			<?php	echo '<span class="material-icons">info</span>&nbsp;'.$_SESSION["show_help"]; ?>
		</div>
<!--		<div class="sidenaventry" onclick="closeNav();phpInfo()">
			<?php	echo '<span class="material-icons">info</span>&nbsp;PHP-Info'; ?>
		</div> -->
		</br></br>
	</div><!-- scrolldiv -->
</div>

<!-- Das war Navbar links! -->

