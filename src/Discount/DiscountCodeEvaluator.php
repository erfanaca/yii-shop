<?php

declare(strict_types=1);

namespace App\Discount;

final readonly class DiscountCodeEvaluator
{
    public function __construct(
        private DiscountCodeRepository $discountCodes,
    ) {
    }

    /**
     * @param array<int, array<string, mixed>> $items
     */
    public function evaluate(DiscountCode $discountCode, int $userId, array $items): DiscountEvaluation
    {
        if ($items === []) {
            throw new DiscountApplicationException('Your cart is empty.');
        }

        $this->assertUserIsEligible($discountCode, $userId);

        $subtotalMinorUnits = 0;
        $eligibleSubtotalMinorUnits = 0;
        $eligibleProductIds = [];
        $allowedProductIds = $discountCode->getProductScope() === DiscountScope::Specific
            ? array_fill_keys($this->discountCodes->findProductIds($discountCode->getId()), true)
            : null;

        foreach ($items as $item) {
            $productId = (int) $item['product_id'];
            $quantity = (int) $item['quantity'];

            if ($quantity <= 0) {
                throw new DiscountApplicationException('Cart contains an invalid item quantity.');
            }

            $lineTotalMinorUnits = $this->toMinorUnits((string) $item['unit_price']) * $quantity;
            $subtotalMinorUnits += $lineTotalMinorUnits;

            $isEligible = $allowedProductIds === null || isset($allowedProductIds[$productId]);

            if (!$isEligible) {
                continue;
            }

            $eligibleSubtotalMinorUnits += $lineTotalMinorUnits;
            $eligibleProductIds[$productId] = $productId;
        }

        if ($eligibleSubtotalMinorUnits <= 0) {
            throw new DiscountApplicationException(
                'This discount code does not apply to any products currently in your cart.',
            );
        }

        $discountMinorUnits = match ($discountCode->getType()) {
            DiscountType::Percentage => $this->calculatePercentageDiscount(
                $discountCode,
                $eligibleSubtotalMinorUnits,
            ),
            DiscountType::Fixed => $this->calculateFixedDiscount(
                $discountCode,
                $subtotalMinorUnits,
                $eligibleSubtotalMinorUnits,
            ),
        };

        $discountMinorUnits = min($discountMinorUnits, $eligibleSubtotalMinorUnits, $subtotalMinorUnits);
        $totalMinorUnits = max(0, $subtotalMinorUnits - $discountMinorUnits);

        return new DiscountEvaluation(
            discountCode: $discountCode,
            subtotalAmount: $this->fromMinorUnits($subtotalMinorUnits),
            eligibleSubtotalAmount: $this->fromMinorUnits($eligibleSubtotalMinorUnits),
            discountAmount: $this->fromMinorUnits($discountMinorUnits),
            totalAmount: $this->fromMinorUnits($totalMinorUnits),
            eligibleProductIds: array_values($eligibleProductIds),
        );
    }

    private function assertUserIsEligible(DiscountCode $discountCode, int $userId): void
    {
        if ($discountCode->getUserScope() === DiscountScope::All) {
            return;
        }

        if (!in_array($userId, $this->discountCodes->findUserIds($discountCode->getId()), true)) {
            throw new DiscountApplicationException('This discount code is not available for your account.');
        }
    }

    private function calculatePercentageDiscount(
        DiscountCode $discountCode,
        int $eligibleSubtotalMinorUnits,
    ): int {
        $percentageBasisPoints = $this->percentageToBasisPoints($discountCode->getValue());
        $discountMinorUnits = intdiv($eligibleSubtotalMinorUnits * $percentageBasisPoints, 10_000);
        $maxDiscount = $discountCode->getMaxDiscountAmount();

        if ($maxDiscount !== null) {
            $discountMinorUnits = min($discountMinorUnits, $this->toMinorUnits($maxDiscount));
        }

        return $discountMinorUnits;
    }

    private function calculateFixedDiscount(
        DiscountCode $discountCode,
        int $subtotalMinorUnits,
        int $eligibleSubtotalMinorUnits,
    ): int {
        $minimumOrderAmount = $discountCode->getMinimumOrderAmount();

        if ($minimumOrderAmount !== null) {
            $minimumMinorUnits = $this->toMinorUnits($minimumOrderAmount);

            if ($subtotalMinorUnits < $minimumMinorUnits) {
                throw new DiscountApplicationException(
                    sprintf(
                        'This discount code requires a minimum order amount of %s.',
                        $this->fromMinorUnits($minimumMinorUnits),
                    ),
                );
            }
        }

        return min(
            $this->toMinorUnits($discountCode->getValue()),
            $eligibleSubtotalMinorUnits,
        );
    }

    private function toMinorUnits(string $amount): int
    {
        $normalized = trim($amount);

        if (!preg_match('/^\d+(?:\.\d{1,2})?$/', $normalized)) {
            throw new DiscountApplicationException('Invalid monetary amount.');
        }

        [$whole, $fraction] = array_pad(explode('.', $normalized, 2), 2, '');
        $fraction = str_pad($fraction, 2, '0');

        return ((int) $whole * 100) + (int) substr($fraction, 0, 2);
    }

    private function percentageToBasisPoints(string $percentage): int
    {
        $normalized = trim($percentage);

        if (!preg_match('/^\d+(?:\.\d{1,2})?$/', $normalized)) {
            throw new DiscountApplicationException('Invalid percentage value.');
        }

        [$whole, $fraction] = array_pad(explode('.', $normalized, 2), 2, '');
        $fraction = str_pad($fraction, 2, '0');

        $basisPoints = ((int) $whole * 100) + (int) substr($fraction, 0, 2);

        if ($basisPoints <= 0 || $basisPoints > 10_000) {
            throw new DiscountApplicationException('Invalid percentage value.');
        }

        return $basisPoints;
    }

    private function fromMinorUnits(int $amount): string
    {
        return sprintf('%d.%02d', intdiv($amount, 100), $amount % 100);
    }
}
