<?php

declare(strict_types=1);

namespace App\Category;

final class CategoryRepository
{
    /**
     * @return Category[]
     */
    public function findAll(): array
    {
        return Category::query()->all();
    }

    public function findById(int $id): ?Category
    {
        return Category::query()->findByPk($id);
    }

    public function findByTitle(string $title): ?Category
    {
        return Category::query()
            ->where(['title' => $title])
            ->one();
    }

    public function save(Category $category): void
    {
        $category->save();
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}
