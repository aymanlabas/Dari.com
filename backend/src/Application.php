<?php

namespace App;

use App\Service\ListingService;
use App\Service\AuthService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class Application
{
    private EntityManagerInterface $entityManager;
    private AuthService $authService;
    private ListingService $listingService;

    public function __construct()
    {
        $config = Setup::createAnnotationMetadataConfiguration(
            [__DIR__ . '/../src/Entity'],
            true,
            null,
            null,
            false
        );

        $connectionParams = [
            'driver' => 'pdo_mysql',
            'host' => $_ENV['DB_HOST'] ?? 'localhost',
            'port' => $_ENV['DB_PORT'] ?? 3306,
            'dbname' => $_ENV['DB_DATABASE'] ?? 'l9it',
            'user' => $_ENV['DB_USERNAME'] ?? 'l9it',
            'password' => $_ENV['DB_PASSWORD'] ?? 'l9it',
            'charset' => 'utf8mb4',
        ];

        $this->entityManager = EntityManager::create($connectionParams, $config);
    }

    public function run(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // Remove base path
        $path = preg_replace('#^/api#', '', $path);
        $path = trim($path, '/');

        try {
            $this->handleRequest($path, $method);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Une erreur est survenue']);
        }
    }

    private function handleRequest(string $path, string $method): void
    {
        $parts = explode('/', $path);

        switch ($path) {
            // Auth
            case 'auth/register':
                if ($method === 'POST') {
                    $this->handleRegister();
                }
                break;

            case 'auth/login':
                if ($method === 'POST') {
                    $this->handleLogin();
                }
                break;

            // Listings
            case 'listings':
                if ($method === 'GET') {
                    $this->handleListings();
                } elseif ($method === 'POST') {
                    $this->handleCreateListing();
                }
                break;

            // Categories
            case 'categories':
                if ($method === 'GET') {
                    $this->handleCategories();
                }
                break;

            // Locations
            case 'locations':
                if ($method === 'GET') {
                    $this->handleLocations();
                }
                break;

            // Favorites
            case 'favorites':
                if ($method === 'POST') {
                    $this->handleFavorite();
                } elseif ($method === 'DELETE') {
                    $this->handleUnfavorite();
                }
                break;

            // Default 404
            default:
                if (preg_match('#^listings/([^/]+)$#', $path, $matches)) {
                    if ($method === 'GET') {
                        $this->handleListingDetail($matches[1]);
                    }
                } else {
                    http_response_code(404);
                    echo json_encode(['success' => false, 'message' => 'Endpoint not found']);
                }
                break;
        }
    }

    private function handleRegister(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        // In production, validate with Symfony Validator
        echo json_encode(['success' => true, 'message' => 'Registration endpoint ready']);
    }

    private function handleLogin(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode(['success' => true, 'token' => 'jwt-token-here', 'message' => 'Login endpoint ready']);
    }

    private function handleListings(): void
    {
        echo json_encode([
            'success' => true,
            'data' => [],
            'message' => 'Listings endpoint ready',
            'meta' => ['totalItems' => 0, 'totalPages' => 0, 'currentPage' => 1]
        ]);
    }

    private function handleListingDetail(string $slug): void
    {
        echo json_encode(['success' => true, 'data' => null, 'message' => 'Listing not found']);
    }

    private function handleCreateListing(): void
    {
        echo json_encode(['success' => true, 'message' => 'Create listing endpoint ready']);
    }

    private function handleCategories(): void
    {
        echo json_encode([
            'success' => true,
            'data' => [],
            'message' => 'Categories endpoint ready'
        ]);
    }

    private function handleLocations(): void
    {
        echo json_encode([
            'success' => true,
            'data' => ['regions' => [], 'cities' => []],
            'message' => 'Locations endpoint ready'
        ]);
    }

    private function handleFavorite(): void
    {
        echo json_encode(['success' => true, 'message' => 'Favorite endpoint ready']);
    }

    private function handleUnfavorite(): void
    {
        echo json_encode(['success' => true, 'message' => 'Unfavorite endpoint ready']);
    }
}
