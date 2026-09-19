<?php

declare(strict_types=1);

namespace App\Web\Admin\Discount\Edit;

use App\Admin\User\UserRepository;
use App\Discount\DiscountCodeForm;
use App\Discount\DiscountCodeRepository;
use App\Discount\DiscountCodeService;
use App\Product\ProductRepository;
use InvalidArgumentException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Http\Status;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private WebViewRenderer $viewRenderer,
        private FormHydrator $formHydrator,
        private DiscountCodeRepository $discountCodes,
        private DiscountCodeService $discountCodeService,
        private UserRepository $users,
        private ProductRepository $products,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, #[RouteArgument] int $id): ResponseInterface
    {
        $discountCode = $this->discountCodes->findById($id);

        if ($discountCode === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        $form = new DiscountCodeForm();
        $errorMessage = null;

        if ($request->getMethod() === 'GET') {
            $this->formHydrator->populate($form, [
                'code' => $discountCode->getCode(),
                'type' => $discountCode->getType()->value,
                'value' => $discountCode->getValue(),
                'userScope' => $discountCode->getUserScope()->value,
                'productScope' => $discountCode->getProductScope()->value,
                'maxDiscountAmount' => $discountCode->getMaxDiscountAmount(),
                'minimumOrderAmount' => $discountCode->getMinimumOrderAmount(),
                'userIds' => $this->discountCodes->findUserIds($discountCode->getId()),
                'productIds' => $this->discountCodes->findProductIds($discountCode->getId()),
            ], scope: '');
        }

        if ($this->formHydrator->populateFromPostAndValidate($form, $request)) {
            try {
                $this->discountCodeService->update(
                    discountCode: $discountCode,
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
            'discountCode' => $discountCode,
            'users' => $this->users->findAll(),
            'products' => $this->products->findAll(),
            'errorMessage' => $errorMessage,
        ]);
    }
}
