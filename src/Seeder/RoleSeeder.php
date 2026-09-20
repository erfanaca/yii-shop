<?php

declare(strict_types=1);

namespace App\Seeder;

use App\Role\Role;

final class RoleSeeder
{
    public function run(): void
    {
        $roles = [
            'super-admin',
            'admin',
            'customer',
        ];

        foreach ($roles as $title) {
            if (Role::query()->where(['title' => $title])->exists()) {
                continue;
            }

            $role = new Role();
            $role->setTitle($title);
            $role->save();
        }
    }
}
