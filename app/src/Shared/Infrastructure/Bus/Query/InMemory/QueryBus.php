<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Query\InMemory;

use App\Shared\Domain\Bus\Query\QueryBusInterface;
use App\Shared\Domain\Bus\Query\QueryInterface;
use App\Shared\Domain\Bus\Query\ResponseInterface;
use App\Shared\Infrastructure\Bus\ParameterTypeExtractor;
use App\Shared\Infrastructure\Bus\Query\QueryNotRegisteredException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class QueryBus implements QueryBusInterface
{
    private MessageBus $bus;

    public function __construct(iterable $queryHandlers)
    {
        $this->bus = new MessageBus(
            [
                new HandleMessageMiddleware(
                    new HandlersLocator(ParameterTypeExtractor::forCallables($queryHandlers))
                ),
            ]
        );
    }

    public function ask(QueryInterface $query): ?ResponseInterface
    {
        try {
            /** @var HandledStamp $stamp */
            $stamp = $this->bus->dispatch($query)->last(HandledStamp::class);

            return $stamp->getResult();
        } catch (NoHandlerForMessageException $e) {
            throw new QueryNotRegisteredException($query);
        }
    }
}
