<?php
/**
 *  $DESCRIPTION$ $END$
 * @name SessionTokensController.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Controllers;

use OpenPOS\Common\Logger;
use OpenPOS\Common\OpenPOSException;
use OpenPOS\Controllers\BaseController;
use OpenPOS\Controllers\BaseControllerInterface;
use OpenPOS\Models\PermissionsModel;
use OpenPOS\Models\SessionTokensModel;
use OpenPOS\Models\UsersModel;
use OpenPOS\Models\UserSummaryModel;

class SessionTokensController extends BaseController implements BaseControllerInterface
{
    public function get(array $args): void
    {
        parent::get($args);

        try {
            $sessionUser = UserSummaryModel::Find($this->sessionToken);
            $userID = $args[0];

            if($sessionUser->getID() == $userID)
            {
                $sessionTokens = SessionTokensModel::Find($userID);
                $this->success($sessionTokens->toArray());
            }
            else
            {
                throw new OpenPOSException('Unauthorised access to resource', "SessionTokensController", "no_permission", "no_permission");
            }
        } catch (OpenPOSException $e) {
            $this->error($e->getPublicCode());
            Logger::error($e);
        }


    }
}