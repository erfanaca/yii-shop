<?php

declare(strict_types=1);

use App\Discount\CartPricing;
use App\Discount\DiscountType;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\View\WebView;
use Yiisoft\Yii\View\Renderer\Csrf;

/** @var array $items */
/** @var CartPricing $pricing */
/** @var ?string $paymentResult */
/** @var ?int $orderId */
/** @var ?string $transactionNumber */
/** @var ?string $invoiceNumber */
/** @var ?string $checkoutError */
/** @var ?string $discountError */
/** @var ?string $discountApplied */
/** @var string $discountCodeInput */
/** @var WebView $this */
/** @var UrlGeneratorInterface $urlGenerator */
/** @var Csrf $csrf */

$this->setTitle('Shopping Cart');
$appliedDiscount = $pricing->getDiscountCode();
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

        <?php if ($discountError !== null): ?>
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
                <?= Html::encode($discountError) ?>
            </div>
        <?php elseif ($discountApplied !== null): ?>
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
                Discount code <strong><?= Html::encode($discountApplied) ?></strong> was applied successfully.
            </div>
        <?php endif; ?>

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
                            $lineTotal = (float) $item['unit_price'] * (int) $item['quantity'];
                            $isDiscountEligible = $pricing->hasDiscount()
                                && $pricing->isProductEligible((int) $item['product_id']);
                            ?>
                            <tr class="border-b">
                                <td class="p-4 font-medium">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span><?= Html::encode($item['title']) ?></span>
                                        <?php if ($isDiscountEligible): ?>
                                            <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">
                                                Discount applies
                                            </span>
                                        <?php endif; ?>
                                    </div>
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
                                <td class="p-4 text-center"><?= number_format($lineTotal, 2, '.', '') ?></td>
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

            <div class="mt-6 grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(320px,420px)]">
                <div class="rounded-2xl border bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold">Discount code</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        A code is calculated only against products covered by that code.
                    </p>

                    <?php if ($appliedDiscount === null): ?>
                        <?= $discountForm = Html::form()
                            ->post($urlGenerator->generate('cart/discount/apply'))
                            ->csrf($csrf) ?>
                        <?= $discountForm->open() ?>
                        <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                            <input
                                type="text"
                                name="code"
                                value="<?= Html::encode($discountCodeInput) ?>"
                                placeholder="Enter discount code"
                                autocomplete="off"
                                class="min-w-0 flex-1 rounded-lg border border-gray-300 px-4 py-2.5 uppercase outline-none focus:border-gray-500"
                            >
                            <button class="rounded-lg bg-gray-900 px-5 py-2.5 font-medium text-white hover:bg-gray-800">
                                Apply code
                            </button>
                        </div>
                        <?= $discountForm->close() ?>
                    <?php else: ?>
                        <div class="mt-4 rounded-xl border border-gray-200 bg-gray-50 p-4">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <div class="font-semibold"><?= Html::encode($appliedDiscount->getCode()) ?></div>
                                    <div class="mt-1 text-sm text-gray-600">
                                        <?php if ($appliedDiscount->getType() === DiscountType::Percentage): ?>
                                            <?= Html::encode($appliedDiscount->getValue()) ?>% off eligible products,
                                            up to <?= Html::encode($appliedDiscount->getMaxDiscountAmount() ?? '0.00') ?>.
                                        <?php else: ?>
                                            <?= Html::encode($appliedDiscount->getValue()) ?> fixed discount on eligible products.
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?= $removeDiscountForm = Html::form()
                                    ->post($urlGenerator->generate('cart/discount/remove'))
                                    ->csrf($csrf) ?>
                                <?= $removeDiscountForm->open() ?>
                                <button class="text-sm font-medium text-red-600 hover:text-red-700">Remove</button>
                                <?= $removeDiscountForm->close() ?>
                            </div>

                            <?php if ($pricing->getDiscountError() !== null): ?>
                                <div class="mt-3 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                                    <?= Html::encode($pricing->getDiscountError()) ?>
                                    The code remains attached to the cart until you remove or replace it, but no discount is currently deducted.
                                </div>
                            <?php elseif ($pricing->getEligibleSubtotalAmount() !== null): ?>
                                <div class="mt-3 text-sm text-green-700">
                                    Eligible products subtotal: <strong><?= Html::encode($pricing->getEligibleSubtotalAmount()) ?></strong>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="rounded-2xl border bg-white p-6 shadow-sm">
                    <div class="space-y-3 border-b pb-4">
                        <div class="flex items-center justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span><?= Html::encode($pricing->getSubtotalAmount()) ?></span>
                        </div>

                        <?php if ($pricing->hasDiscount()): ?>
                            <div class="flex items-center justify-between text-green-700">
                                <span>Discount (<?= Html::encode($appliedDiscount?->getCode() ?? '') ?>)</span>
                                <span>-<?= Html::encode($pricing->getDiscountAmount()) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-lg font-semibold">Order total</span>
                        <span class="text-xl font-bold"><?= Html::encode($pricing->getTotalAmount()) ?></span>
                    </div>

                    <div class="mt-5 flex flex-wrap gap-3">
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
            </div>
        <?php endif; ?>
    </div>
</div>
