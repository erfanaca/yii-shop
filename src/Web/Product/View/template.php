<?php

declare(strict_types=1);

use App\Product\Product;
use App\Product\ProductImage;
use App\Category\Category;
use Yiisoft\Html\Html;
use Yiisoft\View\WebView;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\Renderer\Csrf;

/** @var Product $product */
/** @var ProductImage[] $images */
/** @var Category[] $categories */
/** @var int $cartQuantity */
/** @var WebView $this */
/** @var UrlGeneratorInterface $urlGenerator */
/** @var Csrf $csrf */

$this->setTitle($product->getTitle());
?>

<div class="px-4 py-12">
    <div class="mx-auto max-w-6xl">
        <div class="grid gap-8 lg:grid-cols-2">
            <div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <?php foreach ($images as $image): ?>
                        <img
                            src="<?= Html::encode($image->getPath()) ?>"
                            class="h-72 w-full rounded-2xl border border-gray-200 object-cover shadow-sm"
                            alt="<?= Html::encode($product->getTitle()) ?>"
                        >
                    <?php endforeach; ?>

                    <?php if ($images === []): ?>
                        <div class="flex h-72 items-center justify-center rounded-2xl border bg-gray-50 text-gray-400">
                            No image
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h1 class="text-3xl font-bold text-gray-900">
                    <?= Html::encode($product->getTitle()) ?>
                </h1>

                <div class="mt-4 text-2xl font-semibold text-gray-900">
                    <?= Html::encode($product->getPrice()) ?>
                </div>

                <div class="mt-6 space-y-3 text-sm text-gray-600">
                    <p><b>Quantity:</b> <?= Html::encode((string)$product->getQuantity()) ?></p>
                    <p><b>Created:</b> <?= Html::encode($product->getCreatedAt()->format('Y-m-d')) ?></p>
                </div>

                <?php
                $addToCartForm = Html::form()
                    ->post($urlGenerator->generate('cart/add', ['id' => $product->getId()]))
                    ->csrf($csrf);

                $decreaseCartForm = Html::form()
                    ->post($urlGenerator->generate('cart/decrease', ['id' => $product->getId()]))
                    ->csrf($csrf);
                ?>

                <div class="mt-6 rounded-xl border border-gray-200 bg-gray-50 p-4">
                    <div class="mb-3 flex items-center justify-between gap-4">
                        <span class="text-sm font-medium text-gray-700">In your cart</span>
                        <span class="text-sm text-gray-500">
                            <?= Html::encode((string) $cartQuantity) ?> item<?= $cartQuantity === 1 ? '' : 's' ?>
                        </span>
                    </div>

                    <div class="grid grid-cols-[3rem_1fr_3rem] items-stretch gap-2">
                        <?= $decreaseCartForm->open() ?>
                            <button
                                type="submit"
                                class="flex h-12 w-full items-center justify-center rounded-lg border border-gray-300 bg-white text-xl font-semibold text-gray-800 transition hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-40"
                                aria-label="Remove one item from cart"
                                <?= $cartQuantity <= 0 ? 'disabled' : '' ?>
                            >
                                −
                            </button>
                        <?= $decreaseCartForm->close() ?>

                        <div class="flex h-12 items-center justify-center rounded-lg bg-gray-900 px-4 text-sm font-semibold text-white">
                            <?= $cartQuantity > 0
                                ? Html::encode((string) $cartQuantity) . ' in cart'
                                : 'Not in cart' ?>
                        </div>

                        <?= $addToCartForm->open() ?>
                            <button
                                type="submit"
                                class="flex h-12 w-full items-center justify-center rounded-lg bg-gray-900 text-xl font-semibold text-white transition hover:bg-gray-800"
                                aria-label="Add one item to cart"
                            >
                                +
                            </button>
                        <?= $addToCartForm->close() ?>
                    </div>

                    <?php if ($cartQuantity === 0): ?>
                        <p class="mt-3 text-center text-xs text-gray-500">
                            Use + to add this product to your cart.
                        </p>
                    <?php endif; ?>
                </div>

                <div class="mt-6">
                    <h2 class="font-semibold text-gray-900">Categories</h2>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <?php foreach ($categories as $category): ?>
                            <span class="rounded-full bg-gray-100 px-3 py-1 text-sm text-gray-700">
                                <?= Html::encode($category->getTitle()) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-gray-900">Description</h2>
            <p class="mt-3 whitespace-pre-line text-gray-600">
                <?= Html::encode($product->getDescription() ?? 'No description') ?>
            </p>
        </div>
    </div>
</div>
