<?php

declare(strict_types=1);

namespace App\Product;



final class ProductRepository
{
    /**
     * @return Product[]
     */
    public function findAll(): array
    {
        return Product::query()->all();
    }

    public function findById(int $id): ?Product
    {
        return Product::query()->findByPk($id);
    }

    /**
     * @return Product[]
     */
    public function findAllWithRelations(): array
    {
        return Product::query()
            ->with(['images', 'categories'])
            ->all();
    }

    public function save(Product $product): void
    {
        $product->save();
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}
