<?php

declare(strict_types=1);

namespace App\Auth;

use App\User\UserRepository;
use Yiisoft\Security\PasswordHasher;
use Yiisoft\User\CurrentUser;

final readonly class LoginService
{
    public function __construct(
        private UserRepository $userRepository,
        private PasswordHasher $passwordHasher,
        private CurrentUser $currentUser,
    ) {
    }

    public function login(string $email, string $password): bool
    {
        $email = strtolower(trim($email));

        $user = $this->userRepository->findByEmail($email);

        if ($user === null) {
            return false;
        }

        if (!$this->passwordHasher->validate(
            $password,
            $user->getPasswordHash(),
        )) {
            return false;
        }

        return $this->currentUser->login($user);
    }
}