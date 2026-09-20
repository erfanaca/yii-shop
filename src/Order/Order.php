<?php

declare(strict_types=1);

namespace App\Order;

use DateTimeImmutable;
use Yiisoft\ActiveRecord\ActiveQueryInterface;
use Yiisoft\ActiveRecord\ActiveRecord;
use App\User\User;

final class Order extends ActiveRecord
{
    public ?int $id = null;
    public int $user_id;
    public string $status = 'PENDING';
    public string $total_amount;
    public ?string $subtotal_amount = null;
    public string $discount_amount = '0.00';
    public ?string $discount_code = null;
    public ?string $discount_type = null;
    public ?string $discount_value = null;
    public ?string $discount_eligible_subtotal = null;
    public string $transaction_number;
    public string $invoice_number;
    public DateTimeImmutable $created_at;
    public ?DateTimeImmutable $updated_at = null;

    public function tableName(): string
    {
        return 'orders';
    }

    public function relationQuery(string $name): ActiveQueryInterface
    {
        return match ($name) {
            'user' => $this->hasOne(User::class, ['id' => 'user_id']),
            'items' => $this->hasMany(OrderItem::class, ['order_id' => 'id']),
            default => parent::relationQuery($name),
        };
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $userId): void
    {
        $this->user_id = $userId;
    }

    public function getStatus(): OrderStatus
    {
        return OrderStatus::from($this->status);
    }

    public function setStatus(OrderStatus $status): void
    {
        $this->status = $status->value;
    }

    public function getTotalAmount(): string
    {
        return $this->total_amount;
    }

    public function setTotalAmount(string $totalAmount): void
    {
        $this->total_amount = $totalAmount;
    }

    public function setSubtotalAmount(string $subtotalAmount): void
    {
        $this->subtotal_amount = $subtotalAmount;
    }

    public function setDiscountAmount(string $discountAmount): void
    {
        $this->discount_amount = $discountAmount;
    }

    public function setDiscountCode(?string $discountCode): void
    {
        $this->discount_code = $discountCode;
    }

    public function setDiscountType(?string $discountType): void
    {
        $this->discount_type = $discountType;
    }

    public function setDiscountValue(?string $discountValue): void
    {
        $this->discount_value = $discountValue;
    }

    public function setDiscountEligibleSubtotal(?string $discountEligibleSubtotal): void
    {
        $this->discount_eligible_subtotal = $discountEligibleSubtotal;
    }

    public function getTransactionNumber(): string
    {
        return $this->transaction_number;
    }

    public function setTransactionNumber(string $transactionNumber): void
    {
        $this->transaction_number = $transactionNumber;
    }

    public function getInvoiceNumber(): string
    {
        return $this->invoice_number;
    }

    public function setInvoiceNumber(string $invoiceNumber): void
    {
        $this->invoice_number = $invoiceNumber;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->created_at = $createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): void
    {
        $this->updated_at = $updatedAt;
    }
}
