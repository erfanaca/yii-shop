<?php

declare(strict_types=1);

namespace App\Admin\User;

use App\User\User;
use Yiisoft\Db\Connection\ConnectionInterface;
use DateTimeImmutable;
use DateTimeInterface;

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

    private function createUserFromRow(array $row): User
    {
        return new User(
            id: (int) $row['id'],
            email: (string) $row['email'],
            passwordHash: '',
        );
    }
}
