<?php declare(strict_types=1);

namespace App\Role;
use Yiisoft\ActiveRecord\ActiveRecord;

final class Role extends ActiveRecord
{
    public int $id;
    public string $title;

    public function tableName(): string
    {
        return 'roles';
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
