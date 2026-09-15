<?php

declare(strict_types=1);

use App\Product\Product;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;

/**
 * @var Product[] $products
 * @var UrlGeneratorInterface $urlGenerator
 */

$this->setTitle('Products');

$buttonClass = implode(' ', [
    'inline-flex',
    'cursor-pointer',
    'items-center',
    'rounded-lg',
    'bg-gray-900',
    'px-4',
    'py-2.5',
    'text-sm',
    'font-medium',
    'text-white',
    'transition',
    'hover:bg-gray-800',
    'focus:outline-none',
    'focus:ring-2',
    'focus:ring-gray-900',
    'focus:ring-offset-2',
]);

$linkClass = 'font-medium text-gray-900 hover:underline';
?>

<div class="px-4 py-12">

    <div class="mx-auto w-full max-w-6xl">

        <div class="mb-8 flex items-center justify-between gap-4">

            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">
                    Products
                </h1>

                <p class="mt-2 text-sm text-gray-500">
                    Manage your products
                </p>
            </div>

            <a
                href="<?= Html::encode($urlGenerator->generate('admin/product/create')) ?>"
                class="<?= $buttonClass ?>"
            >
                Create Product
            </a>

        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

            <?php if ($products === []): ?>

                <div class="px-6 py-12 text-center">

                    <h2 class="text-sm font-medium text-gray-900">
                        No products found
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Create your first product to get started.
                    </p>

                    <a
                        href="<?= Html::encode($urlGenerator->generate('admin/product/create')) ?>"
                        class="mt-5 <?= $buttonClass ?>"
                    >
                        Create Product
                    </a>

                </div>

            <?php else: ?>

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                >
                                    Title
                                </th>

                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                >
                                    Quantity
                                </th>

                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                >
                                    Price
                                </th>

                                <th
                                    scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500"
                                >
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">

                            <?php foreach ($products as $product): ?>

                                <tr class="transition hover:bg-gray-50">

                                    <td class="whitespace-nowrap px-6 py-4">

                                        <div class="text-sm font-medium text-gray-900">
                                            <?= Html::encode($product->title) ?>
                                        </div>

                                        <?php if ($product->description !== null): ?>

                                            <div class="mt-1 max-w-md truncate text-sm text-gray-500">
                                                <?= Html::encode($product->description) ?>
                                            </div>

                                        <?php endif ?>

                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                        <?= Html::encode((string) $product->quantity) ?>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                        <?= Html::encode((string) $product->price) ?>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm">

                                        <a
                                            href="#"
                                            class="<?= $linkClass ?>"
                                        >
                                            Edit
                                        </a>

                                        <span class="mx-2 text-gray-300">|</span>

                                        <a
                                            href="products/<?= Html::encode((string) $product->id) ?>/delete"
                                            class="font-medium text-red-600 hover:underline"
                                        >
                                            Delete
                                        </a>

                                    </td>

                                </tr>

                            <?php endforeach ?>

                        </tbody>

                    </table>

                </div>

            <?php endif ?>

        </div>

    </div>

</div>
