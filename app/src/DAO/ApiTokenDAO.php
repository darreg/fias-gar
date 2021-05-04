<?php

namespace App\DAO;

use App\Entity\ApiToken;
use Doctrine\ORM\EntityManagerInterface;

class ApiTokenDAO
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(ApiToken $ApiToken): ApiToken
    {
        $this->entityManager->persist($ApiToken);
        $this->entityManager->flush();

        return $ApiToken;
    }

    public function update(ApiToken $ApiToken): ApiToken
    {
        $this->entityManager->flush();

        return $ApiToken;
    }

    public function delete(ApiToken $ApiToken): void
    {
        $this->entityManager->remove($ApiToken);
        $this->entityManager->flush();
    }
}
