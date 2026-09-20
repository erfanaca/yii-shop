<?php

declare(strict_types=1);

namespace App\User;

use Yiisoft\Auth\IdentityInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;

final class UserRepository implements IdentityRepositoryInterface
{
    public function create(string $email, string $passwordHash): void
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPasswordHash($passwordHash);
        $user->save();
    }

    public function findByEmail(string $email): ?User
    {
        return User::query()
            ->where(['email' => $email])
            ->one();
    }

    public function findIdentity(string $id): ?IdentityInterface
    {
        return User::query()
            ->where(['id' => $id])
            ->one();
    }

    public function existsByEmail(string $email): bool
    {
        return User::query()
            ->where(['email' => $email])
            ->exists();
    }
}
