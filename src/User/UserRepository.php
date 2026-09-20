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
        $user = new User();

        $user->setEmail($email);
        $user->setPasswordHash($passwordHash);

        $user->save();
    }

    /**
     * @throws InvalidConfigException
     * @throws \Throwable
     * @throws Exception
     */
    public function findByEmail(string $email): ?User
    {
        return User::query()->where(['email' => $email])->one();
    }

    public function findIdentity(string $id): ?IdentityInterface
    {
        return User::query()->where(['id' => $id])->one();
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
}
