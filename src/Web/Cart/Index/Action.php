<?php

declare(strict_types=1);

namespace App\Web\Cart\Index;

use App\Cart\CartService;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Http\Status;
use Yiisoft\User\CurrentUser;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private CurrentUser $currentUser,
        private CartService $cartService,
        private WebViewRenderer $viewRenderer,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->currentUser->isGuest()) {
            return $this->responseFactory
                ->createResponse(Status::FOUND)
                ->withHeader('Location', '/login');
        }

        $query = $request->getQueryParams();

        return $this->viewRenderer->render(__DIR__ . '/template', [
            'items' => $this->cartService->getItems((int) $this->currentUser->getId()),
            'paymentResult' => isset($query['payment']) ? (string) $query['payment'] : null,
            'orderId' => isset($query['order']) ? (int) $query['order'] : null,
            'transactionNumber' => isset($query['transaction']) ? (string) $query['transaction'] : null,
            'invoiceNumber' => isset($query['invoice']) ? (string) $query['invoice'] : null,
            'checkoutError' => isset($query['checkout_error']) ? (string) $query['checkout_error'] : null,
        ]);
    }
}
