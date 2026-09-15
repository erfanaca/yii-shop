<?php

namespace App\Web\Admin\Product\DeleteImage;

use App\Product\ProductRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Router\HydratorAttribute\RouteArgument;


final readonly class Action
{
    public function __construct(
        private ProductRepository $products,
        private ResponseFactoryInterface $responseFactory
    ) {
    }

    public function __invoke(#[RouteArgument] int $productId, #[RouteArgument] int $id): ResponseInterface
    {
        $path = $this->products->deleteImage($id);

        if ($path) {
            $file = dirname(__DIR__, 5)
                . '/public/'
                . ltrim($path, '/');

            if (file_exists($file)) {
                unlink($file);
            }
        }

        return $this->responseFactory
            ->createResponse(303)
            ->withHeader(
                'Location',
                '/admin/products/' . $productId . '/edit'
            );
    }
}
