<?php

namespace App\Service;

use App\Entity\Listing;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class FavoriteService
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function addFavorite(User $user, Listing $listing): void
    {
        $existing = $this->em->getRepository(\App\Entity\Favorite::class)
            ->findOneBy(['userId' => $user->getId(), 'listingId' => $listing->getId()]);

        if ($existing) {
            return;
        }

        $favorite = new \App\Entity\Favorite();
        $favorite->setUser($user);
        $favorite->setListing($listing);

        $listing->setFavoritesCount($listing->getFavoritesCount() + 1);
        $this->em->persist($favorite);
        $this->em->flush();
    }

    public function removeFavorite(User $user, Listing $listing): void
    {
        $favorite = $this->em->getRepository(\App\Entity\Favorite::class)
            ->findOneBy(['userId' => $user->getId(), 'listingId' => $listing->getId()]);

        if ($favorite) {
            $listing->setFavoritesCount(max(0, $listing->getFavoritesCount() - 1));
            $this->em->remove($favorite);
            $this->em->flush();
        }
    }
}
