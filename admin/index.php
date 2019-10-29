<?php
/*                           - login.php -
 
            Login worker for MyFileBrowser http(s) file explorer.
   
                         (C) guhoffmann 2019

		   This page is called by the index.php of the web app!
*/

$uname = $_POST['username'];
$password = $_POST['password'];

header("Content-type: text/html");

include '../cgi-bin/myFunctions.php';

// fetch login infos from db
$db = connect_db();
$result = $db->query('SELECT password, rights FROM users WHERE name ="'.$uname.'" ');
$row = $result->fetch();

if ( $result && password_verify($password ,$row[0]) && ($row[1] & 32) != 0  ) {

	$db = null;
	header('Location: main.php');
	
} else {
	echo '
	<head>
		<title>Login</title>
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
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<form action="index.php" method="post">
				<h2><i class="material-icons">supervisor_account</i> MyFilebrowser Administration</h2></br>
	';
	
	if ( $uname == "" ) {
		echo '
				<h3><i class="material-icons">lock</i> Admin-Login</h3>
		';
	} else {
		echo '
				<h3><i class="material-icons">lock</i> Admin-Login Error!</h3>
				<p>Please try again!</br>
				Bitte noch einmal versuchen!</p>
		';
	}
	echo '
				<p><input type="text" placeholder="name" name="username" required></p>
				<p><input type="password" placeholder="password" name="password" required></p>
				<button type="submit"><i class="material-icons">touch_app</i> Enter</button>
				</form>
			</div>
		</div>
	</div>
	<script src="../js/jquery-3.3.1.min.js"></script>
		<!-- disable back button -->
	<script>
		$(document).ready(function() {
		function disableBack() { window.history.forward() }
		window.onload = disableBack();
		window.onpageshow = function(evt) { if (evt.persisted) disableBack() }
		});
	</script>
	</body>
	</html>';

}
?>
