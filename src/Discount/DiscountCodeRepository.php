<?php

declare(strict_types=1);

namespace App\Discount;

use DateTimeImmutable;
use DateTimeInterface;
use Yiisoft\Db\Connection\ConnectionInterface;

final readonly class DiscountCodeRepository
{
    public function __construct(private ConnectionInterface $db)
    {
    }

    /** @return DiscountCode[] */
    public function findAll(): array
    {
        $rows = $this->db
            ->createQuery()
            ->from('discount_codes')
            ->orderBy(['created_at' => SORT_DESC, 'id' => SORT_DESC])
            ->all();

        return array_map($this->createFromRow(...), $rows);
    }

    public function findByCode(string $code): ?DiscountCode
    {
        $row = $this->db
            ->createQuery()
            ->from('discount_codes')
            ->where(['code' => strtoupper(trim($code))])
            ->limit(1)
            ->one();

        if ($row === null || $row === false) {
            return null;
        }

        return $this->createFromRow($row);
    }

    public function findById(int $id): ?DiscountCode
    {
        $row = $this->db
            ->createQuery()
            ->from('discount_codes')
            ->where(['id' => $id])
            ->limit(1)
            ->one();

        if ($row === null || $row === false) {
            return null;
        }

        return $this->createFromRow($row);
    }

    public function codeExists(string $code, ?int $excludeId = null): bool
    {
        $condition = ['code' => $code];

        if ($excludeId !== null) {
            $condition = ['and', $condition, ['<>', 'id', $excludeId]];
        }

        $row = $this->db
            ->createQuery()
            ->select('id')
            ->from('discount_codes')
            ->where($condition)
            ->limit(1)
            ->one();

        return $row !== null && $row !== false;
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
        $now = new DateTimeImmutable();

        $this->db->createCommand()->insert('discount_codes', [
            'code' => $code,
            'type' => $type->value,
            'value' => $value,
            'user_scope' => $userScope->value,
            'product_scope' => $productScope->value,
            'max_discount_amount' => $maxDiscountAmount,
            'minimum_order_amount' => $minimumOrderAmount,
            'created_at' => $now,
            'updated_at' => null,
        ])->execute();

        return new DiscountCode(
            id: (int) $this->db->getLastInsertId(),
            code: $code,
            type: $type,
            value: $value,
            userScope: $userScope,
            productScope: $productScope,
            maxDiscountAmount: $maxDiscountAmount,
            minimumOrderAmount: $minimumOrderAmount,
            createdAt: $now,
            updatedAt: null,
        );
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
        $updatedAt = new DateTimeImmutable();

        $this->db->createCommand()->update('discount_codes', [
            'code' => $code,
            'type' => $type->value,
            'value' => $value,
            'user_scope' => $userScope->value,
            'product_scope' => $productScope->value,
            'max_discount_amount' => $maxDiscountAmount,
            'minimum_order_amount' => $minimumOrderAmount,
            'updated_at' => $updatedAt,
        ], ['id' => $discountCode->getId()])->execute();

        return new DiscountCode(
            id: $discountCode->getId(),
            code: $code,
            type: $type,
            value: $value,
            userScope: $userScope,
            productScope: $productScope,
            maxDiscountAmount: $maxDiscountAmount,
            minimumOrderAmount: $minimumOrderAmount,
            createdAt: $discountCode->getCreatedAt(),
            updatedAt: $updatedAt,
        );
    }

    public function delete(DiscountCode $discountCode): void
    {
        $this->db->createCommand()
            ->delete('discount_codes', ['id' => $discountCode->getId()])
            ->execute();
    }

    /** @return int[] */
    public function findUserIds(int $discountCodeId): array
    {
        $rows = $this->db->createQuery()
            ->select('user_id')
            ->from('discount_code_users')
            ->where(['discount_code_id' => $discountCodeId])
            ->orderBy(['user_id' => SORT_ASC])
            ->all();

        return array_map(static fn (array $row): int => (int) $row['user_id'], $rows);
    }

    /** @return int[] */
    public function findProductIds(int $discountCodeId): array
    {
        $rows = $this->db->createQuery()
            ->select('product_id')
            ->from('discount_code_products')
            ->where(['discount_code_id' => $discountCodeId])
            ->orderBy(['product_id' => SORT_ASC])
            ->all();

        return array_map(static fn (array $row): int => (int) $row['product_id'], $rows);
    }

    /** @param int[] $userIds */
    public function syncUsers(int $discountCodeId, array $userIds): void
    {
        $this->db->createCommand()
            ->delete('discount_code_users', ['discount_code_id' => $discountCodeId])
            ->execute();

        foreach ($userIds as $userId) {
            $this->db->createCommand()->insert('discount_code_users', [
                'discount_code_id' => $discountCodeId,
                'user_id' => $userId,
            ])->execute();
        }
    }

    /** @param int[] $productIds */
    public function syncProducts(int $discountCodeId, array $productIds): void
    {
        $this->db->createCommand()
            ->delete('discount_code_products', ['discount_code_id' => $discountCodeId])
            ->execute();

        foreach ($productIds as $productId) {
            $this->db->createCommand()->insert('discount_code_products', [
                'discount_code_id' => $discountCodeId,
                'product_id' => $productId,
            ])->execute();
        }
    }

    private function createFromRow(array $row): DiscountCode
    {
        return new DiscountCode(
            id: (int) $row['id'],
            code: (string) $row['code'],
            type: DiscountType::from((string) $row['type']),
            value: (string) $row['value'],
            userScope: DiscountScope::from((string) $row['user_scope']),
            productScope: DiscountScope::from((string) $row['product_scope']),
            maxDiscountAmount: $row['max_discount_amount'] === null ? null : (string) $row['max_discount_amount'],
            minimumOrderAmount: $row['minimum_order_amount'] === null ? null : (string) $row['minimum_order_amount'],
            createdAt: $this->toDateTimeImmutable($row['created_at']),
            updatedAt: $row['updated_at'] === null ? null : $this->toDateTimeImmutable($row['updated_at']),
        );
    }

    private function toDateTimeImmutable(mixed $value): DateTimeImmutable
    {
        if ($value instanceof DateTimeImmutable) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            return DateTimeImmutable::createFromInterface($value);
        }

        return new DateTimeImmutable((string) $value);
    }
}
