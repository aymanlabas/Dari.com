<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function notify(int $userId, string $type, string $title, string $body, ?string $actionUrl = null): void
    {
        $notification = new \App\Entity\Notification();
        $notification->setUserId($userId);
        $notification->setType($type);
        $notification->setTitle($title);
        $notification->setBody($body);
        $notification->setActionUrl($actionUrl);

        $this->em->persist($notification);
        $this->em->flush();
    }
}
