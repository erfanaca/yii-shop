<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

final class M260915103524CreateProductsTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $column = $b->columnBuilder();

        $b->createTable('products', [
            'id' => $column::primaryKey(),

            'title' => $column::string(255)
                ->notNull(),

            'description' => $column::text()
                ->null(),
                
            'quantity' => $column::integer()
                ->notNull()
                ->defaultValue(0),

            'price' => $column::decimal(12, 2)
                ->notNull(),

            'created_at' => $column::dateTime()
                ->notNull(),

            'updated_at' => $column::dateTime()
                ->null(),
        ]);
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('products');
    }
}
