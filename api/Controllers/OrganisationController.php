<?php

namespace OpenPOS\Controllers;

use OpenPOS\Controllers\BaseController;
use OpenPOS\Controllers\BaseControllerInterface;

class OrganisationController extends BaseController implements BaseControllerInterface
{
    public function get(array $args): void
    {
        parent::get($args);
        try {
            $sessionUser = \OpenPOS\Models\Account\UserSummaryModel::Find("", "", $this->sessionToken);
            $sessionPermissions = \OpenPOS\Models\Account\RoleModel::Find($sessionUser->getRoleID());
            $requestedOrganisation = \OpenPOS\Models\Organisation\OrganisationModel::Find($args[0]);

            if(($sessionUser->getOrganisationID() == $requestedOrganisation->getID()))
            {
                $this->success($requestedOrganisation->toArray());
            }
            else
            {
                throw new \OpenPOS\Common\OpenPOSException('Unauthorised access to resource', "OrganisationController", "no_permission", "no_permission");
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
            $newUser = \OpenPOS\Models\Organisation\OrganisationModel::Create($this->postBody["organisationName"], $this->postBody["organisationID"], $this->postBody["userName"], $this->postBody["email"], $this->postBody["password"]);
            $this->success($newUser->toArray());
        } catch (\OpenPOS\Common\OpenPOSException $e) {
            $this->error($e->getPublicCode());
            \OpenPOS\Common\Logger::error($e);
        }
    }


}