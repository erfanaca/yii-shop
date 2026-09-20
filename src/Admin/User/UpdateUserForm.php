<?php

declare(strict_types=1);

namespace App\Admin\User;

use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\Rule\Required;

final class UpdateUserForm extends FormModel
{
    #[Required]
    #[Email(skipOnEmpty: true)]
    private ?string $email = null;

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
