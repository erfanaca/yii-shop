<?php

declare(strict_types=1);

namespace App\Permission;

use Yiisoft\ActiveRecord\ActiveRecord;

final class Permission extends ActiveRecord
{
    public ?int $id = null;
    public string $title;

    public function tableName(): string
    {
        return 'permissions';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
