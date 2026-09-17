<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

final class M260917141225CreateCartItemsTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $column = $b->columnBuilder();

        $b->createTable('cart_items', [
            'id' => $column::primaryKey(),

            'cart_id' => $column::integer()
                ->notNull(),

            'product_id' => $column::integer()
                ->notNull(),

            'quantity' => $column::integer()
                ->notNull()
                ->defaultValue(1),

            'unit_price' => $column::decimal(12, 2)
                ->notNull(),

            'created_at' => $column::dateTime()
                ->notNull(),

            'updated_at' => $column::dateTime()
                ->null(),
        ]);

        $b->createIndex('cart_items', 'idx_cart_items_cart_id', ['cart_id']);
        $b->createIndex('cart_items', 'idx_cart_items_product_id', ['product_id']);
        $b->createIndex('cart_items', 'uq_cart_items_product', ['cart_id', 'product_id']);
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('cart_items');
    }
}
