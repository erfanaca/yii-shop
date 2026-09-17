<?php

declare(strict_types=1);

namespace App\Web\Product\View;

use App\Category\CategoryRepository;
use App\Product\ProductRepository;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Http\Status;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;
use Psr\Http\Message\ResponseFactoryInterface;

final readonly class Action
{
    public function __construct(
        private ProductRepository $products,
        private CategoryRepository $categories,
        private WebViewRenderer $viewRenderer,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(#[RouteArgument] int $id): ResponseInterface
    {
        $product = $this->products->findById($id);

        if ($product === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        $categoryItems = [];
        foreach ($this->products->findCategoryIds($id) as $categoryId) {
            $category = $this->categories->findById($categoryId);
            if ($category !== null) {
                $categoryItems[] = $category;
            }
        }

        return $this->viewRenderer->render(__DIR__ . '/template', [
            'product' => $product,
            'images' => $this->products->findImages($id),
            'categories' => $categoryItems,
        ]);
    }
}
