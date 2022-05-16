<?php

namespace Buckaroo\Transaction;

use Buckaroo\Payload\TransactionResponse;
use Buckaroo\PaymentMethods\PaymentInterface;

class PayTransaction extends Transaction
{
    public function handle(): TransactionResponse
    {
        $paymentMethod = $this->getPaymentMethod();

        if(is_a($paymentMethod, PaymentInterface::class))
        {
            return $paymentMethod->pay($this->request);
        }

        throw new \Exception("This payment method doesn't support pay service action.");
    }
}