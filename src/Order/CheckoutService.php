<?php

declare(strict_types=1);

namespace App\Order;

use App\Cart\CartService;
use App\Discount\CartDiscountService;
use App\Discount\DiscountApplicationException;
use App\Product\ProductRepository;
use Yiisoft\Db\Connection\ConnectionInterface;

final readonly class CheckoutService
{
    public function __construct(
        private ConnectionInterface $db,
        private CartService $cartService,
        private OrderRepository $orders,
        private ProductRepository $products,
        private CartDiscountService $discounts,
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
            }

            try {
                $pricing = $this->discounts->requireValidPricing($userId, $items);
            } catch (DiscountApplicationException $exception) {
                throw new CheckoutException($exception->getMessage(), previous: $exception);
            }

            $discountCode = $pricing->getDiscountCode();

            $order = $this->orders->createPending(
                userId: $userId,
                totalAmount: $pricing->getTotalAmount(),
                subtotalAmount: $pricing->getSubtotalAmount(),
                discountAmount: $pricing->getDiscountAmount(),
                discountCode: $discountCode?->getCode(),
                discountType: $discountCode?->getType()->value,
                discountValue: $discountCode?->getValue(),
                discountEligibleSubtotal: $pricing->getEligibleSubtotalAmount(),
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
}
