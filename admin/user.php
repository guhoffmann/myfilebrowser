<?php

/*                     - user.php -

  Managing a user as admin with MyFileBrowser http file explorer.
   
                   (C) guhoffmann 2018 -
*/

$userid = $_GET['id'];
include '../cgi-bin/myFunctions.php';
$db = connect_db();
$result = $db->query('SELECT * FROM users WHERE id = '.$userid);
$row = $result->fetch();

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

echo '
	<!-- ******************************************************* -->
	<!--       The app contents are rendered to div id="app"     -->

	<div class="container">
	  <div class="row">
		<div class="col-sm-12">
			<div id="app">
			<h3>Administration of user '.$row[2].'</h3>
			<form action="submit.php" method="post">
			<input type="hidden" name="id" value="'.$row[2].'" readonly></br>
			<p>Name:</p>
			<input type="text" name="username" value="'.$row[0].'" required></br></br>
			<p>Homedir:</p>
			<input type="text" name="homedir" value="'.$row[3].'" required></br></br>
			<p>Rights:</p>
			<input type="number" min="0" max="63" step="1" name="rights" value="'.$row[4].'" required></br></br>
			<p>Password:</p>
			<input type="text" name="password" value="'.$row[1].'" required></br></br>
			<button type="submit"><i class="material-icons">check_circle</i>Submit</button>
			<button type="button" onclick="location.href='.htmlentities("\"main.php\"").'"><i class="material-icons">clear</i>Cancel</button>
			</form>';
?>

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

