<?php

declare(strict_types=1);

namespace App\Role;

final class RoleRepository
{
    public function findAll(): array
    {
        return Role::query()
            ->orderBy(['id' => SORT_ASC])
            ->all();
    }

    public function findById(int $id): ?Role
    {
        return Role::query()
            ->where(['id' => $id])
            ->one();
    }

    public function findByTitle(string $title): ?Role
    {
        return Role::query()
            ->where(['title' => $title])
            ->one();
    }

    public function create(string $title): void
    {
        $role = new Role();
        $role->setTitle($title);
        $role->save();
    }

    public function update(Role $role, string $title): void
    {
        $role->setTitle($title);
        $role->save();
    }

    public function delete(Role $role): void
    {
        $role->delete();
    }

    public function permissionIds(int $roleId): array
    {
        $rows = RolePermission::query()
            ->where(['role_id' => $roleId])
            ->orderBy(['permission_id' => SORT_ASC])
            ->all();

        return array_map(
            static fn (RolePermission $row): int => $row->permission_id,
            $rows,
        );
    }

    public function syncPermissions(int $roleId, array $ids): void
    {
        $rows = RolePermission::query()
            ->where(['role_id' => $roleId])
            ->all();

        foreach ($rows as $row) {
            $row->delete();
        }

        foreach (array_values(array_unique(array_map('intval', $ids))) as $permissionId) {
            $rolePermission = new RolePermission();
            $rolePermission->setRoleId($roleId);
            $rolePermission->setPermissionId($permissionId);
            $rolePermission->save();
        }
    }
}
