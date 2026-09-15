<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

final class M260915105140CreateCategoriesTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $column = $b->columnBuilder();

        $b->createTable('categories', [
            'id' => $column::primaryKey(),

            'title' => $column::string(255)
                ->notNull()
                ->unique(),

            'created_at' => $column::dateTime()
                ->notNull(),

            'updated_at' => $column::dateTime()
                ->null(),
        ]);
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('categories');
    }
}
