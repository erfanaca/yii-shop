<?php

declare(strict_types=1);

namespace App\Order;

enum OrderStatus: string
{
    case Pending = 'PENDING';
    case Paid = 'PAID';
    case Cancelled = 'CANCELLED';
}
