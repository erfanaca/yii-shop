<?php

declare(strict_types=1);

namespace App\Order;

enum SimulatedPaymentResult: string
{
    case Success = 'success';
    case Failure = 'failure';
}
