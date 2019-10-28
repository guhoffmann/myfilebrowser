<?php

/*                    - submitnew.php -

    Submit new user data from admin page of http file explorer.
   
                   - (C) guhoffmann 2019 -
*/

$username = $_POST['username'];
$homedir = $_POST['homedir'];
$rights = $_POST['rights'];
$password = $_POST['password'];
//$id = $_POST['id'];
include '../cgi-bin/myFunctions.php';
$db = connect_db();
$db->exec('INSERT INTO users (name, homedir, rights, password)
			VALUES ("'.$username.'", "'.$homedir.'", '.$rights.', "'.$password.'");');

// go back to main admin pagae
header('Location: main.php');

?>
