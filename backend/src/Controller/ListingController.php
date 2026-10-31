<?php

namespace App\Controller;

use App\Entity\Listing;
use App\Entity\User;
use App\Service\ListingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ListingController
{
    public function __construct(
        private readonly ListingService $listingService,
        private readonly EntityManagerInterface $em
    ) {}

    #[Route('/api/listings', name: 'api_listings', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $filters = [
            'keyword' => $request->query->get('keyword'),
            'category' => $request->query->get('category'),
            'city' => $request->query->get('city'),
            'region' => $request->query->get('region'),
            'minPrice' => $request->query->get('minPrice'),
            'maxPrice' => $request->query->get('maxPrice'),
            'condition' => $request->query->get('condition'),
            'sort' => $request->query->get('sort', 'recent'),
            'page' => (int)($request->query->get('page', 1)),
        ];

        $result = $this->listingService->findAllFiltered($filters);

        return JsonResponse::fromJsonString(json_encode([
            'success' => true,
            'data' => $result['data'],
            'message' => 'Listings retrieved successfully',
            'meta' => $result['meta'],
        ]));
    }

    #[Route('/api/listings/{slug}', name: 'api_listing_detail', methods: ['GET'])]
    public function detail(string $slug): JsonResponse
    {
        $listing = $this->listingService->findBySlug($slug);

        if (!$listing) {
            return JsonResponse::fromJsonString(json_encode([
                'success' => false,
                'message' => 'Listing not found',
            ]), 404);
        }

        return JsonResponse::fromJsonString(json_encode([
            'success' => true,
            'data' => $listing,
            'message' => 'Listing retrieved successfully',
        ]));
    }

    #[Route('/api/listings', name: 'api_listings_create', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request): JsonResponse
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        $listing = $this->listingService->create($data, $user);

        return JsonResponse::fromJsonString(json_encode([
            'success' => true,
            'data' => $listing,
            'message' => 'Listing created successfully',
        ]), 201);
    }

    #[Route('/api/listings/{id}', name: 'api_listings_update', methods: ['PUT'])]
    #[IsGranted('ROLE_USER')]
    public function update(int $id, Request $request): JsonResponse
    {
        $user = $this->getUser();
        $listing = $this->listingService->find($id);

        if (!$listing || ($listing->getSellerId() !== $user->getId() && !$this->isGranted('ROLE_ADMIN'))) {
            return JsonResponse::fromJsonString(json_encode([
                'success' => false,
                'message' => 'Access denied',
            ]), 403);
        }

        $data = json_decode($request->getContent(), true);
        $listing = $this->listingService->update($listing, $data);

        return JsonResponse::fromJsonString(json_encode([
            'success' => true,
            'data' => $listing,
            'message' => 'Listing updated successfully',
        ]));
    }

    #[Route('/api/listings/{id}', name: 'api_listings_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function delete(int $id): JsonResponse
    {
        $user = $this->getUser();
        $listing = $this->listingService->find($id);

        if (!$listing || ($listing->getSellerId() !== $user->getId() && !$this->isGranted('ROLE_ADMIN'))) {
            return JsonResponse::fromJsonString(json_encode([
                'success' => false,
                'message' => 'Access denied',
            ]), 403);
        }

        $this->listingService->delete($listing);

        return JsonResponse::fromJsonString(json_encode([
            'success' => true,
            'message' => 'Listing deleted successfully',
        ]));
    }
}
