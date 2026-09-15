<?php

declare(strict_types=1);

namespace App\Web\Admin\Product\Delete;

use App\Product\ProductRepository;
use App\Product\ProductService;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Http\Status;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Router\UrlGeneratorInterface;

final readonly class Action
{
    public function __construct(
        private ProductRepository $products,
        private ProductService $productService,
        private UrlGeneratorInterface $urlGenerator,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(#[RouteArgument] int $id): ResponseInterface
    {
        $product = $this->products->findById($id);

        if ($product === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        $this->productService->delete($product);

        return $this->responseFactory
            ->createResponse(302)
            ->withHeader(
                'Location',
                $this->urlGenerator->generate('admin/product/index'),
            );
    }
}
