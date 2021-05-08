<?php

namespace App\Service;

use App\DTO\ApiTokenDTO;
use App\DTO\ApiTokenNewDTO;
use App\DTO\ConstructFromArrayInterface;
use App\Entity\Admin;
use App\Entity\ApiToken;
use App\Manager\ApiTokenManager;
use App\Manager\FormManager;
use App\Serializer\ApiTokenNormalizer;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ApiTokenService
{
    private ApiTokenManager $apiTokenManager;
    private FormManager $formManager;
    private NormalizerInterface $normalizer;

    public function __construct(
        ApiTokenManager $apiTokenManager,
        FormManager $formManager,
        ApiTokenNormalizer $normalizer
    ) {
        $this->apiTokenManager = $apiTokenManager;
        $this->formManager = $formManager;
        $this->normalizer = $normalizer;
    }

    /**
     * @param class-string<FormTypeInterface> $className
     * @param class-string<ConstructFromArrayInterface> $dtoClassName
     */
    public function createForm(string $className, string $dtoClassName, ?ApiToken $apiToken = null): FormInterface
    {

        $data = ($apiToken !== null) ? $this->normalizer->normalize($apiToken) : [];
        if (!\is_array($data)) {
            throw new \LogicException('Не удалось нормализовать объект');
        }

        return $this->formManager->createForDto($className, $dtoClassName, $data);
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

    public function add(ApiTokenNewDTO $apiTokenDto): ?int
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
