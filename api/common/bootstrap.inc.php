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

require_once SOURCE_ROOT_PATH . "Controllers/BaseController.php";
require_once SOURCE_ROOT_PATH . "Models/BaseModel.php";