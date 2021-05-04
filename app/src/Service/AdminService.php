<?php

namespace App\Service;

use App\DTO\AdminDTO;
use App\Entity\Admin;
use App\Manager\AdminManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AdminService
{
    private AdminManager $adminManager;
    private FormFactoryInterface $formFactory;
    private NormalizerInterface $normalizer;

    public function __construct(
        AdminManager $adminManager,
        FormFactoryInterface $formFactory,
        NormalizerInterface $normalizer
    ) {
        $this->adminManager = $adminManager;
        $this->formFactory = $formFactory;
        $this->normalizer = $normalizer;
    }

    /**
     * @param class-string<FormTypeInterface> $className
     */
    public function createForm(string $className, ?Admin $admin = null): FormInterface
    {
        if ($admin === null) {
            return $this->formFactory->create($className);
        }

        /** @var array<string, mixed> $adminArray */
        $adminArray = $this->normalizer->normalize($admin);

        $adminDto = AdminDTO::fromArray($adminArray);

        return $this->formFactory->create($className, $adminDto);
    }

    public function getOne(int $id): ?Admin
    {
        return $this->adminManager->getOne($id);
    }

    /**
     * @return Admin[]
     *
     * @psalm-return array<int, Admin>
     */
    public function getAll(?int $limit = null, ?int $offset = null): array
    {
        return $this->adminManager->getAll($limit, $offset);
    }

    public function add(AdminDTO $adminDto): ?int
    {
        $admin = $this->adminManager->add($adminDto);
        if ($admin === null) {
            return null;
        }

        return $admin->getId();
    }

    public function updateById(
        int $id,
        AdminDTO $adminDto
    ): bool {

        $admin = $this->adminManager->getOne($id);
        if ($admin === null) {
            return false;
        }

        return $this->update(
            $admin,
            $adminDto
        );
    }

    public function update(
        Admin $admin,
        AdminDTO $adminDto
    ): bool {
        $admin = $this->adminManager->update(
            $admin,
            $adminDto
        );
        return !($admin === null);
    }

    public function deleteById(int $id): bool
    {
        return $this->adminManager->deleteById($id);
    }
}
