<?php

declare(strict_types=1);

namespace App\Discount;

use DateTimeImmutable;

final readonly class DiscountCodeRepository
{
    /** @return DiscountCode[] */
    public function findAll(): array
    {
        return DiscountCode::query()
            ->orderBy(['created_at' => SORT_DESC, 'id' => SORT_DESC])
            ->all();
    }

    public function findByCode(string $code): ?DiscountCode
    {
        return DiscountCode::query()
            ->where(['code' => strtoupper(trim($code))])
            ->one();
    }

    public function findById(int $id): ?DiscountCode
    {
        return DiscountCode::query()
            ->where(['id' => $id])
            ->one();
    }

    public function codeExists(string $code, ?int $excludeId = null): bool
    {
        $condition = ['code' => strtoupper(trim($code))];

        if ($excludeId !== null) {
            $condition = ['and', $condition, ['<>', 'id', $excludeId]];
        }

        return DiscountCode::query()
            ->where($condition)
            ->exists();
    }

    public function create(
        string $code,
        DiscountType $type,
        string $value,
        DiscountScope $userScope,
        DiscountScope $productScope,
        ?string $maxDiscountAmount,
        ?string $minimumOrderAmount,
    ): DiscountCode {
        $discountCode = new DiscountCode();
        $discountCode->setCode($code);
        $discountCode->setType($type);
        $discountCode->setValue($value);
        $discountCode->setUserScope($userScope);
        $discountCode->setProductScope($productScope);
        $discountCode->setMaxDiscountAmount($maxDiscountAmount);
        $discountCode->setMinimumOrderAmount($minimumOrderAmount);
        $discountCode->setCreatedAt(new DateTimeImmutable());
        $discountCode->save();

        return $discountCode;
    }

    public function update(
        DiscountCode $discountCode,
        string $code,
        DiscountType $type,
        string $value,
        DiscountScope $userScope,
        DiscountScope $productScope,
        ?string $maxDiscountAmount,
        ?string $minimumOrderAmount,
    ): DiscountCode {
        $discountCode->setCode($code);
        $discountCode->setType($type);
        $discountCode->setValue($value);
        $discountCode->setUserScope($userScope);
        $discountCode->setProductScope($productScope);
        $discountCode->setMaxDiscountAmount($maxDiscountAmount);
        $discountCode->setMinimumOrderAmount($minimumOrderAmount);
        $discountCode->setUpdatedAt(new DateTimeImmutable());
        $discountCode->save();

        return $discountCode;
    }

    public function delete(DiscountCode $discountCode): void
    {
        $discountCode->delete();
    }

    public function findUserIds(int $discountCodeId): array
    {
        $rows = DiscountCodeUser::query()
            ->where(['discount_code_id' => $discountCodeId])
            ->orderBy(['user_id' => SORT_ASC])
            ->all();

        return array_map(
            static fn (DiscountCodeUser $row): int => $row->user_id,
            $rows,
        );
    }

    public function findProductIds(int $discountCodeId): array
    {
        $rows = DiscountCodeProduct::query()
            ->where(['discount_code_id' => $discountCodeId])
            ->orderBy(['product_id' => SORT_ASC])
            ->all();

        return array_map(
            static fn (DiscountCodeProduct $row): int => $row->product_id,
            $rows,
        );
    }

    public function syncUsers(int $discountCodeId, array $userIds): void
    {
        $rows = DiscountCodeUser::query()
            ->where(['discount_code_id' => $discountCodeId])
            ->all();

        foreach ($rows as $row) {
            $row->delete();
        }

        foreach (array_values(array_unique(array_map('intval', $userIds))) as $userId) {
            $row = new DiscountCodeUser();
            $row->discount_code_id = $discountCodeId;
            $row->user_id = $userId;
            $row->save();
        }
    }

    public function syncProducts(int $discountCodeId, array $productIds): void
    {
        $rows = DiscountCodeProduct::query()
            ->where(['discount_code_id' => $discountCodeId])
            ->all();

        foreach ($rows as $row) {
            $row->delete();
        }

        foreach (array_values(array_unique(array_map('intval', $productIds))) as $productId) {
            $row = new DiscountCodeProduct();
            $row->discount_code_id = $discountCodeId;
            $row->product_id = $productId;
            $row->save();
        }
    }
}
