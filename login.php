<?php
session_start();
/*                           - login.php -
 
            Login worker for MyFileBrowser http(s) file explorer.
   
                         (C) guhoffmann 2019

		   This page is called by the index.php of the web app!
*/

$uname = $_POST['username'];
$password = $_POST['password'];

header("Content-type: text/html");

include 'cgi-bin/myFunctions.php';

// fetch login infos from db
$db = connect_db();
$result = $db->query('SELECT password,homedir,rights FROM users WHERE name ="'.$uname.'" ');
$row = $result->fetch();

if ( $result && password_verify($password ,$row[0]) ) {

	// start session and go to first page
	$db = null;
	$_SESSION["username"] = $uname;
	$_SESSION["userdir"] = $row[1];
	$_SESSION["userrights"] = $row[2];
	header('Location: main.php');
	
} else {
	echo '
	<head>
		<title>Login</title>
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
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<form action="login.php" method="post">
				<h2><img src="favicon-32x32.png" /> MyFilebrowser</h2></br>
				<h3><i class="material-icons">lock</i> Login Error!</h3>
				<p>Please try again!</br>
				Bitte noch einmal versuchen!</p>
				<p><input type="text" placeholder="name" name="username" required></p>
				<p><input type="password" placeholder="password" name="password" required></p>
				<button type="submit"><i class="material-icons">touch_app</i> Enter</button>
				</form>
			</div>
		</div>
	</div>
	</body>
	</html>';

}
?>
