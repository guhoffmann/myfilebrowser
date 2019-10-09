<?php
session_start();

/*                     - main.php -

      Start file for MyFileBrowser http file explorer.
   
                   (C) guhoffmann 2018 -
*/

// Initialize session variables here

// Start with German language!
if ( !isset($_SESSION["language"]) ) {
	$_SESSION["language"] = "1";
}

/* Necessary to keep clipboard alive: only clear if not existing!!!
 'clipboard' is named 'memory' not to be confused with language string 'clipboard'! */
if ( !isset($_SESSION["memory"]) ) { 
	$_SESSION["memory"] = array();
}

include 'cgi-bin/myFunctions.php';

// Now fetch all language dependent Strings for menu and main page!
$db = connect_db();
$result = $db->query('SELECT name,value FROM strings WHERE language = '.$_SESSION["language"]);
while ($row = $result->fetch()) {
	$_SESSION[$row[0]] = $row[1];
}

// Now fetch the languages clear name:
$result = $db->query('SELECT name FROM languages WHERE value = '.$_SESSION["language"]);
$_SESSION["language_string"] = $result->fetch()[0];

include 'header.php';
echo '<body>';

// include windows section
include 'modalWindow.php';

if ( $_SESSION["started"] == "started" ) {

	echo '

		<!-- ******************************************************* -->
		<!--          The navbar for the whole HTML app              -->

		<nav class="navbar navbar-dark fixed-top my-nav-bg">
			<!-- Neuer Menüknopp -->
			<button id="menubutton" type="button" class="btn btn-primary material-icons" onclick="openNav()">menu</button>';

	// include navbar on the left!
	include 'sidenav.php';

	echo $_SESSION["username"].':'.$_SESSION["started"].'
			<!-- Link to documents main folder -->
			<a href="/main.php?/#list"><div class="btn btn-primary material-icons">home</div></a>
			<!-- Link to logout -->
			<a href="/logout.php"><div class="btn btn-primary material-icons">cancel</div></a>
			<!-- the lanuage menu -->
			<div class="dropdown show">
				<a class="btn btn-primary dropdown-toggle" role="button" id="dropdownMenuLink"
					data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="material-icons">&nbsp;language&nbsp;</i><span></span>
				</a>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';

	$result = $db->query('SELECT * FROM languages');

	while ($row = $result->fetch()) {
		echo '<div class="dropdown-item" onClick="changeLanguage('.$row[2].')">&nbsp;'.$row[0].'</div>';
	}

	echo '	</div>&nbsp;
			</div> <!-- dropdown show/language menu -->
		</nav>
		<!-- ******************************************************* -->
		<!--       The app contents are rendered to div id="app"     -->

		<div class="container">
		  <div class="row">
			<div class="col-sm-12">
				<div id="app" onclick="closeNav()">
				</div>
			</div>
		  </div>
		</div>';
} else {
	
	echo '<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h3>Sorry!</h3>
				<p>You\'ve destroyed Session data, maybe you cleared the history?!</p>
				<p>No you must login again to use this site.</p>
				<button onclick="location.href=\'index.php\'"><i class="material-icons">touch_app</i> Enter</button>
			</div>
		</div>
	</div>';
}
?>

	<!-- the footer -->
	
	<footer class="footer hidden">
		<div class="progress">
			<div id="progress-bar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
		</div>
	</footer>

	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/router.js"></script>
	<script src="js/myFunctions.js"></script>
	<!-- disable back button -->
	<script>
		$(document).ready(function() {
		function disableBack() { window.history.forward() }
		window.onload = disableBack();
		window.onpageshow = function(evt) { if (evt.persisted) disableBack() }
		});
	</script>
</body>
</html>

