<?php
/**
 *  Public-facing endpoint file
 * @name index.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

$router = new \OpenPOS\Common\Router();
$router->RegisterRoute("/api/users", new \OpenPOS\Controllers\UsersController());
$router->RegisterRoute("/api/user/{userID}", new \OpenPOS\Controllers\UserController());
$router->RegisterRoute("/api/user", new \OpenPOS\Controllers\UserController());
$router->Route($_SERVER["REQUEST_URI"]);