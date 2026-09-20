<?php

declare(strict_types=1);

namespace App\Admin\User;

use App\Role\Role;
use App\User\User;
use App\User\UserRole;
use RectorPrefix202609\Nette\Utils\DateTimeImmutable;
use Yiisoft\Db\Connection\ConnectionInterface;

final class UserRepository
{
    public function __construct(
        private readonly ConnectionInterface $db,
    ) {}

    public function findAll(): array
    {
        return User::query()->all();
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
        $row = $this->db
            ->createQuery()
            ->select('id')
            ->from('users')
            ->where(['email' => $email])
            ->limit(1)
            ->one();

        if ($row === null || $row === false) {
            return false;
        }

        return $excludeUserId === null || (int) $row['id'] !== $excludeUserId;
    }

    public function findById(int $id): ?User
    {
        return User::query()->where(['id' => $id])->one();
    }

    public function update(User $user, string $email, ?string $passwordHash = null): User
    {
        $updatedAt = new DateTimeImmutable();

        $values = [
            'email' => $email,
            'updated_at' => $updatedAt,
        ];

        if ($passwordHash !== null) {
            $values['password_hash'] = $passwordHash;
        }

        $this->db
            ->createCommand()
            ->update('users', $values, [
                'id' => $user->getId(),
            ])
            ->execute();

        return new User(
            id: (int) $user->getId(),
            email: $email,
            passwordHash: $passwordHash ?? $user->getPasswordHash(),
        );
    }

    public function delete(User $user): void
    {
        $user->delete();
    }


    public function roleIds(int $userId): array
    {
        $roleIds = UserRole::query()
            ->where(['user_id' => $userId])
            ->all();

        return array_map(
            static fn(UserRole $row): int => $row->role_id,
            $roleIds,
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
                $role = new UserRole();

                $role->setUserId($userId);
                $role->setRoleId($roleId);

                $role->save();
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

        $roleIds = array_values(array_unique(
            array_map(
                static fn(UserRole $userRole): int => $userRole->role_id,
                $userRoles,
            ),
        ));

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
