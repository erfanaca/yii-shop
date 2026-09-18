<?php

declare(strict_types=1);

namespace App\Web\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\Http\Status;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\User\CurrentUser;

final readonly class GuestOnlyMiddleware implements MiddlewareInterface
{
    private const GUEST_ONLY_PATHS = [
        'login',
        'register',
    ];

    public function __construct(
        private CurrentUser $currentUser,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = trim($request->getUri()->getPath(), '/');

        if (!in_array($path, self::GUEST_ONLY_PATHS, true) || $this->currentUser->isGuest()) {
            return $handler->handle($request);
        }

        return $this->responseFactory
            ->createResponse(Status::FOUND)
            ->withHeader('Location', $this->urlGenerator->generate('dashboard'));
    }
}
