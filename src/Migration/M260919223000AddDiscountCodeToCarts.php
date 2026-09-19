<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

final class M260919223000AddDiscountCodeToCarts implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $column = $b->columnBuilder();

        $b->addColumn(
            'carts',
            'discount_code_id',
            $column::integer()->null(),
        );

        $b->addForeignKey(
            'carts',
            'fk_carts_discount_code_id',
            'discount_code_id',
            'discount_codes',
            'id',
            'SET NULL',
            'CASCADE',
        );

        $b->createIndex('carts', 'idx_carts_discount_code_id', 'discount_code_id');
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropForeignKey('carts', 'fk_carts_discount_code_id');
        $b->dropIndex('carts', 'idx_carts_discount_code_id');
        $b->dropColumn('carts', 'discount_code_id');
    }
}
