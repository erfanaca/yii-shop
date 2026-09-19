<?php

declare(strict_types=1);

use App\Discount\DiscountCodeForm;
use App\Product\Product;
use App\User\User;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\View\WebView;
use Yiisoft\Yii\View\Renderer\Csrf;

/** @var DiscountCodeForm $form */
/** @var User[] $users */
/** @var Product[] $products */
/** @var string|null $errorMessage */
/** @var WebView $this */
/** @var UrlGeneratorInterface $urlGenerator */
/** @var Csrf $csrf */

$this->setTitle('Create Discount Code');
$formAction = $urlGenerator->generate('admin/discount/create');
$submitLabel = 'Create Discount Code';
?>

<div class="px-4 py-12">
    <div class="mx-auto w-full max-w-3xl">
        <div class="mb-8">
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Create Discount Code</h1>
            <p class="mt-2 text-sm text-gray-500">Define value, eligible users and eligible products.</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
            <?php require dirname(__DIR__) . '/form.php'; ?>
        </div>
    </div>
</div>
