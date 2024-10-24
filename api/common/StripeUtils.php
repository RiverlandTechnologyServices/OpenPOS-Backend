<?php

namespace OpenPOS\Common;

class StripeUtils
{
    static protected self $instance;
    protected \Stripe\StripeClient $stripeClient;
    public function getClient(): \Stripe\StripeClient
    {
        return $this->stripeClient;
    }

    public static function GetInstance(): self
    {
        if(self::$instance == null)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct()
    {
        $this->stripeClient = new \Stripe\StripeClient(STRIPE_LIVE_KEY);
    }
}