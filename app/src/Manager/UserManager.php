<?php

namespace App\Manager;

use App\DAO\UserDAO;
use App\DTO\UserDTO;
use App\DTO\UserNewDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    private UserDAO $userDao;
    private UserRepository $userRepository;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        UserDAO $userDao,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->userDao = $userDao;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function getOne(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function getOneByUsername(string $username): ?User
    {
        return $this->userRepository->findOneBy([User::USERNAME_FIELD => $username]);
    }

    /**
     * @return User[]
     *
     * @psalm-return array<int, User>
     */
    public function getAll(?int $limit = null, ?int $offset = null): array
    {
        return $this->userRepository->findBy([], null, $limit, $offset);
    }

    public function add(UserNewDTO $userDto): ?User
    {
        $user = (new User())
            ->setRoles($userDto->roles ?? [])
            ->setStatus($userDto->status ?? false);

        if ($userDto->email !== null) {
            $user->setEmail($userDto->email);
        }

        if ($userDto->name !== null) {
            $user->setName($userDto->name);
        }

        if (!empty($userDto->password)) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $userDto->password));
        }

        try {
            $this->userDao->create($user);
        } catch (\Exception $e) {
            //TODO log
            return null;
        }

        return $user;
    }

    public function updateById(
        int $id,
        UserDTO $userDto
    ): ?User {

        $user = $this->userRepository->find($id);
        if ($user === null) {
            return null;
        }

        return $this->update(
            $user,
            $userDto
        );
    }

    public function update(
        User $user,
        UserDTO $userDto
    ): ?User {

        $user
            ->setEmail($userDto->email)
            ->setName($userDto->name)
            ->setRoles($userDto->roles)
            ->setStatus($userDto->status);

        if (!empty($userDto->password)) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $userDto->password));
        }

        try {
            $this->userDao->update($user);
        } catch (\Exception $e) {
            //TODO log
            return null;
        }

        return $user;
    }

    public function deleteById(int $id): bool
    {
        $user = $this->userRepository->find($id);
        if ($user === null) {
            return false;
        }

        try {
            $this->userDao->delete($user);
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
    }
}
