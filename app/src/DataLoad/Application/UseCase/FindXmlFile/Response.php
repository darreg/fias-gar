<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\FindXmlFile;

use App\DataLoad\Domain\XmlFile\Entity\XmlFile;
use App\Shared\Domain\Bus\Query\ResponseInterface;

/**
 * @psalm-suppress MissingConstructor
 */
class Response implements ResponseInterface
{
    /**
     * @var array<string, XmlFile>
     */
    private array $files;

    public function add(XmlFile $file): void
    {
        $this->files[md5($file->getPath())] = $file;
    }

    /**
     * @return array<string, XmlFile>
     */
    public function answer(): array
    {
        return $this->files;
    }
}
