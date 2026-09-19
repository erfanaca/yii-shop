<?php

declare(strict_types=1);

namespace App\Discount;

use DateTimeImmutable;

final class DiscountCode
{
    public function __construct(
        private readonly int $id,
        private readonly string $code,
        private readonly DiscountType $type,
        private readonly string $value,
        private readonly DiscountScope $userScope,
        private readonly DiscountScope $productScope,
        private readonly ?string $maxDiscountAmount,
        private readonly ?string $minimumOrderAmount,
        private readonly DateTimeImmutable $createdAt,
        private readonly ?DateTimeImmutable $updatedAt,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getType(): DiscountType
    {
        return $this->type;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getUserScope(): DiscountScope
    {
        return $this->userScope;
    }

    public function getProductScope(): DiscountScope
    {
        return $this->productScope;
    }

    public function getMaxDiscountAmount(): ?string
    {
        return $this->maxDiscountAmount;
    }

    public function getMinimumOrderAmount(): ?string
    {
        return $this->minimumOrderAmount;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
