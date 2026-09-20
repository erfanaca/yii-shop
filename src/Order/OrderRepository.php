<?php

declare(strict_types=1);

namespace App\Order;

use DateTimeImmutable;
use RuntimeException;

final readonly class OrderRepository
{
    private const MAX_REFERENCE_GENERATION_ATTEMPTS = 20;

    public function __construct(
        private OrderReferenceGenerator $referenceGenerator,
    ) {
    }

    public function createPending(
        int $userId,
        string $totalAmount,
        string $subtotalAmount,
        string $discountAmount = '0.00',
        ?string $discountCode = null,
        ?string $discountType = null,
        ?string $discountValue = null,
        ?string $discountEligibleSubtotal = null,
    ): Order {
        $transactionNumber = $this->generateUniqueReference();
        $invoiceNumber = $this->generateUniqueReference([$transactionNumber]);

        $order = new Order();
        $order->setUserId($userId);
        $order->setStatus(OrderStatus::Pending);
        $order->setTotalAmount($totalAmount);
        $order->setSubtotalAmount($subtotalAmount);
        $order->setDiscountAmount($discountAmount);
        $order->setDiscountCode($discountCode);
        $order->setDiscountType($discountType);
        $order->setDiscountValue($discountValue);
        $order->setDiscountEligibleSubtotal($discountEligibleSubtotal);
        $order->setTransactionNumber($transactionNumber);
        $order->setInvoiceNumber($invoiceNumber);
        $order->setCreatedAt(new DateTimeImmutable());
        $order->save();

        return $order;
    }

    public function addItem(
        int $orderId,
        ?int $productId,
        string $productTitle,
        int $quantity,
        string $unitPrice,
    ): void {
        $item = new OrderItem();
        $item->setOrderId($orderId);
        $item->setProductId($productId);
        $item->setProductTitle($productTitle);
        $item->setQuantity($quantity);
        $item->setUnitPrice($unitPrice);
        $item->setCreatedAt(new DateTimeImmutable());
        $item->save();
    }

    public function changeStatus(Order $order, OrderStatus $status): Order
    {
        $order->setStatus($status);
        $order->setUpdatedAt(new DateTimeImmutable());
        $order->save();

        return $order;
    }

    private function generateUniqueReference(array $excludedReferences = []): string
    {
        for ($attempt = 0; $attempt < self::MAX_REFERENCE_GENERATION_ATTEMPTS; $attempt++) {
            $reference = $this->referenceGenerator->generate();

            if (in_array($reference, $excludedReferences, true)) {
                continue;
            }

            $exists = Order::query()
                ->where([
                    'or',
                    ['transaction_number' => $reference],
                    ['invoice_number' => $reference],
                ])
                ->exists();

            if (!$exists) {
                return $reference;
            }
        }

        throw new RuntimeException('Unable to generate a unique order reference.');
    }
}
