<?php
/**
 *  $DESCRIPTION$ $END$
 * @name SessionTokensController.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Controllers;

class SessionTokensController extends BaseController implements BaseControllerInterface
{
    public function get(array $args): void
    {
        parent::get($args);

        try {
            $sessionUser = \OpenPOS\Models\Account\UserSummaryModel::Find($this->sessionToken);
            $userID = $args[0];

            if($sessionUser->getID() == $userID)
            {
                $sessionTokens = \OpenPOS\Models\Account\SessionTokensModel::Find($userID);
                $this->success($sessionTokens->toArray());
            }
            else
            {
                throw new \OpenPOS\Common\OpenPOSException('Unauthorised access to resource', "SessionTokensController", "no_permission", "no_permission");
            }
        } catch (\OpenPOS\Common\OpenPOSException $e) {
            $this->error($e->getPublicCode());
            \OpenPOS\Common\Logger::error($e);
        }


    }
}