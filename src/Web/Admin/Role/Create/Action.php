<?php

declare(strict_types=1);

namespace App\Web\Admin\Role\Create;

use App\Role\CreateRoleForm;
use App\Role\RoleRepository;
use Psr\Http\Message\{ResponseFactoryInterface, ResponseInterface, ServerRequestInterface};
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private WebViewRenderer          $viewRenderer,
        private FormHydrator             $formHydrator,
        private RoleRepository           $roles,
        private ResponseFactoryInterface $factory,
        private UrlGeneratorInterface    $url
    )
    {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $form = new CreateRoleForm();

        if ($this->formHydrator->populateFromPostAndValidate($form, $request)) {
            $this->roles->create($form->getTitle() ?? '');

            return $this->factory
                ->createResponse(302)
                ->withHeader(
                    'Location',
                    $this->url->generate('admin/role/index')
                );
        }
        return $this->viewRenderer
            ->render(__DIR__ . '/template', [
                'form' => $form
            ]);
    }
}
