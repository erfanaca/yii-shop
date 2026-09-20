<?php

declare(strict_types=1);

namespace App\Admin\User;

use App\Role\Role;
use App\User\User;
use App\User\UserRole;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\InvalidConfigException;

final class UserRepository
{
    public function __construct(
        private readonly ConnectionInterface $db,
    ) {
    }

    /** @return User[] */
    public function findAll(): array
    {
        return User::query()
            ->orderBy(['id' => SORT_ASC])
            ->all();
    }

    public function create(string $email, string $passwordHash): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPasswordHash($passwordHash);
        $user->save();

        return $user;
    }

    public function existsByEmail(string $email, ?int $excludeUserId = null): bool
    {
        $condition = ['email' => $email];

        if ($excludeUserId !== null) {
            $condition = ['and', $condition, ['<>', 'id', $excludeUserId]];
        }

        return User::query()
            ->where($condition)
            ->exists();
    }

    public function findById(int $id): ?User
    {
        return User::query()
            ->where(['id' => $id])
            ->one();
    }

    public function update(User $user, string $email, ?string $passwordHash = null): User
    {
        $user->setEmail($email);

        if ($passwordHash !== null) {
            $user->setPasswordHash($passwordHash);
        }

        $user->save();

        return $user;
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

    public function roleIds(int $userId): array
    {
        $userRoles = UserRole::query()
            ->where(['user_id' => $userId])
            ->orderBy(['role_id' => SORT_ASC])
            ->all();

        return array_map(
            static fn (UserRole $row): int => $row->role_id,
            $userRoles,
        );
    }

    public function syncRoles(int $userId, array $roleIds): void
    {
        $roleIds = array_values(array_unique(array_map('intval', $roleIds)));

        $this->db->transaction(function () use ($userId, $roleIds): void {
            $rows = UserRole::query()
                ->where(['user_id' => $userId])
                ->all();

            foreach ($rows as $row) {
                $row->delete();
            }

            foreach ($roleIds as $roleId) {
                $userRole = new UserRole();
                $userRole->setUserId($userId);
                $userRole->setRoleId($roleId);
                $userRole->save();
            }
        });
    }

    public function roleTitlesByUserIds(array $userIds): array
    {
        $userIds = array_values(array_unique(array_map('intval', $userIds)));

        if ($userIds === []) {
            return [];
        }

        $userRoles = UserRole::query()
            ->where(['user_id' => $userIds])
            ->all();

        if ($userRoles === []) {
            return [];
        }

        $roleIds = array_values(array_unique(array_map(
            static fn (UserRole $userRole): int => $userRole->role_id,
            $userRoles,
        )));

        $roles = Role::query()
            ->where(['id' => $roleIds])
            ->all();

        $roleTitles = [];
        foreach ($roles as $role) {
            $roleTitles[$role->id] = $role->title;
        }

        $result = [];
        foreach ($userRoles as $userRole) {
            if (!isset($roleTitles[$userRole->role_id])) {
                continue;
            }

            $result[$userRole->user_id][] = $roleTitles[$userRole->role_id];
        }

        foreach ($result as &$titles) {
            sort($titles, SORT_STRING);
        }
        unset($titles);

        return $result;
    }
}
