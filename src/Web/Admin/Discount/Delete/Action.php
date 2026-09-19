<?php

declare(strict_types=1);

namespace App\Web\Admin\Discount\Delete;

use App\Discount\DiscountCodeRepository;
use App\Discount\DiscountCodeService;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Http\Status;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Router\UrlGeneratorInterface;

final readonly class Action
{
    public function __construct(
        private DiscountCodeRepository $discountCodes,
        private DiscountCodeService $discountCodeService,
        private UrlGeneratorInterface $urlGenerator,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(#[RouteArgument] int $id): ResponseInterface
    {
        $discountCode = $this->discountCodes->findById($id);

        if ($discountCode === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        $this->discountCodeService->delete($discountCode);

        return $this->responseFactory->createResponse(302)->withHeader(
            'Location',
            $this->urlGenerator->generate('admin/discount/index'),
        );
    }
}
