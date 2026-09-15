<?php

declare(strict_types=1);

namespace App\Product;

use DateTimeImmutable;

final class Product
{
    public function __construct(
        private readonly int $id,
        private readonly string $title,
        private readonly ?string $description,
        private readonly int $quantity,
        private readonly string $price,
        private readonly DateTimeImmutable $createdAt,
        private readonly ?DateTimeImmutable $updatedAt,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): string
    {
        return $this->price;
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
