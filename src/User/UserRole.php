<?php

declare(strict_types=1);

namespace App\User;

use App\Role\Role;
use DateTimeImmutable;
use Yiisoft\ActiveRecord\ActiveQueryInterface;
use Yiisoft\ActiveRecord\ActiveRecord;

final class UserRole extends ActiveRecord
{
    public int $user_id;
    public int $role_id;

    public function tableName(): string
    {
        return 'user_roles';
    }

    public function relationQuery(string $name): ActiveQueryInterface
    {
        return match ($name) {
            'role' => $this->hasOne(Role::class, ['id' => 'role_id']),
        };
    }

    public function setUserId(int $userId): void
    {
        $this->user_id = $userId;
    }

    public function setRoleId(int $roleId): void
    {
        $this->role_id = $roleId;
    }
}
