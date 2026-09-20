<?php

declare(strict_types=1);

namespace App\User;

use DateTimeImmutable;
use Yiisoft\ActiveRecord\ActiveRecord;
use Yiisoft\Auth\IdentityInterface;

final class User extends ActiveRecord implements IdentityInterface
{
    public ?int $id;
    public string $email;
    public string $password_hash;
    public DateTimeImmutable $created_at;
    public DateTimeImmutable $updated_at;

    public function tableName(): string
    {
        return 'users';
    }

    public function getId(): ?string
    {
        return $this->id === null
            ? null
            : (string)$this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPasswordHash(): string
    {
        return $this->password_hash;
    }

    public function setPasswordHash(string $passwordHash): void
    {
        $this->password_hash = $passwordHash;
    }
}
