<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

final class M260919210802CreateDiscountCodeProductsTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $column = $b->columnBuilder();

        $b->createTable('discount_code_products', [
            'discount_code_id' => $column::integer()
                ->notNull(),

            'product_id' => $column::integer()
                ->notNull(),
        ]);

        $b->addPrimaryKey(
            'discount_code_products',
            'pk_discount_code_products',
            ['discount_code_id', 'product_id'],
        );

        $b->addForeignKey(
            'discount_code_products',
            'fk_discount_code_products_discount_code_id',
            'discount_code_id',
            'discount_codes',
            'id',
            'CASCADE',
            'CASCADE',
        );

        $b->addForeignKey(
            'discount_code_products',
            'fk_discount_code_products_product_id',
            'product_id',
            'products',
            'id',
            'CASCADE',
            'CASCADE',
        );

        $b->createIndex('discount_code_products', 'idx_discount_code_products_product_id', 'product_id');
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('discount_code_products');
    }
}
