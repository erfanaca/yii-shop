<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

final class M260918081220CreateOrdersTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $column = $b->columnBuilder();

        $b->createTable('orders', [
            'id' => $column::primaryKey(),

            'user_id' => $column::integer()
                ->notNull(),

            'status' => $column::string(32)
                ->notNull()
                ->defaultValue('PENDING'),

            'total_amount' => $column::decimal(12, 2)
                ->notNull(),

            'created_at' => $column::dateTime()
                ->notNull(),

            'updated_at' => $column::dateTime()
                ->null(),
        ]);

        $b->addForeignKey(
            'orders',
            'fk_orders_user_id',
            'user_id',
            'users',
            'id',
            'RESTRICT',
            'CASCADE',
        );

        $b->createIndex('orders', 'idx_orders_user_id', 'user_id');
        $b->createIndex('orders', 'idx_orders_status', 'status');
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('orders');
    }
}
