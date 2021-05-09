<?php

namespace App\Service;

use App\DTO\ConstructFromArrayInterface;
use App\DTO\ExtHouseDTO;
use App\Entity\ExtHouse;
use App\Manager\ExtHouseManager;
use App\Manager\FormManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ExtHouseService
{
    private ExtHouseManager $extHouseManager;
    private FormManager $formManager;
    private NormalizerInterface $normalizer;

    public function __construct(
        ExtHouseManager $extHouseManager,
        FormManager $formManager,
        NormalizerInterface $normalizer
    ) {
        $this->extHouseManager = $extHouseManager;
        $this->formManager = $formManager;
        $this->normalizer = $normalizer;
    }

    /**
     * @param class-string<FormTypeInterface> $className
     * @param class-string<ConstructFromArrayInterface> $dtoClassName
     */
    public function createForm(
        string $className,
        string $dtoClassName,
        ?ExtHouse $extHouse = null,
        array $options = []
    ): FormInterface {
        $data = ($extHouse !== null) ? $this->normalizer->normalize($extHouse) : [];
        if (!\is_array($data)) {
            throw new \LogicException('Не удалось нормализовать объект');
        }

        return $this->formManager->createForDto($className, $dtoClassName, $data, $options);
    }

    public function getOne(int $objectid): ?ExtHouse
    {
        return $this->extHouseManager->getOne($objectid);
    }

    /**
     * @return ExtHouse[]
     *
     * @psalm-return array<int, ExtHouse>
     */
    public function getAll(?int $limit = null, ?int $offset = null): array
    {
        return $this->extHouseManager->getAll($limit, $offset);
    }

    public function add(ExtHouseDTO $extHouseDto): ?int
    {
        $extHouse = $this->extHouseManager->add($extHouseDto);
        if ($extHouse === null) {
            return null;
        }

        return $extHouse->getObjectid();
    }

    public function updateById(
        int $objectid,
        ExtHouseDTO $extHouseDto
    ): bool {

        $extHouse = $this->extHouseManager->getOne($objectid);
        if ($extHouse === null) {
            return false;
        }

        return $this->update(
            $extHouse,
            $extHouseDto
        );
    }

    public function update(
        ExtHouse $extHouse,
        ExtHouseDTO $extHouseDto
    ): bool {
        $extHouse = $this->extHouseManager->update(
            $extHouse,
            $extHouseDto
        );
        return !($extHouse === null);
    }

    public function deleteById(int $objectid): bool
    {
        return $this->extHouseManager->deleteById($objectid);
    }
}
