<?php

/*                           - info.php -
 
   Put out a page with some relevant infos about MyFileBrowser.
   
                         (C) guhoffmann 2018 -
*/

include 'myfilebrowser_functions.php';

header("Content-type: text/html");

$clientIp = $_SERVER['REMOTE_ADDR'];
echo("<p class='info'>Einfache Dateiverwaltung f&uuml;r entfernte Daten.</p>");
echo("<table><tr><td class='right info'>Software:</td><td class='left info'>".$_SERVER['SERVER_SOFTWARE']."</td></tr>");
echo("<tr><td class='right info'>Server:</td><td class='left info'>".$_SERVER['SERVER_NAME']." ".$_SERVER['SERVER_ADDR']."</td></tr>");
echo("<tr><td class='right info'>Port:</td><td class='left info'>".$_SERVER['SERVER_PORT']."</td></tr>" );
echo("<tr><td class='right info'>Client:</td><td class='left info'>".$_SERVER['REMOTE_ADDR']."</td></tr></table>" );
echo("<p class='info'>(C) Gert-Uwe Hoffmann 2018</p>");

?>
