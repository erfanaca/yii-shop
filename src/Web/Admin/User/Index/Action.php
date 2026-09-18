<?php

declare(strict_types=1);

namespace App\Web\Admin\User\Index;

use App\Admin\User\UserRepository;
use App\Auth\PermissionChecker;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private UserRepository $users,
        private PermissionChecker $permissionChecker,
        private WebViewRenderer $viewRenderer,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        $users = $this->users->findAll();
        $userIds = array_map(
            static fn($user): int => (int) $user->getId(),
            $users,
        );

        return $this->viewRenderer->render(__DIR__ . '/template', [
            'users' => $users,
            'rolesByUserId' => $this->users->roleTitlesByUserIds($userIds),
            'canCreate' => $this->permissionChecker->can('user.create'),
            'canEdit' => $this->permissionChecker->can('user.edit'),
            'canDelete' => $this->permissionChecker->can('user.delete'),
            'canManageRoles' => $this->permissionChecker->can('user.roles'),
        ]);
    }
}
