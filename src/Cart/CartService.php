<?php

declare(strict_types=1);

namespace App\Cart;

use App\Product\Product;
use DateTimeImmutable;

final readonly class CartService
{
    public function getProductQuantity(int $userId, int $productId): int
    {
        $cart = $this->findActiveCart($userId);

        if ($cart === null) {
            return 0;
        }

        $item = CartItem::query()
            ->where([
                'cart_id' => $cart->getId(),
                'product_id' => $productId,
            ])
            ->one();

        return $item?->quantity ?? 0;
    }

    public function addProduct(int $userId, int $productId, string $unitPrice): void
    {
        $availableQuantity = $this->getAvailableProductQuantity($productId);
        $currentQuantity = $this->getProductQuantity($userId, $productId);

        if ($currentQuantity >= $availableQuantity) {
            return;
        }

        $cart = $this->findActiveCart($userId);
        $now = new DateTimeImmutable();

        if ($cart === null) {
            $cart = new Cart();
            $cart->setUserId($userId);
            $cart->setStatus('ACTIVE');
            $cart->setCreatedAt($now);
            $cart->save();
        }

        $item = CartItem::query()
            ->where([
                'cart_id' => $cart->getId(),
                'product_id' => $productId,
            ])
            ->one();

        if ($item !== null) {
            $item->setQuantity($item->quantity + 1);
            $item->setUpdatedAt($now);
            $item->save();
            $this->touchCart($cart, $now);
            return;
        }

        $item = new CartItem();
        $item->setCartId($cart->getId());
        $item->setProductId($productId);
        $item->setQuantity(1);
        $item->setUnitPrice($unitPrice);
        $item->setCreatedAt($now);
        $item->save();

        $this->touchCart($cart, $now);
    }

    public function decreaseProduct(int $userId, int $productId): void
    {
        $cart = $this->findActiveCart($userId);

        if ($cart === null) {
            return;
        }

        $item = CartItem::query()
            ->where([
                'cart_id' => $cart->getId(),
                'product_id' => $productId,
            ])
            ->one();

        if ($item === null) {
            return;
        }

        $now = new DateTimeImmutable();

        if ($item->quantity <= 1) {
            $item->delete();
            $this->touchCart($cart, $now);
            return;
        }

        $item->setQuantity($item->quantity - 1);
        $item->setUpdatedAt($now);
        $item->save();

        $this->touchCart($cart, $now);
    }


    public function getItems(int $userId): array
    {
        $cart = $this->findActiveCart($userId);

        if ($cart === null) {
            return [];
        }

        $items = CartItem::query()
            ->where(['cart_id' => $cart->getId()])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        if ($items === []) {
            return [];
        }

        $productIds = array_values(array_unique(array_map(
            static fn (CartItem $item): int => $item->product_id,
            $items,
        )));

        $products = Product::query()
            ->where(['id' => $productIds])
            ->all();

        $productsById = [];
        foreach ($products as $product) {
            $productsById[$product->getId()] = $product;
        }

        $result = [];
        foreach ($items as $item) {
            $product = $productsById[$item->product_id] ?? null;

            if ($product === null) {
                continue;
            }

            $result[] = [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'title' => $product->getTitle(),
                'stock' => $product->getQuantity(),
            ];
        }

        return $result;
    }

    public function removeProduct(int $userId, int $productId): void
    {
        $cart = $this->findActiveCart($userId);

        if ($cart === null) {
            return;
        }

        $items = CartItem::query()
            ->where([
                'cart_id' => $cart->getId(),
                'product_id' => $productId,
            ])
            ->all();

        foreach ($items as $item) {
            $item->delete();
        }

        $this->touchCart($cart, new DateTimeImmutable());
    }

    public function getAppliedDiscountCodeId(int $userId): ?int
    {
        return $this->findActiveCart($userId)?->discount_code_id;
    }

    public function applyDiscountCode(int $userId, int $discountCodeId): void
    {
        $cart = $this->findActiveCart($userId);

        if ($cart === null) {
            throw new \RuntimeException('Active cart not found.');
        }

        $cart->setDiscountCodeId($discountCodeId);
        $cart->setUpdatedAt(new DateTimeImmutable());
        $cart->save();
    }

    public function removeDiscountCode(int $userId): void
    {
        $cart = $this->findActiveCart($userId);

        if ($cart === null) {
            return;
        }

        $cart->setDiscountCodeId(null);
        $cart->setUpdatedAt(new DateTimeImmutable());
        $cart->save();
    }

    public function complete(int $userId): void
    {
        $cart = $this->findActiveCart($userId);

        if ($cart === null) {
            throw new \RuntimeException('Active cart not found.');
        }

        $cart->setStatus('COMPLETED');
        $cart->setUpdatedAt(new DateTimeImmutable());
        $cart->save();
    }

    private function getAvailableProductQuantity(int $productId): int
    {
        $product = Product::query()
            ->where(['id' => $productId])
            ->one();

        return $product?->getQuantity() ?? 0;
    }

    private function findActiveCart(int $userId): ?Cart
    {
        return Cart::query()
            ->where([
                'user_id' => $userId,
                'status' => 'ACTIVE',
            ])
            ->orderBy(['id' => SORT_DESC])
            ->one();
    }

    private function touchCart(Cart $cart, DateTimeImmutable $now): void
    {
        $cart->setUpdatedAt($now);
        $cart->save();
    }
}
