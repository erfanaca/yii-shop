<?php

declare(strict_types=1);

namespace App\Product;

use DateTimeImmutable;
use DateTimeInterface;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Expression\Expression;

final class ProductRepository
{
    public function __construct(
        private readonly ConnectionInterface $db,
    ) {
    }

    /**
     * @return Product[]
     */
    public function findAll(): array
    {
        $rows = $this->db
            ->createQuery()
            ->from('products')
            ->all();

        return array_map($this->createProductFromRow(...), $rows);
    }

    public function findById(int $id): ?Product
    {
        $row = $this->db
            ->createQuery()
            ->from('products')
            ->where(['id' => $id])
            ->limit(1)
            ->one();

        if ($row === null || $row === false) {
            return null;
        }

        return $this->createProductFromRow($row);
    }

    /**
     * @return int[]
     */
    public function findCategoryIds(int $productId): array
    {
        $rows = $this->db
            ->createQuery()
            ->from('product_categories')
            ->where(['product_id' => $productId])
            ->all();

        return array_map(
            static fn (array $row): int => (int) $row['category_id'],
            $rows,
        );
    }

    public function create(
        string $title,
        ?string $description,
        int $quantity,
        string $price,
    ): Product {
        $now = new DateTimeImmutable();

        $this->db
            ->createCommand()
            ->insert('products', [
                'title' => $title,
                'description' => $description,
                'quantity' => $quantity,
                'price' => $price,
                'created_at' => $now,
                'updated_at' => null,
            ])
            ->execute();

        return new Product(
            id: (int) $this->db->getLastInsertId(),
            title: $title,
            description: $description,
            quantity: $quantity,
            price: $price,
            createdAt: $now,
            updatedAt: null,
        );
    }

    public function update(
        Product $product,
        string $title,
        ?string $description,
        int $quantity,
        string $price,
    ): Product {
        $updatedAt = new DateTimeImmutable();

        $this->db
            ->createCommand()
            ->update(
                'products',
                [
                    'title' => $title,
                    'description' => $description,
                    'quantity' => $quantity,
                    'price' => $price,
                    'updated_at' => $updatedAt,
                ],
                ['id' => $product->getId()],
            )
            ->execute();

        return new Product(
            id: $product->getId(),
            title: $title,
            description: $description,
            quantity: $quantity,
            price: $price,
            createdAt: $product->getCreatedAt(),
            updatedAt: $updatedAt,
        );
    }

    public function decreaseStock(int $productId, int $quantity): bool
    {
        if ($quantity <= 0) {
            return false;
        }

        $affectedRows = $this->db
            ->createCommand()
            ->update(
                'products',
                ['quantity' => new Expression('quantity - ' . $quantity)],
                ['and', ['id' => $productId], ['>=', 'quantity', $quantity]],
            )
            ->execute();

        return $affectedRows === 1;
    }

    public function delete(Product $product): void
    {
        $this->db
            ->createCommand()
            ->delete('products', ['id' => $product->getId()])
            ->execute();
    }

    /**
     * @param int[] $categoryIds
     */
    public function syncCategories(int $productId, array $categoryIds): void
    {
        $this->db
            ->createCommand()
            ->delete('product_categories', ['product_id' => $productId])
            ->execute();

        foreach ($categoryIds as $categoryId) {
            $this->db
                ->createCommand()
                ->insert('product_categories', [
                    'product_id' => $productId,
                    'category_id' => $categoryId,
                ])
                ->execute();
        }
    }

    public function addImage(int $productId, string $path, int $sortOrder): void
    {
        $now = new DateTimeImmutable();

        $this->db
            ->createCommand()
            ->insert('product_images', [
                'product_id' => $productId,
                'path' => $path,
                'sort_order' => $sortOrder,
                'created_at' => $now,
                'updated_at' => null,
            ])
            ->execute();
    }

    /**
     * @return ProductImage[]
     */
    public function findImages(int $productId): array
    {
        $rows = $this->db
            ->createQuery()
            ->from('product_images')
            ->where(['product_id' => $productId])
            ->orderBy(['sort_order' => SORT_ASC])
            ->all();

        return array_map(
            static fn(array $row): ProductImage => new ProductImage(
                id: (int) $row['id'],
                productId: (int) $row['product_id'],
                path: (string) $row['path'],
                sortOrder: (int) $row['sort_order'],
                createdAt: new DateTimeImmutable($row['created_at']),
                updatedAt: $row['updated_at'] !== null
                    ? new DateTimeImmutable($row['updated_at'])
                    : null,
            ),
            $rows,
        );
    }

    public function deleteImage(int $imageId): ?string
    {
        $image = $this->db
            ->createQuery()
            ->from('product_images')
            ->where(['id' => $imageId])
            ->one();

        if (!$image) {
            return null;
        }

        $this->db
            ->createCommand()
            ->delete(
                'product_images',
                ['id' => $imageId]
            )
            ->execute();

        return $image['path'];
    }

    private function createProductFromRow(array $row): Product
    {
        return new Product(
            id: (int) $row['id'],
            title: (string) $row['title'],
            description: $row['description'] === null
                ? null
                : (string) $row['description'],
            quantity: (int) $row['quantity'],
            price: (string) $row['price'],
            createdAt: $this->toDateTimeImmutable($row['created_at']),
            updatedAt: $row['updated_at'] === null
                ? null
                : $this->toDateTimeImmutable($row['updated_at']),
        );
    }

    private function toDateTimeImmutable(mixed $value): DateTimeImmutable
    {
        if ($value instanceof DateTimeImmutable) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            return DateTimeImmutable::createFromInterface($value);
        }

        return new DateTimeImmutable((string) $value);
    }
}
