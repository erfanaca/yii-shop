<?php

declare(strict_types=1);

namespace App\Web\Admin\User\Index;

use App\Admin\User\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private UserRepository $users,
        private WebViewRenderer $viewRenderer,
    ) {}

    public function __invoke(): ResponseInterface
    {
        return $this->viewRenderer->render(__DIR__. '/template', [
            'users' => $this->users->findAll(),
        ]);
    }
}
