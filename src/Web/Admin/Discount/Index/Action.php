<?php

declare(strict_types=1);

namespace App\Web\Admin\Discount\Index;

use App\Auth\PermissionChecker;
use App\Discount\DiscountCodeRepository;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private DiscountCodeRepository $discountCodes,
        private PermissionChecker $permissionChecker,
        private WebViewRenderer $viewRenderer,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        return $this->viewRenderer->render(__DIR__ . '/template', [
            'discountCodes' => $this->discountCodes->findAll(),
            'canCreate' => $this->permissionChecker->can('discount.create'),
            'canView' => $this->permissionChecker->can('discount.view'),
            'canEdit' => $this->permissionChecker->can('discount.edit'),
            'canDelete' => $this->permissionChecker->can('discount.delete'),
        ]);
    }
}
