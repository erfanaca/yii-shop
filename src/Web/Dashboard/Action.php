<?php

declare(strict_types=1);

namespace App\Web\Dashboard;

use App\Order\Query\OrderQueryService;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Http\Status;
use Yiisoft\User\CurrentUser;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private CurrentUser $currentUser,
        private OrderQueryService $orderQueryService,
        private WebViewRenderer $viewRenderer,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        if ($this->currentUser->isGuest()) {
            return $this->responseFactory
                ->createResponse(Status::FOUND)
                ->withHeader('Location', '/login');
        }

        return $this->viewRenderer->render(__DIR__ . '/template', [
            'dashboard' => $this->orderQueryService->getDashboardForUser(
                (int) $this->currentUser->getId(),
            ),
        ]);
    }
}
