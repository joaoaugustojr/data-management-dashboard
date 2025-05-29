<?php

namespace App\Actions\Payment;

use App\Models\Payment;

final class UpdatePayment
{
    static function handle(Payment $payment, array $data): Payment
    {
        $payment->update($data);
        return $payment;
    }
}
