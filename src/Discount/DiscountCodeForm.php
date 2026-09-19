<?php

declare(strict_types=1);

namespace App\Discount;

use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Rule\Each;
use Yiisoft\Validator\Rule\Integer;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Regex;
use Yiisoft\Validator\Rule\Required;

final class DiscountCodeForm extends FormModel
{
    #[Required]
    #[Length(min: 1, max: 100)]
    #[Regex(
        pattern: '/^[A-Za-z0-9_-]+$/',
        message: 'Code may contain only letters, numbers, dashes and underscores.',
        skipOnEmpty: true,
    )]
    private ?string $code = null;

    #[Required]
    #[Regex(
        pattern: '/^(PERCENTAGE|FIXED)$/',
        message: 'Invalid discount type.',
        skipOnEmpty: true,
    )]
    private ?string $type = null;

    #[Required]
    #[Regex(
        pattern: '/^\d{1,10}(?:\.\d{1,2})?$/',
        message: 'Value must be a positive decimal number with at most 2 decimal places.',
        skipOnEmpty: true,
    )]
    private ?string $value = null;

    #[Required]
    #[Regex(
        pattern: '/^(ALL|SPECIFIC)$/',
        message: 'Invalid user scope.',
        skipOnEmpty: true,
    )]
    private ?string $userScope = DiscountScope::All->value;

    #[Required]
    #[Regex(
        pattern: '/^(ALL|SPECIFIC)$/',
        message: 'Invalid product scope.',
        skipOnEmpty: true,
    )]
    private ?string $productScope = DiscountScope::All->value;

    #[Regex(
        pattern: '/^\d{1,10}(?:\.\d{1,2})?$/',
        message: 'Maximum discount amount must be a positive decimal number with at most 2 decimal places.',
        skipOnEmpty: true,
    )]
    private ?string $maxDiscountAmount = null;

    #[Regex(
        pattern: '/^\d{1,10}(?:\.\d{1,2})?$/',
        message: 'Minimum order amount must be a positive decimal number with at most 2 decimal places.',
        skipOnEmpty: true,
    )]
    private ?string $minimumOrderAmount = null;

    #[Each(new Integer(min: 1))]
    private array $userIds = [];

    #[Each(new Integer(min: 1))]
    private array $productIds = [];

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getUserScope(): ?string
    {
        return $this->userScope;
    }

    public function getProductScope(): ?string
    {
        return $this->productScope;
    }

    public function getMaxDiscountAmount(): ?string
    {
        return $this->maxDiscountAmount;
    }

    public function getMinimumOrderAmount(): ?string
    {
        return $this->minimumOrderAmount;
    }

    /** @return int[] */
    public function getUserIds(): array
    {
        return array_map(static fn (mixed $id): int => (int) $id, $this->userIds);
    }

    /** @return int[] */
    public function getProductIds(): array
    {
        return array_map(static fn (mixed $id): int => (int) $id, $this->productIds);
    }
}
