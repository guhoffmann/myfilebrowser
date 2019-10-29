<?php
/*                          - actions.php -
 
            'Event handler' for MyFileBrowser admin pages.
   
                         (C) guhoffmann 2019 -
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

	$username = $_POST['username'];
	$homedir = $_POST['homedir'];
	$rights = $_POST['rights'];
	$password = $_POST['password'];
	$id = $_POST['id'];
	$db = connect_db();
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
	$rights = $_POST['rights'];
	$password = $_POST['password'];
	//$id = $_POST['id'];
	$db = connect_db();
	$db->exec('INSERT INTO users (name, homedir, rights, password)
				VALUES ("'.$username.'", "'.$homedir.'", '.$rights.', "'.$password.'");');

	// go back to main admin pagae
	header('Location: main.php');

} elseif  ( $action == "deleteUser" ) {

	$userid = $_GET['id'];
	$db = connect_db();
	$db->exec('DELETE FROM users WHERE id = '.$userid.';');
	echo "DELETE FROM users WHERE id = ".$userid;
	// go back to main admin pagae
	//header('Location: main.php');
}
?>
