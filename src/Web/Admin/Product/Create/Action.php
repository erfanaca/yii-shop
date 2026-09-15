<?php

declare(strict_types=1);

namespace App\Web\Admin\Product\Create;

use App\Product\CreateProductForm;
use App\Product\ProductService;
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
        private ProductService $productService,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $form = new CreateProductForm();

        if ($this->formHydrator->populateFromPostAndValidate($form, $request)) {
            $product = $this->productService->create(
                title: $form->getTitle() ?? '',
                description: $form->getDescription(),
                quantity: $form->getQuantity() ?? 0,
                price: $form->getPrice() ?? '0.00',
                categoryIds: $form->getCategoryIds(),
                imagePaths: $form->getImagePaths(),
            );

            return $this->responseFactory
                ->createResponse(302)
                ->withHeader(
                    'Location',
                    $this->urlGenerator->generate(
                        'admin/product/create',
                        ['id' => $product->id],
                    ),
                );
        }

        return $this->viewRenderer->render(__DIR__ . '/template', [
            'form' => $form,
        ]);
    }
}