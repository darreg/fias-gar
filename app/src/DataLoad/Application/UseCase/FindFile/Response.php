<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\FindFile;

use App\DataLoad\Domain\Entity\File;
use App\Shared\Domain\Bus\Query\ResponseInterface;

/**
 * @psalm-suppress MissingConstructor
 */
class Response implements ResponseInterface
{
    /**
     * @var array<string, File>
     */
    private array $files;

    public function add(File $file): void
    {
        $this->files[md5($file->getPath())] = $file;
    }

    /**
     * @return array<string, File>
     */
    public function getAll(): array
    {
        return $this->files;
    }
}
