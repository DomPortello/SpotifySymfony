<?php

namespace App\Service;

use Stripe\PaymentIntent;
use Stripe\Stripe;

class Paiement
{

    private $secretApiKey;
    public function __construct(
    ){
       $this->secretApiKey = $_ENV['STRIP_SECRET_API_KEY'];
    }

    public function paiementIntent(float $amount)
    {
        $stripe = new Stripe();
        $stripe::setApiKey($this->secretApiKey);

        $paiementIntent = new PaymentIntent();
        return $paiementIntent::create([
           'amount' => $amount,
           'currency' => 'eur',
           'payment_method_types' => ['card']
        ]);
    }

}