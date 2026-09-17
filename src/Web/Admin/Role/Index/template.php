<?php
declare(strict_types=1);

use Yiisoft\Html\Html;

$this->setTitle('Roles');
$buttonClass = 'inline-flex items-center rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-gray-800';
?>
<div class="px-4 py-12">
    <div class="mx-auto w-full max-w-6xl">
        <div class="mb-8 flex items-center justify-between">
            <div><h1 class="text-2xl font-semibold text-gray-900">Roles</h1>
                <p class="mt-2 text-sm text-gray-500">Manage roles and permissions</p></div>
            <a class="<?= $buttonClass ?>" href="<?= Html::encode($urlGenerator->generate('admin/role/create')) ?>">Create
                Role</a></div>
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Title</th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase text-gray-500">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                <?php foreach ($roles as $role): ?>
                    <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= Html::encode($role->getTitle()) ?></td>
                    <td class="px-6 py-4 text-right"><a class="font-medium hover:underline"
                                                        href="<?= Html::encode($urlGenerator->generate('admin/role/edit', ['id' => $role->getId()])) ?>">Edit</a>
                    </td>
                    </tr><?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
