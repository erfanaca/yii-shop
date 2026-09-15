<?php

declare(strict_types=1);

namespace App\Seeder;

use Yiisoft\Db\Connection\ConnectionInterface;

final class RoleSeeder
{
    public function __construct(
        private ConnectionInterface $db,
    ) {}

    public function run(): void
    {
        $this->db->createCommand()->insert(
            'roles',
            [
                'title' => 'super-admin',
            ]
        )->execute();

        $this->db->createCommand()->insert(
            'roles',
            [
                'title' => 'admin',
            ]
        )->execute();

        $this->db->createCommand()->insert(
            'roles',
            [
                'title' => 'customer',
            ]
        )->execute();
    }
}
