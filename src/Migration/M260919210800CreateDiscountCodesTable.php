<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

final class M260919210800CreateDiscountCodesTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $column = $b->columnBuilder();

        $b->createTable('discount_codes', [
            'id' => $column::primaryKey(),

            'code' => $column::string(100)
                ->notNull()
                ->unique(),

            'type' => $column::string(16)
                ->notNull(),

            'value' => $column::decimal(12, 2)
                ->notNull(),

            'user_scope' => $column::string(16)
                ->notNull(),

            'product_scope' => $column::string(16)
                ->notNull(),

            'max_discount_amount' => $column::decimal(12, 2)
                ->null(),

            'minimum_order_amount' => $column::decimal(12, 2)
                ->null(),

            'created_at' => $column::dateTime()
                ->notNull(),

            'updated_at' => $column::dateTime()
                ->null(),
        ]);

        $b->createIndex('discount_codes', 'idx_discount_codes_type', 'type');
        $b->createIndex('discount_codes', 'idx_discount_codes_user_scope', 'user_scope');
        $b->createIndex('discount_codes', 'idx_discount_codes_product_scope', 'product_scope');
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('discount_codes');
    }
}
