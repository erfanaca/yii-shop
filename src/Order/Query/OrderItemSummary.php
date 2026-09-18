<?php

declare(strict_types=1);

namespace App\Order\Query;

final readonly class OrderItemSummary
{
    public function __construct(
        private string $productTitle,
        private int $quantity,
        private string $unitPrice,
    ) {
    }

    public function getProductTitle(): string
    {
        return $this->productTitle;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getUnitPrice(): string
    {
        return $this->unitPrice;
    }

    public function getTotalAmount(): string
    {
        return number_format((float) $this->unitPrice * $this->quantity, 2, '.', '');
    }
}
