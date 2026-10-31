<?php

namespace App\Repository;

use App\Entity\Listing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ListingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Listing::class);
    }

    public function findPublishedOrdered(string $sort = 'recent', int $limit = 20, int $offset = 0): array
    {
        $qb = $this->createQueryBuilder('l')
            ->where('l.status = :status')
            ->setParameter('status', 'published');

        match ($sort) {
            'price_asc' => $qb->orderBy('l.price', 'ASC'),
            'price_desc' => $qb->orderBy('l.price', 'DESC'),
            'views' => $qb->orderBy('l.viewsCount', 'DESC'),
            default => $qb->orderBy('l.createdAt', 'DESC'),
        };

        return $qb->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function findBySlug(string $slug): ?Listing
    {
        return $this->findOneBy(['slug' => $slug]);
    }
}
