<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Query;

use App\Shared\Domain\Bus\Query\QueryBusInterface;
use App\Shared\Domain\Bus\Query\QueryInterface;
use App\Shared\Domain\Bus\Query\ResponseInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class QueryBus implements QueryBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    /**
     * @psalm-suppress MixedInferredReturnType
     */
    public function ask(QueryInterface $query): ?ResponseInterface
    {
        /**
         * @var ResponseInterface|null $result
         * @psalm-suppress MixedReturnStatement
         */
        return $this->handle($query);
    }
}
