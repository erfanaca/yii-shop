<?php

declare(strict_types=1);

namespace App\Web\Admin\Category\Create;

use App\Category\CategoryForm;
use App\Category\CategoryRepository;
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
        private CategoryRepository $categories,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $form = new CategoryForm();

        if ($this->formHydrator->populateFromPostAndValidate($form, $request)) {
            $this->categories->create(trim($form->getTitle() ?? ''));

            return $this->responseFactory->createResponse(302)
                ->withHeader('Location', $this->urlGenerator->generate('admin/category/index'));
        }

        return $this->viewRenderer->render(__DIR__.'/template', ['form'=>$form]);
    }
}
