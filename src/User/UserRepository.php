<?php

declare(strict_types=1);

namespace App\User;

use DateTimeImmutable;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\InvalidConfigException;

final class UserRepository implements IdentityRepositoryInterface
{
    public function __construct(
        private readonly ConnectionInterface $db,
    ) {
    }

    public function create(
        string $email,
        string $passwordHash,
    ): void {
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
    }

    /**
     * @throws InvalidConfigException
     * @throws \Throwable
     * @throws Exception
     */
    public function findByEmail(string $email): ?User
    {
        $row = $this->db
            ->createQuery()
            ->from('users')
            ->where(['email' => $email])
            ->limit(1)
            ->one();

        if ($row === null || $row === false) {
            return null;
        }

        return $this->createUserFromRow($row);
    }

    public function findIdentity(string $id): ?IdentityInterface
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

    /**
     * @throws InvalidConfigException
     * @throws \Throwable
     * @throws Exception
     */
    public function existsByEmail(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }

    private function createUserFromRow(array $row): User
    {
        return new User(
            id: (int) $row['id'],
            email: (string) $row['email'],
            passwordHash: (string) $row['password_hash'],
        );
    }
}