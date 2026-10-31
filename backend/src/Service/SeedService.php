<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category;
use App\Entity\Region;
use App\Entity\City;
use App\Entity\User;
use App\Entity\Listing;
use App\Entity\ListingImage;
use App\Entity\Favorite;
use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\Report;
use App\Entity\Notification;

class SeedService
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function seedAll(): void
    {
        $this->seedCategories();
        $this->seedLocations();
        $this->seedUsers();
        $this->seedListings();
        $this->em->flush();
    }

    private function seedCategories(): void
    {
        $categories = [
            ['name' => 'Véhicules', 'slug' => 'vehicles', 'icon' => 'car', 'parentId' => null],
            ['name' => 'Voitures', 'slug' => 'voitures', 'icon' => 'car', 'parentId' => 1],
            ['name' => 'Motos', 'slug' => 'motos', 'icon' => 'motorcycle', 'parentId' => 1],
            ['name' => 'Camions', 'slug' => 'camions', 'icon' => 'truck', 'parentId' => 1],
            ['name' => 'Pièces automobiles', 'slug' => 'pieces-auto', 'icon' => 'wrench', 'parentId' => 1],
            ['name' => 'Immobilier', 'slug' => 'immobilier', 'icon' => 'home', 'parentId' => null],
            ['name' => 'Appartements', 'slug' => 'appartements', 'icon' => 'home', 'parentId' => 6],
            ['name' => 'Maisons', 'slug' => 'maisons', 'icon' => 'home', 'parentId' => 6],
            ['name' => 'Villas', 'slug' => 'villas', 'icon' => 'home', 'parentId' => 6],
            ['name' => 'Terrains', 'slug' => 'terrains', 'icon' => 'map', 'parentId' => 6],
            ['name' => 'Téléphones & Tablettes', 'slug' => 'telephones', 'icon' => 'smartphone', 'parentId' => null],
            ['name' => 'Smartphones', 'slug' => 'smartphones', 'icon' => 'smartphone', 'parentId' => 11],
            ['name' => 'iPhone', 'slug' => 'iphone', 'icon' => 'apple', 'parentId' => 11],
            ['name' => 'Samsung', 'slug' => 'samsung', 'icon' => 'android', 'parentId' => 11],
            ['name' => 'Informatique', 'slug' => 'informatique', 'icon' => 'laptop', 'parentId' => null],
            ['name' => 'PC portables', 'slug' => 'pc-portables', 'icon' => 'laptop', 'parentId' => 15],
            ['name' => 'PC de bureau', 'slug' => 'pc-bureau', 'icon' => 'desktop', 'parentId' => 15],
            ['name' => 'Composants', 'slug' => 'composants', 'icon' => 'chip', 'parentId' => 15],
            ['name' => 'Électronique', 'slug' => 'electronique', 'icon' => 'tv', 'parentId' => null],
            ['name' => 'TV', 'slug' => 'tv', 'icon' => 'tv', 'parentId' => 19],
            ['name' => 'Consoles', 'slug' => 'consoles', 'icon' => 'gamepad-2', 'parentId' => 19],
            ['name' => 'Maison', 'slug' => 'maison', 'icon' => 'shopping-bag', 'parentId' => null],
            ['name' => 'Meubles', 'slug' => 'meubles', 'icon' => 'sofa', 'parentId' => 22],
            ['name' => 'Électroménager', 'slug' => 'electromenager', 'icon' => 'refrigerator', 'parentId' => 22],
            ['name' => 'Mode', 'slug' => 'mode', 'icon' => 'shirt', 'parentId' => null],
            ['name' => 'Vêtements', 'slug' => 'vetements', 'icon' => 'shirt', 'parentId' => 25],
            ['name' => 'Chaussures', 'slug' => 'chaussures', 'icon' => 'shoe-prints', 'parentId' => 25],
            ['name' => 'Emploi & Services', 'slug' => 'emploi', 'icon' => 'briefcase', 'parentId' => null],
            ['name' => 'Loisirs', 'slug' => 'loisirs', 'icon' => 'gamepad-2', 'parentId' => null],
            ['name' => 'Animaux', 'slug' => 'animaux', 'icon' => 'paw-print', 'parentId' => null],
            ['name' => 'Autres', 'slug' => 'autres', 'icon' => 'ellipsis', 'parentId' => null],
        ];

        foreach ($categories as $catData) {
            $cat = new Category();
            $cat->setName($catData['name']);
            $cat->setSlug($catData['slug']);
            $cat->setIcon($catData['icon']);
            $cat->setParentId($catData['parentId']);
            $this->em->persist($cat);
        }
    }

    private function seedLocations(): void
    {
        $regions = [
            ['name' => 'Casablanca-Settat', 'slug' => 'casablanca-settat', 'code' => '06'],
            ['name' => 'Rabat-Salé-Kénitra', 'slug' => 'rabat-salie-kenitra', 'code' => '07'],
            ['name' => 'Marrakech-Safi', 'slug' => 'marrakech-safi', 'code' => '08'],
            ['name' => 'Fès-Meknès', 'slug' => 'fes-meknes', 'code' => '03'],
            ['name' => 'Tanger-Tétouan-Al Hoceïma', 'slug' => 'tanger-tetouan', 'code' => '01'],
            ['name' => 'Oriental', 'slug' => 'oriental', 'code' => '02'],
            ['name' => 'Chchaouia-Béni Mellal', 'slug' => 'chashti-beni-mellal', 'code' => '09'],
            ['name' => 'Drâa-Tafilalet', 'slug' => 'draa-tafilalet', 'code' => '10'],
            ['name' => 'Souss-Massa', 'slug' => 'souss-massa', 'code' => '11'],
        ];

        foreach ($regions as $regData) {
            $reg = new Region();
            $reg->setName($regData['name']);
            $reg->setSlug($regData['slug']);
            $reg->setCode($regData['code']);
            $this->em->persist($reg);
        }

        $cities = [
            ['name' => 'Casablanca', 'slug' => 'casablanca', 'regionCode' => '06'],
            ['name' => 'Mohammedia', 'slug' => 'mohammedia', 'regionCode' => '06'],
            ['name' => 'Rabat', 'slug' => 'rabat', 'regionCode' => '07'],
            ['name' => 'Salé', 'slug' => 'sale', 'regionCode' => '07'],
            ['name' => 'Marrakech', 'slug' => 'marrakech', 'regionCode' => '08'],
            ['name' => 'Fès', 'slug' => 'fes', 'regionCode' => '03'],
            ['name' => 'Meknès', 'slug' => 'meknes', 'regionCode' => '03'],
            ['name' => 'Tanger', 'slug' => 'tanger', 'regionCode' => '01'],
            ['name' => 'Tétouan', 'slug' => 'tetouan', 'regionCode' => '01'],
            ['name' => 'Agadir', 'slug' => 'agadir', 'regionCode' => '09'],
        ];

        foreach ($cities as $cityData) {
            $city = new City();
            $city->setName($cityData['name']);
            $city->setSlug($cityData['slug']);
            // Would need to set region via lookup
            $this->em->persist($city);
        }
    }

    private function seedUsers(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setFirstName(["Ahmed", "Fatima", "Mohamed", "Khadija", "Youssef"][$i - 1]);
            $user->setLastName(["Benali", "Hassan", "Ouazzani", "Lamrani", "Cherif"][$i - 1]);
            $user->setEmail(["ahmed@example.com", "fatima@example.com", "mohamed@example.com", "khadija@example.com", "youssef@example.com"][$i - 1]);
            $user->setPhone(["+212 6 12 34 56 78", "+212 6 23 45 67 89", "+212 6 34 56 78 90", "+212 6 45 67 89 01", "+212 6 56 78 90 12"][$i - 1]);
            $user->setPassword(password_hash("password123", PASSWORD_BCRYPT));
            $user->setRole($i === 1 ? "ADMIN" : "USER");
            $user->setIsVerified(true);
            $user->setCity(["Casablanca", "Rabat", "Marrakech", "Fès", "Tanger"][$i - 1]);
            $this->em->persist($user);
        }
    }

    private function seedListings(): void
    {
        $users = $this->em->getRepository(User::class)->findAll();
        $categories = $this->em->getRepository(Category::class)->findAll();
        $cities = $this->em->getRepository(City::class)->findAll();

        if (empty($users) || empty($categories) || empty($cities)) return;

        $titles = [
            "iPhone 15 Pro 256GB", "Samsung Galaxy S24 Ultra", "Appartement à Casablanca",
            "Voiture Renault Clio", "MacBook Pro M2", "Chaussures Nike Air Max",
            "Tablette iPad Air", "Meuble salon moderne", "Télévision 55\" 4K",
            "PC portable HP Pavilion", "Villa avec piscine", "Motos Honda CB 500",
        ];

        foreach ($titles as $i => $title) {
            $listing = new Listing();
            $listing->setTitle($title);
            $listing->setSlug(strtolower(str_replace(" ", "-", $title)));
            $listing->setDescription("Excellent état. " . $title . " en vente sur L9it.");
            $listing->setPrice([9500, 12000, 350000, 85000, 15000, 800, 4500, 12000, 6500, 8000, 1200000, 65000][$i]);
            $listing->setCondition(["Neuf", "Comme neuf", "Bon état", "Bon état", "Comme neuf", "Bon état", "Comme neuf", "Bon état", "Comme neuf", "Bon état", "Comme neuf", "Bon état"][$i]);
            $listing->setStatus("published");
            $listing->setSeller($users[array_rand($users)]);
            $listing->setSellerId($listing->getSeller()->getId());
            $listing->setCategoryId($categories[array_rand($categories)]->getId());
            $listing->setRegionId(1);
            $listing->setCityId($cities[array_rand($cities)]->getId());
            $listing->setNeighborhood("Centre");
            $listing->setViewsCount(rand(10, 500));
            $listing->setFavoritesCount(rand(0, 50));
            $this->em->persist($listing);

            // Add images
            for ($j = 0; $j < 3; $j++) {
                $image = new ListingImage();
                $image->setUrl("https://picsum.photos/seed/listing{$i}-{$j}/800/600");
                $image->setIsCover($j === 0);
                $image->setOrder($j);
                $image->setListing($listing);
                $this->em->persist($image);
            }
        }
    }
}
