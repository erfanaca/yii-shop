<?php

declare(strict_types=1);

use App\Auth\LoginForm;
use Yiisoft\FormModel\Field;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\View\WebView;
use Yiisoft\Yii\View\Renderer\Csrf;

/**
 * @var WebView $this
 * @var LoginForm $form
 * @var string|null $loginError
 * @var UrlGeneratorInterface $urlGenerator
 * @var Csrf $csrf
 */

$this->setTitle('Login');

$htmlForm = Html::form()
    ->post($urlGenerator->generate('auth/login'))
    ->csrf($csrf);

$inputClass = implode(' ', [
    'block',
    'w-full',
    'rounded-lg',
    'border',
    'border-gray-300',
    'bg-white',
    'px-3',
    'py-2.5',
    'text-sm',
    'text-gray-900',
    'outline-none',
    'transition',
    'placeholder:text-gray-400',
    'focus:border-gray-900',
    'focus:ring-1',
    'focus:ring-gray-900',
]);

$labelClass = 'block text-sm font-medium text-gray-700 mb-2';
$errorClass = 'mt-1.5 text-sm text-red-600';
?>

<div class="flex min-h-[75vh] items-center justify-center px-4 py-12">

    <div class="w-full max-w-md">

        <div class="mb-8 text-center">
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900">
                Welcome back
            </h1>

            <p class="mt-2 text-sm text-gray-500">
                Sign in to your account
            </p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">

            <?php if ($loginError !== null): ?>
                <div class="mb-5 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                    <?= Html::encode($loginError) ?>
                </div>
            <?php endif ?>

            <?= $htmlForm->open() ?>

            <div class="space-y-5">

                <div>
                    <?= Field::email($form, 'email')
                        ->label('Email')
                        ->labelClass($labelClass)
                        ->inputClass($inputClass)
                        ->errorClass($errorClass)
                        ->placeholder('you@example.com')
                        ->addInputAttributes([
                            'autocomplete' => 'email',
                        ])
                    ?>
                </div>

                <div>
                    <?= Field::password($form, 'password')
                        ->label('Password')
                        ->labelClass($labelClass)
                        ->inputClass($inputClass)
                        ->errorClass($errorClass)
                        ->placeholder('Enter your password')
                        ->addInputAttributes([
                            'autocomplete' => 'current-password',
                        ])
                    ?>
                </div>

                <button
                    type="submit"
                    class="w-full cursor-pointer rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
                >
                    Sign in
                </button>

            </div>

            <?= $htmlForm->close() ?>

        </div>

        <p class="mt-6 text-center text-sm text-gray-500">
            Don't have an account?

            <a
                href="<?= Html::encode($urlGenerator->generate('auth/register')) ?>"
                class="font-medium text-gray-900 hover:underline"
            >
                Create account
            </a>
        </p>

    </div>

</div>