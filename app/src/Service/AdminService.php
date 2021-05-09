<?php

namespace App\Service;

use App\DTO\AdminDTO;
use App\DTO\AdminNewDTO;
use App\DTO\ConstructFromArrayInterface;
use App\Entity\Admin;
use App\Manager\AdminManager;
use App\Manager\FormManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AdminService
{
    private AdminManager $adminManager;
    private FormManager $formManager;
    private NormalizerInterface $normalizer;

    public function __construct(
        AdminManager $adminManager,
        FormManager $formManager,
        NormalizerInterface $normalizer
    ) {
        $this->adminManager = $adminManager;
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
        ?Admin $admin = null,
        array $options = []
    ): FormInterface {
        $data = ($admin !== null) ? $this->normalizer->normalize($admin) : [];
        if (!\is_array($data)) {
            throw new \LogicException('Не удалось нормализовать объект');
        }

        return $this->formManager->createForDto($className, $dtoClassName, $data, $options);
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

    public function add(AdminNewDTO $adminDto): ?int
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
