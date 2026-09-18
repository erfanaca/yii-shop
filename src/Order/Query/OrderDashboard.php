<?php

declare(strict_types=1);

namespace App\Order\Query;

final readonly class OrderDashboard
{
    /**
     * @param OrderSummary[] $orders
     */
    public function __construct(
        private int $totalOrders,
        private int $paidOrders,
        private int $cancelledOrders,
        private int $pendingOrders,
        private string $totalPaidAmount,
        private array $orders,
    ) {
    }

    public function getTotalOrders(): int
    {
        return $this->totalOrders;
    }

    public function getPaidOrders(): int
    {
        return $this->paidOrders;
    }

    public function getCancelledOrders(): int
    {
        return $this->cancelledOrders;
    }

    public function getPendingOrders(): int
    {
        return $this->pendingOrders;
    }

    public function getTotalPaidAmount(): string
    {
        return $this->totalPaidAmount;
    }

    /**
     * @return OrderSummary[]
     */
    public function getOrders(): array
    {
        return $this->orders;
    }
}
