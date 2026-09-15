<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

final class M260915072749CreateRolePermissionsTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $column = $b->columnBuilder();

        $b->createTable('role_permissions', [
            'role_id' => $column::integer()
                ->notNull(),

            'permission_id' => $column::integer()
                ->notNull(),
        ]);

        $b->addPrimaryKey(
            'role_permissions',
            'pk_role_permissions',
            ['role_id', 'permission_id'],
        );

        $b->addForeignKey(
            'role_permissions',
            'fk_role_permissions_role',
            ['role_id'],
            'roles',
            ['id'],
            'CASCADE',
            'CASCADE',
        );

        $b->addForeignKey(
            'role_permissions',
            'fk_role_permissions_permission',
            ['permission_id'],
            'permissions',
            ['id'],
            'CASCADE',
            'CASCADE',
        );
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('role_permissions');
    }
}
