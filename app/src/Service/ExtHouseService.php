<?php

namespace App\Service;

use App\DTO\ExtHouseDTO;
use App\Entity\ExtHouse;
use App\Manager\ExtHouseManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ExtHouseService
{
    private ExtHouseManager $extHouseManager;
    private FormFactoryInterface $formFactory;
    private NormalizerInterface $normalizer;

    public function __construct(
        ExtHouseManager $extHouseManager,
        FormFactoryInterface $formFactory,
        NormalizerInterface $normalizer
    ) {
        $this->extHouseManager = $extHouseManager;
        $this->formFactory = $formFactory;
        $this->normalizer = $normalizer;
    }

    /**
     * @param class-string<FormTypeInterface> $className
     */
    public function createForm(string $className, ?ExtHouse $extHouse = null): FormInterface
    {
        if ($extHouse === null) {
            return $this->formFactory->create($className);
        }

        /** @var array<string, mixed> $extHouseArray */
        $extHouseArray = $this->normalizer->normalize($extHouse);

        $extHouseDto = ExtHouseDTO::fromArray($extHouseArray);

        return $this->formFactory->create($className, $extHouseDto);
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
