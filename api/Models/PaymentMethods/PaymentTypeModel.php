<?php
/**
 *  $DESCRIPTION$ $END$
 * @name PaymentTypeModel.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Models\PaymentMethods;

use OpenPOS\Models\BaseDatabaseModel;
use OpenPOS\Models\BaseModelInterface;

abstract class PaymentTypeModel extends BaseDatabaseModel implements BaseModelInterface
{
    abstract public function ProcessPayment(int $amount);


}