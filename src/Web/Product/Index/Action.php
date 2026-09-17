<?php

declare(strict_types=1);

namespace App\Web\Product\Index;

use App\Product\ProductRepository;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private ProductRepository $products,
        private WebViewRenderer $viewRenderer,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        $products = $this->products->findAll();

        $images = [];
        foreach ($products as $product) {
            $images[$product->getId()] = $this->products->findImages($product->getId());
        }

        return $this->viewRenderer->render(__DIR__ . '/template', [
            'products' => $products,
            'images' => $images,
        ]);
    }
}
