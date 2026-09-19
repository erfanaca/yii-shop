<?php

declare(strict_types=1);

namespace App\Discount;

enum DiscountType: string
{
    case Percentage = 'PERCENTAGE';
    case Fixed = 'FIXED';
}
