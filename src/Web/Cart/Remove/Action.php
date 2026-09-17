<?php

declare(strict_types=1);

namespace App\Web\Cart\Remove;

use App\Cart\CartService;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Http\Status;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\User\CurrentUser;

final readonly class Action
{
    public function __construct(
        private CurrentUser $currentUser,
        private CartService $cartService,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public function __invoke(#[RouteArgument] int $id): ResponseInterface
    {
        if (!$this->currentUser->isGuest()) {
            $this->cartService->removeProduct((int)$this->currentUser->getId(), $id);
        }

        return $this->responseFactory
            ->createResponse(Status::FOUND)
            ->withHeader('Location', $this->urlGenerator->generate('cart/index'));
    }
}
