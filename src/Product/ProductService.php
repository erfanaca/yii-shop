<?php

declare(strict_types=1);

namespace App\Product;

use App\Category\CategoryRepository;
use InvalidArgumentException;
use Yiisoft\Db\Connection\ConnectionInterface;

final readonly class ProductService
{
    public function __construct(
        private ConnectionInterface $db,
        private ProductRepository $products,
        private CategoryRepository $categories,
    ) {
    }

    /**
     * @param int[] $categoryIds
     * @param string[] $imagePaths
     */
    public function create(
        string $title,
        ?string $description,
        int $quantity,
        string $price,
        array $categoryIds = [],
        array $imagePaths = [],
    ): Product {
        $title = trim($title);
        $description = $this->normalizeDescription($description);
        $price = trim($price);
        $categoryIds = $this->normalizeCategoryIds($categoryIds);
        $imagePaths = $this->normalizeImagePaths($imagePaths);

        $this->assertCategoriesExist($categoryIds);

        return $this->db->transaction(function () use (
            $title,
            $description,
            $quantity,
            $price,
            $categoryIds,
            $imagePaths,
        ): Product {
            $product = $this->products->create(
                title: $title,
                description: $description,
                quantity: $quantity,
                price: $price,
            );

            $this->products->syncCategories($product->getId(), $categoryIds);

            foreach ($imagePaths as $sortOrder => $path) {
                $this->products->addImage(
                    productId: $product->getId(),
                    path: $path,
                    sortOrder: $sortOrder,
                );
            }

            return $product;
        });
    }

    /**
     * @param int[] $categoryIds
     */
    public function update(
        Product $product,
        string $title,
        ?string $description,
        int $quantity,
        string $price,
        array $categoryIds = [],
    ): Product {
        $title = trim($title);
        $description = $this->normalizeDescription($description);
        $price = trim($price);
        $categoryIds = $this->normalizeCategoryIds($categoryIds);

        $this->assertCategoriesExist($categoryIds);

        return $this->db->transaction(function () use (
            $product,
            $title,
            $description,
            $quantity,
            $price,
            $categoryIds,
        ): Product {
            $updatedProduct = $this->products->update(
                product: $product,
                title: $title,
                description: $description,
                quantity: $quantity,
                price: $price,
            );

            $this->products->syncCategories($product->getId(), $categoryIds);

            return $updatedProduct;
        });
    }

    public function delete(Product $product): void
    {
        $this->products->delete($product);
    }

    /**
     * @param int[] $categoryIds
     */
    private function assertCategoriesExist(array $categoryIds): void
    {
        foreach ($categoryIds as $categoryId) {
            if ($this->categories->findById($categoryId) !== null) {
                continue;
            }

            throw new InvalidArgumentException(
                "Category with ID {$categoryId} does not exist.",
            );
        }
    }

    private function normalizeDescription(?string $description): ?string
    {
        if ($description === null) {
            return null;
        }

        $description = trim($description);

        return $description === '' ? null : $description;
    }

    /**
     * @param int[] $categoryIds
     * @return int[]
     */
    private function normalizeCategoryIds(array $categoryIds): array
    {
        $normalized = [];

        foreach ($categoryIds as $categoryId) {
            $categoryId = (int) $categoryId;

            if ($categoryId <= 0) {
                throw new InvalidArgumentException('Category IDs must be positive integers.');
            }

            $normalized[$categoryId] = $categoryId;
        }

        return array_values($normalized);
    }

    /**
     * @param string[] $imagePaths
     * @return string[]
     */
    private function normalizeImagePaths(array $imagePaths): array
    {
        $normalized = [];

        foreach ($imagePaths as $path) {
            $path = trim($path);

            if ($path === '') {
                throw new InvalidArgumentException('Product image path cannot be empty.');
            }

            $normalized[] = $path;
        }

        return $normalized;
    }
}
