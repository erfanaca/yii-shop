<?php

declare(strict_types=1);

namespace App\Product;

use Yiisoft\ActiveRecord\ActiveRecord;

final class ProductCategory extends ActiveRecord
{
    public int $product_id;
    public int $category_id;

    public function tableName(): string
    {
        return 'product_categories';
    }

    public function setProductId(int $productId): void
    {
        $this->product_id = $productId;
    }

    public function setCategoryId(int $categoryId): void
    {
        $this->category_id = $categoryId;
    }
}
