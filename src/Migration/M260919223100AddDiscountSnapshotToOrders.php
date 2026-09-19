<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

final class M260919223100AddDiscountSnapshotToOrders implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $column = $b->columnBuilder();

        $b->addColumn('orders', 'subtotal_amount', $column::decimal(12, 2)->null());
        $b->addColumn('orders', 'discount_amount', $column::decimal(12, 2)->notNull()->defaultValue(0));
        $b->addColumn('orders', 'discount_code', $column::string(100)->null());
        $b->addColumn('orders', 'discount_type', $column::string(16)->null());
        $b->addColumn('orders', 'discount_value', $column::decimal(12, 2)->null());
        $b->addColumn('orders', 'discount_eligible_subtotal', $column::decimal(12, 2)->null());
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropColumn('orders', 'discount_eligible_subtotal');
        $b->dropColumn('orders', 'discount_value');
        $b->dropColumn('orders', 'discount_type');
        $b->dropColumn('orders', 'discount_code');
        $b->dropColumn('orders', 'discount_amount');
        $b->dropColumn('orders', 'subtotal_amount');
    }
}
