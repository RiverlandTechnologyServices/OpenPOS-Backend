<?php
/**
 *  $DESCRIPTION$ $END$
 * @name SessionTokenController.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Controllers;

use OpenPOS\Common\Logger;
use OpenPOS\Common\OpenPOSException;
use OpenPOS\Models\Account\SessionTokenModel;
use OpenPOS\Models\Account\UserModel;
use OpenPOS\Models\Account\UserSummaryModel;
use OpenPOS\Models\PermissionsModel;

class SessionTokenController extends BaseController implements BaseControllerInterface
{
    public function get(array $args): void
    {
        parent::get($args);
        try {
            $sessionUser = UserSummaryModel::Find($this->sessionToken);
            $requestedUser = UserModel::Find($args[0]);
            $requestedToken = SessionTokenModel::Find($args[1]);

            if($sessionUser->getID() == $requestedUser->getID())
            {
                $this->success($requestedToken->toArray());
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
            $newSession = SessionTokenModel::Create($this->postBody["email"], $this->postBody["password"]);
            $this->success($newSession->toArray());
        } catch (OpenPOSException $e) {
            $this->error($e->getPublicCode());
            Logger::error($e);
        }
    }

    public function delete(array $args): void
    {
        parent::delete($args);
    }
}