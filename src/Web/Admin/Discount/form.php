<?php

declare(strict_types=1);

use App\Discount\DiscountCodeForm;
use App\Discount\DiscountScope;
use App\Discount\DiscountType;
use App\Product\Product;
use App\User\User;
use Yiisoft\FormModel\Field;
use Yiisoft\Html\Html;
use Yiisoft\Yii\View\Renderer\Csrf;

/**
 * @var DiscountCodeForm $form
 * @var User[] $users
 * @var Product[] $products
 * @var string $formAction
 * @var string $submitLabel
 * @var string|null $errorMessage
 * @var Csrf $csrf
 */

$htmlForm = Html::form()->post($formAction)->csrf($csrf);

$inputClass = implode(' ', [
    'block', 'w-full', 'rounded-lg', 'border', 'border-gray-300', 'bg-white',
    'px-3', 'py-2.5', 'text-sm', 'text-gray-900', 'outline-none', 'transition',
    'focus:border-gray-900', 'focus:ring-1', 'focus:ring-gray-900',
]);
$labelClass = 'mb-2 block text-sm font-medium text-gray-700';
$errorClass = 'mt-1.5 text-sm text-red-600';

$userOptions = [];
foreach ($users as $user) {
    $userOptions[(string) $user->getId()] = $user->getEmail();
}

$productOptions = [];
foreach ($products as $product) {
    $productOptions[(string) $product->getId()] = $product->getTitle();
}
?>

<?php if ($errorMessage !== null): ?>
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        <?= Html::encode($errorMessage) ?>
    </div>
<?php endif; ?>

