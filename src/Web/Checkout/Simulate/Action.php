<?php

declare(strict_types=1);

namespace App\Web\Checkout\Simulate;

use App\Order\CheckoutException;
use App\Order\CheckoutService;
use App\Order\SimulatedPaymentResult;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Http\Status;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\User\CurrentUser;

final readonly class Action
{
    public function __construct(
        private CurrentUser $currentUser,
        private CheckoutService $checkoutService,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(#[RouteArgument] string $result): ResponseInterface
    {
        if ($this->currentUser->isGuest()) {
            return $this->redirect($this->urlGenerator->generate('auth/login'));
        }

        $paymentResult = SimulatedPaymentResult::tryFrom($result);

        if ($paymentResult === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        try {
            $order = $this->checkoutService->checkout(
                (int) $this->currentUser->getId(),
                $paymentResult,
            );
        } catch (CheckoutException $exception) {
            return $this->redirect(
                $this->urlGenerator->generate('cart/index')
                . '?' . http_build_query(['checkout_error' => $exception->getMessage()]),
            );
        }

        return $this->redirect(
            $this->urlGenerator->generate('cart/index')
            . '?' . http_build_query([
                'payment' => $paymentResult->value,
                'order' => $order->getId(),
                'transaction' => $order->getTransactionNumber(),
                'invoice' => $order->getInvoiceNumber(),
            ]),
        );
    }

    private function redirect(string $location): ResponseInterface
    {
        return $this->responseFactory
            ->createResponse(Status::FOUND)
            ->withHeader('Location', $location);
    }
}
