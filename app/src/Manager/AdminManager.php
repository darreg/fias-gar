<?php

namespace App\Manager;

use App\DAO\AdminDAO;
use App\DTO\AdminDTO;
use App\Entity\Admin;
use App\Repository\AdminRepository;

class AdminManager
{
    private AdminDAO $AdminDao;
    private AdminRepository $AdminRepository;

    public function __construct(
        AdminDAO $AdminDao,
        AdminRepository $AdminRepository
    ) {
        $this->AdminDao = $AdminDao;
        $this->AdminRepository = $AdminRepository;
    }

    public function getOne(int $id): ?Admin
    {
        return $this->AdminRepository->find($id);
    }

    /**
     * @return Admin[]
     *
     * @psalm-return array<int, Admin>
     */
    public function getAll(?int $limit = null, ?int $offset = null): array
    {
        return $this->AdminRepository->findBy([], null, $limit, $offset);
    }

    public function add(AdminDTO $AdminDto): ?Admin
    {
        $Admin = (new Admin())
            ->setEmail($AdminDto->email)
            ->setName($AdminDto->name)
            ->setRoles($AdminDto->roles)
            ->setPassword($AdminDto->password)
            ->setStatus($AdminDto->status);

        try {
            $this->AdminDao->create($Admin);
        } catch (\Exception $e) {
            //TODO log
            return null;
        }

        return $Admin;
    }

    public function updateById(
        int $id,
        AdminDTO $AdminDto
    ): ?Admin {

        $Admin = $this->AdminRepository->find($id);
        if ($Admin === null) {
            return null;
        }

        return $this->update(
            $Admin,
            $AdminDto
        );
    }

    public function update(
        Admin $Admin,
        AdminDTO $AdminDto
    ): ?Admin {

        $Admin
            ->setEmail($AdminDto->email)
            ->setName($AdminDto->name)
            ->setRoles($AdminDto->roles)
            ->setPassword($AdminDto->password)
            ->setStatus($AdminDto->status);

        try {
            $this->AdminDao->update($Admin);
        } catch (\Exception $e) {
            //TODO log
            return null;
        }

        return $Admin;
    }

    public function deleteById(int $id): bool
    {
        $exHouse = $this->AdminRepository->find($id);
        if ($exHouse === null) {
            return false;
        }

        try {
            $this->AdminDao->delete($exHouse);
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
    }
}
