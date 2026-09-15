<?php

declare(strict_types=1);

namespace App\Web\Admin\Category\Index;

use App\Category\CategoryRepository;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private CategoryRepository $categories,
        private WebViewRenderer $viewRenderer,
    ) {}

    public function __invoke(): ResponseInterface
    {
        return $this->viewRenderer->render(__DIR__.'/template', [
            'categories' => $this->categories->findAll(),
        ]);
    }
}
