<?php

declare(strict_types=1);

namespace App\Auth;

use App\Permission\Permission;
use App\Role\Role;
use App\Role\RolePermission;
use App\User\UserRole;
use Yiisoft\User\CurrentUser;

final readonly class PermissionChecker
{
    public function __construct(
        private CurrentUser $currentUser,
    ) {
    }

    public function isSuperAdmin(): bool
    {
        return !$this->currentUser->isGuest()
            && $this->hasRole('super-admin');
    }

    public function can(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($this->currentUser->isGuest()) {
            return false;
        }

        $permissionModel = Permission::query()
            ->where(['title' => $permission])
            ->one();

        if ($permissionModel === null) {
            return false;
        }

        $rolePermissions = RolePermission::query()
            ->where(['permission_id' => $permissionModel->getId()])
            ->all();

        if ($rolePermissions === []) {
            return false;
        }

        $roleIds = array_map(
            static fn (RolePermission $rolePermission): int => $rolePermission->role_id,
            $rolePermissions,
        );

        return UserRole::query()
            ->where([
                'user_id' => (int) $this->currentUser->getId(),
                'role_id' => $roleIds,
            ])
            ->exists();
    }

    private function hasRole(string $role): bool
    {
        $roleModel = Role::query()
            ->where(['title' => $role])
            ->one();

        if ($roleModel === null) {
            return false;
        }

        return UserRole::query()
            ->where([
                'user_id' => (int) $this->currentUser->getId(),
                'role_id' => $roleModel->getId(),
            ])
            ->exists();
    }
}
