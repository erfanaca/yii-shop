<?php

declare(strict_types=1);

namespace App\Web\Admin\Discount\View;

use App\Admin\User\UserRepository;
use App\Auth\PermissionChecker;
use App\Discount\DiscountCodeRepository;
use App\Product\ProductRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Http\Status;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private DiscountCodeRepository $discountCodes,
        private UserRepository $users,
        private ProductRepository $products,
        private PermissionChecker $permissionChecker,
        private WebViewRenderer $viewRenderer,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(#[RouteArgument] int $id): ResponseInterface
    {
        $discountCode = $this->discountCodes->findById($id);

        if ($discountCode === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        $userIds = $this->discountCodes->findUserIds($id);
        $productIds = $this->discountCodes->findProductIds($id);

        $selectedUsers = array_values(array_filter(
            $this->users->findAll(),
            static fn ($user): bool => in_array((int) $user->getId(), $userIds, true),
        ));
        $selectedProducts = array_values(array_filter(
            $this->products->findAll(),
            static fn ($product): bool => in_array($product->getId(), $productIds, true),
        ));

        return $this->viewRenderer->render(__DIR__ . '/template', [
            'discountCode' => $discountCode,
            'selectedUsers' => $selectedUsers,
            'selectedProducts' => $selectedProducts,
            'canEdit' => $this->permissionChecker->can('discount.edit'),
        ]);
    }
}
