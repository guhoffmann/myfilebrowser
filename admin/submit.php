<?php

/*                     - submit.php -

  Submit changed user data from admin page of http file explorer.
   
                   - (C) guhoffmann 2019 -
*/

$username = $_POST['username'];
$homedir = $_POST['homedir'];
$rights = $_POST['rights'];
$password = $_POST['password'];
$id = $_POST['id'];
include '../cgi-bin/myFunctions.php';
$db = connect_db();
$db->exec('UPDATE users 
			SET name = "'.$username.'",
				homedir = "'.$homedir.'",
				rights = '.$rights.',
				password = "'.$password.'"
			WHERE id = '.$id);
//echo $username."</br>".$homedir."</br>".$rights."</br>";

// go back to main admin pagae
header('Location: main.php');

?>
