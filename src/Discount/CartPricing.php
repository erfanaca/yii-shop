<?php

declare(strict_types=1);

namespace App\Discount;

final readonly class CartPricing
{
    /**
     * @param int[] $eligibleProductIds
     */
    public function __construct(
        private string $subtotalAmount,
        private string $discountAmount,
        private string $totalAmount,
        private ?DiscountCode $discountCode = null,
        private ?string $eligibleSubtotalAmount = null,
        private array $eligibleProductIds = [],
        private ?string $discountError = null,
    ) {
    }

    public function getSubtotalAmount(): string
    {
        return $this->subtotalAmount;
    }

    public function getDiscountAmount(): string
    {
        return $this->discountAmount;
    }

    public function getTotalAmount(): string
    {
        return $this->totalAmount;
    }

    public function getDiscountCode(): ?DiscountCode
    {
        return $this->discountCode;
    }

    public function getEligibleSubtotalAmount(): ?string
    {
        return $this->eligibleSubtotalAmount;
    }

    /** @return int[] */
    public function getEligibleProductIds(): array
    {
        return $this->eligibleProductIds;
    }

    public function getDiscountError(): ?string
    {
        return $this->discountError;
    }

    public function hasDiscount(): bool
    {
        return $this->discountCode !== null && $this->discountError === null;
    }

    public function isProductEligible(int $productId): bool
    {
        return in_array($productId, $this->eligibleProductIds, true);
    }
}
