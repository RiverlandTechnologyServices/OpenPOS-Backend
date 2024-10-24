<?php

namespace OpenPOS\Controllers;

use OpenPOS\Controllers\BaseController;
use OpenPOS\Controllers\BaseControllerInterface;

class PaymentTypeController extends BaseController implements BaseControllerInterface
{
    public function get(array $args): void
    {
        parent::get($args);
        try {
            $sessionUser = \OpenPOS\Models\UserSummaryModel::Find("", "", $this->sessionToken);
            $sessionPermissions = \OpenPOS\Models\PermissionsModel::Find($sessionUser->getRoleID());
            $requestedPaymentType = \OpenPOS\Models\PaymentTypes\PaymentTypeModel::Find($args[0]);

            if($sessionUser->getOrganisationID() == $requestedPaymentType->getOrganisationID() && $sessionPermissions->canReadPaymentTypes())
            {
                $this->success($requestedPaymentType->toArray());
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
}