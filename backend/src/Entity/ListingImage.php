<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "listing_images")]
class ListingImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $url;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $thumbnailUrl = null;

    #[ORM\Column(type: "boolean")]
    private bool $isCover = false;

    #[ORM\Column(type: "integer")]
    private int $order = 0;

    #[ORM\ManyToOne(targetEntity: Listing::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Listing $listing;

    public function getId(): ?int { return $this->id; }
    public function getUrl(): string { return $this->url; }
    public function setUrl(string $url): void { $this->url = $url; }
    public function getThumbnailUrl(): ?string { return $this->thumbnailUrl; }
    public function setThumbnailUrl(?string $thumbnailUrl): void { $this->thumbnailUrl = $thumbnailUrl; }
    public function isCover(): bool { return $this->isCover; }
    public function setIsCover(bool $isCover): void { $this->isCover = $isCover; }
    public function getOrder(): int { return $this->order; }
    public function setOrder(int $order): void { $this->order = $order; }
    public function getListing(): Listing { return $this->listing; }
    public function setListing(Listing $listing): void { $this->listing = $listing; }
}
