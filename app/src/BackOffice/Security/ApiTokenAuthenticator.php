<?php

namespace App\BackOffice\Security;

use App\BackOffice\Repository\ApiTokenRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiTokenAuthenticator extends AbstractGuardAuthenticator
{

    private ApiTokenRepository $apiTokenRepository;

    public function __construct(ApiTokenRepository $apiTokenRepository)
    {
        $this->apiTokenRepository = $apiTokenRepository;
    }

    public function start(Request $request, AuthenticationException $authException = null): JsonResponse
    {
        return new JsonResponse(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

    public function supports(Request $request): bool
    {
        if (!$request->headers->has('Authorization')) {
            return false;
        }
        $authorizationHeader = $request->headers->get('Authorization');

        return strncmp($authorizationHeader ?? '', 'Bearer ', 7) === 0;
    }

    public function getCredentials(Request $request): string
    {
        $authorizationHeader = $request->headers->get('Authorization');
        return substr($authorizationHeader ?? '', 7);
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        $token = $this->apiTokenRepository->findOneBy([
            'token' => $credentials,
            'status' => true
        ]);

        if (!$token) {
            throw new CustomUserMessageAuthenticationException(
                'Invalid API Token'
            );
        }

        if ($token->isExpired()) {
            throw new CustomUserMessageAuthenticationException(
                'Token expired'
            );
        }

        $user = $token->getUser();
        if ($user === null) {
            throw new CustomUserMessageAuthenticationException(
                'Invalid API Token'
            );
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        return new JsonResponse(['message' => $exception->getMessageKey()], Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): void
    {
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
