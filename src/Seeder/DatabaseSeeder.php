<?php

declare(strict_types=1);

namespace App\Seeder;

final class DatabaseSeeder
{
    public function __construct(
        private RoleSeeder $roleSeeder,
        private PermissionSeeder $permissionSeeder,
        private UserSeeder $userSeeder,
    ) {
    }

    public function run(): void
    {
        $this->roleSeeder->run();
        $this->permissionSeeder->run();
        $this->userSeeder->run();
    }
}
