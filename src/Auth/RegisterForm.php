<?php

declare(strict_types=1);

namespace App\Auth;

use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;

final class RegisterForm extends FormModel
{
    #[Required]
    #[Email(skipOnEmpty: true)]
    private ?string $email = null;

    #[Required]
    #[Length(min: 8, max: 255, skipOnEmpty: true)]
    private ?string $password = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}