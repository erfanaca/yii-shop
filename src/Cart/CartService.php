<?php

declare(strict_types=1);

namespace App\Cart;

use DateTimeImmutable;
use Yiisoft\Db\Connection\ConnectionInterface;

final readonly class CartService
{
    public function __construct(
        private ConnectionInterface $db,
    ) {
    }

    public function getProductQuantity(int $userId, int $productId): int
    {
        $cartId = $this->findActiveCartId($userId);

        if ($cartId === null) {
            return 0;
        }

        $item = $this->db
            ->createQuery()
            ->select(['quantity'])
            ->from('cart_items')
            ->where([
                'cart_id' => $cartId,
                'product_id' => $productId,
            ])
            ->one();

        if ($item === null || $item === false) {
            return 0;
        }

        return (int) $item['quantity'];
    }

    public function addProduct(int $userId, int $productId, string $unitPrice): void
    {
        $availableQuantity = $this->getAvailableProductQuantity($productId);

        $currentQuantity = $this->getProductQuantity($userId, $productId);

        if ($currentQuantity >= $availableQuantity) {
            return;
        }

        $cartId = $this->findActiveCartId($userId);
        $now = new DateTimeImmutable();

        if ($cartId === null) {
            $this->db
                ->createCommand()
                ->insert('carts', [
                    'user_id' => $userId,
                    'status' => 'ACTIVE',
                    'created_at' => $now,
                    'updated_at' => null,
                ])
                ->execute();

            $cartId = (int) $this->db->getLastInsertId();
        }

        $item = $this->db
            ->createQuery()
            ->from('cart_items')
            ->where([
                'cart_id' => $cartId,
                'product_id' => $productId,
            ])
            ->one();

        if ($item !== null && $item !== false) {
            $this->db
                ->createCommand()
                ->update(
                    'cart_items',
                    [
                        'quantity' => ((int) $item['quantity']) + 1,
                        'updated_at' => $now,
                    ],
                    ['id' => $item['id']],
                )
                ->execute();

            $this->touchCart($cartId, $now);
            return;
        }

        $this->db
            ->createCommand()
            ->insert('cart_items', [
                'cart_id' => $cartId,
                'product_id' => $productId,
                'quantity' => 1,
                'unit_price' => $unitPrice,
                'created_at' => $now,
                'updated_at' => null,
            ])
            ->execute();

        $this->touchCart($cartId, $now);
    }

    public function decreaseProduct(int $userId, int $productId): void
    {
        $cartId = $this->findActiveCartId($userId);

        if ($cartId === null) {
            return;
        }

        $item = $this->db
            ->createQuery()
            ->from('cart_items')
            ->where([
                'cart_id' => $cartId,
                'product_id' => $productId,
            ])
            ->one();

        if ($item === null || $item === false) {
            return;
        }

        $now = new DateTimeImmutable();
        $quantity = (int) $item['quantity'];

        if ($quantity <= 1) {
            $this->db
                ->createCommand()
                ->delete('cart_items', ['id' => $item['id']])
                ->execute();

            $this->touchCart($cartId, $now);
            return;
        }

        $this->db
            ->createCommand()
            ->update(
                'cart_items',
                [
                    'quantity' => $quantity - 1,
                    'updated_at' => $now,
                ],
                ['id' => $item['id']],
            )
            ->execute();

        $this->touchCart($cartId, $now);
    }


    /**
     * @return array<int, array<string, mixed>>
     */
    public function getItems(int $userId): array
    {
        $cartId = $this->findActiveCartId($userId);

        if ($cartId === null) {
            return [];
        }

        return $this->db
            ->createQuery()
            ->select([
                'cart_items.id',
                'cart_items.product_id',
                'cart_items.quantity',
                'cart_items.unit_price',
                'products.title',
                'products.quantity AS stock',
            ])
            ->from('cart_items')
            ->innerJoin('products', 'products.id = cart_items.product_id')
            ->where(['cart_items.cart_id' => $cartId])
            ->all();
    }

    public function removeProduct(int $userId, int $productId): void
    {
        $cartId = $this->findActiveCartId($userId);

        if ($cartId === null) {
            return;
        }

        $this->db
            ->createCommand()
            ->delete('cart_items', [
                'cart_id' => $cartId,
                'product_id' => $productId,
            ])
            ->execute();

        $this->touchCart($cartId, new DateTimeImmutable());
    }

    public function complete(int $userId): void
    {
        $cartId = $this->findActiveCartId($userId);

        if ($cartId === null) {
            throw new \RuntimeException('Active cart not found.');
        }

        $this->db
            ->createCommand()
            ->update(
                'carts',
                [
                    'status' => 'COMPLETED',
                    'updated_at' => new DateTimeImmutable(),
                ],
                ['id' => $cartId],
            )
            ->execute();
    }

    public function updateQuantity(int $userId, int $productId, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->removeProduct($userId, $productId);
            return;
        }

        $cartId = $this->findActiveCartId($userId);

        if ($cartId === null) {
            return;
        }

        $this->db
            ->createCommand()
            ->update(
                'cart_items',
                [
                    'quantity' => $quantity,
                    'updated_at' => new DateTimeImmutable(),
                ],
                [
                    'cart_id' => $cartId,
                    'product_id' => $productId,
                ],
            )
            ->execute();

        $this->touchCart($cartId, new DateTimeImmutable());
    }

    private function getAvailableProductQuantity(int $productId): int
    {
        $product = $this->db
            ->createQuery()
            ->select(['quantity'])
            ->from('products')
            ->where(['id' => $productId])
            ->one();

        if ($product === null || $product === false) {
            return 0;
        }

        return (int) $product['quantity'];
    }

    private function findActiveCartId(int $userId): ?int
    {
        $cart = $this->db
            ->createQuery()
            ->select(['id'])
            ->from('carts')
            ->where([
                'user_id' => $userId,
                'status' => 'ACTIVE',
            ])
            ->one();

        if ($cart === null || $cart === false) {
            return null;
        }

        return (int) $cart['id'];
    }

    private function touchCart(int $cartId, DateTimeImmutable $now): void
    {
        $this->db
            ->createCommand()
            ->update(
                'carts',
                ['updated_at' => $now],
                ['id' => $cartId],
            )
            ->execute();
    }
}
