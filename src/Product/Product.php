<?php

declare(strict_types=1);

namespace App\Product;

use App\Category\Category;
use Yiisoft\ActiveRecord\ActiveQuery;
use Yiisoft\ActiveRecord\ActiveRecord;
use DateTimeImmutable;

final class Product extends ActiveRecord
{
    public int $id;

    public string $title;

    public ?string $description = null;

    public string|int $quantity = 0;

    public string $price;

    public ?DateTimeImmutable $created_at; 

    public ?DateTimeImmutable $updated_at;

    public function tableName(): string
    {
        return 'products';
    }

    /**
     * @return ActiveQuery<ProductImage>
     */
    public function images(): ActiveQuery
    {
        return $this->hasMany(
            ProductImage::class,
            ['product_id' => 'id'],
        );
    }

    /**
     * @return ActiveQuery<Category>
     */
    public function categories(): ActiveQuery
    {
        return $this->hasMany(
            Category::class,
            ['id' => 'category_id'],
        )->viaTable(
            'product_categories',
            ['product_id' => 'id'],
        );
    }
}
