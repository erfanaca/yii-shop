<?php

declare(strict_types=1);

namespace App\Order;

use DateTimeImmutable;

final class Order
{
    public function __construct(
        private readonly int $id,
        private readonly int $userId,
        private readonly OrderStatus $status,
        private readonly string $totalAmount,
        private readonly string $transactionNumber,
        private readonly string $invoiceNumber,
        private readonly DateTimeImmutable $createdAt,
        private readonly ?DateTimeImmutable $updatedAt,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getStatus(): OrderStatus
    {
        return $this->status;
    }

    public function getTotalAmount(): string
    {
        return $this->totalAmount;
    }

    public function getTransactionNumber(): string
    {
        return $this->transactionNumber;
    }

    public function getInvoiceNumber(): string
    {
        return $this->invoiceNumber;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
