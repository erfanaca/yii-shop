<?php

declare(strict_types=1);

namespace App\Web\Admin\Product\Index;

use App\Product\ProductRepository;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;
use Psr\Http\Message\ResponseInterface;


final readonly class Action
{
    public function __construct(
        private readonly ProductRepository $products,
        private WebViewRenderer $viewRenderer
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        return $this->viewRenderer->render(__DIR__ . '/template', [
            'products' => $this->products->findAll(),
        ]);
    }
}
