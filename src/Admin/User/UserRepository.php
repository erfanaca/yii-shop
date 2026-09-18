<?php

declare(strict_types=1);

namespace App\Admin\User;

use App\User\User;
use DateTimeImmutable;
use Yiisoft\Db\Connection\ConnectionInterface;

final class UserRepository
{
    public function __construct(
        private readonly ConnectionInterface $db,
    ) {}

    public function findAll(): array
    {
        $rows = $this->db
            ->createQuery()
            ->from('users')
            ->all();

        return array_map($this->createUserFromRow(...), $rows);
    }

    public function create(string $email, string $passwordHash): User
    {
        $now = new DateTimeImmutable();

        $this->db
            ->createCommand()
            ->insert('users', [
                'email' => $email,
                'password_hash' => $passwordHash,
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->execute();

        return new User(
            id: (int) $this->db->getLastInsertId(),
            email: $email,
            passwordHash: $passwordHash,
        );
    }

    public function findById(int $id): ?User
    {
        $row = $this->db
            ->createQuery()
            ->from('users')
            ->where(['id' => $id])
            ->limit(1)
            ->one();

        if ($row === null || $row === false) {
            return null;
        }

        return $this->createUserFromRow($row);
    }

    public function update(User $user, string $email, string $passwordHash): User
    {
        $updatedAt = new DateTimeImmutable();

        $this->db
            ->createCommand()
            ->update('users', [
                'email' => $email,
                'password_hash' => $passwordHash,
                'updated_at' => $updatedAt,
            ], [
                'id' => $user->getId(),
            ])
            ->execute();

        return new User(
            id: (int) $user->getId(),
            email: $email,
            passwordHash: $passwordHash,
        );
    }

    public function delete(User $user): void
    {
        $this->db
            ->createCommand()
            ->delete('users', [
                'id' => $user->getId(),
            ])
            ->execute();
    }

    /**
     * @return int[]
     */
    public function roleIds(int $userId): array
    {
        $rows = $this->db
            ->createQuery()
            ->select('role_id')
            ->from('user_roles')
            ->where(['user_id' => $userId])
            ->all();

        return array_map(
            static fn(array $row): int => (int) $row['role_id'],
            $rows,
        );
    }

    /**
     * @param int[] $roleIds
     */
    public function syncRoles(int $userId, array $roleIds): void
    {
        $roleIds = array_values(array_unique(array_map('intval', $roleIds)));

        $this->db->transaction(function () use ($userId, $roleIds): void {
            $this->db
                ->createCommand()
                ->delete('user_roles', ['user_id' => $userId])
                ->execute();

            foreach ($roleIds as $roleId) {
                $this->db
                    ->createCommand()
                    ->insert('user_roles', [
                        'user_id' => $userId,
                        'role_id' => $roleId,
                    ])
                    ->execute();
            }
        });
    }

    /**
     * @param int[] $userIds
     * @return array<int, string[]>
     */
    public function roleTitlesByUserIds(array $userIds): array
    {
        $userIds = array_values(array_unique(array_map('intval', $userIds)));

        if ($userIds === []) {
            return [];
        }

        $rows = $this->db
            ->createQuery()
            ->select([
                'ur.user_id',
                'r.title',
            ])
            ->from(['ur' => 'user_roles'])
            ->innerJoin(['r' => 'roles'], 'r.id = ur.role_id')
            ->where(['ur.user_id' => $userIds])
            ->orderBy(['r.title' => SORT_ASC])
            ->all();

        $result = [];

        foreach ($rows as $row) {
            $result[(int) $row['user_id']][] = (string) $row['title'];
        }

        return $result;
    }

    private function createUserFromRow(array $row): User
    {
        return new User(
            id: (int) $row['id'],
            email: (string) $row['email'],
            passwordHash: '',
        );
    }
}
