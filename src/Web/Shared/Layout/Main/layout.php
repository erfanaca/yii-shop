<?php

declare(strict_types=1);

use App\Web\Shared\Layout\Main\MainAsset;
use Yiisoft\Html\Html;
use Yiisoft\User\CurrentUser;

/**
 * @var \App\Shared\ApplicationParams $applicationParams
 * @var Yiisoft\Aliases\Aliases $aliases
 * @var Yiisoft\Assets\AssetManager $assetManager
 * @var string $content
 * @var string|null $csrf
 * @var Yiisoft\View\WebView $this
 * @var Yiisoft\Router\CurrentRoute $currentRoute
 * @var Yiisoft\Router\UrlGeneratorInterface $urlGenerator
 * @var CurrentUser $currentUser
 */

$assetManager->register(MainAsset::class);

$this->addCssFiles($assetManager->getCssFiles());
$this->addCssStrings($assetManager->getCssStrings());
$this->addJsFiles($assetManager->getJsFiles());
$this->addJsStrings($assetManager->getJsStrings());
$this->addJsVars($assetManager->getJsVars());

$currentPath = $currentRoute->getUri()?->getPath() ?? '';

$isAdmin = str_starts_with($currentPath, '/admin');

$isProductsActive = str_starts_with($currentPath, '/admin/products');
$isOrdersActive = str_starts_with($currentPath, '/admin/orders');
$isCategoriesActive = str_starts_with($currentPath, '/admin/categories');
$isUsersActive = str_starts_with($currentPath, '/admin/users');
$isRolesActive = str_starts_with($currentPath, '/admin/roles');
$isPermissionsActive = str_starts_with($currentPath, '/admin/permissions');

$sidebarItemClass = static function (bool $active): string {
    $base = implode(' ', [
        'flex',
        'items-center',
        'gap-3',
        'rounded-xl',
        'px-3',
        'py-2.5',
        'text-sm',
        'font-medium',
        'transition',
    ]);

    if ($active) {
        return $base . ' bg-gray-900 text-white shadow-sm';
    }

    return $base . ' text-gray-600 hover:bg-gray-100 hover:text-gray-900';
};

$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?= Html::encode($applicationParams->locale) ?>">

<head>
    <meta charset="<?= Html::encode($applicationParams->charset) ?>">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <link
        rel="icon"
        href="<?= $aliases->get('@baseUrl/favicon.svg') ?>"
        type="image/svg+xml">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <title>
        <?= Html::encode($this->getTitle()) ?>
    </title>

    <?php $this->head() ?>
</head>

<body class="<?= $isAdmin ? 'bg-gray-50' : '' ?>">

<?php $this->beginBody() ?>

