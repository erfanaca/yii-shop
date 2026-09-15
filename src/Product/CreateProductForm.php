<?php

declare(strict_types=1);

namespace App\Product;

use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Rule\Each;
use Yiisoft\Validator\Rule\Integer;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Regex;
use Yiisoft\Validator\Rule\Required;

final class CreateProductForm extends FormModel
{
    #[Required]
    #[Length(min: 1, max: 255)]
    private ?string $title = null;

    #[Length(max: 65535, skipOnEmpty: true)]
    private ?string $description = null;

    #[Required]
    #[Integer(min: 0)]
    private ?int $quantity = null;

    #[Required]
    #[Regex(
        pattern: '/^\d{1,10}(?:\.\d{1,2})?$/',
        message: 'Price must be a non-negative decimal number with at most 2 decimal places.',
        skipOnEmpty: true,
    )]
    private ?string $price = null;

    #[Each(new Integer(min: 1))]
    private array $categoryIds = [];

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
        return array_map(static fn (mixed $id): int => (int) $id, $this->categoryIds);
    }
}
