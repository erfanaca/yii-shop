<?php

declare(strict_types=1);

namespace App\Seeder;

use Yiisoft\Db\Connection\ConnectionInterface;

final class RoleSeeder
{
    public function __construct(
        private ConnectionInterface $db,
    ) {
    }

    public function run(): void
    {
        $roles = [
            'super-admin',
            'admin',
            'customer',
        ];

        foreach ($roles as $role) {
            $exists = $this->db->createCommand(
                'SELECT id FROM roles WHERE title = :title',
                [':title' => $role],
            )->queryScalar();

            if ($exists !== false) {
                continue;
            }

            $this->db->createCommand()
                ->insert('roles', ['title' => $role])
                ->execute();
        }
    }
}
