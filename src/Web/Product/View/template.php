<?php

declare(strict_types=1);

use App\Product\Product;
use App\Product\ProductImage;
use App\Category\Category;
use Yiisoft\Html\Html;
use Yiisoft\View\WebView;

/** @var Product $product */
/** @var ProductImage[] $images */
/** @var Category[] $categories */
/** @var WebView $this */

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
