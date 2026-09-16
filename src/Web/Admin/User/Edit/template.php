<?php

declare(strict_types=1);

use App\User\User;
use App\Admin\User\UserForm;
use Yiisoft\FormModel\Field;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\View\WebView;
use Yiisoft\Yii\View\Renderer\Csrf;

/** @var UserForm $form */
/** @var User $user */
/** @var WebView $this */
/** @var UrlGeneratorInterface $urlGenerator */
/** @var Csrf $csrf */

$this->setTitle('Edit Category');

$htmlForm = Html::form()
    ->post($urlGenerator->generate('admin/user/edit', ['id' => $user->getId()]))
    ->csrf($csrf);

$inputClass = 'block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 outline-none transition focus:border-gray-900 focus:ring-1 focus:ring-gray-900';
$labelClass = 'block text-sm font-medium text-gray-700 mb-2';
$errorClass = 'mt-1.5 text-sm text-red-600';
?>

<div class="px-4 py-12">
    <div class="mx-auto w-full max-w-2xl">
        <div class="mb-8">
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Edit User</h1>
            <p class="mt-2 text-sm text-gray-500">Update user information</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
            <?= $htmlForm->open() ?>

            <?= Field::email($form, 'email')
                ->label('Email')
                ->labelClass($labelClass)
                ->inputClass($inputClass)
                ->errorClass($errorClass)
                ->placeholder('you@example.com')
                ->addInputAttributes([
                    'autocomplete' => 'email',
                ])
            ?>

            <?= Field::password($form, 'password')
                ->label('Password')
                ->labelClass($labelClass)
                ->inputClass($inputClass)
                ->errorClass($errorClass)
                ->placeholder('At least 8 characters')
                ->addInputAttributes([
                    'autocomplete' => 'new-password',
                ])
            ?>

            <div class="mt-6 flex items-center gap-3">
                <button type="submit" class="cursor-pointer rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800">
                    Save Changes
                </button>

                <a href="<?= Html::encode($urlGenerator->generate('admin/user/index')) ?>" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
            </div>

            <?= $htmlForm->close() ?>
        </div>
    </div>
</div>
