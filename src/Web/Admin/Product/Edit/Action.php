<?php

declare(strict_types=1);

namespace App\Web\Admin\Product\Edit;

use App\Category\CategoryRepository;
use App\Product\ProductRepository;
use App\Product\ProductService;
use App\Product\UpdateProductForm;
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
        private ProductRepository $products,
        private ProductService $productService,
        private CategoryRepository $categories,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, #[RouteArgument] int $id): ResponseInterface
    {
        $product = $this->products->findById($id);

        if ($product === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        $form = new UpdateProductForm();
        $categories = $this->categories->findAll();

        if ($request->getMethod() === 'GET') {
            $this->formHydrator->populate(
                $form,
                [
                    'title' => $product->getTitle(),
                    'description' => $product->getDescription(),
                    'quantity' => $product->getQuantity(),
                    'price' => $product->getPrice(),
                    'categoryIds' => $this->products->findCategoryIds($product->getId()),
                ],
                scope: '',
            );
        }

        if ($this->formHydrator->populateFromPostAndValidate($form, $request)) {
            try {
                $this->productService->update(
                    product: $product,
                    title: $form->getTitle() ?? '',
                    description: $form->getDescription(),
                    quantity: $form->getQuantity() ?? 0,
                    price: $form->getPrice() ?? '0.00',
                    categoryIds: $form->getCategoryIds(),
                );

                return $this->responseFactory
                    ->createResponse(302)
                    ->withHeader(
                        'Location',
                        $this->urlGenerator->generate('admin/product/index'),
                    );
            } catch (InvalidArgumentException $exception) {
                $form->addError($exception->getMessage(), ['categoryIds']);
            }
        }

        return $this->viewRenderer->render(__DIR__ . '/template', [
            'form' => $form,
            'product' => $product,
            'categories' => $categories,
        ]);
    }
}
