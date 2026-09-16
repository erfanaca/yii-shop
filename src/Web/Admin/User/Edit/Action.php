<?php

declare(strict_types=1);

namespace App\Web\Admin\User\Edit;

use App\Admin\User\UserForm;
use App\Admin\User\UserRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Http\Status;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;
use Yiisoft\Security\PasswordHasher;

final readonly class Action
{
    public function __construct(
        private WebViewRenderer $viewRenderer,
        private FormHydrator $formHydrator,
        private UserRepository $users,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
        private PasswordHasher $passwordHasher,
    ) {}

    public function __invoke(ServerRequestInterface $request, #[RouteArgument] int $id): ResponseInterface
    {
        $user = $this->users->findById($id);

        if ($user === null)
            return $this->responseFactory->createResponse(Status::NOT_FOUND);

        $form = new UserForm();

        if ($request->getMethod() === 'GET') {
            $this->formHydrator->populate($form, ['email' => $user->getEmail()], scope: '');
        }

        if ($this->formHydrator->populateFromPostAndValidate($form, $request)) {
            $this->users->update($user, $form->getEmail(), $this->passwordHasher->hash($form->getPassword()));

            return $this->responseFactory
                ->createResponse(302)
                ->withHeader(
                    'Location',
                    $this->urlGenerator->generate('admin/user/index')
                );
        }

        return $this->viewRenderer->render(__DIR__ . '/template', [
            'form' => $form,
            'user' => $user
        ]);
    }
}
