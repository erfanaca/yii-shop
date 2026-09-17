<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

final class M260917141211CreateCartsTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $column = $b->columnBuilder();

        $b->createTable('carts', [
            'id' => $column::primaryKey(),

            'user_id' => $column::integer()
                ->notNull(),

            'status' => $column::string(32)
                ->notNull()
                ->defaultValue('ACTIVE'),

            'created_at' => $column::dateTime()
                ->notNull(),

            'updated_at' => $column::dateTime()
                ->null(),
        ]);

        $b->createIndex('carts', 'idx_carts_user_status', ['user_id', 'status']);
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('carts');
    }
}
