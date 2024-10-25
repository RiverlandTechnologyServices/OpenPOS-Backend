<?php
/**
 *  $DESCRIPTION$ $END$
 * @name SessionTokenController.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Controllers;


class SessionTokenController extends BaseController implements BaseControllerInterface
{
    public function get(array $args): void
    {
        parent::get($args);
        try {
            $sessionUser = \OpenPOS\Models\Account\UserSummaryModel::Find("", "", $this->sessionToken);
            $requestedUser = \OpenPOS\Models\Account\UserModel::Find("", "", $args[0]);
            $requestedToken = \OpenPOS\Models\Account\SessionTokenModel::Find($args[1]);

            if($sessionUser->getID() == $requestedUser->getID())
            {
                $this->success($requestedToken->toArray());
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
            $newSession = \OpenPOS\Models\Account\SessionTokenModel::Create($this->postBody["email"], $this->postBody["password"]);
            $this->success($newSession->toArray());
        } catch (\OpenPOS\Common\OpenPOSException $e) {
            $this->error($e->getPublicCode());
            \OpenPOS\Common\Logger::error($e);
        }
    }

    public function delete(array $args): void
    {
        parent::delete($args);
    }
}