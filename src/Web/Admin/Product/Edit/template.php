<?php

declare(strict_types=1);

use App\Category\Category;
use App\Product\Product;
use App\Product\UpdateProductForm;
use Yiisoft\FormModel\Field;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\View\WebView;
use Yiisoft\Yii\View\Renderer\Csrf;

/**
 * @var UpdateProductForm $form
 * @var Product $product
 * @var Category[] $categories
 * @var WebView $this
 * @var UrlGeneratorInterface $urlGenerator
 * @var Csrf $csrf
 */

$this->setTitle('Edit Product');

$htmlForm = Html::form()
    ->post($urlGenerator->generate('admin/product/edit', ['id' => $product->getId()]))
    ->csrf($csrf)
    ->attribute('enctype', 'multipart/form-data');

$inputClass = implode(' ', [
    'block',
    'w-full',
    'rounded-lg',
    'border',
    'border-gray-300',
    'bg-white',
    'px-3',
    'py-2.5',
    'text-sm',
    'text-gray-900',
    'outline-none',
    'transition',
    'placeholder:text-gray-400',
    'focus:border-gray-900',
    'focus:ring-1',
    'focus:ring-gray-900',
]);

$textareaClass = $inputClass . ' resize-y';
$labelClass = 'block text-sm font-medium text-gray-700 mb-2';
$errorClass = 'mt-1.5 text-sm text-red-600';

$categoryOptions = [];
foreach ($categories as $category) {
    $categoryOptions[(string)$category->getId()] = $category->getTitle();
}
?>

<div class="px-4 py-12">
    <div class="mx-auto w-full max-w-2xl">
        <div class="mb-8">
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Edit Product</h1>
            <p class="mt-2 text-sm text-gray-500">Update product information</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
            <?= $htmlForm->open() ?>

            <div class="space-y-5">
                <div>
                    <?= Field::text($form, 'title')
                        ->label('Title')
                        ->labelClass($labelClass)
                        ->inputClass($inputClass)
                        ->errorClass($errorClass)
                        ->placeholder('Product title') ?>
                </div>

                <div>
                    <?= Field::textarea($form, 'description')
                        ->label('Description')
                        ->labelClass($labelClass)
                        ->inputClass($textareaClass)
                        ->errorClass($errorClass)
                        ->placeholder('Describe the product')
                        ->addInputAttributes(['rows' => 5]) ?>
                </div>

                <div>
                    <?= Field::number($form, 'quantity')
                        ->label('Quantity')
                        ->labelClass($labelClass)
                        ->inputClass($inputClass)
                        ->errorClass($errorClass)
                        ->addInputAttributes(['min' => 0, 'step' => 1]) ?>
                </div>

                <div>
                    <?= Field::text($form, 'price')
                        ->label('Price')
                        ->labelClass($labelClass)
                        ->inputClass($inputClass)
                        ->errorClass($errorClass)
                        ->placeholder('0.00') ?>
                </div>

                <div>
                    <label class="<?= $labelClass ?>">Images</label>
                    <input
                        type="file"
                        name="images[]"
                        multiple
                        accept="image/*"
                        class="<?= $inputClass ?>"
                    >
                    <p class="mt-1.5 text-xs text-gray-500">
                        You can select multiple images.
                    </p>
                </div>

                <div>
                    <?php if ($categoryOptions === []): ?>
                        <label class="<?= $labelClass ?>">Categories</label>
                        <div
                            class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-3 py-3 text-sm text-gray-500">
                            No categories are available yet.
                        </div>
                    <?php else: ?>
                        <?= Field::select($form, 'categoryIds')
                            ->label('Categories')
                            ->labelClass($labelClass)
                            ->inputClass($inputClass)
                            ->errorClass($errorClass)
                            ->optionsData($categoryOptions)
                            ->multiple()
                            ->size(min(max(count($categoryOptions), 3), 6)) ?>
                        <p class="mt-1.5 text-xs text-gray-500">
                            You can select more than one category.
                        </p>
                    <?php endif ?>
                </div>

                <div class="flex items-center gap-3">
                    <button
                        type="submit"
                        class="cursor-pointer rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
                    >
                        Save Changes
                    </button>

                    <a
                        href="<?= Html::encode($urlGenerator->generate('admin/product/index')) ?>"
                        class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                    >
                        Cancel
                    </a>
                </div>
            </div>

            <?= $htmlForm->close() ?>

            <?php if (!empty($images)): ?>
                <div class="mt-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <?php foreach ($images as $image): ?>

                            <?php
                            $deleteImageForm = Html::form()
                                ->post(
                                    $urlGenerator->generate(
                                        'admin/product/delete-image',
                                        [
                                            'productId' => $product->getId(),
                                            'id' => $image->getId(),
                                        ]
                                    )
                                )
                                ->csrf($csrf);
                            ?>

                            <div class="relative rounded-lg border border-gray-200 bg-white p-2">

                                <img
                                    src="<?= '/' . ltrim($image->getPath(), '/') ?>"
                                    class="h-32 w-full rounded-lg object-cover"
                                    alt="product image"
                                >


                                <?= $deleteImageForm->open() ?>

                                <button
                                    type="submit"
                                    onclick="return confirm('Delete this image?')"
                                    class="absolute right-2 top-2 rounded-full bg-red-600 px-3 py-1 text-sm text-white hover:bg-red-700"
                                >
                                    ×
                                </button>

                                <?= $deleteImageForm->close() ?>

                            </div>

                        <?php endforeach ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
