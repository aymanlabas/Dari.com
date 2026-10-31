<?php

namespace App\Service;

use App\Entity\Listing;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ReportService
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function createReport(int $listingId, string $reason, string $description, User $user): array
    {
        $listing = $this->em->getRepository(Listing::class)->find($listingId);

        if (!$listing) {
            return ['success' => false, 'message' => 'Listing not found'];
        }

        $report = new \App\Entity\Report();
        $report->setListing($listing);
        $report->setReason($reason);
        $report->setDescription($description);
        $report->setStatus('pending');

        $this->em->persist($report);
        $this->em->flush();

        return ['success' => true, 'message' => 'Report submitted successfully'];
    }
}
