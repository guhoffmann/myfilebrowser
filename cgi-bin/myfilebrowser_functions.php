<?php

/*                  - myfilebrowser_functions.php -
 
   Global variables and functions for MyFileBrowser http file explorer.
   
                         (C) guhoffmann 2018 -
*/

// $baseDir must be the symbolic link 'docs' to the file exchange directory!
// If it doesn't exist, you must manually create the link in the
// websites main directory.
$baseDir = $_SERVER["DOCUMENT_ROOT"]."/docs";

// Remove path or files recursively

function delete_files($target) {
    if(is_dir($target)){
        $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

        foreach( $files as $file ){
            delete_files( $file );      
        }

        rmdir( $target );
    } elseif(is_file($target)) {
        unlink( $target );  
    }
}

// Human readable filesize

function formatSize($bytes, $decimals = 2) {
    $factor = floor((strlen($bytes) - 1) / 3);
    if ($factor > 0) $sz = 'KMGT';
    return sprintf("%.{$decimals}f ", $bytes / pow(1024, $factor)) . @$sz[$factor - 1] . 'B';
}

// Check for unwanted URL!

function checkUrl($url): int {

	if ( preg_match("[\.\.]",$url) > 0 ) {

		return 0; // URL not allowed!

	} else {
		return 1; // URL allowed
	}
}

?>
