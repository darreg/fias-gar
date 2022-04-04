<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Repository;

use App\Auth\Domain\User\ReadModel\AuthModel;
use App\Auth\Domain\User\Repository\UserFetcherInterface;
use App\Auth\Infrastructure\Exception\UserNameNotFoundException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;
use JsonException;
use LogicException;

final class UserFetcher implements UserFetcherInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws DBALException
     * @throws UserNameNotFoundException
     * @throws LogicException
     */
    public function findForAuthByEmail(string $email): AuthModel
    {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'email',
                'password_hash',
                'status',
                'main_role',
                'roles'
            )
            ->from('users')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->executeQuery();

        $result = $queryBuilder->fetchAssociative();

        if (!$result) {
            throw new UserNameNotFoundException($email);
        }

        $result['roles'] = self::decodeRoles((string)$result['main_role'], (string)$result['roles']);

        return AuthModel::fromArray($result);
    }

    /**
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedInferredReturnType
     * @throws LogicException
     * @return string[]
     */
    private static function decodeRoles(string $mainRole, string $roleJson): array
    {
        try {
            /** @var string[] $roles */
            $roles = json_decode($roleJson, true, 512, JSON_THROW_ON_ERROR);
            $roles[] = $mainRole;
            return array_unique($roles);
        } catch (JsonException $e) {
            throw new LogicException('Invalid roles json: ' . $e->getMessage());
        }
    }
}
