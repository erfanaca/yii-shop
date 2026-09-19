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

        foreach ($permissions as $permission) {
            $exists = $this->db->createCommand(
                'SELECT id FROM permissions WHERE title = :title',
                [':title' => $permission],
            )->queryScalar();

            if ($exists === false) {
                $this->db->createCommand()
                    ->insert('permissions', ['title' => $permission])
                    ->execute();
            }
        }
    }
}
