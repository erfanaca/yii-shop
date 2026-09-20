<?php

declare(strict_types=1);

namespace App\Permission;

final class PermissionRepository
{
    public function findAll(): array
    {
        return Permission::query()
            ->orderBy(['id' => SORT_ASC])
            ->all();
    }

    public function findById(int $id): ?Permission
    {
        return Permission::query()
            ->where(['id' => $id])
            ->one();
    }

    public function findByTitle(string $title): ?Permission
    {
        return Permission::query()
            ->where(['title' => $title])
            ->one();
    }

    public function create(string $title): void
    {
        $permission = new Permission();
        $permission->setTitle($title);
        $permission->save();
    }

    public function update(Permission $permission, string $title): void
    {
        $permission->setTitle($title);
        $permission->save();
    }

    public function delete(Permission $permission): void
    {
        $permission->delete();
    }
}
