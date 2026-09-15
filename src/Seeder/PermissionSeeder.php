<?php

declare(strict_types=1);

namespace App\Seeder;

use Yiisoft\Db\Connection\ConnectionInterface;

final class PermissionSeeder
{
    public function __construct(
        private ConnectionInterface $db,
    ) {
    }

    public function run(): void
    {
        $permissions = [
            'user.create',
            'user.update',
            'user.delete',
            'user.view',
        ];

        foreach ($permissions as $permission) {
            $this->db->createCommand()->insert(
                'permissions',
                [
                    'title' => $permission,
                ]
            )->execute();
        }
    }
}