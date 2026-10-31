<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JwtAuthenticator extends AbstractGuardAuthenticator
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization');
    }

    public function getCredentials(Request $request): ?array
    {
        $header = $request->headers->get('Authorization');
        if (!$header || !str_starts_with($header, 'Bearer ')) {
            return null;
        }
        return ['token' => substr($header, 7)];
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        $token = $credentials['token'];
        return $this->em->getRepository(User::class)->findOneBy(['apiToken' => $token]);
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?JsonResponse
    {
        return new JsonResponse(['success' => false, 'message' => 'Invalid token'], 401);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?JsonResponse
    {
        return null;
    }

    public function start(Request $request, AuthenticationException $authException = null): ?JsonResponse
    {
        return new JsonResponse(['success' => false, 'message' => 'Authentication required'], 401);
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
