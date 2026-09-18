<?php

declare(strict_types=1);

namespace App\Web\Admin\User\Roles;

use App\Admin\User\UserRepository;
use App\Role\RoleRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Http\Status;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private UserRepository $users,
        private RoleRepository $roles,
        private WebViewRenderer $viewRenderer,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public function __invoke(ServerRequestInterface $request, #[RouteArgument] int $id): ResponseInterface
    {
        $user = $this->users->findById($id);

        if ($user === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        $roles = $this->roles->findAll();
        $selectedRoleIds = $this->users->roleIds($id);

        if ($request->getMethod() === 'POST') {
            $body = $request->getParsedBody();
            $submittedRoleIds = is_array($body) && isset($body['roles']) && is_array($body['roles'])
                ? array_map('intval', $body['roles'])
                : [];

            $availableRoleIds = array_map(
                static fn($role): int => $role->getId(),
                $roles,
            );

            $selectedRoleIds = array_values(array_intersect($availableRoleIds, $submittedRoleIds));
            $this->users->syncRoles($id, $selectedRoleIds);

            return $this->responseFactory
                ->createResponse(Status::FOUND)
                ->withHeader(
                    'Location',
                    $this->urlGenerator->generate('admin/user/index'),
                );
        }

        return $this->viewRenderer->render(__DIR__ . '/template', [
            'user' => $user,
            'roles' => $roles,
            'selectedRoleIds' => $selectedRoleIds,
        ]);
    }
}
