<?php

declare(strict_types=1);

namespace App\User;

use Yiisoft\Auth\IdentityInterface;

final class User implements IdentityInterface
{
    public function __construct(
        private readonly ?int $id,
        private readonly string $email,
        private readonly string $passwordHash,
    ) {
    }

    public function getId(): ?string
    {
        return $this->id === null
            ? null
            : (string) $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
}