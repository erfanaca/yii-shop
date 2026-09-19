<?php

declare(strict_types=1);

use App\Order\OrderStatus;
use App\Order\Query\AdminOrderDetails;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\View\WebView;

/** @var AdminOrderDetails $order */
/** @var WebView $this */
/** @var UrlGeneratorInterface $urlGenerator */

$this->setTitle('Order #' . $order->getId());

$status = match ($order->getStatus()) {
    OrderStatus::Paid => [
        'label' => 'Paid',
        'classes' => 'bg-green-50 text-green-700 ring-green-600/20',
    ],
    OrderStatus::Cancelled => [
        'label' => 'Cancelled',
        'classes' => 'bg-red-50 text-red-700 ring-red-600/20',
    ],
    OrderStatus::Pending => [
        'label' => 'Pending',
        'classes' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
    ],
};
?>

<div class="px-4 py-12">
    <div class="mx-auto w-full max-w-5xl">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Order #<?= Html::encode((string) $order->getId()) ?></h1>
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset <?= Html::encode($status['classes']) ?>">
                        <?= Html::encode($status['label']) ?>
                    </span>
                </div>
                <p class="mt-2 text-sm text-gray-500">Order details and purchased items.</p>
            </div>

            <a
                href="<?= Html::encode($urlGenerator->generate('admin/order/index')) ?>"
                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
            >
                Back to Orders
            </a>
        </div>

        <div class="grid gap-5 lg:grid-cols-2">
            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-400">Customer</h2>
                <dl class="mt-5 space-y-4">
                    <div>
                        <dt class="text-xs font-medium text-gray-400">Email</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900"><?= Html::encode($order->getUserEmail()) ?></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400">User ID</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">#<?= Html::encode((string) $order->getUserId()) ?></dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-400">Payment</h2>
                <dl class="mt-5 space-y-4">
                    <div>
                        <dt class="text-xs font-medium text-gray-400">Invoice Number</dt>
                        <dd class="mt-1 font-mono text-sm font-semibold text-gray-900"><?= Html::encode($order->getInvoiceNumber()) ?></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400">Transaction Number</dt>
                        <dd class="mt-1 font-mono text-sm font-semibold text-gray-900"><?= Html::encode($order->getTransactionNumber()) ?></dd>
                    </div>

                    <?php if ($order->hasDiscount()): ?>
                        <div class="rounded-xl border border-green-200 bg-green-50/60 p-4">
                            <dt class="text-xs font-medium uppercase tracking-wider text-green-700">Discount</dt>
                            <dd class="mt-2 flex flex-wrap items-center justify-between gap-3">
                                <span class="rounded-md bg-white px-2.5 py-1.5 font-mono text-sm font-semibold text-green-800 ring-1 ring-inset ring-green-600/20">
                                    <?= Html::encode((string) $order->getDiscountCode()) ?>
                                </span>
                                <span class="text-base font-bold text-green-800">
                                    -<?= Html::encode(number_format((float) $order->getDiscountAmount(), 2, '.', '')) ?>
                                </span>
                            </dd>
                        </div>
                    <?php endif; ?>
                </dl>
            </section>
        </div>

        <section class="mt-5 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="grid gap-5 sm:grid-cols-3">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Created</p>
                    <p class="mt-2 text-sm font-medium text-gray-900"><?= Html::encode($order->getCreatedAt()->format('Y-m-d H:i')) ?></p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Last Updated</p>
                    <p class="mt-2 text-sm font-medium text-gray-900">
                        <?= Html::encode($order->getUpdatedAt()?->format('Y-m-d H:i') ?? '—') ?>
                    </p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Total Amount</p>
                    <p class="mt-2 text-xl font-bold text-gray-900"><?= Html::encode(number_format((float) $order->getTotalAmount(), 2, '.', '')) ?></p>
                </div>
            </div>
        </section>

        <section class="mt-5 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-6 py-5">
                <h2 class="text-lg font-semibold text-gray-900">Order Items</h2>
                <p class="mt-1 text-sm text-gray-500"><?= Html::encode((string) count($order->getItems())) ?> item line(s)</p>
            </div>

            <?php if ($order->getItems() === []): ?>
                <div class="px-6 py-10 text-center text-sm text-gray-500">No order items found.</div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Unit Price</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Line Total</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                        <?php foreach ($order->getItems() as $item): ?>
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= Html::encode($item->getProductTitle()) ?></td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600"><?= Html::encode((string) $item->getQuantity()) ?></td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600"><?= Html::encode($item->getUnitPrice()) ?></td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-semibold text-gray-900"><?= Html::encode($item->getTotalAmount()) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>
