<?php

namespace App\Service;

use App\Entity\Listing;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\PasswordHasher\PasswordHasherInterface;

class ListingService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PasswordHasherInterface $passwordHasher
    ) {}

    public function findAllFiltered(array $filters): array
    {
        $repo = $this->em->getRepository(Listing::class);
        $qb = $repo->createQueryBuilder('l')
            ->where('l.status = :status')
            ->setParameter('status', 'published');

        if (!empty($filters['keyword'])) {
            $qb->andWhere('l.title LIKE :keyword')
                ->setParameter('keyword', '%' . $filters['keyword'] . '%');
        }
        if (!empty($filters['category'])) {
            $qb->andWhere('l.categoryId = :category')
                ->setParameter('category', $filters['category']);
        }
        if (!empty($filters['city'])) {
            $qb->andWhere('l.cityId = :city')
                ->setParameter('city', $filters['city']);
        }
        if (!empty($filters['minPrice'])) {
            $qb->andWhere('l.price >= :minPrice')
                ->setParameter('minPrice', $filters['minPrice']);
        }
        if (!empty($filters['maxPrice'])) {
            $qb->andWhere('l.price <= :maxPrice')
                ->setParameter('maxPrice', $filters['maxPrice']);
        }

        $sort = $filters['sort'] ?? 'recent';
        switch ($sort) {
            case 'price_asc': $qb->orderBy('l.price', 'ASC'); break;
            case 'price_desc': $qb->orderBy('l.price', 'DESC'); break;
            case 'views': $qb->orderBy('l.viewsCount', 'DESC'); break;
            default: $qb->orderBy('l.createdAt', 'DESC'); break;
        }

        $page = $filters['page'] ?? 1;
        $perPage = 20;
        $total = count($qb->getQuery()->getResult());
        $qb->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);

        return [
            'data' => $qb->getQuery()->getResult(),
            'meta' => [
                'currentPage' => $page,
                'pageSize' => $perPage,
                'totalItems' => $total,
                'totalPages' => (int)ceil($total / $perPage),
                'hasNextPage' => $page * $perPage < $total,
                'hasPreviousPage' => $page > 1,
            ],
        ];
    }

    public function findBySlug(string $slug): ?Listing
    {
        return $this->em->getRepository(Listing::class)->findOneBy(['slug' => $slug]);
    }

    public function find(int $id): ?Listing
    {
        return $this->em->getRepository(Listing::class)->find($id);
    }

    public function create(array $data, User $user): Listing
    {
        $listing = new Listing();
        $listing->setTitle($data['title']);
        $listing->setSlug($this->generateSlug($data['title']));
        $listing->setDescription($data['description']);
        $listing->setPrice($data['price']);
        $listing->setCondition($data['condition'] ?? 'Neuf');
        $listing->setSeller($user);
        $listing->setSellerId($user->getId());
        $listing->setCategoryId($data['categoryId'] ?? null);
        $listing->setRegionId($data['regionId'] ?? 0);
        $listing->setCityId($data['cityId'] ?? 0);
        $listing->setNeighborhood($data['neighborhood'] ?? null);
        $listing->setStatus('pending');

        $this->em->persist($listing);
        $this->em->flush();

        return $listing;
    }

    public function update(Listing $listing, array $data): Listing
    {
        if (isset($data['title'])) $listing->setTitle($data['title']);
        if (isset($data['description'])) $listing->setDescription($data['description']);
        if (isset($data['price'])) $listing->setPrice($data['price']);
        if (isset($data['condition'])) $listing->setCondition($data['condition']);
        if (isset($data['regionId'])) $listing->setRegionId($data['regionId']);
        if (isset($data['cityId'])) $listing->setCityId($data['cityId']);
        if (isset($data['neighborhood'])) $listing->setNeighborhood($data['neighborhood']);

        $listing->setUpdatedAt(new \DateTimeImmutable());
        $this->em->flush();

        return $listing;
    }

    public function delete(Listing $listing): void
    {
        $this->em->remove($listing);
        $this->em->flush();
    }

    private function generateSlug(string $title): string
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
    }
}
