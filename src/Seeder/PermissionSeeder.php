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
        $resourceActions = [
            'user' => ['manage', 'view', 'create', 'update', 'delete'],
            'product' => ['manage', 'view', 'create', 'update', 'delete'],
            'category' => ['manage', 'view', 'create', 'update', 'delete'],
            'role' => ['manage', 'view', 'create', 'update', 'delete'],
            'permission' => ['manage', 'view', 'create', 'update', 'delete'],
            'order' => ['manage', 'view'],
        ];

        $permissions = [];

        foreach ($resourceActions as $resource => $actions) {
            foreach ($actions as $action) {
                $permissions[] = $resource . '.' . $action;
            }
        }

        $permissions[] = 'user.roles.manage';

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
