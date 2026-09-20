<?php

declare(strict_types=1);

namespace App\Product;

use DateTimeImmutable;
use Yiisoft\ActiveRecord\ActiveRecord;

final class ProductImage extends ActiveRecord
{
    public ?int $id = null;
    public int $product_id;
    public string $path;
    public int $sort_order = 0;
    public DateTimeImmutable $created_at;
    public ?DateTimeImmutable $updated_at = null;

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
        return $this->sort_order;
    }

    public function setSortOrder(int $sortOrder): void
    {
        $this->sort_order = $sortOrder;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updated_at;
    }
}
