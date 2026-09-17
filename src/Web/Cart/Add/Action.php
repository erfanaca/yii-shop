<?php

declare(strict_types=1);

namespace App\Web\Cart\Add;

use App\Cart\CartService;
use App\Product\ProductRepository;
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
        private ProductRepository $products,
        private CartService $cartService,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(#[RouteArgument] int $id): ResponseInterface
    {
        if (!$this->currentUser->isGuest()) {
            $product = $this->products->findById($id);

            if ($product !== null) {
                $this->cartService->addProduct(
                    (int) $this->currentUser->getId(),
                    $product->getId(),
                    $product->getPrice(),
                );
            }

            return $this->redirect('product/view', $id);
        }

        return $this->responseFactory
            ->createResponse(Status::FOUND)
            ->withHeader(
                'Location',
                $this->urlGenerator->generate('auth/login'),
            );
    }

    private function redirect(string $route, int $id): ResponseInterface
    {
        return $this->responseFactory
            ->createResponse(Status::FOUND)
            ->withHeader(
                'Location',
                $this->urlGenerator->generate($route, ['id' => $id]),
            );
    }
}
