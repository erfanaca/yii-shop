<?php

declare(strict_types=1);

use App\Permission\Permission;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\View\WebView;
use Yiisoft\Yii\View\Renderer\Csrf;

/** @var Permission[] $permissions */
/** @var WebView $this */
/** @var UrlGeneratorInterface $urlGenerator */
/** @var Csrf $csrf */

$this->setTitle('Permissions');

$buttonClass = 'inline-flex cursor-pointer items-center rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800';
?>

<div class="px-4 py-12">
    <div class="mx-auto w-full max-w-6xl">
        <div class="mb-8 flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Permissions</h1>
                <p class="mt-2 text-sm text-gray-500">Manage your product permissions</p>
            </div>

            <a href="<?= Html::encode($urlGenerator->generate('admin/permission/create')) ?>" class="<?= $buttonClass ?>">
                Create Permission
            </a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <?php if ($permissions === []): ?>
                <div class="px-6 py-12 text-center">
                    <h2 class="text-sm font-medium text-gray-900">No permission found</h2>
                    <p class="mt-1 text-sm text-gray-500">Create your first permission to get started.</p>
                </div>
            <?php else: ?>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Title</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                    <?php foreach ($permissions as $permission): ?>
                        <tr class="transition hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                <?= Html::encode($permission->getTitle()) ?>
                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <div class="inline-flex items-center gap-3">
                                    <a href="<?= Html::encode($urlGenerator->generate('admin/permission/edit', ['id' => $permission->getId()])) ?>" class="font-medium text-gray-900 hover:underline">
                                        Edit
                                    </a>

                                    <?php $deleteForm = Html::form()
                                        ->post($urlGenerator->generate('admin/permission/delete', ['id' => $permission->getId()]))
                                        ->csrf($csrf); ?>

                                    <?= $deleteForm->open() ?>
                                    <button type="submit" class="border-0 bg-transparent p-0 font-medium text-red-600 hover:underline" onclick="return confirm('Delete this permission?')">
                                        Delete
                                    </button>
                                    <?= $deleteForm->close() ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            <?php endif ?>
        </div>
    </div>
</div>
