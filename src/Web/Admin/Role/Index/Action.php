<?php declare(strict_types=1); namespace App\Web\Admin\Role\Index;
use App\Role\RoleRepository; use Psr\Http\Message\ResponseInterface; use Yiisoft\Yii\View\Renderer\WebViewRenderer;
final readonly class Action{public function __construct(private RoleRepository $roles,private WebViewRenderer $viewRenderer){} public function __invoke():ResponseInterface{return $this->viewRenderer->render(__DIR__.'/template',['roles'=>$this->roles->findAll()]);}}