<?= $htmlForm->open() ?>
<div class="space-y-7">
    <section class="space-y-5">
        <div>
            <h2 class="text-sm font-semibold text-gray-900">Discount</h2>
            <p class="mt-1 text-xs text-gray-500">Define the code and how its value is calculated.</p>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <?= Field::text($form, 'code')
                    ->label('Code')
                    ->labelClass($labelClass)
                    ->inputClass($inputClass)
                    ->errorClass($errorClass)
                    ->placeholder('SUMMER20')
                    ->addInputAttributes(['autocomplete' => 'off']) ?>
                <p class="mt-1.5 text-xs text-gray-500">Letters, numbers, dash and underscore are allowed.</p>
            </div>

            <div>
                <?= Field::select($form, 'type')
                    ->label('Discount Type')
                    ->labelClass($labelClass)
                    ->inputClass($inputClass)
                    ->errorClass($errorClass)
                    ->optionsData([
                        DiscountType::Percentage->value => 'Percentage',
                        DiscountType::Fixed->value => 'Fixed amount',
                    ])
                    ->addInputAttributes(['id' => 'discount-type']) ?>
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <?= Field::text($form, 'value')
                    ->label('Value')
                    ->labelClass($labelClass)
                    ->inputClass($inputClass)
                    ->errorClass($errorClass)
                    ->placeholder('20.00') ?>
                <p id="discount-value-help" class="mt-1.5 text-xs text-gray-500">For percentage discounts enter a value from 0 to 100.</p>
            </div>

            <div id="percentage-limit-field">
                <?= Field::text($form, 'maxDiscountAmount')
                    ->label('Maximum Discount Amount')
                    ->labelClass($labelClass)
                    ->inputClass($inputClass)
                    ->errorClass($errorClass)
                    ->placeholder('500000.00') ?>
                <p class="mt-1.5 text-xs text-gray-500">Required for percentage discounts.</p>
            </div>

            <div id="fixed-minimum-field" class="hidden">
                <?= Field::text($form, 'minimumOrderAmount')
                    ->label('Minimum Order Amount')
                    ->labelClass($labelClass)
                    ->inputClass($inputClass)
                    ->errorClass($errorClass)
                    ->placeholder('2000000.00') ?>
                <p class="mt-1.5 text-xs text-gray-500">Required for fixed discounts.</p>
            </div>
        </div>
    </section>

    <div class="border-t border-gray-200"></div>

    <section class="space-y-5">
        <div>
            <h2 class="text-sm font-semibold text-gray-900">Eligible Users</h2>
            <p class="mt-1 text-xs text-gray-500">Choose whether everyone or only selected users can use this code.</p>
        </div>

        <div>
            <?= Field::select($form, 'userScope')
                ->label('User Scope')
                ->labelClass($labelClass)
                ->inputClass($inputClass)
                ->errorClass($errorClass)
                ->optionsData([
                    DiscountScope::All->value => 'All users',
                    DiscountScope::Specific->value => 'Specific users',
                ])
                ->addInputAttributes(['id' => 'discount-user-scope']) ?>
        </div>

        <div id="specific-users-field" class="hidden">
            <?php if ($userOptions === []): ?>
                <label class="<?= $labelClass ?>">Users</label>
                <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-3 py-3 text-sm text-gray-500">
                    No users are available.
                </div>
            <?php else: ?>
                <?= Field::select($form, 'userIds')
                    ->label('Users')
                    ->labelClass($labelClass)
                    ->inputClass($inputClass)
                    ->errorClass($errorClass)
                    ->optionsData($userOptions)
                    ->multiple()
                    ->size(min(max(count($userOptions), 4), 8)) ?>
                <p class="mt-1.5 text-xs text-gray-500">Hold Ctrl/Cmd to select multiple users.</p>
            <?php endif; ?>
        </div>
    </section>

    <div class="border-t border-gray-200"></div>

    <section class="space-y-5">
        <div>
            <h2 class="text-sm font-semibold text-gray-900">Eligible Products</h2>
            <p class="mt-1 text-xs text-gray-500">Choose whether this code applies to every product or selected products only.</p>
        </div>

        <div>
            <?= Field::select($form, 'productScope')
                ->label('Product Scope')
                ->labelClass($labelClass)
                ->inputClass($inputClass)
                ->errorClass($errorClass)
                ->optionsData([
                    DiscountScope::All->value => 'All products',
                    DiscountScope::Specific->value => 'Specific products',
                ])
                ->addInputAttributes(['id' => 'discount-product-scope']) ?>
        </div>

        <div id="specific-products-field" class="hidden">
            <?php if ($productOptions === []): ?>
                <label class="<?= $labelClass ?>">Products</label>
                <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-3 py-3 text-sm text-gray-500">
                    No products are available.
                </div>
            <?php else: ?>
                <?= Field::select($form, 'productIds')
                    ->label('Products')
                    ->labelClass($labelClass)
                    ->inputClass($inputClass)
                    ->errorClass($errorClass)
                    ->optionsData($productOptions)
                    ->multiple()
                    ->size(min(max(count($productOptions), 4), 8)) ?>
                <p class="mt-1.5 text-xs text-gray-500">Hold Ctrl/Cmd to select multiple products.</p>
            <?php endif; ?>
        </div>
    </section>

    <div class="flex items-center gap-3 pt-1">
        <button type="submit" class="cursor-pointer rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
            <?= Html::encode($submitLabel) ?>
        </button>
        <a href="<?= Html::encode($urlGenerator->generate('admin/discount/index')) ?>" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
            Cancel
        </a>
    </div>
</div>
<?= $htmlForm->close() ?>

<script>
    (() => {
        const type = document.getElementById('discount-type');
        const userScope = document.getElementById('discount-user-scope');
        const productScope = document.getElementById('discount-product-scope');
        const percentageLimit = document.getElementById('percentage-limit-field');
        const fixedMinimum = document.getElementById('fixed-minimum-field');
        const specificUsers = document.getElementById('specific-users-field');
        const specificProducts = document.getElementById('specific-products-field');
        const valueHelp = document.getElementById('discount-value-help');

        const refreshType = () => {
            const fixed = type?.value === 'FIXED';
            percentageLimit?.classList.toggle('hidden', fixed);
            fixedMinimum?.classList.toggle('hidden', !fixed);
            if (valueHelp) {
                valueHelp.textContent = fixed
                    ? 'Enter the fixed amount deducted from the eligible order.'
                    : 'For percentage discounts enter a value from 0 to 100.';
            }
        };

        const refreshScopes = () => {
            specificUsers?.classList.toggle('hidden', userScope?.value !== 'SPECIFIC');
            specificProducts?.classList.toggle('hidden', productScope?.value !== 'SPECIFIC');
        };

        type?.addEventListener('change', refreshType);
        userScope?.addEventListener('change', refreshScopes);
        productScope?.addEventListener('change', refreshScopes);

        refreshType();
        refreshScopes();
    })();
</script>
