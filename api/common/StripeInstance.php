<?php
/**
 *  $DESCRIPTION$ $END$
 * @name StripeInstance.php
 * @copyright 2024 Riverland Technology Services/OpenPOS
 */

namespace OpenPOS\Common;

class StripeInstance
{
    protected \Stripe\StripeClient $stripe;
    public function getStripeClient(): \Stripe\StripeClient
    {
        return $this->stripe;
    }

    static protected self $liveInstance;
    static protected self $testInstance;

    static public function getLiveInstance(): self
    {
        if (!isset(self::$liveInstance)) {
            self::$liveInstance = new self(false);
        }
        return self::$liveInstance;
    }

    static public function getTestInstance(): self
    {
        if (!isset(self::$testInstance)) {
            self::$testInstance = new self(true);
        }
        return self::$testInstance;
    }

    function __construct(bool $test = false)
    {
        $stripe = new \Stripe\StripeClient($test ? STRIPE_TEST_KEY : STRIPE_LIVE_KEY);
    }

}