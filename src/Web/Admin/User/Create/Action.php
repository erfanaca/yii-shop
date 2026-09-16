<?php

declare(strict_types=1);

namespace App\Web\Admin\User\Create;

use App\Admin\User\UserForm;
use App\Admin\User\UserRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
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
        private readonly PasswordHasher $passwordHasher,
    ) {}

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $form = new UserForm();

        if ($this->formHydrator->populateFromPostAndValidate($form, $request)) {
            $this->users->create($form->getEmail(), $this->passwordHasher->hash($form->getPassword()));

            return $this->responseFactory
                ->createResponse(302)
                ->withHeader(
                    'Location',
                    $this->urlGenerator->generate('admin/user/index')
                );
        }

        return $this->viewRenderer->render(__DIR__ . '/template', [
            'form' => $form
        ]);
    }
}
