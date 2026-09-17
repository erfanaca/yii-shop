<?php
declare(strict_types=1);
use Yiisoft\Html\Html;
$this->setTitle('Permissions');
?>
<div class="px-4 py-12"><div class="mx-auto w-full max-w-6xl">
<div class="mb-8 flex items-center justify-between"><div><h1 class="text-2xl font-semibold text-gray-900">Permissions</h1><p class="mt-2 text-sm text-gray-500">Manage available permissions</p></div>
<a class="inline-flex rounded-lg bg-gray-900 px-4 py-2.5 text-white" href="<?=$urlGenerator->generate('admin/permission/create')?>">Create Permission</a></div>
<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm"><table class="min-w-full divide-y divide-gray-200">
<thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs text-gray-500">Title</th><th class="px-6 py-3 text-right text-xs text-gray-500">Actions</th></tr></thead>
<tbody class="divide-y divide-gray-200"><?php foreach($permissions as $p):?><tr class="hover:bg-gray-50"><td class="px-6 py-4"><?=Html::encode($p->getTitle())?></td><td class="px-6 py-4 text-right"><a href="<?=$urlGenerator->generate('admin/permission/edit',['id'=>$p->getId()])?>" class="font-medium hover:underline">Edit</a></td></tr><?php endforeach;?></tbody></table></div>
</div></div>