<?php

namespace App\Service;

use App\Manager\UserManager;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class JwtTokenService
{
    private UserManager $userManager;
    private UserPasswordEncoderInterface $passwordEncoder;
    private JWTEncoderInterface $jwtEncoder;
    private int $tokenTTL;

    public function __construct(
        UserManager $userManager,
        UserPasswordEncoderInterface $passwordEncoder,
        JWTEncoderInterface $jwtEncoder,
        int $tokenTTL
    ) {
        $this->userManager = $userManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->jwtEncoder = $jwtEncoder;
        $this->tokenTTL = $tokenTTL;
    }

    public function isCredentialsValid(string $username, string $password): bool
    {
        $user = $this->userManager->getOneByUsername($username);
        if ($user === null) {
            return false;
        }

        return $this->passwordEncoder->isPasswordValid($user, $password);
    }

    public function getToken(string $username): string
    {
        $user = $this->userManager->getOneByUsername($username);
        if ($user === null) {
            throw new \LogicException('Incorrect username');
        }

        $tokenData = [
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
            'exp' => time() + $this->tokenTTL,
        ];

        return $this->jwtEncoder->encode($tokenData);
    }
}
