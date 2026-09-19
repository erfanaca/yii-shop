<?php

declare(strict_types=1);

namespace App\Web\Cart\Index;

use App\Cart\CartService;
use App\Discount\CartDiscountService;
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
        private CartDiscountService $discounts,
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
        $userId = (int) $this->currentUser->getId();
        $items = $this->cartService->getItems($userId);
        $pricing = $this->discounts->getPricing($userId, $items);

        return $this->viewRenderer->render(__DIR__ . '/template', [
            'items' => $items,
            'pricing' => $pricing,
            'paymentResult' => isset($query['payment']) ? (string) $query['payment'] : null,
            'orderId' => isset($query['order']) ? (int) $query['order'] : null,
            'transactionNumber' => isset($query['transaction']) ? (string) $query['transaction'] : null,
            'invoiceNumber' => isset($query['invoice']) ? (string) $query['invoice'] : null,
            'checkoutError' => isset($query['checkout_error']) ? (string) $query['checkout_error'] : null,
            'discountError' => isset($query['discount_error']) ? (string) $query['discount_error'] : null,
            'discountApplied' => isset($query['discount_applied']) ? (string) $query['discount_applied'] : null,
            'discountCodeInput' => isset($query['discount_code']) ? (string) $query['discount_code'] : '',
        ]);
    }
}
