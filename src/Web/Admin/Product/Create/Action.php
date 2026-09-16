<?php

declare(strict_types=1);

namespace App\Web\Admin\Product\Create;

use App\Category\CategoryRepository;
use App\Product\CreateProductForm;
use App\Product\ProductService;
use App\Product\ProductImageUploader;
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
        private ProductService $productService,
        private ProductImageUploader $imageUploader,
        private CategoryRepository $categories,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $form = new CreateProductForm();
        $categories = $this->categories->findAll();

        if ($this->formHydrator->populateFromPostAndValidate($form, $request)) {
            try {
                $this->productService->create(
                    title: $form->getTitle() ?? '',
                    description: $form->getDescription(),
                    quantity: $form->getQuantity() ?? 0,
                    price: $form->getPrice() ?? '0.00',
                    categoryIds: $form->getCategoryIds(),
                    imagePaths: $this->imageUploader->upload($request->getUploadedFiles()['images'] ?? []),
                );

                return $this->responseFactory
                    ->createResponse(302)
                    ->withHeader(
                        'Location',
                        $this->urlGenerator->generate('admin/product/index'),
                    );
            } catch (InvalidArgumentException $exception) {
                $form->addError($exception->getMessage());
            }
        }

        return $this->viewRenderer->render(__DIR__ . '/template', [
            'form' => $form,
            'categories' => $categories,
        ]);
    }
}
