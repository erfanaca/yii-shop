<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

final class M260920111759ChangeProductImagesTimestamps implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $b->alterColumn(
            'product_images',
            'created_at',
            'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP'
        );

        $b->alterColumn(
            'product_images',
            'updated_at',
            'DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        );
    }

    public function down(MigrationBuilder $b): void
    {
        $b->alterColumn(
            'product_images',
            'created_at',
            'DATETIME NOT NULL'
        );

        $b->alterColumn(
            'product_images',
            'updated_at',
            'DATETIME NULL'
        );
    }
}
