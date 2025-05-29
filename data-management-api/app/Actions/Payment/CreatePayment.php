<?php

namespace App\Actions\Payment;

use App\Models\Payment;

final class CreatePayment
{
    static function handle(array $data): Payment
    {
        return Payment::create($data);
    }
}
