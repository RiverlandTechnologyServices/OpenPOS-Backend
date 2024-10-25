<?php
/**
 *  Controller for the '/users' endpoint
 * @name UsersController.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Controllers;

class UsersController extends BaseController implements BaseControllerInterface
{
    public function get(array $args): void
    {
        parent::get($args);

        try {
            $sessionUser = \OpenPOS\Models\Account\UserSummaryModel::Find($this->sessionToken);
            $sessionPermissions = \OpenPOS\Models\Account\RoleModel::Find($sessionUser->getRoleID());
            $userName = $_GET["userName"] ?? "";
            $organisationID = $_GET['organisationID'] ?? "";
            $roleID = $_GET['roleID'] ?? "";
            $globalRoleID = $_GET['globalRoleID'] ?? "";
            $enabled = $_GET['enabled'] ?? "";
            $searchTerm = $_GET['searchTerm'] ?? "";

            if($sessionUser->getOrganisationID() == $organisationID && $sessionPermissions->canReadUsers())
            {
                $users = \OpenPOS\Models\Account\UsersModel::Find($userName, $organisationID, $roleID, $globalRoleID, $enabled, $searchTerm);
                $this->success($users->toArray());
            }
            else
            {
                throw new \OpenPOS\Common\OpenPOSException('Unauthorised access to resource', "UsersController", "no_permission", "no_permission");
            }
        } catch (\OpenPOS\Common\OpenPOSException $e) {
            $this->error($e->getPublicCode());
            \OpenPOS\Common\Logger::error($e);
        }


    }
}