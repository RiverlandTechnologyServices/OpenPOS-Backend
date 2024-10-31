<?php

namespace OpenPOS\Models\Organisation;

use OpenPOS\Common\OpenPOSException;
use OpenPOS\Common\StripeUtils;
use OpenPOS\Models\Account\RoleModel;
use OpenPOS\Models\Account\UserModel;
use OpenPOS\Models\BaseDatabaseModel;
use OpenPOS\Models\BaseModelInterface;

class OrganisationModel extends BaseDatabaseModel implements BaseModelInterface
{
    protected string $id;
    public function getID(): string
    {
        return $this->id;
    }
    protected string $organisationID;
    public function getOrganisationID(): string
    {
        return $this->organisationID;
    }
    protected string $name;
    public function getName(): string
    {
        return $this->name;
    }
    protected string $stripeAccountID;
    public function getStripeAccountID(): string
    {
        return $this->stripeAccountID;
    }


    /**
     * @throws OpenPOSException
     */
    public static function Find($id = "", $organisationID = ""): OrganisationModel
    {
        $requestedOrganisation = new OrganisationModel();
        if($id)
        {
            $stmt = (new \OpenPOS\Common\SQLQuery())->select(["id", "organisationID", "name", "stripeAccountID"])->from("organisations")->where()->variableName("id")->equals()->variable($id);
        }
        else if($organisationID)
        {
            $stmt = (new \OpenPOS\Common\SQLQuery())->select(["id", "organisationID", "name", "stripeAccountID"])->from("organisations")->where()->variableName("organisationID")->equals()->variable($organisationID);
        }
        else
        {
            throw new \OpenPOS\Common\OpenPOSException("Failed to provide ID, Email, or SessionToken", "OrganisationModel", "no_token", "no_token");
        }

        //$data = ($this->execute("SELECT userName, email, sessionTokens, role, globalRole, enabled, userSettings, organisationID FROM users WHERE id = ?", [$id]))[0];

        if($data = \OpenPOS\Common\DatabaseManager::getInstance()->execute($stmt)->fetch_assoc()[0])
        {
            $requestedOrganisation->id = $data["id"];
            $requestedOrganisation->organisationID = $data["organisationID"];
            $requestedOrganisation->name = $data["name"];
            $requestedOrganisation->stripeAccountID = $data["stripeAccountID"];
            return $requestedOrganisation;
        }
        else
        {
            throw new \OpenPOS\Common\OpenPOSException("No Organisation with ID found", "OrganisationModel", "no_user", "no_user");
        }
    }

    /**
     * @throws OpenPOSException
     */
    public static function Create($name = "", $organisationID = "", $userName = "", $email = "", $password = ""): UserModel
    {
        $stripeAccount = "";
        try {
            $account = \OpenPOS\Common\StripeUtils::GetInstance()->getClient()->accounts->create([
                'controller' => [
                    'stripe_dashboard' => [
                        'type' => 'none',
                    ],
                ],
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true],
                ],
                'country' => "AU",
            ]);
            $stripeAccount = $account->id;
        }
        catch (\Exception $e)
        {
            throw new OpenPOSException($e->getMessage(), "OrganisationModel", $e->getCode(), "internal_error");
        }

        if(!$name)
        {
            throw new OpenPOSException("Failed to provide Organisation Name", "OrganisationModel", "insufficient_inputs", "insufficient_inputs");
        }



        $stmt = (new \OpenPOS\Common\SQLQuery())->select(["id"])->from("organisations")->where()->variableName("organisationID")->equals()->variable($organisationID);
        $result = \OpenPOS\Common\DatabaseManager::getInstance()->execute($stmt);
        if($result->num_rows != 0)
        {
            throw new OpenPOSException("Organisation already exists", "OrganisationModel", "organisation_already_exists", "organisation_already_exists");
        }


        $stmt = (new \OpenPOS\Common\SQLQuery())->insertInto("organisations", ["name", "organisationID", "stripeAccountID"], [$name, $organisationID, $stripeAccount]);
        $result = \OpenPOS\Common\DatabaseManager::getInstance()->execute($stmt);
        if(!$result)
        {
            throw new OpenPOSException("Failed to create new Organisation!", "OrganisationModel", "create_fail", "internal_error");
        }


        $organisation = OrganisationModel::Find("", $organisationID);
        $role = \OpenPOS\Models\Account\RoleModel::Create("admin", $organisation->getID(),\OpenPOS\Models\Account\PermissionValues::ReadWrite, \OpenPOS\Models\Account\PermissionValues::ReadWrite);
        return UserModel::Create($userName, $email, $password, $organisation->getID(), $role->getID());
    }

    public function toArray(): array
    {
        return array(
            "id" => $this->id,
            "name" => $this->name,
            "organisationID" => $this->organisationID,
            "stripeAccountID" => $this->stripeAccountID,
        );
    }

    /**
     * @throws OpenPOSException
     */
    public function createStripeConnectionToken(): \Stripe\Terminal\ConnectionToken
    {
        try {
            return StripeUtils::GetInstance()->getClient()->terminal->connectionTokens->create(["stripe_account" => $this->stripeAccountID]);
        }
        catch (\Exception $e) {
            throw new OpenPOSException($e->getMessage(), "OrganisationModel", "create_connection_token", "internal_error");
        }
    }
}