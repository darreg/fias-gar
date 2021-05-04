<?php

namespace App\Service;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Manager\UserManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserService
{
    private UserManager $userManager;
    private FormFactoryInterface $formFactory;
    private NormalizerInterface $normalizer;

    public function __construct(
        UserManager $userManager,
        FormFactoryInterface $formFactory,
        NormalizerInterface $normalizer
    ) {
        $this->userManager = $userManager;
        $this->formFactory = $formFactory;
        $this->normalizer = $normalizer;
    }

    /**
     * @param class-string<FormTypeInterface> $className
     */
    public function createForm(string $className, ?User $user = null): FormInterface
    {
        if ($user === null) {
            return $this->formFactory->create($className);
        }

        /** @var array<string, mixed> $userArray */
        $userArray = $this->normalizer->normalize($user);

        $userDto = UserDTO::fromArray($userArray);

        return $this->formFactory->create($className, $userDto);
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

    public function add(UserDTO $userDto): ?int
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
