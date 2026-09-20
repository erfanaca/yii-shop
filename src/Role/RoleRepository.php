<?php

declare(strict_types=1);

namespace App\Role;

use App\Permission\Permission;
use Yiisoft\Db\Connection\ConnectionInterface;

final class RoleRepository
{
    public function __construct(private readonly ConnectionInterface $db)
    {
    }

    public function findAll(): array
    {
        return Role::query()
            ->all();
    }

    public function findById(int $id): ?Role
    {
        return Role::query()
            ->where(['id' => $id])
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
        return array_map(fn($r) => (int)$r['permission_id'], $this->db->createQuery()->select('permission_id')->from('role_permissions')->where(['role_id' => $roleId])->all());
    }

    public function syncPermissions(int $roleId, array $ids): void
    {
        $this->db->createCommand()->delete('role_permissions', ['role_id' => $roleId])->execute();
        foreach ($ids as $id) {
            $this->db->createCommand()->insert('role_permissions', ['role_id' => $roleId, 'permission_id' => $id])->execute();
        }
    }
}
