<?php

declare(strict_types=1);

use App\Role\Role;
use App\User\User;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\View\WebView;
use Yiisoft\Yii\View\Renderer\Csrf;

/** @var User $user */
/** @var Role[] $roles */
/** @var int[] $selectedRoleIds */
/** @var WebView $this */
/** @var UrlGeneratorInterface $urlGenerator */
/** @var Csrf $csrf */

$this->setTitle('Manage User Roles');

$htmlForm = Html::form()
    ->post($urlGenerator->generate('admin/user/roles', ['id' => $user->getId()]))
    ->csrf($csrf);
?>

<div class="px-4 py-12">
    <div class="mx-auto w-full max-w-3xl">
        <div class="mb-8">
            <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">
                <a href="<?= Html::encode($urlGenerator->generate('admin/user/index')) ?>" class="transition hover:text-gray-900">
                    Users
                </a>
                <span>/</span>
                <span>Roles</span>
            </div>
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Manage User Roles</h1>
            <p class="mt-2 text-sm text-gray-500">
                Select the roles assigned to
                <span class="font-medium text-gray-700"><?= Html::encode($user->getEmail()) ?></span>.
            </p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
            <?= $htmlForm->open() ?>

            <?php if ($roles === []): ?>
                <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 px-5 py-8 text-center">
                    <p class="text-sm font-medium text-gray-900">No roles are available</p>
                    <p class="mt-1 text-sm text-gray-500">Create a role first, then return here to assign it to this user.</p>
                </div>
            <?php else: ?>
                <div class="grid gap-3 sm:grid-cols-2">
                    <?php foreach ($roles as $role): ?>
                        <?php $checked = in_array($role->getId(), $selectedRoleIds, true); ?>
                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 p-4 transition hover:border-gray-300 hover:bg-gray-50">
                            <input
                                type="checkbox"
                                name="roles[]"
                                value="<?= $role->getId() ?>"
                                class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900"
                                <?= $checked ? 'checked' : '' ?>
                            >
                            <span class="text-sm font-medium text-gray-900">
                                <?= Html::encode($role->getTitle()) ?>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="mt-8 flex items-center gap-3 border-t border-gray-100 pt-6">
                <button
                    type="submit"
                    class="cursor-pointer rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800"
                >
                    Save Roles
                </button>

                <a
                    href="<?= Html::encode($urlGenerator->generate('admin/user/index')) ?>"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    Cancel
                </a>
            </div>

            <?= $htmlForm->close() ?>
        </div>
    </div>
</div>
