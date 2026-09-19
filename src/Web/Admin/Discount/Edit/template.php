<?php

declare(strict_types=1);

use App\Discount\DiscountCode;
use App\Discount\DiscountCodeForm;
use App\Product\Product;
use App\User\User;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\View\WebView;
use Yiisoft\Yii\View\Renderer\Csrf;

/** @var DiscountCodeForm $form */
/** @var DiscountCode $discountCode */
/** @var User[] $users */
/** @var Product[] $products */
/** @var string|null $errorMessage */
/** @var WebView $this */
/** @var UrlGeneratorInterface $urlGenerator */
/** @var Csrf $csrf */

$this->setTitle('Edit Discount Code');
$formAction = $urlGenerator->generate('admin/discount/edit', ['id' => $discountCode->getId()]);
$submitLabel = 'Save Changes';
?>

<div class="px-4 py-12">
    <div class="mx-auto w-full max-w-3xl">
        <div class="mb-8">
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Edit Discount Code</h1>
            <p class="mt-2 text-sm text-gray-500">Update <?= \Yiisoft\Html\Html::encode($discountCode->getCode()) ?> and its eligibility rules.</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
            <?php require dirname(__DIR__) . '/form.php'; ?>
        </div>
    </div>
</div>
