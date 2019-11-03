<?php
/*                          - actions.php -
 
            'Event handler' for MyFileBrowser admin pages.
   
                         (C) guhoffmann 2019 -

 Note: for some actions the user rights are defined by a byte value in the
 myfilebrowser.db database! It is defined as a byte value with the bits
 
	1	delete files or folders
	2	upload files
	4	insert from clipboard
	8	edit notices in folder
	16	create folders
	32	site admin
	64	reserved
	128	reserved
*/

/** Get the action parameter from the calling GET or POST request *************
 */

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
	$action =  $_POST["action"];
} else {
	$action =  urldecode($_GET["action"]);
}

include '../cgi-bin/myFunctions.php';

//=============================================================================
//=========================== action handlers =================================
//=============================================================================

/******************************************************************************
 ** Submit changes in user data
 */

if ( $action == "submit" ) {

	$db = connect_db();
	$result = $db->query('SELECT * FROM users WHERE name = '.$username);
	
	$username = $_POST['username'];
	$homedir = $_POST['homedir'];
	//$rights = $_POST['rights'];
	$rights = $_POST['delete'] + $_POST['upload'] + $_POST['insert']
			+ $_POST['editnotice'] + $_POST['createfolder'] + $_POST['siteadmin'];
	$password = $_POST['password'];
	$id = $_POST['id'];

	$db->exec('UPDATE users 
				SET name = "'.$username.'",
					homedir = "'.$homedir.'",
					rights = '.$rights.',
					password = "'.$password.'"
				WHERE id = '.$id);

	// go back to main admin pagae
	header('Location: main.php');

/******************************************************************************
 ** Submit new user data from admin page of http file explorer.
 */

} elseif  ( $action == "submitnew" ) {

	$username = $_POST['username'];
	$homedir = $_POST['homedir'];
	$rights = $_POST['delete'] + $_POST['upload'] + $_POST['insert']
			+ $_POST['editnotice'] + $_POST['createfolder'] + $_POST['siteadmin'];
	$password = $_POST['password'];
	$db = connect_db();
	
	// check if user exists
	
	$result = $db->query('SELECT count(*) FROM users WHERE name= "'.$username.'"');
	$row = $result->fetch();
	if ( $row[0] == 1 ) {
		echo '<head>
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
		<!-- ******************************************************* -->
		<!--       The app contents are rendered to div id="app"     -->

		<div class="container">
		  <div class="row">
			<div class="col-sm-12">
				<div id="app">
				<div align="center">
				<h3>ERROR, user &#39;'.$username.'&#39; already exists!</h3>
				<button type="button" onclick="location.href='.htmlentities("\"main.php\"").'"><i class="material-icons">undo</i> back to administration</button>
				</div>	
				</div>
			</div>
		  </div>
		</div>
		</body>
		</html>';
		
	// create new user if username doesn't exist
	
	} else {
		$db->exec('INSERT INTO users (name, homedir, rights, password)
				VALUES ("'.$username.'", "'.$homedir.'", '.$rights.', "'.$password.'");');
		// go back to main admin pagae
		header('Location: main.php');
	}

/******************************************************************************
 ** create new user password
 */
 
} elseif ( $action == "newPassword" ) {
	$chars = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$pwd = "";
	for ( $i = 0; $i < 9; $i++ ) {
		$pwd = $pwd.$chars[rand(0,61)];
	}
	header("Content-type: text/html");
	echo '<input type="text" name="password" value="'.password_hash($pwd,PASSWORD_DEFAULT).'" required readonly> '.$pwd;
 
/******************************************************************************
 ** delete user from list
 */
 
} elseif  ( $action == "deleteUser" ) {

	$userid = $_GET['id'];
	$db = connect_db();
	$db->exec('DELETE FROM users WHERE id = '.$userid.';');
	echo "DELETE FROM users WHERE id = ".$userid;
	// go back to main admin pagae
	//header('Location: main.php');
}
?>
