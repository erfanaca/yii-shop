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

            Route::get('/admin/products')
                ->action(Web\Admin\Product\Index\Action::class)
                ->name('admin/product/index'),

            Route::methods([Method::GET, Method::POST], '/admin/products/create')
                ->action(Web\Admin\Product\Create\Action::class)
                ->name('admin/product/create'),

            Route::methods([Method::GET, Method::POST], '/admin/products/{id:\d+}/edit')
                ->action(Web\Admin\Product\Edit\Action::class)
                ->name('admin/product/edit'),

            Route::post('/admin/products/{id:\d+}/delete')
                ->action(Web\Admin\Product\Delete\Action::class)
                ->name('admin/product/delete'),
        ),
];
