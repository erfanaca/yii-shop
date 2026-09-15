<?php

declare(strict_types=1);

namespace App\Category;

use App\Product\Product;
use Yiisoft\ActiveRecord\ActiveQuery;
use Yiisoft\ActiveRecord\ActiveRecord;

final class Category extends ActiveRecord
{
    public int $id;

    public string $title;

    public string $created_at;

    public ?string $updated_at = null;

    public function tableName(): string
    {
        return 'categories';
    }

    /**
     * @return ActiveQuery<Product>
     */
    public function products(): ActiveQuery
    {
        return $this->hasMany(
            Product::class,
            ['id' => 'product_id'],
        )->viaTable(
            'product_categories',
            ['category_id' => 'id'],
        );
    }
}