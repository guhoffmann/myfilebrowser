<?php  

// These are needed to destroy session variables
session_start();
session_destroy();
//$_SESSION["memory"] = array();

header('Location: /');

?>
