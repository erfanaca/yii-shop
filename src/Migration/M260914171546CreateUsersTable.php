<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

final class M260914171546CreateUsersTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $column = $b->columnBuilder();

        $b->createTable('users', [
            'id' => $column::primaryKey(),

            'email' => $column::string(255)
                ->notNull()
                ->unique(),

            'password_hash' => $column::string(255)
                ->notNull(),

            'created_at' => $column::dateTime()
                ->notNull(),

            'updated_at' => $column::dateTime()
                ->null(),
        ]);
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('users');
    }
}