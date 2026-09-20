<?php

declare(strict_types=1);

namespace App\Order;

use DateTimeImmutable;
use Yiisoft\ActiveRecord\ActiveRecord;

final class OrderItem extends ActiveRecord
{
    public ?int $id = null;
    public int $order_id;
    public ?int $product_id = null;
    public string $product_title;
    public int $quantity;
    public string $unit_price;
    public DateTimeImmutable $created_at;

    public function tableName(): string
    {
        return 'order_items';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOrderId(): int
    {
        return $this->order_id;
    }

    public function setOrderId(int $orderId): void
    {
        $this->order_id = $orderId;
    }

    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    public function setProductId(?int $productId): void
    {
        $this->product_id = $productId;
    }

    public function getProductTitle(): string
    {
        return $this->product_title;
    }

    public function setProductTitle(string $productTitle): void
    {
        $this->product_title = $productTitle;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getUnitPrice(): string
    {
        return $this->unit_price;
    }

    public function setUnitPrice(string $unitPrice): void
    {
        $this->unit_price = $unitPrice;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->created_at = $createdAt;
    }
}
