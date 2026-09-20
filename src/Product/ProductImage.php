<?php

declare(strict_types=1);

namespace App\Product;

use DateTimeImmutable;
use Yiisoft\ActiveRecord\ActiveRecord;

final class ProductImage extends ActiveRecord
{
    public int $id;
    public int $product_id;
    public string $path;
    public int $sortOrder;
    public DateTimeImmutable $createdAt;
    public ?DateTimeImmutable $updatedAt;

    public function tableName(): string
    {
        return 'product_images';
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function setProductId(int $productId): void
    {
        $this->product_id = $productId;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
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
