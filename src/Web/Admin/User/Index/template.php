<?php

declare(strict_types=1);

use App\User\User;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\View\WebView;
use Yiisoft\Yii\View\Renderer\Csrf;

/** @var User[] $users */
/** @var array<int, string[]> $rolesByUserId */
/** @var bool $canCreate */
/** @var bool $canEdit */
/** @var bool $canDelete */
/** @var bool $canManageRoles */
/** @var WebView $this */
/** @var UrlGeneratorInterface $urlGenerator */
/** @var Csrf $csrf */

$this->setTitle('Users');

$buttonClass = 'inline-flex cursor-pointer items-center rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800';
?>

<div class="px-4 py-12">
    <div class="mx-auto w-full max-w-6xl">
        <div class="mb-8 flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Users</h1>
                <p class="mt-2 text-sm text-gray-500">Manage users and their assigned roles</p>
            </div>

            <?php if ($canCreate): ?>
                <a href="<?= Html::encode($urlGenerator->generate('admin/user/create')) ?>" class="<?= $buttonClass ?>">
                    Create User
                </a>
            <?php endif; ?>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <?php if ($users === []): ?>
                <div class="px-6 py-12 text-center">
                    <h2 class="text-sm font-medium text-gray-900">No users found</h2>
                    <p class="mt-1 text-sm text-gray-500">Create your first user to get started.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Roles</th>
                            <?php if ($canManageRoles || $canEdit || $canDelete): ?>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                            <?php endif; ?>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                        <?php foreach ($users as $user): ?>
                            <?php $roleTitles = $rolesByUserId[(int) $user->getId()] ?? []; ?>
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    <?= Html::encode($user->getEmail()) ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php if ($roleTitles === []): ?>
                                        <span class="text-gray-400">No roles</span>
                                    <?php else: ?>
                                        <div class="flex flex-wrap gap-2">
                                            <?php foreach ($roleTitles as $roleTitle): ?>
                                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                                                    <?= Html::encode($roleTitle) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <?php if ($canManageRoles || $canEdit || $canDelete): ?>
                                    <td class="px-6 py-4 text-right text-sm">
                                        <div class="inline-flex items-center gap-3">
                                            <?php if ($canManageRoles): ?>
                                                <a
                                                    href="<?= Html::encode($urlGenerator->generate('admin/user/roles', ['id' => $user->getId()])) ?>"
                                                    class="font-medium text-blue-600 hover:underline"
                                                >
                                                    Roles
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($canEdit): ?>
                                                <a href="<?= Html::encode($urlGenerator->generate('admin/user/edit', ['id' => $user->getId()])) ?>" class="font-medium text-gray-900 hover:underline">
                                                    Edit
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($canDelete): ?>
                                                <?php $deleteForm = Html::form()
                                                    ->post($urlGenerator->generate('admin/user/delete', ['id' => $user->getId()]))
                                                    ->csrf($csrf); ?>

                                                <?= $deleteForm->open() ?>
                                                <button type="submit" class="border-0 bg-transparent p-0 font-medium text-red-600 hover:underline" onclick="return confirm('Delete this user?')">
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
