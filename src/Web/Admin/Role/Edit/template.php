<?php

declare(strict_types=1);

use App\Permission\Permission;
use App\Role\Role;
use App\Role\UpdateRoleForm;
use Yiisoft\FormModel\Field;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\Renderer\Csrf;

/** @var UpdateRoleForm $form */
/** @var Role $role */
/** @var array $selected */
/** @var Permission $permissions */
/** @var UrlGeneratorInterface $urlGenerator */
/** @var Csrf $csrf */

$this->setTitle('Edit Role');
$htmlForm = Html::form()
    ->post($urlGenerator->generate('admin/role/edit', ['id' => $role->getId()]))
    ->csrf($csrf);
$inputClass = 'block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 outline-none transition focus:border-gray-900 focus:ring-1 focus:ring-gray-900';
$labelClass = 'block text-sm font-medium text-gray-700 mb-2';
$errorClass = 'mt-1.5 text-sm text-red-600';

?>
<div class="px-4 py-12">
    <div class="mx-auto w-full max-w-2xl">
        <div class="mb-8"><h1 class="text-2xl font-semibold tracking-tight text-gray-900">Edit Role</h1>
            <p class="mt-2 text-sm text-gray-500">Update role information</p></div>
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
            <?= $htmlForm->open() ?>
            <div class="space-y-5">
                <?= Field::text($form, 'title')->label('Title')
                    ->labelClass($labelClass)
                    ->inputClass($inputClass)
                    ->errorClass($errorClass) ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <?php foreach ($permissions as $permission): ?>
                            <label class="flex items-center gap-2 rounded-lg border border-gray-200 p-3">
                                <input
                                    type="checkbox" name="permissions[]"
                                    value="<?= $permission->getId() ?>" <?= in_array($permission->getId(), $selected, true) ? 'checked' : '' ?>
                                >
                                <?= Html::encode($permission->getTitle()) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="cursor-pointer rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white">
                        Save Role
                    </button>
                    <a href="<?= Html::encode($urlGenerator->generate('admin/role/index')) ?>"
                       class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700">Cancel</a>
                </div>
            </div>

            <?= $htmlForm->close() ?>

        </div>
    </div>
</div>
