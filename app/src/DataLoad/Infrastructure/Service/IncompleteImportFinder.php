<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\Import\Entity\Import;
use App\DataLoad\Domain\Import\Repository\ImportRepositoryInterface;
use App\DataLoad\Domain\Import\Service\IncompleteImportFinderInterface;

class IncompleteImportFinder implements IncompleteImportFinderInterface
{
    private ImportRepositoryInterface $importRepository;

    public function __construct(ImportRepositoryInterface $importRepository)
    {
        $this->importRepository = $importRepository;
    }

    /**
     * @return list<Import>
     */
    public function find(): array
    {
        $incompleteImports = [];
        $imports = $this->importRepository->findAll();
        foreach ($imports as $import) {
            if ($import->isFinished()) {
                continue;
            }
            if (
                (time() - $import->getUpdatedAt()->getTimestamp()) > IncompleteImportFinderInterface::EXPIRE_INTERVAL
            ) {
                continue;
            }
            $incompleteImports[] = $import;
        }

        return $incompleteImports;
    }

    public function check(): bool
    {
        $imports = $this->find();
        return \count($imports) > 0;
    }
}
