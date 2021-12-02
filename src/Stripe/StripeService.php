<?php

namespace App\Stripe;

use App\Entity\Purchase;

class StripeService
{
    protected $secreteKey;
    protected $publicKey;

    public function __construct(string $secreteKey, string $publicKey)
    {

        $this->secreteKey = $secreteKey;
        $this->publicKey = $publicKey;
    }

    public function getPubicKey(): string
    {
        return $this->publicKey;
    }

    public function getPaymentIntent(Purchase $purchase)
    {
        \Stripe\Stripe::setApiKey($this->secreteKey);

        return \Stripe\PaymentIntent::create([
            'amount' => $purchase->getTotal(),
            'currency' => 'eur'
        ]);
    }
}
