<?php

declare(strict_types=1);

namespace App\Web\Cart\Discount\Remove;

use App\Discount\CartDiscountService;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Http\Status;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\User\CurrentUser;

final readonly class Action
{
    public function __construct(
        private CurrentUser $currentUser,
        private CartDiscountService $discounts,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        if (!$this->currentUser->isGuest()) {
            $this->discounts->remove((int) $this->currentUser->getId());
        }

        return $this->responseFactory
            ->createResponse(Status::FOUND)
            ->withHeader('Location', $this->urlGenerator->generate('cart/index'));
    }
}
