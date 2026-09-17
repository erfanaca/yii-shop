<?php declare(strict_types=1);
namespace App\Role;
final class Role { public function __construct(private readonly int $id, private readonly string $title) {} public function getId(): int{return $this->id;} public function getTitle(): string{return $this->title;} }
