<?php

declare(strict_types=1);

use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\View\WebView;
use Yiisoft\Yii\View\Renderer\Csrf;

/** @var array $items */
/** @var ?string $paymentResult */
/** @var ?int $orderId */
/** @var ?string $transactionNumber */
/** @var ?string $invoiceNumber */
/** @var ?string $checkoutError */
/** @var WebView $this */
/** @var UrlGeneratorInterface $urlGenerator */
/** @var Csrf $csrf */

$this->setTitle('Shopping Cart');
?>

<div class="px-4 py-12">
    <div class="mx-auto max-w-6xl">
        <h1 class="mb-6 text-3xl font-bold">Shopping Cart</h1>

        <?php if ($paymentResult === 'success' && $orderId !== null): ?>
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
                <div>Simulated payment was successful. Order #<?= Html::encode((string) $orderId) ?> is now PAID.</div>
                <?php if ($invoiceNumber !== null && $transactionNumber !== null): ?>
                    <div class="mt-2 text-sm">
                        Invoice: <strong><?= Html::encode($invoiceNumber) ?></strong>
                        <span class="mx-2">|</span>
                        Transaction: <strong><?= Html::encode($transactionNumber) ?></strong>
                    </div>
                <?php endif; ?>
            </div>
        <?php elseif ($paymentResult === 'failure' && $orderId !== null): ?>
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
                <div>Simulated payment failed. Order #<?= Html::encode((string) $orderId) ?> was CANCELLED and your cart was kept.</div>
                <?php if ($invoiceNumber !== null && $transactionNumber !== null): ?>
                    <div class="mt-2 text-sm">
                        Invoice: <strong><?= Html::encode($invoiceNumber) ?></strong>
                        <span class="mx-2">|</span>
                        Transaction: <strong><?= Html::encode($transactionNumber) ?></strong>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($checkoutError !== null): ?>
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
                <?= Html::encode($checkoutError) ?>
            </div>
        <?php endif; ?>

        <?php if ($items === []): ?>
            <div class="rounded-2xl border bg-white p-8 text-gray-500">
                Your cart is empty.
            </div>
        <?php else: ?>
            <?php $grandTotal = 0.0; ?>

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
                        $total = (float) $item['unit_price'] * (int) $item['quantity'];
                        $grandTotal += $total;
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
                            <td class="p-4 text-center"><?= number_format($total, 2, '.', '') ?></td>
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

            <div class="mt-6 rounded-2xl border bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center justify-between">
                    <span class="text-lg font-semibold">Order total</span>
                    <span class="text-xl font-bold"><?= number_format($grandTotal, 2, '.', '') ?></span>
                </div>

                <div class="flex flex-wrap gap-3">
                    <?= $successForm = Html::form()
                        ->post($urlGenerator->generate('checkout/simulate', ['result' => 'success']))
                        ->csrf($csrf) ?>
                    <?= $successForm->open() ?>
                    <button class="rounded-lg bg-green-600 px-5 py-2.5 font-medium text-white hover:bg-green-700">
                        Payment Successful
                    </button>
                    <?= $successForm->close() ?>

                    <?= $failureForm = Html::form()
                        ->post($urlGenerator->generate('checkout/simulate', ['result' => 'failure']))
                        ->csrf($csrf) ?>
                    <?= $failureForm->open() ?>
                    <button class="rounded-lg bg-red-600 px-5 py-2.5 font-medium text-white hover:bg-red-700">
                        Payment Failed
                    </button>
                    <?= $failureForm->close() ?>
                </div>

                <p class="mt-3 text-sm text-gray-500">
                    These buttons simulate a payment gateway callback for this test project.
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>
