<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\SaveTag;

use App\DataLoad\Domain\Import\Entity\Import;
use App\DataLoad\Domain\Import\Service\ImportCounterIncrementorInterface;
use App\DataLoad\Domain\Tag\Service\TagSaverInterface;
use App\DataLoad\Domain\Version\Entity\Version;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use Exception;
use Psr\Log\LoggerInterface;

final class Handler implements CommandHandlerInterface
{
    private TagSaverInterface $saver;
    private ImportCounterIncrementorInterface $incrementor;
    private LoggerInterface $saveErrorsLogger;

    public function __construct(
        TagSaverInterface $saver,
        ImportCounterIncrementorInterface $incrementor,
        LoggerInterface $saveErrorsLogger
    ) {
        $this->saver = $saver;
        $this->incrementor = $incrementor;
        $this->saveErrorsLogger = $saveErrorsLogger;
    }

    public function __invoke(Command $command): void
    {
        /** @var Version::TYPE_* $type */
        $type = $command->getType();
        $versionId = $command->getVersionId();
        $fileToken = $command->getFileToken();

        try {
            $this->saver->upsert($fileToken, $command->getValues());
            $this->incrementor->inc($type, $versionId, Import::COUNTER_FIELD_SAVE_SUCCESS_NUM);
        } catch (Exception $e) {
            $this->saveErrorsLogger->info(
                $versionId . ';' . $fileToken . ' ; ' . serialize($command->getValues()) . ' ; ' . $e->getMessage()
            );
            $this->incrementor->inc($type, $versionId, Import::COUNTER_FIELD_SAVE_ERROR_NUM);
        }
    }
}
