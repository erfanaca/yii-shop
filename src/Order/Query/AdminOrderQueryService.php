<?php

declare(strict_types=1);

namespace App\Order\Query;

use App\Order\Order;
use App\Order\OrderItem;
use App\User\User;

final readonly class AdminOrderQueryService
{
    public function findAll(): array
    {
        $orders = Order::query()
            ->orderBy(['created_at' => SORT_DESC, 'id' => SORT_DESC])
            ->all();

        if ($orders === []) {
            return [];
        }

        $usersById = $this->findUsersByIds(array_map(
            static fn (Order $order): int => $order->getUserId(),
            $orders,
        ));

        $result = [];
        foreach ($orders as $order) {
            $user = $usersById[$order->getUserId()] ?? null;

            if ($user === null) {
                continue;
            }

            $result[] = new AdminOrderSummary(
                id: $order->getId(),
                userId: $order->getUserId(),
                userEmail: $user->getEmail(),
                status: $order->getStatus(),
                totalAmount: $order->getTotalAmount(),
                discountCode: $order->discount_code,
                discountAmount: $order->discount_amount,
                transactionNumber: $order->getTransactionNumber(),
                invoiceNumber: $order->getInvoiceNumber(),
                createdAt: $order->getCreatedAt(),
                updatedAt: $order->getUpdatedAt(),
            );
        }

        return $result;
    }

    public function findById(int $id): ?AdminOrderDetails
    {
        $order = Order::query()
            ->where(['id' => $id])
            ->one();

        if ($order === null) {
            return null;
        }

        $user = User::query()
            ->where(['id' => $order->getUserId()])
            ->one();

        if ($user === null) {
            return null;
        }

        return new AdminOrderDetails(
            id: $order->getId(),
            userId: $order->getUserId(),
            userEmail: $user->getEmail(),
            status: $order->getStatus(),
            totalAmount: $order->getTotalAmount(),
            discountCode: $order->discount_code,
            discountAmount: $order->discount_amount,
            transactionNumber: $order->getTransactionNumber(),
            invoiceNumber: $order->getInvoiceNumber(),
            createdAt: $order->getCreatedAt(),
            updatedAt: $order->getUpdatedAt(),
            items: $this->getItems($order->getId()),
        );
    }

    private function getItems(int $orderId): array
    {
        $items = OrderItem::query()
            ->where(['order_id' => $orderId])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        return array_map(
            static fn (OrderItem $item): OrderItemSummary => new OrderItemSummary(
                productTitle: $item->getProductTitle(),
                quantity: $item->getQuantity(),
                unitPrice: $item->getUnitPrice(),
            ),
            $items,
        );
    }


    private function findUsersByIds(array $userIds): array
    {
        $userIds = array_values(array_unique($userIds));
        $users = User::query()
            ->where(['id' => $userIds])
            ->all();

        $result = [];
        foreach ($users as $user) {
            $result[(int) $user->getId()] = $user;
        }

        return $result;
    }
}
