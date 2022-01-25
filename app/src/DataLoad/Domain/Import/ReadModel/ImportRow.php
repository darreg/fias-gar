<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Import\ReadModel;

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
                (string)$data['type'],
                (string)$data['versionId'],
                (int)$data['taskNum'],
                (int)$data['parseErrorNum'],
                (int)$data['parseSuccessNum'],
                (int)$data['saveErrorNum'],
                (int)$data['saveSuccessNum'],
                (bool)$data['viewsRefreshed'],
                new DateTimeImmutable((string)$data['createdAt']),
                new DateTimeImmutable((string)$data['updatedAt'])
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
