<?php

declare(strict_types=1);

namespace App\Web\Admin\Product\Index;

use App\Auth\PermissionChecker;
use App\Product\ProductRepository;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private ProductRepository $products,
        private PermissionChecker $permissionChecker,
        private WebViewRenderer $viewRenderer,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        return $this->viewRenderer->render(__DIR__ . '/template', [
            'products' => $this->products->findAll(),
            'canCreate' => $this->permissionChecker->can('product.create'),
            'canEdit' => $this->permissionChecker->can('product.edit'),
            'canDelete' => $this->permissionChecker->can('product.delete'),
        ]);
    }
}
