<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JwtTokenAuthenticator extends AbstractGuardAuthenticator
{
    private JWTEncoderInterface $jwtEncoder;

    public function __construct(
        JWTEncoderInterface $jwtEncoder
    ) {
        $this->jwtEncoder = $jwtEncoder;
    }

    public function start(Request $request, AuthenticationException $authException = null): JsonResponse
    {
        return new JsonResponse(['message' => 'Authentication required'], Response::HTTP_UNAUTHORIZED);
    }

    public function supports(Request $request): bool
    {
        if (!$request->headers->has('Authorization')) {
            return false;
        }
        $authorizationHeader = $request->headers->get('Authorization');

        return strncmp($authorizationHeader ?? '', 'Bearer ', 7) === 0;
    }

    public function getCredentials(Request $request): ?array
    {
        $extractor = new AuthorizationHeaderTokenExtractor('Bearer', 'Authorization');
        $token = $extractor->extract($request);
        if ($token === false) {
            throw new CustomUserMessageAuthenticationException('Invalid JWT Token');
        }

        try {
            $credentials = $this->jwtEncoder->decode($token);
        } catch (JWTDecodeFailureException $e) {
            throw new CustomUserMessageAuthenticationException('Invalid JWT Token');
        }

        return $credentials;
    }

    /**
     * @param array{username: string, roles: array<int, string>} $credentials
     */
    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        return empty($credentials['username']) ? null : new JwtUser($credentials);
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return !empty($credentials['username']);
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
