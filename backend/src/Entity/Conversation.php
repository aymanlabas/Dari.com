<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "conversations")]
class Conversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "integer")]
    private int $listingId;

    #[ORM\Column(type: "integer")]
    private int $buyerId;

    #[ORM\Column(type: "integer")]
    private int $sellerId;

    #[ORM\Column(type: "datetime_immutable")]
    private \DateTimeImmutable $updatedAt;

    public function __construct()
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getListingId(): int { return $this->listingId; }
    public function setListingId(int $listingId): void { $this->listingId = $listingId; }
    public function getBuyerId(): int { return $this->buyerId; }
    public function setBuyerId(int $buyerId): void { $this->buyerId = $buyerId; }
    public function getSellerId(): int { return $this->sellerId; }
    public function setSellerId(int $sellerId): void { $this->sellerId = $sellerId; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }
}

#[ORM\Entity]
#[ORM\Table(name: "messages")]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "integer")]
    private int $conversationId;

    #[ORM\Column(type: "integer")]
    private int $senderId;

    #[ORM\Column(type: "text")]
    private string $content;

    #[ORM\Column(type: "boolean")]
    private bool $isRead = false;

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    private ?\DateTimeImmutable $readAt = null;

    #[ORM\Column(type: "datetime_immutable")]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getConversationId(): int { return $this->conversationId; }
    public function setConversationId(int $conversationId): void { $this->conversationId = $conversationId; }
    public function getSenderId(): int { return $this->senderId; }
    public function setSenderId(int $senderId): void { $this->senderId = $senderId; }
    public function getContent(): string { return $this->content; }
    public function setContent(string $content): void { $this->content = $content; }
    public function isRead(): bool { return $this->isRead; }
    public function setIsRead(bool $isRead): void { $this->isRead = $isRead; }
    public function getReadAt(): ?\DateTimeImmutable { return $this->readAt; }
    public function setReadAt(?\DateTimeImmutable $readAt): void { $this->readAt = $readAt; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
}
