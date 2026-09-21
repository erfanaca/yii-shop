<?php

declare(strict_types=1);

namespace App\Product;

final class ProductRepository
{
    public function findAll(): array
    {
        return Product::query()
            ->orderBy(['id' => SORT_ASC])
            ->all();
    }

    public function findById(int $id): ?Product
    {
        return Product::query()
            ->where(['id' => $id])
            ->one();
    }

    public function findCategoryIds(int $productId): array
    {
        $rows = ProductCategory::query()
            ->where(['product_id' => $productId])
            ->orderBy(['category_id' => SORT_ASC])
            ->all();

        return array_map(
            static fn (ProductCategory $row): int => $row->category_id,
            $rows,
        );
    }

    public function create(
        string $title,
        ?string $description,
        int $quantity,
        string $price,
    ): Product {
        $product = new Product();
        $product->setTitle($title);
        $product->setDescription($description);
        $product->setQuantity($quantity);
        $product->setPrice($price);
        $product->save();

        return $product;
    }

    public function update(
        Product $product,
        string $title,
        ?string $description,
        int $quantity,
        string $price,
    ): Product {
        $product->setTitle($title);
        $product->setDescription($description);
        $product->setQuantity($quantity);
        $product->setPrice($price);
        $product->save();

        return $product;
    }

    public function decreaseStock(int $productId, int $quantity): bool
    {
        if ($quantity <= 0) {
            return false;
        }

        $product = $this->findById($productId);

        if ($product === null || $product->getQuantity() < $quantity) {
            return false;
        }

        $product->setQuantity($product->getQuantity() - $quantity);
        $product->save();

        return true;
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    public function syncCategories(int $productId, array $categoryIds): void
    {
        $rows = ProductCategory::query()
            ->where(['product_id' => $productId])
            ->all();

        foreach ($rows as $row) {
            $row->delete();
        }

        foreach ($categoryIds as $categoryId) {
            $productCategory = new ProductCategory();
            $productCategory->setProductId($productId);
            $productCategory->setCategoryId($categoryId);
            $productCategory->save();
        }
    }

    public function addImage(int $productId, string $path, int $sortOrder): void
    {
        $productImage = new ProductImage();
        $productImage->setProductId($productId);
        $productImage->setPath($path);
        $productImage->setSortOrder($sortOrder);
        $productImage->save();
    }

    public function findImages(int $productId): array
    {
        return ProductImage::query()
            ->where(['product_id' => $productId])
            ->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC])
            ->all();
    }

    public function deleteImage(int $imageId): ?string
    {
        $productImage = ProductImage::query()
            ->where(['id' => $imageId])
            ->one();

        if ($productImage === null) {
            return null;
        }

        $path = $productImage->getPath();
        $productImage->delete();

        return $path;
    }
}
