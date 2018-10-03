<?php

/*                           - info.php -
 
   Put out a page with some relevant infos about MyFileBrowser.
   
                         (C) guhoffmann 2018 -
*/

include 'myfilebrowser_functions.php';

header("Content-type: text/html");

$clientIp = $_SERVER['REMOTE_ADDR'];
echo("<table><tr><td colspan='2' class='info'>Einfache Dateiverwaltung f&uuml;r entfernte Daten.</br></br></td></tr>");
echo("<tr><td class='right info'>Server-Software:</td><td class='left info'>".$_SERVER['SERVER_SOFTWARE']."</td></tr>");
echo("<tr><td class='right info'>Server-Name/-IP:</td><td class='left info'>".$_SERVER['SERVER_NAME']." ".$_SERVER['SERVER_ADDR']."</td></tr>");
echo("<tr><td class='right info'>Port:</td><td class='left info'>".$_SERVER['SERVER_PORT']."</td></tr>" );
echo("<tr><td class='right info'>Client-IP:</td><td class='left info'>".$_SERVER['REMOTE_ADDR']."</td></tr>");
echo("<tr><td colspan='2' class='info'></br>(C) Gert-Uwe Hoffmann 2018</td></tr></table>");

?>