<?php if ($isAdmin): ?>

    <div class="min-h-screen bg-gray-50">

        <aside
            class="fixed inset-y-0 left-0 z-40 hidden w-64 border-r border-gray-200 bg-white lg:flex lg:flex-col">

            <div
                class="flex h-16 items-center border-b border-gray-200 px-6">
                <a
                    href="<?= Html::encode(
                        $urlGenerator->generate('admin/product/index')
                    ) ?>"
                    class="flex items-center gap-3">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-gray-900 text-sm font-bold text-white
                        ">
                        S
                    </div>

                    <div>
                        <div class="text-sm font-semibold text-gray-900">
                            Shop Admin
                        </div>

                        <div class="text-xs text-gray-400">
                            Management Panel
                        </div>
                    </div>
                </a>
            </div>

            <div class="flex-1 overflow-y-auto px-4 py-6">

                <div
                    class=" mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400
                    ">
                    Management
                </div>


                <nav class="space-y-1">
                    <a
                        href="<?= Html::encode(
                            $urlGenerator->generate('admin/product/index')
                        ) ?>"
                        class="<?= $sidebarItemClass($isProductsActive) ?>">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.8"
                            stroke="currentColor"
                            class="h-5 w-5 shrink-0">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d=" M21 7.5 12 2.25 3 7.5 m18 0 -9 5.25 m9 -5.25 v9 l-9 5.25 m0 -9 L3 7.5 m9 5.25 v9 m-9 -14.25 v9 l9 5.25
                                "/>
                        </svg>

                        <span>Products</span>
                    </a>

                    <a
                        href="<?= Html::encode(
                            $urlGenerator->generate('admin/order/index')
                        ) ?>"
                        class="<?= $sidebarItemClass($isOrdersActive) ?>">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.8"
                            stroke="currentColor"
                            class="h-5 w-5 shrink-0">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 2.75h12a1.25 1.25 0 0 1 1.25 1.25v17l-3-1.75L13.5 21l-3-1.75L7.75 21 4.75 19.25V4A1.25 1.25 0 0 1 6 2.75Zm2.25 5h7.5m-7.5 4h7.5m-7.5 4h4.5"/>
                        </svg>

                        <span>Orders</span>
                    </a>

                    <a
                        href="<?= Html::encode(
                            $urlGenerator->generate('admin/category/index')
                        ) ?>"
                        class="<?= $sidebarItemClass($isCategoriesActive) ?>">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.8"
                            stroke="currentColor"
                            class="h-5 w-5 shrink-0">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d=" M3.75 6 A2.25 2.25 0 0 1 6 3.75 h2.25 A2.25 2.25 0 0 1 10.5 6 v2.25 a2.25 2.25 0 0 1 -2.25 2.25 H6 a2.25 2.25 0 0 1 -2.25 -2.25 V6 M13.5 6 a2.25 2.25 0 0 1 2.25 -2.25 H18 A2.25 2.25 0 0 1 20.25 6 v2.25 A2.25 2.25 0 0 1 18 10.5 h-2.25 a2.25 2.25 0 0 1 -2.25 -2.25 V6 M3.75 15.75 A2.25 2.25 0 0 1 6 13.5 h2.25 a2.25 2.25 0 0 1 2.25 2.25 V18 a2.25 2.25 0 0 1 -2.25 2.25 H6 A2.25 2.25 0 0 1 3.75 18 v-2.25 M13.5 15.75 a2.25 2.25 0 0 1 2.25 -2.25 H18 a2.25 2.25 0 0 1 2.25 2.25 V18 A2.25 2.25 0 0 1 18 20.25 h-2.25 A2.25 2.25 0 0 1 13.5 18 v-2.25 Z
                                "/>
                        </svg>

                        <span>Categories</span>
                    </a>

                    <a
                        href="<?= Html::encode(
                            $urlGenerator->generate('admin/user/index')
                        ) ?>"
                        class="<?= $sidebarItemClass($isUsersActive) ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="1.5"
                                  d="M18.5 20.5c-.234-2.931-2.658-5.252-5.692-5.448L11.999 15q-.431.012-.811.03C8.18 15.172 5.73 17.597 5.5 20.5m9.75-11.25a3.25 3.25 0 1 1-6.5 0a3.25 3.25 0 0 1 6.5 0M5.502 8.5A3.25 3.25 0 0 1 9.5 3.752M18.496 8.5A3.25 3.25 0 0 0 14.5 3.752M22 18c-.18-2.263-2-4.5-4-5M2 18c.18-2.263 2-4.5 4-5"/>
                        </svg>

                        <span>Users</span>
                    </a>
                    <a href="<?= Html::encode($urlGenerator->generate('admin/role/index')) ?>"
                       class="<?= $sidebarItemClass($isRolesActive) ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke-width="1.5"
                                      d="M4 17c0 2.21 1.753 4 3.916 4c1.774 0 3.272-1.205 3.754-2.857h4.19c.422 0 .448.026.448.457v1.257c0 .539 0 .808.163.976c.164.167.428.167.955.167h.761c.44 0 .659 0 .814-.13c.154-.129.197-.349.284-.789h0l.307-1.57c.069-.352.088-.368.439-.368h.752c.527 0 .791 0 .955-.168c.367-.374.332-2.183 0-2.522c-.164-.167-.428-.167-.955-.167h-9.328C10.827 13.934 9.478 13 7.916 13C5.753 13 4 14.79 4 17"/>
                                <path stroke-width="2" d="M8.009 17H8"/>
                                <path stroke-width="1.5"
                                      d="M19 12.5V9c0-2.828 0-4.243-.879-5.121C17.243 3 15.828 3 13 3H8c-2.828 0-4.243 0-5.121.879C2 4.757 2 6.172 2 9v5"/>
                            </g>
                        </svg>
                        <span>Roles</span>
                    </a>

                    <a href="<?= Html::encode($urlGenerator->generate('admin/permission/index')) ?>"
                       class="<?= $sidebarItemClass($isPermissionsActive) ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <g fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-2"/>
                                <path d="M5 15a7 7 0 1 1 14 0a7 7 0 0 1-14 0Z"/>
                                <path stroke-linecap="round" d="M16.5 9.5v-3a4.5 4.5 0 1 0-9 0v3"/>
                            </g>
                        </svg>
                        <span>Permissions</span>
                    </a>
                </nav>
            </div>

            <div class="border-t border-gray-200 p-4">

                <a
                    href="<?= Html::encode(
                        $urlGenerator->generate('home')
                    ) ?>"
                    class=" flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-100 hover:text-gray-900
                    ">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-5 w-5">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M10.5 19.5 3 12 m0 0 7.5 -7.5 M3 12 h18
                            "/>
                    </svg>

                    <span>Back to Shop</span>
                </a>

            </div>

        </aside>

        <div class="lg:pl-64">
            <header
                class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-gray-200 bg-white/90 px-4 backdrop-blur sm:px-6 lg:px-8
                ">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-gray-900 text-sm font-bold text-white lg:hidden
                        ">
                        S
                    </div>

                    <div>
                        <div class="text-sm font-semibold text-gray-900">
                            <?= Html::encode(
                                $this->getTitle() ?: 'Admin Panel'
                            ) ?>
                        </div>

                        <div class="hidden text-xs text-gray-400 sm:block">
                            Manage your online store
                        </div>
                    </div>

                </div>


                <div class="flex items-center gap-3">

                    <a
                        href="<?= Html::encode(
                            $urlGenerator->generate('home')
                        ) ?>"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-600 transition hover:border-gray-300 hover:bg-gray-50 hover:text-gray-900
                        ">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.8"
                            stroke="currentColor"
                            class="h-4 w-4">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m2.25 12 8.954 -8.955 c.44 -.439 1.152 -.439 1.591 0 L21.75 1 M4.5 9.75 v10.125 c0 .621 .504 1.125 1.125 1.125 H9.75 v-4.875 c0 -.621 .504 -1.125 1.125 -1.125 h2.25 c.621 0 1.125 .504 1.125 1.125 V21 h4.125 c.621 0 1.125 -.504 1.125 -1.125 V9.75
                                "/>
                        </svg>

                        <span class="hidden sm:inline">
                                View Store
                            </span>
                    </a>

                </div>

            </header>

            <div
                class="border-b border-gray-200 bg-white px-4 py-3 lg:hidden
                ">
                <div class="flex gap-2 overflow-x-auto">

                    <a
                        href="<?= Html::encode(
                            $urlGenerator->generate('admin/product/index')
                        ) ?>"
                        class="whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium transition
                            <?= $isProductsActive
                            ? 'bg-gray-900 text-white'
                            : 'bg-gray-100 text-gray-600'
                        ?>
                        ">
                        Products
                    </a>

                    <a
                        href="<?= Html::encode(
                            $urlGenerator->generate('admin/category/index')
                        ) ?>"
                        class="whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium transition
                            <?= $isCategoriesActive
                            ? 'bg-gray-900 text-white'
                            : 'bg-gray-100 text-gray-600'
                        ?>
                        ">
                        Categories
                    </a>

                </div>
            </div>

            <main class="min-h-[calc(100vh-4rem)]">
                <?= $content ?>
            </main>

        </div>

    </div>

