<?php

namespace App\Manager;

use App\DAO\AdminDAO;
use App\DTO\AdminDTO;
use App\DTO\AdminNewDTO;
use App\Entity\Admin;
use App\Repository\AdminRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminManager
{
    private AdminDAO $adminDao;
    private AdminRepository $adminRepository;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        AdminDAO $adminDao,
        AdminRepository $adminRepository,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->adminDao = $adminDao;
        $this->adminRepository = $adminRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function getOne(int $id): ?Admin
    {
        return $this->adminRepository->find($id);
    }

    /**
     * @return Admin[]
     *
     * @psalm-return array<int, Admin>
     */
    public function getAll(?int $limit = null, ?int $offset = null): array
    {
        return $this->adminRepository->findBy([], null, $limit, $offset);
    }

    public function add(AdminNewDTO $adminDto): ?Admin
    {
        $admin = (new Admin())
            ->setEmail($adminDto->email)
            ->setName($adminDto->name)
            ->setRoles($adminDto->roles ?? [])
            ->setStatus($adminDto->status);

        $admin->setPassword($this->passwordEncoder->encodePassword($admin, $adminDto->password));

        try {
            $this->adminDao->create($admin);
        } catch (\Exception $e) {
            //TODO log
            return null;
        }

        return $admin;
    }

    public function updateById(
        int $id,
        AdminDTO $adminDto
    ): ?Admin {

        $admin = $this->adminRepository->find($id);
        if ($admin === null) {
            return null;
        }

        return $this->update(
            $admin,
            $adminDto
        );
    }

    public function update(
        Admin $admin,
        AdminDTO $adminDto
    ): ?Admin {

        $admin
            ->setEmail($adminDto->email)
            ->setName($adminDto->name)
            ->setRoles($adminDto->roles ?? [])
            ->setStatus($adminDto->status);

        if (!empty($adminDto->password)) {
            $admin->setPassword($this->passwordEncoder->encodePassword($admin, $adminDto->password));
        }

        try {
            $this->adminDao->update($admin);
        } catch (\Exception $e) {
            //TODO log
            return null;
        }

        return $admin;
    }

    public function deleteById(int $id): bool
    {
        $admin = $this->adminRepository->find($id);
        if ($admin === null) {
            return false;
        }

        try {
            $this->adminDao->delete($admin);
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
    }
}
