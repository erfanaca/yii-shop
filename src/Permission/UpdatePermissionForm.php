<?php
declare(strict_types=1);

namespace App\Permission;

use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;

final class UpdatePermissionForm extends FormModel
{
    #[Required]
    #[Length(min: 1, max: 255)]
    private ?string $title = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
