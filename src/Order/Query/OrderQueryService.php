<?php

declare(strict_types=1);

namespace App\Order\Query;

use App\Order\OrderStatus;
use DateTimeImmutable;
use Yiisoft\Db\Connection\ConnectionInterface;

final readonly class OrderQueryService
{
    public function __construct(
        private ConnectionInterface $db,
    ) {
    }

    public function getDashboardForUser(int $userId): OrderDashboard
    {
        $rows = $this->db
            ->createQuery()
            ->select([
                'id',
                'status',
                'total_amount',
                'transaction_number',
                'invoice_number',
                'created_at',
            ])
            ->from('orders')
            ->where(['user_id' => $userId])
            ->orderBy(['created_at' => SORT_DESC, 'id' => SORT_DESC])
            ->all();

        if ($rows === []) {
            return new OrderDashboard(
                totalOrders: 0,
                paidOrders: 0,
                cancelledOrders: 0,
                pendingOrders: 0,
                totalPaidAmount: '0.00',
                orders: [],
            );
        }

        $orderIds = array_map(
            static fn (array $row): int => (int) $row['id'],
            $rows,
        );

        $itemsByOrderId = $this->getItemsByOrderIds($orderIds);
        $orders = [];
        $paidOrders = 0;
        $cancelledOrders = 0;
        $pendingOrders = 0;
        $totalPaidAmount = 0.0;

        foreach ($rows as $row) {
            $status = OrderStatus::from((string) $row['status']);
            $totalAmount = (string) $row['total_amount'];

            if ($status === OrderStatus::Paid) {
                $paidOrders++;
                $totalPaidAmount += (float) $totalAmount;
            } elseif ($status === OrderStatus::Cancelled) {
                $cancelledOrders++;
            } else {
                $pendingOrders++;
            }

            $orderId = (int) $row['id'];

            $orders[] = new OrderSummary(
                id: $orderId,
                status: $status,
                totalAmount: $totalAmount,
                transactionNumber: (string) $row['transaction_number'],
                invoiceNumber: (string) $row['invoice_number'],
                createdAt: new DateTimeImmutable((string) $row['created_at']),
                items: $itemsByOrderId[$orderId] ?? [],
            );
        }

        return new OrderDashboard(
            totalOrders: count($orders),
            paidOrders: $paidOrders,
            cancelledOrders: $cancelledOrders,
            pendingOrders: $pendingOrders,
            totalPaidAmount: number_format($totalPaidAmount, 2, '.', ''),
            orders: $orders,
        );
    }

    /**
     * @param int[] $orderIds
     * @return array<int, OrderItemSummary[]>
     */
    private function getItemsByOrderIds(array $orderIds): array
    {
        $rows = $this->db
            ->createQuery()
            ->select([
                'order_id',
                'product_title',
                'quantity',
                'unit_price',
            ])
            ->from('order_items')
            ->where(['order_id' => $orderIds])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        $itemsByOrderId = [];

        foreach ($rows as $row) {
            $orderId = (int) $row['order_id'];
            $itemsByOrderId[$orderId][] = new OrderItemSummary(
                productTitle: (string) $row['product_title'],
                quantity: (int) $row['quantity'],
                unitPrice: (string) $row['unit_price'],
            );
        }

        return $itemsByOrderId;
    }
}
