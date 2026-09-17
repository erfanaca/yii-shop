<?php

declare(strict_types=1);

namespace App\Web\Admin\Permission\Edit;

use App\Permission\PermissionRepository;
use App\Permission\UpdatePermissionForm;
use Psr\Http\Message\{ResponseFactoryInterface, ResponseInterface, ServerRequestInterface};
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Http\Status;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private PermissionRepository     $permissions,
        private FormHydrator             $formHydrator,
        private WebViewRenderer          $viewRenderer,
        private ResponseFactoryInterface $factory,
        private UrlGeneratorInterface    $url
    )
    {
    }

    public function __invoke(ServerRequestInterface $request, #[RouteArgument] int $id): ResponseInterface
    {
        $permission = $this->permissions->findById($id);

        if ($permission === null)
            return $this->factory->createResponse(Status::NOT_FOUND);

        $form = new UpdatePermissionForm();

        if ($request->getMethod() === 'GET') {
            $this->formHydrator->populate($form, ['title' => $permission->getTitle()], scope: '');
        }

        if ($this->formHydrator->populateFromPostAndValidate($form, $request)) {
            $this->permissions->update($permission, $form->getTitle() ?? '');

            return $this->factory
                ->createResponse(302)
                ->withHeader(
                    'Location',
                    $this->url->generate('admin/permission/index')
                );
        }

        return $this->viewRenderer
            ->render(__DIR__ . '/template', [
                'form' => $form,
                'permission' => $permission
            ]);
    }
}
