<?php

declare(strict_types=1);

namespace App\Cart;

use DateTimeImmutable;
use Yiisoft\ActiveRecord\ActiveRecord;

final class Cart extends ActiveRecord
{
    public ?int $id = null;
    public int $user_id;
    public string $status = 'ACTIVE';
    public ?int $discount_code_id = null;
    public DateTimeImmutable $created_at;
    public ?DateTimeImmutable $updated_at = null;

    public function tableName(): string
    {
        return 'carts';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setUserId(int $userId): void
    {
        $this->user_id = $userId;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function setDiscountCodeId(?int $discountCodeId): void
    {
        $this->discount_code_id = $discountCodeId;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->created_at = $createdAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): void
    {
        $this->updated_at = $updatedAt;
    }
}
