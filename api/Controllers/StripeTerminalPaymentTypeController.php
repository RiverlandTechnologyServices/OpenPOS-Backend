<?php

namespace OpenPOS\Controllers;

use OpenPOS\Controllers\BaseController;
use OpenPOS\Controllers\BaseControllerInterface;

class StripeTerminalPaymentTypeController extends BaseController implements BaseControllerInterface
{
    public function post(array $args): void
    {
        parent::post($args);
        try {
            $newPaymentType = \OpenPOS\Models\PaymentTypes\StripeTerminalPaymentType::Create($this->postBody["name"], $this->postBody["readableName"], $this->postBody["organisationId"], $this->postBody["locationId"], $this->postBody["registrationCode"]);
            $this->success($newPaymentType->toArray());
        } catch (\OpenPOS\Common\OpenPOSException $e) {
            $this->error($e->getPublicCode());
            \OpenPOS\Common\Logger::error($e);
        }
    }
}