<?php

declare(strict_types=1);

namespace App\Web\Admin\Order\Index;

use App\Order\Query\AdminOrderQueryService;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private AdminOrderQueryService $orders,
        private WebViewRenderer $viewRenderer,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        return $this->viewRenderer->render(__DIR__ . '/template', [
            'orders' => $this->orders->findAll(),
        ]);
    }
}
