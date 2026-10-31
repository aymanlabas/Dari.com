<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "notifications")]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "integer")]
    private int $userId;

    #[ORM\Column(type: "string", length: 50)]
    private string $type;

    #[ORM\Column(type: "string", length: 255)]
    private string $title;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $body = null;

    #[ORM\Column(type: "boolean")]
    private bool $isRead = false;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $actionUrl = null;

    #[ORM\Column(type: "datetime_immutable")]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function setUserId(int $userId): void { $this->userId = $userId; }
    public function getType(): string { return $this->type; }
    public function setType(string $type): void { $this->type = $type; }
    public function getTitle(): string { return $this->title; }
    public function setTitle(string $title): void { $this->title = $title; }
    public function getBody(): ?string { return $this->body; }
    public function setBody(?string $body): void { $this->body = $body; }
    public function isRead(): bool { return $this->isRead; }
    public function setIsRead(bool $isRead): void { $this->isRead = $isRead; }
    public function getActionUrl(): ?string { return $this->actionUrl; }
    public function setActionUrl(?string $actionUrl): void { $this->actionUrl = $actionUrl; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
}
