<?php

declare(strict_types=1);

namespace App\Discount;

use App\Admin\User\UserRepository;
use App\Product\ProductRepository;
use InvalidArgumentException;
use Yiisoft\Db\Connection\ConnectionInterface;

final readonly class DiscountCodeService
{
    public function __construct(
        private ConnectionInterface $db,
        private DiscountCodeRepository $discountCodes,
        private UserRepository $users,
        private ProductRepository $products,
    ) {
    }

    /**
     * @param int[] $userIds
     * @param int[] $productIds
     */
    public function create(
        string $code,
        string $type,
        string $value,
        string $userScope,
        string $productScope,
        ?string $maxDiscountAmount,
        ?string $minimumOrderAmount,
        array $userIds,
        array $productIds,
    ): DiscountCode {
        [$code, $discountType, $value, $userScopeEnum, $productScopeEnum, $maxDiscountAmount, $minimumOrderAmount, $userIds, $productIds]
            = $this->normalizeAndValidate(
                code: $code,
                type: $type,
                value: $value,
                userScope: $userScope,
                productScope: $productScope,
                maxDiscountAmount: $maxDiscountAmount,
                minimumOrderAmount: $minimumOrderAmount,
                userIds: $userIds,
                productIds: $productIds,
            );

        if ($this->discountCodes->codeExists($code)) {
            throw new InvalidArgumentException('This discount code already exists.');
        }

        return $this->db->transaction(function () use (
            $code,
            $discountType,
            $value,
            $userScopeEnum,
            $productScopeEnum,
            $maxDiscountAmount,
            $minimumOrderAmount,
            $userIds,
            $productIds,
        ): DiscountCode {
            $discountCode = $this->discountCodes->create(
                code: $code,
                type: $discountType,
                value: $value,
                userScope: $userScopeEnum,
                productScope: $productScopeEnum,
                maxDiscountAmount: $maxDiscountAmount,
                minimumOrderAmount: $minimumOrderAmount,
            );

            $this->discountCodes->syncUsers($discountCode->getId(), $userIds);
            $this->discountCodes->syncProducts($discountCode->getId(), $productIds);

            return $discountCode;
        });
    }

    /**
     * @param int[] $userIds
     * @param int[] $productIds
     */
    public function update(
        DiscountCode $discountCode,
        string $code,
        string $type,
        string $value,
        string $userScope,
        string $productScope,
        ?string $maxDiscountAmount,
        ?string $minimumOrderAmount,
        array $userIds,
        array $productIds,
    ): DiscountCode {
        [$code, $discountType, $value, $userScopeEnum, $productScopeEnum, $maxDiscountAmount, $minimumOrderAmount, $userIds, $productIds]
            = $this->normalizeAndValidate(
                code: $code,
                type: $type,
                value: $value,
                userScope: $userScope,
                productScope: $productScope,
                maxDiscountAmount: $maxDiscountAmount,
                minimumOrderAmount: $minimumOrderAmount,
                userIds: $userIds,
                productIds: $productIds,
            );

        if ($this->discountCodes->codeExists($code, $discountCode->getId())) {
            throw new InvalidArgumentException('This discount code already exists.');
        }

        return $this->db->transaction(function () use (
            $discountCode,
            $code,
            $discountType,
            $value,
            $userScopeEnum,
            $productScopeEnum,
            $maxDiscountAmount,
            $minimumOrderAmount,
            $userIds,
            $productIds,
        ): DiscountCode {
            $updated = $this->discountCodes->update(
                discountCode: $discountCode,
                code: $code,
                type: $discountType,
                value: $value,
                userScope: $userScopeEnum,
                productScope: $productScopeEnum,
                maxDiscountAmount: $maxDiscountAmount,
                minimumOrderAmount: $minimumOrderAmount,
            );

            $this->discountCodes->syncUsers($discountCode->getId(), $userIds);
            $this->discountCodes->syncProducts($discountCode->getId(), $productIds);

            return $updated;
        });
    }

    public function delete(DiscountCode $discountCode): void
    {
        $this->discountCodes->delete($discountCode);
    }

    /**
     * @param int[] $userIds
     * @param int[] $productIds
     * @return array{string, DiscountType, string, DiscountScope, DiscountScope, ?string, ?string, int[], int[]}
     */
    private function normalizeAndValidate(
        string $code,
        string $type,
        string $value,
        string $userScope,
        string $productScope,
        ?string $maxDiscountAmount,
        ?string $minimumOrderAmount,
        array $userIds,
        array $productIds,
    ): array {
        $code = strtoupper(trim($code));
        $value = trim($value);
        $maxDiscountAmount = $this->normalizeOptionalAmount($maxDiscountAmount);
        $minimumOrderAmount = $this->normalizeOptionalAmount($minimumOrderAmount);
        $discountType = DiscountType::tryFrom($type);
        $userScopeEnum = DiscountScope::tryFrom($userScope);
        $productScopeEnum = DiscountScope::tryFrom($productScope);

        if ($code === '') {
            throw new InvalidArgumentException('Discount code is required.');
        }

        if ($discountType === null) {
            throw new InvalidArgumentException('Invalid discount type.');
        }

        if ($userScopeEnum === null || $productScopeEnum === null) {
            throw new InvalidArgumentException('Invalid discount scope.');
        }

        if ((float) $value <= 0) {
            throw new InvalidArgumentException('Discount value must be greater than zero.');
        }

        if ($discountType === DiscountType::Percentage) {
            if ((float) $value > 100) {
                throw new InvalidArgumentException('Percentage discount cannot be greater than 100.');
            }

            if ($maxDiscountAmount === null || (float) $maxDiscountAmount <= 0) {
                throw new InvalidArgumentException('Maximum discount amount is required for percentage discounts.');
            }

            $minimumOrderAmount = null;
        } else {
            if ($minimumOrderAmount === null || (float) $minimumOrderAmount <= 0) {
                throw new InvalidArgumentException('Minimum order amount is required for fixed discounts.');
            }

            $maxDiscountAmount = null;
        }

        $userIds = $this->normalizeIds($userIds, 'User');
        $productIds = $this->normalizeIds($productIds, 'Product');

        if ($userScopeEnum === DiscountScope::All) {
            $userIds = [];
        } else {
            if ($userIds === []) {
                throw new InvalidArgumentException('Select at least one user for a specific-user discount code.');
            }
            $this->assertUsersExist($userIds);
        }

        if ($productScopeEnum === DiscountScope::All) {
            $productIds = [];
        } else {
            if ($productIds === []) {
                throw new InvalidArgumentException('Select at least one product for a specific-product discount code.');
            }
            $this->assertProductsExist($productIds);
        }

        return [
            $code,
            $discountType,
            $value,
            $userScopeEnum,
            $productScopeEnum,
            $maxDiscountAmount,
            $minimumOrderAmount,
            $userIds,
            $productIds,
        ];
    }

    private function normalizeOptionalAmount(?string $amount): ?string
    {
        if ($amount === null) {
            return null;
        }

        $amount = trim($amount);

        return $amount === '' ? null : $amount;
    }

    /** @param int[] $ids @return int[] */
    private function normalizeIds(array $ids, string $label): array
    {
        $normalized = [];

        foreach ($ids as $id) {
            $id = (int) $id;
            if ($id <= 0) {
                throw new InvalidArgumentException($label . ' IDs must be positive integers.');
            }
            $normalized[$id] = $id;
        }

        return array_values($normalized);
    }

    /** @param int[] $userIds */
    private function assertUsersExist(array $userIds): void
    {
        foreach ($userIds as $userId) {
            if ($this->users->findById($userId) === null) {
                throw new InvalidArgumentException("User with ID {$userId} does not exist.");
            }
        }
    }

    /** @param int[] $productIds */
    private function assertProductsExist(array $productIds): void
    {
        foreach ($productIds as $productId) {
            if ($this->products->findById($productId) === null) {
                throw new InvalidArgumentException("Product with ID {$productId} does not exist.");
            }
        }
    }
}
