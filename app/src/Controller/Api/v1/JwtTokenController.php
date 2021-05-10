<?php

namespace App\Controller\Api\v1;

use App\Service\JwtTokenService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/jwt-token")
 */
class JwtTokenController
{
    private JwtTokenService $jwtTokenService;

    public function __construct(
        JwtTokenService $jwtTokenService
    ) {
        $this->jwtTokenService = $jwtTokenService;
    }

    /**
     * @Route("", methods={"POST"})
     */
    public function getToken(Request $request): JsonResponse
    {
        $user = $request->getUser();
        $password = $request->getPassword();

        if (!$user || !$password) {
            return new JsonResponse(['message' => 'Authorization required'], Response::HTTP_UNAUTHORIZED);
        }

        if (!$this->jwtTokenService->isCredentialsValid($user, $password)) {
            return new JsonResponse(['message' => 'Invalid password or username'], Response::HTTP_FORBIDDEN);
        }

        return new JsonResponse(['token' => $this->jwtTokenService->getToken($user)]);
    }
}
