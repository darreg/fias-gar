<?php

namespace App\Manager;

use App\DAO\ApiTokenDAO;
use App\DTO\ApiTokenDTO;
use App\DTO\ApiTokenNewDTO;
use App\Entity\ApiToken;
use App\Repository\ApiTokenRepository;
use App\Repository\UserRepository;

class ApiTokenManager
{
    private ApiTokenDAO $apiTokenDao;
    private ApiTokenRepository $apiTokenRepository;
    private UserRepository $userRepository;

    public function __construct(
        ApiTokenDAO $apiTokenDao,
        ApiTokenRepository $apiTokenRepository,
        UserRepository $userRepository
    ) {
        $this->apiTokenDao = $apiTokenDao;
        $this->apiTokenRepository = $apiTokenRepository;
        $this->userRepository = $userRepository;
    }

    public function getOne(int $id): ?ApiToken
    {
        return $this->apiTokenRepository->find($id);
    }

    /**
     * @return ApiToken[]
     *
     * @psalm-return array<int, ApiToken>
     */
    public function getAll(?int $limit = null, ?int $offset = null): array
    {
        return $this->apiTokenRepository->findBy([], null, $limit, $offset);
    }

    public function add(ApiTokenNewDTO $apiTokenDto): ?ApiToken
    {
        $user = $this->userRepository->find($apiTokenDto->user);

        $apiToken = (new ApiToken())
            ->setName($apiTokenDto->name)
            ->setExpiresAt(\DateTime::createFromFormat('Y-m-d H:i', $apiTokenDto->expiresAt))
            ->setStatus($apiTokenDto->status)
            ->setUser($user);

        if ($apiTokenDto->token !== null) {
            $apiToken->setToken($apiTokenDto->token);
        }

        try {
            $this->apiTokenDao->create($apiToken);
        } catch (\Exception $e) {
            //TODO log
            return null;
        }

        return $apiToken;
    }

    public function updateById(
        int $id,
        ApiTokenDTO $apiTokenDto
    ): ?ApiToken {

        $apiToken = $this->apiTokenRepository->find($id);
        if ($apiToken === null) {
            return null;
        }

        return $this->update(
            $apiToken,
            $apiTokenDto
        );
    }

    public function update(
        ApiToken $apiToken,
        ApiTokenDTO $apiTokenDto
    ): ?ApiToken {

        $user = $this->userRepository->find($apiTokenDto->user);

        $apiToken
            ->setName($apiTokenDto->name)
            ->setToken($apiTokenDto->token)
            ->setExpiresAt(\DateTime::createFromFormat('Y-m-d H:i', $apiTokenDto->expiresAt))
            ->setStatus($apiTokenDto->status)
            ->setUser($user);

        try {
            $this->apiTokenDao->update($apiToken);
        } catch (\Exception $e) {
            //TODO log
            return null;
        }

        return $apiToken;
    }

    public function deleteById(int $id): bool
    {
        $apiToken = $this->apiTokenRepository->find($id);
        if ($apiToken === null) {
            return false;
        }

        try {
            $this->apiTokenDao->delete($apiToken);
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
    }
}
