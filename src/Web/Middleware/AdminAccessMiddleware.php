<?php

declare(strict_types=1);

namespace App\Web\Middleware;

use App\Auth\PermissionChecker;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\User\CurrentUser;

final readonly class AdminAccessMiddleware implements MiddlewareInterface
{
    public function __construct(
        private CurrentUser $currentUser,
        private PermissionChecker $permissionChecker,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = trim($request->getUri()->getPath(), '/');

        if (!str_starts_with($path, 'admin')) {
            return $handler->handle($request);
        }

        if ($this->currentUser->isGuest()) {
            return $this->responseFactory->createResponse(302)
                ->withHeader('Location', '/login');
        }

        if ($this->permissionChecker->isSuperAdmin()) {
            return $handler->handle($request);
        }

        $permission = $this->resolvePermission($path);

        if ($permission === null || !$this->permissionChecker->can($permission)) {
            return $this->responseFactory->createResponse(403);
        }

        return $handler->handle($request);
    }

    private function resolvePermission(string $path): ?string
    {
        $parts = explode('/', $path);

        $resource = $parts[1] ?? null;
        $action = $parts[2] ?? 'index';

        if ($resource === null) {
            return null;
        }

        $resource = match ($resource) {
            'products' => 'product',
            'orders' => 'order',
            'categories' => 'category',
            'users' => 'user',
            'roles' => 'role',
            'permissions' => 'permission',
            default => null,
        };

        if ($resource === null) {
            return null;
        }

        return match ($action) {
            'create' => $resource . '.create',
            'update', 'edit' => $resource . '.update',
            'delete' => $resource . '.delete',
            'view', 'show' => $resource . '.view',
            default => $resource . '.manage',
        };
    }
}
