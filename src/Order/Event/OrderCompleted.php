<?php

declare(strict_types=1);

namespace App\Order\Event;

final readonly class OrderCompleted
{
    public function __construct(
        public int $orderId,
        public int $userId,
    ) {
    }
}
