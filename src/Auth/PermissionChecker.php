<?php

declare(strict_types=1);

namespace App\Auth;

use Yiisoft\User\CurrentUser;
use Yiisoft\Db\Connection\ConnectionInterface;

final readonly class PermissionChecker
{
    public function __construct(
        private CurrentUser $currentUser,
        private ConnectionInterface $db,
    ) {
    }

    public function isSuperAdmin(): bool
    {
        if (!$this->currentUser->isGuest()) {
            return $this->hasRole('super-admin');
        }

        return false;
    }

    public function can(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($this->currentUser->isGuest()) {
            return false;
        }

        $exists = $this->db->createCommand(
            'SELECT 1
             FROM user_roles ur
             INNER JOIN role_permissions rp ON rp.role_id = ur.role_id
             INNER JOIN permissions p ON p.id = rp.permission_id
             WHERE ur.user_id = :user_id AND p.title = :permission
             LIMIT 1',
            [
                ':user_id' => (int)$this->currentUser->getId(),
                ':permission' => $permission,
            ]
        )->queryScalar();

        return $exists !== false;
    }

    private function hasRole(string $role): bool
    {
        return $this->db->createCommand(
            'SELECT 1
             FROM user_roles ur
             INNER JOIN roles r ON r.id = ur.role_id
             WHERE ur.user_id = :user_id AND r.title = :role
             LIMIT 1',
            [
                ':user_id' => (int)$this->currentUser->getId(),
                ':role' => $role,
            ]
        )->queryScalar() !== false;
    }
}
