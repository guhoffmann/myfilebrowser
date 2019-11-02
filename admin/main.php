<?php

/*                     - main.php -

  Start file for MyFileBrowser http file explorer admin page.
   
                   (C) guhoffmann 2018 -
*/

// Initialize session variables here

// Start with German language!
if ( !isset($_SESSION["language"]) ) {
	$_SESSION["language"] = "1";
}

include '../cgi-bin/myFunctions.php';

// Now fetch all language dependent Strings for menu and main page!
$db = connect_db();
$result = $db->query('SELECT name,value FROM strings WHERE language = '.$_SESSION["language"]);
while ($row = $result->fetch()) {
	$_SESSION[$row[0]] = $row[1];
}

// Now fetch the languages clear name:
$result = $db->query('SELECT name FROM languages WHERE value = '.$_SESSION["language"]);
$_SESSION["language_string"] = $result->fetch()[0];

echo '<!DOCTYPE html>
<html lang="en">

<head>
	<title>Admin</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/custom.css">
	<!-- Insert the icons -->
	<link rel="apple-touch-icon" sizes="76x76" href="../apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
	<link rel="manifest" href="../site.webmanifest">
	<link rel="mask-icon" href="../safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">
</head>
<body>
';

// include windows section
include '../modalWindow.php';

echo '
	<!-- ******************************************************* -->
	<!--       The app contents are rendered to div id="app"     -->

	<div class="container">
	  <div class="row">
		<div class="col-sm-12">
			<div id="app">
			<h2>Admin Page</h2>
			<h3>User list</h3>';
$result = $db->query('SELECT * FROM users ORDER BY name ASC');
echo '<table><col width="10%"><col width="80%"><col width="10%">
		<tr><td>name:</td><td>homedir:</td><td>rights:</td></tr>';
while ($row = $result->fetch()) {
	echo '<tr class="direntry"><td onclick="location.href='.
	htmlentities("\"user.php?id=".$row[2]."\"").'">'.$row[0].'</td><td onclick="location.href='.
	htmlentities("\"user.php?id=".$row[2]."\"").'">'.$row[3].'</td><td onclick="location.href='.
	htmlentities("\"user.php?id=".$row[2]."\"").'">'.$row[4].
	'</td><td onclick="deleteUser('.$row[2].')"><i class="material-icons blue5">delete_forever</i></td>
	</tr>';
}
?>
				</table>
				</br>
				<button onclick="location.href='newuser.php'"><i class="material-icons">add_circle</i>New User</button>
				<button onclick="location.href='index.php'"><i class="material-icons">touch_app</i>Logout</button>
			</div>
		</div>
	  </div>
	</div>
	<script src="../js/jquery-3.3.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/myFunctions.js"></script>
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

