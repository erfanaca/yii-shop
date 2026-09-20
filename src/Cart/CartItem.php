<?php

declare(strict_types=1);

namespace App\Cart;

use DateTimeImmutable;
use Yiisoft\ActiveRecord\ActiveRecord;

final class CartItem extends ActiveRecord
{
    public ?int $id = null;
    public int $cart_id;
    public int $product_id;
    public int $quantity = 1;
    public string $unit_price;
    public DateTimeImmutable $created_at;
    public ?DateTimeImmutable $updated_at = null;

    public function tableName(): string
    {
        return 'cart_items';
    }

    public function setCartId(int $cartId): void
    {
        $this->cart_id = $cartId;
    }

    public function setProductId(int $productId): void
    {
        $this->product_id = $productId;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function setUnitPrice(string $unitPrice): void
    {
        $this->unit_price = $unitPrice;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->created_at = $createdAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): void
    {
        $this->updated_at = $updatedAt;
    }
}
