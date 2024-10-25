<?php
/**
 *  $DESCRIPTION$ $END$
 * @name UserController.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Controllers;

class UserController extends BaseController implements BaseControllerInterface
{
    public function get(array $args): void
    {
        parent::get($args);
        try {
            $sessionUser = \OpenPOS\Models\Account\UserSummaryModel::Find("", "", $this->sessionToken);
            $sessionPermissions = \OpenPOS\Models\Account\RoleModel::Find($sessionUser->getRoleID());
            $requestedUser = \OpenPOS\Models\Account\UserModel::Find($args[0]);

            if(($sessionUser->getOrganisationID() == $requestedUser->getOrganisationID() && $sessionPermissions->canReadUsers()) || $sessionUser->getID() == $requestedUser->getID())
            {
                $this->success($requestedUser->toArray());
            }
            else
            {
                throw new \OpenPOS\Common\OpenPOSException('Unauthorised access to resource', "UserController", "no_permission", "no_permission");
            }
        } catch (\OpenPOS\Common\OpenPOSException $e) {
            $this->error($e->getPublicCode());
            \OpenPOS\Common\Logger::error($e);
        }
    }

    public function post(array $args): void
    {
        parent::post($args);
        try {
            $newUser = \OpenPOS\Models\Account\UserModel::Create($this->postBody["userName"], $this->postBody["email"], $this->postBody["password"], $this->postBody["organisationID"], $this->postBody["roleID"], "user", $this->postBody["enabled"]);
            $this->success($newUser->toArray());
        } catch (\OpenPOS\Common\OpenPOSException $e) {
            $this->error($e->getPublicCode());
            \OpenPOS\Common\Logger::error($e);
        }
    }

    public function put(array $args): void
    {
        parent::put($args);
    }

    public function delete(array $args): void
    {
        parent::delete($args);
    }
}