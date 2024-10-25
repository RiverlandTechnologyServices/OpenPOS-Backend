<?php

namespace OpenPOS\Models\Organisation;

use OpenPOS\Common\OpenPOSException;
use OpenPOS\Common\StripeUtils;
use OpenPOS\Models\BaseDatabaseModel;
use OpenPOS\Models\BaseModelInterface;

class OrganisationModel extends BaseDatabaseModel implements BaseModelInterface
{
    protected string $name;
    protected string $id;
    protected string $stripeAccountId;


    public static function Find(): OrganisationModel
    {
        // TODO: Implement Find() method.
    }

    public static function Create(): OrganisationModel
    {
        // TODO: Implement Create() method.
    }

    public function toArray(): array
    {
        // TODO: Implement toArray() method.
    }

    /**
     * @throws OpenPOSException
     */
    public function createStripeConnectionToken(): \Stripe\Terminal\ConnectionToken
    {
        try {
            return StripeUtils::GetInstance()->getClient()->terminal->connectionTokens->create(["stripe_account" => $this->stripeAccountId]);
        }
        catch (\Exception $e) {
            throw new OpenPOSException($e->getMessage(), "OrganisationModel", "create_connection_token", "internal_error");
        }
    }
}