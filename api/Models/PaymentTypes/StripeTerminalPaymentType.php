<?php

namespace OpenPOS\Models\PaymentTypes;

use OpenPOS\Common\OpenPOSException;
use OpenPOS\Models\BaseModelInterface;
use OpenPOS\Models\Organisation\OrganisationModel;

class StripeTerminalPaymentType extends PaymentTypeModel implements BaseModelInterface
{
    /**
     * @param string $name
     * @param string $readableName
     * @param string $organisationId
     * @param string $locationId
     * @return StripeTerminalPaymentType
     * @throws OpenPOSException
     */
    public static function Create(string $name = "", string $readableName = "", string $organisationId = "", string $locationId = "", string $registrationCode = ""): self
    {

        $reader = \OpenPOS\Common\StripeUtils::GetInstance()->getClient()->terminal->readers->create([
            "registration_code" => $registrationCode,
            "label" => $readableName,
            "location" => $locationId,
        ],
        [
            "stripe_account" => OrganisationModel::Find($organisationId)->getStripeAccountId(),
        ]);
        if(!$reader)
        {
            throw new OpenPOSException("Unable to create StripeTerminal Reader", "StripeTerminalPaymentType", "create_stripe_terminal", "internal_error");
        }
        return (parent::CreatePaymentType($name, $readableName, $organisationId, $locationId, PaymentType::Card, array("provider" => "stripe", "reader_id" => $reader->id, "reader_type" => $reader->device_type)));
    }
}