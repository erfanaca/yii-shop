<?php

declare(strict_types=1);

namespace App\Order\Query;

use App\Order\OrderStatus;
use DateTimeImmutable;

final readonly class OrderSummary
{
    /**
     * @param OrderItemSummary[] $items
     */
    public function __construct(
        private int $id,
        private OrderStatus $status,
        private string $totalAmount,
        private ?string $discountCode,
        private string $discountAmount,
        private string $transactionNumber,
        private string $invoiceNumber,
        private DateTimeImmutable $createdAt,
        private array $items,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStatus(): OrderStatus
    {
        return $this->status;
    }

    public function getTotalAmount(): string
    {
        return $this->totalAmount;
    }

    public function getDiscountCode(): ?string
    {
        return $this->discountCode;
    }

    public function getDiscountAmount(): string
    {
        return $this->discountAmount;
    }

    public function hasDiscount(): bool
    {
        return $this->discountCode !== null && trim($this->discountCode) !== '';
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

    /**
     * @return OrderItemSummary[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
