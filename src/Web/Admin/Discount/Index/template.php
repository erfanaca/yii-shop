<?php

declare(strict_types=1);

use App\Discount\DiscountCode;
use App\Discount\DiscountScope;
use App\Discount\DiscountType;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\View\WebView;
use Yiisoft\Yii\View\Renderer\Csrf;

/** @var DiscountCode[] $discountCodes */
/** @var bool $canCreate */
/** @var bool $canView */
/** @var bool $canEdit */
/** @var bool $canDelete */
/** @var WebView $this */
/** @var UrlGeneratorInterface $urlGenerator */
/** @var Csrf $csrf */

$this->setTitle('Discount Codes');
$buttonClass = 'inline-flex cursor-pointer items-center rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800';
?>

<div class="px-4 py-12">
    <div class="mx-auto w-full max-w-6xl">
        <div class="mb-8 flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Discount Codes</h1>
                <p class="mt-2 text-sm text-gray-500">Create and manage promotional discounts.</p>
            </div>
            <?php if ($canCreate): ?>
                <a href="<?= Html::encode($urlGenerator->generate('admin/discount/create')) ?>" class="<?= $buttonClass ?>">Create Discount Code</a>
            <?php endif; ?>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <?php if ($discountCodes === []): ?>
                <div class="px-6 py-12 text-center">
                    <h2 class="text-sm font-medium text-gray-900">No discount codes found</h2>
                    <p class="mt-1 text-sm text-gray-500">Create your first discount code to get started.</p>
                    <?php if ($canCreate): ?>
                        <a href="<?= Html::encode($urlGenerator->generate('admin/discount/create')) ?>" class="mt-5 <?= $buttonClass ?>">Create Discount Code</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Discount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Users</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Products</th>
                            <?php if ($canView || $canEdit || $canDelete): ?>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                            <?php endif; ?>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                        <?php foreach ($discountCodes as $discountCode): ?>
                            <tr class="transition hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="inline-flex rounded-lg bg-gray-100 px-2.5 py-1 font-mono text-sm font-semibold text-gray-900">
                                        <?= Html::encode($discountCode->getCode()) ?>
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                    <?php if ($discountCode->getType() === DiscountType::Percentage): ?>
                                        <div class="font-medium text-gray-900"><?= Html::encode($discountCode->getValue()) ?>%</div>
                                        <div class="mt-1 text-xs text-gray-500">Max <?= Html::encode($discountCode->getMaxDiscountAmount() ?? '-') ?></div>
                                    <?php else: ?>
                                        <div class="font-medium text-gray-900"><?= Html::encode($discountCode->getValue()) ?> fixed</div>
                                        <div class="mt-1 text-xs text-gray-500">Min order <?= Html::encode($discountCode->getMinimumOrderAmount() ?? '-') ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                    <?= $discountCode->getUserScope() === DiscountScope::All ? 'All users' : 'Specific users' ?>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                    <?= $discountCode->getProductScope() === DiscountScope::All ? 'All products' : 'Specific products' ?>
                                </td>
                                <?php if ($canView || $canEdit || $canDelete): ?>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                        <div class="inline-flex items-center gap-3">
                                            <?php if ($canView): ?>
                                                <a href="<?= Html::encode($urlGenerator->generate('admin/discount/view', ['id' => $discountCode->getId()])) ?>" class="font-medium text-blue-600 hover:underline">View</a>
                                            <?php endif; ?>
                                            <?php if ($canEdit): ?>
                                                <a href="<?= Html::encode($urlGenerator->generate('admin/discount/edit', ['id' => $discountCode->getId()])) ?>" class="font-medium text-gray-900 hover:underline">Edit</a>
                                            <?php endif; ?>
                                            <?php if ($canDelete): ?>
                                                <?php $deleteForm = Html::form()->post($urlGenerator->generate('admin/discount/delete', ['id' => $discountCode->getId()]))->csrf($csrf); ?>
                                                <?= $deleteForm->open() ?>
                                                <button type="submit" class="cursor-pointer border-0 bg-transparent p-0 font-medium text-red-600 hover:underline" onclick="return confirm('Delete this discount code?')">Delete</button>
                                                <?= $deleteForm->close() ?>
                                            <?php endif; ?>
                                        </div>
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
