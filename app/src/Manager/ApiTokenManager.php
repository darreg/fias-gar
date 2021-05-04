<?php

namespace App\Manager;

use App\DAO\ApiTokenDAO;
use App\DTO\ApiTokenDTO;
use App\Entity\ApiToken;
use App\Repository\ApiTokenRepository;
use App\Repository\UserRepository;

class ApiTokenManager
{
    private ApiTokenDAO $ApiTokenDao;
    private ApiTokenRepository $ApiTokenRepository;
    private UserRepository $userRepository;

    public function __construct(
        ApiTokenDAO $ApiTokenDao,
        ApiTokenRepository $ApiTokenRepository,
        UserRepository $userRepository
    ) {
        $this->ApiTokenDao = $ApiTokenDao;
        $this->ApiTokenRepository = $ApiTokenRepository;
        $this->userRepository = $userRepository;
    }

    public function getOne(int $id): ?ApiToken
    {
        return $this->ApiTokenRepository->find($id);
    }

    /**
     * @return ApiToken[]
     *
     * @psalm-return array<int, ApiToken>
     */
    public function getAll(?int $limit = null, ?int $offset = null): array
    {
        return $this->ApiTokenRepository->findBy([], null, $limit, $offset);
    }

    public function add(ApiTokenDTO $ApiTokenDto): ?ApiToken
    {
        $user = $this->userRepository->find($ApiTokenDto->userId);

        $ApiToken = (new ApiToken())
            ->setName($ApiTokenDto->name)
            ->setToken($ApiTokenDto->token)
            ->setExpiresAt($ApiTokenDto->expiresAt)
            ->setStatus($ApiTokenDto->status)
            ->setUser($user);

        try {
            $this->ApiTokenDao->create($ApiToken);
        } catch (\Exception $e) {
            //TODO log
            return null;
        }

        return $ApiToken;
    }

    public function updateById(
        int $id,
        ApiTokenDTO $ApiTokenDto
    ): ?ApiToken {

        $ApiToken = $this->ApiTokenRepository->find($id);
        if ($ApiToken === null) {
            return null;
        }

        return $this->update(
            $ApiToken,
            $ApiTokenDto
        );
    }

    public function update(
        ApiToken $ApiToken,
        ApiTokenDTO $ApiTokenDto
    ): ?ApiToken {

        $user = $this->userRepository->find($ApiTokenDto->userId);

        $ApiToken
            ->setName($ApiTokenDto->name)
            ->setToken($ApiTokenDto->token)
            ->setExpiresAt($ApiTokenDto->expiresAt)
            ->setStatus($ApiTokenDto->status)
            ->setUser($user);

        try {
            $this->ApiTokenDao->update($ApiToken);
        } catch (\Exception $e) {
            //TODO log
            return null;
        }

        return $ApiToken;
    }

    public function deleteById(int $id): bool
    {
        $exHouse = $this->ApiTokenRepository->find($id);
        if ($exHouse === null) {
            return false;
        }

        try {
            $this->ApiTokenDao->delete($exHouse);
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
    }
}
