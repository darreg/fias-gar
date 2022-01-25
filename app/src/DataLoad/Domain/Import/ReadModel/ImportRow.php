<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Import\ReadModel;

use App\DataLoad\Domain\Import\Entity\Import;
use App\Shared\Domain\Exception\ConstructFromArrayException;
use App\Shared\Infrastructure\ConstructFromArrayInterface;
use DateTimeImmutable;
use Exception;

class ImportRow implements ConstructFromArrayInterface
{
    public function __construct(
        public string $type,
        public string $versionId,
        public int $taskNum,
        public int $parseErrorNum,
        public int $parseSuccessNum,
        public int $saveErrorNum,
        public int $saveSuccessNum,
        public bool $viewsRefreshed,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ) {
    }

    /**
     * @throws ConstructFromArrayException
     */
    public static function fromArray(array $data): self
    {
        try {
            return new self(
                (string)$data[Import::FIELD_TYPE],
                (string)$data[Import::FIELD_VERSION_ID],
                (int)$data[Import::COUNTER_FIELD_TASK_NUM],
                (int)$data[Import::COUNTER_FIELD_PARSE_ERROR_NUM],
                (int)$data[Import::COUNTER_FIELD_PARSE_SUCCESS_NUM],
                (int)$data[Import::COUNTER_FIELD_SAVE_ERROR_NUM],
                (int)$data[Import::COUNTER_FIELD_SAVE_SUCCESS_NUM],
                (bool)$data[Import::FIELD_VIEWS_REFRESHED],
                new DateTimeImmutable((string)$data[Import::FIELD_CREATED_AT]),
                new DateTimeImmutable((string)$data[Import::FIELD_UPDATED_AT])
            );
        } catch (Exception $e) {
            throw new ConstructFromArrayException(
                'It is not possible to convert an array of data into a model ' . self::class,
                0,
                $e
            );
        }
    }
}
