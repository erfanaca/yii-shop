<?php

declare(strict_types=1);

namespace App\Product;

use App\Category\CategoryRepository;
use InvalidArgumentException;
use Yiisoft\Db\Connection\ConnectionInterface;

final class ProductService
{
    public function __construct(
        private readonly ConnectionInterface $db,
        private readonly ProductRepository $products,
        private readonly CategoryRepository $categories,
    ) {
    }

    /**
     * @param int[] $categoryIds
     * @param string[] $imagePaths
     */
    public function create(
        string $title,
        ?string $description,
        string $quantity,
        string $price,
        array $categoryIds = [],
        array $imagePaths = [],
    ): Product {
        $transaction = $this->db->beginTransaction();

        try {
            $now = date('Y-m-d H:i:s');

            $product = new Product();

            $product->title = $title;
            $product->description = $description;
            $product->quantity = $quantity;
            $product->price = $price;
            $product->created_at = $now;

            $this->products->save($product);

            foreach ($categoryIds as $categoryId) {
                $category = $this->categories->findById($categoryId);

                if ($category === null) {
                    throw new InvalidArgumentException(
                        "Category with ID {$categoryId} does not exist.",
                    );
                }

                $product->link('categories', $category);
            }

            foreach ($imagePaths as $sortOrder => $path) {
                $image = new ProductImage();

                $image->path = $path;
                $image->sort_order = $sortOrder;
                $image->created_at = $now;

                $product->link('images', $image);
            }

            $transaction->commit();

            return $product;
        } catch (\Throwable $e) {
            $transaction->rollBack();

            throw $e;
        }
    }
}