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
        $resources = [
            'user',
            'product',
            'category',
            'role',
            'permission',
        ];

        $actions = [
            'manage',
            'view',
            'create',
            'update',
            'delete',
        ];

        $permissions = [];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $permissions[] = $resource . '.' . $action;
            }
        }

        foreach ($permissions as $permission) {
            $exists = $this->db->createCommand(
                'SELECT id FROM permissions WHERE title = :title',
                [':title' => $permission]
            )->queryScalar();

            if ($exists === false) {
                $this->db->createCommand()->insert(
                    'permissions',
                    [
                        'title' => $permission,
                    ]
                )->execute();
            }
        }
    }
}
