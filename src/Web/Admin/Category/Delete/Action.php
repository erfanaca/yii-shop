<?php

declare(strict_types=1);

namespace App\Web\Admin\Category\Delete;

use App\Category\CategoryRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Router\HydratorAttribute\RouteArgument;
use Yiisoft\Router\UrlGeneratorInterface;

final readonly class Action
{
    public function __construct(
        private CategoryRepository $categories,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public function __invoke(#[RouteArgument] int $id): ResponseInterface
    {
        $category = $this->categories->findById($id);
        if ($category !== null) {
            $this->categories->delete($category);
        }

        return $this->responseFactory->createResponse(302)
            ->withHeader('Location', $this->urlGenerator->generate('admin/category/index'));
    }
}
