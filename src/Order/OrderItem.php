<?php

declare(strict_types=1);

namespace App\Order;

use DateTimeImmutable;

final class OrderItem
{
    public function __construct(
        private readonly int $id,
        private readonly int $orderId,
        private readonly ?int $productId,
        private readonly string $productTitle,
        private readonly int $quantity,
        private readonly string $unitPrice,
        private readonly DateTimeImmutable $createdAt,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
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

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
