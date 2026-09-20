<?php

declare(strict_types=1);

use App\Discount\DiscountCode;
use App\Discount\DiscountScope;
use App\Discount\DiscountType;
use App\Product\Product;
use App\User\User;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\View\WebView;

/** @var DiscountCode $discountCode */
/** @var User[] $selectedUsers */
/** @var Product[] $selectedProducts */
/** @var bool $canEdit */
/** @var WebView $this */
/** @var UrlGeneratorInterface $urlGenerator */

$this->setTitle('Discount Code Details');
?>

<div class="px-4 py-12">
    <div class="mx-auto w-full max-w-4xl">
        <div class="mb-8 flex items-start justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Discount Code</h1>
                    <span
                        class="rounded-lg bg-gray-100 px-2.5 py-1 font-mono text-sm font-semibold text-gray-900"><?= Html::encode($discountCode->getCode()) ?></span>
                </div>
                <p class="mt-2 text-sm text-gray-500">Review discount rules and eligibility.</p>
            </div>
            <?php if ($canEdit): ?>
                <a href="<?= Html::encode($urlGenerator->generate('admin/discount/edit', ['id' => $discountCode->getId()])) ?>"
                   class="rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800">Edit</a>
            <?php endif; ?>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-sm font-semibold text-gray-900">Discount Rules</h2>
                <dl class="mt-5 space-y-4 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500">Type</dt>
                        <dd class="font-medium text-gray-900"><?= $discountCode->getType() === DiscountType::Percentage ? 'Percentage' : 'Fixed amount' ?></dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500">Value</dt>
                        <dd class="font-medium text-gray-900"><?= Html::encode($discountCode->getValue()) ?><?= $discountCode->getType() === DiscountType::Percentage ? '%' : '' ?></dd>
                    </div>
                    <?php if ($discountCode->getType() === DiscountType::Percentage): ?>
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500">Maximum discount</dt>
                            <dd class="font-medium text-gray-900"><?= Html::encode($discountCode->getMaxDiscountAmount() ?? '-') ?></dd>
                        </div>
                    <?php else: ?>
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500">Minimum order</dt>
                            <dd class="font-medium text-gray-900"><?= Html::encode($discountCode->getMinimumOrderAmount() ?? '-') ?></dd>
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500">Created</dt>
                        <dd class="font-medium text-gray-900"><?= Html::encode($discountCode->getCreatedAt()->format('Y-m-d H:i')) ?></dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-sm font-semibold text-gray-900">Eligibility</h2>
                <div class="mt-5 space-y-6">
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-gray-400">Users</div>
                        <?php if ($discountCode->getUserScope() === DiscountScope::All): ?>
                            <p class="mt-2 text-sm font-medium text-gray-900">All users</p>
                        <?php else: ?>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <?php foreach ($selectedUsers as $user): ?>
                                    <span
                                        class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700"><?= Html::encode($user->getEmail()) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-gray-400">Products</div>
                        <?php if ($discountCode->getProductScope() === DiscountScope::All): ?>
                            <p class="mt-2 text-sm font-medium text-gray-900">All products</p>
                        <?php else: ?>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <?php foreach ($selectedProducts as $product): ?>
                                    <span
                                        class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700"><?= Html::encode($product->getTitle()) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </div>

        <div class="mt-6">
            <a href="<?= Html::encode($urlGenerator->generate('admin/discount/index')) ?>"
               class="text-sm font-medium text-gray-700 hover:underline">← Back to discount codes</a>
        </div>
    </div>
</div>
