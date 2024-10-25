<?php
/**
 *  Common Imports and Settings
 * @name bootstrap.inc.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

/************************
 * Constant Definitions *
 ************************/

/**
 * Root path for the OpenPOS Backend Source
 * @type string
 */
const SOURCE_ROOT_PATH = __DIR__ . "/../";

/**
 * Root path for the OpenPOS Backend Project
 */
const PROJECT_ROOT_PATH = __DIR__ . "/../../";

/***********
 * Imports *
 ***********/

function includeDir($path) {
    $dir      = new RecursiveDirectoryIterator($path);
    $iterator = new RecursiveIteratorIterator($dir);
    foreach ($iterator as $file) {
        $fname = $file->getFilename();
        if (preg_match('%\.php$%', $fname)) {
            require_once($file->getPathname());
        }
    }
}

require_once SOURCE_ROOT_PATH . "Controllers/BaseController.php";
require_once SOURCE_ROOT_PATH . "Models/BaseModel.php";
require_once PROJECT_ROOT_PATH . "config.inc.php";
includeDir(SOURCE_ROOT_PATH);
require_once PROJECT_ROOT_PATH . "vendor/autoload.php";