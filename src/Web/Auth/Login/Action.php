<?php

declare(strict_types=1);

namespace App\Web\Auth\Login;

use App\Auth\LoginForm;
use App\Auth\LoginService;
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
        private LoginService $loginService,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $form = new LoginForm();
        $loginError = null;

        if ($this->formHydrator->populateFromPostAndValidate($form, $request)) {
            $loggedIn = $this->loginService->login(
                $form->getEmail() ?? '',
                $form->getPassword() ?? '',
            );

            if ($loggedIn) {
                return $this->responseFactory
                    ->createResponse(302)
                    ->withHeader(
                        'Location',
                        $this->urlGenerator->generate('dashboard'),
                    );
            }

            $loginError = 'Invalid email or password.';
        }

        return $this->viewRenderer->render(__DIR__ . '/template', [
            'form' => $form,
            'loginError' => $loginError,
        ]);
    }
}