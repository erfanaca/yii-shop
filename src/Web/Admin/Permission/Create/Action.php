<?php

declare(strict_types=1);

namespace App\Web\Admin\Permission\Create;

use App\Permission\CreatePermissionForm;
use App\Permission\PermissionRepository;
use Psr\Http\Message\{ResponseFactoryInterface, ResponseInterface, ServerRequestInterface};
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private WebViewRenderer $view,
        private FormHydrator $formHydrator,
        private PermissionRepository $permissions,
        private ResponseFactoryInterface $factory,
        private UrlGeneratorInterface $url
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $form = new CreatePermissionForm();

        if ($this->formHydrator->populateFromPostAndValidate($form, $request)) {
            $this->permissions->create($form->getTitle() ?? '');
            return $this->factory->createResponse(302)->withHeader('Location', $this->url->generate('admin/permission/index'));
        }

        return $this->view->render(__DIR__ . '/template', [
            'form' => $form
        ]);
    }
}
