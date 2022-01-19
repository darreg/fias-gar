<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\ImportXmlFiles;

use App\DataLoad\Application\UseCase\FindXmlFile\Query as FindFileQuery;
use App\DataLoad\Application\UseCase\FindXmlFile\Response as FindFileResponse;
use App\DataLoad\Application\UseCase\SplitXmlFile\Command as SplitFileCommand;
use App\DataLoad\Domain\Counter\Entity\Counter;
use App\DataLoad\Domain\Counter\Repository\CounterRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandBusInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Bus\Query\QueryBusInterface;

class Handler implements CommandHandlerInterface
{
    private CommandBusInterface $commandBus;
    private QueryBusInterface $queryBus;
    private CounterRepositoryInterface $counterRepository;

    public function __construct(
        CommandBusInterface $commandBus,
        QueryBusInterface $queryBus,
        CounterRepositoryInterface $counterRepository
    ) {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->counterRepository = $counterRepository;
    }

    public function __invoke(Command $command)
    {
        $type = $command->getType();
        $versionId = $command->getVersionId();
        $tokens = $command->getTokens();

        $this->restartCounterIfExists($type, $versionId);

        foreach ($tokens as $token) {
            /** @var FindFileResponse $response */
            $response = $this->queryBus->ask(new FindFileQuery($versionId, $token));
            foreach ($response->answer() as $file) {
                $this->commandBus->dispatch(
                    new SplitFileCommand($type, $versionId, $file->getPath(), $file->getToken(), $file->getTagName())
                );
            }
        }
    }

    private function restartCounterIfExists(string $type, string $versionId): void
    {
        $counter = $this->counterRepository->find(Counter::buildKey($type, $versionId));
        if ($counter !== null) {
            $this->counterRepository->remove($counter);
        }
    }
}
