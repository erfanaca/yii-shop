<?php

declare(strict_types=1);

use Yiisoft\Html\Html;
use Yiisoft\Yii\View\Renderer\Csrf;
use Yiisoft\View\WebView;
use Yiisoft\Router\UrlGeneratorInterface;

/** @var array $items */
/** @var WebView $this */
/** @var UrlGeneratorInterface $urlGenerator */
/** @var Csrf $csrf */

$this->setTitle('Shopping Cart');
?>

<div class="px-4 py-12">
    <div class="mx-auto max-w-6xl">
        <h1 class="mb-6 text-3xl font-bold">Shopping Cart</h1>

        <?php if ($items === []): ?>
            <div class="rounded-2xl border bg-white p-8 text-gray-500">
                Your cart is empty.
            </div>
        <?php else: ?>
            <div class="overflow-hidden rounded-2xl border bg-white shadow-sm">
                <table class="w-full">
                    <thead class="border-b bg-gray-50">
                    <tr>
                        <th class="p-4 text-left">Product</th>
                        <th class="p-4">Price</th>
                        <th class="p-4">Quantity</th>
                        <th class="p-4">Total</th>
                        <th class="p-4"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($items as $item): ?>
                        <?php
                        $total = (float)$item['unit_price'] * (int)$item['quantity'];
                        ?>
                        <tr class="border-b">
                            <td class="p-4 font-medium">
                                <?= Html::encode($item['title']) ?>
                            </td>
                            <td class="p-4 text-center"><?= Html::encode($item['unit_price']) ?></td>
                            <td class="p-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <?= $decForm = Html::form()
                                        ->post($urlGenerator->generate('cart/decrease', ['id' => $item['product_id']]))
                                        ->csrf($csrf) ?>

                                    <?= $decForm->open() ?>
                                    <button class="rounded bg-gray-100 px-3 py-1">-</button>
                                    <?= $decForm->close() ?>

                                    <span class="px-2"><?= $item['quantity'] ?></span>

                                    <?= $incForm = Html::form()
                                        ->post($urlGenerator->generate('cart/add', ['id' => $item['product_id']]))
                                        ->csrf($csrf) ?>

                                    <?= $incForm->open() ?>
                                    <button class="rounded bg-gray-900 px-3 py-1 text-white">+</button>
                                    <?= $incForm->close() ?>
                                </div>
                            </td>
                            <td class="p-4 text-center"><?= $total ?></td>
                            <td class="p-4 text-center">
                                <?= $removeForm = Html::form()
                                    ->post($urlGenerator->generate('cart/remove', ['id' => $item['product_id']]))
                                    ->csrf($csrf) ?>

                                <?= $removeForm->open() ?>

                                <button class="rounded bg-red-600 px-3 py-1 text-white">
                                    Remove
                                </button>
                                <?= $removeForm->close() ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
