<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

final class M260915105435CreateProductImagesTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $column = $b->columnBuilder();

        $b->createTable('product_images', [
            'id' => $column::primaryKey(),

            'product_id' => $column::integer()
                ->notNull(),

            'path' => $column::string(255)
                ->notNull(),

            'sort_order' => $column::integer()
                ->notNull()
                ->defaultValue(0),

            'created_at' => $column::dateTime()
                ->notNull(),

            'updated_at' => $column::dateTime()
                ->null(),
        ]);

        $b->addForeignKey(
            'product_images',
            'fk_product_images_product_id',
            'product_id',
            'products',
            'id',
            'CASCADE',
            'CASCADE',
        );

        $b->createIndex(
            'product_images',
            'idx_product_images_product_id',
            'product_id',
        );
    }


    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('product_images');
    }
}
