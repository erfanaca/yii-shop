<?php

declare(strict_types=1);

use Yiisoft\Html\Html;

/**
 * @var string $content
 * @var Yiisoft\Router\UrlGeneratorInterface $urlGenerator
 * @var \Yiisoft\Router\CurrentRoute $currentRoute
 * @var \Yiisoft\User\CurrentUser $currentUser
 */

$this->beginPage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->getTitle() ?? 'Shop') ?></title>
    <?php $this->head() ?>
</head>
<body class="bg-gray-50 text-gray-900">
<?php $this->beginBody(); ?>

<header class="border-b bg-white">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
        <a href="<?= $urlGenerator->generate('home') ?>" class="text-xl font-bold">
            Simple Shop
        </a>

        <nav class="flex items-center gap-2">
            <a class="rounded-lg px-3 py-2 hover:bg-gray-100" href="<?= $urlGenerator->generate('home') ?>">Home</a>
            <a class="rounded-lg px-3 py-2 hover:bg-gray-100" href="<?= $urlGenerator->generate('product/index') ?>">Products</a>
            <a class="rounded-lg px-3 py-2 hover:bg-gray-100" href="#">Cart</a>

            <?php if ($currentUser->isGuest()): ?>
                <a class="rounded-lg bg-gray-900 px-3 py-2 text-white"
                   href="<?= $urlGenerator->generate('auth/login') ?>">
                    Login
                </a>
            <?php else: ?>
                <form method="post" action="<?= $urlGenerator->generate('auth/logout') ?>">
                    <button class="rounded-lg bg-gray-900 px-3 py-2 text-white">
                        Logout
                    </button>
                </form>
            <?php endif; ?>
        </nav>
    </div>
</header>

<?= $content ?>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
