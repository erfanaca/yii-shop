<?php

declare(strict_types=1);

namespace App\Web\Admin\User\Edit;

use App\Admin\User\UpdateUserForm;
use App\Admin\User\UserRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Db\Exception\IntegrityException;
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

        $form = new UpdateUserForm();

        if ($request->getMethod() === 'GET') {
            $this->formHydrator->populate($form, ['email' => $user->getEmail()], scope: '');
        }

        if ($this->formHydrator->populateFromPostAndValidate($form, $request)) {
            $email = strtolower(trim($form->getEmail() ?? ''));

            if ($this->users->existsByEmail($email, (int) $user->getId())) {
                $form->addError('This email is already registered.', ['email']);
            } else {
                try {
                    $password = $form->getPassword();
                    $passwordHash = $password === null || $password === ''
                        ? null
                        : $this->passwordHasher->hash($password);

                    $this->users->update($user, $email, $passwordHash);
                } catch (IntegrityException $exception) {
                    if (!$this->users->existsByEmail($email, (int) $user->getId())) {
                        throw $exception;
                    }

                    $form->addError('This email is already registered.', ['email']);

                    return $this->viewRenderer->render(__DIR__ . '/template', [
                        'form' => $form,
                        'user' => $user,
                    ]);
                }

                return $this->responseFactory
                    ->createResponse(302)
                    ->withHeader(
                        'Location',
                        $this->urlGenerator->generate('admin/user/index')
                    );
            }
        }

        return $this->viewRenderer->render(__DIR__ . '/template', [
            'form' => $form,
            'user' => $user
        ]);
    }
}
