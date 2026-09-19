<?php

declare(strict_types=1);

namespace App\Web\Admin\Discount\Create;

use App\Admin\User\UserRepository;
use App\Discount\DiscountCodeForm;
use App\Discount\DiscountCodeService;
use App\Product\ProductRepository;
use InvalidArgumentException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private WebViewRenderer $viewRenderer,
        private FormHydrator $formHydrator,
        private DiscountCodeService $discountCodeService,
        private UserRepository $users,
        private ProductRepository $products,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $form = new DiscountCodeForm();
        $errorMessage = null;

        if ($this->formHydrator->populateFromPostAndValidate($form, $request)) {
            try {
                $this->discountCodeService->create(
                    code: $form->getCode() ?? '',
                    type: $form->getType() ?? '',
                    value: $form->getValue() ?? '',
                    userScope: $form->getUserScope() ?? '',
                    productScope: $form->getProductScope() ?? '',
                    maxDiscountAmount: $form->getMaxDiscountAmount(),
                    minimumOrderAmount: $form->getMinimumOrderAmount(),
                    userIds: $form->getUserIds(),
                    productIds: $form->getProductIds(),
                );

                return $this->responseFactory->createResponse(302)->withHeader(
                    'Location',
                    $this->urlGenerator->generate('admin/discount/index'),
                );
            } catch (InvalidArgumentException $exception) {
                $errorMessage = $exception->getMessage();
            }
        }

        return $this->viewRenderer->render(__DIR__ . '/template', [
            'form' => $form,
            'users' => $this->users->findAll(),
            'products' => $this->products->findAll(),
            'errorMessage' => $errorMessage,
        ]);
    }
}
