<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "categories")]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 100)]
    private string $name;

    #[ORM\Column(type: "string", length: 100, unique: true)]
    private string $slug;

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $icon = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $parentId = null;

    #[ORM\Column(type: "boolean", default: true)]
    private bool $isActive = true;

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }
    public function getSlug(): string { return $this->slug; }
    public function setSlug(string $slug): void { $this->slug = $slug; }
    public function getIcon(): ?string { return $this->icon; }
    public function setIcon(?string $icon): void { $this->icon = $icon; }
    public function getParentId(): ?int { return $this->parentId; }
    public function setParentId(?int $parentId): void { $this->parentId = $parentId; }
    public function isActive(): bool { return $this->isActive; }
    public function setIsActive(bool $isActive): void { $this->isActive = $isActive; }
}

#[ORM\Entity]
#[ORM\Table(name: "regions")]
class Region
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 100)]
    private string $name;

    #[ORM\Column(type: "string", length: 100, unique: true)]
    private string $slug;

    #[ORM\Column(type: "string", length: 3)]
    private string $code;

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }
    public function getSlug(): string { return $this->slug; }
    public function setSlug(string $slug): void { $this->slug = $slug; }
    public function getCode(): string { return $this->code; }
    public function setCode(string $code): void { $this->code = $code; }
}

#[ORM\Entity]
#[ORM\Table(name: "cities")]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 100)]
    private string $name;

    #[ORM\Column(type: "string", length: 100, unique: true)]
    private string $slug;

    #[ORM\ManyToOne(targetEntity: Region::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Region $region;

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }
    public function getSlug(): string { return $this->slug; }
    public function setSlug(string $slug): void { $this->slug = $slug; }
    public function getRegion(): Region { return $this->region; }
    public function setRegion(Region $region): void { $this->region = $region; }
}
