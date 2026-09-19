<?php

declare(strict_types=1);

namespace App\Web\Cart\Discount\Apply;

use App\Discount\CartDiscountService;
use App\Discount\DiscountApplicationException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Http\Status;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\User\CurrentUser;

final readonly class Action
{
    public function __construct(
        private CurrentUser $currentUser,
        private CartDiscountService $discounts,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->currentUser->isGuest()) {
            return $this->redirect($this->urlGenerator->generate('auth/login'));
        }

        $body = $request->getParsedBody();
        $code = is_array($body) ? (string) ($body['code'] ?? '') : '';

        try {
            $pricing = $this->discounts->apply((int) $this->currentUser->getId(), $code);
            $appliedCode = $pricing->getDiscountCode()?->getCode() ?? strtoupper(trim($code));

            return $this->redirect(
                $this->urlGenerator->generate('cart/index')
                . '?' . http_build_query(['discount_applied' => $appliedCode]),
            );
        } catch (DiscountApplicationException $exception) {
            return $this->redirect(
                $this->urlGenerator->generate('cart/index')
                . '?' . http_build_query([
                    'discount_error' => $exception->getMessage(),
                    'discount_code' => strtoupper(trim($code)),
                ]),
            );
        }
    }

    private function redirect(string $location): ResponseInterface
    {
        return $this->responseFactory
            ->createResponse(Status::FOUND)
            ->withHeader('Location', $location);
    }
}
