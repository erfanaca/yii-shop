<?php

declare(strict_types=1);

namespace App\Product;

use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\StringValue;

final class UpdateProductForm extends FormModel
{
    #[Required]
    #[StringValue(minLength: 1, maxLength: 255)]
    private ?string $title = null;

    #[StringValue(maxLength: 65535)]
    private ?string $description = null;

    #[Required]
    private ?int $quantity = null;

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

    public function getQuantity(): ?int
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