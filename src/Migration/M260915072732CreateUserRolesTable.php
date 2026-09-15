<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

final class M260915072732CreateUserRolesTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $column = $b->columnBuilder();

        $b->createTable('user_roles', [
            'user_id' => $column::integer()
                ->notNull(),

            'role_id' => $column::integer()
                ->notNull(),
        ]);

        $b->addPrimaryKey(
            'user_roles',
            'pk_user_roles',
            ['user_id', 'role_id'],
        );

        $b->addForeignKey(
            'user_roles',
            'fk_user_roles_user_id',
            ['user_id'],
            'users',
            ['id'],
            'CASCADE',
            'CASCADE',
        );

        $b->addForeignKey(
            'user_roles',
            'fk_user_roles_role_id',
            ['role_id'],
            'roles',
            ['id'],
            'CASCADE',
            'CASCADE',
        );
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('user_roles');
    }
}
