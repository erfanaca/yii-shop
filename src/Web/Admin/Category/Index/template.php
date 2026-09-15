<?php
use App\Category\Category;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
/** @var Category[] $categories */
/** @var UrlGeneratorInterface $urlGenerator */
$this->setTitle('Categories');
?>
<div class="px-4 py-12">
<div class="mx-auto max-w-6xl">
<h1 class="text-2xl font-semibold">Categories</h1>
<a href="<?=Html::encode($urlGenerator->generate('admin/category/create'))?>">Create Category</a>
<table class="mt-6 min-w-full">
<tr><th>Title</th><th>Actions</th></tr>
<?php foreach($categories as $category): ?>
<tr>
<td><?=Html::encode($category->getTitle())?></td>
<td>
<a href="<?=Html::encode($urlGenerator->generate('admin/category/edit',['id'=>$category->getId()]))?>">Edit</a>
<form method="post" action="<?=Html::encode($urlGenerator->generate('admin/category/delete',['id'=>$category->getId()]))?>" style="display:inline">
<button type="submit">Delete</button>
</form>
</td>
</tr>
<?php endforeach;?>
</table>
</div></div>
