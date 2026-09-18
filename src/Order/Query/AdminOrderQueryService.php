<?php

declare(strict_types=1);

namespace App\Order\Query;

use App\Order\OrderStatus;
use DateTimeImmutable;
use Yiisoft\Db\Connection\ConnectionInterface;

final readonly class AdminOrderQueryService
{
    public function __construct(
        private ConnectionInterface $db,
    ) {
    }

    /**
     * @return AdminOrderSummary[]
     */
    public function findAll(): array
    {
        $rows = $this->db
            ->createQuery()
            ->select([
                'orders.id',
                'orders.user_id',
                'users.email AS user_email',
                'orders.status',
                'orders.total_amount',
                'orders.transaction_number',
                'orders.invoice_number',
                'orders.created_at',
                'orders.updated_at',
            ])
            ->from('orders')
            ->innerJoin('users', 'users.id = orders.user_id')
            ->orderBy(['orders.created_at' => SORT_DESC, 'orders.id' => SORT_DESC])
            ->all();

        return array_map(
            static fn (array $row): AdminOrderSummary => new AdminOrderSummary(
                id: (int) $row['id'],
                userId: (int) $row['user_id'],
                userEmail: (string) $row['user_email'],
                status: OrderStatus::from((string) $row['status']),
                totalAmount: (string) $row['total_amount'],
                transactionNumber: (string) $row['transaction_number'],
                invoiceNumber: (string) $row['invoice_number'],
                createdAt: new DateTimeImmutable((string) $row['created_at']),
                updatedAt: $row['updated_at'] === null
                    ? null
                    : new DateTimeImmutable((string) $row['updated_at']),
            ),
            $rows,
        );
    }

    public function findById(int $id): ?AdminOrderDetails
    {
        $row = $this->db
            ->createQuery()
            ->select([
                'orders.id',
                'orders.user_id',
                'users.email AS user_email',
                'orders.status',
                'orders.total_amount',
                'orders.transaction_number',
                'orders.invoice_number',
                'orders.created_at',
                'orders.updated_at',
            ])
            ->from('orders')
            ->innerJoin('users', 'users.id = orders.user_id')
            ->where(['orders.id' => $id])
            ->one();

        if ($row === null || $row === false) {
            return null;
        }

        return new AdminOrderDetails(
            id: (int) $row['id'],
            userId: (int) $row['user_id'],
            userEmail: (string) $row['user_email'],
            status: OrderStatus::from((string) $row['status']),
            totalAmount: (string) $row['total_amount'],
            transactionNumber: (string) $row['transaction_number'],
            invoiceNumber: (string) $row['invoice_number'],
            createdAt: new DateTimeImmutable((string) $row['created_at']),
            updatedAt: $row['updated_at'] === null
                ? null
                : new DateTimeImmutable((string) $row['updated_at']),
            items: $this->getItems($id),
        );
    }

    /**
     * @return OrderItemSummary[]
     */
    private function getItems(int $orderId): array
    {
        $rows = $this->db
            ->createQuery()
            ->select([
                'product_title',
                'quantity',
                'unit_price',
            ])
            ->from('order_items')
            ->where(['order_id' => $orderId])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        return array_map(
            static fn (array $row): OrderItemSummary => new OrderItemSummary(
                productTitle: (string) $row['product_title'],
                quantity: (int) $row['quantity'],
                unitPrice: (string) $row['unit_price'],
            ),
            $rows,
        );
    }
}
