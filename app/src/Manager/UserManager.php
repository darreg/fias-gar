<?php

namespace App\Manager;

use App\DAO\UserDAO;
use App\DTO\UserDTO;
use App\Entity\User;
use App\Repository\UserRepository;

class UserManager
{
    private UserDAO $UserDao;
    private UserRepository $UserRepository;

    public function __construct(
        UserDAO $UserDao,
        UserRepository $UserRepository
    ) {
        $this->UserDao = $UserDao;
        $this->UserRepository = $UserRepository;
    }

    public function getOne(int $id): ?User
    {
        return $this->UserRepository->find($id);
    }

    /**
     * @return User[]
     *
     * @psalm-return array<int, User>
     */
    public function getAll(?int $limit = null, ?int $offset = null): array
    {
        return $this->UserRepository->findBy([], null, $limit, $offset);
    }

    public function add(UserDTO $UserDto): ?User
    {
        $User = (new User())
            ->setEmail($UserDto->email)
            ->setName($UserDto->name)
            ->setRoles($UserDto->roles)
            ->setPassword($UserDto->password)
            ->setStatus($UserDto->status);

        try {
            $this->UserDao->create($User);
        } catch (\Exception $e) {
            //TODO log
            return null;
        }

        return $User;
    }

    public function updateById(
        int $id,
        UserDTO $UserDto
    ): ?User {

        $User = $this->UserRepository->find($id);
        if ($User === null) {
            return null;
        }

        return $this->update(
            $User,
            $UserDto
        );
    }

    public function update(
        User $User,
        UserDTO $UserDto
    ): ?User {

        $User
            ->setEmail($UserDto->email)
            ->setName($UserDto->name)
            ->setRoles($UserDto->roles)
            ->setPassword($UserDto->password)
            ->setStatus($UserDto->status);

        try {
            $this->UserDao->update($User);
        } catch (\Exception $e) {
            //TODO log
            return null;
        }

        return $User;
    }

    public function deleteById(int $id): bool
    {
        $exHouse = $this->UserRepository->find($id);
        if ($exHouse === null) {
            return false;
        }

        try {
            $this->UserDao->delete($exHouse);
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
    }
}
