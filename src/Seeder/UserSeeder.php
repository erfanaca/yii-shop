<?php

declare(strict_types=1);

namespace App\Seeder;

use Yiisoft\Db\Connection\ConnectionInterface;

final class UserSeeder
{
    public function __construct(
        private ConnectionInterface $db,
    ) {
    }

    public function run(): void
    {
        $email = 'admin@example.com';
        $now = time();

        $role = $this->db->createCommand(
            'SELECT id FROM roles WHERE title = :title',
            [
                ':title' => 'super-admin',
            ]
        )->queryScalar();

        if ($role === false) {
            throw new \RuntimeException(
                'The "super-admin" role does not exist.'
            );
        }

        $this->db->createCommand()->insert(
            'users',
            [
                'email' => $email,
                'password_hash' => password_hash(
                    'password',
                    PASSWORD_DEFAULT
                ),
                'created_at' => $now,
                'updated_at' => $now,
            ]
        )->execute();

        $userId = $this->db->getLastInsertID();

        $this->db->createCommand()->insert(
            'user_roles',
            [
                'user_id' => $userId,
                'role_id' => $role,
            ]
        )->execute();
    }
}