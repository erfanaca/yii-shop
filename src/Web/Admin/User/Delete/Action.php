<?php

declare(strict_types=1);

namespace App\Web\Admin\User\Delete;

use App\Admin\User\UserRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Router\UrlGeneratorInterface;

final readonly class Action
{
    public function __construct(
        private UserRepository $users,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public function __invoke(#[RouteArgument] int $id): ResponseInterface
    {
        $user = $this->users->findById($id);

        if ($user !== null) {
            $this->users->delete($user);
        }

        return $this->responseFactory
            ->createResponse(302)
            ->withHeader(
                'Location',
                $this->urlGenerator->generate('admin/user/index')
            );
    }
}
