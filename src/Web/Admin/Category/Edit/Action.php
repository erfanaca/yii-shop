<?php

declare(strict_types=1);

namespace App\Web\Admin\Category\Edit;

use App\Category\CategoryForm;
use App\Category\CategoryRepository;
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
        private CategoryRepository $categories,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public function __invoke(ServerRequestInterface $request, #[RouteArgument] int $id): ResponseInterface
    {
        $category = $this->categories->findById($id);

        if ($category === null) return $this->responseFactory->createResponse(Status::NOT_FOUND);

        $form = new CategoryForm();

        if ($request->getMethod() === 'GET') {
            $this->formHydrator->populate($form, ['title'=>$category->getTitle()], scope: '');
        }

        if ($this->formHydrator->populateFromPostAndValidate($form, $request)) {
            $this->categories->update($category, trim($form->getTitle() ?? ''));

            return $this->responseFactory->createResponse(302)
                ->withHeader('Location', $this->urlGenerator->generate('admin/category/index'));
        }

        return $this->viewRenderer->render(__DIR__.'/template', ['form'=>$form, 'category'=>$category]);
    }
}
