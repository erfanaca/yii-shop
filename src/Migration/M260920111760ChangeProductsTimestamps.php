<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

final class M260920111760ChangeProductsTimestamps implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $b->alterColumn(
            'products',
            'created_at',
            'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP'
        );

        $b->alterColumn(
            'products',
            'updated_at',
            'DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        );
    }

    public function down(MigrationBuilder $b): void
    {
        $b->alterColumn(
            'products',
            'created_at',
            'DATETIME NOT NULL'
        );

        $b->alterColumn(
            'products',
            'updated_at',
            'DATETIME NULL'
        );
    }
}
