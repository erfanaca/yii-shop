<?php

declare(strict_types=1);

namespace App\Auth;

use App\User\UserRepository;
use RuntimeException;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Security\PasswordHasher;

final class RegistrationService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly PasswordHasher $passwordHasher,
    ) {
    }

    /**
     * @throws InvalidConfigException
     * @throws \Throwable
     * @throws Exception
     */
    public function register(string $email, string $password): void
    {
        $email = strtolower(trim($email));

        if ($this->userRepository->existsByEmail($email)) {
            throw new RuntimeException('This email is already registered.');
        }

        $passwordHash = $this->passwordHasher->hash($password);

        $this->userRepository->create(
            email: $email,
            passwordHash: $passwordHash,
        );
    }
}