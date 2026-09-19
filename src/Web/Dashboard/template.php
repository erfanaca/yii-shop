<?php

declare(strict_types=1);

use App\Order\OrderStatus;
use App\Order\Query\OrderDashboard;
use App\Order\Query\OrderSummary;
use Yiisoft\Html\Html;
use Yiisoft\View\WebView;

/** @var OrderDashboard $dashboard */
/** @var WebView $this */

$this->setTitle('Dashboard');

/** @var callable(OrderStatus): array{label: string, classes: string} $statusPresentation */
$statusPresentation = static fn (OrderStatus $status): array => match ($status) {
    OrderStatus::Paid => [
        'label' => 'Paid',
        'classes' => 'bg-green-50 text-green-700 ring-green-600/20',
    ],
    OrderStatus::Cancelled => [
        'label' => 'Payment Failed',
        'classes' => 'bg-red-50 text-red-700 ring-red-600/20',
    ],
    OrderStatus::Pending => [
        'label' => 'Pending',
        'classes' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
    ],
};

?>

<div class="px-4 py-12">
    <div class="mx-auto w-full max-w-6xl">
        <div class="mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">My Dashboard</h1>
            <p class="mt-2 text-sm text-gray-500">
                Review your order history, payment status, invoices, and purchased items.
            </p>
        </div>

        <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-gray-500">Total Orders</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">
                    <?= Html::encode((string) $dashboard->getTotalOrders()) ?>
                </p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-gray-500">Paid Orders</p>
                <p class="mt-2 text-3xl font-bold text-green-700">
                    <?= Html::encode((string) $dashboard->getPaidOrders()) ?>
                </p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-gray-500">Failed Payments</p>
                <p class="mt-2 text-3xl font-bold text-red-700">
                    <?= Html::encode((string) $dashboard->getCancelledOrders()) ?>
                </p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-gray-500">Total Paid</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">
                    <?= Html::encode($dashboard->getTotalPaidAmount()) ?>
                </p>
                <?php if ($dashboard->getPendingOrders() > 0): ?>
                    <p class="mt-1 text-xs text-amber-700">
                        <?= Html::encode((string) $dashboard->getPendingOrders()) ?> pending
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <div class="mb-5 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Order History</h2>
                <p class="mt-1 text-sm text-gray-500">Newest orders are shown first.</p>
            </div>
        </div>

        <?php if ($dashboard->getOrders() === []): ?>
            <div class="rounded-2xl border border-gray-200 bg-white p-10 text-center shadow-sm">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-xl">🛍️</div>
                <h3 class="mt-4 text-lg font-semibold text-gray-900">No orders yet</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Your completed or failed checkout attempts will appear here.
                </p>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($dashboard->getOrders() as $order): ?>
                    <?php
                    /** @var OrderSummary $order */
                    $status = $statusPresentation($order->getStatus());
                    ?>
                    <article class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                        <div class="flex flex-col gap-5 border-b border-gray-100 p-5 lg:flex-row lg:items-center lg:justify-between">
                            <div class="flex flex-wrap items-center gap-x-6 gap-y-3">
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Order</p>
                                    <p class="mt-1 font-semibold text-gray-900">#<?= Html::encode((string) $order->getId()) ?></p>
                                </div>

                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Invoice</p>
                                    <p class="mt-1 font-mono text-sm font-semibold text-gray-800">
                                        <?= Html::encode($order->getInvoiceNumber()) ?>
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Transaction</p>
                                    <p class="mt-1 font-mono text-sm font-semibold text-gray-800">
                                        <?= Html::encode($order->getTransactionNumber()) ?>
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Date</p>
                                    <p class="mt-1 text-sm font-medium text-gray-800">
                                        <?= Html::encode($order->getCreatedAt()->format('Y-m-d H:i')) ?>
                                    </p>
                                </div>

                                <?php if ($order->hasDiscount()): ?>
                                    <div>
                                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Discount Code</p>
                                        <div class="mt-1 flex flex-wrap items-center gap-2">
                                            <span class="rounded-md bg-green-50 px-2 py-1 font-mono text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20">
                                                <?= Html::encode((string) $order->getDiscountCode()) ?>
                                            </span>
                                            <span class="text-sm font-semibold text-green-700">
                                                -<?= Html::encode(number_format((float) $order->getDiscountAmount(), 2, '.', '')) ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="flex items-center gap-4 lg:text-right">
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Total</p>
                                    <p class="mt-1 text-lg font-bold text-gray-900">
                                        <?= Html::encode(number_format((float) $order->getTotalAmount(), 2, '.', '')) ?>
                                    </p>
                                </div>

                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset <?= Html::encode($status['classes']) ?>">
                                    <?= Html::encode($status['label']) ?>
                                </span>
                            </div>
                        </div>

                        <details class="group">
                            <summary class="flex cursor-pointer list-none items-center justify-between px-5 py-4 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                <span>
                                    View items (<?= Html::encode((string) count($order->getItems())) ?>)
                                </span>
                                <span class="text-lg text-gray-400 transition group-open:rotate-180">⌄</span>
                            </summary>

                            <div class="border-t border-gray-100 bg-gray-50/60 px-5 py-4">
                                <?php if ($order->getItems() === []): ?>
                                    <p class="text-sm text-gray-500">No order items found.</p>
                                <?php else: ?>
                                    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
                                        <div class="divide-y divide-gray-100">
                                            <?php foreach ($order->getItems() as $item): ?>
                                                <div class="grid gap-3 p-4 sm:grid-cols-[1fr_auto_auto_auto] sm:items-center">
                                                    <div>
                                                        <p class="font-medium text-gray-900">
                                                            <?= Html::encode($item->getProductTitle()) ?>
                                                        </p>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        Qty: <span class="font-medium text-gray-800"><?= Html::encode((string) $item->getQuantity()) ?></span>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        Unit: <span class="font-medium text-gray-800"><?= Html::encode($item->getUnitPrice()) ?></span>
                                                    </div>
                                                    <div class="text-sm font-semibold text-gray-900 sm:text-right">
                                                        <?= Html::encode($item->getTotalAmount()) ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </details>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
