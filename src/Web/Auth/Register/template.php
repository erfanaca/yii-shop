<?php

declare(strict_types=1);

use App\Auth\RegisterForm;
use Yiisoft\FormModel\Field;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\View\WebView;
use Yiisoft\Yii\View\Renderer\Csrf;

/**
 * @var WebView $this
 * @var RegisterForm $form
 * @var bool $success
 * @var string|null $registrationError
 * @var UrlGeneratorInterface $urlGenerator
 * @var Csrf $csrf
 */

$this->setTitle('Register');

$htmlForm = Html::form()
    ->post($urlGenerator->generate('auth/register'))
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
                Create an account
            </h1>

            <p class="mt-2 text-sm text-gray-500">
                Create your account to get started
            </p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">

            <?php if ($success): ?>

                <div class="rounded-lg bg-green-50 px-4 py-4 text-sm text-green-700">
                    Your account was created successfully.
                </div>

                <a
                    href="<?= Html::encode($urlGenerator->generate('auth/login')) ?>"
                    class="mt-5 block w-full rounded-lg bg-gray-900 px-4 py-2.5 text-center text-sm font-medium text-white transition hover:bg-gray-800"
                >
                    Sign in
                </a>

            <?php else: ?>

                <?php if ($registrationError !== null): ?>
                    <div class="mb-5 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                        <?= Html::encode($registrationError) ?>
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
                            ->placeholder('At least 8 characters')
                            ->addInputAttributes([
                                'autocomplete' => 'new-password',
                            ])
                        ?>
                    </div>

                    <button
                        type="submit"
                        class="w-full cursor-pointer rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
                    >
                        Create account
                    </button>

                </div>

                <?= $htmlForm->close() ?>

            <?php endif ?>

        </div>

        <?php if (!$success): ?>
            <p class="mt-6 text-center text-sm text-gray-500">
                Already have an account?

                <a
                    href="<?= Html::encode($urlGenerator->generate('auth/login')) ?>"
                    class="font-medium text-gray-900 hover:underline"
                >
                    Sign in
                </a>
            </p>
        <?php endif ?>

    </div>

</div>