<?php

declare(strict_types=1);

use App\Order\OrderStatus;
use App\Order\Query\AdminOrderSummary;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\View\WebView;

/** @var AdminOrderSummary[] $orders */
/** @var bool $canView */
/** @var WebView $this */
/** @var UrlGeneratorInterface $urlGenerator */

$this->setTitle('Orders');

/** @var callable(OrderStatus): array{label: string, classes: string} $statusPresentation */
$statusPresentation = static fn (OrderStatus $status): array => match ($status) {
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
    <div class="mx-auto w-full max-w-7xl">
        <div class="mb-8">
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Orders</h1>
            <p class="mt-2 text-sm text-gray-500">View and manage customer orders.</p>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <?php if ($orders === []): ?>
                <div class="px-6 py-12 text-center">
                    <h2 class="text-sm font-medium text-gray-900">No orders found</h2>
                    <p class="mt-1 text-sm text-gray-500">Customer orders will appear here after checkout.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Invoice</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Transaction</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                            <?php if ($canView): ?>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                            <?php endif; ?>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                        <?php foreach ($orders as $order): ?>
                            <?php $status = $statusPresentation($order->getStatus()); ?>
                            <tr class="transition hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-gray-900">
                                    #<?= Html::encode((string) $order->getId()) ?>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900"><?= Html::encode($order->getUserEmail()) ?></div>
                                    <div class="mt-1 text-xs text-gray-400">User #<?= Html::encode((string) $order->getUserId()) ?></div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 font-mono text-sm text-gray-700">
                                    <?= Html::encode($order->getInvoiceNumber()) ?>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 font-mono text-sm text-gray-700">
                                    <?= Html::encode($order->getTransactionNumber()) ?>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset <?= Html::encode($status['classes']) ?>">
                                        <?= Html::encode($status['label']) ?>
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-gray-900">
                                    <?= Html::encode(number_format((float) $order->getTotalAmount(), 2, '.', '')) ?>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                    <?= Html::encode($order->getCreatedAt()->format('Y-m-d H:i')) ?>
                                </td>
                                <?php if ($canView): ?>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                        <a
                                            href="<?= Html::encode($urlGenerator->generate('admin/order/view', ['id' => $order->getId()])) ?>"
                                            class="font-medium text-gray-900 hover:underline"
                                        >
                                            View
                                        </a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
