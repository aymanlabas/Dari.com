<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: "App\Repository\ListingRepository")]
#[ORM\Table(name: "listings")]
#[ORM\HasLifecycleCallbacks]
class Listing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $title;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private string $slug;

    #[ORM\Column(type: "text")]
    private string $description;

    #[ORM\Column(type: "decimal", precision: 12, scale: 2)]
    private string $price;

    #[ORM\Column(type: "string", length: 3)]
    private string $currency = "MAD";

    #[ORM\Column(type: "string", length: 255)]
    private string $condition;

    #[ORM\Column(type: "string", length: 20)]
    private string $status = "draft";

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $categoryId = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $subcategoryId = null;

    #[ORM\Column(type: "integer")]
    private int $sellerId;

    #[ORM\Column(type: "integer")]
    private int $regionId;

    #[ORM\Column(type: "integer")]
    private int $cityId;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $neighborhood = null;

    #[ORM\Column(type: "integer", default: 0)]
    private int $viewsCount = 0;

    #[ORM\Column(type: "integer", default: 0)]
    private int $favoritesCount = 0;

    #[ORM\Column(type: "datetime_immutable")]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: "datetime_immutable")]
    private \DateTimeImmutable $updatedAt;

    #[ORM\OneToMany(targetEntity: ListingImage::class, mappedBy: "listing", cascade: ["persist", "remove"])]
    private Collection $images;

    #[ORM\ManyToOne(targetEntity: "App\Entity\User")]
    #[ORM\JoinColumn(nullable: false)]
    private User $seller;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function setTitle(string $title): void { $this->title = $title; }
    public function getSlug(): string { return $this->slug; }
    public function setSlug(string $slug): void { $this->slug = $slug; }
    public function getDescription(): string { return $this->description; }
    public function setDescription(string $description): void { $this->description = $description; }
    public function getPrice(): string { return $this->price; }
    public function setPrice(string $price): void { $this->price = $price; }
    public function getCurrency(): string { return $this->currency; }
    public function getCondition(): string { return $this->condition; }
    public function setCondition(string $condition): void { $this->condition = $condition; }
    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): void { $this->status = $status; }
    public function getViewsCount(): int { return $this->viewsCount; }
    public function setViewsCount(int $viewsCount): void { $this->viewsCount = $viewsCount; }
    public function getFavoritesCount(): int { return $this->favoritesCount; }
    public function setFavoritesCount(int $favoritesCount): void { $this->favoritesCount = $favoritesCount; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }
    public function getImages(): Collection { return $this->images; }
    public function getSeller(): User { return $this->seller; }
    public function setSeller(User $seller): void { $this->seller = $seller; }
    public function getSellerId(): int { return $this->sellerId; }
    public function setSellerId(int $sellerId): void { $this->sellerId = $sellerId; }
    public function getCategoryId(): ?int { return $this->categoryId; }
    public function setCategoryId(?int $categoryId): void { $this->categoryId = $categoryId; }
    public function getSubcategoryId(): ?int { return $this->subcategoryId; }
    public function setSubcategoryId(?int $subcategoryId): void { $this->subcategoryId = $subcategoryId; }
    public function getRegionId(): int { return $this->regionId; }
    public function setRegionId(int $regionId): void { $this->regionId = $regionId; }
    public function getCityId(): int { return $this->cityId; }
    public function setCityId(int $cityId): void { $this->cityId = $cityId; }
    public function getNeighborhood(): ?string { return $this->neighborhood; }
    public function setNeighborhood(?string $neighborhood): void { $this->neighborhood = $neighborhood; }
}
