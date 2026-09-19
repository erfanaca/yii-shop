<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

final class M260919210801CreateDiscountCodeUsersTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $column = $b->columnBuilder();

        $b->createTable('discount_code_users', [
            'discount_code_id' => $column::integer()
                ->notNull(),

            'user_id' => $column::integer()
                ->notNull(),
        ]);

        $b->addPrimaryKey(
            'discount_code_users',
            'pk_discount_code_users',
            ['discount_code_id', 'user_id'],
        );

        $b->addForeignKey(
            'discount_code_users',
            'fk_discount_code_users_discount_code_id',
            'discount_code_id',
            'discount_codes',
            'id',
            'CASCADE',
            'CASCADE',
        );

        $b->addForeignKey(
            'discount_code_users',
            'fk_discount_code_users_user_id',
            'user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE',
        );

        $b->createIndex('discount_code_users', 'idx_discount_code_users_user_id', 'user_id');
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('discount_code_users');
    }
}
