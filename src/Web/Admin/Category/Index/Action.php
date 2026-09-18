<?php

declare(strict_types=1);

namespace App\Web\Admin\Category\Index;

use App\Auth\PermissionChecker;
use App\Category\CategoryRepository;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private CategoryRepository $categories,
        private PermissionChecker $permissionChecker,
        private WebViewRenderer $viewRenderer,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        return $this->viewRenderer->render(__DIR__ . '/template', [
            'categories' => $this->categories->findAll(),
            'canCreate' => $this->permissionChecker->can('category.create'),
            'canEdit' => $this->permissionChecker->can('category.edit'),
            'canDelete' => $this->permissionChecker->can('category.delete'),
        ]);
    }
}
