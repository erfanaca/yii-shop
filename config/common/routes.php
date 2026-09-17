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

            Route::get('/products')
                ->action(Web\Product\Index\Action::class)
                ->name('product/index'),

            Route::get('/products/{id:\d+}')
                ->action(Web\Product\View\Action::class)
                ->name('product/view'),

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

            Route::get('/admin/categories')
                ->action(Web\Admin\Category\Index\Action::class)
                ->name('admin/category/index'),

            Route::methods([Method::GET, Method::POST], '/admin/categories/create')
                ->action(Web\Admin\Category\Create\Action::class)
                ->name('admin/category/create'),

            Route::methods([Method::GET, Method::POST], '/admin/categories/{id:\d+}/edit')
                ->action(Web\Admin\Category\Edit\Action::class)
                ->name('admin/category/edit'),

            Route::post('/admin/categories/{id:\d+}/delete')
                ->action(Web\Admin\Category\Delete\Action::class)
                ->name('admin/category/delete'),

            Route::post('/admin/products/{productId:\d+}/images/{id:\d+}/delete')
                ->action(Web\Admin\Product\DeleteImage\Action::class)
                ->name('admin/product/delete-image'),

            Route::get('/admin/users')
                ->action(Web\Admin\User\Index\Action::class)
                ->name('admin/user/index'),

            Route::methods([Method::GET, Method::POST], '/admin/users/create')
                ->action(Web\Admin\User\Create\Action::class)
                ->name('admin/user/create'),

            Route::methods([Method::GET, Method::POST], '/admin/users/{id:\d+}/edit')
                ->action(Web\Admin\User\Edit\Action::class)
                ->name('admin/user/edit'),

            Route::post('/admin/users/{id:\d+}/delete')
                ->action(Web\Admin\User\Delete\Action::class)
                ->name('admin/user/delete'),

            Route::get('/admin/roles')
                ->action(Web\Admin\Role\Index\Action::class)
                ->name('admin/role/index'),
            Route::methods([Method::GET, Method::POST], '/admin/roles/create')
                ->action(Web\Admin\Role\Create\Action::class)
                ->name('admin/role/create'),
            Route::methods([Method::GET, Method::POST], '/admin/roles/{id:\d+}/edit')
                ->action(Web\Admin\Role\Edit\Action::class)
                ->name('admin/role/edit'),
            Route::post('/admin/roles/{id:\d+}/delete')
                ->action(Web\Admin\Role\Delete\Action::class)
                ->name('admin/role/delete'),

            Route::get('/admin/permissions')
                ->action(Web\Admin\Permission\Index\Action::class)
                ->name('admin/permission/index'),
            Route::methods([Method::GET, Method::POST], '/admin/permissions/create')
                ->action(Web\Admin\Permission\Create\Action::class)
                ->name('admin/permission/create'),
            Route::methods([Method::GET, Method::POST], '/admin/permissions/{id:\d+}/edit')
                ->action(Web\Admin\Permission\Edit\Action::class)
                ->name('admin/permission/edit'),
            Route::post('/admin/permissions/{id:\d+}/delete')
                ->action(Web\Admin\Permission\Delete\Action::class)
                ->name('admin/permission/delete'),
        ),
];
