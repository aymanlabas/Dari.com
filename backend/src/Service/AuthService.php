<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\PasswordHasher\PasswordHasherInterface;

class AuthService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PasswordHasherInterface $passwordHasher
    ) {}

    public function register(
        string $firstName,
        string $lastName,
        string $email,
        string $phone,
        string $password
    ): User {
        // Check if email exists
        $existing = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existing) {
            throw new \RuntimeException('Email already registered');
        }

        $user = new User();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setEmail($email);
        $user->setPhone($phone);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function verifyCredentials(string $email, string $password): ?User
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        if (!$user || $user->isBlocked()) {
            return null;
        }

        if ($this->passwordHasher->isPasswordValid($user, $password)) {
            return $user;
        }

        return null;
    }

    public function findUserById(int $id): ?User
    {
        return $this->em->getRepository(User::class)->find($id);
    }
}
