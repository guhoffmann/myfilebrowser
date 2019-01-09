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

include 'header.php';
echo '<body>';

// include windows section
include 'modalWindow.php';

echo '

	<!-- ******************************************************* -->
	<!--          The navbar for the whole HTML app              -->

	<nav class="navbar navbar-dark fixed-top my-nav-bg">
		<!-- Neuer Menüknopp -->
		<button type="button" class="btn btn-primary material-icons" onclick="openNav()">menu</button>';

// include navbar on the left!
include 'sidenav.php';

echo '		<!-- Link to documents main folder -->
		<a href="/"><div class="btn btn-primary material-icons">home</div></a>
		<!-- the lanuage menu -->
		<div class="dropdown show">
			<a class="btn btn-primary dropdown-toggle material-icons" role="button" id="dropdownMenuLink"
				data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">&nbsp;language&nbsp;
			</a>
			<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';

$result = $db->query('SELECT * FROM languages');

while ($row = $result->fetch()) {
	echo '<div class="dropdown-item" onClick="changeLanguage('.$row[2].')">&nbsp;'.$row[0].'</div>';
}

echo '			</div>&nbsp;
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

</body>
</html>'

?>
