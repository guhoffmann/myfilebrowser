<?php
/*                  - myfilebrowser_functions.php -
 
   Global variables and functions for MyFileBrowser http file explorer.
   
                         (C) guhoffmann 2018 -
*/

/* $baseDir must be the symbolic link 'docs' to the file exchange directory!
   If it doesn't exist, you must manually create the link in the
   websites main directory. */
$baseDir = $_SERVER["DOCUMENT_ROOT"]."/docs";

/******************************************************************************
 ** Return a connection to the sqlite database
 */

function connect_db() {

	return new PDO("sqlite:".$_SERVER["DOCUMENT_ROOT"]."/myfilebrowser.db");

}

/******************************************************************************
 ** Remove path or files recursively
 */

function delete_files($target) {

	if (is_file($target) ) {
		unlink($target);
	} else if (is_dir($target)) {
		$i = new DirectoryIterator($target);
		foreach($i as $f) {
			if( $f->isFile()  ) {
				unlink($f->getRealPath());
			} else if(!$f->isDot() && $f->isDir()) {
				delete_files($f->getRealPath());
			}
		}
		rmdir($target);
	}// of is_dir($target)...*/	
}

/******************************************************************************
 ** Human readable filesize
 */

function formatSize($bytes, $decimals = 2) {
    $factor = floor((strlen($bytes) - 1) / 3);
    if ($factor > 0) $sz = 'KMGT';
    return sprintf("%.{$decimals}f ", $bytes / pow(1024, $factor)) . @$sz[$factor - 1] . 'B';
}

/******************************************************************************
 ** Check for unwanted URL!
 */

function checkUrl($url): int {

	if ( preg_match("[\.\.]",$url) > 0 ) {

		return 0; // URL not allowed!

	} else {
		return 1; // URL allowed
	}
}

?>
