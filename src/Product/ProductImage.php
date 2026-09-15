<?php

declare(strict_types=1);

namespace App\Product;

use Yiisoft\ActiveRecord\ActiveQuery;
use Yiisoft\ActiveRecord\ActiveRecord;

final class ProductImage extends ActiveRecord
{
    public int $id;

    public int $product_id;

    public string $path;

    public int $sort_order = 0;

    public string $created_at;

    public ?string $updated_at = null;

    public function tableName(): string
    {
        return 'product_images';
    }

    /**
     * @return ActiveQuery<Product>
     */
    public function product(): ActiveQuery
    {
        return $this->hasOne(
            Product::class,
            ['id' => 'product_id'],
        );
    }
}