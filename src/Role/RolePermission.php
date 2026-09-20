<?php

declare(strict_types=1);

namespace App\Role;

use Yiisoft\ActiveRecord\ActiveRecord;

final class RolePermission extends ActiveRecord
{
    public int $role_id;
    public int $permission_id;

    public function tableName(): string
    {
        return 'role_permissions';
    }

    public function setRoleId(int $roleId): void
    {
        $this->role_id = $roleId;
    }

    public function setPermissionId(int $permissionId): void
    {
        $this->permission_id = $permissionId;
    }
}
