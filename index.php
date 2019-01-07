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

/* Necessary to keep clipboard alive: only clear if not existing!!!
 'clipboard' is named 'memory' to not be confused with language string 'clipboard'! */
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
<body>';

// include windows section
include 'modalWindow.php';

echo '

	<!-- ******************************************************* -->
	<!--          The navbar for the whole HTML app              -->

	<nav class="navbar navbar-dark fixed-top my-nav-bg">
		<!-- Neuer Menüknopp -->
		<button type="button" class="btn btn-primary" onclick="openNav()"><i class="material-icons">menu</i></button>';

// include navbar on the left!
include 'sidenav.php';

echo '		<!-- Link to documents main folder -->
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
			<div id="app" onclick="closeNav()">
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
