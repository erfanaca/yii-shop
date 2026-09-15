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
     * Category IDs and image paths are kept as optional persistence inputs because
     * these relations already exist in the current schema. The current admin form
     * intentionally does not expose them until their UI/upload requirements are defined.
     *
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

        return $this->db->transaction(function () use (
            $title,
            $description,
            $quantity,
            $price,
            $categoryIds,
            $imagePaths,
        ): Product {
            foreach ($categoryIds as $categoryId) {
                if ($this->categories->findById($categoryId) === null) {
                    throw new InvalidArgumentException(
                        "Category with ID {$categoryId} does not exist.",
                    );
                }
            }

            $product = $this->products->create(
                title: $title,
                description: $description,
                quantity: $quantity,
                price: $price,
            );

            foreach ($categoryIds as $categoryId) {
                $this->products->addCategory($product->getId(), $categoryId);
            }

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

    public function update(
        Product $product,
        string $title,
        ?string $description,
        int $quantity,
        string $price,
    ): Product {
        return $this->products->update(
            product: $product,
            title: trim($title),
            description: $this->normalizeDescription($description),
            quantity: $quantity,
            price: trim($price),
        );
    }

    public function delete(Product $product): void
    {
        $this->products->delete($product);
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
