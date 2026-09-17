<?php declare(strict_types=1); use Yiisoft\Html\Html; $this->setTitle('Edit Role'); ?>
<div class="px-4 py-12"><div class="mx-auto max-w-3xl">
<div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
<h1 class="mb-6 text-2xl font-semibold text-gray-900">Edit Role</h1>
<form method="post" class="space-y-6">
<div><label class="mb-2 block text-sm font-medium">Title</label><input name="title" value="<?=Html::encode($role->getTitle())?>" class="w-full rounded-lg border border-gray-300 p-3"></div>
<div><h3 class="mb-3 text-lg font-medium">Permissions</h3><div class="grid gap-3 sm:grid-cols-2"><?php foreach($permissions as $p):?><label class="flex items-center gap-2 rounded-lg border p-3"><input type="checkbox" name="permissions[]" value="<?=$p->getId()?>" <?=in_array($p->getId(),$selected,true)?'checked':''?>> <?=Html::encode($p->getTitle())?></label><?php endforeach;?></div></div>
<button class="rounded-lg bg-gray-900 px-5 py-2.5 text-white">Save</button></form></div></div></div>