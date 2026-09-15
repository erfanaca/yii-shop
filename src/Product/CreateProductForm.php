<?php

declare(strict_types=1);

namespace App\Product;

use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Rule\Required;

final class CreateProductForm extends FormModel
{
    #[Required]
    private ?string $title = null;

    private ?string $description = null;

    #[Required]
    private ?string $quantity = null;

    #[Required]
    private ?string $price = null;

    /**
     * @var int[]
     */
    private array $categoryIds = [];

    /**
     * @var string[]
     */
    private array $imagePaths = [];

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * @return int[]
     */
    public function getCategoryIds(): array
    {
        return $this->categoryIds;
    }

    /**
     * @return string[]
     */
    public function getImagePaths(): array
    {
        return $this->imagePaths;
    }
}