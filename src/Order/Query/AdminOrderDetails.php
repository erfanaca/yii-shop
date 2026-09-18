<?php

declare(strict_types=1);

namespace App\Order\Query;

use App\Order\OrderStatus;
use DateTimeImmutable;

final readonly class AdminOrderDetails
{
    /**
     * @param OrderItemSummary[] $items
     */
    public function __construct(
        private int $id,
        private int $userId,
        private string $userEmail,
        private OrderStatus $status,
        private string $totalAmount,
        private string $transactionNumber,
        private string $invoiceNumber,
        private DateTimeImmutable $createdAt,
        private ?DateTimeImmutable $updatedAt,
        private array $items,
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

    public function getUserEmail(): string
    {
        return $this->userEmail;
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

    /**
     * @return OrderItemSummary[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
