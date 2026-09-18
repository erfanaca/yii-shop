<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Constant\IndexType;
use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

final class M260918081240AddOrderReferences implements RevertibleMigrationInterface
{
    private const ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    private const REFERENCE_LENGTH = 10;

    public function up(MigrationBuilder $b): void
    {
        $column = $b->columnBuilder();

        $b->addColumn(
            'orders',
            'transaction_number',
            $column::string(self::REFERENCE_LENGTH)->null(),
        );

        $b->addColumn(
            'orders',
            'invoice_number',
            $column::string(self::REFERENCE_LENGTH)->null(),
        );

        $b->alterColumn(
            'orders',
            'transaction_number',
            $column::string(self::REFERENCE_LENGTH)->notNull(),
        );

        $b->alterColumn(
            'orders',
            'invoice_number',
            $column::string(self::REFERENCE_LENGTH)->notNull(),
        );

        $b->createIndex(
            'orders',
            'uq_orders_transaction_number',
            'transaction_number',
            IndexType::UNIQUE,
        );

        $b->createIndex(
            'orders',
            'uq_orders_invoice_number',
            'invoice_number',
            IndexType::UNIQUE,
        );
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropIndex('orders', 'uq_orders_invoice_number');
        $b->dropIndex('orders', 'uq_orders_transaction_number');
        $b->dropColumn('orders', 'invoice_number');
        $b->dropColumn('orders', 'transaction_number');
    }
}
