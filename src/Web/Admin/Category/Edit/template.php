<?php use Yiisoft\Html\Html; ?>
<h1>Edit Category</h1>
<form method="post">
<label>Title</label>
<input name="title" value="<?=Html::encode($form->getTitle() ?? '')?>">
<button type="submit">Save</button>
</form>
