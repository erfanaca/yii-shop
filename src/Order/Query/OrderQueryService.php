<?php

declare(strict_types=1);

namespace App\Order\Query;

use App\Order\Order;
use App\Order\OrderItem;
use App\Order\OrderStatus;

final readonly class OrderQueryService
{
    public function getDashboardForUser(int $userId): OrderDashboard
    {
        $orders = Order::query()
            ->where(['user_id' => $userId])
            ->orderBy(['created_at' => SORT_DESC, 'id' => SORT_DESC])
            ->all();

        if ($orders === []) {
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
            static fn (Order $order): int => $order->getId(),
            $orders,
        );

        $itemsByOrderId = $this->getItemsByOrderIds($orderIds);
        $summaries = [];
        $paidOrders = 0;
        $cancelledOrders = 0;
        $pendingOrders = 0;
        $totalPaidAmount = 0.0;

        foreach ($orders as $order) {
            $status = $order->getStatus();
            $totalAmount = $order->getTotalAmount();

            if ($status === OrderStatus::Paid) {
                $paidOrders++;
                $totalPaidAmount += (float) $totalAmount;
            } elseif ($status === OrderStatus::Cancelled) {
                $cancelledOrders++;
            } else {
                $pendingOrders++;
            }

            $orderId = $order->getId();

            $summaries[] = new OrderSummary(
                id: $orderId,
                status: $status,
                totalAmount: $totalAmount,
                discountCode: $order->discount_code,
                discountAmount: $order->discount_amount,
                transactionNumber: $order->getTransactionNumber(),
                invoiceNumber: $order->getInvoiceNumber(),
                createdAt: $order->getCreatedAt(),
                items: $itemsByOrderId[$orderId] ?? [],
            );
        }

        return new OrderDashboard(
            totalOrders: count($summaries),
            paidOrders: $paidOrders,
            cancelledOrders: $cancelledOrders,
            pendingOrders: $pendingOrders,
            totalPaidAmount: number_format($totalPaidAmount, 2, '.', ''),
            orders: $summaries,
        );
    }

    private function getItemsByOrderIds(array $orderIds): array
    {
        $items = OrderItem::query()
            ->where(['order_id' => $orderIds])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        $itemsByOrderId = [];

        foreach ($items as $item) {
            $itemsByOrderId[$item->getOrderId()][] = new OrderItemSummary(
                productTitle: $item->getProductTitle(),
                quantity: $item->getQuantity(),
                unitPrice: $item->getUnitPrice(),
            );
        }

        return $itemsByOrderId;
    }
}
