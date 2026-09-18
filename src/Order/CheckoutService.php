<?php

declare(strict_types=1);

namespace App\Order;

use App\Cart\CartService;
use App\Product\ProductRepository;
use Yiisoft\Db\Connection\ConnectionInterface;

final readonly class CheckoutService
{
    public function __construct(
        private ConnectionInterface $db,
        private CartService $cartService,
        private OrderRepository $orders,
        private ProductRepository $products,
    ) {
    }

    public function checkout(int $userId, SimulatedPaymentResult $paymentResult): Order
    {
        /** @var Order $order */
        $order = $this->db->transaction(function () use ($userId, $paymentResult): Order {
            $items = $this->cartService->getItems($userId);

            if ($items === []) {
                throw new CheckoutException('Your cart is empty.');
            }

            $totalMinorUnits = 0;

            foreach ($items as $item) {
                $quantity = (int) $item['quantity'];
                $stock = (int) $item['stock'];

                if ($quantity <= 0) {
                    throw new CheckoutException('Cart contains an invalid item quantity.');
                }

                if ($quantity > $stock) {
                    throw new CheckoutException(
                        sprintf('Not enough stock for "%s".', (string) $item['title']),
                    );
                }

                $totalMinorUnits += $this->toMinorUnits((string) $item['unit_price']) * $quantity;
            }

            $order = $this->orders->createPending(
                $userId,
                $this->fromMinorUnits($totalMinorUnits),
            );

            foreach ($items as $item) {
                $this->orders->addItem(
                    orderId: $order->getId(),
                    productId: (int) $item['product_id'],
                    productTitle: (string) $item['title'],
                    quantity: (int) $item['quantity'],
                    unitPrice: (string) $item['unit_price'],
                );
            }

            if ($paymentResult === SimulatedPaymentResult::Failure) {
                return $this->orders->changeStatus($order, OrderStatus::Cancelled);
            }

            foreach ($items as $item) {
                $changed = $this->products->decreaseStock(
                    (int) $item['product_id'],
                    (int) $item['quantity'],
                );

                if (!$changed) {
                    throw new CheckoutException(
                        sprintf('Not enough stock for "%s".', (string) $item['title']),
                    );
                }
            }

            $paidOrder = $this->orders->changeStatus($order, OrderStatus::Paid);
            $this->cartService->complete($userId);

            return $paidOrder;
        });

        return $order;
    }

    private function toMinorUnits(string $amount): int
    {
        $normalized = trim($amount);

        if (!preg_match('/^\d+(?:\.\d{1,2})?$/', $normalized)) {
            throw new CheckoutException('Invalid product price in cart.');
        }

        [$whole, $fraction] = array_pad(explode('.', $normalized, 2), 2, '');
        $fraction = str_pad($fraction, 2, '0');

        return ((int) $whole * 100) + (int) substr($fraction, 0, 2);
    }

    private function fromMinorUnits(int $amount): string
    {
        return sprintf('%d.%02d', intdiv($amount, 100), $amount % 100);
    }
}