<?php else: ?>

    <div class="min-h-screen bg-gray-50">

        <header class="border-b border-gray-200 bg-white">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">

                <a href="<?= Html::encode($urlGenerator->generate('home')) ?>"
                   class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-900 text-sm font-bold text-white">
                        S
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-gray-900">Simple Shop</div>
                        <div class="text-xs text-gray-400">Online Store</div>
                    </div>
                </a>

                <nav class="hidden items-center gap-6 md:flex">
                    <a href="<?= Html::encode($urlGenerator->generate('home')) ?>"
                       class="text-sm font-medium text-gray-600 transition hover:text-gray-900">
                        Home
                    </a>

                    <a href="<?= Html::encode($urlGenerator->generate('product/index')) ?>"
                       class="text-sm font-medium text-gray-600 transition hover:text-gray-900">
                        Products
                    </a>

                    <a href="<?= Html::encode($urlGenerator->generate('cart/index')) ?>"
                       class="text-sm font-medium text-gray-600 transition hover:text-gray-900">
                        Cart
                    </a>
                </nav>

                <div class="flex items-center gap-3">
                    <?php if (!$currentUser->isGuest()): ?>
                        <?php $identity = $currentUser->getIdentity(); ?>
                        <?php if ($identity instanceof \App\User\User): ?>
                            <span class="hidden text-sm text-gray-500 sm:block">
                                <?= Html::encode($identity->getEmail()) ?>
                            </span>
                        <?php endif ?>

                        <a href="<?= Html::encode($urlGenerator->generate('dashboard')) ?>"
                           class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-800">
                            Account
                        </a>
                    <?php else: ?>
                        <a href="<?= Html::encode($urlGenerator->generate('auth/login')) ?>"
                           class="rounded-lg px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 hover:text-gray-900">
                            Login
                        </a>

                        <a href="<?= Html::encode($urlGenerator->generate('auth/register')) ?>"
                           class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-800">
                            Register
                        </a>
                    <?php endif ?>
                </div>

            </div>
        </header>

        <main>
            <?= $content ?>
        </main>

    </div>

<?php endif ?>

<?php $this->endBody() ?>

</body>

</html>
<?php $this->endPage() ?>
