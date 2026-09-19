<?php

declare(strict_types=1);

namespace App\Order;

use DateTimeImmutable;
use RuntimeException;
use Yiisoft\Db\Connection\ConnectionInterface;

final readonly class OrderRepository
{
    private const MAX_REFERENCE_GENERATION_ATTEMPTS = 20;

    public function __construct(
        private ConnectionInterface $db,
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
        $now = new DateTimeImmutable();
        $transactionNumber = $this->generateUniqueReference();
        $invoiceNumber = $this->generateUniqueReference([$transactionNumber]);

        $this->db
            ->createCommand()
            ->insert('orders', [
                'user_id' => $userId,
                'status' => OrderStatus::Pending->value,
                'total_amount' => $totalAmount,
                'subtotal_amount' => $subtotalAmount,
                'discount_amount' => $discountAmount,
                'discount_code' => $discountCode,
                'discount_type' => $discountType,
                'discount_value' => $discountValue,
                'discount_eligible_subtotal' => $discountEligibleSubtotal,
                'transaction_number' => $transactionNumber,
                'invoice_number' => $invoiceNumber,
                'created_at' => $now,
                'updated_at' => null,
            ])
            ->execute();

        return new Order(
            id: (int) $this->db->getLastInsertId(),
            userId: $userId,
            status: OrderStatus::Pending,
            totalAmount: $totalAmount,
            transactionNumber: $transactionNumber,
            invoiceNumber: $invoiceNumber,
            createdAt: $now,
            updatedAt: null,
        );
    }

    public function addItem(
        int $orderId,
        ?int $productId,
        string $productTitle,
        int $quantity,
        string $unitPrice,
    ): void {
        $this->db
            ->createCommand()
            ->insert('order_items', [
                'order_id' => $orderId,
                'product_id' => $productId,
                'product_title' => $productTitle,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'created_at' => new DateTimeImmutable(),
            ])
            ->execute();
    }

    public function changeStatus(Order $order, OrderStatus $status): Order
    {
        $updatedAt = new DateTimeImmutable();

        $this->db
            ->createCommand()
            ->update(
                'orders',
                [
                    'status' => $status->value,
                    'updated_at' => $updatedAt,
                ],
                ['id' => $order->getId()],
            )
            ->execute();

        return new Order(
            id: $order->getId(),
            userId: $order->getUserId(),
            status: $status,
            totalAmount: $order->getTotalAmount(),
            transactionNumber: $order->getTransactionNumber(),
            invoiceNumber: $order->getInvoiceNumber(),
            createdAt: $order->getCreatedAt(),
            updatedAt: $updatedAt,
        );
    }

    /**
     * @param string[] $excludedReferences
     */
    private function generateUniqueReference(array $excludedReferences = []): string
    {
        for ($attempt = 0; $attempt < self::MAX_REFERENCE_GENERATION_ATTEMPTS; $attempt++) {
            $reference = $this->referenceGenerator->generate();

            if (in_array($reference, $excludedReferences, true)) {
                continue;
            }

            $existingOrder = $this->db
                ->createQuery()
                ->select(['id'])
                ->from('orders')
                ->where([
                    'or',
                    ['transaction_number' => $reference],
                    ['invoice_number' => $reference],
                ])
                ->limit(1)
                ->one();

            if ($existingOrder === null || $existingOrder === false) {
                return $reference;
            }
        }

        throw new RuntimeException('Unable to generate a unique order reference.');
    }
}
