<?php

declare(strict_types=1);

namespace App\Web\Admin\Product\Delete;

use App\Product\ProductRepository;
use Yiisoft\Http\Status;
use Yiisoft\Router\UrlGeneratorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;


final class Action
{
    public function __construct(
        private readonly ProductRepository $products,
        private readonly UrlGeneratorInterface $urlGenerator,
        private ResponseFactoryInterface $responseFactory,
    ) {}

    public function __invoke(
        int $id,
    ): ResponseInterface {
        $product = $this->products->findById($id);

        if ($product === null) {
            return $this->responseFactory
                ->createResponse(Status::NOT_FOUND)
                ->withHeader(
                    'Location',
                    $this->urlGenerator->generate('admin/product/index'),
                );
        }

        $this->products->delete($product);

        return $this->responseFactory
            ->createResponse(302)
            ->withHeader(
                'Location',
                $this->urlGenerator->generate('admin/product/index'),
            );
    }
}
