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

        $roleId = $this->db->createCommand(
            'SELECT id FROM roles WHERE title = :title',
            [':title' => 'super-admin'],
        )->queryScalar();

        if ($roleId === false) {
            throw new \RuntimeException(
                'The "super-admin" role does not exist.'
            );
        }

        $userId = $this->db->createCommand(
            'SELECT id FROM users WHERE email = :email',
            [':email' => $email],
        )->queryScalar();

        if ($userId === false) {
            $now = time();

            $this->db->createCommand()
                ->insert(
                    'users',
                    [
                        'email' => $email,
                        'password_hash' => password_hash(
                            'password',
                            PASSWORD_DEFAULT,
                        ),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                )
                ->execute();

            $userId = $this->db->getLastInsertID();
        }

        $userRoleExists = $this->db->createCommand(
            <<<'SQL'
            SELECT user_id
            FROM user_roles
            WHERE user_id = :user_id AND role_id = :role_id
            SQL,
            [
                ':user_id' => $userId,
                ':role_id' => $roleId,
            ],
        )->queryScalar();

        if ($userRoleExists === false) {
            $this->db->createCommand()
                ->insert(
                    'user_roles',
                    [
                        'user_id' => $userId,
                        'role_id' => $roleId,
                    ],
                )
                ->execute();
        }
    }
}
