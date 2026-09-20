<?php

declare(strict_types=1);

namespace App\Category;

use DateTimeImmutable;

final class CategoryRepository
{
    public function findAll(): array
    {
        return Category::query()
            ->orderBy(['id' => SORT_ASC])
            ->all();
    }

    public function findById(int $id): ?Category
    {
        return Category::query()
            ->where(['id' => $id])
            ->one();
    }

    public function findByTitle(string $title): ?Category
    {
        return Category::query()
            ->where(['title' => $title])
            ->one();
    }

    public function create(string $title): Category
    {
        $category = new Category();
        $category->setTitle($title);
        $category->setCreatedAt(new DateTimeImmutable());
        $category->save();

        return $category;
    }

    public function update(Category $category, string $title): Category
    {
        $category->setTitle($title);
        $category->setUpdatedAt(new DateTimeImmutable());
        $category->save();

        return $category;
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}
