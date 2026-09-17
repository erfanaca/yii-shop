<?php

declare(strict_types=1);

namespace App\Web\Product\View;

use App\Cart\CartService;
use App\Category\CategoryRepository;
use App\Product\ProductRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Http\Status;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\User\CurrentUser;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private ProductRepository $products,
        private CategoryRepository $categories,
        private CartService $cartService,
        private CurrentUser $currentUser,
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

        $cartQuantity = 0;
        if (!$this->currentUser->isGuest()) {
            $cartQuantity = $this->cartService->getProductQuantity(
                (int) $this->currentUser->getId(),
                $id,
            );
        }

        return $this->viewRenderer->render(__DIR__ . '/template', [
            'product' => $product,
            'images' => $this->products->findImages($id),
            'categories' => $categoryItems,
            'cartQuantity' => $cartQuantity,
        ]);
    }
}
