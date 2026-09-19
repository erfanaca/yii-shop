<?php

declare(strict_types=1);

namespace App\Discount;

final readonly class DiscountEvaluation
{
    /**
     * @param int[] $eligibleProductIds
     */
    public function __construct(
        private DiscountCode $discountCode,
        private string $subtotalAmount,
        private string $eligibleSubtotalAmount,
        private string $discountAmount,
        private string $totalAmount,
        private array $eligibleProductIds,
    ) {
    }

    public function getDiscountCode(): DiscountCode
    {
        return $this->discountCode;
    }

    public function getSubtotalAmount(): string
    {
        return $this->subtotalAmount;
    }

    public function getEligibleSubtotalAmount(): string
    {
        return $this->eligibleSubtotalAmount;
    }

    public function getDiscountAmount(): string
    {
        return $this->discountAmount;
    }

    public function getTotalAmount(): string
    {
        return $this->totalAmount;
    }

    /** @return int[] */
    public function getEligibleProductIds(): array
    {
        return $this->eligibleProductIds;
    }
}
