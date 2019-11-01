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

	$username = $_POST['username'];
	$homedir = $_POST['homedir'];
	//$rights = $_POST['rights'];
	$rights = $_POST['delete'] + $_POST['upload'] + $_POST['insert']
			+ $_POST['editnotice'] + $_POST['createfolder'] + $_POST['siteadmin'];
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
	//$rights = $_POST['rights'];
	$rights = $_POST['delete'] + $_POST['upload'] + $_POST['insert']
			+ $_POST['editnotice'] + $_POST['createfolder'] + $_POST['siteadmin'];
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
