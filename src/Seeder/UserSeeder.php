<?php

declare(strict_types=1);

namespace App\Seeder;

use App\Role\Role;
use App\User\User;
use App\User\UserRole;

final class UserSeeder
{
    public function run(): void
    {
        $email = 'admin@example.com';

        $role = Role::query()
            ->where(['title' => 'super-admin'])
            ->one();

        if ($role === null) {
            throw new \RuntimeException('The "super-admin" role does not exist.');
        }

        $user = User::query()
            ->where(['email' => $email])
            ->one();

        if ($user === null) {
            $user = new User();
            $user->setEmail($email);
            $user->setPasswordHash(password_hash('password', PASSWORD_DEFAULT));
            $user->save();
        }

        $userId = (int) $user->getId();
        $roleId = $role->getId();

        if (UserRole::query()->where(['user_id' => $userId, 'role_id' => $roleId])->exists()) {
            return;
        }

        $userRole = new UserRole();
        $userRole->setUserId($userId);
        $userRole->setRoleId($roleId);
        $userRole->save();
    }
}
