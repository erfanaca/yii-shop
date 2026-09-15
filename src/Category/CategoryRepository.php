<?php

declare(strict_types=1);

namespace App\Category;

use DateTimeImmutable;
use DateTimeInterface;
use Yiisoft\Db\Connection\ConnectionInterface;

final class CategoryRepository
{
    public function __construct(
        private readonly ConnectionInterface $db,
    ) {
    }

    /**
     * @return Category[]
     */
    public function findAll(): array
    {
        $rows = $this->db
            ->createQuery()
            ->from('categories')
            ->all();

        return array_map($this->createCategoryFromRow(...), $rows);
    }

    public function findById(int $id): ?Category
    {
        $row = $this->db
            ->createQuery()
            ->from('categories')
            ->where(['id' => $id])
            ->limit(1)
            ->one();

        if ($row === null || $row === false) {
            return null;
        }

        return $this->createCategoryFromRow($row);
    }

    public function findByTitle(string $title): ?Category
    {
        $row = $this->db
            ->createQuery()
            ->from('categories')
            ->where(['title' => $title])
            ->limit(1)
            ->one();

        if ($row === null || $row === false) {
            return null;
        }

        return $this->createCategoryFromRow($row);
    }

    private function createCategoryFromRow(array $row): Category
    {
        return new Category(
            id: (int) $row['id'],
            title: (string) $row['title'],
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
