<?php
/**
 *  $DESCRIPTION$ $END$
 * @name StripeTerminalPaymentTypeModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models\PaymentMethods;

use OpenPOS\Models\BaseModelInterface;
use OpenPOS\Models\Organisation\OrganisationModel;
use OpenPOS\Models\PaymentMethods\PaymentTypeModel;

class StripeTerminalPaymentTypeModel extends PaymentTypeModel implements BaseModelInterface
{
    protected string $id;
    protected string $accountID;
    protected string $organisationID;
    protected string $name;

    public static function Create(string $organisationID = ""): StripeTerminalPaymentTypeModel
    {
        $organisation = OrganisationModel::Find($organisationID);

    }


}