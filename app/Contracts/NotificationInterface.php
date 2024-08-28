<?php

namespace App\Contracts;

use Illuminate\Validation\Rules\In;

interface NotificationInterface
{
    public function sendNotification(string $type, string $message, int $orderId);
}
