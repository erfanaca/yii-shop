<?php

declare(strict_types=1);

namespace App\Discount;

use Yiisoft\ActiveRecord\ActiveRecord;

final class DiscountCodeProduct extends ActiveRecord
{
    public int $discount_code_id;
    public int $product_id;

    public function tableName(): string
    {
        return 'discount_code_products';
    }
}
