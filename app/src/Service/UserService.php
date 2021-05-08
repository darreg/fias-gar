<?php

namespace App\Service;

use App\DTO\ConstructFromArrayInterface;
use App\DTO\UserDTO;
use App\DTO\UserNewDTO;
use App\Entity\User;
use App\Manager\FormManager;
use App\Manager\UserManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserService
{
    private UserManager $userManager;
    private FormManager $formManager;
    private NormalizerInterface $normalizer;

    public function __construct(
        UserManager $userManager,
        FormManager $formManager,
        NormalizerInterface $normalizer
    ) {
        $this->userManager = $userManager;
        $this->formManager = $formManager;
        $this->normalizer = $normalizer;
    }

    /**
     * @param class-string<FormTypeInterface> $className
     * @param class-string<ConstructFromArrayInterface> $dtoClassName
     */
    public function createForm(string $className, string $dtoClassName, ?User $user = null): FormInterface
    {
        $data = [];
        if ($user !== null) {
            $data = $this->normalizer->normalize($user);
        }
        return $this->formManager->createForDto($className, $dtoClassName, $data);
    }

    public function getOne(int $id): ?User
    {
        return $this->userManager->getOne($id);
    }

    /**
     * @return User[]
     *
     * @psalm-return array<int, User>
     */
    public function getAll(?int $limit = null, ?int $offset = null): array
    {
        return $this->userManager->getAll($limit, $offset);
    }

    public function add(UserNewDTO $userDto): ?int
    {
        $user = $this->userManager->add($userDto);
        if ($user === null) {
            return null;
        }

        return $user->getId();
    }

    public function updateById(
        int $id,
        UserDTO $userDto
    ): bool {

        $user = $this->userManager->getOne($id);
        if ($user === null) {
            return false;
        }

        return $this->update(
            $user,
            $userDto
        );
    }

    public function update(
        User $user,
        UserDTO $userDto
    ): bool {
        $user = $this->userManager->update(
            $user,
            $userDto
        );
        return !($user === null);
    }

    public function deleteById(int $id): bool
    {
        return $this->userManager->deleteById($id);
    }
}
