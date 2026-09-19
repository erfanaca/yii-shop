<?php

declare(strict_types=1);

namespace App\Discount;

use App\Cart\CartService;

final readonly class CartDiscountService
{
    public function __construct(
        private CartService $cartService,
        private DiscountCodeRepository $discountCodes,
        private DiscountCodeEvaluator $evaluator,
    ) {
    }

    /**
     * @param array<int, array<string, mixed>>|null $items
     */
    public function getPricing(int $userId, ?array $items = null): CartPricing
    {
        $items ??= $this->cartService->getItems($userId);
        $subtotalAmount = $this->calculateSubtotal($items);
        $discountCodeId = $this->cartService->getAppliedDiscountCodeId($userId);

        if ($discountCodeId === null) {
            return new CartPricing(
                subtotalAmount: $subtotalAmount,
                discountAmount: '0.00',
                totalAmount: $subtotalAmount,
            );
        }

        $discountCode = $this->discountCodes->findById($discountCodeId);

        if ($discountCode === null) {
            $this->cartService->removeDiscountCode($userId);

            return new CartPricing(
                subtotalAmount: $subtotalAmount,
                discountAmount: '0.00',
                totalAmount: $subtotalAmount,
            );
        }

        try {
            $evaluation = $this->evaluator->evaluate($discountCode, $userId, $items);
        } catch (DiscountApplicationException $exception) {
            return new CartPricing(
                subtotalAmount: $subtotalAmount,
                discountAmount: '0.00',
                totalAmount: $subtotalAmount,
                discountCode: $discountCode,
                discountError: $exception->getMessage(),
            );
        }

        return $this->fromEvaluation($evaluation);
    }

    /**
     * @param array<int, array<string, mixed>>|null $items
     */
    public function apply(int $userId, string $code, ?array $items = null): CartPricing
    {
        $normalizedCode = strtoupper(trim($code));

        if ($normalizedCode === '') {
            throw new DiscountApplicationException('Enter a discount code.');
        }

        $discountCode = $this->discountCodes->findByCode($normalizedCode);

        if ($discountCode === null) {
            throw new DiscountApplicationException('Discount code was not found.');
        }

        $items ??= $this->cartService->getItems($userId);
        $evaluation = $this->evaluator->evaluate($discountCode, $userId, $items);

        $this->cartService->applyDiscountCode($userId, $discountCode->getId());

        return $this->fromEvaluation($evaluation);
    }

    public function remove(int $userId): void
    {
        $this->cartService->removeDiscountCode($userId);
    }

    /**
     * Used by checkout so an invalid/stale code can never produce a stale payable amount.
     *
     * @param array<int, array<string, mixed>> $items
     */
    public function requireValidPricing(int $userId, array $items): CartPricing
    {
        $pricing = $this->getPricing($userId, $items);

        if ($pricing->getDiscountCode() !== null && $pricing->getDiscountError() !== null) {
            throw new DiscountApplicationException($pricing->getDiscountError());
        }

        return $pricing;
    }

    private function fromEvaluation(DiscountEvaluation $evaluation): CartPricing
    {
        return new CartPricing(
            subtotalAmount: $evaluation->getSubtotalAmount(),
            discountAmount: $evaluation->getDiscountAmount(),
            totalAmount: $evaluation->getTotalAmount(),
            discountCode: $evaluation->getDiscountCode(),
            eligibleSubtotalAmount: $evaluation->getEligibleSubtotalAmount(),
            eligibleProductIds: $evaluation->getEligibleProductIds(),
        );
    }

    /** @param array<int, array<string, mixed>> $items */
    private function calculateSubtotal(array $items): string
    {
        $minorUnits = 0;

        foreach ($items as $item) {
            $minorUnits += $this->toMinorUnits((string) $item['unit_price']) * (int) $item['quantity'];
        }

        return sprintf('%d.%02d', intdiv($minorUnits, 100), $minorUnits % 100);
    }

    private function toMinorUnits(string $amount): int
    {
        $normalized = trim($amount);

        if (!preg_match('/^\d+(?:\.\d{1,2})?$/', $normalized)) {
            throw new DiscountApplicationException('Invalid product price in cart.');
        }

        [$whole, $fraction] = array_pad(explode('.', $normalized, 2), 2, '');
        $fraction = str_pad($fraction, 2, '0');

        return ((int) $whole * 100) + (int) substr($fraction, 0, 2);
    }
}
