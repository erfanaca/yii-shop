<?php

declare(strict_types=1);

namespace App\Web\HomePage;

use App\User\User;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\User\CurrentUser;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private WebViewRenderer $viewRenderer,
        private CurrentUser $currentUser,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        $identity = $this->currentUser->getIdentity();

        return $this->viewRenderer->render(__DIR__ . '/template', [
            'user' => $identity instanceof User ? $identity : null,
        ]);
    }
}