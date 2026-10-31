<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\AuthService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AuthController
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly EntityManagerInterface $em
    ) {}

    #[Route('/api/auth/register', name: 'api_auth_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['email']) || empty($data['password']) || empty($data['firstName'])) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => ['required' => ['email', 'password', 'firstName']],
            ], 422);
        }

        try {
            $user = $this->authService->register(
                $data['firstName'],
                $data['lastName'] ?? '',
                $data['email'],
                $data['phone'] ?? '',
                $data['password']
            );

            return new JsonResponse([
                'success' => true,
                'data' => [
                    'id' => $user->getId(),
                    'firstName' => $user->getFirstName(),
                    'email' => $user->getEmail(),
                    'role' => $user->getRole(),
                ],
                'message' => 'Account created successfully',
            ], 201);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Registration failed',
                'errors' => ['email' => ['Email already exists']],
            ], 409);
        }
    }

    #[Route('/api/auth/login', name: 'api_auth_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['email']) || empty($data['password'])) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Email and password are required',
            ], 400);
        }

        // In production, use JWT
        $user = $this->authService->verifyCredentials($data['email'], $data['password']);

        if (!$user) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        // Generate JWT token (simplified for demo)
        $token = $this->generateJWT($user);

        return new JsonResponse([
            'success' => true,
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->getId(),
                    'firstName' => $user->getFirstName(),
                    'lastName' => $user->getLastName(),
                    'email' => $user->getEmail(),
                    'phone' => $user->getPhone(),
                    'role' => $user->getRole(),
                    'isVerified' => $user->isVerified(),
                ],
            ],
            'message' => 'Login successful',
        ]);
    }

    #[Route('/api/auth/me', name: 'api_auth_me', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function me(Request $request): JsonResponse
    {
        $user = $request->getUser();
        return new JsonResponse([
            'success' => true,
            'data' => $user,
            'message' => 'User data retrieved',
        ]);
    }

    #[Route('/api/auth/logout', name: 'api_auth_logout', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function logout(): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    private function generateJWT(User $user): string
    {
        $payload = [
            'sub' => $user->getId(),
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
            'iat' => time(),
            'exp' => time() + 3600,
        ];
        return base64_encode(json_encode($payload));
    }
}
