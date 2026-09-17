<?php

declare(strict_types=1);

use App\Product\Product;
use App\Product\ProductImage;
use Yiisoft\Html\Html;
use Yiisoft\View\WebView;

/**
 * @var Product[] $products
 * @var array<int, ProductImage[]> $images
 * @var WebView $this
 */

$this->setTitle('Products');
?>

<div class="px-4 py-12">
    <div class="mx-auto w-full max-w-7xl">
        <div class="mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                Products
            </h1>
            <p class="mt-2 text-sm text-gray-500">
                Browse available products
            </p>
        </div>

        <?php if ($products === []): ?>
            <div class="rounded-2xl border border-gray-200 bg-white p-10 text-center shadow-sm">
                <p class="text-gray-500">No products found.</p>
            </div>
        <?php else: ?>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <?php foreach ($products as $product): ?>
                    <?php $productImages = $images[$product->getId()] ?? []; ?>

                    <a href="<?= Html::encode($urlGenerator->generate('product/view', ['id' => $product->getId()])) ?>" class="block">
                    <article class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="aspect-square bg-gray-100">
                            <?php if ($productImages !== []): ?>
                                <img
                                    src="<?= Html::encode('/' . ltrim($productImages[0]->getPath(), '/')) ?>"
                                    alt="<?= Html::encode($product->getTitle()) ?>"
                                    class="h-full w-full object-cover"
                                >
                            <?php else: ?>
                                <div class="flex h-full items-center justify-center text-sm text-gray-400">
                                    No Image
                                </div>
                            <?php endif ?>
                        </div>

                        <div class="p-5">
                            <h2 class="truncate text-lg font-semibold text-gray-900">
                                <?= Html::encode($product->getTitle()) ?>
                            </h2>

                            <?php if ($product->getDescription() !== null): ?>
                                <p class="mt-2 h-10 overflow-hidden text-sm leading-5 text-gray-500">
                                    <?= Html::encode($product->getDescription()) ?>
                                </p>
                            <?php endif ?>

                            <div class="mt-5 flex items-center justify-between">
                                <span class="text-lg font-bold text-gray-900">
                                    <?= Html::encode($product->getPrice()) ?>
                                </span>

                                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-600">
                                    Stock: <?= Html::encode((string) $product->getQuantity()) ?>
                                </span>
                            </div>
                        </div>
                    </article>
                    </a>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>
</div>
