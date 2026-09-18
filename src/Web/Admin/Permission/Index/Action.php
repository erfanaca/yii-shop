<?php

declare(strict_types=1);

namespace App\Web\Admin\Permission\Index;

use App\Auth\PermissionChecker;
use App\Permission\PermissionRepository;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private PermissionRepository $permissions,
        private PermissionChecker $permissionChecker,
        private WebViewRenderer $viewRenderer,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        return $this->viewRenderer->render(__DIR__ . '/template', [
            'permissions' => $this->permissions->findAll(),
            'canCreate' => $this->permissionChecker->can('permission.create'),
            'canEdit' => $this->permissionChecker->can('permission.edit'),
            'canDelete' => $this->permissionChecker->can('permission.delete'),
        ]);
    }
}
