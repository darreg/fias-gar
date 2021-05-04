<?php

namespace App\Service;

use App\DTO\ApiTokenDTO;
use App\Entity\ApiToken;
use App\Manager\ApiTokenManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ApiTokenService
{
    private ApiTokenManager $apiTokenManager;
    private FormFactoryInterface $formFactory;
    private NormalizerInterface $normalizer;

    public function __construct(
        ApiTokenManager $apiTokenManager,
        FormFactoryInterface $formFactory,
        NormalizerInterface $normalizer
    ) {
        $this->apiTokenManager = $apiTokenManager;
        $this->formFactory = $formFactory;
        $this->normalizer = $normalizer;
    }

    /**
     * @param class-string<FormTypeInterface> $className
     */
    public function createForm(string $className, ?ApiToken $apiToken = null): FormInterface
    {
        if ($apiToken === null) {
            return $this->formFactory->create($className);
        }

        /** @var array<string, mixed> $apiTokenArray */
        $apiTokenArray = $this->normalizer->normalize($apiToken);

        $apiTokenDto = ApiTokenDTO::fromArray($apiTokenArray);

        return $this->formFactory->create($className, $apiTokenDto);
    }

    public function getOne(int $id): ?ApiToken
    {
        return $this->apiTokenManager->getOne($id);
    }

    /**
     * @return ApiToken[]
     *
     * @psalm-return array<int, ApiToken>
     */
    public function getAll(?int $limit = null, ?int $offset = null): array
    {
        return $this->apiTokenManager->getAll($limit, $offset);
    }

    public function add(ApiTokenDTO $apiTokenDto): ?int
    {
        $apiToken = $this->apiTokenManager->add($apiTokenDto);
        if ($apiToken === null) {
            return null;
        }

        return $apiToken->getId();
    }

    public function updateById(
        int $id,
        ApiTokenDTO $apiTokenDto
    ): bool {

        $apiToken = $this->apiTokenManager->getOne($id);
        if ($apiToken === null) {
            return false;
        }

        return $this->update(
            $apiToken,
            $apiTokenDto
        );
    }

    public function update(
        ApiToken $apiToken,
        ApiTokenDTO $apiTokenDto
    ): bool {
        $apiToken = $this->apiTokenManager->update(
            $apiToken,
            $apiTokenDto
        );
        return !($apiToken === null);
    }

    public function deleteById(int $id): bool
    {
        return $this->apiTokenManager->deleteById($id);
    }
}
