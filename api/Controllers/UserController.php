<?php
/**
 *  $DESCRIPTION$ $END$
 * @name UserController.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Controllers;

use OpenPOS\Common\Logger;
use OpenPOS\Common\OpenPOSException;
use OpenPOS\Models\PermissionsModel;
use OpenPOS\Models\UserModel;
use OpenPOS\Models\UsersModel;
use OpenPOS\Models\UserSummaryModel;

class UserController extends BaseController implements BaseControllerInterface
{
    public function get(array $args): void
    {
        parent::get($args);
        try {
            $sessionUser = UserSummaryModel::Find($this->sessionToken);
            $sessionPermissions = PermissionsModel::Find($sessionUser->getRoleID());
            $requestedUser = UserModel::Find($args[0]);

            if(($sessionUser->getOrganisationID() == $requestedUser->getOrganisationID() && $sessionPermissions->canReadUsers()) || $sessionUser->getID() == $requestedUser->getID())
            {
                $this->success($requestedUser->toArray());
            }
            else
            {
                throw new OpenPOSException('Unauthorised access to resource', "UserController", "no_permission", "no_permission");
            }
        } catch (OpenPOSException $e) {
            $this->error($e->getPublicCode());
            Logger::error($e);
        }

    }

    public function post(array $args): void
    {
        parent::post($args);
        try {
            $newUser = UserModel::Create($this->postBody["userName"], $this->postBody["email"], $this->postBody["password"], $this->postBody["organisationID"], $this->postBody["roleID"], "user", $this->postBody["enabled"]);
            $this->success($newUser->toArray());
        } catch (OpenPOSException $e) {
            $this->error($e->getPublicCode());
            Logger::error($e);
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