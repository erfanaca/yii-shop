<?php

declare(strict_types=1);

namespace App\Product;

use App\Category\Category;
use DateTimeImmutable;
use Yiisoft\ActiveRecord\ActiveQueryInterface;
use Yiisoft\ActiveRecord\ActiveRecord;

final class Product extends ActiveRecord
{
    public ?int $id = null;
    public ?string $title = null;
    public ?string $description = null;
    public ?int $quantity = null;
    public ?string $price = null;
    public DateTimeImmutable $created_at;
    public ?DateTimeImmutable $updated_at = null;

    public function tableName(): string
    {
        return 'products';
    }

    public function relationQuery(string $name): ActiveQueryInterface
    {
        return match ($name) {
            'productCategories' => $this->hasMany(ProductCategory::class, ['product_id' => 'id']),
            'categories' => $this->hasMany(Category::class, ['id' => 'category_id'])->via('productCategories'),
            'images' => $this->hasMany(ProductImage::class, ['product_id' => 'id']),
            default => parent::relationQuery($name),
        };
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setPrice(string $price): void
    {
        $this->price = $price;
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
