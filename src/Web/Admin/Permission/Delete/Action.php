<?php

declare(strict_types=1);

namespace App\Web\Admin\Permission\Delete;

use App\Permission\PermissionRepository;
use Psr\Http\Message\{ResponseFactoryInterface, ResponseInterface};
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Router\UrlGeneratorInterface;

final readonly class Action
{
    public function __construct(
        private PermissionRepository     $permissions,
        private ResponseFactoryInterface $factory,
        private UrlGeneratorInterface    $url
    )
    {
    }

    public function __invoke(#[RouteArgument] int $id): ResponseInterface
    {
        $permission = $this->permissions->findById($id);

        if ($permission)
            $this->permissions->delete($permission);

        return $this->factory
            ->createResponse(302)
            ->withHeader(
                'Location',
                $this->url->generate('admin/permission/index')
            );
    }
}
