<?php

declare(strict_types=1);

use App\User\User;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\View\WebView;
use Yiisoft\Yii\View\Renderer\Csrf;

/**
 * @var WebView $this
 * @var User|null $user
 * @var UrlGeneratorInterface $urlGenerator
 * @var Csrf $csrf
 */

$this->setTitle('Home');
?>

<div class="flex min-h-[75vh] items-center justify-center px-4 py-12">

    <div class="w-full max-w-md">

        <div class="mb-8 text-center">

            <h1 class="text-2xl font-semibold tracking-tight text-gray-900">
                Simple Shop
            </h1>

            <p class="mt-2 text-sm text-gray-500">
                A simple online shop built with Yii3
            </p>

        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">

            <?php if ($user !== null): ?>

                <div class="text-center">

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">
                            You are logged in as
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            <?= Html::encode($user->getEmail()) ?>
                        </p>
                    </div>

                    <?php
                    $logoutForm = Html::form()
                        ->post($urlGenerator->generate('auth/logout'))
                        ->csrf($csrf);
                    ?>

                    <?= $logoutForm->open() ?>

                    <button
                        type="submit"
                        class="w-full cursor-pointer rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
                    >
                        Logout
                    </button>

                    <?= $logoutForm->close() ?>

                </div>

            <?php else: ?>

                <div class="text-center">

                    <h2 class="text-lg font-semibold text-gray-900">
                        Welcome
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-gray-500">
                        Sign in to your account or create a new account to get started.
                    </p>

                </div>

                <div class="mt-6 space-y-3">

                    <a
                        href="<?= Html::encode($urlGenerator->generate('auth/login')) ?>"
                        class="block w-full rounded-lg bg-gray-900 px-4 py-2.5 text-center text-sm font-medium text-white transition hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
                    >
                        Sign in
                    </a>

                    <a
                        href="<?= Html::encode($urlGenerator->generate('auth/register')) ?>"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-center text-sm font-medium text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
                    >
                        Create account
                    </a>

                </div>

            <?php endif ?>

        </div>

        <p class="mt-6 text-center text-xs text-gray-400">
            Yii3 Simple Shop
        </p>

    </div>

</div>