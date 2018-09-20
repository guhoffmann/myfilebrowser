<?php

/*                  - myfilebrowser_functions.php -
 
   Global variables and functions for MyFileBrowser http file explorer.
   
                         (C) guhoffmann 2018 -
*/

// Determine base data dirs depending on used system

$systemOs = "local";

if ($systemOs == "Android") {
    $baseDir ="/data/data/com.termux/files/home/myfilebrowser/docs";
} elseif ($systemOs == "Pi") {
    $baseDir = "/home/pi/extern/data/proftpd/files/docs";
} elseif ($systemOs == "local") {
    $baseDir = "/home/uwe";
}

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

?>
