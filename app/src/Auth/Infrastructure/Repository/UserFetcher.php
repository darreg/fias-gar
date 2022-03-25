<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Repository;

use App\Auth\Domain\Shared\ReadModel\AuthModel;
use App\Auth\Domain\User\Entity\Email;
use App\Auth\Domain\User\Repository\UserFetcherInterface;
use App\Auth\Infrastructure\Exception\UserNameNotFoundException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;
use JsonException;
use LogicException;

class UserFetcher implements UserFetcherInterface
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

        $result['roles'] = self::decodeRoles((string)$result['roles']);

        return AuthModel::fromArray($result);
    }

    /**
     * @throws DBALException
     */
    public function hasByEmail(Email $email): bool
    {
        return $this->connection->createQueryBuilder()
            ->select('COUNT(id)')
            ->from('users')
            ->andWhere('email = :email')
            ->setParameter('email', $email->getValue())
            ->executeQuery()
            ->fetchOne() > 0;
    }

    /** @throws LogicException */
    private static function decodeRoles(string $roles): array
    {
        try {
            /** @var array $result */
            return json_decode($roles, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new LogicException('Invalid roles json: ' . $e->getMessage());
        }
    }
}
