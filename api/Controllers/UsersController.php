<?php
/**
 *  Controller for the '/users' endpoint
 * @name UsersController.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Controllers;

use OpenPOS\Common\Logger;
use OpenPOS\Common\OpenPOSException;
use OpenPOS\Models\PermissionsModel;
use OpenPOS\Models\UsersModel;
use OpenPOS\Models\UserSummaryModel;

class UsersController extends BaseController implements BaseControllerInterface
{
    public function get(array $args): void
    {
        parent::get($args);

        try {
            $sessionUser = UserSummaryModel::Find($this->sessionToken);
            $sessionPermissions = PermissionsModel::Find($sessionUser->getRoleID());
            $userName = $_GET["userName"] ?? "";
            $organisationID = $_GET['organisationID'] ?? "";
            $roleID = $_GET['roleID'] ?? "";
            $globalRoleID = $_GET['globalRoleID'] ?? "";
            $enabled = $_GET['enabled'] ?? "";
            $searchTerm = $_GET['searchTerm'] ?? "";

            if($sessionUser->getOrganisationID() == $organisationID && $sessionPermissions->canReadUsers())
            {
                $users = UsersModel::Find($userName, $organisationID, $roleID, $globalRoleID, $enabled, $searchTerm);
                $this->success($users->toArray());
            }
            else
            {
                throw new OpenPOSException('Unauthorised access to resource', "UsersController", "no_permission", "no_permission");
            }
        } catch (OpenPOSException $e) {
            $this->error($e->getPublicCode());
            Logger::error($e);
        }


    }
}