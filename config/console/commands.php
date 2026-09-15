<?php

declare(strict_types=1);

use App\Console\SeedCommand;
use App\Console;

return [
    'hello' => Console\HelloCommand::class,
    'db:seed' => SeedCommand::class,
];
