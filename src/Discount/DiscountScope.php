<?php

declare(strict_types=1);

namespace App\Discount;

enum DiscountScope: string
{
    case All = 'ALL';
    case Specific = 'SPECIFIC';
}
