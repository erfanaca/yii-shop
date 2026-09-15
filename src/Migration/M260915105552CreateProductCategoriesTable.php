<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

final class M260915105552CreateProductCategoriesTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $column = $b->columnBuilder();

        $b->createTable('product_categories', [
            'product_id' => $column::integer()
                ->notNull(),

            'category_id' => $column::integer()
                ->notNull(),
        ]);

        $b->addPrimaryKey(
            'product_categories',
            'pk_product_categories',
            ['product_id', 'category_id'],
        );

        $b->addForeignKey(
            'product_categories',
            'fk_product_categories_product_id',
            'product_id',
            'products',
            'id',
            'CASCADE',
            'CASCADE',
        );

        $b->addForeignKey(
            'product_categories',
            'fk_product_categories_category_id',
            'category_id',
            'categories',
            'id',
            'CASCADE',
            'CASCADE',
        );
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('product_categories');
    }
}
