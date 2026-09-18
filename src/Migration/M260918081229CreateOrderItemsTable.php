<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

final class M260918081229CreateOrderItemsTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $column = $b->columnBuilder();

        $b->createTable('order_items', [
            'id' => $column::primaryKey(),

            'order_id' => $column::integer()
                ->notNull(),

            'product_id' => $column::integer()
                ->null(),

            'product_title' => $column::string(255)
                ->notNull(),

            'quantity' => $column::integer()
                ->notNull(),

            'unit_price' => $column::decimal(12, 2)
                ->notNull(),

            'created_at' => $column::dateTime()
                ->notNull(),
        ]);

        $b->addForeignKey(
            'order_items',
            'fk_order_items_order_id',
            'order_id',
            'orders',
            'id',
            'CASCADE',
            'CASCADE',
        );

        $b->addForeignKey(
            'order_items',
            'fk_order_items_product_id',
            'product_id',
            'products',
            'id',
            'SET NULL',
            'CASCADE',
        );

        $b->createIndex('order_items', 'idx_order_items_order_id', 'order_id');
        $b->createIndex('order_items', 'idx_order_items_product_id', 'product_id');
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('order_items');
    }
}
