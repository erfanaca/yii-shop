<?php

declare(strict_types=1);

use App\Web;
use Yiisoft\Http\Method;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;

return [
    Group::create()
        ->routes(
            Route::get('/')
                ->action(Web\HomePage\Action::class)
                ->name('home'),

            Route::methods([Method::GET, Method::POST], '/register')
                ->action(Web\Auth\Register\Action::class)
                ->name('auth/register'),

            Route::methods([Method::GET, Method::POST], '/login')
                ->action(Web\Auth\Login\Action::class)
                ->name('auth/login'),

            Route::post('/logout')
                ->action(Web\Auth\Logout\Action::class)
                ->name('auth/logout'),
        ),
];