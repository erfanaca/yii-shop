<?php

declare(strict_types=1);

use Yiisoft\FormModel\Field;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\View\WebView;
use Yiisoft\Yii\View\Renderer\Csrf;
use App\Category\CategoryForm;

/**
 * @var CategoryForm $form
 * @var WebView $this
 * @var UrlGeneratorInterface $urlGenerator
 * @var Csrf $csrf
 */

$this->setTitle('Create Category');

$htmlForm = Html::form()
    ->post($urlGenerator->generate('admin/category/create'))
    ->csrf($csrf);

$inputClass = 'block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 outline-none transition focus:border-gray-900 focus:ring-1 focus:ring-gray-900';
$labelClass = 'block text-sm font-medium text-gray-700 mb-2';
$errorClass = 'mt-1.5 text-sm text-red-600';
?>

<div class="px-4 py-12">
    <div class="mx-auto w-full max-w-2xl">
        <div class="mb-8">
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Create Category</h1>
            <p class="mt-2 text-sm text-gray-500">Add a new category to your catalog</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
            <?= $htmlForm->open() ?>

            <?= Field::text($form, 'title')
                ->label('Title')
                ->labelClass($labelClass)
                ->inputClass($inputClass)
                ->errorClass($errorClass)
                ->placeholder('Category title') ?>

            <div class="mt-6 flex items-center gap-3">
                <button type="submit" class="cursor-pointer rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800">
                    Create Category
                </button>

                <a href="<?= Html::encode($urlGenerator->generate('admin/category/index')) ?>" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
            </div>

            <?= $htmlForm->close() ?>
        </div>
    </div>
</div>
