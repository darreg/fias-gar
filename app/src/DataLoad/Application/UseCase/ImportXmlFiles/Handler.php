<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\ImportXmlFiles;

use App\DataLoad\Application\UseCase\FindXmlFile\Query as FindFileQuery;
use App\DataLoad\Application\UseCase\FindXmlFile\Response as FindFileResponse;
use App\DataLoad\Application\UseCase\SplitXmlFile\Command as SplitFileCommand;
use App\DataLoad\Domain\Import\Entity\Import;
use App\DataLoad\Domain\Import\Repository\ImportRepositoryInterface;
use App\DataLoad\Domain\Version\Entity\Version;
use App\Shared\Domain\Bus\Command\CommandBusInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Bus\Query\QueryBusInterface;

class Handler implements CommandHandlerInterface
{
    private CommandBusInterface $commandBus;
    private QueryBusInterface $queryBus;
    private ImportRepositoryInterface $importRepository;

    public function __construct(
        CommandBusInterface $commandBus,
        QueryBusInterface $queryBus,
        ImportRepositoryInterface $importRepository
    ) {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->importRepository = $importRepository;
    }

    public function __invoke(Command $command)
    {
        /** @var Version::TYPE_* $type */
        $type = $command->getType();
        $versionId = $command->getVersionId();
        $tokens = $command->getTokens();

        $this->restartImportIfExists($type, $versionId);

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

    private function restartImportIfExists(string $type, string $versionId): void
    {
        $import = $this->importRepository->find($type, $versionId);
        if ($import !== null) {
            $this->importRepository->remove($import);
        }
        $this->importRepository->persist(new Import($type, $versionId));
    }
}
