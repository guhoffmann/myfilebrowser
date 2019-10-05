<?php

/*                           - login.php -
 
            Login worker for MyFileBrowser http(s) file explorer.
   
                         (C) guhoffmann 2019

		   This page is called by the index.php of the web app!
*/

$uname = $_POST['username'];
$password = $_POST['password'];
header("Content-type: text/html");

include 'cgi-bin/myFunctions.php';

// Now fetch all language dependent Strings for menu and main page!
$db = connect_db();
$result = $db->query('SELECT password,homedir FROM users WHERE name ="'.$uname.'" ');
$row = $result->fetch();

if ( $result && password_verify($password ,$row[0]) ) {

	// start session and go to first page
	session_start();
	$_SESSION["username"] = $uname;
	$_SESSION["userdir"] = $row[1];
	header('Location: main.php');;
	
} else {
	echo '
	<html>
	<body>
		<h1>-'.$uname.'- Login error, try again:</h1>
		<form action="login.php" method="post">
		<table>
		<p><label for="username">
		<tr>
			<td><b>Username</b></label></td>
			<td><input type="text" placeholder="Enter Username" name="username" required></td>
		</tr>
		<tr>
			<td><label for="password"><b>Password</b></label></td>
			<td><input type="password" placeholder="Enter Password" name="password" required></td>
		</tr>
		<tr><td></td><td><button type="submit">Login</button></td></tr>
		</table>
		</form> 
	</body>
	</html>';

}
?>
