<?php

declare(strict_types=1);

namespace App\Discount;

use DateTimeImmutable;
use Yiisoft\ActiveRecord\ActiveRecord;

final class DiscountCode extends ActiveRecord
{
    public ?int $id = null;
    public string $code;
    public string $type;
    public string $value;
    public string $user_scope;
    public string $product_scope;
    public ?string $max_discount_amount = null;
    public ?string $minimum_order_amount = null;
    public DateTimeImmutable $created_at;
    public ?DateTimeImmutable $updated_at = null;

    public function tableName(): string
    {
        return 'discount_codes';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getType(): DiscountType
    {
        return DiscountType::from($this->type);
    }

    public function setType(DiscountType $type): void
    {
        $this->type = $type->value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getUserScope(): DiscountScope
    {
        return DiscountScope::from($this->user_scope);
    }

    public function setUserScope(DiscountScope $userScope): void
    {
        $this->user_scope = $userScope->value;
    }

    public function getProductScope(): DiscountScope
    {
        return DiscountScope::from($this->product_scope);
    }

    public function setProductScope(DiscountScope $productScope): void
    {
        $this->product_scope = $productScope->value;
    }

    public function getMaxDiscountAmount(): ?string
    {
        return $this->max_discount_amount;
    }

    public function setMaxDiscountAmount(?string $maxDiscountAmount): void
    {
        $this->max_discount_amount = $maxDiscountAmount;
    }

    public function getMinimumOrderAmount(): ?string
    {
        return $this->minimum_order_amount;
    }

    public function setMinimumOrderAmount(?string $minimumOrderAmount): void
    {
        $this->minimum_order_amount = $minimumOrderAmount;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->created_at = $createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): void
    {
        $this->updated_at = $updatedAt;
    }
}
