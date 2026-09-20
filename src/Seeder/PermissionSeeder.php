<?php

declare(strict_types=1);

namespace App\Seeder;

use App\Permission\Permission;

final class PermissionSeeder
{
    public function run(): void
    {
        $resources = [
            'user',
            'product',
            'category',
            'role',
            'permission',
            'order',
            'discount',
        ];

        $actions = ['index', 'create', 'edit', 'delete', 'view'];
        $permissions = [];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $permissions[] = $resource . '.' . $action;
            }
        }

        $permissions[] = 'user.roles';

        foreach ($permissions as $title) {
            if (Permission::query()->where(['title' => $title])->exists()) {
                continue;
            }

            $permission = new Permission();
            $permission->setTitle($title);
            $permission->save();
        }
    }
}
