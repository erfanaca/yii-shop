<?php

declare(strict_types=1);

use App\Seeder\DatabaseSeeder;
use App\Seeder\PermissionSeeder;
use App\Seeder\RoleSeeder;
use App\Seeder\UserSeeder;

return [
    DatabaseSeeder::class => [
        'class' => DatabaseSeeder::class,
    ],

    RoleSeeder::class => [
        'class' => RoleSeeder::class,
    ],

    PermissionSeeder::class => [
        'class' => PermissionSeeder::class,
    ],

    UserSeeder::class => [
        'class' => UserSeeder::class,
    ],
];