<?php

namespace App\Controller;

use App\Service\SeedService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SeedController
{
    public function __construct(private readonly SeedService $seedService) {}

    #[Route('/api/seed', name: 'api_seed', methods: ['POST'])]
    public function seed(): JsonResponse
    {
        try {
            $this->seedService->seedAll();
            return new JsonResponse(['success' => true, 'message' => 'Seed data created successfully']);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
