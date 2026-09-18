<?php

declare(strict_types=1);

use App\Product\Product;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\View\WebView;
use Yiisoft\Yii\View\Renderer\Csrf;

/**
 * @var Product[] $products
 * @var bool $canCreate
 * @var bool $canEdit
 * @var bool $canDelete
 * @var WebView $this
 * @var UrlGeneratorInterface $urlGenerator
 * @var Csrf $csrf
 */

$this->setTitle('Products');

$buttonClass = implode(' ', [
    'inline-flex',
    'cursor-pointer',
    'items-center',
    'rounded-lg',
    'bg-gray-900',
    'px-4',
    'py-2.5',
    'text-sm',
    'font-medium',
    'text-white',
    'transition',
    'hover:bg-gray-800',
    'focus:outline-none',
    'focus:ring-2',
    'focus:ring-gray-900',
    'focus:ring-offset-2',
]);
?>

<div class="px-4 py-12">
    <div class="mx-auto w-full max-w-6xl">
        <div class="mb-8 flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Products</h1>
                <p class="mt-2 text-sm text-gray-500">Manage your products</p>
            </div>

            <?php if ($canCreate): ?>
                <a
                    href="<?= Html::encode($urlGenerator->generate('admin/product/create')) ?>"
                    class="<?= $buttonClass ?>"
                >
                    Create Product
                </a>
            <?php endif; ?>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <?php if ($products === []): ?>
                <div class="px-6 py-12 text-center">
                    <h2 class="text-sm font-medium text-gray-900">No products found</h2>
                    <p class="mt-1 text-sm text-gray-500">Create your first product to get started.</p>

                    <?php if ($canCreate): ?>
                        <a
                            href="<?= Html::encode($urlGenerator->generate('admin/product/create')) ?>"
                            class="mt-5 <?= $buttonClass ?>"
                        >
                            Create Product
                        </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Quantity
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Price
                            </th>
                            <?php if ($canEdit || $canDelete): ?>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Actions
                                </th>
                            <?php endif; ?>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                        <?php foreach ($products as $product): ?>
                            <tr class="transition hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= Html::encode($product->getTitle()) ?>
                                    </div>

                                    <?php if ($product->getDescription() !== null): ?>
                                        <div class="mt-1 max-w-md truncate text-sm text-gray-500">
                                            <?= Html::encode($product->getDescription()) ?>
                                        </div>
                                    <?php endif ?>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                    <?= Html::encode((string) $product->getQuantity()) ?>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                    <?= Html::encode($product->getPrice()) ?>
                                </td>

                                <?php if ($canEdit || $canDelete): ?>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                        <div class="inline-flex items-center gap-3">
                                            <?php if ($canEdit): ?>
                                                <a
                                                    href="<?= Html::encode($urlGenerator->generate('admin/product/edit', ['id' => $product->getId()])) ?>"
                                                    class="font-medium text-gray-900 hover:underline"
                                                >
                                                    Edit
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($canDelete): ?>
                                                <?php $deleteForm = Html::form()
                                                    ->post($urlGenerator->generate('admin/product/delete', ['id' => $product->getId()]))
                                                    ->csrf($csrf); ?>

                                                <?= $deleteForm->open() ?>
                                                <button
                                                    type="submit"
                                                    class="cursor-pointer border-0 bg-transparent p-0 font-medium text-red-600 hover:underline"
                                                    onclick="return confirm('Delete this product?')"
                                                >
                                                    Delete
                                                </button>
                                                <?= $deleteForm->close() ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>
