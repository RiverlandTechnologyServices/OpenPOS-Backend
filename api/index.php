<?php
/**
 *  Public-facing endpoint file
 * @name index.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

require_once "common/bootstrap.inc.php";

$router = new \OpenPOS\Common\Router();
$router->RegisterRoute("api/v1/users", new \OpenPOS\Controllers\UsersController());
$router->RegisterRoute("api/v1/user/{userID}", new \OpenPOS\Controllers\UserController());
$router->RegisterRoute("api/v1/user", new \OpenPOS\Controllers\UserController());
$router->RegisterRoute("api/v1/sessionTokens", new \OpenPOS\Controllers\SessionTokensController());
$router->RegisterRoute("api/v1/sessionToken/{sessionToken}", new \OpenPOS\Controllers\SessionTokenController());
$router->RegisterRoute("api/v1/sessionToken", new \OpenPOS\Controllers\SessionTokenController());
$router->RegisterRoute("api/v1/organisation/{organisationID}", new \OpenPOS\Controllers\OrganisationController());
$router->RegisterRoute("api/v1/organisation", new \OpenPOS\Controllers\OrganisationController());
$router->Route($_SERVER["REQUEST_URI"]);