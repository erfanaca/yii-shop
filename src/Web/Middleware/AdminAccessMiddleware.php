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
        $resourceSegment = $parts[1] ?? null;

        if ($resourceSegment === null) {
            return null;
        }

        $resource = match ($resourceSegment) {
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

        $segments = array_slice($parts, 2);

        if ($resource === 'user' && in_array('roles', $segments, true)) {
            return 'user.roles';
        }

        if ($segments === []) {
            return $resource . '.index';
        }

        if (($segments[0] ?? null) === 'create') {
            return $resource . '.create';
        }

        if (in_array('delete', $segments, true)) {
            return $resource . '.delete';
        }

        if (in_array('edit', $segments, true) || in_array('update', $segments, true)) {
            return $resource . '.edit';
        }

        if (in_array('view', $segments, true) || in_array('show', $segments, true)) {
            return $resource . '.view';
        }

        if (count($segments) === 1 && ctype_digit($segments[0])) {
            return $resource . '.view';
        }

        return null;
    }
}
