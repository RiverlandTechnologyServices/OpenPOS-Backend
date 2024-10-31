<?php
/**
 *  Configuration for OpenPOS Backend
 * @name config.inc.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

/*************************
 * OpenPOS Configuration *
 *************************/

define("STRIPE_LIVE_KEY", $_ENV["STRIPE_LIVE_KEY"]);


/**************************
 * Database Configuration *
 **************************/
/**
 * Database Hostname
 * @type String
 */
define("DB_HOST", 'db');
/**
 * Database Username
 * @type string
 */
define("DB_USER", 'root');
/**
 * Database Password
 * @type string
 */
define("DB_PASS", $_ENV["MARIADB_ROOT_PASSWORD"]);
/**
 * Database Name
 * @type string
 */
define("DB_NAME", 'openpos');