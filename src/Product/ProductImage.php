<?php

declare(strict_types=1);

namespace App\Product;

use DateTimeImmutable;

final class ProductImage
{
    public function __construct(
        private readonly int $id,
        private readonly int $productId,
        private readonly string $path,
        private readonly int $sortOrder,
        private readonly DateTimeImmutable $createdAt,
        private readonly ?DateTimeImmutable $updatedAt,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
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
