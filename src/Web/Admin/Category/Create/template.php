<?php use Yiisoft\Html\Html; use Yiisoft\Router\UrlGeneratorInterface; ?>
<h1>Create Category</h1>
<form method="post">
<label>Title</label>
<input name="title" value="<?=Html::encode($form->getTitle() ?? '')?>">
<button type="submit">Save</button>
</form>
