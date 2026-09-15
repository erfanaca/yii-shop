<?php

declare(strict_types=1);

namespace App\Web\Auth\Register;

use App\Auth\RegisterForm;
use App\Auth\RegistrationService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private WebViewRenderer     $viewRenderer,
        private FormHydrator        $formHydrator,
        private RegistrationService $registrationService,
    )
    {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $form = new RegisterForm();

        $success = false;
        $registrationError = null;

        if ($this->formHydrator->populateFromPostAndValidate($form, $request)) {
            try {
                $this->registrationService->register(
                    $form->getEmail() ?? '',
                    $form->getPassword() ?? '',
                );

                $success = true;
            } catch (RuntimeException $exception) {
                $registrationError = $exception->getMessage();
            }
        }

        return $this->viewRenderer->render(__DIR__ . '/template', [
            'form' => $form,
            'success' => $success,
            'registrationError' => $registrationError,
        ]);
    }
}